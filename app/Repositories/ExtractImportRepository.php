<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use App\Models\ExtractImport;
use Illuminate\Support\Facades\DB;

class ExtractImportRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(ExtractImport::class);
    }

    public function list(bool $onlyNotReady = true, string $fileName = '')
    {
        try {
            return $this->model
                ->when($onlyNotReady, function ($query) {
                    $query->where('ready', false);
                })
                ->when($fileName, function ($query) use ($fileName) {
                    $query->where('file_name', $fileName);
                })
                ->get();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function listReadyFiles(string $fileName = '')
    {
        try {
            return $this->model::select([
                    'file_name',
                    DB::raw('SUM(CASE WHEN ready = FALSE THEN 1 ELSE 0 END) AS transactions_left')
                ])
                ->when($fileName, function ($query) use ($fileName) {
                    $query->where('file_name', $fileName);
                })
                ->groupBy('file_name')
                ->get();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function insertMultiples(array $attributes)
    {
        try {
            return $this->model::insert($attributes);
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function listByFile(string $fileName)
    {
        try {
            return $this->model
                ->where('file_name', $fileName)
                ->get();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }
}
