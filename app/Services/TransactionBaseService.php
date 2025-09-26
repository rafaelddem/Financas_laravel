<?php

namespace App\Services;

use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Repositories\CardRepository;
use App\Repositories\TransactionBaseRepository;

class TransactionBaseService extends BaseService
{
    private CardRepository $cardRepository;

    public function __construct()
    {
        $this->repository = app(TransactionBaseRepository::class);
        $this->cardRepository = app(CardRepository::class);
    }

    public function create(array $input)
    {
        try {
            if (isset($input['card_id'])) {
                $card = $this->cardRepository->find($input['card_id']);

                if ($input['source_wallet_id'] != $card->wallet_id) {
                    throw new ServiceException(__('The selected Card must belong to the Source Wallet.'));
                }
            }

            return $this->repository->create($input);
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }
}
