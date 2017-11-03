<!doctype html>
<html>
<head>
	@include('includes.head')
</head>

<body>
@include('includes.header')
	<div id='main_div'>
		<a href="/add"><img class='m_pic' src="/image/add.png"class="mnnw"/><p>新增資料</p></a>
		<a href="/search"><img class='m_pic' src="/image/search.png"  class="mnsh"/><p>搜尋資料</p></a>
		@if(Session::get('user_type')=='admin'||Session::get('user_type')=='superadmin')
			<a href="/setting"><img class='m_pic' src="/image/setting.png" class="mnad"/><p>系統管理</p></a>
		@endif
		<a href="/logout"><img class='m_pic' src="/image/logout.png" class="mnet"/><p>登出</p></a>
	</div>
</body>
</html>
