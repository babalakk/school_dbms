<!doctype html>
<html>
<head>
	@include('includes.head')
</head>
<body>
<script>
function checkForm()
{
	if( $('#pw1').val() != $('#pw2').val() )
	{
		alert('確認密碼錯誤，請再輸入一次');
		return false;
	}
	else
	{
		return true;
	}
}

function change_pw(id)
{
	$( ('#tr_'+id) ).children('.pw').html('<input id="new_pw" type="password" name="new_password">');
	$( ('#tr_'+id) ).children('.ch_pw').html('<input type="submit" value="確認">');
}
function submit_pw(id)
{
	if( $(('#tr_'+id)).children('.pw').children('#new_pw').val()=='' )
	{
		alert('請輸入新密碼');
		return false;	
	}
	else
	{
		return true;
	}
}
function del_user(id,name)
{
	if(window.confirm("是否刪除 '"+ name +"' ？") == true){	
		window.location ='/setting/delete_user/'+id;
	}
}

</script>


@include('includes.header')

<h2 class='setting_title'>帳號管理</h2>
<div id="user">
	<div id="ur-bn">
		<button onclick='$("#user_form").show()' type="submit" class="ur-ct">新增使用者</button>
	</div>
</div>
<div id="user-lt">
	<div id="urlt">
		<div class="urlt-tr" style="background-color:#5DBCD0;">
			<div class="urlt-td"><span style="font-size:15px; width: 100px; margin: 5px"><strong>類型</strong></span></div>
			<div class="urlt-td"><span style="font-size:15px; width: 100px; margin: 5px"><strong>處室</strong></span></div>
			<div class="urlt-td"><span style="font-size:15px; width: 100px; margin: 5px"><strong>帳號</strong></span></div>
			<div class="urlt-td"><span style="font-size:15px; width: 100px; margin: 5px"><strong>密碼</strong></span></div>
			<div class="urlt-td"><span style="font-size:15px; width: 100px; margin: 5px"></span></div>
			<div class="urlt-td"><span style="font-size:15px; width: 100px; margin: 5px"></span></div>
		</div>
		<form id='user_form' method='POST' action='/setting/add_user' style='display:none;' onsubmit='return checkForm();' >
			<div class="urlt-tr">
				<div class="urlt-td">
					<select name='user_type' style="font-size:15px; font-family: 'Noto Sans TC Regular', 'Noto Sans TC Medium', 'Noto Sans TC Light';">
						<option value="user">行政人員</option>
	　					<option value="admin">處室管理員</option>
					</select>
					</div>
				<div class="urlt-td">
					<select name='user_office' style="font-size:15px; font-family: 'Noto Sans TC Regular', 'Noto Sans TC Medium', 'Noto Sans TC Light';">
						@foreach($offices as $o)
							<option value="{{ $o->value }}">{{ $o->value }}</option>
						@endforeach
					</select>
				</div>
				<div class="urlt-td">
					<input name='user_name' type="text" placeholder="請輸入帳號" style="font-size:15px;" id="ur-ac" required>
				</div>
				<div class="urlt-td">
					<input id='pw1' name='user_password' type="password" placeholder="請輸入密碼" style="font-size:15px;" id="ur-pw" required>
				</div>
				<div class="urlt-td">
					<input id='pw2' type="password" placeholder="再次輸入密碼" style="font-size:15px" id="ur-cpw" required>
				</div>
				<div class="urlt-td">
					<button id="ur-create">確認建立</button>
					<button type="button" onclick='$("#user_form").hide();'>取消</button>
				</div>
			</div>
			{{ csrf_field() }}
			<br>
			<br>
		</form>
				
		@foreach($users as $u_key => $u)
		@if($u['data']->type != 'superadmin' || Session::get('user_type') == 'superadmin' )
		<form method='POST' action='/setting/change_password/{{$u["data"]->id}}' onsubmit="return submit_pw({{$u["data"]->id}});">
			<div class="urlt-tr" id="tr_{{$u['data']->id}}">
				<div class="urlt-td">
					<span style="font-size:15px; width: 100px; margin: 5px">
						@php
							switch( $u['data']->type )
							{
								case 'user':
									echo '行政人員';
									break;
								case 'admin':
									echo '處室管理員';
									break;
								case 'superadmin':
									echo '超級管理員';
									break;
							}
						@endphp
					</span>
				</div>
				<div class="urlt-td"><span style="font-size:15px; width: 100px; margin: 5px">{{ $u['office'] }}</span></div>
				<div class="urlt-td"><span style="font-size:15px; width: 100px; margin: 5px">{{ $u['data']->name }}</span></div>
				<div class="urlt-td pw"><span style="font-size:15px; width: 100px; margin: 5px">*********</span></div>
				<div class="urlt-td ch_pw"><button onclick='change_pw({{$u["data"]->id}})' style="font-size:15px; width: 100px; margin: 5px">更改密碼</button></div>
				<div class="urlt-td">
					@if( $u['data']->type != Session::get('user_type') )
						<button type="button" onclick="del_user({{ $u['data']->id }},'{{ $u['data']->name }}')" class="ur-delete" style="">刪除使用者</button>
					@endif
				</div>
			</div>
		{{ csrf_field() }}
		</form>
		@endif
		@endforeach		
	</div>
</div>

</body>
</html>
