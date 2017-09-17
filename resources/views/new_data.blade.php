<div id="nw-wr">
	<header id="nw-tp">
		<p>新增資料</p>
  </header>
</div>
<div id="nw-mn">
<ul class="nw-bc">
	<li>所屬分類: {{$category_name}}</li>
</ul>
<form method="POST" action="/add/add_data">
	<select name='semester'>
	@foreach($semesters as $s)
		<option value="{{$s->value}}">{{$s->value}}</option>
	@endforeach
	</select>
	<input type="text" name="name" placeholder="資料名稱" class="nw-name" required>
	<input type="text" name="value" placeholder="內容" class="nw-content" required>
	<input type="hidden" name="category_id" value="{{$category_id}}">
	{{ csrf_field() }}
	<button type="submit" class="nw-create">確認新增</button>
</form>
</div>
