<div class="info">
	<div>共查詢到 {{$count}} 個結果</div>
	<!--
	<div><button class='ct-ct' onclick='excel()'>匯出所有資料</button></div>
	-->
</div>

<div id="table-div">
	<table class="table-fill" id='data_table'>
		<thead>
			<tr>
				<th width=70>學年度</th>
				<th width=50>年分</th>
				<th width=50>月份</th>
				<th width=120>處室</th>
				<th width=160>主分類</th>
				<th width=160>子分類</th>
				<th width=200>項目</th>
				<th width=100>屬性</th>
				<th width=100>內容</th>
				<th width=150>檔案</th>
				<th>group</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($data as $d)
			<tr>
				<td>{{$d['semester'] }}</td>
				<td>{{$d['year'] }}</td>
				<td>{{$d['month'] }}</td>
				<td>{{$d['office'] }}</td>
				<td>{{$d['category1'] }}</td>
				<td>{{$d['category2'] }}</td>
				<td>{{$d['data_name'] }}</td>
				<td>{{$d['name']}}</td>
				<td>{{$d['value']}}</td>
				<td>
					@if($d['type'] == 'text')

					@elseif($d['type'] == 'file')
						<a href='{{$d["url"]}}' >{{$d["file"]}}</a>
					@elseif($d["type"] == 'image')
						<img src='{{ $d["url"] }}' width=150 height=100 >
					@else
						
					@endif
				</td>
				<td>{{ $d['office'].$d['category1'].$d['category2'].$d['data_name'].$d['name'] }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	<div id="chart-list"></div>
</div>


<script>
$("img").each(function() {
  var src = this.src;
  this.src = src;
});
$("a").each(function() {
  var href = this.href;
  this.href = href;
});
</script>