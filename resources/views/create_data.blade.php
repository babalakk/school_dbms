<div>
	<table>
		<tr>
			<th>學年度</th>
			<th>項目</th>
			<th>內容</th>
		</tr>
		@foreach($data as $d)
			<tr>
				<td>{{$d->semester}}</td>
				<td>{{$d->name}}</td>
				<td>
					@if($d->type == 'text')
						{{ $d->value }}
					@elseif($d->type == 'file')
						<a href='{{ $d->url }}' >{{$d->value}}</a>
					@elseif($d->type == 'image')
						<img src='{{ $d->url }}' width=300 height=200 >
					@endif
				</td>
				<td><button onclick={window.location='/add/delete_data/{{ $d->id }}'} >刪除</button></td>
				
			</tr>
		@endforeach
	</table>
</div>