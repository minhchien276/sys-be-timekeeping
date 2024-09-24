<?php

namespace App\ServicesAdmin\ExportPdf;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class exportPdf
{
    public function handle($id)
    {
        $orders = DB::table('order_details')
            ->leftJoin('order_medicine', 'order_details.orderId', '=', 'order_medicine.id')
            ->select('order_medicine.title', 'order_medicine.expertId', 'order_details.*')
            ->where('order_details.orderId', '=', $id)
            ->get();

        $expert = DB::table('order_details')
            ->leftJoin('order_medicine', 'order_details.orderId', '=', 'order_medicine.id')
            ->leftJoin('expert', 'order_medicine.expertId', '=', 'expert.id')
            ->select('expert.*', 'order_medicine.note', 'order_medicine.qrCode')
            ->where('order_details.orderId', '=', $id)
            ->first();

        $order_name = DB::table('order_details')
            ->leftJoin('order_medicine', 'order_details.orderId', '=', 'order_medicine.id')
            ->select('order_medicine.title')
            ->where('order_details.orderId', '=', $id)
            ->first();

        $data = [
            'title' => 'THƯ VIỆN DA LIỄU TRỰC TUYẾN',
            'expert' => $expert,
            'order_name' => $order_name,
            'orders' => $orders,
        ];

        $pdf = Pdf::loadView('admin.orders.pdf', $data);
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'isHtml5Powered' => true,
        ]);

        return $pdf->download($order_name->title . '.pdf');
    }
}
