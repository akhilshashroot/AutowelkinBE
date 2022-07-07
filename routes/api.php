<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

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
// Route::post('/login', [LoginController::class, 'login'])->name('login.user');

// Route::prefix('admin')->group(function() {
//     //Route::get('/', 'Admin\AdminController@index')->name('admin.dashboard');
//     Route::get('/', 'Admin\AdminController@index')->name('admin.dashboard');
//     Route::get('/viewdepts', 'Admin\DepartmentController@index')->name('admin.viewdepts');
//     Route::post('/newdept', 'Admin\DepartmentController@store')->name('admin.newdept');
//     Route::put('/editdepts/{dept_id}', 'Admin\DepartmentController@update')->name('admin.editdepts');
//     Route::delete('/delete_depts/{dept_id}', 'Admin\DepartmentController@destroy')->name('admin.delete_depts');
//     Route::resource('teams', 'Admin\TeamController');
//     Route::get('userlist', 'Admin\EmployeeController@index')->name('admin.userlist');
//     Route::resource('attendance', 'Admin\AttendanceController');
//    }) ;
//    Route::group(['prefix' => 'user'],function(){

//     Route::post('/login', [LoginController::class, 'login'])->name('login.user');

// Route::group( ['middleware' => ['auth:user','scope:user'] ],function(){
//           Route::get('/viewdepts', 'Admin\DepartmentController@index')->name('admin.viewdepts');
          
//        });
// });