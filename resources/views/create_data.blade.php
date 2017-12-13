<div>
	<table>
		<tr>
			<th width=70>學年度</th>
			<th width=50>年分</th>
			<th width=50>月份</th>
			<th width=200>項目</th>
			<th width=120>動作</th>
		</tr>
		@foreach($data as $d)
			<tr>
				<td>{{$d->semester}}</td>
				<td>{{$d->year}}</td>
				<td>{{$d->month}}</td>
				<td>{{$d->name}}</td>
				<td>
					<button onclick='editData({{$d->id}});' >編輯</button>
					<button onclick={window.location='/add/delete_data/{{ $d->id }}'} >刪除</button>
				</td>
			</tr>
		@endforeach
	</table>
</div>