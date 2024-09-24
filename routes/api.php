<?php

use App\Http\Controllers\Api\ApiApplicationController;
use App\Http\Controllers\Api\ApiAuthConttroller;
use App\Http\Controllers\Api\ApiBlogController;
use App\Http\Controllers\Api\ApiCalendarController;
use App\Http\Controllers\Api\ApiCheckinController;
use App\Http\Controllers\Api\ApiCheckoutController;
use App\Http\Controllers\Api\ApiDocumentController;
use App\Http\Controllers\Api\ApiEmployeeController;
use App\Http\Controllers\Api\ApiOverTimeController;
use App\Http\Controllers\Api\ApiParticipantController;
use App\Http\Controllers\Api\ApiRoomController;
use App\Http\Controllers\Api\ApiSalaryController;
use App\Http\Controllers\Api\ApiTaskController;
use App\Http\Controllers\Api\ApiTaskParticipantController;
use App\Http\Controllers\Api\ApiTestController;
use App\Http\Controllers\ApiNotificationController;
use App\Http\Controllers\Api\TuViController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// auth
Route::prefix('auth')->group(function () {
    Route::post('login', [ApiAuthConttroller::class, 'login']);
    Route::post('register', [ApiAuthConttroller::class, 'register']);
    Route::post('refresh-token', [ApiAuthConttroller::class, 'refreshToken']);
    Route::post('logout', [ApiAuthConttroller::class, 'logout']);
});

// employee
Route::prefix('employee')->middleware('check_user_status')->group(function () {
    Route::get('get-all-employee', [ApiEmployeeController::class, 'getAllEmployee']);
    Route::get('get-employee-details/{id}', [ApiEmployeeController::class, 'getEmployeeDetails']);
    Route::get('get-birthday-today', [ApiEmployeeController::class, 'getBirthdayToday']);
    Route::get('get-on-board-today', [ApiEmployeeController::class, 'getOnBoardToday']);

    // Route::post('insert-all-checkin', [ApiEmployeeController::class, 'checkinAll']);
    // Route::post('insert-all-checkout', [ApiEmployeeController::class, 'checkoutAll']);

    // get-leader
    Route::get('get-leader', [ApiEmployeeController::class, 'getLeader']);
});

// notification
Route::prefix('notification')->middleware('check_user_status')->group(function () {
    Route::patch('update-device-token/{id}', [ApiNotificationController::class, 'updateDeviceToken']);
    Route::get('push-noti', [ApiNotificationController::class, 'pushNoti']);
});

// blog
Route::prefix('blog')->middleware('check_user_status')->group(function () {
    Route::get('get-all-blog', [ApiBlogController::class, 'getAllBlog']);
    Route::get('get-blog-details/{id}', [ApiBlogController::class, 'getBlogById']);
});

// document
Route::prefix('document')->middleware('check_user_status')->group(function () {
    Route::get('get-by-id/{employeeId}', [ApiDocumentController::class, 'getById']);
});

// application
Route::prefix('application')->middleware('check_user_status')->group(function () {
    Route::get('get-by-id', [ApiApplicationController::class, 'getById']);
    Route::get('get-applications-leader', [ApiApplicationController::class, 'getApplications']);
    Route::get('get-application-details/{applicationId}', [ApiApplicationController::class, 'getApplicationDetails']);
});

///////////////////   user    //////////////////////////
Route::prefix('user')->group(function () {

    // checkin
    Route::prefix('checkin')->middleware('check_user_status')->group(function () {
        Route::post('create', [ApiCheckinController::class, 'insert']);

        Route::get('/checkin-all', [ApiCheckinController::class, 'checkinAll']);
        Route::get('/delete-all', [ApiCheckinController::class, 'deleteAll']);
    });

    // checkout
    Route::prefix('checkout')->middleware('check_user_status')->group(function () {
        Route::post('create', [ApiCheckoutController::class, 'insert']);
    });

    // calendar
    Route::prefix('calendar')->middleware('check_user_status')->group(function () {
        Route::get('calendar', [ApiCalendarController::class, 'calendar']);
    });

    // application
    Route::prefix('application')->middleware('check_user_status')->group(function () {
        Route::post('create', [ApiApplicationController::class, 'create']);
        Route::get('get-receiver/{employeeId}', [ApiApplicationController::class, 'getReceiver']);
    });

    // document
    Route::prefix('document')->middleware('check_user_status')->group(function () {
        Route::post('create', [ApiDocumentController::class, 'create']);
    });

    // salary
    Route::prefix('salary')->middleware('check_user_status')->group(function () {
        Route::get('get-salary', [ApiSalaryController::class, 'getSalary']);
        Route::get('get-last-salary', [ApiSalaryController::class, 'getLastSalary']);
        Route::get('get-salary-by-notification', [ApiSalaryController::class, 'getSalaryByNotification']);
        Route::get('calculate-salary', [ApiSalaryController::class, 'calculateSalary']);
    });

    // over-time
    Route::prefix('over-time')->middleware('check_user_status')->group(function () {
        Route::get('get-over-time', [ApiOverTimeController::class, 'getOverTime']);
    });

    // notification
    Route::prefix('notification')->middleware('check_user_status')->group(function () {
        Route::get('get-notification', [ApiNotificationController::class, 'getNotification']);
        Route::get('get-notification-details/{id}', [ApiNotificationController::class, 'getNotificationDetails']);
    });

    // test
    Route::prefix('test')->middleware('check_user_status')->group(function () {
        Route::get('get-all-tests', [ApiTestController::class, 'getAllTests']);
        Route::get('get-test-details/{employeeTestId}', [ApiTestController::class, 'getTestDetails']);
        Route::get('get-employee-tests', [ApiTestController::class, 'getEmployeeTests']);
        Route::post('save-employee-test', [ApiTestController::class, 'saveEmployeeTest']);
        Route::get('get-tests-score/{testId}', [ApiTestController::class, 'getTestsScore']);
        Route::patch('begin-test/{employeeTestId}', [ApiTestController::class, 'beginTest']);
    });

    // room
    Route::prefix('room')->middleware('check_user_status')->group(function () {
        Route::post('create', [ApiRoomController::class, 'createRoom']);
        Route::get('get-room-approved', [ApiRoomController::class, 'getRoomApproved']);
        Route::get('get-room-pending', [ApiRoomController::class, 'getRoomPending']);
        Route::get('search', [ApiRoomController::class, 'search']);
    });

    // task
    Route::prefix('task')->middleware('check_user_status')->group(function () {
        Route::post('create', [ApiTaskController::class, 'createTask']);
        Route::get('get-by-participant', [ApiTaskController::class, 'getTaskByParticipant']);
        Route::get('get-tasks', [ApiTaskController::class, 'getTasks']);
        Route::get('search', [ApiTaskController::class, 'search']);
    });

    // participant
    Route::prefix('participant')->middleware('check_user_status')->group(function () {
        Route::post('create', [ApiParticipantController::class, 'createParticipant']);
        Route::patch('approved-participant/{participantId}', [ApiParticipantController::class, 'updateStatusParticipant']);
    });

    // task-participant
    Route::prefix('task-participant')->middleware('check_user_status')->group(function () {
        Route::post('create', [ApiTaskParticipantController::class, 'createTaskParticipant']);
    });
});

/////////////////////////    admin    //////////////////////////
Route::prefix('admin')->group(function () {

    // blog
    Route::prefix('blog')->middleware('check_user_status')->group(function () {
        Route::post('create', [ApiBlogController::class, 'create']);
    });

    // document
    Route::prefix('document')->middleware('check_user_status')->group(function () {
        Route::get('get-by-department/{employeeId}/{departmentId}', [ApiDocumentController::class, 'getByDepartment']);
        Route::get('get-by-company/{employeeId}', [ApiDocumentController::class, 'getByCompany']);
    });

    // application
    Route::prefix('application')->middleware('check_user_status')->group(function () {
        Route::get('get-by-department/{employeeId}/{departmentId}', [ApiApplicationController::class, 'getByDepartment']);
        Route::get('get-by-company/{employeeId}', [ApiApplicationController::class, 'getByCompany']);
        Route::patch('update-status', [ApiApplicationController::class, 'updateStatus']);
        Route::get('get-by-approve-id', [ApiApplicationController::class, 'getByApproveId']);
        Route::get('get-approve-application', [ApiApplicationController::class, 'getApproveApplication']);
        Route::get('get-pending-application', [ApiApplicationController::class, 'getPendingApplication']);
    });

    // notification
    Route::prefix('notification')->middleware('check_user_status')->group(function () {
        Route::get('get-approve', [ApiNotificationController::class, 'getApprove']);
        Route::patch('update-seen', [ApiNotificationController::class, 'updateSeen']);
    });

    // employee
    Route::prefix('employee')->middleware('check_user_status')->group(function () {
        Route::get('get-employee-online', [ApiEmployeeController::class, 'getEmployeeOnline']);
        Route::get('get-employee-dayoff', [ApiEmployeeController::class, 'getEmployeeDayoff']);
        Route::get('get-home-admin', [ApiEmployeeController::class, 'getHomeAdmin']);
    });
});


// tuvi
Route::prefix('tuvi')->group(function () {
    Route::get('xemtuoikethon/{age}/{sex}/{year}', [TuViController::class, 'xemtuoikethon'])->middleware('cors');
});

