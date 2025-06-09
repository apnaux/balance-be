<?php

namespace App\Helpers;

use Carbon\Carbon;

class Utils
{
    public static function getProperStatementDate(string $timezone, int $cutoff)
    {
        $dateNow = Carbon::now($timezone)->day;
        $calculatedDate = Carbon::now($timezone)->setDay($cutoff)->startOfDay()->timezone('UTC');
        return $dateNow < $cutoff ? $calculatedDate->subMonth()
            : $calculatedDate;
    }
}
