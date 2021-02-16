<?php

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
    //return view('Admin/index');
    return view('mails/welcome');
});
Route::resource('/fittech/exercise', 'Admin\ExerciseController');
Route::resource('/fittech/plan', 'Admin\PlanController');
Route::resource('/fittech/user', 'Admin\UserController');
