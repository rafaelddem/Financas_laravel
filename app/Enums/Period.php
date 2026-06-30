<?php

namespace App\Enums;

use Carbon\Carbon;

enum Period: string {
    case TODAY = 'today';
    case THIS_MONTH = 'this_month';
    case THIS_YEAR = 'this_year';

    case LAST_7_DAYS = 'last_7_days';
    case LAST_14_DAYS = 'last_14_days';
    case LAST_30_DAYS = 'last_30_days';
    case LAST_90_DAYS = 'last_90_days';
    case LAST_180_DAYS = 'last_180_days';
    case LAST_365_DAYS = 'last_365_days';
    case LAST_730_DAYS = 'last_730_days';
    case LAST_1095_DAYS = 'last_1095_days';

    case LAST_MONTH = 'last_month';
    case LAST_3_MONTHS = 'last_3_months';
    case LAST_6_MONTHS = 'last_6_months';
    case LAST_YEAR = 'last_year';
    case LAST_2_YEARS = 'last_2_years';
    case LAST_3_YEARS = 'last_3_years';
    case LAST_10_YEARS = 'last_10_years';

    case NEXT_7_DAYS = 'next_7_days';
    case NEXT_14_DAYS = 'next_14_days';
    case NEXT_30_DAYS = 'next_30_days';
    case NEXT_90_DAYS = 'next_90_days';
    case NEXT_180_DAYS = 'next_180_days';
    case NEXT_365_DAYS = 'next_365_days';
    case NEXT_730_DAYS = 'next_730_days';
    case NEXT_1095_DAYS = 'next_1095_days';

    case NEXT_MONTH = 'next_month';
    case NEXT_3_MONTHS = 'next_3_months';
    case NEXT_6_MONTHS = 'next_6_months';
    case NEXT_YEAR = 'next_year';
    case NEXT_2_YEARS = 'next_2_years';
    case NEXT_3_YEARS = 'next_3_years';

    case ONCE = 'once';
    case EVERY_DAY = 'every_day';
    case EVERY_WEEK = 'every_week';
    case EVERY_2_WEEKS = 'every_2_weeks';
    case EVERY_MONTH = 'every_month';
    case EVERY_2_MONTHS = 'every_2_months';
    case EVERY_3_MONTHS = 'every_3_months';
    case EVERY_4_MONTHS = 'every_4_months';
    case EVERY_6_MONTHS = 'every_6_months';
    case EVERY_YEAR = 'every_year';

    public function getDateLimits(?Carbon $referenceDate = null)
    {
        $referenceDate = $referenceDate ?? Carbon::now();

        return match($this) {
            self::TODAY =>              [$referenceDate->clone()->startOfDay(),                                     $referenceDate->clone()->endOfDay()],
            self::THIS_MONTH =>         [$referenceDate->clone()->startOfMonth()->startOfDay(),                     $referenceDate->clone()->endOfMonth()->endOfDay()],
            self::THIS_YEAR =>          [$referenceDate->clone()->startOfYear()->startOfDay(),                      $referenceDate->clone()->endOfYear()->endOfDay()],

            self::LAST_7_DAYS =>        [$referenceDate->clone()->startOfDay()->subDays(6),                         $referenceDate->clone()->endOfDay()],
            self::LAST_14_DAYS =>       [$referenceDate->clone()->startOfDay()->subDays(13),                        $referenceDate->clone()->endOfDay()],
            self::LAST_30_DAYS =>       [$referenceDate->clone()->startOfDay()->subMonth()->addDay(),               $referenceDate->clone()->endOfDay()],
            self::LAST_90_DAYS =>       [$referenceDate->clone()->startOfDay()->subMonths(3)->addDay(),             $referenceDate->clone()->endOfDay()],
            self::LAST_180_DAYS =>      [$referenceDate->clone()->startOfDay()->subMonths(6)->addDay(),             $referenceDate->clone()->endOfDay()],
            self::LAST_365_DAYS =>      [$referenceDate->clone()->startOfDay()->subYear()->addDay(),                $referenceDate->clone()->endOfDay()],
            self::LAST_730_DAYS =>      [$referenceDate->clone()->startOfDay()->subYears(2)->addDay(),              $referenceDate->clone()->endOfDay()],
            self::LAST_1095_DAYS =>     [$referenceDate->clone()->startOfDay()->subYears(3)->addDay(),              $referenceDate->clone()->endOfDay()],

            self::LAST_MONTH =>         [$referenceDate->clone()->startOfMonth()->startOfDay()->subMonth(),         $referenceDate->clone()->endOfMonth()->endOfDay()],
            self::LAST_3_MONTHS =>      [$referenceDate->clone()->startOfMonth()->startOfDay()->subMonths(2),       $referenceDate->clone()->endOfMonth()->endOfDay()],
            self::LAST_6_MONTHS =>      [$referenceDate->clone()->startOfMonth()->startOfDay()->subMonths(5),       $referenceDate->clone()->endOfMonth()->endOfDay()],
            self::LAST_YEAR =>          [$referenceDate->clone()->startOfMonth()->startOfDay()->subMonths(11),      $referenceDate->clone()->endOfMonth()->endOfDay()],
            self::LAST_2_YEARS =>       [$referenceDate->clone()->startOfMonth()->startOfDay()->subMonths(23),      $referenceDate->clone()->endOfMonth()->endOfDay()],
            self::LAST_3_YEARS =>       [$referenceDate->clone()->startOfMonth()->startOfDay()->subMonths(35),      $referenceDate->clone()->endOfMonth()->endOfDay()],
            self::LAST_10_YEARS =>      [$referenceDate->clone()->startOfMonth()->startOfDay()->subMonths(119),     $referenceDate->clone()->endOfMonth()->endOfDay()],

            self::NEXT_7_DAYS =>        [$referenceDate->clone()->startOfDay(),                                     $referenceDate->clone()->endOfDay()->addDays(6)],
            self::NEXT_14_DAYS =>       [$referenceDate->clone()->startOfDay(),                                     $referenceDate->clone()->endOfDay()->addDays(13)],
            self::NEXT_30_DAYS =>       [$referenceDate->clone()->startOfDay(),                                     $referenceDate->clone()->endOfDay()->addMonth()->addDay()],
            self::NEXT_90_DAYS =>       [$referenceDate->clone()->startOfDay(),                                     $referenceDate->clone()->endOfDay()->addMonths(3)->addDay()],
            self::NEXT_180_DAYS =>      [$referenceDate->clone()->startOfDay(),                                     $referenceDate->clone()->endOfDay()->addMonths(6)->addDay()],
            self::NEXT_365_DAYS =>      [$referenceDate->clone()->startOfDay(),                                     $referenceDate->clone()->endOfDay()->addYear()->addDay()],
            self::NEXT_730_DAYS =>      [$referenceDate->clone()->startOfDay(),                                     $referenceDate->clone()->endOfDay()->addYears(2)->addDay()],
            self::NEXT_1095_DAYS =>     [$referenceDate->clone()->startOfDay(),                                     $referenceDate->clone()->endOfDay()->addYears(3)->addDay()],

            self::NEXT_MONTH =>         [$referenceDate->clone()->startOfMonth()->startOfDay(),                     $referenceDate->clone()->endOfDay()->addMonth()->endOfMonth()],
            self::NEXT_3_MONTHS =>      [$referenceDate->clone()->startOfMonth()->startOfDay(),                     $referenceDate->clone()->endOfDay()->addMonths(2)->endOfMonth()],
            self::NEXT_6_MONTHS =>      [$referenceDate->clone()->startOfMonth()->startOfDay(),                     $referenceDate->clone()->endOfDay()->addMonths(5)->endOfMonth()],
            self::NEXT_YEAR =>          [$referenceDate->clone()->startOfMonth()->startOfDay(),                     $referenceDate->clone()->endOfDay()->addMonths(11)->endOfMonth()],
            self::NEXT_2_YEARS =>       [$referenceDate->clone()->startOfMonth()->startOfDay(),                     $referenceDate->clone()->endOfDay()->addMonths(23)->endOfMonth()],
            self::NEXT_3_YEARS =>       [$referenceDate->clone()->startOfMonth()->startOfDay(),                     $referenceDate->clone()->endOfDay()->addMonths(35)->endOfMonth()],

            default =>                  [$referenceDate->clone()->startOfMonth()->startOfDay(),                     $referenceDate->clone()->endOfMonth()->endOfDay()],
        };
    }

    public function getInterval()
    {
        return match($this) {
            self::ONCE              => ['days', 1],
            self::EVERY_DAY         => ['days', 1],
            self::EVERY_WEEK        => ['weeks', 1],
            self::EVERY_2_WEEKS     => ['weeks', 2],
            self::EVERY_MONTH       => ['months', 1],
            self::EVERY_2_MONTHS    => ['months', 2],
            self::EVERY_3_MONTHS    => ['months', 3],
            self::EVERY_4_MONTHS    => ['months', 4],
            self::EVERY_6_MONTHS    => ['months', 6],
            self::EVERY_YEAR        => ['years', 1],

            default                 => ['addMonths', 1],
        };
    }

    public static function frequencyValues(): array {
        $values = [];
        foreach (self::cases() as $period) {
            if (str_contains($period->value, 'every') || $period->value == 'once') {
                $values[$period->name] = $period->value;
            }
        }
        return $values;
    }
}
