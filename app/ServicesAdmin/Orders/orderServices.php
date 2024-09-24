<?php

namespace App\ServicesAdmin\Orders;

use App\Models\advice;
use App\Models\dosage;
use App\Models\expert;
use App\Models\medicine;
use App\Models\note;
use App\Models\order_details;
use App\Models\order_medicine;
use App\Models\type_medicine;
use App\Models\uses;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class orderServices
{
    public function index()
    {
        $startTime = Carbon::now()->startOfDay()->timestamp * 1000;
        $endTime = Carbon::now()->endOfDay()->timestamp * 1000;

        $search_date = '';

        $orders = order_medicine::join('expert', 'order_medicine.expertId', '=', 'expert.id')
            ->select('order_medicine.id', 'order_medicine.title', 'expert.name', 'order_medicine.createdAt')
            ->whereBetween('order_medicine.createdAt', [$startTime, $endTime])
            ->get();

        $orders->map(function ($item) {
            if ($item->createdAt) {
                $createdAt = Carbon::createFromTimestamp($item->createdAt / 1000);
                $item->createdAt = $createdAt->format('d-m-Y H:i:s');
            }

            return $item;
        });

        return view('admin.orders.index', compact('orders', 'search_date'));
    }

    public function create()
    {
        $expert = expert::select('id', 'name')->get();

        $medicine = medicine::select('id', 'medicineName')->get();

        $type_medicine = type_medicine::select('id', 'type')->get();

        $uses = uses::select('id', 'usesName')->get();

        $dosage = dosage::select('id', 'dosageName')->get();

        $advice = advice::select('id', 'adviceName')->get();

        $note = note::select('id', 'noteName', 'keyword')->get();

        return view('admin.orders.create')->with([
            'expert' => $expert,
            'medicine' => $medicine,
            'type_medicine' => $type_medicine,
            'uses' => $uses,
            'dosage' => $dosage,
            'advice' => $advice,
            'note' => $note,
        ]);
    }

    public function store($request)
    {
        $now = Carbon::now()->timestamp * 1000;

        try {
            DB::beginTransaction();

            $order = new order_medicine();
            $order->title = $request->input('title');
            $order->expertId = $request->input('expert');
            $order->note = $request->input('note');
            $order->qrCode = $request->input('qrCode');
            $order->createdAt = $now;
            $order->save();

            // Lưu thông tin từng loại thuốc
            foreach ($request->input('medicines') as $index => $medicineData) {
                $medicine = new order_details();
                $medicine->orderId = $order->id;
                $medicine->medicine = ($medicineData['medicineName'] === 'other') ? $request->otherMedicine : $medicineData['medicineName'];
                $medicine->typeMedicine = ($request->input('type')[$index]['type'] === 'other') ? $request->otherType : $request->input('type')[$index]['type'];
                $medicine->uses = ($request->input('uses')[$index]['uses'] === 'other') ? $request->otherUses : $request->input('uses')[$index]['uses'];
                $medicine->dosage = ($request->input('dosage')[$index]['dosage'] === 'other') ? $request->otherDosage : $request->input('dosage')[$index]['dosage'];
                $medicine->advice = ($request->input('advice')[$index]['advice'] === 'other') ? $request->otherAdvice : $request->input('advice')[$index]['advice'];
                $medicine->createdAt = $now;
                $medicine->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            Session::flash('error', 'Tạo đơn không thành công.');

            return redirect()->back()->with('error', 'Tạo đơn không thành công.');
        }

        Session::flash('success', 'Đơn đã được tạo thành công.');

        return redirect()->back()->with('success', 'Đơn đã được tạo thành công.');
    }

    public function delete($id)
    {
        order_details::where('orderId', $id)->delete();

        order_medicine::where('id', $id)->delete();

        $this->index();

        return back();
    }

    public function searchDate($request)
    {
        $search_date = $request->input('search_date');

        $format_search_date = Carbon::createFromFormat('Y-m-d', $search_date);

        $startTime = $format_search_date->startOfDay()->timestamp * 1000;

        $endTime = $format_search_date->endOfDay()->timestamp * 1000;

        $orders = order_medicine::join('expert', 'order_medicine.expertId', '=', 'expert.id')
            ->select('order_medicine.id', 'order_medicine.title', 'expert.name', 'order_medicine.createdAt')
            ->whereBetween('order_medicine.createdAt', [$startTime, $endTime])
            ->get();

        $orders->map(function ($item) {
            if ($item->createdAt) {
                $createdAt = Carbon::createFromTimestamp($item->createdAt / 1000);
                $item->createdAt = $createdAt->format('d-m-Y H:i:s');
            }

            return $item;
        });

        return view('admin.orders.index', compact('orders', 'search_date'));
    }
}
