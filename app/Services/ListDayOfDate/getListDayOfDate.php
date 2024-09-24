<?php

namespace App\Services\ListDayOfDate;

use Carbon\Carbon;

class getListDayOfDate
{
    public function handle()
    {
        $today = Carbon::today();
        $lastMonth = Carbon::today()->subMonth()->day(21)->timestamp * 1000;
        $thisMonth = Carbon::today()->day(20)->timestamp * 1000;
        $thisMonth2 = Carbon::today()->day(21)->timestamp * 1000;
        $nextMonth = Carbon::today()->addMonth()->day(20)->timestamp * 1000;
        $listDate = [];

        if ($today->day >= 21) {
            $listDate = [$thisMonth2, $nextMonth];
        } else {
            $listDate = [$lastMonth, $thisMonth];
        }

        return $listDate;
    }
}
