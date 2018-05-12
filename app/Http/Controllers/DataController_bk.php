<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\data;
use App\data_attribute;
use App\category;
use App\user;
use App\office;
use App\user_office;
use App\semester;

use Session;

class DataController extends Controller
{
    public function view(Request $request)
    {
		//if(Session::get('user_name')!='admin'&&Session::get('user_name')!='superadmin'){return redirect('main');}
		
		$tree = [];
		$offices = [];
		if( Session::get('user_type') == 'superadmin' )
		{
			$r = office::all();
			foreach($r as $o){ array_push($offices,$o->value); }
		}
		else
		{
			$r = user_office::where('user_id',Session::get('user_id'))->get();
			foreach($r as $o){ array_push($offices,$o->office); }
			
			$r = office::where('shared',1)->get();
			foreach($r as $o){ 
				if(!in_array($o->value,$offices,true))
				{
					array_push($offices,$o->value); 
				}
			}
		}
		
		foreach($offices as $key => $o)
		{
			$tree[$key]['name'] = $o;
			$tree[$key]['categorys'] = [];
			$categorys = category::where('office',$o)->where('parent_id','0')->get();
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
		
	public function newData($category_id)
	{
		$category = category::find($category_id);
		$semesters = semester::orderBy('value','desc')->get();
		$attr_name_list = data_attribute::select('name')->groupby('name')->get();
		return view('new_data',['category_id'=>$category_id,
								'attr_name_list'=>$attr_name_list,
								'category_name'=>$category->name,
								'semesters'=>$semesters]);
	}		
	
	public function addData(Request $request)
	{	
		$data = new data;
		$data->category_id = $request['category_id'];
		$data->semester = $request['semester'];
		$data->name =  $request['name'];
		$data->year =  $request['year'];		
		$data->month =  $request['month'];	
		$data->save();		
		
		$supported_image = array(
			'gif',
			'jpg',
			'jpeg',
			'png'
		);
		
		foreach($request['attr_name'] as $key => $_)
		{
			$attr = new data_attribute;
			$attr->data_id = $data->id;
			$attr->name= $request['attr_name'][$key];
			$attr->value= $request['attr_value'][$key];
			
			if(isset($request['attr_file'][$key])&& $request['attr_file'][$key] ){  // have one file
				$file = $request['attr_file'][$key];
				$extension = $file->getClientOriginalExtension();
				$file_name = strval(time()).str_random(5).'.'.$extension;
				
				Storage::disk('public')->put($file_name,  File::get($file));
				$attr->file = $file->getClientOriginalName();
				$attr->url = Storage::url($file_name);
				
				if (in_array(strtolower($extension), $supported_image)) {
					$attr->type = 'image';
				} else {
					$attr->type = 'file';
				}	
			}
			else if($request['attr_zip'][$key]){  // have multiple file
				$filename = explode("/",$request['attr_zip'][$key]);
				$attr->file = $filename[count($filename)-1];
				$attr->url = $request['attr_zip'][$key];
				$attr->type = 'file';
			}
			else{
				$attr->type = 'text';
			}
			$attr->save();
		}
		
		return redirect('/add?select='.$request['category_id']);
	}
		
	public function addZip(Request $request)
	{
		$fname = hash('crc32', time()).'.zip';
		$file = Input::file('data');
		Storage::disk('public')->put($fname,  File::get($file));
		return Storage::url($fname);
	}
		
	public function editDataPage($id)
	{
		$data = data::find($id);
		$category = category::find($data->category_id);
		$semesters = semester::orderBy('value','desc')->get();
		$data_attr = data_attribute::where('data_id',$id)->get();
		$attr_name_list = data_attribute::select('name')->groupby('name')->get();
		return view('edit_data',['data'=>$data,
								'data_attr'=>$data_attr,
								'attr_name_list'=>$attr_name_list,
								'category_id'=>$data->category_id,
								'category_name'=>$category->name,
								'semesters'=>$semesters]);
	}
		
	public function editData(Request $request,$id)
	{
		$data = data::find($id);
		
		if(!$data)
		{
			return 'no data found';
		}
		
		$data->category_id = $request['category_id'];
		$data->semester = $request['semester'];
		$data->name = $request['name'];
		$data->year =  $request['year'];		
		$data->month =  $request['month'];	
		$data->save();
		
		$supported_image = array(
			'gif',
			'jpg',
			'jpeg',
			'png'
		);
		
		$old_attr = data_attribute::where('data_id',$data->id)->get();
		$update_list = [];
		
		foreach($request['attr_name'] as $key => $_)
		{
			if(isset($request['attr_id'][$key])&&$request['attr_id'][$key]) // 舊有屬性
			{
				array_push($update_list,$request['attr_id'][$key]);
				$attr = data_attribute::find($request['attr_id'][$key]); 
				$attr->name= $request['attr_name'][$key];
				$attr->value= $request['attr_value'][$key];
				$attr->save();

			}
			else // 新屬性
			{
				$attr = new data_attribute;
				$attr->data_id = $data->id;
				$attr->name= $request['attr_name'][$key];
				$attr->value= $request['attr_value'][$key];
				if(isset($request['attr_file'][$key])&& $request['attr_file'][$key] ){  // have one file
					$file = $request['attr_file'][$key];
					$extension = $file->getClientOriginalExtension();
					$file_name = strval(time()).str_random(5).'.'.$extension;
					Storage::disk('public')->put($file_name,  File::get($file));
					$attr->file = $file->getClientOriginalName();
					$attr->url = Storage::url($file_name);
					if (in_array(strtolower($extension), $supported_image)) {
						$attr->type = 'image';
					} else {
						$attr->type = 'file';
					}	
				}
				else if($request['attr_zip'][$key]){  // have multiple file
					$filename = explode("/",$request['attr_zip'][$key]);
					$attr->file = $filename[count($filename)-1];
					$attr->url = $request['attr_zip'][$key];
					$attr->type = 'file';
				}
				else{
					$attr->type = 'text';
				}
				$attr->save();
			}
		}
		
		foreach($old_attr as $a)
		{
			if(in_array($a->id,$update_list)){continue;}
			else{
				self::deleteDataAttr($a->id);
			}
		}
		
		return redirect('/add?select='.$request['category_id']);
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
			
			return redirect('/add?select='.$category->id);
		}
		else
		{
			return redirect('/add');
		}
	}	
	
	public function addOffice(Request $request)
	{
		if($request['new_office'] && !office::where('value',$request['new_office'])->exists() )
		{
			$office = new office;
			$office->value = $request['new_office'];
			$office->shared = $request['shared']?1:0;
			$office->save();
			
			return redirect('/add');
		}
		else
		{
			return redirect('/add');
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
		$data = data::where('category_id',$c_id)->get();
		foreach($data as $d)
		{
			self::deleteData($d->id);
		}
		
		/* delete category */
		category::where('parent_id',$c_id)->delete();
		category::find($c_id)->delete();
		
		return redirect('/add');
	}		
	
	public function editCategory($c_id,$new_name)
	{
		if(!isset($c_id) || $c_id == 0)
		{
			return redirect('/add');
		}
		
		$c = category::find($c_id);
		$c->name = $new_name;
		$c->save();
		
		return redirect('/add');
	}	
	
	public function deleteOffice($office)
	{
		if(!isset($office) || $office == '')
		{
			return redirect('/main');
		}
		
		$categorys = category::where('office',$office)->where('parent_id',0)->get();
		foreach($categorys as $c){self::deleteCategory($c->id);}
		user_office::where('office',$office)->delete();
		office::where('value',$office)->delete();
		
		return redirect('/add');
	}	
	
	public function deleteDataAttr($id)
	{
		$attr = data_attribute::find($id);
		if($attr)
		{
			File::delete(substr($attr->url, 1));
			$attr->delete();	
		}
		return ;
	}
	
	public function deleteData($id)
	{		
		$attrs = data_attribute::where('data_id',$id)->get();
		foreach($attrs as $a)
		{
			self::deleteDataAttr($a->id);
		}
		
		$cate_id = 0;
		$data = data::find($id);
		if($data)
		{
			$cate_id = $data->category_id;			
			$data->delete();
		}

		return redirect('/add?select='.$cate_id);
	}
	
	public function search()
	{
		$offices = office::all();
		$semesters = semester::all();
		$semesters_year = [];
		foreach($semesters as $s)
		{
			array_push($semesters_year,explode('-',$s->value)[0]);
		}
		$semesters_year = array_unique($semesters_year);
		$year = data::select('year')->distinct()->get();
		$month = data::select('month')->distinct()->get();
		
		return view('search',[ 'offices' => $offices,
							   'semesters' => $semesters,
							   'year' => $year,
							   'month' => $month,
							   'semesters_year' => $semesters_year]);
	}	
	
	public function searchGet(Request $request)
	{
		$builder = data_attribute::select('data_attribute.id'
										,'data_attribute.data_id'
										,'data_attribute.name'
										,'data_attribute.value'
										,'data_attribute.file'
										,'data_attribute.url'
										,'data_attribute.type'
										,'data.name AS data_name'
										,'category_id'
										,'office'
										,'semester'
										,'year'
										,'month');
		$builder = $builder->leftJoin('data', 'data_attribute.data_id', '=', 'data.id');
		$builder = $builder->leftJoin('category', 'data.category_id', '=', 'category.id');
		
		// filter
		if( isset($request['keyword']) )
		{
			$builder = $builder->where(function($q)use($request){
				$q->where('data_attribute.name','like','%'.$request['keyword'].'%')
				->orWhere('data_attribute.value','like','%'.$request['keyword'].'%')
				->orWhere('data_attribute.file','like','%'.$request['keyword'].'%')
				->orWhere('data.name','like','%'.$request['keyword'].'%');
			});
		}
		if( isset($request['office']) )
		{
			$builder = $builder->whereIn('office',$request['office']);
		}		
		if( isset($request['semester']) )
		{
			$builder = $builder->whereIn('semester',$request['semester']);
		}	
		if( isset($request['semester_year']) )
		{
			$builder = $builder->where(function($q)use($request){
				for($i=0;$i<count($request['semester_year']);$i++){
					$q->orWhere('semester','like','%'.$request['semester_year'][$i].'%');
				}
			});
		}	
		if( isset($request['year']) )
		{
			$builder = $builder->whereIn('year',$request['year']);
		}	
		if( isset($request['month']) )
		{
			$builder = $builder->whereIn('month',$request['month']);
		}	
	
		
		// page
		$count = $builder->count();
		if( isset($request['limit']) &&  isset($request['page']) )
		{
			$builder = $builder->offset( ($request['page']-1)*$request['limit'] )->limit( $request['limit'] );
		}
		
		//echo $builder->toSql();
		$result = $builder->get();

		
		foreach($result as $key => $r)
		{
			$c = category::find($r->category_id);
			if($c->parent_id == 0)
			{
				$result[$key]['category1'] = $c->name;
				$result[$key]['category2'] = '';
			}
			else
			{
				$result[$key]['category1'] = (category::find($c->parent_id))->name ;
				$result[$key]['category2'] = $c->name;
			}
		}
		
		return view('search_data',[ 'data' => $result,
									'limit' => $request['limit']?$request['limit']:30,
									'page' => $request['page'],
									'count' => $count ]);
									
	}

	public function searchExport(Request $request)
	{
		$builder = data::select('data.id','data.semester','data.name','data.value','data.category_id','category.office');
		
		if( isset($request['keyword']) )
		{
			$builder = $builder->where('data.name','like','%'.$request['keyword'].'%')->leftJoin('category', 'data.category_id', '=', 'category.id');
		}
		
		if( isset($request['semester']) )
		{
			$builder = $builder->whereIn('semester',$request['semester']);
		}
		
		if( isset($request['office']) )
		{
			$builder = $builder->whereIn('office',$request['office']);
		}
		
		$builder = $builder->orderBy('semester','DESC')->orderBy('office','ASC')->orderBy('category_id','ASC')->orderBy('name','ASC');
		
		$result = $builder->get();
		foreach($result as $key => $r)
		{
			$c = category::find($r->category_id);
			if($c->parent_id == 0)
			{
				$result[$key]['category1'] = $c->name;
				$result[$key]['category2'] = '';
			}
			else
			{
				$result[$key]['category1'] = (category::find($c->parent_id))->name ;
				$result[$key]['category2'] = $c->name;
			}
		}
		
		return json_encode($result);
	}
}