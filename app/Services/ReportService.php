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

    public function myIncome(Carbon $startDate, Carbon $endDate)
    {
        try {
            $wallets = $this->repository->calculateAllWalletsValue($startDate, $endDate);

            $total = 0;
            foreach ($wallets as $wallet) {
                if($wallet->owner_id == env('MY_OWNER_ID')) {
                    $total += (int) ($wallet->value * 100);
                }
            }

            return $total / 100;
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    public function futureInvoiceAmounts()
    {
        try {
            return $this->transactionRepository->futureInvoiceAmounts();
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    public function totalLoans(?Carbon $startDate = null, ?Carbon $endDate = null)
    {
        try {
            $totalLoans = [
                'negative' => 0,
                'positive' => 0,
            ];

            $loans = $this->repository->calculateLoansByOwner($startDate, $endDate);
            foreach ($loans as $owner) {
                if (!in_array($owner->id, [env('SYSTEM_ID'), env('MY_OWNER_ID')])) {
                    if ($owner->gross_value > 0) {
                        $totalLoans['positive'] += $owner->gross_value;
                    } else {
                        $totalLoans['negative'] += ($owner->gross_value * -1);
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

    public function totalByWallet(Carbon $startDate, Carbon $endDate)
    {
        $walletsData = [
            'label' => [],
            'value' => [[]],
        ];

        try {
            $wallets = $this->repository->calculateAllWalletsValue($startDate, $endDate);
            foreach ($wallets as $wallet) {
                if ($wallet->owner_id == env('MY_OWNER_ID')) {
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
        $loans = [
            'label' => ['Emprestimos'],
            'value' => [],
        ];

        try {
            $owners = $this->repository->calculateLoansByOwner($startDate, $endDate);

            foreach ($owners as $owner) {
                if (!in_array($owner->id, [env('SYSTEM_ID'), env('MY_OWNER_ID')]) && (float) $owner->gross_value != 0) {
                    $loans['value'][$owner->name] = [$owner->gross_value];
                }
            }

            return $loans;
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }

        return $loans;
    }

    public function ownerLoansTransactions(int $ownerId, Carbon $startDate, Carbon $endDate)
    {
        try {
            $ownerLoans['fromPeriod'] = [];
            $transactions = $this->repository->listLoansTransactionsByOwner($ownerId, $startDate, $endDate);
            foreach ($transactions as $transaction) {
                $date = Carbon::createFromFormat('Y-m-d', $transaction->date);
                $month = $date->format('m/Y');
                $ownerLoans['fromPeriod'][$month][] = [
                    'transactions_id' => $transaction->transactions_id,
                    'transactions_title' => $transaction->transactions_title,
                    'source_owner_id' => $transaction->source_owner_id,
                    'source_owner_name' => $transaction->source_owner_name,
                    'destination_owner_id' => $transaction->destination_owner_id,
                    'destination_owner_name' => $transaction->destination_owner_name,
                    'payment_methods_id' => $transaction->payment_methods_id,
                    'payment_methods_type' => $transaction->payment_methods_type,
                    'date' => $date,
                    'net_value' => $transaction->gross_value,
                ];
            }

            $ownerLoans['beforePeriod'] = 0;
            $loans = $this->repository->calculateLoansByOwner(null, $startDate->clone()->subDay());
            foreach ($loans as $owner) {
                if ($owner->id == $ownerId) {
                    $ownerLoans['beforePeriod'] = $owner->gross_value;
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
}