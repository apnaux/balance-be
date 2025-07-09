<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Number;

class Utils
{
    /**
     * Retrieves the UTC time of the specified timezone and cutoff date
     *
     * @param string $timezone The timezone to use before converting to UTC time
     * @param int $cutoff The statement's cutoff date
     * @return Carbon The UTC time of the specified timezone and cutoff date
     */
    public static function getProperStatementDate(string $timezone, int $cutoff)
    {
        $dayNow = Carbon::now($timezone)->day;
        $calculatedDate = Carbon::now($timezone)->setDay($cutoff)->startOfDay()->timezone('UTC');
        return $dayNow < $cutoff ? $calculatedDate->subMonth()
            : $calculatedDate;
    }

    public static function getFormattedAmount(int $unformatted, string $currency){
        return Number::currency(round($unformatted / 100, 2), $currency);
    }
}
