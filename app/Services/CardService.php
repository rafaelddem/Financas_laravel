<?php

namespace App\Services;

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

    public function create(array $input)
    {
        try {
            \DB::beginTransaction();

            $card = $this->repository->create($input);

            if ($card->card_type == 'credit') {
                $end_date = now()->endOfDay()->setDay($card->first_day_month)->subDay();
                if (Carbon::now()->greaterThan($end_date)) {
                    $end_date->addMonth();
                }
    
                $this->invoiceRepository->create([
                    'card_id' => $card->id,
                    'start_date' => now()->startOfDay(),
                    'end_date' => $end_date,
                    'due_date' => $end_date->clone()->addDays($card->days_to_expiration),
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
}
