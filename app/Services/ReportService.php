<?php

namespace App\Services;

use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Repositories\ReportRepository;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class ReportService extends BaseService
{
    private TransactionRepository $transactionRepository;

    public function __construct()
    {
        $this->repository = app(ReportRepository::class);
        $this->transactionRepository = app(TransactionRepository::class);
    }

    public function futureCreditValue()
    {
        try {
            return $this->transactionRepository->futureCreditValue();
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    public function totalByWallet(Carbon $startDate, Carbon $endDate)
    {
        $walletsData = [
            'label' => [],
            'value' => [[]],
        ];

        try {
            $wallets = $this->repository->calculateAllWalletsValue($startDate, $endDate);
            foreach ($wallets as $wallet) {
                if ($wallet->mine) {
                    array_push($walletsData['label'], $wallet->name);
                    array_push($walletsData['value'][0], $wallet->value);
                }
            }
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }

        return $walletsData;
    }

    public function loans(Carbon $startDate, Carbon $endDate)
    {
        $other_wallets = [
            'label' => ['Emprestimos'],
            'value' => [],
        ];

        try {
            $owners = $this->repository->calculateLoansByOwner($startDate, $endDate);

            foreach ($owners as $owner) {
                if (!in_array($owner->id, [env('SYSTEM_ID'), env('MY_OWNER_ID')]) && (float) $owner->value != 0) {
                    $other_wallets['value'][$owner->name] = [$owner->value];
                }
            }

            return $other_wallets;
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }

        return $other_wallets;
    }

    public function calculateTotalWalletsValue(Carbon $startDate, Carbon $endDate)
    {
        $wallets = $this->repository->calculateAllWalletsValue($startDate, $endDate);

        $total = 0;
        foreach ($wallets as $wallet) {
            if($wallet->mine) {
                $total += (int) ($wallet->value * 100);
            }
        }

        return $total / 100;
    }

    public function ownerLoansTransactions(int $ownerId, Carbon $startDate, Carbon $endDate)
    {
        try {
            $ownerLoans['fromPeriod'] = $this->transactionRepository->ownerLoansTransactions($ownerId, $startDate, $endDate)->reduce(function ($period, $transaction) {
                $month = $transaction->processing_date->format('m/Y');
                $period[$month][] = $transaction;
                return $period;
            }, []);

            $ownerLoans['beforePeriod'] = 0;
            $loans = $this->repository->calculateLoansByOwner(null, $startDate->clone()->subDay());
            foreach ($loans as $owner) {
                if ($owner->id == $ownerId) {
                    $ownerLoans['beforePeriod'] = $owner->value;
                }
            }

            return $ownerLoans;
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }

        return new Collection();
    }

    public function totalLoans()
    {
        try {
            $totalLoans = [
                'negative' => 0,
                'positive' => 0,
            ];

            $loans = $this->repository->calculateLoansByOwner();
            foreach ($loans as $owner) {
                if (!in_array($owner->id, [env('SYSTEM_ID'), env('MY_OWNER_ID')])) {
                    if ($owner->value > 0) {
                        $totalLoans['positive'] += $owner->value;
                    } else {
                        $totalLoans['negative'] += ($owner->value * -1);
                    }
                }
            }

            return $totalLoans;
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }

        return new Collection();
    }
}