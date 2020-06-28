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
    return view('welcome');
});

// 

Route::post( '/sendmessage' , 'ChatController@sendMessage')->name('sendmessage');

Route::post( '/messageseen' , 'ChatController@messageSeen')->name('messageseen');

Route::post( '/messagecount' , 'ChatController@messagecount')->name('messagecount');

Route::get( '/message/{sender}/to/{reciever}' , 'ChatController@MessageBox')->name('messagebox');

Route::get( '/fetchMessage/{id}' , 'ChatController@fetchMessage')->name('fetchMessage');


Route::post('/ajax/message/old', 'ChatController@oldMessage')->name('ajax.old.message');

Auth::routes();

Route::get('/message', 'ChatController@getUser')->name('message');

Route::get('/fetchUser', 'ChatController@getUser')->name('fetchUser');

// 


Route::get('/home', 'HomeController@index')->name('home');

Route::post('/autocomplete', 'GuessContoller@searchSuggestion')->name('autocomplete');


Route::get('/home', 'HomeController@home')->name('home');
Route::get('/profile', 'HomeController@profile')->name('profile');
Route::get('/edit/profile', 'HomeController@editProfile')->name('editProfile');
Route::get('/setting', 'HomeController@setting')->name('setting');
Route::get('/help', 'HomeController@help')->name('help');

Route::get('/result', 'GuessContoller@index')->name('result');

Route::get('/edit/book/{id}', 'BookContoller@edit')->name('EditBookForm');
Route::get('/new/book', 'BookContoller@create')->name('bookUploadForm');
Route::post('/new/book', 'BookContoller@store')->name('bookUploadStore');
Route::get('/delete/book/{id}', 'BookContoller@destroy')->name('bookDelete');

Route::get('/new/location', 'LocationContoller@create')->name('locationUploadForm');
Route::post('/new/location', 'LocationContoller@store')->name('locationUploadStore');
Route::get('/delete/location/{id}', 'LocationContoller@destroy')->name('locationDeleteStore');

