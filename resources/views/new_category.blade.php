<button onclick="selected.click();">返回</button><br><br>
<div id="nw-wr">
	<header id="nw-tp">
		<p style="margin:0px;">新增分類</p>
  </header>
</div>
<div id="nw-mn">
<div class="nw-bc">
	<div>處室：{{$office}}</div>
	@if(isset($parent) && $parent!='')
		<div>上層目錄：{{$parent->name}}</div>
	@endif
</div>

	<form method='POST' action='/add/add_category'  style="text-align:center;padding:10px;">
		{{ csrf_field() }}
		<input type="hidden" name="office" value='{{$office}}'>
		@if(isset($parent) && $parent!='')
			<input type="hidden" name="parent_id" value='{{$parent->id}}'>
		@endif
		<input type="text" name="new_category" placeholder="請輸入分類名稱" class="nw-name">
		<button type="submit" class="nw-create">確認建立</button>
	</form>
</div>