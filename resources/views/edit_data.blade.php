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
	<span>學年度</span><select name='semester'>
	@foreach($semesters as $s)
		@if($s->value == $data->semester)
		<option value="{{$s->value}}" selected>{{$s->value}}</option>
		@else
		<option value="{{$s->value}}">{{$s->value}}</option>
		@endif
	@endforeach
	</select><br>
	<span>資料名稱</span><input type="text" name="name" value="{{ $data->name }}" class="nw-name" required><br>
	<span>檔案上傳</span><input type='file' name='upload_file' id='c_file' ><br>
	@if($data->type == 'text')
		
	@elseif($data->type == 'file')
		<a href='{{ $data->url }}' >{{$data->file}}</a>
	@elseif($data->type == 'image')
		<img src='{{ $data->url }}' width=200 height=150 >
	@endif
	<br><span style='vertical-align:top;'>資料描述</span><textarea rows="4" cols="50" name="value" class="nw-content" >{{ $data->value }}</textarea><br>
	<input type="hidden" name="category_id" value="{{$category_id}}"><br>
	{{ csrf_field() }}
	<button type="submit" class="nw-create">確認</button>
	</form>
</div>

</div>
