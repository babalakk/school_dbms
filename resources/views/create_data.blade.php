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
				<td>{{$d->value}}</td>
			</tr>
		@endforeach
	</table>
</div>