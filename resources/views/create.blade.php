<!doctype html>
<html>
<head>
	@include('includes.head')
	<script type="text/javascript" src="/js/jquery.table2excel.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/rg-1.0.2/datatables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
	<script type="text/javascript" src="https://code.highcharts.com/highcharts.js"></script>
	<script type="text/javascript" src="http://code.highcharts.com/modules/exporting.js"></script>
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
</head>

<body>
<script>
	var selected = null;
	var getUrlParameter = function getUrlParameter(sParam) {
		var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;
		for (i = 0; i < sURLVariables.length; i++) {
			sParameterName = sURLVariables[i].split('=');
			if (sParameterName[0] === sParam) {
				return sParameterName[1] === undefined ? true : sParameterName[1];
			}
		}
	};
	var c_id = getUrlParameter('select');
	$(function(){
		$('.option').click(function(){
			selected = $(this);
			//c_id = $(this).attr('c_id');
			$('.option').each(function(){
				$(this).css('color','#555555');
			});
			$(this).css('color','#58B2DC');
			$('#op_title').html($(this).html());
			$('#add_category').show();			
			$('#delete_entry').show();			
			if(selected.hasClass('office')){
				$('#ct-bn').hide();
				$('#edit_entry').hide();
			}else{
				$('#ct-bn').show();
				$('#edit_entry').show();			
			}
			$(this).siblings('label').click();
		});
		
		$('#add_office').click(function(){
			$.ajax({
				url:'/add/new_office',
				type:'get',
				success: function(data){
					$('#ct-ct').html(data);
				},
				error: function(xhr,ajaxOptions,thrownError){
					alert(xhr.status);
					alert(thrownError);
				}
			});	
		});		
		
		$('#add_category').click(function(){
			office = '';
			if(selected.hasClass('office')){office = selected.html();}
			else {office = selected.parents('.sb-lv2').siblings('.office').html();}
			parent_id = selected.attr('p_id');
			if (typeof parent_id === typeof undefined && parent_id === false) {parent_id = 0;}
			$.ajax({
				url:'/add/new_category/'+office+'/'+parent_id,
				type:'get',
				success: function(data){
					$('#ct-ct').html(data);
				},
				error: function(xhr,ajaxOptions,thrownError){
					alert(xhr.status);
					alert(thrownError);
				}
			});	
		});
		
		$('#delete_entry').click(function()
		{
			if(window.confirm("此動作將會刪除 '"+selected.html()+"' 與子分類，並清除分類裡的所有資料，此為無法回復的動作，請問是否繼續？") == true){			
				if(selected.hasClass('office')){
					window.location = '/add/delete_office/'+selected.html();
				}else{
					window.location = '/add/delete_category/'+selected.attr('c_id');
				}
			}
		});
		
		$('#edit_entry').click(function(){
			var new_name = prompt("請輸入新的名稱",selected.html());
			if(new_name){
				window.location = '/add/edit_category/'+selected.attr('c_id')+'/'+new_name;	
			}
			
		});
		
		$("#new_data").click(function(){
			$.ajax({
				url:'/add/new_data/'+selected.attr('c_id'),
				type:'get',
				success: function(data){
					$('#ct-ct').html(data);
				},
				error: function(xhr,ajaxOptions,thrownError){
					alert(xhr.status);
					alert(thrownError);
				}
			});	
		});
		$('.hide').hide();
		$( ('.option[c_id='+c_id+']') ).parent().parent().siblings('.office').click();
		$( ('.option[c_id='+c_id+']') ).click();
	});
	
	function getData(type,id){
		$.ajax({
			url:'/getdata',
			type:'get',
			data: {'type':type,'id':id},
			dataType:'html',
			success: function(data){
				$('#ct-ct').html(data);
				table = $('#data_table').DataTable();

				$("#data_table_wrapper").css("clear","none");
				$("#data_table").css("clear","none");

			},
			error: function(xhr,ajaxOptions,thrownError){
				alert(xhr.status);
				alert(thrownError);
			}
		});	
	}
	
	function editData(id){
		$.ajax({
			url:'/add/edit/'+id,
			type:'get',
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
	
	function duplicate(id){
		$.ajax({
			url:'/add/duplicate/'+id,
			type:'get',
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
		@if(Session::get('user_type') == 'superadmin' )
		<div id="cy-bn">
			<div style="width:100%;height:30px;">
				<button id="add_office" type="submit" class="cy-ct" style="display:inline-block;float:left;">新建處室</button>
				<button id="add_category" type="submit" class="cy-ct" style="display:none;float:left;margin-left:10px;">新建分類</button>
			</div>
			<div>
				<button id="delete_entry" type="submit" class="cy-dt" style="color:red;display:none;margin-right:5px;">刪除所選分類/處室</button>
				<button id="edit_entry" type="submit" class="cy-dt" style="display:none;">編輯名稱</button>
			</div>
		</div>
		@elseif(Session::get('user_type') == 'admin' )
		<div id="cy-bn">
			<div style="width:100%;height:30px;">
				<button id="add_category" type="submit" class="cy-ct" style="display:none;float:left;">新建分類</button>
				<button id="delete_entry" type="submit" class="cy-dt" style="color:red;display:none;float:left;margin-left:10px;">刪除分類</button>
			</div>			
		</div>
		@endif
		<div>
		@foreach($offices as $o_key => $office)
			<ul>
				<li class="sb-lvl-1-cat">
					<input type="checkbox" id="sb-cat-{{$o_key}}" class='hide'>
					<label for="sb-cat-{{$o_key}}">&nbsp;</label>
					<a class='option office' onclick=getData("office","{{$office['name']}}") >{{$office['name']}}</a>
					@foreach($office['categorys'] as $c_key => $category)
						<ul class="sb-lv2 list">
							<li name="big-cat" class="sb-lvl-2-cat ">
								<input type="checkbox" id="sb-cat{{$o_key+1}}-{{$c_key}}" class='hide'>
								<label for="sb-cat{{$o_key+1}}-{{$c_key}}">&nbsp;</label>
								<a class='option' p_id='{{$category["id"]}}' c_id='{{$category["id"]}}' onclick='getData("category","{{$category["id"]}}")'>{{$category['name']}}</a>
								@foreach($category['child'] as $subc_key => $child)
								<ul class="sb-lv3 list2">
									<li name="small-cat" class="sb-lvl-3-cat ">
										<a class='option' p_id='{{$category["id"]}}' c_id='{{$child["id"]}}' onclick='getData("category","{{$child["id"]}}")'>{{$child['name']}}</a>
									</li>
								</ul>
								@endforeach
							</li>
						</ul>
					@endforeach
				</li>
			</ul>
		@endforeach
		</div>
	</aside>
</div>
<div id="ct-main">
	<div style="padding-top:10px;padding-left:10px;">
		<span id='op_title' style="font:30px bold;color:gray;"></span>
		<div id="ct-bn" style="display:none;">
			<button id="new_data" type="submit" class="ct-ud">新增資料</button>
		</div>
	</div>
	<div id="ct-ct">
		<span id='ct_default_text' >請選擇左側分類</span>
		<iframe id="ct_frame" style='width:100%;height:100%;' frameborder="0" >
		
		</iframe>
	</div>
</div>
</body>
</html>
