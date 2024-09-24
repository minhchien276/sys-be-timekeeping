<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCheckinRequest;
use App\ServicesAdmin\Checkin\createCheckin;
use App\ServicesAdmin\Checkin\detailsCheckin;
use App\ServicesAdmin\Checkin\editCheckin;
use App\ServicesAdmin\Checkin\indexCheckin;
use App\ServicesAdmin\Checkin\searchDate;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    private $indexCheckin;
    private $searchDate;
    private $createCheckin;
    private $editCheckin;
    private $detailsCheckin;

    public function __construct(
        indexCheckin $indexCheckin,
        searchDate $searchDate,
        createCheckin $createCheckin,
        editCheckin $editCheckin,
        detailsCheckin $detailsCheckin,
    ) {
        $this->indexCheckin = $indexCheckin;
        $this->searchDate = $searchDate;
        $this->createCheckin = $createCheckin;
        $this->editCheckin = $editCheckin;
        $this->detailsCheckin = $detailsCheckin;
    }

    public function index()
    {
        return $this->indexCheckin->index();
    }

    public function searchDate(Request $request)
    {
        return $this->searchDate->handle($request);
    }

    public function create()
    {
        return $this->createCheckin->create();
    }

    public function store(CreateCheckinRequest $request)
    {
        return $this->createCheckin->store($request);
    }

    public function edit($id)
    {
        return $this->editCheckin->edit($id);
    }

    public function update(Request $request, $id)
    {
        return $this->editCheckin->update($request, $id);
    }

    public function indexDetails()
    {
        return $this->detailsCheckin->indexDetails();
    }

    public function CheckinDetails(Request $request)
    {
        return $this->detailsCheckin->CheckinDetails($request);
    }
}
