<button onclick="selected.click();">返回</button><br><br>
<div id="nw-wr">
	<header id="nw-tp">
		<p style="margin:0px;">編輯資料</p>
  </header>
</div>
<div id="nw-mn">
<div class="nw-bc">
	<div>所屬分類: {{$category_name}}</div>
	<form id='new_data_form' method="POST" action="/add/edit_data/{{$data->id}}" enctype="multipart/form-data">	
	<span>項目名稱</span><input type="text" name="name" value="{{$data->name}}" class="nw-name" required><br>
	<span>學年度</span>
	<select name='semester'>
	@foreach($semesters as $s)
		@if($s->value == $data->semester)
		<option value="{{$s->value}}" selected>{{$s->value}}</option>
		@else
		<option value="{{$s->value}}">{{$s->value}}</option>
		@endif
	@endforeach
	</select>
	<span>年</span><input type='number' name='year' value="{{$data->year}}" />
	<span>月</span><input type='number' name='month' value="{{$data->month}}" /><br>	
	<br>
	<table id='attr_table'>
		<tbody>
		<tr>
			<th>屬性名稱</th>
			<th>內容</th>
			<th></th>
		</tr>
		@foreach($data_attr as $key => $a)
		<tr>
			<td>
				<input type="text" name="attr_name[]" list="attr_name_list" value="{{$a->name}}" />
			</td>
			<td>
				<input type='text' name='attr_value[]' value="{{$a->value}}" />
				<input type='file' name='attr_file[]' />
			</td>
			<td>
				@if($key!=0)
				<button type='button' onclick='cancelAttr(this)'>刪除</button>
				@endif
			</td>
		</tr>
		@endforeach
		<tr>
			<td><button type='button' onclick='add_attr()'>新增屬性</button></td>
		</tr>
		</tbody>
	</table>
	
	<datalist id="attr_name_list">
		@foreach($attr_name_list as $a)
		<option value="{{$a->name}}">
		@endforeach
	</datalist>
	
	<input type="hidden" name="category_id" value="{{$category_id}}"><br>
	{{ csrf_field() }}
	<button type="submit" class="nw-create">確認</button>
	</form>
</div>
</div>
<script>
function add_attr(){
	var elem = '<tr><td><input type="text" name="attr_name[]" list="attr_name_list" /></td>' +
		'<td><input type="text" name="attr_value[]"/><input type="file" name="attr_file[]" /></td>' +
		'<td><button type="button" onclick="cancelAttr(this)">刪除</button></td></tr>';
	var n = $(elem);
	n.insertBefore($('#attr_table').children("tbody").children().last());
}
function cancelAttr(elem)
{
	$(elem).parent().parent().remove();	
}
</script>