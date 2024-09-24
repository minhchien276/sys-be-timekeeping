<?php

namespace App\Services\ListDayOfDate;

use Carbon\Carbon;

class getTwoMonth
{
    public function handle()
    {
        // tháng này
        $firstOfThisMonth = Carbon::now()->firstOfMonth();
        $endOfThisMonth = Carbon::now()->endOfMonth();

        // tháng trước
        $firstOfLastMonth = Carbon::now()->subMonth()->firstOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        $currentMonth = [$firstOfThisMonth->timestamp * 1000, $endOfThisMonth->timestamp * 1000];
        $previousMonth = [$firstOfLastMonth->timestamp * 1000, $endOfLastMonth->timestamp * 1000];

        $data = [$currentMonth, $previousMonth];

        return $data;
    }
}
