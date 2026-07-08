<?php

namespace App\Services;

use App\Enums\Period;
use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Repositories\InstallmentRepository;
use App\Repositories\TransactionRepository;
use App\Traits\TransactionValidations;
use Carbon\Carbon;

class TransactionPlannedService extends BaseService
{
    use TransactionValidations;

    private DateService $dateService;

    private TransactionRepository $transactionRepository;
    private InstallmentRepository $installmentRepository;

    public function __construct()
    {
        $this->dateService = app(DateService::class);

        $this->transactionRepository = app(TransactionRepository::class);
        $this->installmentRepository = app(InstallmentRepository::class);
    }

    public function create(array $input)
    {
        try {
            \DB::beginTransaction();

            unset($input['processing_date']);

            $this->validateNewTransaction($input);

            $dates = $this->dateService->generateNextDates(Period::from($input['frequency']), Carbon::createFromFormat('Y-m-d', $input['period_start_date']), Carbon::createFromFormat('Y-m-d', $input['period_end_date']));
            unset($input['frequency']);
            unset($input['period_start_date']);
            unset($input['undefined_period']);
            unset($input['period_end_date']);

            $input['transaction_planned_id'] = $dates[0]->getTimestamp() . str_pad($input['source_wallet_id'], 3, "0", STR_PAD_LEFT) . str_pad($input['destination_wallet_id'], 3, "0", STR_PAD_LEFT);
            foreach ($dates as $transaction_date) {
                $input['transaction_date'] = $transaction_date;

                $transaction = $this->transactionRepository->create($input);

                if (isset($input['installments'])) 
                    $this->installmentRepository->insertMultiples($transaction->id, $input['installments']);
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

    public function listLastTransactionsPlannedCreated()
    {
        try {
            return $this->transactionRepository->listLastTransactionsToProcessingCreated(20);
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }

        return [];
    }

    public function findByTransactionPlannedId(int $transactionPlannedId)
    {
        try {
            return $this->transactionRepository->findByTransactionPlannedId($transactionPlannedId);
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }

        return [];
    }

    public function approveTransactionPlanned(int $id)
    {
        try {
            \DB::beginTransaction();

            $this->transactionRepository->update($id, ['processing_date' => Carbon::now()]);

            \DB::commit();
        } catch (BaseException $exception) {
            \DB::rollBack();
            throw $exception;
        } catch (\Throwable $th) {
            \DB::rollBack();
            throw new ServiceException();
        }
    }

    public function delete(?int $id = null, ?int $transactionPlannedId = null)
    {
        try {
            if ($id) {
                $this->transactionRepository->delete($id);
            } else {
                $this->transactionRepository->removeByTransactionPlannedId($transactionPlannedId);
            }
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }
}
