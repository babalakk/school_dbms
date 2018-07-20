<!DOCTYPE html>
<html>
<head>
	@include('includes.head')
	<script type="text/javascript" src="/js/jquery.table2excel.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/rg-1.0.2/datatables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
	<script type="text/javascript" src="https://code.highcharts.com/highcharts.js"></script>
	<script type="text/javascript" src="http://code.highcharts.com/modules/exporting.js"></script>
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script type="text/javascript">
var office_filter = [];
var semester_filter = [];
var semester_year_filter = [];
var year_filter = [];
var month_filter = [];
var chart_count = 0;
var chart_limit = 3;

let draw = false;
 
 // divide table to different statis group
function divideTable(table){
  const tables = [];
  table.rows({ search: "applied" }).every(function() {
    const data = this.data();
	index = data[3]+data[4]+data[5]+data[6]+data[7];
	if(!tables[index]){
		tables[index] = {
			semester:[],
			year:[],
			month:[],
			cate2:data[5],
			cate3:data[6],
			name:data[7],
			label:[],
			data:[]
		};
	}
	tables[index]['semester'].push(data[0]);
	tables[index]['year'].push(data[1]);
	tables[index]['month'].push(data[2]);
	tables[index]['label'].push(data[0]+"學期");
	tables[index]['data'].push(parseInt(data[8]));
  });
  return tables;
}
function setTableEvents(table) {
  table.on("page", function(){
    draw = true;
  });
 
  table.on("draw", function(){
    if (draw) {
      draw = false;
    } else {
		$("#chart-list").empty();
		const tableData = divideTable(table);
		var i = 0;
		for(var t in tableData){
			createCharts(tableData[t]);
			i++;
			if(i==chart_limit){break;}
		}		
    }
  });
}

function createCharts(data){
  chart_count+=1;
  id = "chart"+chart_count;
  $("#chart-list").append("<div id='"+id+"'></div>");
  
  // Process data
  series = [];
  for(var key in data['semester']){
	s = data['semester'][key];
    if(!series[s]){
		series[s]={
			name:s,
			data:[0,0,0,0,0,0,0,0,0,0,0,0]
		};
	}
	series[s]['data'][data['month'][key]] = data['data'][key];
  }
  // Convert obj to array
  var series_arr = [];
  for(var key in series){
	  series_arr.push(series[key]);
  }
    
  Highcharts.chart(id,{
	  chart: {
        type: 'column'
      },
	  title:{
		text: "統計結果"
	  },
	  subtitle:{
		text: data['cate2']+" "+data['cate3']+" "+data['name']
	  },
	  xAxis: {
        categories: [
            '一月',
            '二月',
            '三月',
            '四月',
            '五月',
            '六月',
            '七月',
            '八月',
            '九月',
            '十月',
            '十一月',
            '十二月'
        ],
        crosshair: true
	  },
	  yAxis: {
        min: 0,
        title: {
            text: data['name']
        }
      },
	  series: series_arr,
	  navigation: {
        buttonOptions: {
          enabled: true
        }
      }
	  
  });

}
function search()
{
	$.ajax({
		url:'/search/get',
		type:'get',
		data: { 'keyword':$('#keyword').val(),
				'office':office_filter,
				'semester':semester_filter,
				'semester_year':semester_year_filter,
				'year':year_filter,
				'month':month_filter
				},
		dataType:'html',
		success: function(data){
			$('#searching').css('margin-top','20px');
			$('#data').html(data);
			const table = $('#data_table').DataTable({
				order:[[10,'desc'],[0,'desc'],[1,'desc'],[2,'desc']],
				columnDefs: [
					{
						// hide last column which is for grouping
						"targets": [ 10 ],
						"visible": false,
						"searchable": false
					}
				],
				rowGroup:{
					startRender: function ( rows, group ) {
						return group +' ('+rows.count()+' 筆)';
					},
					endRender: function ( rows, group ) {
						var avg = rows
							.data()
							.pluck(8)
							.reduce( function (a, b) {
								return a + b.replace(/[^\d]/g, '')*1;
							}, 0) ;
						return '<span style="float:right;">累計' + rows.data().pluck(7)[0] + ' ' + avg + '</span>';
					},
					dataSrc:10
				},
				dom: 'Bfrtip',
				buttons: [
					'csv', 'excel','print'
				]
			});
			const tableData = divideTable(table);
			var i=0;
			for(var t in tableData){
				createCharts(tableData[t]);
				i++;
				if(i==chart_limit){break;}
			}
			setTableEvents(table);
		},
		error: function(xhr,ajaxOptions,thrownError){
			alert(xhr.status);
			alert(thrownError);
		}
	});	
}
//var search = search_fn;

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
	else if(filter=='semester_year')
	{
		semester_year_filter.push(self.innerHTML);
		$(self).attr('onclick','remove(this,"semester_year")');
	}
	else if(filter=='year')
	{
		year_filter.push(self.innerHTML);
		$(self).attr('onclick','remove(this,"year")');
	}
	else if(filter=='month')
	{
		month_filter.push(self.innerHTML);
		$(self).attr('onclick','remove(this,"month")');
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
	else if(filter=='semester_year')
	{
		semester_year_filter.splice( $.inArray(self.innerHTML, semester_year_filter), 1 );
		$(self).attr('onclick','add(this,"semester_year")');
	}
	else if(filter=='year')
	{
		year_filter.splice( $.inArray(self.innerHTML, year_filter), 1 );
		$(self).attr('onclick','add(this,"year")');
	}
	else if(filter=='month')
	{
		month_filter.splice( $.inArray(self.innerHTML, month_filter), 1 );
		$(self).attr('onclick','add(this,"month")');
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
</head>
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
	<div id='advance_searching'>
		<ul class="searching">
			<span class="h_shbn">指定學年：</span>
			@foreach ($semesters_year as $sy)
			<button type="button" class="shyr filter_btn" onclick="add(this,'semester_year')">{{ $sy }}</button>
			@endforeach
			<br>
		</ul>
		<ul class="searching">
			<span class="h_shbn">指定年：</span>
			@foreach ($year as $y)
				@if($y->year!="")
					<button type="button" class="shyr filter_btn" onclick="add(this,'year')">{{ $y->year }}</button>
				@endif
			@endforeach
			<br>
		</ul>
		<ul class="searching">
			<span class="h_shbn">指定月：</span>
			@foreach ($month as $m)
				@if($m->month!="")
					<button type="button" class="shyr filter_btn" onclick="add(this,'month')">{{ $m->month }}</button>
				@endif
			@endforeach
		</ul>
	</div>
	<span>*以上皆可複選</span>
</div>
<div id='data'>
</div>

</body>
</html>
