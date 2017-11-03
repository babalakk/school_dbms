<div id="wrapper">
	<header id="top">
		<p id="hd_url"><a href="/main" id="header1">國立臺灣師範大學學務處資料管理系統</a></p>
		@if(Session::has('user_id'))
			<img title='登出' id="hd_logout" src='/image/hd_logout.png' onclick='window.location="/logout" '>
		@endif
		<img title='回首頁' id="hd_home" src='/image/home.png' onclick='window.location="/" ' >
		<p class="user">{{ Session::get('user_name') }}</p>
		<p class="grt">你好,</p>
	</header>
</div>