<!doctype html>
<html>
<head>
	@include('includes.head')
</head>

<body>
<script>
	var selected = null;
	$(function(){
		
		$('.hide').hide();
		
		$('.option').click(function(){
			selected = $(this);
			$('.option').each(function(){
				$(this).css('background-color','');
			});
			$(this).css('background-color','gray');
		});
		
		$('#add_office').click(function(){
			window.open('add/new_office', '新增處室' ,config='height=250,width=481,toolbar=no,scrollbars=no,resizable=no,location=no,menubar=no,status=no');
		});
		
		$('#add_category').click(function(){
			
			office = '';
			if(selected.hasClass('office')){office = selected.html();}
			else {office = selected.parents('.sb-lv2').siblings('.office').html();}
			
			parent_id = selected.attr('p_id');
			if (typeof parent_id === typeof undefined && parent_id === false) {parent_id = 0;}
			
			window.open('/add/new_category/'+office+'/'+parent_id, '新增分類', config='height=250,width=481,toolbar=no,scrollbars=no,resizable=no,location=no,menubar=no,status=no');	
		});
		
		$('#delete_entry').click(function()
		{
			if(selected.hasClass('office'))
			{
				window.location = '/add/delete_office/'+selected.html();
			}
			else
			{
				window.location = '/add/delete_category/'+selected.attr('c_id');
			}
		});
	});
	
	function getData(type,id){
		$.ajax({
			url:'/getdata',
			type:'get',
			data: {'type':type,'id':id},
			dataType:'html',
			success: function(data){
				$('#ct-ct').html(data);
			},
			error: function(xhr,ajaxOptions,thrownError){
				alert(xhr.status);
				alert(thrownError);
			}
		});	
	}	
</script>

@include('includes.header')

<div id="category">
<aside class="sb-wrapper">
<div id="cy-bn">
<button id="add_office" type="submit" class="cy-ct">新建處室</button>
<button id="add_category" type="submit" class="cy-ct">新建分類</button>
<button id="delete_entry" type="submit" class="cy-dt">刪除分類/處室</button>
	</div>
<div>
	@foreach($offices as $o_key => $office)
		<ul>
			<li class="sb-lvl-1-cat">
			<input type="checkbox" id="sb-cat-{{$o_key}}" class='hide'>
			<label for="sb-cat-{{$o_key}}">&nbsp;</label>
			<a class='option office' href="#" onclick=getData("office","{{$office['name']}}") >{{$office['name']}}</a>
			@foreach($office['categorys'] as $c_key => $category)
				<ul class="sb-lv2">
					<li name="big-cat" class="sb-lvl-2-cat ">
						<input type="checkbox" id="sb-cat1-{{$c_key}}" class='hide'>
						<label for="sb-cat1-{{$c_key}}">&nbsp;</label>
						<a class='option' p_id='{{$category["id"]}}' c_id='{{$category["id"]}}'  href="#" onclick='getData("category","{{$category["id"]}}")'>{{$category['name']}}</a>
						@foreach($category['child'] as $subc_key => $child)
						<ul class="sb-lv3">
							<li name="small-cat" class="sb-lvl-3-cat ">
								<a class='option' p_id='{{$category["id"]}}' c_id='{{$child["id"]}}' href='#' onclick='getData("category","{{$child["id"]}}")'>{{$child['name']}}</a>
							</li>
						</ul>
						@endforeach
					</li>
				</ul>
			@endforeach
		</ul>
	@endforeach
	</div>
	</aside>
	</div>
<div id="ct-main">
<div id="ct-bn">
<button type="submit" class="ct-ct">新建</button>
<button type="submit" class="ct-ud">上傳</button>
	</div>
<div id="ct-ct">

</div>
	</div>
</body>
</html>
