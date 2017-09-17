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
Route::get('/add/new_data/{category_id}','DataController@newData');
Route::get('/add/new_file/{category_id}','DataController@newFile');
Route::post('/add/add_category','DataController@addCategory');
Route::post('/add/add_office','DataController@addOffice');
Route::post('/add/add_data','DataController@addData');
Route::post('/add/add_file','DataController@addFile');
Route::get('/add/delete_category/{c_id}','DataController@deleteCategory');
Route::get('/add/delete_data/{id}','DataController@deleteData');
Route::get('/add/delete_office/{office}','DataController@deleteOffice');
Route::get('/getdata','DataController@getData');

Route::get('/setting','UserController@setting');
Route::post('/setting/add_user','UserController@addUser');
Route::post('/setting/change_password/{id}','UserController@changePassword');
Route::get('/setting/delete_user/{id}','UserController@deleteUser');

Route::get('/storage/{filename}', function ($filename)
{
    $path = storage_path('public/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});