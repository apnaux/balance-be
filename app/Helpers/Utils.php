<?php

namespace App\Helpers;

use Carbon\Carbon;

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
}
