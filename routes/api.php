<?php

use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/posts', 'PostController@index')->name('allPosts');
Route::post('/post', 'PostController@store')->name('storePosts')->middleware('auth:api');
Route::get('/post/{post}', 'PostController@show')->name('showPost');
Route::post('/updatePost/{post}', 'PostController@update')->name('updatePost')->middleware('auth:api');
Route::delete('/post/{post}', 'PostController@destroy')->name('deletePost');

//USERS
Route::post('/register', 'AuthController@register')->name('register');
Route::post('/login', 'AuthController@login')->name('login');
Route::get('/user', 'AuthController@user')->name('user')->middleware('auth:api');
Route::get('/logout', 'AuthController@logout')->name('logout')->middleware('auth:api');

//Forgot Pass
Route::post('/forgot', 'ForgotController@forgot')->name('forgot');
Route::post('/reset', 'ForgotController@reset')->name('reset');
