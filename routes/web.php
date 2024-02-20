<?php

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

Route::get('/', function () {
    return view('welcome',['users' => User::latest()->get()]);      // ->with(['address'])
});

Route::get('/addnew',function(){
    return view('registration');
});

Route::post('/submit-registration','UserController@saveuser');

Route::get('/edit/{userid}',function($user){
    //ddd($user);
    return view('edit',['user' => User::find($user)]);
});
