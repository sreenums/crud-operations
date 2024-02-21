<?php

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

Route::get('/', function () {
    return view('welcome',['users' => User::latest()->with(['address'])->get()]);      // ->with(['address'])
});

Route::get('/addnew',function(){
    return view('registration');
});

Route::post('/submit-registration','UserController@addUser');

Route::get('/edit/{userid}',function($user){
    return view('edit',['user' => User::find($user)]);
});

Route::post('/user-modify/{userid}','UserController@modifyUser');
Route::delete('/{id}', 'UserController@deleteUser')->name('users.delete');

Route::get('/view-address/{uid}',function($uid){
    return view('addresses',['addresses' => Address::all()->where('user_id',$uid)]);
});
