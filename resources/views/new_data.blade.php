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
	<span>學年度</span><select name='semester'>
	@foreach($semesters as $s)
		<option value="{{$s->value}}">{{$s->value}}</option>
	@endforeach
	</select><br>
	<span>資料名稱</span><input type="text" name="name" placeholder="資料名稱" class="nw-name" required><br>
	<span>檔案上傳</span><input type='file' name='upload_file' id='c_file' ><br>
	<span style='vertical-align:top;'>資料描述</span><textarea rows="4" cols="50" name="value" placeholder="內容" class="nw-content" ></textarea><br>
	<input type="hidden" name="category_id" value="{{$category_id}}"><br>
	{{ csrf_field() }}
	<button type="submit" class="nw-create">確認新增</button>
	</form>
</div>

</div>
