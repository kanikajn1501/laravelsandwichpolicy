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

Route::get('/', 'EmployeeController@index');
Route::get('apply_leave/{id}', 'EmployeeController@apply_leave')->name('apply_leave');
Route::post('/submit_leave', 'EmployeeController@approvedLeave')->name('submit_leave');