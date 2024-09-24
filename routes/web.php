<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CheckinController;
use App\Http\Controllers\Admin\CheckoutController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DayoffController;
use App\Http\Controllers\Admin\EarlyLateController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ErrorController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OvertimeController;
use App\Http\Controllers\Admin\SalaryController;
use App\Http\Controllers\Admin\TestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/sign-in', [AuthController::class, 'signIn'])->name('signIn');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('admin.auth');

    //error
    Route::prefix('error')->middleware('admin.auth')->group(function () {
        Route::get('/error-403', [ErrorController::class, 'Error403'])->name('error-403');
    });

    //dashboard
    Route::prefix('dashboard')->middleware('admin.auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    });

    //salary
    Route::prefix('salary')->middleware('admin.auth', 'admin:3,6,11')->group(function () {
        Route::get('/', [SalaryController::class, 'indexImport'])->name('salary.index');
        Route::post('/export-daywork', [SalaryController::class, 'exportDaywork'])->name('export.daywork');
        Route::post('/import-daywork', [SalaryController::class, 'importDaywork'])->name('import.daywork');
    });

    //orders
    Route::prefix('orders')->middleware(['admin.auth', 'admin:3,8,11'])->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/store', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/delete/{id}', [OrderController::class, 'delete'])->name('orders.delete');
        Route::post('/search-date', [OrderController::class, 'searchDate'])->name('orders.search-date');
        Route::get('generate-pdf/{id}', [OrderController::class, 'generatePDF'])->name('orders.generatePDF');

        //medicine
        Route::post('/create-medicine', [OrderController::class, 'createMedicine'])->name('orders.create-medicine');

        //type medicine
        Route::post('/create-type-medicine', [OrderController::class, 'createTypeMedicine'])->name('orders.create-type-medicine');

        //uses
        Route::post('/create-uses', [OrderController::class, 'createUses'])->name('orders.create-uses');

        //uses
        Route::post('/create-dosage', [OrderController::class, 'createDosage'])->name('orders.create-dosage');

        //advice
        Route::post('/create-advice', [OrderController::class, 'createAdvice'])->name('orders.create-advice');
    });

    //employee
    Route::prefix('employee')->middleware(['admin.auth', 'admin:3,6,11'])->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('employee.index');
        Route::get('/create', [EmployeeController::class, 'create'])->name('employee.create');
        Route::post('/store', [EmployeeController::class, 'store'])->name('employee.store');
        Route::get('/find/{id}', [EmployeeController::class, 'find'])->name('employee.find');
        Route::post('/update/{id}', [EmployeeController::class, 'update'])->name('employee.update');
        Route::post('/search', [EmployeeController::class, 'search'])->name('employee.search');
        Route::get('/employee-retired', [EmployeeController::class, 'indexRetired'])->name('employee.index-retired');
    });

    //checkin
    Route::prefix('checkin')->middleware(['admin.auth', 'admin:3,6,11'])->group(function () {
        Route::get('/', [CheckinController::class, 'index'])->name('checkin.index');
        Route::post('/search-date', [CheckinController::class, 'searchDate'])->name('checkin.search-date');
        Route::get('/create', [CheckinController::class, 'create'])->name('checkin.create');
        Route::post('/store', [CheckinController::class, 'store'])->name('checkin.store');
        Route::get('/edit/{id}', [CheckinController::class, 'edit'])->name('checkin.edit');
        Route::post('/update/{id}', [CheckinController::class, 'update'])->name('checkin.update');

        Route::get('/index-details', [CheckinController::class, 'indexDetails'])->name('checkin.index-details');
        Route::post('/details-checkin', [CheckinController::class, 'CheckinDetails'])->name('checkin.details-checkin');
    });

    //checkout
    Route::prefix('checkout')->middleware(['admin.auth', 'admin:3,6,11'])->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/search-date', [CheckoutController::class, 'searchDate'])->name('checkout.search-date');
        Route::get('/create', [CheckoutController::class, 'create'])->name('checkout.create');
        Route::post('/store', [CheckoutController::class, 'store'])->name('checkout.store');
        Route::get('/edit/{id}', [CheckoutController::class, 'edit'])->name('checkout.edit');
        Route::post('/update/{id}', [CheckoutController::class, 'update'])->name('checkout.update');

        Route::get('/index-details', [CheckoutController::class, 'indexDetails'])->name('checkout.index-details');
        Route::post('/details-checkout', [CheckoutController::class, 'CheckoutDetails'])->name('checkout.details-checkout');
    });

    //notification
    Route::prefix('notification')->middleware(['admin.auth', 'admin:3,6,11'])->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notification.index');
        Route::post('/push-notification-to-all', [NotificationController::class, 'pushNotificationToAll'])->name('notification.push-notification-to-all');
    });

    //test
    Route::prefix('test')->middleware(['admin.auth', 'admin:3,6,11'])->group(function () {
        // test
        Route::get('/', [TestController::class, 'indexTests'])->name('test.index');
        Route::get('/create-test', [TestController::class, 'createTest'])->name('test.create');
        Route::post('/store-test', [TestController::class, 'storeTest'])->name('test.store');

        // question
        Route::get('/questions', [TestController::class, 'indexQuestion'])->name('question.index');
        Route::get('/create-question', [TestController::class, 'createQuestion'])->name('question.create');
        Route::post('/store-question', [TestController::class, 'storeQuestion'])->name('question.store');

        // answer
        Route::get('/answers', [TestController::class, 'indexAnswer'])->name('answer.index');
        Route::get('/create-answer', [TestController::class, 'createAnswer'])->name('answer.create');
        Route::post('/store-answer', [TestController::class, 'storeAnswer'])->name('answer.store');

        // test-all
        Route::get('/create-all-test/{testId}', [TestController::class, 'createAllTest'])->name('test.create-all');
        Route::post('/store-all-test', [TestController::class, 'storeAllTest'])->name('test.store-all');

        // mark
        Route::get('/index-mark', [TestController::class, 'indexMark'])->name('mark.index');
        Route::get('/caculate-score/{employeeAnswerId}', [TestController::class, 'indexCaculateScore'])->name('caculate.score');
        Route::post('/store-caculate-score', [TestController::class, 'caculateScore'])->name('store-caculate-score');

        // employee-test
        Route::get('/create-employee-test', [TestController::class, 'createEmployeeTest'])->name('create-employee-test');
        Route::post('/store-employee-test', [TestController::class, 'storeEmployeeTest'])->name('store-employee-test');

        // employee-test
        Route::get('/index-employee-answer/{testId}', [TestController::class, 'indexEmployeeAnswer'])->name('index-employee-answer');
        Route::get('/create-employee-answer/{testId}/{employeeId}', [TestController::class, 'createEmployeeAnswer'])->name('create-employee-answer');
        Route::post('/store-employee-answer', [TestController::class, 'storeEmployeeAnswer'])->name('store-employee-answer');

        Route::get('/index-employee-tests/{testId}', [TestController::class, 'indexEmployeeTests'])->name('index-employee-tests');
        Route::get('/details-employee-tests/{employeeId}/{testId}', [TestController::class, 'employeeTestDetails'])->name('details-employee-tests');
    });

    //blog
    Route::prefix('blog')->middleware(['admin.auth', 'admin:3,6,11'])->group(function () {
        Route::get('/index', [BlogController::class, 'index'])->name('blog.index');
        Route::get('/create', [BlogController::class, 'create'])->name('blog.create');
        Route::post('/store', [BlogController::class, 'store'])->name('blog.store');
        Route::get('/edit/{id}', [BlogController::class, 'edit'])->name('blog.edit');
        Route::post('/update/{id}', [BlogController::class, 'update'])->name('blog.update');
        Route::post('/delete/{id}', [BlogController::class, 'delete'])->name('blog.delete');
    });

    //dayoff
    Route::prefix('dayoff')->middleware(['admin.auth', 'admin:3,6,11'])->group(function () {
        Route::get('/', [DayoffController::class, 'index'])->name('dayoff.index');
        Route::post('/search-date', [DayoffController::class, 'searchDate'])->name('dayoff.search-date');
    });

    //overtime
    Route::prefix('overtime')->middleware(['admin.auth', 'admin:3,6,11'])->group(function () {
        Route::get('/', [OvertimeController::class, 'index'])->name('overtime.index');
        Route::post('/search-date', [OvertimeController::class, 'searchDate'])->name('overtime.search-date');
    });

    //early-late
    Route::prefix('early-late')->middleware(['admin.auth', 'admin:3,6,11'])->group(function () {
        Route::get('/', [EarlyLateController::class, 'index'])->name('early-late.index');
        Route::post('/search-date', [EarlyLateController::class, 'searchDate'])->name('early-late.search-date');
    });
});
