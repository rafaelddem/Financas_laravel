<?php

namespace App\Services;

use App\Enums\Period;
use Carbon\Carbon;

class DateService extends BaseService
{
    public function generateNextDates(Period $period, Carbon $startDate, ?Carbon $endDate = null)
    {
        if ($period == Period::ONCE)
            return [$startDate];

        $endDate = $endDate ?? $startDate->clone()->addYears(5)->subDay();

        [$interval, $frequency] = $period->getInterval();

        switch ($interval) {
            case 'days':
                return $this->eachDay($startDate, $endDate, $frequency);

            case 'weeks':
                return $this->eachDay($startDate, $endDate, ($frequency * 7));

            case 'months':
                return $this->eachMonth($startDate, $endDate, $frequency);

            case 'years':
                return $this->eachMonth($startDate, $endDate, ($frequency * 12));
            
            default:
                return $this->eachMonth($startDate, $endDate, $frequency);
        }
    }

    public function eachDay(Carbon $startDate, Carbon $endDate, int $frequency)
    {
        $dates = [$startDate->clone()];

        while ($startDate->lte($endDate)) {
            $dates[] = $startDate->addDays($frequency)->clone();
        }

        return $dates;
    }

    public function eachMonth(Carbon $startDate, Carbon $endDate, int $frequency)
    {
        $dates = [$startDate->clone()];
        $referenceDate = $startDate->clone();
        $lastDate = $startDate->clone();
        $months = 0;

        while ($lastDate->lte($endDate)) {
            $months += $frequency;
            $lastDate = $referenceDate->clone()->addMonthNoOverflows($months);
            $dates[] = $referenceDate->clone()->addMonthNoOverflows($months);
        }

        return $dates;
    }
}