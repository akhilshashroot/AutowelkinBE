<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
 //   return view('welcome');
    return view('auth.login');

});
Route::get('resume/{id}', 'Admin\InterviewController@download')->name('resume.download');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::prefix('admin')->group(function() {
    Route::get('/login','Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    Route::get('logout/', 'Auth\AdminLoginController@logout')->name('admin.logout');
  
   }) ;

    Route::get('/login','Auth\LoginController@showLoginForm')->name('user.login');
    Route::post('user/login', 'Auth\LoginController@login')->name('user.login.submit');
    Route::get('logout', 'Auth\LoginController@logout')->name('user.logout');
    Route::get('user/dashboard', 'User\UserController@index')->name('user.dashboard');
 
Route::get('web/artisan/{command}/{param}', 'CommandController@index');
