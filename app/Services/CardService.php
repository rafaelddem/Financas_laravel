<?php

namespace App\Services;

use App\Enums\PaymentType;
use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Repositories\CardRepository;
use App\Repositories\InvoiceRepository;
use Carbon\Carbon;

class CardService extends BaseService
{
    private InvoiceRepository $invoiceRepository;

    public function __construct()
    {
        $this->repository = app(CardRepository::class);
        $this->invoiceRepository = app(InvoiceRepository::class);
    }

    public static function calculateStartDate(int $first_day_month): Carbon
    {
        $startDate = now()->startOfDay()->setDay($first_day_month);
        if ($startDate->greaterThan(Carbon::now())) {
            $startDate->subMonth();
        }

        return $startDate;
    }

    public function create(array $input)
    {
        try {
            \DB::beginTransaction();

            $card = $this->repository->create($input);

            if ($card->card_type == 'credit') {
                $startDate = self::calculateStartDate($card->first_day_month);
                $endDate = $startDate->clone()->addMonth()->subDay()->endOfDay();
                $this->invoiceRepository->create([
                    'card_id' => $card->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'due_date' => $endDate->clone()->addDays($card->days_to_expiration),
                ]);
            }

            \DB::commit();
            return $card;
        } catch (BaseException $exception) {
            \DB::rollBack();
            throw $exception;
        } catch (\Throwable $th) {
            \DB::rollBack();
            throw new ServiceException();
        }
    }

    public function update(int $id, array $input)
    {
        try {
            $card = $this->repository->find($id);

            if (!$card->active) 
                throw new ServiceException('It is not allowed to update an intive Card.');

            $wallet = $this->repository->update($id, $input);

            return $wallet;
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    public function listDebit(int $wallet_id)
    {
        try {
            return $this->repository->listDebitCredit($wallet_id, PaymentType::Debit->value);
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }

        return [];
    }

    public function listCredit(int $wallet_id)
    {
        try {
            return $this->repository->listDebitCredit($wallet_id, PaymentType::Credit->value);
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }

        return [];
    }
}
