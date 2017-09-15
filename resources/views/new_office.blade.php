<!doctype html>
<html>
<head>
	@include('includes.head')
</head>

<body id="ct-new">
<div id="nw-wr">
	<header id="nw-tp">
		<p>新增處室</p>
  </header>
</div>
<div id="nw-mn">
	<form method='POST' action='/add/add_office'>
		{{ csrf_field() }}
		<input type="text" name="new_office" placeholder="請輸入處室名稱" class="nw-name">
		<button type="submit" class="nw-create">確認建立</button>
	</form>
</div>
</body>
</html>
