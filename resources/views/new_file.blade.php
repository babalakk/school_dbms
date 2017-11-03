<button onclick="selected.click();">返回</button><br><br>
<div id="nw-wr">
	<header id="nw-tp">
		<p style="margin:0px;">上傳資料</p>
  </header>
</div>
<div id="nw-mn">
<div class="nw-bc">
	<div>所屬分類: {{$category_name}}</div>
</div>
<form method="POST" action="/add/add_file" style="text-align:center;padding:10px;" enctype="multipart/form-data">
	<select name='semester'>
	@foreach($semesters as $s)
		<option value="{{$s->value}}">{{$s->value}}</option>
	@endforeach
	</select>
	<input type="text" name="name" placeholder="資料名稱" class="nw-name" style="width:150px;" required>
	<input type='file' name='upload_file' id='c_file' required>
	<input type="hidden" name="category_id" value="{{$category_id}}">
	{{ csrf_field() }}
	<button type="submit" class="nw-create">確認新增</button>
</form>
</div>
