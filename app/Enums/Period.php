<?php

namespace App\Enums;

enum Period: string {
    case DEFAULT = 'default';

    case TODAY = 'today';
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
}
