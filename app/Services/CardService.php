<?php

namespace App\Services;

use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Repositories\CardRepository;

class CardService extends BaseService
{
    public function __construct()
    {
        $this->repository = app(CardRepository::class);
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
