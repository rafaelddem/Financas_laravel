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
        $startDate->addDays($frequency);

        while ($startDate->lte($endDate)) {
            $dates[] = $startDate->clone();
            $startDate->addDays($frequency);
        }

        return $dates;
    }

    public function eachMonth(Carbon $startDate, Carbon $endDate, int $frequency)
    {
        $dates = [$startDate->clone()];
        $startDate->addMonthNoOverflows($frequency);

        while ($startDate->lte($endDate)) {
            $dates[] = $startDate->clone();
            $startDate->addMonthNoOverflows($frequency);
        }

        return $dates;
    }
}