<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const DEFAULT_CONTROLLER_ERROR = 'An error occurred while performing the action. Please try again or contact support.';

    public array $top_bar_data = [];

    public function __construct()
    {
        $this->getTopBarData();
    }

    public function getTopBarData()
    {
        $this->top_bar_data = [];
    }
}
