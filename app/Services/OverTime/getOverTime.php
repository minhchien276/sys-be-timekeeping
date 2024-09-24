<?php

namespace App\Services\OverTime;

use App\Models\overtime;
use App\Services\ListDayOfDate\getListDayOfDate;
use App\Services\ParseToken\ParseToken;
use App\Supports\Responder;
use Exception;

class getOverTime
{
    private $getListDayOfDate;
    private $parseToken;

    public function __construct(
        getListDayOfDate $getListDayOfDate,
        ParseToken $parseToken,
    ) {
        $this->getListDayOfDate = $getListDayOfDate;
        $this->parseToken = $parseToken;
    }

    public function handle()
    {
        $listDate = $this->getListDayOfDate->handle();
        $employee = $this->parseToken->handle();

        try {
            $overtime = overtime::leftJoin('application', 'application.id', '=', 'overtime.applicationId')
                ->where('application.status', 1)
                ->where('overtime.employeeId', $employee->id)
                ->whereBetween('overtime.dayOffDate', $listDate)
                ->get();

            if (!$overtime) {
                return Responder::fail(null, "Không tìm thấy tăng ca", 404);
            }

            return Responder::success($overtime, "Danh sách tăng ca");
        } catch (Exception $e) {
            return Responder::fail(null, $e->getMessage());
        }
    }
}
