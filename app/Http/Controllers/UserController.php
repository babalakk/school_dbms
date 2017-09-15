<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\user;

use Session;

class UserController extends Controller
{
    public function login(Request $request)
    {
		$user = user::where('name',$request['name'])->where('password',md5($request['password']))->get();
		if(isset($user[0]))
		{
			$user = $user[0];
			
			Session::put('user_name', $user->name);
			Session::put('user_id', $user->id);
			Session::put('user_type', $user->type);
			
			return redirect('main');
		}
		else
		{
			return view('login',['msg'=>'帳號/密碼輸入錯誤']);
		}
    }
    public function logout(Request $request)
    {
		
		Session::forget('user_name');
		Session::forget('user_id');
		Session::forget('user_type');
		
		return redirect('/login');
    }
}