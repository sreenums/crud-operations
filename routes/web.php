<?php

use App\Http\Controllers\UserController;
use App\Models\Address;
use Illuminate\Support\Facades\Route;
use App\Models\User;
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

Route::get('/', 'UserController@index');

Route::resource('/users', UserController::class);

Route::get('/view-address/{uid}',function($uid){
    return view('addresses',['addresses' => Address::all()->where('user_id',$uid)]);
});
