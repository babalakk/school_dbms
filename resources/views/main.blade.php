<!doctype html>
<html>
<head>
	@include('includes.head')
</head>

<body>
@include('includes.header')
	<div>
		<ul id="mnin">
			<a href="/search"><img src="/image/search.svg" alt="" width="281" class="mnsh"/></a>
			<a href="/logout"><img src="/image/logout.svg" alt="" width="281" class="mnet"/></a>
			<a href="/add"><img src="/image/add.svg" alt="" width="281" class="mnnw"/></a>
			@if(Session::get('user_name')=='admin'||Session::get('user_name')=='superadmin')
				<a href="/setting"><img src="/image/setting.svg" alt="" width="281" class="mnad"/></a>
			@endif
		</ul>
	</div>
</body>
</html>
