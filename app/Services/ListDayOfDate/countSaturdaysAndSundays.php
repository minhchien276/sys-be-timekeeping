<?php

namespace App\Services\ListDayOfDate;

use Carbon\Carbon;

class countSaturdaysAndSundays
{
    public function handle($start, $end)
    {
        $saturdays = 0;
        $sundays = 0;
        $currentDate = $start->copy(); // Copy ngày bắt đầu để tránh thay đổi giá trị ban đầu

        // Duyệt qua từng ngày từ ngày bắt đầu đến ngày kết thúc
        while ($currentDate->lte($end)) {
            // Kiểm tra nếu ngày hiện tại là thứ 7
            if ($currentDate->dayOfWeek === Carbon::SATURDAY) {
                $saturdays++;
            }
            // Kiểm tra nếu ngày hiện tại là chủ nhật
            if ($currentDate->dayOfWeek === Carbon::SUNDAY) {
                $sundays++;
            }
            // Tăng ngày hiện tại lên 1
            $currentDate->addDay();
        }

        // Kiểm tra có bao nhiêu ngày trong khoảng thời gian truyền vào
        $totaldays = $end->diffInDays($start) + 1;

        return [
            'totaldays' => $totaldays,
            'saturdays' => $saturdays,
            'sundays' => $sundays,
        ];
    }
}
