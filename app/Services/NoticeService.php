<?php

namespace App\Services;

use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Repositories\NoticeRepository;
use App\Repositories\TransactionRepository;
use App\Services\BaseService;
use Carbon\Carbon;

class NoticeService extends BaseService
{
    private TransactionRepository $transactionRepository;

    public function __construct()
    {
        $this->repository = app(NoticeRepository::class);
        $this->transactionRepository = app(TransactionRepository::class);
    }

    public function listLastNotices(int $limit = 5, bool $onlyUnread = true)
    {
        try {
            return $this->repository->listLastNotices($limit, $onlyUnread);
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    public function listNotices()
    {
        try {
            return $this->repository->listLastNotices(999, false);
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    public function read(int $id, bool $read = true)
    {
        try {
            return $this->repository->update($id, ['read' => $read]);
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    public function generateNoticeByTransactionsPlannedForToday()
    {
        try {
            $transactionsPlanned = $this->transactionRepository->listTransactionsToProcessing(Carbon::now()->startOfDay()->addDays(5), Carbon::now()->endOfDay()->addDays(5));

            foreach ($transactionsPlanned as $transactionPlanned) {
                $this->repository->create([
                    'title' => __("Transaction Planned Entry") . ' - ' . $transactionPlanned->transaction_date->format('d/m'),
                    'description' => __("Notice regarding transaction ':transaction_title', scheduled for ':transaction_date'.", ['transaction_title' => $transactionPlanned->title, 'transaction_date' => $transactionPlanned->transaction_date->format('d/m/Y')]),
                    'link' => route('transaction.edit', ['id' => $transactionPlanned->id]),
                    'important' => true,
                    'read' => false,
                ]);
            }
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }
}
