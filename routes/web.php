<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/threads', 'ThreadsController@index');
Route::get('/threads/create', 'ThreadsController@create');
Route::post('/threads', 'ThreadsController@store');
Route::get('/threads/{channel}/{thread}', 'ThreadsController@show');
Route::delete('/threads/{channel}/{thread}', 'ThreadsController@destroy');
Route::get('/threads/{channel}', 'ThreadsController@index');

Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store');

Route::post('/replies/{reply}/favorites', 'FavoritesController@store');

Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');



//Route::resource é usado pra resumir o que está comentado acima, ele recebe como primeiro parametro o nome que será usado no route
//em segundo parametro recebe a controller de destino, isso ocorre quando se trabalha com uma controller de resources (CRUD)
// Route::resource('threads', 'ThreadsController');







