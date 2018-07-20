<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\user;
use App\user_office;
use App\office;
use App\semester;

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
			
			
			/* check semester and add new entry */
			/*
			$y = 0;
			$m = 0;
			$date_y = intval(date('Y'));
			$date_m = intval(date('m'));
			if($date_m==1)
			{
				$y = $date_y - 1912;
				$m = 2;
			}
			else if($date_m>=2 && $date_m<=7)
			{
				$y = $date_y - 1911;
				$m = 1;
			}
			else if($date_m >=8 && $date_m<=12)
			{
				$y = $date_y - 1911;
				$m = 2;
			}
			$s = semester::where('value',$y.'-'.$m)->first();
			if(!$s)
			{
				$ns = new semester;
				$ns->value = $y.'-'.$m;
				$ns->save();
			}
			*/			
			
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
	
	public function setting()
	{
		if(Session::get('user_type') != 'superadmin' )
		{
			$admin_office = (user_office::where('user_id',Session::get('user_id'))->first())['office'];
		}
		else
		{
			$admin_office = '';
		}
		
		// get semester list
		$semester = semester::all();
		
		// get user, office list
		$r = user::all();
		$users = [];
		foreach($r as $key => $u)
		{
			$office = (user_office::where('user_id',$u->id)->first())['office'];
			if($admin_office && $admin_office != $office)
			{
				continue;
			}
			
			$users[$key]['data'] = $u;
			$users[$key]['office'] = $office;
		}
		$offices = office::all();
		
		return view('/setting',['users'=>$users,
								'offices'=>$offices,
								'semester'=>$semester]);
	}
	
	public function addSemester(Request $request)
	{
		if($request['semester_section']!=""){
			$value = $request['semester_year'].'-'.$request['semester_section'];
		}else{
			$value = $request['semester_year'];

		}
		
		//check duplicate and save
		if(!semester::where('value','=',$value)->exists())
		{
			$semester = new semester;
			$semester->value = $value;
			$semester->save();
		}
		
		return redirect('/setting');
	}

	public function addUser(Request $request)
	{
		$user = new user;
		$user->type = $request['user_type'];
		$user->name = $request['user_name'];
		$user->password = md5($request['user_password']);
		$user->save();
		
		$u_office = new user_office;
		$u_office->office = $request['user_office'];
		$u_office->user_id = $user->id;
		$u_office->save();
	
		
		return redirect('/setting');
	}
	
	public function deleteUser($id)
	{
		if(!isset($id) && $id == '')
		{
			return redirect('/setting');
		}
		
		$u_office = user_office::where('user_id',$id)->get();
		foreach($u_office as $uo)
		{
			$uo->delete();
		}
		
		$user = user::find($id);
		if($user){$user->delete();}
		
		return redirect('/setting');
	}
	
	public function changePassword(Request $request,$id)
	{
		$user = user::find($id);
		if($user)
		{
			$user->password = md5($request['new_password']);
			$user->save();
		}
		
		return redirect('/setting');
	}
	
}