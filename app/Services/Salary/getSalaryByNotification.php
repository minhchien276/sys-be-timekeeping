<?php

namespace App\Services\Salary;

use App\Models\notification;
use App\Services\ParseToken\ParseToken;
use App\Supports\Responder;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class getSalaryByNotification
{
    private $parseToken;

    public function __construct(
        ParseToken $parseToken,
    ) {
        $this->parseToken = $parseToken;
    }

    public function handle($request)
    {
        $employee = $this->parseToken->handle();

        try {
            $createdAt = notification::where('id', $request->id)->pluck('createdAt')->first();
            $newCreatedAt = Carbon::createFromTimestamp($createdAt / 1000)->format('Y-m-d');

            $salary = DB::table('salary')
                ->where('employeeId', $employee->id)
                ->orderByRaw('ABS(DATEDIFF(createdAt, ?))', [$newCreatedAt])
                ->first();

            if (!$salary) {
                return Responder::fail(null, 'Không tìm thấy bảng lương', 404);
            }

            $formattedNumber = function ($value) {
                return !is_double($value) ? str_replace(',', '.', number_format($value)) : $value;
            };

            $data = [];

            $fieldSales = [
                "workDay" => ["name" => "Số ngày công", "subTitle" => null, "type" => 0],
                "less5m" => ["name" => "Số buổi đi muộn dưới 5 phút", "subTitle" => null, "type" => 0],
                "more5m" => ["name" => "Số buổi đi muộn trên 5 phút", "subTitle" => null, "type" => 0],
                "dayMissing" => ["name" => "Số lần quên chấm công", "subTitle" => null, "type" => 0],
                "dayOff" => ["name" => "Số ngày nghỉ", "subTitle" => null, "type" => 0],
                "dayOffLeft" => ["name" => "Số ngày phép còn lại", "subTitle" => null, "type" => 0],
                "salary" => ["name" => "Lương", "subTitle" => null, "type" => 0],
                "bonus" => ["name" => "Phụ cấp", "subTitle" => null, "type" => 0],
                "bonusByMonth" => ["name" => "Thưởng", "subTitle" => null, "type" => 1],
                "refundPrice" => ["name" => "Thực nhận lương thưởng", "subTitle" => null, "type" => 1],
                "otherBonus" => ["name" => "Thưởng khác", "subTitle" => null, "type" => 0],
                "CK" => ["name" => "Chiết khấu", "subTitle" => null, "type" => 1],
                "discountRefund" => ["name" => "Phần CK hoàn trả", "subTitle" => null, "type" => 0],
                "discountKeeping" => ["name" => "Phần CK giữ lại (30%)", "subTitle" => null, "type" => 0],
                "insurancePrice" => ["name" => "BHXH - BHYT", "subTitle" => "(CT chi trả cho người lao động)", "type" => 1],
                "punishPrice" => ["name" => "Phạt", "subTitle" => null, "type" => 2],
                "drugPrice" => ["name" => "Mua thuốc công ty", "subTitle" => null, "type" => 2],
                "errorOrderPrice" => ["name" => "Trừ lỗi đơn hàng", "subTitle" => null, "type" => 2],
                // "responseDeadline" => ["name" => "Thời gian phản hồi", "subTitle" => null, "type" => 0],
                // "responseContent" => ["name" => "Nội dung phản hồi", "subTitle" => null, "type" => 0],
                "total" => ["name" => "Tổng thực nhận", "subTitle" => null, "type" => 1],
            ];

            $fields = [
                "workDay" => ["name" => "Số ngày công", "subTitle" => null, "type" => 0],
                "less5m" => ["name" => "Số buổi đi muộn dưới 5 phút", "subTitle" => null, "type" => 0],
                "more5m" => ["name" => "Số buổi đi muộn trên 5 phút", "subTitle" => null, "type" => 0],
                "dayMissing" => ["name" => "Số lần quên chấm công", "subTitle" => null, "type" => 0],
                "dayOff" => ["name" => "Số ngày nghỉ", "subTitle" => null, "type" => 0],
                "dayOffLeft" => ["name" => "Số ngày phép còn lại", "subTitle" => null, "type" => 0],
                "salary" => ["name" => "Lương", "subTitle" => null, "type" => 0],
                "bonus" => ["name" => "Phụ cấp", "subTitle" => null, "type" => 0],
                "bonusByMonth" => ["name" => "Thưởng", "subTitle" => null, "type" => 1],
                "otherBonus" => ["name" => "Thưởng khác", "subTitle" => null, "type" => 0],
                "insurancePrice" => ["name" => "BHXH - BHYT", "subTitle" => "(CT chi trả cho người lao động)", "type" => 1],
                "punishPrice" => ["name" => "Phạt", "subTitle" => null, "type" => 2],
                "drugPrice" => ["name" => "Mua thuốc công ty", "subTitle" => null, "type" => 2],
                "errorOrderPrice" => ["name" => "Trừ lỗi đơn hàng", "subTitle" => null, "type" => 2],
                // "responseDeadline" => ["name" => "Thời gian phản hồi", "subTitle" => null, "type" => 0],
                // "responseContent" => ["name" => "Nội dung phản hồi", "subTitle" => null, "type" => 0],
                "total" => ["name" => "Tổng thực nhận", "subTitle" => null, "type" => 1],
            ];

            $rowData = [];
            foreach ($employee->departmentId == 1 ? $fieldSales : $fields as $field => $info) {
                $rowData[] = [
                    "name" => $info["name"],
                    "subTitle" => $info["subTitle"],
                    "number" => strval($formattedNumber($salary->$field)) ?? null,
                    "type" => $info["type"],
                ];
            }

            $data[] = [
                "month" => $salary->createdAt,
                "items" => $rowData
            ];


            return Responder::success($data, 'lay bang luong thanh cong');
        } catch (Exception $e) {
            return Responder::fail(null, $e->getMessage());
        }
    }
}
