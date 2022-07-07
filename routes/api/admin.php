<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\RequestController;
use App\Http\Controllers\Admin\DailyReportController;
use App\Http\Controllers\Admin\WeeklyReportController;
use App\Http\Controllers\Admin\MonthlyReportController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ShiftWeekController;

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

///url example api/admin/login
    Route::get('/test',function(){
       return "test route admin";
    });
    Route::post('admin_user/login',[LoginController::class, 'adminUserLogin'])->name('adminUser');

    Route::post('admin/login',[LoginController::class, 'adminLogin'])->name('adminLogin');
    Route::group( ['prefix' => 'admin','middleware' => ['auth:admin-api','scopes:admin'] ],function(){
       // authenticated admin routes here 
       Route::get('/viewdepts', [DepartmentController::class, 'index'])->name('admin.viewdepts');
       Route::post('/newdept', 'Admin\DepartmentController@store')->name('admin.newdept');
       Route::put('/editdepts/{dept_id}', 'Admin\DepartmentController@update')->name('admin.editdepts');
       Route::delete('/delete_depts/{dept_id}', 'Admin\DepartmentController@destroy')->name('admin.delete_depts');
       Route::resource('teams', 'Admin\TeamController');
       Route::resource('designation', 'Admin\DesignationController');
       Route::resource('notification', 'Admin\NotificationController');
       Route::resource('settings', 'Admin\SettingsController');
       //Route::resource('attendance', 'Admin\AttendanceController');
       Route::post('attendance', 'Admin\AttendanceController@index');
       Route::resource('request', 'Admin\RequestController');
       Route::resource('employee', 'Admin\EmployeeController');
       Route::get('get_resigned', 'Admin\EmployeeController@showResigned');
       Route::post('activate_notice_period/{id}', 'Admin\EmployeeController@updateNoticePeriod');
       Route::post('make_core_employee/{id}', 'Admin\EmployeeController@updateCoreEmployee');
       Route::post('manage_wfh/{id}', 'Admin\EmployeeController@manageWfh');
       Route::post('manage_upload/{id}', 'Admin\EmployeeController@manageUpload');
       Route::get('performance/{id}', 'Admin\PerformanceController@performance');
       Route::get('get_evaluation/{id}', 'Admin\PerformanceController@evaluationHistory');
       Route::put('performance_update/{id}', 'Admin\PerformanceController@updatepoint');
       Route::post('overtime_reset', 'Admin\PerformanceController@overtime_reset');     
       Route::post('update_mandatory', 'Admin\PerformanceController@updateMandatory');
       Route::post('manage_warning', 'Admin\PerformanceController@manage_warning');
       Route::get('getEmployeeSkillSet/{user_id}', 'Admin\EmployeeController@getEmployeeSkillSet');
       Route::post('addNewSkill', 'Admin\EmployeeController@addNewSkill');
       Route::post('changeSkillStatus', 'Admin\EmployeeController@changeSkillStatus');
       Route::delete('removeSkill/{skill_id}', 'Admin\EmployeeController@removeSkill');
       Route::resource('announcement', 'Admin\AnnouncementController');
       Route::get('/notice_board_details/{notice_id}/{type}', 'Admin\AnnouncementController@editList');
       Route::resource('interview', 'Admin\InterviewController');
       Route::post('interview_update/{id}', 'Admin\InterviewController@interviewUpdate');
       Route::post('/addcomment', 'Admin\InterviewController@add_new_comment');
       Route::get('resume/{id}', 'Admin\InterviewController@download');
       Route::get('get_employees', 'Admin\MasterController@get_employees');
       Route::resource('task_management', 'Admin\TaskerController');
       Route::post('taskmanagement', 'Admin\TaskerController@taskUpdate');
       Route::get('get_teams', 'Admin\MasterController@get_team');
       Route::get('get_departments', 'Admin\MasterController@get_department');
       Route::put('/request/approve/{lv_id}', 'Admin\RequestController@approve');
       Route::put('/request/reject/{lv_id}', 'Admin\RequestController@reject');
       Route::delete('/request/delete/{lv_id}', 'Admin\RequestController@destroy');
       Route::post('/logout', [LoginController::class, 'logout']);
       Route::get('/daily_datas/{user_id}/{month_pick}', [DailyReportController::class, 'daily_datas']);
       Route::get('/getjd/{dep_id}', [DailyReportController::class, 'getjd']);
       Route::post('/changejd', [DailyReportController::class, 'changejd']);
       Route::post('/add_new_act', [DailyReportController::class, 'add_new_act']);
       Route::delete('/delete_Activity/{activity_id}', 'Admin\DailyReportController@destroy');
       //Route::resource('weekly_report', 'Admin\WeeklyReportController');
       Route::get('weekly_report/{user_id}/{month_pick}', 'Admin\WeeklyReportController@index');
       Route::post('weekly_report', 'Admin\WeeklyReportController@store');
       Route::delete('/weekly_report/{activity_id}', 'Admin\WeeklyReportController@destroy');
       Route::get('/getweeklyactivity/{dep_id}', [WeeklyReportController::class, 'getweeklyactivity']);
       //Route::resource('monthly_report', 'Admin\MonthlyReportController');
       Route::get('monthly_report/{user_id}/{month_pick}', 'Admin\MonthlyReportController@index');
       Route::post('monthly_report', 'Admin\MonthlyReportController@store');
       Route::delete('/monthly_report/{activity_id}', 'Admin\MonthlyReportController@destroy');
       Route::get('/getmonthlyactivity/{dep_id}', [MonthlyReportController::class, 'getmonthlyactivity']);
       Route::post('/monthly_datas', [MonthlyReportController::class, 'monthly_datas']);
       Route::resource('inventory', 'Admin\InventoryController');
       Route::resource('project-room', 'Admin\ProjectController');
       Route::get('shiftweek_manager/getweeks/{team_id}', [ShiftWeekController::class, 'getweeks']);
       Route::get('shiftweek_manager/loadPreviousShift/{week_id}', [ShiftWeekController::class, 'loadPreviousShift']);
    });


    //Hashbook common routes
    Route::resource('discussion', 'Admin\HashBookController');
    Route::get('get_authors', 'Admin\HashBookController@get_authors');
    Route::post('create_subtopic', 'Admin\HashBookController@create_subtitle');
    Route::post('create_comment', 'Admin\HashBookController@post_comments');
    Route::get('discussion_details/{id}/{user_id}', 'Admin\HashBookController@details');