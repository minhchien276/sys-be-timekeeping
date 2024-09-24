<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WorkOrder\Participant\updateStatusParticipant;
use App\Services\WorkOrder\Participant\createParticipant;
use Illuminate\Http\Request;

class ApiParticipantController extends Controller
{
    private $createParticipant;
    private $updateStatusParticipant;

    public function __construct(
        createParticipant $createParticipant,
        updateStatusParticipant $updateStatusParticipant,
    ) {
        $this->middleware('auth:api');
        $this->updateStatusParticipant = $updateStatusParticipant;
        $this->createParticipant = $createParticipant;
    }

    public function createParticipant(Request $request)
    {
        return $this->createParticipant->handle($request);
    }

    public function updateStatusParticipant(Request $request, $participantId)
    {
        return $this->updateStatusParticipant->handle($request, $participantId);
    }
}
