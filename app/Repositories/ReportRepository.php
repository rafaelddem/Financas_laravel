<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use App\Models\NullModel;
use Carbon\Carbon;

class ReportRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(NullModel::class);
    }

    public function calculateAllWalletsValue(Carbon $startDate, Carbon $endDate)
    {
        try {
            return \DB::select('CALL calculate_all_wallets_value(?, ?)', [$startDate, $endDate]);
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function calculateLoansByOwner(?Carbon $startDate = null, ?Carbon $endDate = null)
    {
        try {
            return \DB::select('CALL calculate_loans_by_owner(?, ?)', [$startDate, $endDate]);
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function listLoansTransactionsByOwner(int $ownerId, ?Carbon $startDate = null, ?Carbon $endDate = null)
    {
        try {
            return \DB::select('CALL list_loans_transactions_by_owner(?, ?, ?, ?)', [env('SYSTEM_ID'), $ownerId, $startDate, $endDate]);
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }
}
