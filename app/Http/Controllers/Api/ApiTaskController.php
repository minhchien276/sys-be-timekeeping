<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WorkOrder\Task\createTask;
use App\Services\WorkOrder\Task\getTaskByParticipant;
use App\Services\WorkOrder\Task\getTasks;
use App\Services\WorkOrder\Task\searchTasks;
use Illuminate\Http\Request;

class ApiTaskController extends Controller
{
    private $createTask;
    private $getTaskByParticipant;
    private $getTasks;
    private $searchTasks;

    public function __construct(
        createTask $createTask,
        getTaskByParticipant $getTaskByParticipant,
        getTasks $getTasks,
        searchTasks $searchTasks,
    ) {
        $this->middleware('auth:api');
        $this->createTask = $createTask;
        $this->getTaskByParticipant = $getTaskByParticipant;
        $this->getTasks = $getTasks;
        $this->searchTasks = $searchTasks;
    }

    public function createTask(Request $request)
    {
        return $this->createTask->handle($request);
    }

    public function getTaskByParticipant(Request $request)
    {
        return $this->getTaskByParticipant->handle($request);
    }

    public function getTasks(Request $request)
    {
        return $this->getTasks->handle($request);
    }

    public function search(Request $request)
    {
        return $this->searchTasks->handle($request);
    }
}
