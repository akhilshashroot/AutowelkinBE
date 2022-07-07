<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\User\AttendanceController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
///url example api/user/login

    Route::post('user/login',[LoginController::class, 'userLogin'])->name('userLogin');
    Route::post('password/email',  [ForgotPasswordController::class, 'forgot']);
    Route::post('reset-password/{token}', [ForgotPasswordController::class, 'updatePassword'])->name('reset-password');

    Route::group( ['prefix' => 'user','middleware' => ['auth:user-api','scopes:user'] ],function(){
       // authenticated user routes here 
       Route::post('/logout', [LoginController::class, 'logout']);
       Route::get('user_data/{id}','User\UserController@index');
       Route::put('user_data/{id}','User\UserController@update');
       Route::get('getuserWorkandBreaktime/{id}','User\UserController@getuserWorkandBreaktime');
       Route::resource('request_leave', 'User\LeaveRequestController');
       Route::get('attendancelog/{user_id}','User\AttendanceController@index');
       Route::post('punchin','User\AttendanceController@punchin');
       Route::post('breaktime','User\AttendanceController@breaktime');
       Route::post('punchout','User\AttendanceController@punchout');
       Route::post('workscreenshort','User\AttendanceController@workscreenshort');
       Route::resource('score', 'User\ScoreController');
       Route::post('get_evaluation_details','User\ScoreController@get_evaluation_details');
       Route::post('get_evaluation_history','User\ScoreController@get_evaluation_history');
       Route::resource('get_task', 'User\TaskController');
       Route::post('post_comment', 'User\TaskController@post_comment');
       Route::get('getTeamMembers/{team_id}','User\ShiftManagerController@getteam_members');
       Route::post('createShift', 'User\ShiftManagerController@createShift');
       Route::get('loadShifts/{team_id}/{user_id}','User\ShiftManagerController@loadShift');
       Route::get('getWeeks/{team_id}','User\ShiftManagerController@getWeeks');
       Route::get('loadPreviousShift/{week_id}/{user_id}','User\ShiftManagerController@loadPreviousShift');
       Route::put('editShifts/{shiftid}','User\ShiftManagerController@editShifts');
       Route::post('swapShift', 'User\ShiftManagerController@swapShift');
       Route::get('previewShift/{team_id}','User\ShiftManagerController@previewShift');
       Route::delete('deleteSwap/{swap_id}/{user_id}', 'User\ShiftManagerController@deleteSwap');
       Route::post('updateComment/{week_id}','User\ShiftManagerController@updateComment');
       Route::resource('work_data', 'User\WorkSheetController');
       Route::post('ticket_data_save', 'User\WorkSheetController@ticketSaveBox');
       Route::get('get_saved_ticket_details/{user_id}', 'User\WorkSheetController@getTicketData');
       Route::put('update_ticket_response', 'User\WorkSheetController@updateTicketResponse');
       Route::post('skill_status_update', 'User\WorkSheetController@skillStatusUpdater');
       Route::get('skill_list/{user_id}', 'User\WorkSheetController@getSkillList');
       Route::get('ticket_count/{user_id}', 'User\WorkSheetController@getTicketCount');
       Route::get('weekly_data/{user_id}', 'User\WorkSheetController@weeklyData');
       Route::get('monthly_data/{user_id}', 'User\WorkSheetController@MonthlyData');
       Route::post('save_weekly_data', 'User\WorkSheetController@updateWeekly');
       Route::post('save_montly_data', 'User\WorkSheetController@updateMonthly');
       Route::post('work_report', 'User\WorkSheetController@getWorkReport');
       Route::post('notification', 'User\NotificationController@notification');
       Route::get('request_type/{id}', 'User\LeaveRequestController@requestDropdown');
       Route::get('get_employees', 'Admin\MasterController@get_employees');

    });