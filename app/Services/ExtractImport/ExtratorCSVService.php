<?php

namespace App\Services\ExtractImport;

use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Models\ExtractModule;
use App\Models\Transaction;
use App\Repositories\ExtractImportRepository;
use App\Repositories\ExtractModuleRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

abstract class ExtratorCSVService
{
    protected bool $hasHeaderOnFile;
    private ExtractImportRepository $extractImportRepository;
    private ExtractModuleRepository $extractModuleRepository;

    abstract protected function defineModelData(ExtractModule $extractModule, string $fileName, array $attributes): array;

    public function __construct(bool $hasHeaderOnFile = true)
    {
        $this->hasHeaderOnFile = $hasHeaderOnFile;
        $this->extractImportRepository = app(ExtractImportRepository::class);
        $this->extractModuleRepository = app(ExtractModuleRepository::class);
    }

    public function extract(UploadedFile $file, int $moduleId)
    {
        try {
            $extractModule = $this->extractModuleRepository->find($moduleId);

            if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
                if ($this->hasHeaderOnFile) 
                    fgetcsv($handle, 1000, ',');

                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    if (empty($row)) 
                        continue;

                    $this->extractImportRepository->create($this->defineModelData($extractModule, $file->getClientOriginalName(), $row));
                }
                fclose($handle);
            }
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    public function listReadyFiles()
    {
        try {
            return $this->extractImportRepository->listReadyFiles();
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    public function listExtractImportAsTransaction()
    {
        $transactionsToImport = new Collection();

        try {
            $extractImports = $this->extractImportRepository->list()->toArray();
            $transactionsToImport = Transaction::hydrate($extractImports);
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }

        return $transactionsToImport;
    }

    public function update(int $id, array $attributes)
    {
        try {
            $attributes['ready'] = true;
            $extractImport = $this->extractImportRepository->update($id, $attributes);

            return $extractImport;
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    public function delete(int $id)
    {
        try {
            $this->extractImportRepository->delete($id);
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    public function import(string $file_name)
    {
        try {
            \DB::beginTransaction();

            $result = $this->extractImportRepository->listReadyFiles($file_name);

            if ($result->count() < 1) 
                throw new ServiceException(__('File Not Found'));

            if ($result->first()->transactions_left > 0) 
                throw new ServiceException(trans_choice('Files Left', $result->first()->transactions_left, ['count' => $result->first()->transactions_left]));

            $extractImports = $this->extractImportRepository->list(false, $file_name);
            foreach ($extractImports as $extractImport) {
                $extractImport->convertToTransaction()->save();
                $extractImport->delete();
            }

            \DB::commit();
        } catch (BaseException $exception) {
            \DB::rollBack();
            throw $exception;
        } catch (\Throwable $th) {
            \DB::rollBack();
            throw new ServiceException();
        }
    }
}
