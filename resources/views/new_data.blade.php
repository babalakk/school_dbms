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
	</select>
	<span>年</span><input type='number' name='year' />
	<span>月</span><input type='number' name='month' /><br>
	<br>
	<table id='attr_table'>
		<tbody>
			<tr>
				<th>屬性名稱</th>
				<th>內容</th>
				<th></th>
			</tr>
			<tr>
				<td>
					<input type="text" name="attr_name[]" list="attr_name_list" />
				</td>
				<td>
					<input type='text' name='attr_value[]'/>
					<input type='file' name='attr_file[]' />
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