<?php

namespace App\Services\ExtractImport;

use App\Exceptions\ServiceException;
use App\Models\ExtractModule;
use App\Models\Transaction;
use App\Repositories\ExtractModuleRepository;
use Illuminate\Http\UploadedFile;

abstract class ExtratorCSVService
{
    protected bool $hasHeaderOnFile;
    private ExtractModuleRepository $extractModuleRepository;

    abstract protected function defineModelData(ExtractModule $extractModule, array $attributes): array;

    public function __construct(bool $hasHeaderOnFile = true)
    {
        $this->hasHeaderOnFile = $hasHeaderOnFile;
        $this->extractModuleRepository = app(ExtractModuleRepository::class);
    }

    public function process(int $module_id, UploadedFile $file)
    {
        try {
            $extractModule = $this->extractModuleRepository->find($module_id, ['transactionBaseIn', 'transactionBaseOut']);
            
            if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
                if ($this->hasHeaderOnFile) 
                    fgetcsv($handle, 1000, ',');

                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    if (empty($row)) 
                        continue;

                    $transactions[] = $this->defineModelData($extractModule, $row);
                }
                fclose($handle);
            }

            if (empty($transactions)) 
                exit;

            Transaction::insert($transactions);
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }
}
