<?php

namespace App\Http\Controllers;

use App\Services\NoticeService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const DEFAULT_CONTROLLER_ERROR = 'An error occurred while performing the action. Please try again or contact support.';

    private NoticeService $noticeService;

    public array $top_bar_data = [];

    public function __construct()
    {
        $this->noticeService = app(NoticeService::class);

        $this->getTopBarData();
    }

    public function getTopBarData()
    {
        $this->top_bar_data = [
            'total_notices' => $this->noticeService->listNotices()->count(),
            'last_notices' => $this->noticeService->listLastNotices(),
        ];
    }
}
