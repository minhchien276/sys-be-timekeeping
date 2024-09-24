<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WorkOrder\TaskParticipant\createTaskParticipant;
use Illuminate\Http\Request;

class ApiTaskParticipantController extends Controller
{
    private $createTaskParticipant;

    public function __construct(
        createTaskParticipant $createTaskParticipant,
    ) {
        $this->middleware('auth:api');
        $this->createTaskParticipant = $createTaskParticipant;
    }

    public function createTaskParticipant(Request $request)
    {
        return $this->createTaskParticipant->handle($request);
    }
}
