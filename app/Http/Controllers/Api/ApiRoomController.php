<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WorkOrder\Room\createRoom;
use App\Services\WorkOrder\Room\getRoomApproved;
use App\Services\WorkOrder\Room\getRoomPending;
use App\Services\WorkOrder\Room\searchRoom;
use Illuminate\Http\Request;

class ApiRoomController extends Controller
{
    private $createRoom;
    private $getRoomApproved;
    private $getRoomPending;
    private $searchRoom;

    public function __construct(
        createRoom $createRoom,
        getRoomApproved $getRoomApproved,
        getRoomPending $getRoomPending,
        searchRoom $searchRoom,
    ) {
        $this->middleware('auth:api');
        $this->createRoom = $createRoom;
        $this->getRoomApproved = $getRoomApproved;
        $this->getRoomPending = $getRoomPending;
        $this->searchRoom = $searchRoom;
    }

    public function createRoom(Request $request)
    {
        return $this->createRoom->handle($request);
    }

    public function getRoomApproved(Request $request)
    {
        return $this->getRoomApproved->getRoomApproved($request);
    }

    public function getRoomPending(Request $request)
    {
        return $this->getRoomPending->getRoomPending($request);
    }

    public function search(Request $request)
    {
        return $this->searchRoom->handle($request);
    }
}
