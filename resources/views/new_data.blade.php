<script type='text/javascript' src='js/jszip.min.js'></script>

<button onclick="selected.click();">返回</button><br><br>
<div id="nw-wr">
	<header id="nw-tp">
		<p style="margin:0px;">新增資料</p>
  </header>
</div>
<div id="nw-mn">
<div class="nw-bc">
	<div>所屬分類: {{$category_name}}</div>
	<form id='new_data_form' method="POST" action="/add/add_data" enctype="multipart/form-data">	
	<span>項目名稱</span><input type="text" name="name" placeholder="資料名稱" class="nw-name" required><br>
	<span>學年度</span><select name='semester'>
	@foreach($semesters as $s)
		<option value="{{$s->value}}">{{$s->value}}</option>
	@endforeach
	</select><br>
	<span>年份</span><input type='number' name='year' min='0' style='width:50px;' />
	<span>月份</span><input type='number' name='month' min='1' max='12' /><br>
	<br>
	<table id='attr_table'>
		<tbody>
			<tr>
				<th>屬性名稱</th>
				<th>內容</th>
				<th>檔案(可省略)</th>
				<th></th>
			</tr>
			<tr>
				<td>
					<input type="text" name="attr_name[]" list="attr_name_list" >
				</td>
				<td>
					<input type='text' name='attr_value[]' >
				</td>
				<td>
					<input type='file' name='attr_file[]' class='input_file' multiple="multiple" >
					<input type='hidden' name='attr_zip[]'  class="input_zip" >
				</td>
			</tr>
			<tr>
				<td><button type='button' onclick='add_attr()'>新增屬性</button></td>
			</tr>
		</tbody>
		<datalist id="attr_name_list">
			@foreach($attr_name_list as $a)
			<option value="{{$a->name}}">
			@endforeach
		</datalist>
	</table>
	
	<input type="hidden" name="category_id" value="{{$category_id}}"><br>
	{{ csrf_field() }}
	<button type="submit" class="nw-create">確認新增</button>
	</form>
</div>
</div>
<script>
function add_attr(){
	var elem = '<tr><td><input type="text" name="attr_name[]" list="attr_name_list" /></td>' 
		+ '<td><input type="text" name="attr_value[]"></td>'
		+ '<td><input type="file" name="attr_file[]" class="input_file" multiple="multiple">'
		+ '<input type="hidden" name="attr_zip[]" class="input_zip"></td>' 
		+ '<td><button type="button" onclick="cancelAttr(this)">刪除</button></td></tr>' ;
	var n = $(elem);
	n.insertBefore($('#attr_table').children("tbody").children().last());
}
function cancelAttr(elem)
{
	$(elem).parent().parent().remove();	
}
$('.input_file').on('change',function(event){
	var files = event.target.files;
	if(files.length>=2){
	$(event.target).hide();
		$(event.target).parent().append("<span class='upload_msg'>處理中...</span>");
		var zip = new JSZip();
		for(var i=0;i<files.length;i++)
		{
			zip.file(files[i].name,files[i]);
		}
		zip.generateAsync({type:'blob'}).then(function (content) {
			//console.log(content);
			var fd = new FormData();
			fd.append('data', content);
			$.ajax({
				url:'/add/zip_upload',
				type:'post',
				headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}'},
				data: fd,
				processData: false,
				contentType: false,
				success: function(file_url){
					$(event.target).siblings('.input_zip').attr('value',file_url);
					$(event.target).siblings(".upload_msg").html("上傳完成");
					$(event.target).val("");
				},
				error: function(xhr,ajaxOptions,thrownError){
					//alert(xhr.status);
					//alert(thrownError);
					alert("檔案上傳失敗，請重新選擇檔案或選擇較小的檔案");
					$(event.target).show();
					$(event.target).val("");
				}
			});	
		});
	}
});
</script>