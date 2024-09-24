<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCheckoutRequest;
use App\ServicesAdmin\Checkout\createCheckout;
use App\ServicesAdmin\Checkout\detailsCheckout;
use App\ServicesAdmin\Checkout\editCheckout;
use App\ServicesAdmin\Checkout\indexCheckout;
use App\ServicesAdmin\Checkout\searchDate;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    private $indexCheckout;
    private $searchDate;
    private $createCheckout;
    private $editCheckout;
    private $detailsCheckout;

    public function __construct(
        indexCheckout $indexCheckout,
        searchDate $searchDate,
        createCheckout $createCheckout,
        editCheckout $editCheckout,
        detailsCheckout $detailsCheckout,
    ) {
        $this->indexCheckout = $indexCheckout;
        $this->searchDate = $searchDate;
        $this->createCheckout = $createCheckout;
        $this->editCheckout = $editCheckout;
        $this->detailsCheckout = $detailsCheckout;
    }

    public function index()
    {
        return $this->indexCheckout->index();
    }

    public function searchDate(Request $request)
    {
        return $this->searchDate->handle($request);
    }

    public function create()
    {
        return $this->createCheckout->create();
    }

    public function store(CreateCheckoutRequest $request)
    {
        return $this->createCheckout->store($request);
    }

    public function edit($id)
    {
        return $this->editCheckout->edit($id);
    }

    public function update(Request $request, $id)
    {
        return $this->editCheckout->update($request, $id);
    }

    public function indexDetails()
    {
        return $this->detailsCheckout->indexDetails();
    }

    public function CheckoutDetails(Request $request)
    {
        return $this->detailsCheckout->CheckoutDetails($request);
    }
}
