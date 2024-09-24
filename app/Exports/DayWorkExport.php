<?php

namespace App\Exports;

use App\Enums\DepartmentEnum;
use App\Models\employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DayWorkExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    private $current;

    public function __construct($current)
    {
        $this->current = $current;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = &$this->current;
        foreach ($data as $key => &$item) {
            $employee = employee::leftJoin('department', 'department.id', '=', 'employee.departmentId')
                ->leftJoin('roles', 'roles.id', '=', 'employee.roleId')
                ->select('employee.*', 'department.name as departmentName', 'roles.name as roleName')
                ->where('employee.departmentId', '!=', DepartmentEnum::Director)
                ->where('employee.status', 1)
                ->where('employee.id', $item['employeeId'])
                ->first();

            if ($employee) {
                $item['fullname'] = $employee->fullname;
                // $item['salary'] = $employee->salary;
                $item['departmentName'] = $employee->departmentName;
                $item['roleId'] = $employee->roleId;
            } else {
                unset($data[$key]);
            }
        }

        return collect($data);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Tự động điều chỉnh chiều rộng của các cột dựa trên nội dung của chúng
                $event->sheet->autoSize();

                // Lấy số lượng cột
                $columnCount = $event->sheet->getDelegate()->getHighestColumn();

                // Thiết lập font đậm
                $event->sheet->getStyle('A1:' . $columnCount . '1')->getFont()->setBold(true);

                // Thiết lập màu nền xanh
                $event->sheet->getStyle('A1:' . $columnCount . '1')->getFill()->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFFFD700');
            },
        ];
    }

    public function headings(): array
    {
        return [
            'Mã nhân viên',
            'Tên nhân viên',
            'Phòng ban',
            'Số ngày công',
            'Số công tăng ca',
            'Số công tính lương',
            'Số buổi nghỉ có lương',
            'Số buổi nghỉ không lương',
            'Số ngày phép còn lại',
            'Số buổi đi muộn trên 5 phút',
            'Số buổi đi muộn dưới 5 phút',
            'Số lần quên chấm công',
            'Lương',
            'Thưởng',
            'CK',
            'Thưởng khác',
            'Thực nhận lương thưởng',
            'Phần CK giữ lại (30%)',
            'Phần CK hoàn trả',
            'Phụ cấp',
            'BHXH - BHYT',
            'Phạt',
            'Trừ lỗi đơn hàng',
            'Mua thuốc công ty',
            'Nộp bù',
        ];
    }

    public function map($record): array
    {
        $phucap = 0;

        if ($record['roleId'] == 5 || $record['roleId'] == 6 || $record['roleId'] == 7) {
            $phucap = 0;
        } else {
            $phucap = 200000;
        }

        return [
            $record['employeeId'],
            $record['fullname'],
            $record['departmentName'],
            $record['daywork'],
            $record['dayworkOvertime'],
            $record['daywork'] + $record['dayworkOvertime'],
            $record['dayOffPaidLeave'],
            $record['dayoff'] - $record['dayOffPaidLeave'],
            $record['dayOffLeft'],
            $record['more5m'],
            $record['less5m'],
            $record['missing'],
            number_format($record['salary']),
            null,
            null,
            null,
            null,
            null,
            null,
            number_format($phucap),
            null,
            number_format($record['punishPrice']),
            null,
            null,
            null,
            null,
        ];
    }
}
