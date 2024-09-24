<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ServicesAdmin\ExportPdf\exportPdf;
use App\ServicesAdmin\Orders\createAdvice;
use App\ServicesAdmin\Orders\createDosage;
use App\ServicesAdmin\Orders\createMedicine;
use App\ServicesAdmin\Orders\createTypeMedicine;
use App\ServicesAdmin\Orders\createUses;
use App\ServicesAdmin\Orders\orderServices;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $createDosage;
    private $createUses;
    private $createAdvice;
    private $createTypeMedicine;
    private $createMedicine;
    private $exportPdf;
    private $orderServices;

    public function __construct(
        createDosage $createDosage,
        createUses $createUses,
        createAdvice $createAdvice,
        createTypeMedicine $createTypeMedicine,
        createMedicine $createMedicine,
        exportPdf $exportPdf,
        orderServices $orderServices,
    ) {
        $this->createDosage = $createDosage;
        $this->createUses = $createUses;
        $this->createAdvice = $createAdvice;
        $this->createTypeMedicine = $createTypeMedicine;
        $this->createMedicine = $createMedicine;
        $this->exportPdf = $exportPdf;
        $this->orderServices = $orderServices;
    }

    public function index()
    {
        return $this->orderServices->index();
    }

    public function create()
    {
        return $this->orderServices->create();
    }

    public function store(Request $request)
    {
        return $this->orderServices->store($request);
    }

    public function generatePDF($id)
    {
        return $this->exportPdf->handle($id);
    }

    public function delete($id)
    {
        return $this->orderServices->delete($id);
    }

    public function searchDate(Request $request)
    {
        return $this->orderServices->searchDate($request);
    }

    public function createMedicine(Request $request)
    {
        return $this->createMedicine->handle($request);
    }

    public function createTypeMedicine(Request $request)
    {
        return $this->createTypeMedicine->handle($request);
    }

    public function createUses(Request $request)
    {
        return $this->createUses->handle($request);
    }

    public function createDosage(Request $request)
    {
        return $this->createDosage->handle($request);
    }

    public function createAdvice(Request $request)
    {
        return $this->createAdvice->handle($request);
    }
}
