<!DOCTYPE html>
<html>
<head>
	@include('includes.head')
	<script src="/js/jquery.table2excel.js"></script>
</head>
<script>
var office_filter = [];
var semester_filter = [];
var page = 1;

function search_fn()
{
	$.ajax({
		url:'/search/get',
		type:'get',
		data: { 'keyword':$('#keyword').val(),
				'office':office_filter,
				'semester':semester_filter,
				'limit': $('#limit').val()?$('#limit').val():30,
				'page': page },
		dataType:'html',
		success: function(data){
			$('#searching').css('margin-top','20px');
			$('#data').html(data);
		},
		error: function(xhr,ajaxOptions,thrownError){
			alert(xhr.status);
			alert(thrownError);
		}
	});	
}
var search = search_fn;

function add(self,filter)
{
	if(filter=='office')
	{
		office_filter.push(self.innerHTML);
		$(self).attr('onclick','remove(this,"office")');
	}
	else if(filter=='semester')
	{
		semester_filter.push(self.innerHTML);
		$(self).attr('onclick','remove(this,"semester")');
	}

	$(self).addClass("selected");
	search();
}
function remove(self,filter)
{
	if(filter=='office')
	{
		office_filter.splice( $.inArray(self.innerHTML, office_filter), 1 );
		$(self).attr('onclick','add(this,"office")');
	}	
	else if(filter=='semester')
	{
		semester_filter.splice( $.inArray(self.innerHTML, semester_filter), 1 );
		$(self).attr('onclick','add(this,"semester")');
	}
	$(self).removeClass("selected");
	search();
}

function output()
{
	$.ajax({
		url:'/search/export',
		type:'get',
		data: { 'keyword':$('#keyword').val(),
				'office':office_filter,
				'semester':semester_filter },
		dataType:'json',
		success: function(data){
			var csvContent = "data:text/csv;charset=utf-8,";			
			data.forEach(function(entry, index){
			   dataString = entry['semester']
							+','+ entry['office']
							+','+ entry['category1']
							+','+ entry['category2']
							+','+ entry['name']
							+','+ entry['value'];
				csvContent += index < data.length ? dataString+ "\n" : dataString;
			}); 
			var encodedUri = encodeURI(csvContent);
			var link = document.createElement("a");
			link.setAttribute("href", encodedUri);
			link.setAttribute("download", "export.csv");
			document.body.appendChild(link); 
			link.click();
		},
		error: function(xhr,ajaxOptions,thrownError){
			alert(xhr.status);
			alert(thrownError);
		}
	});	
}
function excel(){
	$("#data_table").table2excel({
    //name: "export",
    filename: "export.xls",
	//fileext: ".xls",
	exclude_img: false,
	exclude_links: false,
	exclude_inputs: true
});
}
</script>
<body>
@include('includes.header')

<div id='search_box'>
  <ul id="searching">
	<input id="keyword" type="text" placeholder="請輸入關鍵字" class="shbk">
	<div style='float:right;width:180px;'><button class='ct-ct' onclick='search()'>搜尋</button></div>
	</ul>
	<ul id="searching1">
		<span class="h_shbn">指定處室：</span>
		@foreach ($offices as $o)
		<button type="button" class="shbn filter_btn" onclick="add(this,'office')">{{ $o->value }}</button>
		@endforeach
	</ul>
	<ul id="searching2">
		<span class="h_shbn">指定學年度：</span>
		@foreach ($semesters as $s)
		<button type="button" class="shyr filter_btn" onclick="add(this,'semester')">{{ $s->value }}</button>
		@endforeach
	</ul>
</div>
<div id='data'>
</div>

</div>
</body>
</html>
