<button onclick="selected.click();">返回</button><br><br>
<div id="nw-wr">
	<header id="nw-tp">
		<p style="margin:0px;">新增處室</p>
  </header>
</div>
<div id="nw-mn">
	<form method='POST' action='/add/add_office'  style="text-align:center;padding:10px;">
		{{ csrf_field() }}
		<input type="text" name="new_office" placeholder="請輸入處室名稱" class="nw-name">
		<span>共用處室</span><input type="checkbox" name="shared" /><br>
		<button type="submit" class="nw-create">確認建立</button>
	</form>
</div>
