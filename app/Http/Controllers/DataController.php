<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\data;
use App\category;
use App\user;
use App\office;
use App\user_office;

use Session;

class DataController extends Controller
{
    public function view(Request $request)
    {
		//if(Session::get('user_name')!='admin'&&Session::get('user_name')!='superadmin'){return redirect('main');}
		
		$tree = [];
		$offices = user_office::where('user_id',Session::get('user_id'))->get();
		foreach($offices as $key => $o)
		{
			$tree[$key]['name'] = $o->office;
			$tree[$key]['categorys'] = [];
			$categorys = category::where('office',$o->office)->where('parent_id','0')->get();
			foreach($categorys as $key_c => $c)
			{
				$tree[$key]['categorys'][$key_c]['name'] = $c->name;
				$tree[$key]['categorys'][$key_c]['id'] = $c->id;
				$tree[$key]['categorys'][$key_c]['child'] = [];
				$sub_categorys = category::where('parent_id',$c->id)->get();
				foreach($sub_categorys as $subc_key => $subc)
				{
					$tree[$key]['categorys'][$key_c]['child'][$subc_key]['name'] = $subc->name;
					$tree[$key]['categorys'][$key_c]['child'][$subc_key]['id'] = $subc->id;
				}
			}
		}
			
		return view('create',['offices'=>$tree]);
    }
	
	public function getData(Request $request)
	{
		$data = array();
		if($request['type']=='office')
		{
			$categorys = category::where('office',$request['id'])->get();
			$id_arr = array();
			foreach($categorys as $key => $c)
			{
				array_push($id_arr,$c->id);
			}
			$data = data::whereIn('category_id',$id_arr)->get();
		}
		else if($request['type']=='category')
		{
			$categorys = category::where('parent_id',$request['id'])->get();
			$id_arr = array();
			foreach($categorys as $key => $c)
			{
				array_push($id_arr,$c->id);
			}
			array_push($id_arr,$request['id']);
			
			$data = data::whereIn('category_id',$id_arr)->get();
		}
		else
		{
			return '伺服器錯誤';
		}
		return view('create_data',['data'=>$data]);
	}
	
	public function newCategory($office,$parent_id)
	{
		$parent = category::find($parent_id);
		return view('new_category',['office'=>$office,'parent'=>$parent]);
	}
		
	public function newOffice()
	{
		return view('new_office');
	}
	
	public function addCategory(Request $request)
	{
		if($request['office'] && $request['new_category'])
		{
			$category = new category;
			$category->name = $request['new_category'];
			$category->office = $request['office'];
			if(isset($request['parent_id']) && $request['parent_id']!='' )
			{
				$category->parent_id = $request['parent_id'];
			}
			else
			{
				$category->parent_id = 0;
			}
			
			$category->save();
			
			return '新增成功';
		}
		else
		{
			return '新增失敗';
		}
	}	
	
	public function addOffice(Request $request)
	{
		if($request['new_office'] && !office::where('value',$request['new_office'])->exists() )
		{
			$office = new office;
			$office->value = $request['new_office'];
			$office->save();
			
			return '新增成功';
		}
		else
		{
			return '新增失敗';
		}
	}
	
	public function deleteCategory($c_id)
	{
		if(!isset($c_id) || $c_id == 0)
		{
			return redirect('/add');
		}
		
		/* delete data */
		$children = category::where('parent_id',$c_id)->get();
		foreach($children as $child)
		{
			data::where('category_id',$child->id)->delete();
		}
		data::where('category_id',$c_id)->delete();
		
		/* delete category */
		category::where('parent_id',$c_id)->delete();
		category::find($c_id)->delete();
		
		return redirect('/add');
	}	
	
	public function deleteOffice($office)
	{
		if(!isset($office) || $office == '')
		{
			return redirect('/main');
		}
		
		$categorys = category::where('office',$office)->where('parent_id',0)->get();
		foreach($categorys as $c){deleteCategory($c->id);}
		user_office::where('office',$office)->delete();
		office::where('value',$office)->delete();
		
		return redirect('/add');
	}
	
	
}