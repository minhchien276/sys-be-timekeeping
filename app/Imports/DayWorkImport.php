<?php

namespace App\Imports;

use App\Models\employee;
use App\Models\salary;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DayWorkImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $collection->transform(function ($row) {
            return array_map(function ($value) {
                return $value === null ? 0 : $value;
            }, $row->toArray());
        });

        $employeeId = employee::pluck('id')->toArray();

        foreach ($collection as $row) {
            if (in_array($row['ma_nhan_vien'], $employeeId)) {
                $total = 
                    (is_numeric(preg_replace('/[\s,\.]+/', '', $row['luong'])) ? (int) preg_replace('/[\s,\.]+/', '', $row['luong']) : 0) +
                    (is_numeric(preg_replace('/[\s,\.]+/', '', $row['phan_ck_hoan_tra'])) ? (int) preg_replace('/[\s,\.]+/', '', $row['phan_ck_hoan_tra']) : 0) +
                    (is_numeric(preg_replace('/[\s,\.]+/', '', $row['thuong'])) ? (int) preg_replace('/[\s,\.]+/', '', $row['thuong']) : 0) +
                    (is_numeric(preg_replace('/[\s,\.]+/', '', $row['thuong_khac'])) ? (int) preg_replace('/[\s,\.]+/', '', $row['thuong_khac']) : 0) +
                    (is_numeric(preg_replace('/[\s,\.]+/', '', $row['thuc_nhan_luong_thuong'])) ? (int) preg_replace('/[\s,\.]+/', '', $row['thuc_nhan_luong_thuong']) : 0) +
                    (is_numeric(preg_replace('/[\s,\.]+/', '', $row['phu_cap'])) ? (int) preg_replace('/[\s,\.]+/', '', $row['phu_cap']) : 0) -
                    (is_numeric(preg_replace('/[\s,\.]+/', '', $row['phat'])) ? (int) preg_replace('/[\s,\.]+/', '', $row['phat']) : 0) -
                    (is_numeric(preg_replace('/[\s,\.]+/', '', $row['mua_thuoc_cong_ty'])) ? (int) preg_replace('/[\s,\.]+/', '', $row['mua_thuoc_cong_ty']) : 0) -
                    (is_numeric(preg_replace('/[\s,\.]+/', '', $row['tru_loi_don_hang'])) ? (int) preg_replace('/[\s,\.]+/', '', $row['tru_loi_don_hang']) : 0) -
                    (is_numeric(preg_replace('/[\s,\.]+/', '', $row['nop_bu'])) ? (int) preg_replace('/[\s,\.]+/', '', $row['nop_bu']) : 0);

                salary::create([
                    'employeeId' => $row['ma_nhan_vien'],
                    'workDay' => $row['so_cong_tinh_luong'],
                    'less5m' => $row['so_buoi_di_muon_duoi_5_phut'],
                    'more5m' => $row['so_buoi_di_muon_tren_5_phut'],
                    'dayMissing' => $row['so_lan_quen_cham_cong'],
                    'dayOff' => $row['so_buoi_nghi_khong_luong'] + $row['so_buoi_nghi_co_luong'],
                    "unpaidLeaveDays" => $row['so_buoi_nghi_khong_luong'],
                    "paidLeaveDays" => $row['so_buoi_nghi_co_luong'],
                    'dayOffLeft' => $row['so_ngay_phep_con_lai'],
                    'salary' => preg_replace('/[\s,\.]+/', '', $row['luong']),
                    'bonus' => preg_replace('/[\s,\.]+/', '', $row['phu_cap']),
                    'otherBonus' => preg_replace('/[\s,\.]+/', '', $row['thuong_khac']),
                    'insurancePrice' => preg_replace('/[\s,\.]+/', '', $row['bhxh_bhyt']),
                    'total' => intval($total / 1000) * 1000,
                    'punishPrice' => is_numeric(preg_replace('/[\s,\.]+/', '', $row['phat'])) ? (int) preg_replace('/[\s,\.]+/', '', $row['phat']) : 0,
                    'drugPrice' => preg_replace('/[\s,\.]+/', '', $row['mua_thuoc_cong_ty']),
                    'errorOrderPrice' => preg_replace('/[\s,\.]+/', '', $row['tru_loi_don_hang']),
                    'refundPrice' => preg_replace('/[\s,\.]+/', '', $row['thuc_nhan_luong_thuong']),
                    'CK' => preg_replace('/[\s,\.]+/', '', $row['ck']),
                    'bonusByMonth' => preg_replace('/[\s,\.]+/', '', $row['thuong']),
                    'discountRefund' => preg_replace('/[\s,\.]+/', '', $row['phan_ck_hoan_tra']),
                    'discountKeeping' => preg_replace('/[\s,\.]+/', '', $row['phan_ck_giu_lai_30']),
                    'responseDeadline' => null,
                    'responseContent' => preg_replace('/[\s,\.]+/', '', $row['nop_bu']),
                    'createdAt' => Carbon::now(),
                ]);
            }
        }
    }
}
