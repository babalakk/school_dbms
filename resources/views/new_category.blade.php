<div id="nw-wr">
	<header id="nw-tp">
		<p>新增分類</p>
  </header>
</div>
<div id="nw-mn">
	<span>處室：{{$office}}</span>
	@if(isset($parent) && $parent!='')
		<span>上層目錄：{{$parent->name}}</span>
	@endif
	<form method='POST' action='/add/add_category'>
		{{ csrf_field() }}
		<input type="hidden" name="office" value='{{$office}}'>
		@if(isset($parent) && $parent!='')
			<input type="hidden" name="parent_id" value='{{$parent->id}}'>
		@endif
		<input type="text" name="new_category" placeholder="請輸入分類名稱" class="nw-name">
		<button type="submit" class="nw-create">確認建立</button>
	</form>
</div>