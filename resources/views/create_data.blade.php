<div>
	<table id="data_table">
		<thead>
			<tr>
				<th width=70>學年度</th>
				<th width=50>年分</th>
				<th width=50>月份</th>
				<th width=350>項目</th>
				<th width=200>動作</th>
			</tr>
		</thead>
		<tbody>
		@foreach($data as $d)
			<tr>
				<td>{{$d->semester}}</td>
				<td>{{$d->year}}</td>
				<td>{{$d->month}}</td>
				<td>{{$d->name}}</td>
				<td>
					<button onclick='editData({{$d->id}});' >編輯</button>
					<button onclick='duplicate({{$d->id}});' >新增副本</button>
					<button onclick={window.location='/add/delete_data/{{ $d->id }}'} >刪除</button>
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
</div>