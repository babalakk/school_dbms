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

Route::get('/', function () {return redirect('/main');});
Route::get('/login',function () {return view('login');});
Route::post('/login','UserController@login');
Route::get('/logout','UserController@logout');
Route::get('/main',function () {
	return (Session::get('user_name')=='')?redirect('login'):view('main');
	});
Route::get('/search',function () {return view('search');});


Route::get('/add','DataController@view');
Route::get('/add/new_category/{office}/{parent_id}','DataController@newCategory');
Route::get('/add/new_office','DataController@newOffice');
Route::post('/add/add_category','DataController@addCategory');
Route::post('/add/add_office','DataController@addOffice');
Route::get('/add/delete_category/{c_id}','DataController@deleteCategory');
Route::get('/add/delete_office/{office}','DataController@deleteOffice');
Route::get('/getdata','DataController@getData');