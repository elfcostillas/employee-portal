<?php

use App\Livewire\DTR\FTP\CreateFTPRequest;
use App\Livewire\DTR\FTP\EditFTPRequest;
use App\Livewire\DTR\FTP\MainComponent as FTPMainComponent;
use App\Livewire\DTR\FTPApproval\MainComponent as FTPApprovalMainComponent;
use App\Livewire\DTR\Logs\MainComponent as LogsMainComponent;
use App\Livewire\IndexPage;
use App\Livewire\LeaveCredits\MainComponent;
use App\Livewire\Leaves\CreateLeaveRequest;
use App\Livewire\Leaves\EditLeaveRequest;
use App\Livewire\Leaves\LeaveApproval;
use App\Livewire\Leaves\LeaveApprovalViewing;
use App\Livewire\Leaves\LeaveBalance;
use App\Livewire\Leaves\LeaveBalanceDetailed;
use App\Livewire\Leaves\MainComponent as LeavesMainComponent;
use App\Livewire\UserManagement\MainComponent as UserManagementMainComponent;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

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

Route::get('/', function () {
  
    return view('welcome');
});


Route::get('dashboard', function () {
    return redirect('/');
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get('employee-portal/livewire/livewire.js', $handle);
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/employee-portal/livewire/update', $handle);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/',IndexPage::class );


    // Route::get('dtr/ftp-request',FTPMainComponent::class);
    // Route::get('dtr/ftp-request-create',CreateFTPRequest::class);
    // Route::get('dtr/ftp-request-edit/{id}',EditFTPRequest::class);
    // Route::get('dtr/ftp-approval',FTPApprovalMainComponent::class);

    Route::get('dtr/view-dtr',LogsMainComponent::class);

    Route::prefix('dtr')->group(function(){
        Route::get('ftp-request',FTPMainComponent::class);
        Route::get('ftp-request-create',CreateFTPRequest::class);
        Route::get('ftp-request-edit/{id}',EditFTPRequest::class);
        Route::get('ftp-approval',FTPApprovalMainComponent::class);
    });


    Route::prefix('leave')->group(function(){
        Route::get('leave-request',LeavesMainComponent::class);
        Route::get('leave-request-create',CreateLeaveRequest::class);
        Route::get('leave-request-edit/{id}',EditLeaveRequest::class);

        Route::get('leave-approval',LeaveApproval::class);
        Route::get('leave-approval-view/{id}',LeaveApprovalViewing::class);

        Route::get('leave-balance',LeaveBalance::class);
        Route::get('leave-balance-detailed',LeaveBalanceDetailed::class);

    });


    Route::prefix('management')->group(function(){
        Route::get('user-rights',UserManagementMainComponent::class);
    });


});
