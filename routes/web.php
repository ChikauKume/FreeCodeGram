<?php
use App\Mail\NewUserWelcomeMail;

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

Auth::routes();

Route::get('/email', function(){
    return new NewUserWelcomeMail();
});

Route::post('follow/{user}', 'FollowsController@store');

Route::get('/', function(){
    return view('welcome');
});
Route::get('/home', 'ProfileController@home');

Route::get('/p/create','PostsController@create');
Route::get('/p/{post}','PostsController@show');
Route::post('/p','PostsController@store');

Route::get('/profile/{user}', 'ProfileController@index')->name('profiles.show');
Route::get('/profile/{user}/edit','ProfileController@edit')->name('profiles.edit');
Route::patch('/profile/{user}','ProfileController@update')->name('profiles.update');