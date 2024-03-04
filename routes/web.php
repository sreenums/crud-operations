<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authenticate;
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

//User
Route::get('/home', 'UserController@index')->name('user.home')->middleware(Authenticate::class);

Route::resource('/users', UserController::class)->middleware(Authenticate::class);
Route::get('/view-address/{uid}',function($uid){
    return view('addresses',['addresses' => Address::all()->where('user_id',$uid)]);
})->middleware(Authenticate::class);

//Login, Logout
Route::get('/', 'LoginController@loginForm')->name('login.index');
Route::post('/login', 'LoginController@login');
Route::get('/logot', 'LoginController@logout')->name('logout');
Route::get('/dashboard', function(){
    return view('dashboard');
})->name('dashboard')->middleware(Authenticate::class);

//For Posts
Route::resource('/posts', PostController::class)->middleware(Authenticate::class);

//For Comments
Route::get('/posts/{post}/comments', 'CommentController@showComments');
Route::post('/posts/{postId}','CommentController@addComment')->name('comment.save');

//For posts search
Route::get('/search', 'SearchController@search');