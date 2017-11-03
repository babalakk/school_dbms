<div class="info">
	<div>共查詢到 {{$count}} 個結果</div>
	<div>每頁顯示<input id='limit' type="number" style="width: 30pt; height: 10pt;" value='{{$limit}}'>筆資料</div>
	<div><button class='ct-ct' onclick='excel()'>匯出所有資料</button></div>
</div>

<table class="table-fill" id='data_table'>
<tr>
	<th width=50>學年度</th>
	<th width=120>處室</th>
	<th width=160>大分類</th>
	<th width=160>小分類</th>
	<th width=160>資料名稱</th>
	<th width=200>內容</th>
	<th width=150>檔案</th>
</tr>
@foreach ($data as $d)
<tr>
	<td>{{$d['semester'] }}</td>
	<td>{{$d['office'] }}</td>
	<td>{{$d['category1'] }}</td>
	<td>{{$d['category2'] }}</td>
	<td>{{$d['name']}}</td>
	<td>{{$d['value']}}</td>
	<td>
		@if($d['type'] == 'text')

		@elseif($d['type'] == 'file')
			<a href='{{ $d["url"] }}' >{{$d["file"]}}</a>
		@elseif($d["type"] == 'image')
			<img src='{{ $d["url"] }}' width=150 height=100 >
		@else
			
		@endif
	</td>
</tr>
@endforeach
</table>

<div id="page_box" class="pagination">
	  <span onclick='page=1;search();'>最前頁</span>
	  @if ($page!=1)
		  <span onclick='page-=1;search();'>前一頁</span>
	  @endif
 	  @for ($i = 1; ($i-1)*$limit < $count ; $i++)
		@if ($i == $page)
		<span style="color:#fff; background-color: #5DBCD0;">{{$i}}</span>
		@else
		<span onclick='page={{$i}};search();' >{{$i}}</span>
		@endif
	  @endfor
	  @if($page*$limit < $count)
		<span onclick='page+=1;search();'>下一頁</span>
	  @endif
	  <span onclick='page={{$i-1}};search();'>最末頁</span>
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