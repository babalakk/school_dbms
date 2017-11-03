<div>
	<table>
		<tr>
			<th width=50>學年度</th>
			<th width=200>項目</th>
			<th width=500>內容</th>
			<th width=200>檔案</th>
			<th width=120>動作</th>
		</tr>
		@foreach($data as $d)
			<tr>
				<td>{{$d->semester}}</td>
				<td>{{$d->name}}</td>
				<td>{{$d->value}}</td>
				<td>
					@if($d->type == 'text')
						
					@elseif($d->type == 'file')
						<a href='{{ $d->url }}' >{{$d->file}}</a>
					@elseif($d->type == 'image')
						<img src='{{ $d->url }}' width=200 height=150 >
					@endif
				</td>
				<td>
					<button onclick='editData({{$d->id}});' >編輯</button>
					<button onclick={window.location='/add/delete_data/{{ $d->id }}'} >刪除</button>
				</td>
			</tr>
		@endforeach
	</table>
</div>