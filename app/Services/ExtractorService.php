<?php

namespace App\Services;

use App\Enums\ExtentionFile;
use App\Enums\PaymentType;
use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Models\TransactionBase;
use App\Repositories\ExtractImportRepository;
use App\Repositories\TransactionBaseRepository;
use App\Services\ExtractModule\BaseModule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class ExtractorService
{
    private const MODULES_NAMESPACE = 'Services/ExtractModule';
    private const MODULES_PATH = 'App\\Services\\ExtractModule\\';

    protected static Collection $modules;
    private TransactionBase $transactionBaseIn;
    private TransactionBase $transactionBaseOut;

    private TransactionService $transactionService;
    private ExtractImportRepository $extractImportRepository;
    private TransactionBaseRepository $transactionBaseRepository;
    protected BaseModule $module;

    public static function loadModules()
    {
        self::$modules = collect(File::allFiles(app_path(self::MODULES_NAMESPACE)))
            ->map(function ($file, $index) {
                $class = self::MODULES_PATH . $file->getFilenameWithoutExtension();

                if (class_exists($class) && is_subclass_of($class, BaseModule::class)) {
                    return (object)[
                        'id'    => $index + 1,
                        'name'  => app($class)->toString(),
                        'class' => $class,
                    ];
                }

                return null;
            })
            ->filter();
    }

    public function __construct()
    {
        $this->transactionService = app(TransactionService::class);
        $this->extractImportRepository = app(ExtractImportRepository::class);
        $this->transactionBaseRepository = app(TransactionBaseRepository::class);
    }

    public function getModules(): Collection
    {
        return self::$modules;
    }

    public function configure(int $id, int $transactionBaseIdIn, int $transactionBaseIdOut)
    {
        $this->module = app(self::$modules->where('id', $id)->first()->class);
        $this->transactionBaseIn = $this->transactionBaseRepository->find($transactionBaseIdIn);
        $this->transactionBaseOut = $this->transactionBaseRepository->find($transactionBaseIdOut);

        return $this;
    }

    public function extract(UploadedFile $file)
    {
        try {
            switch ($this->module->getFormat()->value) {
                case ExtentionFile::CSV->value:
                    if ($file->extension() != ExtentionFile::CSV->value) 
                        throw new ServiceException("The file extension is not compatible with the module.");

                    $this->extractCSV($file);
                    break;
                
                default:
                    throw new ServiceException("The data format (or source) is not compatible.");
                    break;
            }
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    private function extractCSV(UploadedFile $file)
    {
        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            if ($this->module->hasHeaderOnFile()) 
                fgetcsv($handle, 1000, ',');

            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (empty($row)) 
                    continue;

                $transactionData = $this->module->defineModelData(
                    $file->getClientOriginalName(), 
                    $row,
                    $this->transactionBaseIn,
                    $this->transactionBaseOut,
                );

                if (count($transactionData) > 0) 
                    $this->extractImportRepository->create($transactionData);
            }
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

    public function listExtractImport()
    {
        $transactionsToImport = new Collection();

        try {
            $transactionsToImport = $this->extractImportRepository->list();
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

            $extractImport = $this->extractImportRepository->find($id, ['paymentMethod']);
            if ($extractImport->paymentMethod->type == PaymentType::Credit && ($attributes['installment_total'] == 1 || $attributes['installment_number'] != 1)) 
                unset($attributes['gross_value']);

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
                $transaction = $extractImport->convertToTransactionData();
                $this->transactionService->create($transaction);
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
