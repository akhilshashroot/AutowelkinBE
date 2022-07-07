<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| All URL are prefixed with /admin (Example: /admin/set)
| All Route names are prefixed with admin. (Example: admin.set)
| This route is accessible for logged in users only
|
*/

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
