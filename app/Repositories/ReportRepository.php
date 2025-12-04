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

    public function calculateIncomeUntilDate(Carbon $limitDate)
    {
        try {
            return \DB::select('CALL calculate_income_until_date(?, ?)', [env('MY_OWNER_ID'), $limitDate])[0]->total;
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function calculateIncomeByPeriod(Carbon $startDate, Carbon $limitDate)
    {
        try {
            return \DB::select('CALL calculate_income_by_period(?, ?, ?)', [env('MY_OWNER_ID'), $startDate, $limitDate]);
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
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
            return \DB::select('CALL list_loans_transactions_by_owner(?, ?, ?)', [$ownerId, $startDate, $endDate]);
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }
}
