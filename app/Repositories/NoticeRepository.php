<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use App\Models\Notice;

class NoticeRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Notice::class);
    }

    public function listLastNotices(int $limit = 5, bool $onlyUnread = true)
    {
        try {
            return $this->model
                ->when($onlyUnread, function ($query) {
                    $query->where('read', false);
                })
                ->orderBy('read')
                ->orderBy('important', 'desc')
                ->limit($limit)
                ->get();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }
}
