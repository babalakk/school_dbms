<!doctype html>
<html>
<head>
	@include('includes.head')
</head>

<body>

@include('includes.header')
<div id="user">
<div id="ur-bn">
<button type="submit" class="ur-ct">新建</button>
	</div>
	</div>
<div id="user-lt">
	<div id="urlt">
	<div class="urlt-tr">
			<div class="urlt-td">
				<span style="font-size:15px; width: 100px; margin: 5px">行政人員</span>
				</div>
			<div class="urlt-td">
			<span style="font-size:15px; width: 100px; margin: 5px">生活輔導組</span>
				</div>
			<div class="urlt-td">
				<span style="font-size:15px; width: 100px; margin: 5px">使用者帳號</span>
				</div>
			<div class="urlt-td">
				<span style="font-size:15px; width: 100px; margin: 5px">***********</span>
				</div>
			<div class="urlt-td">
			<span style="font-size:15px; width: 100px; margin: 5px">更改密碼</span>
				</div>
			<div class="urlt-td">
				<button type="submit" class="ur-delete" style="">刪除使用者</button>
				</div>
	</div>
	<div class="urlt-tr">
			<div class="urlt-td">
				<span style="font-size:15px; width: 100px; margin: 5px">行政人員</span>
				</div>
			<div class="urlt-td">
			<span style="font-size:15px; width: 100px; margin: 5px">生活輔導組</span>
				</div>
			<div class="urlt-td">
				<span style="font-size:15px; width: 100px; margin: 5px">使用者帳號</span>
				</div>
			<div class="urlt-td">
				<span style="font-size:15px; width: 100px; margin: 5px">1234567890</span>
				</div>
			<div class="urlt-td">
			<span style="font-size:15px; width: 100px; margin: 5px">確定</span>
				</div>
			<div class="urlt-td">
				<button type="submit" class="ur-delete" style="">刪除使用者</button>
				</div>
	</div>
		<div class="urlt-tr">
			<div class="urlt-td">
				<select style="font-size:15px; font-family: 'Noto Sans TC Regular', 'Noto Sans TC Medium', 'Noto Sans TC Light';">
					<option value="">行政人員</option>
　					<option value="">教師</option>
　					<option value="">學生</option>
　					<option value="">管理員</option>
				</select>
				</div>
			<div class="urlt-td">
			<select style="font-size:15px; font-family: 'Noto Sans TC Regular', 'Noto Sans TC Medium', 'Noto Sans TC Light';">
					<option value="">生活輔導組</option>
　					<option value="">課外活動指導組</option>
　					<option value="">公館學務組</option>
　					<option value="">學輔中心</option>
　					<option value="">健康中心</option>
　					<option value="">專責導師室</option>
　					<option value="">全人教育中心</option>
				</select>
				</div>
			<div class="urlt-td">
			<input type="text" placeholder="請輸入帳號" style="font-size:15px;" id="ur-ac">
				</div>
			<div class="urlt-td">
			<input type="text" placeholder="請輸入密碼" style="font-size:15px;" id="ur-pw">
				</div>
			<div class="urlt-td">
			<input type="text" placeholder="再次輸入密碼" style="font-size:15px" id="ur-cpw">
				</div>
			<div class="urlt-td">
				<button type="submit" id="ur-create">確認建立</button>
				</div>
	</div>
	</div>
</div>
</body>
</html>
