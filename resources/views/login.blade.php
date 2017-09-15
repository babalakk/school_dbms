<!doctype html>
<html>
<head>
	@include('includes.head')
</head>

<body>
@include('includes.header')
<div>
<form action='login' method='post'>
  <ul id="login">
	<input type="text" placeholder="帳號" class="account" name='name'>
	<input type="password" placeholder="密碼" class="password" name='password'>
	<button type="submit" class="lgst">登入</button>
	@if(isset($msg))
	 <p class="lger">{{ $msg }}</p>
	@endif
	 {{ csrf_field() }}
  </ul>
<form>
</div>
</body>
</html>
