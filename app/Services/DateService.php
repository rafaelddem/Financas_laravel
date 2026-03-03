<?php

namespace App\Services;

use App\Enums\Period;
use Carbon\Carbon;

class DateService extends BaseService
{
    public function extractFilterDateFromRequest(Period $period = Period::DEFAULT, ?string $startDate = null, ?string $endDate = null)
    {
        if ($startDate AND $endDate) {
            return [
                Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay(),
                Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay(),
            ];
        }

        switch ($period) {
            case Period::TODAY:
                return [
                    Carbon::now()->startOfDay(),
                    Carbon::now()->endOfDay(),
                ];
                break;

            case Period::LAST_7_DAYS:
                return [
                    Carbon::now()->startOfDay()->subDays(6),
                    Carbon::now()->endOfDay(),
                ];
                break;

            case Period::LAST_14_DAYS:
                return [
                    Carbon::now()->startOfDay()->subDays(13),
                    Carbon::now()->endOfDay(),
                ];
                break;

            case Period::LAST_30_DAYS:
                return [
                    Carbon::now()->startOfDay()->subMonth()->addDay(),
                    Carbon::now()->endOfDay(),
                ];
                break;

            case Period::LAST_90_DAYS:
                return [
                    Carbon::now()->startOfDay()->subMonths(3)->addDay(),
                    Carbon::now()->endOfDay(),
                ];
                break;

            case Period::LAST_180_DAYS:
                return [
                    Carbon::now()->startOfDay()->subMonths(6)->addDay(),
                    Carbon::now()->endOfDay(),
                ];
                break;

            case Period::LAST_365_DAYS:
                return [
                    Carbon::now()->startOfDay()->subYear()->addDay(),
                    Carbon::now()->endOfDay(),
                ];
                break;

            case Period::LAST_730_DAYS:
                return [
                    Carbon::now()->startOfDay()->subYears(2)->addDay(),
                    Carbon::now()->endOfDay(),
                ];
                break;

            case Period::LAST_1095_DAYS:
                return [
                    Carbon::now()->startOfDay()->subYears(3)->addDay(),
                    Carbon::now()->endOfDay(),
                ];
                break;

            case Period::LAST_MONTH:
                return [
                    Carbon::now()->startOfMonth()->startOfDay(),
                    Carbon::now()->endOfMonth()->endOfDay(),
                ];
                break;

            case Period::LAST_3_MONTHS:
                return [
                    Carbon::now()->startOfMonth()->startOfDay()->subMonths(2),
                    Carbon::now()->endOfMonth()->endOfDay(),
                ];
                break;

            case Period::LAST_6_MONTHS:
                return [
                    Carbon::now()->startOfMonth()->startOfDay()->subMonths(5),
                    Carbon::now()->endOfMonth()->endOfDay(),
                ];
                break;

            case Period::LAST_YEAR:
                return [
                    Carbon::now()->startOfMonth()->startOfDay()->subMonths(11),
                    Carbon::now()->endOfMonth()->endOfDay(),
                ];

            case Period::LAST_2_YEARS:
                return [
                    Carbon::now()->startOfMonth()->startOfDay()->subMonths(23),
                    Carbon::now()->endOfMonth()->endOfDay(),
                ];

            case Period::LAST_3_YEARS:
                return [
                    Carbon::now()->startOfMonth()->startOfDay()->subMonths(35),
                    Carbon::now()->endOfMonth()->endOfDay(),
                ];

            default:
                return [
                    Carbon::now()->startOfMonth()->startOfDay(),
                    Carbon::now()->endOfMonth()->endOfDay(),
                ];

                break;
        }
    }
}