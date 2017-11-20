<?php session_start();
	 if(((!isset($_SESSION['sku_user_email'])) || (!isset($_SESSION['sku_role']))|| (!$_SESSION['sku_logged']==1)))
	 {
		echo "<script type=\"text/javascript\">window.close();</script>";
	 }
	date_default_timezone_set('GMT');
	include('config.php');
	$filter_data_field=" where 1=1 ";
	if(isset($_REQUEST['date']))
	{
		$date=$_REQUEST['date']; 
		$filter_data_field=$filter_data_field." and date_format(`date_range`,'%m-%d-%Y')='".$date."'";
	}
	if(isset($_REQUEST['sku']))
	{
		$sku=$_REQUEST['sku'];
		if($sku!="0")
		{
			$filter_data_field=$filter_data_field." and sku='".$sku."'";
		}
	}
	//here set the group concat maximum value
	$query="SET SESSION group_concat_max_len=150000;";
	mysql_query($query) or die(mysql_error());
?>

<html>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="css/Styles.css"/>
	<link rel="stylesheet" href="css/admindiv.css"/>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.js" type="text/javascript"></script>
	<script src="js/jquery-ui-1.10.3.min.js"></script> 
	<script src="js/main.js"></script> 
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	
<script type="text/javascript">
google.load("visualization", "1", {packages:["corechart"]});
var OrderedProductSalesDailyData_chart="";
var OrderedProductSalesDailyData="";
function OrderedProductSalesDaily(){
	OrderedProductSalesDailyData = google.visualization.arrayToDataTable([['Daily','Ordered Product Sales',{role: 'annotation'}],
	<?php 
	$Q = "SELECT sum(ordered_product_sales) as ordered_product_sales, date_format(`date_range`,'%m-%d-%Y') AS Daily FROM tbldashboard".$filter_data_field." group by date_range order by date_range asc";
	$R = mysql_query($Q)or die(mysql_error());
	$msg_text="";
	$nums_row=mysql_num_rows($R); 
	if($nums_row==0)
	{
		echo "['0',0,0],\r\n";
		$msg_text="No Data Found in Current Trends Please Try Again";
	}
	else
	{
		while($row = mysql_fetch_array($R)){
		$value= str_replace( '$', '', $row['ordered_product_sales'] );
		$value=str_replace(",","",$value);
		$value=round($value,2);
		$value1="$".$value;
		echo "['{$row['Daily']}',{$value},'{$value1}'],\r\n";
		} 
	}
	?>]);
	var OrderedProductSalesDailyDataSettings = {
	title : 'Ordered Product Sales Daily',
	//titlePosition: 'center',
	vAxis: { textStyle: {fontSize:12},title: "Ordered Product",titleTextStyle:{/*fontName: "Times",*/ italic: false,fontStyle: "normal"},titleFontSize:16,format: '$'},
	hAxis: { textStyle: {fontSize:12},title: "Month", titleTextStyle:{/*fontName: "Times",*/ italic: false,fontStyle: "normal"},titleFontSize:16},//titleFontSize:14 ,fontStyle:"normal"//or bold, italic, etc.
	seriesType: "bars",
	colors: ['#993300','#C10827'],
	legend: { position: 'none'},
	chartArea: {'width': '70%', 'height': '60%'},
	height: '100%',
	width: '100%',
	annotations: {textStyle:{fontSize: 9,}}
  };
	//this is for tool tip
	var formatter = new google.visualization.NumberFormat({prefix: '$'});
	// format column 1 of the DataTable
	formatter.format(OrderedProductSalesDailyData, 1);
   var chart = new google.visualization.ComboChart(document.getElementById('Dchart_div1'));
   chart.draw(OrderedProductSalesDailyData, OrderedProductSalesDailyDataSettings);
	google.visualization.events.addListener(chart, 'select', selectHandler);
   OrderedProductSalesDailyData_chart=chart;
   }

google.setOnLoadCallback(OrderedProductSalesDaily);

function selectHandler(e) {
var getData = OrderedProductSalesDailyData['Nf'];
	var selectedItem =OrderedProductSalesDailyData_chart.getSelection()[0];
	var column = getData[selectedItem['row']];//, getData['row']);
    var col=column['c'];
	var value=col[0];
	var date=value['v'];
	alert('The user selected ' + date);
	OrderedProductSalesDailyData_chart.setSelection([]);
}
</script>
	</head><title>SKU Analysis</title>
	<body style="background-color:#f1f1f1;">
		<center><div id="Dchart_div1" style="width:80%;float:left;height:50%;margin-left:10%"></div></center>
	</body>
</html>