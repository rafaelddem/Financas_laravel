<?php

namespace App\Services;

use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Repositories\NoticeRepository;
use App\Services\BaseService;

class NoticeService extends BaseService
{
    public function __construct()
    {
        $this->repository = app(NoticeRepository::class);
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

    public function read(int $id, bool $read = false)
    {
        try {
            return $this->repository->update($id, ['read' => $read]);
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }
}
