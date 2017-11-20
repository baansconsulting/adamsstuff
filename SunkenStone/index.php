<?php session_start();
	 if(((!isset($_SESSION['sku_user_email'])) || (!isset($_SESSION['sku_role']))|| (!$_SESSION['sku_logged']==1)))
	 {
		header('Location:login.php');
	 }
	date_default_timezone_set('GMT');
	include('config.php');
	$user=$_SESSION["sku_user_email"];
	$user_name=$_SESSION["sku_user_name"];
	$user_role=$_SESSION['sku_role'];
	if(isset($_SESSION["temp_sku_user_id"]))
	{
		
		$user_id=$_SESSION["temp_sku_user_id"];
		
		if($user_id!=$_SESSION['sku_user_id'])
		{
			$user_role=0;
		}
	}
	else
	{
		
		$user_id=$_SESSION['sku_user_id'];
		
	}
	
	$filter_data_field=" Where 1=1 ";
	$allSku="";
	$whereDate="";
	$filterTotalCondition=" where 1=1 ";
	$filterSKU="";
	$filterDate="";
	$filterDateYearly="";
	$filterDateFieldYearly=" Where 1=1";
	$Wheredateyearly="";
	if(isset($_POST['submit']))
	{
		$sku=$_POST['sku'];
		if($sku!="0")
		{
			$filter_data_field=$filter_data_field." and sku='".$sku."'";
			$filterTotalCondition=$filterTotalCondition." and sku='".$sku."'";
			$filterSKU=$filterSKU." and sku='".$sku."'";
			$filterDateFieldYearly=$filterDateFieldYearly." and sku='".$sku."'"; 
		}
		else
		{
			if($user_role!=1)
			{
				$allSku=getAllSku($user_id);
				$filter_data_field=$filter_data_field." and sku in(".$allSku.")";
				$filterTotalCondition=$filterTotalCondition." and sku in(".$allSku.")";
				$filterSKU=$filterSKU." and sku in(".$allSku.")";
				$filterDateFieldYearly=$filterDateFieldYearly." and sku in(".$allSku.")";
			}
		}
		if(($_POST['txtFromDate']!="") && ($_POST['txtToDate']!=""))
		{	
			$fromDate=date("Y-m-d", strtotime($_POST['txtFromDate']));
			$toDate=date("Y-m-d", strtotime($_POST['txtToDate']));
			$filter_data_field=$filter_data_field." and date_range >= '".$fromDate."' and date_range <='".$toDate."'";
			$whereDate=" and date_range >= '".$fromDate."' and date_range <='".$toDate."'";
			$filterDate=" daily_date_range >= '".$fromDate."' and `daily_date_range` <= '".$toDate."'";
			$filterDateYearly=" daily_date_range >= '2015-01-01' and `daily_date_range` <= '".$toDate."'";
			$filterDateFieldYearly=$filterDateFieldYearly." and date_range >= '2015-01-01' and date_range <='".$toDate."'";
			$Wheredateyearly=" and date_range >= '2015-01-01' and date_range <='".$toDate."'";
		}
		else if(($_POST['txtFromDate']=="") && ($_POST['txtToDate']!=""))
		{
			$fromDate="";
			$toDate=date("Y-m-d", strtotime($_POST['txtToDate']));
			$filter_data_field=$filter_data_field." and date_range >= DATE_SUB('".$toDate."',INTERVAL 12 MONTH) and date_range <='".$toDate."'";
			$whereDate=" and date_range >= DATE_SUB('".$toDate."',INTERVAL 12 MONTH) and date_range <='".$toDate."'";
			$filterDate=" daily_date_range >= DATE_SUB('".$toDate."',INTERVAL 12 MONTH) and daily_date_range <='".$toDate."'";
			$filterDateYearly=" daily_date_range >= '2015-01-01' and `daily_date_range` <= '".$toDate."'";
			$filterDateFieldYearly=$filterDateFieldYearly." and date_range >= '2015-01-01' and date_range <='".$toDate."'";
			$Wheredateyearly=" and date_range >= '2015-01-01' and date_range <='".$toDate."'";
		}
		else if(($_POST['txtFromDate']!="") && ($_POST['txtToDate']==""))
		{
			$fromDate=date("Y-m-d", strtotime($_POST['txtFromDate']));
			$toDate="";
			$filter_data_field=$filter_data_field." and date_range >= '".$fromDate."' and date_range <=ADDDATE('".$fromDate."',INTERVAL 12 MONTH)";
			$whereDate=" and date_range >= '".$fromDate."' and date_range <= ADDDATE('".$fromDate."',INTERVAL 12 MONTH)";
			$filterDate=" daily_date_range >= '".$fromDate."' and daily_date_range <= ADDDATE('".$fromDate."',INTERVAL 12 MONTH)";
			$filterDateYearly=" daily_date_range >= '2015-01-01' and `daily_date_range` <= '".$toDate."'";
			$filterDateFieldYearly=$filterDateFieldYearly." and date_range >= '2015-01-01' and date_range <='".$toDate."'";
			$Wheredateyearly=" and date_range >= '2015-01-01' and date_range <='".$toDate."'";
		}
		$toDate=$_POST['txtToDate'];
		$fromDate=$_POST['txtFromDate'];
		$tb=$_POST['tb'];
		//echo "post from:".$fromDate."-".$toDate;
	}
	else
	{
		//echo 1;
		//$Q = "SELECT if(`from_date`='0000-00-00',CURDATE(),`from_date`) as from_date,if(`to_date`='0000-00-00',CURDATE(),`to_date`) as to_date,`sku` FROM `tbltemp` WHERE `user_id`=".$user_id;
		//echo $Q;
		
		if ($user_role==1){
			$Q = "SELECT if(`from_date`='0000-00-00','',`from_date`) as from_date,if(`to_date`='0000-00-00','',`to_date`) as to_date,`sku` FROM `tbltemp` WHERE `user_id`=".$user_id;
		}
		else{
			$Q = "SELECT if(`from_date`='0000-00-00','',`from_date`) as from_date,if(`to_date`='0000-00-00','',`to_date`) as to_date,`sku` FROM `tbltemp` WHERE `user_id`=".$_SESSION['sku_user_id'];
		}
		
		$R = mysql_query($Q)or die(mysql_error());
		$row = mysql_fetch_array($R);
		//echo $Q;
		$sku=$row['sku'];
		if($sku!="0")
		{
			if ($sku!="")
			{
				$filter_data_field=$filter_data_field." and sku='".$sku."'";
				$filterTotalCondition=$filterTotalCondition." and sku='".$sku."'";
				$filterSKU=$filterSKU." and sku='".$sku."'";
				$filterDateFieldYearly=$filterDateFieldYearly." and sku='".$sku."'";
			}
		}
		else
		{
			if($user_role!=1)
			{
				$allSku=getAllSku($user_id);
				$filter_data_field=$filter_data_field." and sku in(".$allSku.")";
				$filterTotalCondition=$filterTotalCondition." and sku in(".$allSku.")";
				$filterSKU=$filterSKU." and sku in(".$allSku.")";
				$filterDateFieldYearly=$filterDateFieldYearly." and sku in(".$allSku.")";
			}
		}
		if(($row['from_date']!="") && ($row['to_date']!=""))
		{	
			$fromDate=date("m/d/Y", strtotime($row['from_date']));
			$toDate=date("m/d/Y", strtotime($row['to_date']));
			$filter_data_field=$filter_data_field." and date_range >= '".$row['from_date']."' and date_range<='".$row['to_date']."'";
			$whereDate=" and date_range >= '".$row['from_date']."' and date_range <='".$row['to_date']."'";
			$filterDate=" daily_date_range >= '".$row['from_date']."' and daily_date_range <='".$row['to_date']."'";
			$filterDateYearly=" daily_date_range >= '2015-01-01' and `daily_date_range` <= '".$row['to_date']."'";
			$filterDateFieldYearly=$filterDateFieldYearly." and date_range >= '2015-01-01' and date_range <='".$row['to_date']."'";
			$Wheredateyearly=" and date_range >= '2015-01-01' and date_range <='".$row['to_date']."'";
		}
		else if(($row['from_date']=="") && ($row['to_date']!=""))
		{
			$fromDate="";
			$toDate=date("m/d/Y", strtotime($row['to_date']));
			$filter_data_field=$filter_data_field." and date_range >= DATE_SUB('".$row['to_date']."',INTERVAL 12 MONTH) and date_range <='".$row['to_date']."'";
			$whereDate=" and date_range >= DATE_SUB('".$row['to_date']."',INTERVAL 12 MONTH) and date_range <='".$row['to_date']."'";
			$filterDate=" daily_date_range >= DATE_SUB('".$row['to_date']."',INTERVAL 12 MONTH) and daily_date_range <='".$row['to_date']."'";
			$filterDateYearly=" daily_date_range >= '2015-01-01' and `daily_date_range` <= '".$row['to_date']."'";
			$filterDateFieldYearly=$filterDateFieldYearly." and date_range >= '2015-01-01' and date_range <='".$row['to_date']."'";
			$Wheredateyearly=" and date_range >= '2015-01-01' and date_range <='".$row['to_date']."'";
		}
		else if(($row['from_date']!="") && ($row['to_date']==""))
		{
			$fromDate=date("m/d/Y", strtotime($row['from_date']));
			$toDate="";
			$filter_data_field=$filter_data_field." and date_range between '". $row['from_date']."' and ADDDATE('".$row['from_date']."',INTERVAL 12 MONTH)";
			$whereDate=" and daily_date_range >= '".$row['from_date']."' and daily_date_range <=ADDDATE('".$row['from_date']."',INTERVAL 12 MONTH)";
			$filterDate=" daily_date_range >= '".$row['from_date']."' and `daily_date_range` <= ADDDATE('".$row['from_date']."',INTERVAL 12 MONTH)";
		}
		else{
			$toDate="";
			$fromDate="";
		}
		
		//$tb="Daily";
		//21 Aug
		$tb="Weekly";
		//echo "NO post from:".$fromDate."-".$toDate;
	}
	
	
	//retrun all sku's which are assign to user
	function getAllSku($u_id)
	{
		$allSku="";
		$rs=mysql_query("Select sku from tblusersku where user_id=".$u_id.";") or die(mysql_error());
		while($row=mysql_fetch_array($rs))
		{
			$allSku=$allSku."'".$row["sku"]."',";
		}
		return rtrim($allSku, ",");
	}	
	//here set the group concat maximum value
	$query="SET SESSION group_concat_max_len=150000;";
	mysql_query($query) or die(mysql_error());
	//echo $filter_data_field;
	$total=0;
	$avg=1;
	$R = mysql_query("select max(date_range) as date_range from tblinventory")or die(mysql_error());
	$row = mysql_fetch_array($R);
	//echo $Q;
	$mxd=$row['date_range'];
	$fulfillable=0;
	$reserved=0;
	$total_quantity=0;
	$qty=0;
	if ($mxd!=""){
		$qry="SELECT sum(`afn_fulfillable_quantity`) as afn_fulfillable_quantity , sum(`afn_reserved_quantity`) as afn_reserved_quantity, sum(`afn_total_quantity`) as afn_total_quantity, sum(`afn_inbound_working_quantity`+`afn_inbound_shipped_quantity`+`afn_inbound_receiving_quantity`) as qty 
		FROM `tblinventory`".$filterTotalCondition." and date_range='".$mxd."'";
		//echo $qry;
		$R = mysql_query($qry)or die(mysql_error());
		$row = mysql_fetch_array($R);
		$fulfillable=$row['afn_fulfillable_quantity'];
		$reserved=$row['afn_reserved_quantity'];
		$total_quantity=$row['afn_total_quantity'];
		$qty=$row['qty'];
	}
	$totalInve="<b>Inventory -- Total : </b>".$total_quantity."&nbsp;&nbsp;&nbsp; <b>Fulfillable : </b>".$fulfillable." &nbsp;&nbsp;&nbsp;<b>Reserved : </b>".$reserved."<b>&nbsp;&nbsp;&nbsp;Inbound : </b>".$qty;
	$fdt= date("l",strtotime($fromDate));
	$tdt= date("l",strtotime($toDate));
	
?>

<html>
<style>
 
 </style>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="css/Styles.css"/>
	<link rel="stylesheet" href="css/admindiv.css"/>
	<script src="js/jquery.js"></script> 
	<script src="js/main_new.js"></script> 
	<script src="js/highcharts.js"></script>
	<!--script src="js/highcharts-3d.js"></script-->
	<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
$(document).ready(function() {
	$('#cboUsers').show();
	//document.oncontextmenu = document.body.oncontextmenu = function() {return false;}
	$('#home').css("background-color","darkred");
	$("#sku").customselect();	
	$("#tabsProfile").tabs();
	$( function() {
		$('#txtFromDate').datepicker({dateFormat: 'mm/dd/yy'});
		$('#txtToDate').datepicker({dateFormat: 'mm/dd/yy'});
		$('#uploaddate').datepicker({dateFormat: 'mm/dd/yy'});
		//21 Aug
		// $("#tabsProfile a[href='#<?php echo $tb;?>'").click();
		$("#tabsProfile a[href='#Weekly-Trends'").click();
		
	  } );
	drawVisualization();
}); 

var TabID=[];
TabID[0]="Daily";
var Tab="";	
$(function () {
	$("#tabsProfile").tabs({
	  beforeActivate: function (event, ui) {
		 Tab=ui.newPanel.attr("id");
		 TabID=Tab.split('-Trends');
		 //alert(TabID[0]);
		 $("#tb").val(TabID[0]+"-Trends");
		 if((TabID[0]=="Admin")||(TabID[0]=="Report"))
		 {
			$('#filterC').hide();
		 }
		 else
		 {
			$('#filterC').show();
			
		 }
	  }
	});
});	
</script>

<div id="i"></div>

<script type="text/javascript">
//***********************************************************Daily Data Start************************************************************
var chartDate=[];
var chartData=[];
var modalChart="modalChart";
function SessionChartDaily(id)
{
	
	 chartDate=[];
	 chartData=[];
	 if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="Dchart_div1";
	 }
	<?php
	
	$Q="SELECT IFNULL(SUM(`sessions`),0) as sessions, date_format(date1.`daily_date_range`,'%m-%d-%y') as Daily 
	FROM (Select daily_date_range from date_range Where ".$filterDate.") date1 
	left Join (select sessions,`date_range` from `tbldashboard` ".$filter_data_field.") as `tbldashboard` on (date1.`daily_date_range`=`tbldashboard`.`date_range`)
	group by date1.daily_date_range order by Daily Asc";
	$R = mysql_query($Q)or die(mysql_error());
	$nums_row=mysql_num_rows($R);
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		while($row = mysql_fetch_array($R)){
			$value= str_replace( ',', '', $row['sessions'] );
			$total=$total+$value;
			$avg=$avg+1;
			?>
			chartDate=chartDate.concat(["<?php echo $row['Daily'];?>"]);
			chartData=chartData.concat([<?php echo floatval($value);?>]);
			<?php 
		} 
	}
	?>
	buildChart(chartDate,chartData,id,"column","Sessions Daily ("+"<?php echo "Total: ".$total.", Average: ".round(($total/$avg),2);?>)","Session","Daily","#69A4DE","{point.y}");
}

function ConversionChartDaily(id){
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="Dchart_div2";
	 }
	<?php
	//$Q = "SELECT avg(`unit_session_percentage`) as unit_session_percentage,date_format(`date_range`,'%m-%d-%y') AS Daily FROM tbldashboard".$filter_data_field." group by date_range order by date_range asc";
	$Q="SELECT IFNULL((1-((sum(sessions)-sum(units_ordered))/Sum(sessions)))*100,0) as unit_session_percentage, date_format(date1.`daily_date_range`,'%m-%d-%y') as Daily 
	FROM (Select daily_date_range from date_range Where ".$filterDate.") date1 
	left Join (select unit_session_percentage,`date_range`,sessions,units_ordered from `tbldashboard` ".$filter_data_field.") as `tbldashboard` on (date1.`daily_date_range`=`tbldashboard`.`date_range`)
	group by date1.daily_date_range order by Daily Asc";
	$R = mysql_query($Q)or die(mysql_error());
	$msg_text="";
	$nums_row=mysql_num_rows($R);
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
			$value= round(str_replace( '%', '', $row['unit_session_percentage']),2);
			$total=$total+$value;
			$avg=$avg+1;
			?>
			chartDate=chartDate.concat(["<?php echo $row['Daily'];?>"]);
			chartData=chartData.concat([<?php echo $value;?>]);
			<?php 
		}	
	}
	?>
	buildChart(chartDate,chartData,id,"column","Conversion Rate Daily ("+"<?php echo "Average: ".round(($total/$avg),2)."%";?>)","Unit Session Percentage","Daily","#91CD6A","{point.y}%");
}

function UnitOrderDataDaily(id)
{
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="Dchart_div3";
	 }
	<?php 
	//$Q = "SELECT sum(`units_ordered`) as units_ordered, date_format(`date_range`,'%m-%d-%y') AS Daily FROM tbldashboard".$filter_data_field." group by date_range order by date_range asc";
	$Q="SELECT IFNULL(sum(`units_ordered`),0) as units_ordered, date_format(date1.`daily_date_range`,'%m-%d-%y') as Daily 
	FROM (Select daily_date_range from date_range Where ".$filterDate.") date1 
	left Join (select units_ordered,`date_range` from `tbldashboard` ".$filter_data_field.") as `tbldashboard` on (date1.`daily_date_range`=`tbldashboard`.`date_range`)
	group by date1.`daily_date_range` order by Daily Asc";
	$R = mysql_query($Q);
	$i=0;
	$nums_row=mysql_num_rows($R);
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
		$value= $row['units_ordered'];
		$total=$total+$value;
		$avg=$avg+1;
		?>
			chartDate=chartDate.concat(["<?php echo $row['Daily'];?>"]);
			chartData=chartData.concat([<?php echo $value;?>]);
		<?php 
		} 
	}
	?>
	buildChart(chartDate,chartData,id,"column","Units Ordered Daily ("+"<?php echo "Total: ".$total.", Average: ".round(($total/$avg),2);?>)","Unit Ordered","Daily","#E36068","{point.y}");
}

function SalesPriceDataDaily(id){
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="Dchart_div4";
	 }
	<?php 
	//$Q = "SELECT sum(replace(`ordered_product_sales`,'$','')) / sum(`units_ordered`) as `selling_price`, date_format(`date_range`,'%m-%d-%y') AS Daily FROM tbldashboard".$filter_data_field." group by date_range order by date_range asc";
	$Q="SELECT IFNULL(sum(replace(`ordered_product_sales`,'$','')) / sum(`units_ordered`),0) as selling_price, date_format(date1.`daily_date_range`,'%m-%d-%y') as Daily 
	FROM (Select daily_date_range from date_range Where ".$filterDate.") date1 
	left Join (select ordered_product_sales,units_ordered,`date_range` from `tbldashboard` ".$filter_data_field.") as `tbldashboard` on (date1.`daily_date_range`=`tbldashboard`.`date_range`)
	group by date1.`daily_date_range` order by Daily Asc";
	$R = mysql_query($Q);
	$nums_row=mysql_num_rows($R);
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
			$value= round($row['selling_price'],2);
			$total=$total+$value;
			$avg=$avg+1;
			?>
			chartDate=chartDate.concat(["<?php echo $row['Daily'];?>"]);
			chartData=chartData.concat([<?php echo floatval($value);?>]);
			<?php 
		} 
	}
	?>
	buildChart(chartDate,chartData,id,"column","Sales Price Daily ("+"<?php echo "Average: $".round(($total/$avg),2);?>)","Sales Price","Daily","#CC99FF","${point.y}");
}

function BuyBoxPercentageDaily(id){
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="Dchart_div5";
	 }
	<?php 
	//$Q = "SELECT avg(buy_box_percentage) as buy_box_percentage, date_format(`date_range`,'%m-%d-%y') AS Daily FROM tbldashboard".$filter_data_field." group by date_range order by date_range asc";
	$Q="SELECT IFNULL(avg(buy_box_percentage),0) as buy_box_percentage, date_format(date1.`daily_date_range`,'%m-%d-%y') as Daily 
	FROM (Select daily_date_range from date_range Where ".$filterDate.") date1 
	left Join (select buy_box_percentage,`date_range` from `tbldashboard` ".$filter_data_field.") as `tbldashboard` on (date1.`daily_date_range`=`tbldashboard`.`date_range`)
	group by date1.`daily_date_range` order by Daily Asc";
	
	$R = mysql_query($Q)or die(mysql_error());
	$msg_text="";
	$nums_row=mysql_num_rows($R); 
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
		$value= str_replace( '%', '', $row['buy_box_percentage'] );
		$value=round($value,2);
		$total=$total+$value;
		$avg=$avg+1;
		?>
			chartDate=chartDate.concat(["<?php echo $row['Daily'];?>"]);
			chartData=chartData.concat([<?php echo floatval($value);?>]);
			<?php 
		} 
	}
?>
	buildChart(chartDate,chartData,id,"column","Buy Box % Daily ("+"<?php echo "Average: ".round(($total/$avg),2)."%";?>)","Buy Box % Value","Daily","#2D7094","{point.y}%");
}

function OrderedProductSalesDaily(id){
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="Dchart_div6";
	 }
	<?php 
	//$Q = "SELECT sum(ordered_product_sales) as ordered_product_sales, date_format(`date_range`,'%m-%d-%y') AS Daily FROM tbldashboard".$filter_data_field." group by date_range order by date_range asc";
	$Q="SELECT IFNULL(sum(ordered_product_sales),0) as ordered_product_sales, date_format(date1.`daily_date_range`,'%m-%d-%y') as Daily 
	FROM (Select daily_date_range from date_range Where ".$filterDate.") date1 
	left Join (select ordered_product_sales,`date_range` from `tbldashboard` ".$filter_data_field.") as `tbldashboard` on (date1.`daily_date_range`=`tbldashboard`.`date_range`)
	group by date1.`daily_date_range` order by Daily Asc";
	
	$R = mysql_query($Q)or die(mysql_error());
	$msg_text="";
	$nums_row=mysql_num_rows($R); 
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
		$value= str_replace( '$', '', $row['ordered_product_sales'] );
		$value=str_replace(",","",$value);
		$value=round($value,2);
		$total=$total+$value;
		$avg=$avg+1;
		?>
			chartDate=chartDate.concat(["<?php echo $row['Daily'];?>"]);
			chartData=chartData.concat([<?php echo floatval($value);?>]);
		<?php 
		} 
	}
	?>
	buildChart(chartDate,chartData,id,"column","Ordered Product Sales Daily ("+"<?php echo "Total: $".$total.", Average: $".round(($total/$avg),2);?>)","Order Product","Daily","#993300","${point.y}");
}

function BuyBoxPercentageDaily_Weighted(id){
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="Dchart_div7";
	 }
	<?php 
	if($user_role==1)
	{
		// $Q = "SELECT sum(buy_box_percentage * `units_ordered`) /sum(`units_ordered`) as weighted_buy_box_pct, date_format(`date_range`,'%m-%d-%y') AS Daily
		// FROM tbldashboard where 1=1 ".$whereDate." group by date_range asc";
		$Q="SELECT IFNULL((IFNULL(sum(buy_box_percentage * `units_ordered`),0) /IFNULL(sum(`units_ordered`),0)),0) as weighted_buy_box_pct, date_format(date1.`daily_date_range`,'%m-%d-%y') AS Daily
		FROM ((Select daily_date_range From date_range Where ".$filterDate.")date1 
		left Join (select buy_box_percentage,units_ordered,`date_range` from `tbldashboard` where 1=1 ".$whereDate.") as `tbldashboard`
		on (date1.`daily_date_range`=`tbldashboard`.`date_range`)) group by date1.daily_date_range asc";
	}
	else
	{
		// $Q = "SELECT sum(buy_box_percentage * `units_ordered`) /sum(`units_ordered`) as weighted_buy_box_pct, date_format(`date_range`,'%m-%d-%y') AS Daily
		// FROM tbldashboard,tblusersku where tblusersku.sku=tbldashboard.sku ".$whereDate. " and tblusersku.user_id=".$user_id." group by date_range asc";
		/*$Q = "SELECT IFNULL((IFNULL(sum(buy_box_percentage * `units_ordered`),0) /IFNULL(sum(`units_ordered`),0)),0) as weighted_buy_box_pct, date_format(date1.`daily_date_range`,'%m-%d-%y') AS Daily
		FROM (((Select daily_date_range From date_range Where ".$filterDate.")date1 
		left Join (select buy_box_percentage,units_ordered,`date_range`,sku from `tbldashboard` where 1=1 ".$whereDate.") as `tbldashboard`
		on (date1.`daily_date_range`=`tbldashboard`.`date_range`))
		left Join tblusersku on (tblusersku.sku=tbldashboard.sku) And tblusersku.user_id=".$user_id.") 
		group by date1.daily_date_range Order by Daily Asc"; */

		
		$Q = "SELECT IFNULL((IFNULL(sum(buy_box_percentage * `units_ordered`),0) /IFNULL(sum(`units_ordered`),0)),0) as weighted_buy_box_pct, date_format(date1.`daily_date_range`,'%m-%d-%y') AS Daily
		FROM ((Select daily_date_range From date_range Where ".$filterDate.")date1 
		left Join (SELECT buy_box_percentage,units_ordered,`date_range`
		 FROM tbldashboard,tblusersku where tblusersku.sku=tbldashboard.sku ".$whereDate. " and tblusersku.user_id=".$user_id." ) as `tbldashboard`
		on (date1.`daily_date_range`=`tbldashboard`.`date_range`))
		group by date1.daily_date_range Order by Daily Asc";
		

	}
	$R = mysql_query($Q)or die(mysql_error());
	$msg_text="";
	$nums_row=mysql_num_rows($R); 
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
			$value= $row['weighted_buy_box_pct'];
			$value=round($value,2);
			$total=$total+$value;
			$avg=$avg+1;
			?>
				chartDate=chartDate.concat(["<?php echo $row['Daily'];?>"]);
				chartData=chartData.concat([<?php echo floatval($value);?>]);
			<?php 
		} 
	}
?>
	buildChart(chartDate,chartData,id,"column","Total SKU's Buy Box Daily Average ("+"<?php echo " Average: ".round(($total/$avg),2)."%";?>)","Weighted Buy Box","Daily","#2D7094","{point.y}");
}
	
    
function OrderedProductSalesDaily_2(id){
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="Dchart_div8";
	 }
	<?php 
	if($user_role==1)
	{
		//$Q="SELECT sum(tbldashboard.ordered_product_sales) as ordered_product_sales, date_format(tbldashboard.date_range,'%m-%d-%y') AS Daily FROM tbldashboard where 1=1 ".$whereDate. " group by tbldashboard.date_range order by tbldashboard.date_range asc";
		$Q="SELECT IFNULL(sum(ordered_product_sales),0) as ordered_product_sales, date_format(date1.`daily_date_range`,'%m-%d-%y') AS Daily
		FROM ((Select daily_date_range From date_range Where ".$filterDate.")date1 
		left Join (select ordered_product_sales,units_ordered,`date_range` from `tbldashboard` where 1=1 ".$whereDate.") as `tbldashboard`
		on (date1.`daily_date_range`=`tbldashboard`.`date_range`)) group by date1.daily_date_range asc";
	} 
	else
	{
		
		$Q = "SELECT IFNULL(sum(ordered_product_sales),0) as ordered_product_sales, date_format(date1.`daily_date_range`,'%m-%d-%y') AS Daily
		FROM ((Select daily_date_range From date_range Where ".$filterDate.")date1 
		left Join (SELECT sum(tbldashboard.ordered_product_sales) as ordered_product_sales, tbldashboard.date_range FROM tbldashboard,tblusersku where tblusersku.sku=tbldashboard.sku ".$whereDate. " and tblusersku.user_id=".$user_id." group by tbldashboard.date_range) as `tbldashboard`
		on (date1.`daily_date_range`=`tbldashboard`.`date_range`))
				group by date1.daily_date_range Order by Daily Asc";
		
	}
	$R = mysql_query($Q)or die(mysql_error());
	$msg_text="";
	$nums_row=mysql_num_rows($R); 
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
		$value= str_replace( '$', '', $row['ordered_product_sales'] );
		$value=str_replace(",","",$value);
		$value=round($value,2);
		$total=$total+$value;
		$avg=$avg+1;
		?>
			chartDate=chartDate.concat(["<?php echo $row['Daily'];?>"]);
			chartData=chartData.concat([<?php echo floatval($value);?>]);
		<?php 
		} 
	}
	?>
	buildChart(chartDate,chartData,id,"column","Total SKU's Sales Daily ("+"<?php echo "Total: $".$total.", Average: $".round(($total/$avg),2);?>)","Order Product","Daily","#993300","${point.y}");
}

// function PieChartUnitOrderData(id)
// {
	// id=modalChart;
	// chartModalUnitdata.style.display = "block";
// }
// function PieChartUnitOrderDetails(id)
// {
	// if(id){ 
		// id=modalChart;
		// modal.style.display = "block";
	 // }
	 // else
	 // {
		// id="DPie_div1";
	 // }
	// buildPiechartUnitOrder(id);
// }
// function PieDataDailyData2(id)
// {
	// id=modalChart;
	// chartModaldata.style.display = "block";
// }
// function PieChartDailyData2(id){
	// chartskuname=[];
	// chartDatavalue=[];
	// if(id){ 
		// id=modalChart;
		// modal.style.display = "block";
	 // }
	 // else
	 // {
		// id="DPie_div2";
	 // }
	
	// buildpiechart(id);
// }
function buildpiechart(div)
{
	Highcharts.chart(div, 
	{
		chart:
		{
			plotbackgroundcolor: null,
			plotborderwidth: null,
			plotshadow: false,
			
		},
		title: 
		{
			text: 'Unit BreakDown(Revenue)'
		},
		tooltip: {
                   // pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			
				},
        
		 plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },
		series: 
		[{
			type: 'pie',
			name: 'Revenue',
			
			data: 
			[
				<?php
				
				if($user_role==1)
				{
				$Q="SELECT sku,Round(IFNULL(sum(ordered_product_sales),0),2) as ordered_product_sales From `tbldashboard` where 1=1 " .$whereDate."   group by sku order by ordered_product_sales desc";
				}
			else{
				$Q="SELECT tblusersku.sku as sku , Round(IFNULL(sum(ordered_product_sales),0),2)  as ordered_product_sales FROM tbldashboard,tblusersku where tblusersku.sku=tbldashboard.sku " .$whereDate." and tblusersku.user_id=".$user_id." group by tblusersku.sku Order by ordered_product_sales desc";
			
				}
				
				$R = mysql_query($Q)or die(mysql_error());
				$nums_row=mysql_num_rows($R); 
				$avg=0;
				$total=0;
				while($row = mysql_fetch_array($R)){
				$value= str_replace( '$', '', $row['ordered_product_sales'] );
				$data=$row['sku'];
				$value1= $row['ordered_product_sales'];
				
				?>
				[
					'<?php echo $data . '<span style="color:red;">  (' . $value1 . ') </span>'?>',  <?php echo  $value1;  ?>
					
				],
				<?php 
				} 
				?>
			]
		}]
		
	});
}

function buildPiechartUnitOrder(div)
{
	Highcharts.chart(div, 
	{
		chart:
		{
			plotbackgroundcolor: null,
			plotborderwidth: null,
			plotshadow: false,
			
		},
		title: 
		{
			text: 'Unit BreakDown(Number of Unit)'
		},
		tooltip: {
                   // pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			
				},
        
		 plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },
		series: 
		[{
			type: 'pie',
			name: 'Number of Unit',
			
			data: 
			[
				<?php
				
				if($user_role==1)
				{
				$Q="SELECT sku,Round(IFNULL(sum(units_ordered),0),0) as ordered_product_sales From `tbldashboard` where 1=1 " .$whereDate."   group by sku order by ordered_product_sales desc";
				}
			else{
				$Q="SELECT tblusersku.sku as sku , Round(IFNULL(sum(units_ordered),0),0)  as ordered_product_sales FROM tbldashboard,tblusersku where tblusersku.sku=tbldashboard.sku " .$whereDate." and tblusersku.user_id=".$user_id." group by tblusersku.sku Order by ordered_product_sales desc";
			
				}
				
				$R = mysql_query($Q)or die(mysql_error());
				$nums_row=mysql_num_rows($R); 
				$avg=0;
				$total=0;
				while($row = mysql_fetch_array($R)){
				$value= str_replace( '$', '', $row['ordered_product_sales'] );
				$data=$row['sku'];
				$value1= $row['ordered_product_sales'];
				
				?>
				[
					'<?php echo $data . '<span style="color:red;">  (' . $value1 . ') </span>'?>',  <?php echo  $value1;  ?>
					
				],
				<?php 
				} 
				?>
			]
		}]
		
	});
}



	
//**********************************************************Daily Data End************************************************************

//***********************************************************Weekly Data Start************************************************************

function SessionChartWeekly(id){
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="Wchart_div1";
	 }
	<?php 
	//$Q = "SELECT sum(sessions) as sessions,(DATE_ADD(date_range, INTERVAL case when WEEKDAY(date_range)=6 then 8 else (7-WEEKDAY(date_range)) END DAY)) as week FROM tbldashboard ".$filter_data_field." GROUP BY (DATE_ADD(date_range, INTERVAL case when WEEKDAY(date_range)=6 then 8 else (7-WEEKDAY(date_range)) END DAY)) order by date_range asc;";
	$Q ="SELECT IFNULL(SUM(`sessions`),0) as sessions, date_format(date1.`weekly_date_range`,'%m-%d-%Y') as week 
	FROM (Select daily_date_range,weekly_date_range from date_range Where ".$filterDate.")date1 
	left Join (select sessions,`date_range` from `tbldashboard` ".$filter_data_field.") as `tbldashboard` 
	on (date1.`daily_date_range`=`tbldashboard`.`date_range`)
	group by date1.`weekly_date_range` order by week Asc";
	$R = mysql_query($Q)or die(mysql_error());
	$msg_text="";
	$nums_row=mysql_num_rows($R); 
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
		$value= str_replace( ',', '', $row['sessions'] );
		$total=$total+$value;
		$avg=$avg+1;
		?>
			chartDate=chartDate.concat(["<?php echo $row['week'];?>"]);
			chartData=chartData.concat([<?php echo floatval($value);?>]);
		<?php 
		} 
	}
	?>
	buildChart(chartDate,chartData,id,"column","Sessions Weekly ("+"<?php echo "Total: ".$total.", Average: ".round(($total/$avg),2);?>)","Session","Week","#69A4DE","{point.y}");
}

function ConversionChartWeekly(id){
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="Wchart_div2";
	 }
	<?php 
	//$Q = "SELECT avg(unit_session_percentage) as unit_session_percentage,(DATE_ADD(date_range, INTERVAL case when WEEKDAY(date_range)=6 then 8 else (7-WEEKDAY(date_range)) END DAY)) as week FROM tbldashboard ".$filter_data_field." GROUP BY (DATE_ADD(date_range, INTERVAL case when WEEKDAY(date_range)=6 then 8 else (7-WEEKDAY(date_range)) END DAY)) order by date_range asc;";
	$Q ="SELECT IFNULL((1-((sum(sessions)-sum(units_ordered))/Sum(sessions)))*100,0) as unit_session_percentage, date_format(date1.`weekly_date_range`,'%m-%d-%Y') as week 
	FROM (Select daily_date_range,weekly_date_range from date_range Where ".$filterDate.")date1 
	left Join (select unit_session_percentage,`date_range`,sessions,units_ordered from `tbldashboard` ".$filter_data_field.") as `tbldashboard` 
	on (date1.`daily_date_range`=`tbldashboard`.`date_range`)
	group by date1.`weekly_date_range` order by week Asc";
	$R = mysql_query($Q);
	$nums_row=mysql_num_rows($R);
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
			$value= round(str_replace( '%', '', $row['unit_session_percentage']),2);
			$total=$total+$value;
			$avg=$avg+1;
		?>
			chartDate=chartDate.concat(["<?php echo $row['week'];?>"]);
			chartData=chartData.concat([<?php echo $value;?>]);
		<?php 
		} 
	}
?>
	buildChart(chartDate,chartData,id,"column","Conversion Rate Weekly ("+"<?php echo "Average: ".round(($total/$avg),2)."%";?>)","Unit Session Percentage","Week","#91CD6A","{point.y}%");
}


function UnitOrderDataWeekly(id){
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="Wchart_div3";
	 }
	<?php 
	//$Q = "SELECT sum(`units_ordered`) as units_ordered,(DATE_ADD(date_range, INTERVAL case when WEEKDAY(date_range)=6 then 8 else (7-WEEKDAY(date_range)) END DAY)) as week FROM tbldashboard ".$filter_data_field." GROUP BY (DATE_ADD(date_range, INTERVAL case when WEEKDAY(date_range)=6 then 8 else (7-WEEKDAY(date_range)) END DAY)) order by date_range asc;";
	$Q ="SELECT IFNULL(sum(`units_ordered`),0) as units_ordered, date_format(date1.`weekly_date_range`,'%m-%d-%Y') as week 
	FROM (Select daily_date_range,weekly_date_range from date_range Where ".$filterDate.")date1 
	left Join (select units_ordered,`date_range` from `tbldashboard` ".$filter_data_field.") as `tbldashboard` 
	on (date1.`daily_date_range`=`tbldashboard`.`date_range`)
	group by date1.`weekly_date_range` order by week Asc";
	$R = mysql_query($Q);
	$i=0;
	$nums_row=mysql_num_rows($R);
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
		$value= $row['units_ordered'];
		$total=$total+$value;
		$avg=$avg+1;
		?>
			chartDate=chartDate.concat(["<?php echo $row['week'];?>"]);
			chartData=chartData.concat([<?php echo $value;?>]);
		<?php 
		} 
	}
	?>
	buildChart(chartDate,chartData,id,"column","Units Ordered Weekly ("+"<?php echo "Total: ".$total.", Average: ".round(($total/$avg),2);?>)","Unit Ordered","Week","#E36068","{point.y}");
}

function SalesPriceDataWeekly(id){
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="Wchart_div4";
	 }
	<?php 
	//$Q = "SELECT sum(replace(`ordered_product_sales`,'$','')) / sum(`units_ordered`) as `selling_price`,(DATE_ADD(date_range, INTERVAL case when WEEKDAY(date_range)=6 then 8 else (7-WEEKDAY(date_range)) END DAY)) as week FROM tbldashboard ".$filter_data_field." GROUP BY (DATE_ADD(date_range, INTERVAL case when WEEKDAY(date_range)=6 then 8 else (7-WEEKDAY(date_range)) END DAY)) order by date_range asc;";
	$Q ="SELECT IFNULL(sum(replace(`ordered_product_sales`,'$','')) / sum(`units_ordered`),0) as selling_price, date_format(date1.`weekly_date_range`,'%m-%d-%Y') as week 
	FROM (Select daily_date_range,weekly_date_range from date_range Where ".$filterDate.")date1 
	left Join (select ordered_product_sales,units_ordered,`date_range` from `tbldashboard` ".$filter_data_field.") as `tbldashboard` 
	on (date1.`daily_date_range`=`tbldashboard`.`date_range`)
	group by date1.`weekly_date_range` order by week Asc";
	
	$R = mysql_query($Q);
	$nums_row=mysql_num_rows($R);
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
		$value= round($row['selling_price'],2);
		$total=$total+$value;
		$avg=$avg+1;
		?>
			chartDate=chartDate.concat(["<?php echo $row['week'];?>"]);
			chartData=chartData.concat([<?php echo floatval($value);?>]);
		<?php 
		} 
	}
?>
	buildChart(chartDate,chartData,id,"column","Sales Price Weekly ("+"<?php echo "Average: $".round(($total/$avg),2);?>)","Sales Price","Week","#CC99FF","${point.y}");
}

function BuyBoxPercentageWeekly(id){
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="Wchart_div5";
	 }
	<?php 
	//$Q = "SELECT avg(buy_box_percentage) as buy_box_percentage,(DATE_ADD(date_range, INTERVAL case when WEEKDAY(date_range)=6 then 8 else (7-WEEKDAY(date_range)) END DAY)) as week FROM tbldashboard ".$filter_data_field." GROUP BY (DATE_ADD(date_range, INTERVAL case when WEEKDAY(date_range)=6 then 8 else (7-WEEKDAY(date_range)) END DAY)) order by date_range asc;";
	$Q ="SELECT IFNULL(avg(buy_box_percentage),0) as buy_box_percentage, date_format(date1.`weekly_date_range`,'%m-%d-%Y') as week 
	FROM (Select daily_date_range,weekly_date_range from date_range Where ".$filterDate.")date1 
	left Join (select buy_box_percentage,`date_range` from `tbldashboard` ".$filter_data_field.") as `tbldashboard` 
	on (date1.`daily_date_range`=`tbldashboard`.`date_range`)
	group by date1.`weekly_date_range` order by week Asc";
	$R = mysql_query($Q)or die(mysql_error());
	$msg_text="";
	$nums_row=mysql_num_rows($R); 
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
		$value= str_replace( '%', '', $row['buy_box_percentage'] );
		$value=round($value,2);
		$total=$total+$value;
		$avg=$avg+1;
		?>
			chartDate=chartDate.concat(["<?php echo $row['week'];?>"]);
			chartData=chartData.concat([<?php echo floatval($value);?>]);
		<?php 
		}  
	}
?>
	buildChart(chartDate,chartData,id,"column","Buy Box % Weekly"+"<?php echo "Average: ".round(($total/$avg),2)."%";?>)","Buy Box % Value","Week","#2D7094","{point.y}%");

	}

function OrderedProductSalesWeekly(id){
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="Wchart_div6";
	 }
	<?php 
	//$Q = "SELECT sum(ordered_product_sales) as ordered_product_sales,(DATE_ADD(date_range, INTERVAL case when WEEKDAY(date_range)=6 then 8 else (7-WEEKDAY(date_range)) END DAY)) as week FROM tbldashboard ".$filter_data_field." GROUP BY (DATE_ADD(date_range, INTERVAL case when WEEKDAY(date_range)=6 then 8 else (7-WEEKDAY(date_range)) END DAY)) order by date_range asc;";
	$Q ="SELECT IFNULL(SUM(`ordered_product_sales`),0) as ordered_product_sales, date_format(date1.`weekly_date_range`,'%m-%d-%Y') as week 
	FROM (Select daily_date_range,weekly_date_range from date_range  Where ".$filterDate.")date1 
	left Join (select ordered_product_sales,`date_range` from `tbldashboard` ".$filter_data_field.") as `tbldashboard` 
	on (date1.`daily_date_range`=`tbldashboard`.`date_range`)
	group by date1.weekly_date_range order by week Asc";
	
	
	$R = mysql_query($Q)or die(mysql_error());
	$msg_text="";
	$nums_row=mysql_num_rows($R); 
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
		$value= str_replace( '$', '', $row['ordered_product_sales'] );
		$value=str_replace(",","",$value);
		$value=round($value,2);
		$total=$total+$value;
		$avg=$avg+1;
		?>
			chartDate=chartDate.concat(["<?php echo $row['week'];?>"]);
			chartData=chartData.concat([<?php echo ($value);?>]);
		<?php 
		} 
	}
	?>
	buildChart(chartDate,chartData,id,"column","Ordered Product Sales Weekly ("+"<?php echo "Total: $".$total.", Average: $".round(($total/$avg),2);?>)","Order Product","Week","#993300","${point.y}");
}


function BuyBoxPercentageWeekly_Weighted(id){
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="Wchart_div7";
	 }
	<?php 
	if($user_role==1)
	{
		//$Q = "SELECT sum(buy_box_percentage * `units_ordered`) /sum(`units_ordered`) as weighted_buy_box_pct,(DATE_ADD(date_range, INTERVAL case when WEEKDAY(date_range)=6 then 8 else (7-WEEKDAY(date_range)) END DAY)) as week
		//FROM tbldashboard where 1=1 ".$whereDate." GROUP BY (DATE_ADD(date_range, INTERVAL case when WEEKDAY(date_range)=6 then 8 else (7-WEEKDAY(date_range)) END DAY)) order by date_range asc;";
		$Q="SELECT IFNULL((IFNULL(sum(buy_box_percentage * `units_ordered`),0) /IFNULL(sum(`units_ordered`),0)),0) as weighted_buy_box_pct, date_format(date1.`weekly_date_range`,'%m-%d-%Y') AS week
		FROM ((Select daily_date_range,weekly_date_range From date_range Where ".$filterDate.")date1 
		left Join (select buy_box_percentage,units_ordered,`date_range` from `tbldashboard` where 1=1 ".$whereDate.") as `tbldashboard`
		on (date1.daily_date_range=`tbldashboard`.`date_range`)) group by date1.`weekly_date_range` order by week asc";
	}
	else
	{
		//$Q = "SELECT sum(buy_box_percentage * `units_ordered`) /sum(`units_ordered`) as weighted_buy_box_pct,(DATE_ADD(date_range, INTERVAL case when WEEKDAY(date_range)=6 then 8 else (7-WEEKDAY(date_range)) END DAY)) as week
		//FROM tbldashboard,tblusersku where tblusersku.sku=tbldashboard.sku ".$whereDate. " and tblusersku.user_id=".$user_id." GROUP BY (DATE_ADD(date_range, INTERVAL case when WEEKDAY(date_range)=6 then 8 else (7-WEEKDAY(date_range)) END DAY)) order by date_range asc;";
		/*$Q = "SELECT IFNULL((IFNULL(sum(buy_box_percentage * `units_ordered`),0) /IFNULL(sum(`units_ordered`),0)),0) as weighted_buy_box_pct, date_format(date1.`weekly_date_range`,'%m-%d-%Y') AS week
		FROM (((Select daily_date_range,weekly_date_range From date_range Where ".$filterDate.")date1 
		left Join (select buy_box_percentage,units_ordered,`date_range`,sku from `tbldashboard` where 1=1 ".$whereDate.") as `tbldashboard`
		on (date1.`daily_date_range`=`tbldashboard`.`date_range`))
		left Join tblusersku on (tblusersku.sku=tbldashboard.sku) And tblusersku.user_id=".$user_id.") 
		group by date1.weekly_date_range Order by week Asc";*/

		$Q = "SELECT IFNULL((IFNULL(sum(buy_box_percentage * `units_ordered`),0) /IFNULL(sum(`units_ordered`),0)),0) as weighted_buy_box_pct, date_format(date1.`weekly_date_range`,'%m-%d-%y') AS week
		FROM ((Select daily_date_range,weekly_date_range  From date_range Where ".$filterDate.")date1 
		left Join (SELECT buy_box_percentage,units_ordered,`date_range`
		 FROM tbldashboard,tblusersku where tblusersku.sku=tbldashboard.sku ".$whereDate. " and tblusersku.user_id=".$user_id." ) as `tbldashboard`
		on (date1.`daily_date_range`=`tbldashboard`.`date_range`))
		group by date1.`weekly_date_range` Order by week Asc";
	
	}
	$R = mysql_query($Q)or die(mysql_error());
	$msg_text="";
	$nums_row=mysql_num_rows($R); 
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
		$value= $row['weighted_buy_box_pct'];
		$value=round($value,2);
		$total=$total+$value;
		$avg=$avg+1;
		?>
			chartDate=chartDate.concat(["<?php echo $row['week'];?>"]);
			chartData=chartData.concat([<?php echo ($value);?>]);
		<?php 
		} 
	}
?>
	buildChart(chartDate,chartData,id,"column","Total SKU's Buy Box Weekly Average ("+"<?php echo "Average: ".round(($total/$avg),2)."%";?>)","Weighted Buy Box","Week","#2D7094","{point.y}");
}

function OrderedProductSalesWeekly_2(id){
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="Wchart_div8";
	 }
	<?php 
	if($user_role==1)
	{
		
		$Q="SELECT IFNULL(sum(tbldashboard.ordered_product_sales),0) as ordered_product_sales, date_format(date1.weekly_date_range,'%m-%d-%Y') AS week
		FROM ((Select daily_date_range,weekly_date_range From date_range Where ".$filterDate.")date1 
		left Join (select ordered_product_sales,units_ordered,`date_range` from `tbldashboard` where 1=1 ".$whereDate.") as `tbldashboard`
		on (date1.daily_date_range=`tbldashboard`.`date_range`)) group by date1.`weekly_date_range` order by week asc";	
	} 
	else
	{
		
		$Q = "SELECT IFNULL(sum(ordered_product_sales),0) as ordered_product_sales, date_format(date1.`weekly_date_range`,'%m-%d-%y') AS week
		FROM ((Select daily_date_range ,weekly_date_range  From date_range Where ".$filterDate.")date1 
		left Join (SELECT tbldashboard.ordered_product_sales as ordered_product_sales, tbldashboard.date_range FROM tbldashboard,tblusersku where tblusersku.sku=tbldashboard.sku ".$whereDate. " and tblusersku.user_id=".$user_id." ) as `tbldashboard`
		on (date1.`daily_date_range`=`tbldashboard`.`date_range`))
				group by date1.`weekly_date_range` Order by week Asc";

	
	}
	$R = mysql_query($Q)or die(mysql_error());
	$msg_text="";
	$nums_row=mysql_num_rows($R); 
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
		$value= str_replace( '$', '', $row['ordered_product_sales'] );
		$value=str_replace(",","",$value);
		$value=round($value,2);
		$total=$total+$value;
		$avg=$avg+1;
		?>
			chartDate=chartDate.concat(["<?php echo $row['week'];?>"]);
			chartData=chartData.concat([<?php echo ($value);?>]);
		<?php 
		} 
	}
?>
	buildChart(chartDate,chartData,id,"column","Total SKU's Sales Weekly  ("+"<?php echo "Total: $".$total.", Average: $".round(($total/$avg),2);?>)","Order Product","Week","#993300","${point.y}");
}
function WeeklyPieChartUnitData(id)
{
	id=modalChart;
	chartModalUnitdata.style.display = "block";
}
function WeeklyPiechartUnitdetails(id)
{
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="WPiechart_div1";
	 }
	buildPiechartUnitOrder(id);
}
function WeeklyPieChartData(id)
{
	id=modalChart;
	chartModaldata.style.display = "block";
}
function WeeklyPiechartDetails(id)
{
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="WPiechart_div2";
	 }
	 buildpiechart(id);
}

//**********************************************************Weekly Data End************************************************************
//**********************************************************Monthly Start**************************************************************

function SessionChartMonthly(id){
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="Mchart_div1";
	 }
	<?php 
	//$Q="SELECT sum(`sessions`) as session ,DATE_FORMAT(date_range, '%b %y') AS Month,date_range FROM `tbldashboard` ".$filter_data_field." GROUP BY Month order by date_range asc";
	$Q="SELECT IFNULL(sum(`sessions`),0) as session,DATE_FORMAT(date1.monthly_date_range, '%b %y') AS Month,date1.monthly_date_range 
	FROM (Select daily_date_range,monthly_date_range From date_range Where ".$filterDate.")date1 
	left Join (select sessions,`date_range` from `tbldashboard` ".$filter_data_field.") as `tbldashboard`
	on (date1.`daily_date_range`=`tbldashboard`.`date_range`) GROUP BY date1.monthly_date_range order by date1.monthly_date_range asc";
		
	$R = mysql_query($Q)or die(mysql_error());
	$msg_text="";
	$nums_row=mysql_num_rows($R); 
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
		$value= str_replace( ',', '', $row['session'] );
		$total=$total+$value;
		$avg=$avg+1;
		?>
			chartDate=chartDate.concat(["<?php echo $row['Month'];?>"]);
			chartData=chartData.concat([<?php echo floatval($value);?>]);
		<?php 
		} 
	}
?>
	buildChart(chartDate,chartData,id,"column","Sessions Monthly ("+"<?php echo "Total: ".$total.", Average: ".round(($total/$avg),2);?>)","Session","Month","#69A4DE","{point.y}");
}


function ConversionChartMonthly(id){
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	}
	else
	{
		id="Mchart_div2";
	}
	<?php 
	//$Q="SELECT avg(`unit_session_percentage`) as unit_session_percentage,DATE_FORMAT(date_range, '%b %y') AS Month,date_range FROM `tbldashboard` ".$filter_data_field." GROUP BY Month order by date_range asc";
	$Q="SELECT IFNULL((1-((sum(sessions)-sum(units_ordered))/Sum(sessions)))*100,0) as unit_session_percentage,DATE_FORMAT(date1.monthly_date_range, '%b %y') AS Month,date1.monthly_date_range 
	FROM (Select daily_date_range,monthly_date_range From date_range Where ".$filterDate.")date1 
	left Join (select unit_session_percentage,`date_range`,sessions,units_ordered from `tbldashboard` ".$filter_data_field.") as `tbldashboard`
	on (date1.`daily_date_range`=`tbldashboard`.`date_range`) GROUP BY date1.monthly_date_range order by date1.monthly_date_range asc";
	$R = mysql_query($Q);
	$nums_row=mysql_num_rows($R);
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
			$value= round(str_replace( '%', '', $row['unit_session_percentage']),2);
			$total=$total+$value;
			$avg=$avg+1;
			?>
				chartDate=chartDate.concat(["<?php echo $row['Month'];?>"]);
				chartData=chartData.concat([<?php echo $value;?>]);
			<?php 
		} 
	}
?>
	buildChart(chartDate,chartData,id,"column","Conversion Rate Monthly ("+"<?php echo "Average: ".round(($total/$avg),2)."%";?>)","Unit Session Percentage","Month","#91CD6A","{point.y}%");
}
   

function UnitOrderDataMonthly(id){
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	}
	else
	{
		id="Mchart_div3";
	}
	<?php 
	//$Q="SELECT sum(`units_ordered`) as units_ordered,DATE_FORMAT(date_range, '%b %y') AS Month,date_range FROM `tbldashboard` ".$filter_data_field." GROUP BY Month order by date_range asc";
	$Q="SELECT IFNULL(sum(`units_ordered`),0) as units_ordered,DATE_FORMAT(date1.monthly_date_range, '%b %y') AS Month,date1.monthly_date_range 
	FROM (Select daily_date_range,monthly_date_range From date_range Where ".$filterDate.")date1 
	left Join (select units_ordered,`date_range` from `tbldashboard` ".$filter_data_field.") as `tbldashboard`
	on (date1.`daily_date_range`=`tbldashboard`.`date_range`) GROUP BY date1.monthly_date_range order by date1.monthly_date_range asc";
	
	$R = mysql_query($Q);
	$i=0;
	$nums_row=mysql_num_rows($R);
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
		$value= $row['units_ordered'];
		$total=$total+$value;
		$avg=$avg+1;
		?>
			chartDate=chartDate.concat(["<?php echo $row['Month'];?>"]);
			chartData=chartData.concat([<?php echo $value;?>]);
		<?php 
		} 
	}
?>
	buildChart(chartDate,chartData,id,"column","Units Ordered Monthly ("+"<?php echo "Total: ".$total.", Average: ".round(($total/$avg),2);?>)","Unit Ordered","Month","#E36068","{point.y}");
}

function SalesPriceDataMonthly(id){
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	}
	else
	{
		id="Mchart_div4";
	}
	<?php 
	//$Q="SELECT sum(replace(`ordered_product_sales`,'$','')) / sum(`units_ordered`) as `selling_price`,DATE_FORMAT(date_range, '%b %y') AS Month,date_range FROM `tbldashboard` ".$filter_data_field." GROUP BY Month order by date_range asc";
	$Q="SELECT IFNULL(sum(replace(`ordered_product_sales`,'$','')) / sum(`units_ordered`),0) as selling_price,DATE_FORMAT(date1.monthly_date_range, '%b %y') AS Month,date1.monthly_date_range 
	FROM (Select daily_date_range,monthly_date_range From date_range Where ".$filterDate.")date1 
	left Join (select ordered_product_sales,units_ordered,`date_range` from `tbldashboard` ".$filter_data_field.") as `tbldashboard`
	on (date1.`daily_date_range`=`tbldashboard`.`date_range`) GROUP BY date1.monthly_date_range order by date1.monthly_date_range asc";
	$R = mysql_query($Q);
	$nums_row=mysql_num_rows($R);
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
		$value= round($row['selling_price'],2);
		$total=$total+$value;
		$avg=$avg+1;
		?>
			chartDate=chartDate.concat(["<?php echo $row['Month'];?>"]);
			chartData=chartData.concat([<?php echo floatval($value);?>]);
		<?php 
		} 
	}
?>
	buildChart(chartDate,chartData,id,"column","Sales Price Monthly ("+"<?php echo "Average: $".round(($total/$avg),2);?>)","Sales Price","Month","#CC99FF","${point.y}");
}
	

function BuyBoxPercentageMonthly(id){
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	}
	else
	{
		id="Mchart_div5";
	}
	<?php 
	//$Q="SELECT avg(`buy_box_percentage`) as buy_box_percentage,DATE_FORMAT(date_range, '%b %y') AS Month,date_range FROM `tbldashboard` ".$filter_data_field." GROUP BY Month order by date_range asc";
	$Q="SELECT IFNULL(avg(`buy_box_percentage`),0) as buy_box_percentage,DATE_FORMAT(date1.monthly_date_range, '%b %y') AS Month,date1.monthly_date_range 
	FROM (Select daily_date_range,monthly_date_range From date_range Where ".$filterDate.")date1 
	left Join (select buy_box_percentage,`date_range` from `tbldashboard` ".$filter_data_field.") as `tbldashboard`
	on (date1.`daily_date_range`=`tbldashboard`.`date_range`) GROUP BY date1.monthly_date_range order by date1.monthly_date_range asc";

	$R = mysql_query($Q)or die(mysql_error());
	$msg_text="";
	$nums_row=mysql_num_rows($R); 
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
		$value= str_replace( '%', '', $row['buy_box_percentage']);
		$value=round($value,2);
		$total=$total+$value;
		$avg=$avg+1;
		?>
			chartDate=chartDate.concat(["<?php echo $row['Month'];?>"]);
			chartData=chartData.concat([<?php echo floatval($value);?>]);
		<?php 
		} 
	}
	?>
buildChart(chartDate,chartData,id,"column","Buy Box % Monthly ("+"<?php echo "Average: ".round(($total/$avg),2)."%";?>)","Buy Box % Value","Month","#2D7094","{point.y}%");
}
	

function OrderedProductSalesMonthly(id){
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	}
	else
	{
		id="Mchart_div6";
	}
	<?php 
	//$Q="SELECT sum(replace(`ordered_product_sales`,'$','')) as ordered_product_sales,DATE_FORMAT(date_range, '%b %y') AS Month,date_range FROM `tbldashboard` ".$filter_data_field." GROUP BY Month order by date_range asc";
	$Q="SELECT IFNULL(sum(replace(`ordered_product_sales`,'$','')),0) as ordered_product_sales,DATE_FORMAT(date1.monthly_date_range, '%b %y') AS Month,date1.monthly_date_range 
	FROM (Select daily_date_range,monthly_date_range From date_range Where ".$filterDate.")date1 
	left Join (select ordered_product_sales,`date_range` from `tbldashboard` ".$filter_data_field.") as `tbldashboard`
	on (date1.`daily_date_range`=`tbldashboard`.`date_range`) GROUP BY date1.monthly_date_range order by date1.monthly_date_range asc";

	$R = mysql_query($Q)or die(mysql_error());
	$msg_text="";
	$nums_row=mysql_num_rows($R); 
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
		$value= str_replace( '$', '', $row['ordered_product_sales'] );
		$value=str_replace(",","",$value);
		$value=round($value,2);
		$total=$total+$value;
		$avg=$avg+1;
		?>
			chartDate=chartDate.concat(["<?php echo $row['Month'];?>"]);
			chartData=chartData.concat([<?php echo ($value);?>]);
		<?php 
		} 
	}
	?>
	buildChart(chartDate,chartData,id,"column","Ordered Product Sales Monthly ("+"<?php echo "Total: $".$total.", Average: $".round(($total/$avg),2);?>)","Order Product","Month","#993300","${point.y}");
}
	
function BuyBoxPercentageMonthly_Weighted(id){
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	}
	else
	{
		id="Mchart_div7";
	}
	<?php 
	if($user_role==1)
	{
		//$Q = "SELECT sum(buy_box_percentage * `units_ordered`) /sum(`units_ordered`) as weighted_buy_box_pct, date_format(`date_range`,'%b %Y') AS Month,date_range
		//FROM tbldashboard where 1=1 ".$whereDate. " GROUP BY Month order by date_range asc";
		$Q="SELECT IFNULL(sum(buy_box_percentage * `units_ordered`) /sum(`units_ordered`),0) as weighted_buy_box_pct, date_format(date1.`monthly_date_range`,'%b %Y') AS Month,date1.monthly_date_range 
		FROM ((Select daily_date_range,monthly_date_range From date_range Where ".$filterDate.")date1 
		left Join (select buy_box_percentage,units_ordered,`date_range` from `tbldashboard` where 1=1 ".$whereDate.") as `tbldashboard`
		on (date1.`daily_date_range`=`tbldashboard`.`date_range`)) group by date1.`monthly_date_range` order by date1.monthly_date_range asc";
	}
	else
	{
		//$Q = "SELECT sum(buy_box_percentage * `units_ordered`) /sum(`units_ordered`) as weighted_buy_box_pct, date_format(`date_range`,'%b %Y') AS Month,date_range
		//FROM tbldashboard,tblusersku where tblusersku.sku=tbldashboard.sku ".$whereDate. " and tblusersku.user_id=".$user_id." GROUP BY Month order by date_range asc";
		/*$Q = "SELECT IFNULL((IFNULL(sum(buy_box_percentage * `units_ordered`),0) /IFNULL(sum(`units_ordered`),0)),0) as weighted_buy_box_pct, date_format(date1.`monthly_date_range`,'%b %Y') AS Month,date1.monthly_date_range 
		FROM (((Select daily_date_range,monthly_date_range From date_range Where ".$filterDate.")date1 
		left Join (select buy_box_percentage,units_ordered,`date_range`,sku from `tbldashboard` where 1=1 ".$whereDate.") as `tbldashboard`
		on (date1.`daily_date_range`=`tbldashboard`.`date_range`))
		left Join tblusersku on (tblusersku.sku=tbldashboard.sku) And tblusersku.user_id=".$user_id.") 
		group by date1.monthly_date_range order by date1.monthly_date_range asc"; */

		$Q = "SELECT IFNULL((IFNULL(sum(buy_box_percentage * `units_ordered`),0) /IFNULL(sum(`units_ordered`),0)),0) as weighted_buy_box_pct, date_format(date1.`monthly_date_range`,'%b %Y') AS Month
		FROM ((Select daily_date_range,monthly_date_range  From date_range Where ".$filterDate.")date1 
		left Join (SELECT buy_box_percentage,units_ordered,`date_range`
		 FROM tbldashboard,tblusersku where tblusersku.sku=tbldashboard.sku ".$whereDate. " and tblusersku.user_id=".$user_id." ) as `tbldashboard`
		on (date1.`daily_date_range`=`tbldashboard`.`date_range`))
		group by date1.monthly_date_range order by date1.monthly_date_range asc";
	}
	$R = mysql_query($Q)or die(mysql_error());
	$msg_text="";
	$nums_row=mysql_num_rows($R); 
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
		$value= str_replace( '%', '', $row['weighted_buy_box_pct']);
		$value=round($value,2);
		$total=$total+$value;
		$avg=$avg+1;
		?>
			chartDate=chartDate.concat(["<?php echo $row['Month'];?>"]);
			chartData=chartData.concat([<?php echo ($value);?>]);
		<?php 
		} 
	}
?>
	buildChart(chartDate,chartData,id,"column","Total SKU's Buy Box Monthly Average ("+"<?php echo "Average: ".round(($total/$avg),2)."%";?>)","Weighted Buy Box","Month","#2D7094","{point.y}");
}
	

function OrderedProductSalesMonthly_2(id){
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	}
	else
	{
		id="Mchart_div8";
	}
	<?php 
	if($user_role==1)
	{
		//$Q="SELECT sum(tbldashboard.ordered_product_sales) as ordered_product_sales, DATE_FORMAT(date_range, '%b %y') AS Month,date_range FROM tbldashboard where 1=1 ".$whereDate. " GROUP BY Month order by date_range asc";
		$Q="SELECT IFNULL(sum(tbldashboard.ordered_product_sales),0) as ordered_product_sales, date_format(date1.`monthly_date_range`,'%b %y') AS Month,date1.monthly_date_range 
		FROM ((Select daily_date_range,monthly_date_range From date_range Where ".$filterDate.")date1 
		left Join (select ordered_product_sales,`date_range` from `tbldashboard` where 1=1 ".$whereDate.") as `tbldashboard`
		on (date1.`daily_date_range`=`tbldashboard`.`date_range`)) group by date1.`monthly_date_range` order by date1.monthly_date_range asc";
	
	} 
	else
	{
		//$Q="SELECT sum(tbldashboard.ordered_product_sales) as ordered_product_sales, DATE_FORMAT(date_range, '%b %y') AS Month,date_range FROM tbldashboard,tblusersku where tblusersku.sku=tbldashboard.sku ".$whereDate. " and tblusersku.user_id=".$user_id." GROUP BY Month order by date_range asc";
		/*$Q = "SELECT IFNULL(sum(tbldashboard.ordered_product_sales),0) as ordered_product_sales, date_format(date1.`daily_date_range`,'%b %Y') AS Month,date1.monthly_date_range 
		FROM (((Select daily_date_range,monthly_date_range From date_range Where ".$filterDate.")date1 
		left Join (select ordered_product_sales,`date_range`,sku from `tbldashboard` where 1=1 ".$whereDate.") as `tbldashboard`
		on (date1.`daily_date_range`=`tbldashboard`.`date_range`))
		left Join tblusersku on (tblusersku.sku=tbldashboard.sku) And tblusersku.user_id=".$user_id.") 
		group by date1.monthly_date_range order by date1.monthly_date_range asc";*/

		$Q = "SELECT IFNULL(sum(ordered_product_sales),0) as ordered_product_sales, date_format(date1.`daily_date_range`,'%b %Y') AS Month
		FROM ((Select daily_date_range ,monthly_date_range  From date_range Where ".$filterDate.")date1 
		left Join (SELECT tbldashboard.ordered_product_sales as ordered_product_sales, tbldashboard.date_range FROM tbldashboard,tblusersku where tblusersku.sku=tbldashboard.sku ".$whereDate. " and tblusersku.user_id=".$user_id." ) as `tbldashboard`
		on (date1.`daily_date_range`=`tbldashboard`.`date_range`))
				group by date1.monthly_date_range Order by monthly_date_range Asc";

	}
	$R = mysql_query($Q)or die(mysql_error());
	$msg_text="";
	$nums_row=mysql_num_rows($R); 
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
		$value= str_replace( '$', '', $row['ordered_product_sales'] );
		$value=str_replace(",","",$value);
		$value=round($value,2);
		$total=$total+$value;
		$avg=$avg+1;
		?>
			chartDate=chartDate.concat(["<?php echo $row['Month'];?>"]);
			chartData=chartData.concat([<?php echo ($value);?>]);
		<?php 
		} 
	}
?>
	buildChart(chartDate,chartData,id,"column","Total SKU's Sales Monthly  ("+"<?php echo "Total: $".$total.", Average: $".round(($total/$avg),2);?>)","Order Product","Month","#993300","${point.y}");
}
// function MonthlyPieChartUniyData(id)
// {
	// id=modalChart;
	// chartModalUnitdata.style.display = "block";
// }
// function MonthlyPieChartUnitDetails(id)
// {
	// if(id){ 
		// id=modalChart;
		// modal.style.display = "block";
	 // }
	 // else
	 // {
		// id="MPiechart_div1";
	 // }
	// buildPiechartUnitOrder(id);
	
// }
// function MonthlyPieChartData(id)
// {
	// id=modalChart;
	// chartModaldata.style.display = "block";
// }
// function MonthlyPiechartDetails(id)
// {
	// chartDate=[];
	// chartData=[];
	// if(id){ 
		// id=modalChart;
		// modal.style.display = "block";
	 // }
	 // else
	 // {
		// id="MPiechart_div2";
	 // }
	 // buildpiechart(id);
// }
//**********************************************************Monthly Data End**************************************************************
//**********************************************************Yearly data start*************************************************************

function YearlySessionDetails(id)
{
	chartDate=[];
	 chartData=[];
	 if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="YSessionchart_div1";
	 }
	<?php
	
	$Q="SELECT IFNULL(SUM(`sessions`),0) as sessions, Year(date1.`daily_date_range`) as Daily 
	FROM (Select daily_date_range from date_range Where ".$filterDateYearly.") date1 
	left Join (select sessions,`date_range` from `tbldashboard` ".$filterDateFieldYearly.") as `tbldashboard` on (date1.`daily_date_range`=`tbldashboard`.`date_range`)
	group by Year(date1.`daily_date_range`) order by Daily Asc";
	$R = mysql_query($Q)or die(mysql_error());
	$nums_row=mysql_num_rows($R);
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		while($row = mysql_fetch_array($R)){
			
			$value= Round(str_replace( '$', '', $row['sessions'] ),2);
			$total=$total+$value;
			$avg=$avg+1;
			?>
			chartDate=chartDate.concat(["<?php echo $row['Daily'];?>"]);
			chartData=chartData.concat([<?php echo floatval($value);?>]);
			<?php 
		} 
	}
	?>
	buildChart(chartDate,chartData,id,"column","Sessions Yearly ("+"<?php echo "Total: ".$total.", Average: ".round(($total/$avg),2);?>)","Session","Year","#69A4DE","{point.y}");


}
function YearlyConversionRate(id)
{
	
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="YConversionchart_div2";
	 }
	<?php
	$Q="SELECT IFNULL((1-((sum(sessions)-sum(units_ordered))/Sum(sessions)))*100,0) as unit_session_percentage, Year(date1.`daily_date_range`) as Daily 
	FROM (Select daily_date_range from date_range Where ".$filterDateYearly.") date1 
	left Join (select unit_session_percentage,`date_range`,units_ordered,sessions from `tbldashboard` ".$filterDateFieldYearly.") as `tbldashboard` on (date1.`daily_date_range`=`tbldashboard`.`date_range`)
	group by Year(date1.`daily_date_range`) order by Daily Asc";
	$R = mysql_query($Q)or die(mysql_error());
	$msg_text="";
	$nums_row=mysql_num_rows($R);
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
			$value= round(str_replace( '%', '', $row['unit_session_percentage']),2);
			$total=$total+$value;
			$avg=$avg+1;
			?>
			chartDate=chartDate.concat(["<?php echo $row['Daily'];?>"]);
			chartData=chartData.concat([<?php echo $value;?>]);
			<?php 
		}	
	}
	?>
	buildChart(chartDate,chartData,id,"column","Conversion Rate Yearly ("+"<?php echo "Average: ".round(($total/$avg),2)."%";?>)","Unit Session Percentage","Year","#91CD6A","{point.y}%");
}
function YearlyUnitOrder(id)
{

	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="Yunitorderchart_div1";
	 }
	<?php 
	
	$Q="SELECT IFNULL(sum(`units_ordered`),0) as units_ordered, Year(date1.`daily_date_range`) as Daily 
	FROM (Select daily_date_range from date_range Where ".$filterDateYearly.") date1 
	left Join (select units_ordered,`date_range` from `tbldashboard` ".$filterDateFieldYearly.") as `tbldashboard` on (date1.`daily_date_range`=`tbldashboard`.`date_range`)
	group by Year(date1.`daily_date_range`) order by Daily Asc";
	$R = mysql_query($Q);
	$i=0;
	$nums_row=mysql_num_rows($R);
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
		$value= $row['units_ordered'];
		$total=$total+$value;
		$avg=$avg+1;
		?>
			chartDate=chartDate.concat(["<?php echo $row['Daily'];?>"]);
			chartData=chartData.concat([<?php echo $value;?>]);
		<?php 
		} 
	}
	?>
	buildChart(chartDate,chartData,id,"column","Units Ordered Yearly ("+"<?php echo "Total: ".$total.", Average: ".round(($total/$avg),2);?>)","Unit Ordered","Year","#E36068","{point.y}");}
function YearlySalePriceDetails(id)
{
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="YSalepricechart_div2";
	 }
	<?php 
	
	$Q="SELECT IFNULL(sum(replace(`ordered_product_sales`,'$','')) / sum(`units_ordered`),0) as selling_price, Year(date1.`daily_date_range`) as Daily 
	FROM (Select daily_date_range from date_range Where ".$filterDateYearly.") date1 
	left Join (select ordered_product_sales,units_ordered,`date_range` from `tbldashboard` ".$filterDateFieldYearly.") as `tbldashboard` on (date1.`daily_date_range`=`tbldashboard`.`date_range`)
	group by Year(date1.`daily_date_range`) order by Daily Asc";
	$R = mysql_query($Q);
	$nums_row=mysql_num_rows($R);
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
			$value= round($row['selling_price'],2);
			$total=$total+$value;
			$avg=$avg+1;
			?>
			chartDate=chartDate.concat(["<?php echo $row['Daily'];?>"]);
			chartData=chartData.concat([<?php echo floatval($value);?>]);
			<?php 
		} 
	}
	?>
	buildChart(chartDate,chartData,id,"column","Sales Price Yearly ("+"<?php echo "Average: $".round(($total/$avg),2);?>)","Sales Price","Year","#CC99FF","${point.y}");
}
function YearlyBuyBoxDetails(id)
{
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="Ybuyboxchart_div1";
	 }
	<?php 
	$Q="SELECT IFNULL(avg(buy_box_percentage),0) as buy_box_percentage, Year(date1.`daily_date_range`) as Daily 
	FROM (Select daily_date_range from date_range Where ".$filterDateYearly.") date1 
	left Join (select buy_box_percentage,`date_range` from `tbldashboard` ".$filterDateFieldYearly.") as `tbldashboard` on (date1.`daily_date_range`=`tbldashboard`.`date_range`)
	group by Year(date1.`daily_date_range`) order by Daily Asc";
	
	$R = mysql_query($Q)or die(mysql_error());
	$msg_text="";
	$nums_row=mysql_num_rows($R); 
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
		$value= str_replace( '%', '', $row['buy_box_percentage'] );
		$value=round($value,2);
		$total=$total+$value;
		$avg=$avg+1;
		?>
			chartDate=chartDate.concat(["<?php echo $row['Daily'];?>"]);
			chartData=chartData.concat([<?php echo floatval($value);?>]);
			<?php 
		} 
	}
?>
	buildChart(chartDate,chartData,id,"column","Buy Box % Yearly ("+"<?php echo "Average: ".round(($total/$avg),2)."%";?>)","Buy Box % Value","Year","#2D7094","{point.y}%");
}
function YearlyOrderedProductSaleDetails(id)
{
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="Yorderproductchart_div2";
	 }
	<?php 
	$Q="SELECT IFNULL(sum(ordered_product_sales),0) as ordered_product_sales, Year(date1.`daily_date_range`) as Daily 
	FROM (Select daily_date_range from date_range Where ".$filterDateYearly.") date1 
	left Join (select ordered_product_sales,`date_range` from `tbldashboard` ".$filterDateFieldYearly.") as `tbldashboard` on (date1.`daily_date_range`=`tbldashboard`.`date_range`)
	group by Year(date1.`daily_date_range`) order by Daily Asc";
	
	$R = mysql_query($Q)or die(mysql_error());
	$msg_text="";
	$nums_row=mysql_num_rows($R); 
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
		$value= str_replace( '$', '', $row['ordered_product_sales'] );
		$value=str_replace(",","",$value);
		$value=round($value,2);
		$total=$total+$value;
		$avg=$avg+1;
		?>
			chartDate=chartDate.concat(["<?php echo $row['Daily'];?>"]);
			chartData=chartData.concat([<?php echo floatval($value);?>]);
		<?php 
		} 
	}
	?>
	buildChart(chartDate,chartData,id,"column","Ordered Product Sales Yearly ("+"<?php echo "Total: $".$total.", Average: $".round(($total/$avg),2);?>)","Order Product","Year","#993300","${point.y}");
}
function YearlyTotalSkuBuyBoxDetails(id)
{
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="Ytotalskubuychart_div1";
	 }
	<?php 
	if($user_role==1)
	{
		$Q="SELECT IFNULL((IFNULL(sum(buy_box_percentage * `units_ordered`),0) /IFNULL(sum(`units_ordered`),0)),0) as weighted_buy_box_pct, Year(date1.`daily_date_range`) AS Daily
		FROM ((Select daily_date_range From date_range Where ".$filterDateYearly.")date1 
		left Join (select buy_box_percentage,units_ordered,`date_range` from `tbldashboard` where 1=1 ".$Wheredateyearly.") as `tbldashboard`
		on (date1.`daily_date_range`=`tbldashboard`.`date_range`)) group by Year(date1.`daily_date_range`) order by Daily asc";
	}
	else
	{
		$Q = "SELECT IFNULL((IFNULL(sum(buy_box_percentage * `units_ordered`),0) /IFNULL(sum(`units_ordered`),0)),0) as weighted_buy_box_pct, Year(date1.`daily_date_range`) AS Daily
		FROM ((Select daily_date_range From date_range Where ".$filterDateYearly.")date1 
		left Join (SELECT buy_box_percentage,units_ordered,`date_range`
		 FROM tbldashboard,tblusersku where tblusersku.sku=tbldashboard.sku ".$Wheredateyearly. " and tblusersku.user_id=".$user_id." ) as `tbldashboard`
		on (date1.`daily_date_range`=`tbldashboard`.`date_range`))
		group by Year(date1.`daily_date_range`) Order by Daily Asc";
		

	}
	$R = mysql_query($Q)or die(mysql_error());
	$msg_text="";
	$nums_row=mysql_num_rows($R); 
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
			$value= $row['weighted_buy_box_pct'];
			$value=round($value,2);
			$total=$total+$value;
			$avg=$avg+1;
			?>
				chartDate=chartDate.concat(["<?php echo $row['Daily'];?>"]);
				chartData=chartData.concat([<?php echo floatval($value);?>]);
			<?php 
		} 
	}
?>
	buildChart(chartDate,chartData,id,"column","Total SKU's Buy Box Yearly Average ("+"<?php echo " Average: ".round(($total/$avg),2)."%";?>)","Weighted Buy Box","Year","#2D7094","{point.y}");
}
function YearlyTotalSkuSalesDetails(id)
{
	chartDate=[];
	chartData=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="Ytotalskusalchart_div2";
	 }
	<?php 
	if($user_role==1)
	{
		$Q="SELECT IFNULL(sum(ordered_product_sales),0) as ordered_product_sales, Year(date1.`daily_date_range`) AS Daily
		FROM ((Select daily_date_range From date_range Where ".$filterDateYearly.")date1 
		left Join (select ordered_product_sales,units_ordered,`date_range` from `tbldashboard` where 1=1 ".$Wheredateyearly.") as `tbldashboard`
		on (date1.`daily_date_range`=`tbldashboard`.`date_range`)) group by Year(date1.`daily_date_range`) order by Daily asc";
	} 
	else
	{
		$Q = "SELECT IFNULL(sum(ordered_product_sales),0) as ordered_product_sales, Year(date1.`daily_date_range`) AS Daily
		FROM ((Select daily_date_range From date_range Where ".$filterDateYearly.")date1 
		left Join (SELECT sum(tbldashboard.ordered_product_sales) as ordered_product_sales, tbldashboard.date_range FROM tbldashboard,tblusersku where tblusersku.sku=tbldashboard.sku ".$Wheredateyearly. " and tblusersku.user_id=".$user_id." group by tbldashboard.date_range) as `tbldashboard`
		on (date1.`daily_date_range`=`tbldashboard`.`date_range`))
				group by Year(date1.`daily_date_range`)  Order by Daily Asc";
		
	}
	$R = mysql_query($Q)or die(mysql_error());
	$msg_text="";
	$nums_row=mysql_num_rows($R); 
	if($nums_row==0)
	{
		?>
			chartDate=[0];
			chartData=[0];
		<?php 
	}
	else
	{
		$avg=0;
		$total=0;
		while($row = mysql_fetch_array($R)){
		$value= str_replace( '$', '', $row['ordered_product_sales'] );
		$value=str_replace(",","",$value);
		$value=round($value,2);
		$total=$total+$value;
		$avg=$avg+1;
		?>
			chartDate=chartDate.concat(["<?php echo $row['Daily'];?>"]);
			chartData=chartData.concat([<?php echo floatval($value);?>]);
		<?php 
		} 
	}
	?>
	buildChart(chartDate,chartData,id,"column","Total SKU's Sales Yearly ("+"<?php echo "Total: $".$total.", Average: $".round(($total/$avg),2);?>)","Order Product","Year","#993300","${point.y}");
}
function YearlyPieunitorederdata(id)
{
	id=modalChart;
	chartModalUnitdata.style.display = "block";
}
function YearlyPieUnitoredreDetails(id)
{
	
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="YPiechart_div1";
	 }
	buildPiechartUnitOrder(id);
}
function YearlyPiechartdata(id)
{
	id=modalChart;
	chartModaldata.style.display = "block";
}
function YearlyPiechartDetails(id)
{
	chartskuname=[];
	chartDatavalue=[];
	if(id){ 
		id=modalChart;
		modal.style.display = "block";
	 }
	 else
	 {
		id="YPiechart_div2";
	 }
	
	buildpiechart(id);
}
function buildChart(dDate,Data,div,type,title,yTitle,xTitle,colomColor,format)
{
	Highcharts.chart(div, 
	{
		chart: 
		{
			//renderTo: 'container',
			type: type,
			options3d: 
			{
				/*enabled: true,
				alpha: 10,
				beta: 0,
				depth: 50,
				viewDistance: 25*/
			}
		},
		title: 
		{
			text: title,
			style: {
					font: 'normal 10px Verdana, sans-serif',
					color : 'black'
					}
		},
		subtitle: 
		{
			text: ''
		},
		xAxis: 
		{
			categories: dDate,
			crosshair: true
		},
		yAxis: 
		{
			min: 0,
			title: 
			{
				text: yTitle
			}
		},
		tooltip: 
		{	//series.color
			headerFormat: '<span style="font-size:12px;color:{black};">{point.key}</span><table style="width:170;">',
			pointFormat: '<tr><td style="font-size:12px;color:{black};width:70;"><b>'+yTitle+':</b></td>' +
				'<td style="font-size:12px;color:{black};width:100;"><b>'+format+'</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			useHTML: true
		},
		plotOptions: 
		{		},
		series: [{
			name:xTitle,
			data:Data,
			color: colomColor,
			dataLabels: 
			{
				enabled: true,
				y: -6,
				format: format,
			}
		}]
	});
}
	function drawVisualization() {
		SessionChartDaily();
		ConversionChartDaily();
		UnitOrderDataDaily();
		SalesPriceDataDaily();
		BuyBoxPercentageDaily();
		OrderedProductSalesDaily();
		BuyBoxPercentageDaily_Weighted();
		OrderedProductSalesDaily_2();
		// PieChartDailyData2();
		// PieChartUnitOrderDetails();
		
		SessionChartWeekly();
		ConversionChartWeekly();
		UnitOrderDataWeekly();
		SalesPriceDataWeekly();
		BuyBoxPercentageWeekly();
		OrderedProductSalesWeekly();
		BuyBoxPercentageWeekly_Weighted();
		OrderedProductSalesWeekly_2();
		WeeklyPiechartDetails();
		WeeklyPiechartUnitdetails();
		
		SessionChartMonthly();
		ConversionChartMonthly();
		UnitOrderDataMonthly();
		SalesPriceDataMonthly();
		BuyBoxPercentageMonthly();
		OrderedProductSalesMonthly();
		BuyBoxPercentageMonthly_Weighted();
		OrderedProductSalesMonthly_2();
		// MonthlyPiechartDetails();
		// MonthlyPieChartUnitDetails();
		
		//yearly
		YearlySessionDetails();
		YearlyConversionRate();
		YearlyUnitOrder();
		YearlySalePriceDetails();
		YearlyBuyBoxDetails();
		YearlyOrderedProductSaleDetails();
		YearlyTotalSkuBuyBoxDetails();
		YearlyTotalSkuSalesDetails();
		YearlyPiechartDetails();
		YearlyPieUnitoredreDetails();
		
}

	function exportPDF(){
		var row;
		$('#headers').hide();
		$('#filterTable').hide();
		$('#Daily').hide();
		$('#Weekly').hide();
		$('#Monthly').hide();
		$('#Admin').hide();
		$('#Report').hide();
		$('#tb').hide();
		$(TabID[0]).show();
		$("#welcome").removeClass("topTitle");
		$("#top").removeClass("top");
		row="<b>From Date:-&nbsp;</b>"+$('#txtFromDate').val()+"&nbsp;&nbsp;";
		row=row+"<b>To Date:-&nbsp;</b>"+$('#txtToDate').val()+"&nbsp;&nbsp;";
		row=row+"<b>SKU:-</b>&nbsp;"+$('#sku :selected').text();
		$("#rowPrint").html(row);
		$("#rowPrint").show();
		$("#printHeader").show();
		
		window.print();
		$('#headers').show();
		$('#filterTable').show();
		$('#Daily').show();
		$('#Weekly').show();
		$('#Monthly').show();
		$('#Admin').show();
		$('#Report').show();
		$('#tb').show();
		$("#welcome").addClass("topTitle");
		$("#top").addClass("top"); 
		$("#rowPrint").html("");
		$("#rowPrint").hide();
		$("#printHeader").hide();
	}
	
	function exportCSV()
	{
	 if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            }
        };
		xmlhttp.open("GET","exceldownload.php",true);
        xmlhttp.send();		
		return true;
	}
	function fileOperation(div)
	{
		
		if(div=="Sales")
		{
			$("#InventoryDiv").css("display", "none");
			$("#SKUDiv").css("display", "none");
			$("#InventoryAdditDiv").css("display", "none");
			$("#SalesDiv").css("display", "block");
		}
		else if(div=="Inventory")
		{
			$("#SalesDiv").css("display", "none");
			$("#SKUDiv").css("display", "none");
			$("#InventoryAdditDiv").css("display", "none");
			$("#InventoryDiv").css("display", "block");
		}	
		else if(div=="SKUFile")
		{
			$("#SalesDiv").css("display", "none");
			$("#InventoryDiv").css("display", "none");
			$("#InventoryAdditDiv").css("display", "none");
			$("#SKUDiv").css("display", "block");
		}
		else if(div=="InventoryAddit")
		{
			$("#SalesDiv").css("display", "none");
			$("#InventoryDiv").css("display", "none");
			$("#SKUDiv").css("display", "none");
			$("#InventoryAdditDiv").css("display", "block");
		}		
		$("#Inventory").css("background-color", '');
		$("#Sales").css("background-color", '');
		$("#SKUFile").css("background-color", '');
		$("#InventoryAddit").css("background-color", '');
		$("#"+div).css("background-color", 'white');
	}
	
	function reportOperation(div,report)
	{
		// if(div=="btnIOReport")
		// {
			// $("#IReportDiv").css("display", "block");
			// $("#IAReportDiv").css("display", "none");
		// }
		// else if(div=="btnAnalReport")
		// {
			// $("#IReportDiv").css("display", "none");
			// $("#IAReportDiv").css("display", "block");
		// }	
		$("#btnIOReport").css("background-color", '');
		$("#btnAnalReport").css("background-color", '');
		$("#btnWeeklyPercentageReport").css("background-color", '');
		$("#btnSalesReport").css("background-color", '');
		$("#"+div).css("background-color", 'white');
		getReport(report);
	}
	
function getReport(report)
{
	var httpReq;
	document.getElementById("viewreport").innerHTML="<div style=\"z-index: 1000;"+
		"margin: 100px auto;padding: 10px;width: 130px; border-radius: 10px;filter: alpha(opacity=100);"+
		"opacity: 1;-moz-opacity: 1;\">"+
		"<img src=\"images/loading_spinner.gif\" style=\"height: 128px;width: 128px;\" /></div>";
	
		if (window.XMLHttpRequest)
		{
		//If the browser if IE7+[or]Firefox[or]Chrome[or]Opera[or]Safari
		  httpReq=new XMLHttpRequest();
		}
	   else
		{
		//If browser is IE6, IE5
		  httpReq=new ActiveXObject("Microsoft.XMLHTTP");
		}
		httpReq.onreadystatechange=function()
		{
			if (httpReq.readyState==4)
			{
				document.getElementById("viewreport").innerHTML=httpReq.responseText;
				// if(report=="Inventory")
				// {
					// document.getElementById("IReportDiv").innerHTML=httpReq.responseText;
				// }
				// else if(report=="InventoryAnal")
				// {
					// document.getElementById("IAReportDiv").innerHTML=httpReq.responseText;
				// }
			}
		}
	//var r="?report="+report+"&Task=Delete";
	var r="?report="+report;
	httpReq.open("POST","inventoryreport.php"+r,true);
	httpReq.send();
}
</script>
<link rel="stylesheet" href="css/main.css"/>
<link rel="stylesheet" href="css/bootstrap.min.css"/>
<link rel="stylesheet" href="css/w3.css"/>
<!--script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script-->
<script src="js/jquery-customselect.js"></script>
<link href="css/jquery-customselect.css" rel="stylesheet"/>
<script src="js/jquery-ui-1.10.3.min.js"></script> 

	</head><title>SKU Analysis</title>
		<body style="background-color:#f1f1f1;">
			<div class="gcontainer" style="overflow-x: hidden;">
				<?php include("menus.php"); ?>
				<!--div id="top" class="top"> 
					<div id="welcome" class="topTitle" style="font-size: 35px;text-align:center">
						Welcome To SKU Analysis
					</div>	
				</div-->
				<form name="filterForm" id="filterForm" method="post"  onsubmit="return getFilteredData();"action="<?php $_PHP_SELF;?>"method="post">		
					<div id="filterC "class="well well-sm">
						<div class="row ">
						<div class="col-lg-5 col-md-5 col-sm-5 cl-xs-5">
							<div class="col-lg-6 ">
								<label style="text-align: right;">From Date</label>
								<input type="text"  id="txtFromDate" name="txtFromDate" value="<?php echo $fromDate;?>" class="styled-text2" />
							</div>
							
							<div class="col-lg-6 ">
								<label style="text-align: right;">To Date</label>
								<input type="text" id="txtToDate"  name="txtToDate" value="<?php echo $toDate;?>" class="styled-text3"/>
							</div>
							
						</div>
						<div class="col-lg-7 col-md-7 col-sm-7 cl-xs-7">
							<div class="col-lg-1 col-sm-1" style="padding-right: 0px;margin-top: 4px;"><label>SKU</label></div>
							<div class="col-lg-5" >
								
								<select name="sku" id="sku"  class="custom-select">
									<option value="0">Select SKU</option>
									<?php	
									if($user_role==1)
									{
										$query="SELECT distinct tblskumaster.sku,tblskumaster.skuname FROM tbldashboard INNER JOIN tblskumaster ON tbldashboard.sku = tblskumaster.sku order by tblskumaster.skuname";
									}
									else
									{
										$query="SELECT distinct tblusersku.sku,tblskumaster.`skuname` FROM `tblskumaster` left join tblusersku on tblskumaster.sku=tblusersku.sku where tblusersku.user_id=".$user_id;
									}
									$result=mysql_query($query) or die(mysql_error());
									while($row=mysql_fetch_array($result))
									{
									?>				
										<option value="<?php echo $row['sku']; ?>"<?php if($row['sku']==$sku) echo 'selected="selected"';?>><?php echo $row['skuname'];?></option>
									<?php
									}
									?>
								</select>
							</div>
							<div class="col-lg-1">
								<input type="submit" style="font-size:11px;" name="submit" value="Filter" class="btn btn-primary w3-hover" >
							</div>
							<div class="col-lg-2" style="padding-right:0px">
								<input type="button" style="font-size:11px;" text="Export Data" id="exportpdf" value="Export PDF" class="btn btn-primary button-loading removepadding" onclick="return exportPDF();">
							</div>
							<div class="col-lg-2" style="padding-right:0px;Padding-left:0px;">
								<a href="exportdashboard.php" id="linkexport" style="text-decoration:none"><input style="display:block;font-size:11px;margin-left: -20px;" type="button" text="Export CSV" id="exportdashboard" value="Export CSV" class="btn btn-primary button-loading removepadding"></a>
							</div>
							<div class="col-lg-1">
								<a href="exportreport.php?filename=organifi.csv&pro=Pro_CustomSKU" id="linkcustome" style="text-decoration:none"><input style="display:block;font-size:11px;margin-left:-64px;" type="button" id="exportcustomsku" value="Custom Export" class="btn btn-primary button-loading removepadding"></a>
								<input type="hidden" id="tb" name="tb"></td>
							</div>
						</div>
						</div>
					</div>
					<div id="printHeader" style="display:none;text-align: center; background-color: lightgray;"><h2>SKU Analysis</h2></div>
					<div id="rowPrint" class="for-print" style="display:none"></div>
					<!--div id="modalAgency" class="w3-modal" style="display:None" >
						<div class="w3-modal-content w3-animate-zoom w3-card-8" style="width: 480px; margin-top: 29px;">
							<header class="w3-container w3-blue">
								<h4 id="modalTitle" class="w3-text-white">Information</h4>
							</header>
							<div class="w3-container">
								<p class="w3-padding-top w3-text-grey">
									<label id="datemsg"></label>
								</p>
								<br/>
								<div class="row" style="margin-bottom: 10px;">
									<div class="col-md-3 col-lg-3 col-md-offset-3" style="text-align: right;">
										<input type="button" value="No" class="btn button-loading removepadding" onclick="return comfirmationOnOff(false)" style="background: red;color: white;">
										<input type="submit"  name="submit" value="Yes" class="btn button-loading removepadding" style="background: green;color: white;">
									</div>
								</div>
							</div>
						</div>
					</div-->
				</form>
				<div id="tabsProfile">
					<ul >
						<li><a href="#Daily-Trends" id="Daily"><span>Daily Data</span></a></li>
						<li><a href="#Weekly-Trends" id="Weekly"><span>Weekly Data</span></a></li>
						<li><a href="#Monthly-Trends" id="Monthly"><span>Monthly Data</span></a></li>
						<li><a href="#Yearly-Trends" id="Yearly"><span>Yearly Data</span></a></li>
						<?php if($_SESSION['sku_role']==1){?>
						<li><a href="#Admin-Trends" id="Admin"><span>File Operation</span></a></li>
						<li><a href="#Report-Trends" id="Report"><span>Reports</span></a></li>
						<?php }?>
					</ul>
				   
					<div id="Daily-Trends">
						<center><div class="row">
						<div class="col-lg-12" id="totalTitle">
							<div class="alert alert-info alert-dismissable">
								<?php echo $totalInve;?>
							</div>
						</div>
						</div></center>
						<div class="row" id="dpage1">
							<div class="col-lg-6" id="dpage1_chart1">
								<div class="panel" style="border-color: #69A4DE;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Dchart_div1"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="dDetails1" onclick="SessionChartDaily(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6" id="dpage1_chart2">
								<div class="panel" style="border-color: #91CD6A;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Dchart_div2"></div>
										</div>
										<div  class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="dDetails2" onclick="ConversionChartDaily(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row" id="dpage2">
							<div class="col-lg-6" id="dpage2_chart1">
								<div class="panel" style="border-color: #E36068;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Dchart_div3"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="dDetails3" onclick="UnitOrderDataDaily(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6" id="dpage2_chart2">
								<div class="panel" style="border-color: #CC99FF;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Dchart_div4"></div>
										</div>
										<div  class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="dDetails4" onclick="SalesPriceDataDaily(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row" id="dpage3">
							<div class="col-lg-6" id="dpage3_chart1">
								<div class="panel" style="border-color: #2D7094;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Dchart_div5"></div>
										</div>
										<div  class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="dDetails5" onclick="BuyBoxPercentageDaily(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6" id="dpage3_chart2">
								<div class="panel" style="border-color: #993300;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Dchart_div6"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="dDetails6" onclick="OrderedProductSalesDaily(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row" id="dpage4">
							<div class="col-lg-6" id="dpage4_chart1">
								<div class="panel" style="border-color: #2D7094;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Dchart_div7"></div>
										</div>
										<div  class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="dDetails7" onclick="BuyBoxPercentageDaily_Weighted(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6" id="dpage4_chart2">
								<div class="panel" style="border-color: #993300;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Dchart_div8"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="dDetails8" onclick="OrderedProductSalesDaily_2(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--<div class="row" id="dpage5">
							<div class="col-lg-6" id="dpiepage1_chart1">
								<div class="panel" style="border-color: #2D7094;">
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="DPie_div1"></div>
										</div>
										<div  class="text-right">
											<button class="viewDet" id="dpieData1" onclick="PieChartUnitOrderData(this.id)">View Drill Down<i class="fa fa-arrow-circle-right"></i></button>
											<button class="viewDet" id="dPieDetails1" onclick="PieChartUnitOrderDetails(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6" id="dPiepage2_chart2">
								<div class="panel" style="border-color: #993300;">
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="DPie_div2"></div>
										</div>
										<div class="text-right">
											<button class="viewDet" id="dpieData2" onclick="PieDataDailyData2(this.id)">View Drill Down<i class="fa fa-arrow-circle-right"></i></button>
											<button class="viewDet" id="dpieDetails2" onclick="PieChartDailyData2(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
											
										</div>
									</div>
								</div>
							</div>
						</div>-->
					</div>
					<div id="Weekly-Trends">
						<center><div class="row">
						<div class="col-lg-12" id="totalTitle">
							<div class="alert alert-info alert-dismissable">
								<?php echo $totalInve;?>
							</div>
						</div>
						</div></center>
						<div class="row" id="wpage1">
							<div class="col-lg-6" id="wpage1_chart1">
								<div class="panel" style="border-color: #69A4DE;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Wchart_div1"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="wDetails1" onclick="SessionChartWeekly(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6" id="wpage1_chart2">
								<div class="panel" style="border-color: #91CD6A;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Wchart_div2"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="wDetails2" onclick="ConversionChartWeekly(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row" id="wpage2">
							<div class="col-lg-6" id="wpage2_chart1">
								<div class="panel" style="border-color: #E36068;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Wchart_div3"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="wDetails3" onclick="UnitOrderDataWeekly(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6" id="wpage2_chart2">
								<div class="panel" style="border-color: #CC99FF;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Wchart_div4"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="wDetails4" onclick="SalesPriceDataWeekly(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row" id="wpage3">
							<div class="col-lg-6" id="wpage3_chart1">
								<div class="panel" style="border-color: #2D7094;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Wchart_div5"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="wDetails5" onclick="BuyBoxPercentageWeekly(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6" id="wpage3_chart2">
								<div class="panel" style="border-color: #993300;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Wchart_div6"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="wDetails6" onclick="OrderedProductSalesWeekly(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row" id="wpage4">
							<div class="col-lg-6" id="wpage4_chart1">
								<div class="panel" style="border-color: #2D7094;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Wchart_div7"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="wDetails7" onclick="BuyBoxPercentageWeekly_Weighted(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6" id="wpage4_chart2">
								<div class="panel" style="border-color: #993300;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Wchart_div8"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="wDetails8" onclick="OrderedProductSalesWeekly_2(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row" id="wpage5">
							<div class="col-lg-6" id="wpage5_chart1">
								<div class="panel" style="border-color: #2D7094;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="WPiechart_div1"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="WpieData1" onclick="WeeklyPieChartUnitData(this.id)">View Drill Down<i class="fa fa-arrow-circle-right"></i></button>
											<button class="viewDet" id="wPieDetails1" onclick="WeeklyPiechartUnitdetails(this.id)" >View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6" id="wpage5_chart2">
								<div class="panel" style="border-color: #993300;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="WPiechart_div2"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="WpieData2" onclick="WeeklyPieChartData(this.id)">View Drill Down<i class="fa fa-arrow-circle-right"></i></button>
											<button class="viewDet" id="wPieDetails2" onclick="WeeklyPiechartDetails(this.id)" >View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="Monthly-Trends">
						<center><div class="row">
						<div class="col-lg-12" id="totalTitle">
							<div class="alert alert-info alert-dismissable">
								<?php echo $totalInve;?>
							</div>
						</div>
						</div></center>
						<div class="row" id="mpage1">
							<div class="col-lg-6" id="mpage1_chart1">
								<div class="panel" style="border-color: #69A4DE;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Mchart_div1"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="mDetails1" onclick="SessionChartMonthly(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6"  id="mpage1_chart2">
								<div class="panel" style="border-color: #91CD6A;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Mchart_div2"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="mDetails2" onclick="ConversionChartMonthly(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row"  id="mpage2">
							<div class="col-lg-6"  id="mpage2_chart1">
								<div class="panel" style="border-color: #E36068;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Mchart_div3"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="mDetails3" onclick="UnitOrderDataMonthly(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6"  id="mpage2_chart2">
								<div class="panel" style="border-color: #CC99FF;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Mchart_div4"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="mDetails4" onclick="SalesPriceDataMonthly(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row"  id="mpage3">
							<div class="col-lg-6"  id="mpage3_chart1">
								<div class="panel" style="border-color: #2D7094;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Mchart_div5"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="mDetails5" onclick="BuyBoxPercentageMonthly(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6"  id="mpage3_chart2">
								<div class="panel" style="border-color: #993300;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Mchart_div6"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="mDetails6" onclick="OrderedProductSalesMonthly(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row"  id="mpage4">
							<div class="col-lg-6"  id="mpage4_chart1">
								<div class="panel" style="border-color: #2D7094;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Mchart_div7"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="mDetails7" onclick="BuyBoxPercentageMonthly_Weighted(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6"  id="mpage4_chart2">
								<div class="panel" style="border-color: #993300;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Mchart_div8"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="mDetails8" onclick="OrderedProductSalesMonthly_2(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--<div class="row"  id="mpage5">
							<div class="col-lg-6"  id="mpage5_chart1">
								<div class="panel" style="border-color: #2D7094;">
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="MPiechart_div1"></div>
										</div>
										<div class="text-right">
											<button class="viewDet" id="MpieData1" onclick="MonthlyPieChartUniyData(this.id)">View Drill Down<i class="fa fa-arrow-circle-right"></i></button>
											<button class="viewDet" id="mPieDetails1" onclick="MonthlyPieChartUnitDetails(this.id)" >View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6"  id="mpage5_chart2">
								<div class="panel" style="border-color: #993300;">
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="MPiechart_div2"></div>
										</div>
										<div class="text-right">
											<button class="viewDet" id="MpieData2" onclick="MonthlyPieChartData(this.id)">View Drill Down<i class="fa fa-arrow-circle-right"></i></button>
											<button class="viewDet" id="mPieDetails2" onclick="MonthlyPiechartDetails(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>-->
					</div>
					<div id="Yearly-Trends">
						<center><div class="row">
						<div class="col-lg-12" id="totalTitle">
							<div class="alert alert-info alert-dismissable">
								<?php echo $totalInve;?>
							</div>
						</div>
						</div></center>
						<div class="row" id="Ypage1">
							<div class="col-lg-6" id="Ypage1_chart1">
								<div class="panel" style="border-color: #69A4DE;">
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="YSessionchart_div1"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="YSessionDetails1" onclick="YearlySessionDetails(this.id)" >View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6"  id="Ypage1_chart2">
								<div class="panel" style="border-color: #91CD6A;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="YConversionchart_div2"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="YConversionDetails2" onclick="YearlyConversionRate(this.id)" >View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row" id="Ypage2">
							<div class="col-lg-6" id="Yupage2_chart1">
								<div class="panel" style="border-color: #E36068">
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Yunitorderchart_div1"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="YunitorderDetails1" onclick="YearlyUnitOrder(this.id)" >View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6"  id="Yspage2_chart2">
								<div class="panel" style="border-color: #CC99FF;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="YSalepricechart_div2"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="YsalepriceDetails2" onclick="YearlySalePriceDetails(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row" id="Ypage3">
							<div class="col-lg-6" id="Ypage3_chart1">
								<div class="panel" style="border-color: #2D7094;">
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Ybuyboxchart_div1"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="YbuyboxDetails1" onclick="YearlyBuyBoxDetails(this.id)" >View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6"  id="Ypage3_chart2">
								<div class="panel" style="border-color: #993300;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Yorderproductchart_div2"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="YorderproductDetails2" onclick="YearlyOrderedProductSaleDetails(this.id)" >View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row" id="Ypage4">
							<div class="col-lg-6" id="Ypage4_chart1">
								<div class="panel" style="border-color: #2D7094;">
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Ytotalskubuychart_div1"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="YtotalskubuyDetails1" onclick="YearlyTotalSkuBuyBoxDetails(this.id)" >View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6"  id="Ypage4_chart2">
								<div class="panel" style="border-color: #993300;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="Ytotalskusalchart_div2"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="YtotalskusalDetails2" onclick="YearlyTotalSkuSalesDetails(this.id)" >View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row"  id="Ypage5">
							<div class="col-lg-6"  id="Ypage5_chart1">
								<div class="panel" style="border-color: #2D7094;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="YPiechart_div1"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="YpieData1" onclick="YearlyPieunitorederdata(this.id)" >View Drill Down<i class="fa fa-arrow-circle-right"></i></button>
											<button class="viewDet" id="YPieDetails1" onclick="YearlyPieUnitoredreDetails(this.id)" >View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6"  id="Ypage5_chart2">
								<div class="panel" style="border-color: #993300;">
									<!--div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Moving Line Chart</h3>
									</div-->
									<div class="panel-body">
										<div class="flot-chart">
											<div class="flot-chart-content chart-wrapper1" id="YPiechart_div2"></div>
										</div>
										<div class="text-right">
											<!-- Trigger/Open The Modal -->
											<button class="viewDet" id="YpieData2" onclick="YearlyPiechartdata(this.id)" >View Drill Down<i class="fa fa-arrow-circle-right"></i></button>
											<button class="viewDet" id="YPieDetails2"  onclick="YearlyPiechartDetails(this.id)">View Details<i class="fa fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php if($_SESSION['sku_role']==1){?>
					<div id="Admin-Trends" class="admindiv">
						<div class="row">
							<div class="col-xs-4 col-sm-3 col-md-2 col-lg-2" style="background-color:#e9e9e9;float: left;height:90%;">
								<button id="Sales" onclick="fileOperation(this.id)" style="width: 100%;height:30px;border-style: hidden;color:darkblue;cursor: pointer;margin-top:6px;background-color:white">Sales</button>
								<hr>
								<button id="Inventory" onclick="fileOperation(this.id)" style="width: 100%;height:30px;border-style: hidden;color:darkblue;cursor: pointer;">Inventory</button>
								<hr>
								<button id="SKUFile" onclick="fileOperation(this.id)" style="width: 100%;height:30px;border-style: hidden;color:darkblue;cursor: pointer;">SKU's</button>
								<hr>
								<button id="InventoryAddit" onclick="fileOperation(this.id)" style="width: 100%;height:30px;border-style: hidden;color:darkblue;cursor: pointer;">Inventory Additional</button>
								<hr>
							</div>
							<div class="col-xs-8 col-sm-9 col-md-10 col-lg-10" style="float: left;">
								<div id="SalesDiv" style="display:block;">
								<div>
									<fieldset>
										<legend>Sales Data</legend>
										<div class="row control-group">
											<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 controls">
												<div class="col-lg-6  col-md-6">
													<label>Files Listing<span style="color:red">*</span></label>
													<select name="salescsvfile" id="salescsvfile" style="width:60% !important;align:center;" class="styled-select">
														<option value="0">Select File</option>
														<?php
														$query="SELECT distinct `file_name`FROM `tbldashboard` where `file_name` is not null order by date_range desc";
														$result=mysql_query($query) or die(mysql_error());
														while($row=mysql_fetch_array($result))
														{
														?>				
															<option value="<?php echo $row['file_name']; ?>"><?php echo $row['file_name'];?></option>
														<?php
														}
														?>
													</select>
												</div>
												<div class="col-lg-6 col-sm-12 col-xs-12 col-md-6" style="margin-top:2px;">
													<div class="col-lg-4 col-xs-4 col-md-4 col-sm-4">
														<button id="viewcsv" onclick="viewCSV(salescsvfile,'sales');" class="btn btn-primary button-loading" style="width: 100%;">View</button>
													</div>
													<div class="col-lg-4 col-xs-4 col-md-4 col-sm-4">
														<button id="deletecsv" onclick="removeCSV(salescsvfile,'tbldashboard','salescsvfile');"class="btn btn-primary button-loading" style="width: 100%;">Delete</button>
													</div>
													<div class="col-lg-4 col-xs-4 col-md-4 col-sm-4">
														<button  onclick="openUpload('sales');" class="btn btn-primary button-loading" style="width: 100%;">Upload</button>
													</div>
												</div>
											</div>
										</div>
										<div class="row " style="margin-left:5px;margin-top:12px">
										<table>
										<tr style="background-color: gainsboro;">
										<td style="text-align: center;" onMouseover="this.bgColor='cornflowerblue'" onMouseout="this.bgColor='gainsboro'"><a href="javascript:redirect('Dashboard','Dashboard');" style="font-size: 16px;font-family: initial; text-decoration:none"><img src="images/dashboard.png"><br>All Sales Data</a></td>
										</tr>	
										</table>
										</div>
									</fieldset>
								</div>
								
							</div>
							<div id="InventoryDiv" style="display:none">
								<div>
									<fieldset>
										<legend style="widh:12px">Inventory Data</legend>
										<div class="row control-group">
											<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 controls">
												<div class="col-lg-6  col-md-6">
													<label>Files Listing<span style="color:red">*</span></label>
													<select name="inventorycsvfile" id="inventorycsvfile" style="width:60%;align:center;" class="styled-select">
														<option value="0">Select File</option>
														<?php
														$query="SELECT distinct `file_name`FROM `tblinventory` where `file_name` is not null order by date_range desc";
														$result=mysql_query($query) or die(mysql_error());
														while($row=mysql_fetch_array($result))
														{
														?>				
															<option value="<?php echo $row['file_name']; ?>"><?php echo $row['file_name'];?></option>
														<?php
														}
														?>
													</select>
												</div>
												<div class="col-lg-6 col-sm-12 col-xs-12 col-md-6" style="margin-top:2px;">
													<div class="col-lg-4 col-xs-4 col-md-4 col-sm-4">
													<button id="viewcsv" onclick="viewCSV(inventorycsvfile,'inventory');" class="btn btn-primary button-loading" style="width: 100%;">View</button>
													</div>
													<div class="col-lg-4 col-xs-4 col-md-4 col-sm-4">
													<button id="deletecsv" onclick="removeCSV(inventorycsvfile,'tblinventory','inventorycsvfile');"class="btn btn-primary button-loading" style="width: 100%;">Delete</button>
													</div>
													<div class="col-lg-4 col-xs-4 col-md-4 col-sm-4">
													<button  onclick="openUpload('inventory');" class="btn btn-primary button-loading" style="width: 100%;">Upload</button>
													</div>
												</div>
											</div>
										</div>
										<div class="row" style="margin-left:5px;margin-top:12px">
											<table>
												<tr style="background-color: gainsboro;">
													<td style="text-align: center;" onMouseover="this.bgColor='cornflowerblue'" onMouseout="this.bgColor='gainsboro'"><a href="javascript:redirect('Inventory','Inventory');" style="font-size: 16px;font-family: initial; text-decoration:none"><img src="images/dashboard.png"><br>All Inventory Data</a></td>
												</tr>
											</table>
										</div>
									</fieldset>
								</div>
								
							</div>
							
							<div id="InventoryAdditDiv" style="display:none">
								<div>
									<fieldset>
										<legend>Inventory Additional Data</legend>
										<div class="row control-group">
											<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 controls">
												<div class="col-lg-6  col-md-6 ">
												<label>Files Listing<span style="color:red">*</span></label>
												<select name="inventory_addi_file" id="inventory_addi_file" style="width:60%;align:center;" class="styled-select">
													<option value="0">Select File</option>
													<?php
													$query="SELECT distinct date_format(date_range,'%m-%d-%Y') as view_date_range FROM `tblinventoryonhandinbound` where `date_range` is not null order by date_range desc";
													$result=mysql_query($query) or die(mysql_error());
													while($row=mysql_fetch_array($result))
													{
													?>				
														<option value="<?php echo $row['view_date_range']; ?>"><?php echo $row['view_date_range'];?></option>
													<?php
													}
													?>
												</select>
												</div>
												<div class="col-lg-6  col-md-6 col-sm-6 col-xs-6">
												<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 " style="margin-top:5px;margin-bottom:6px"> 
												<button id="viewcsv" onclick="viewCSV(inventory_addi_file,'InventoryAddi');" class="btn btn-primary button-loading" style="width: 100%;">View</button>
												</div>
												<div class="col-lg-4 col-xs-4 col-md-4 col-sm-4"></div>
												<div class="col-lg-4 col-xs-4 col-md-4 col-sm-4"></div>
												</div>
											</div>
										</div>
									</fieldset>
								</div>
								<div style="height: 55%; widh:100%">
									<table>
										<tr style="background-color: gainsboro;">
											<td style="text-align: center;" onMouseover="this.bgColor='cornflowerblue'" onMouseout="this.bgColor='gainsboro'"><a href="javascript:redirect('InventoryAddi','InventoryAddi');" style="font-size: 16px;font-family: initial; text-decoration:none"><img src="images/dashboard.png"><br>All Additional Inventory Data</a></td>
										</tr>
									</table>
								</div>
							</div>
							<div style="width:89%;float: left;">
							
							
							<div id="SKUDiv" style="display:none">
								<fieldset style="background-color: whitesmoke;margin-top: 6px;">
									<!--legend style="width: 9%;">All SKU's Data</legend-->
									<span>Click on below image for view the file</span>
								</fieldset>
									<div style="height: 55%; widh:100%;margin-top: 10px;">
										<table>
											<tr style="background-color: gainsboro;">
												<td style="text-align: center;" onMouseover="this.bgColor='cornflowerblue'" onMouseout="this.bgColor='gainsboro'"><a href="javascript:redirect('Skumaster','Skumaster');" style="font-size: 16px;font-family: initial;text-decoration:none"><img src="images/skumaster.png"><br>All SKU's</a></td>
											</tr>	
										</table>
									</div>
							</div>
						</div>
							</div>
						</div>
						
						
						<!--<div style="background-color:#e9e9e9;float: left;height:90%;">
							<button id="Sales" onclick="fileOperation(this.id)" style="width: 100%;height:30px;border-style: hidden;color:darkblue;cursor: pointer;margin-top:6px;background-color:white">Sales</button>
							<hr>
							<button id="Inventory" onclick="fileOperation(this.id)" style="width: 100%;height:30px;border-style: hidden;color:darkblue;cursor: pointer;">Inventory</button>
							<hr>
							<button id="SKUFile" onclick="fileOperation(this.id)" style="width: 100%;height:30px;border-style: hidden;color:darkblue;cursor: pointer;">SKU's</button>
							<hr>
							<button id="InventoryAddit" onclick="fileOperation(this.id)" style="width: 100%;height:30px;border-style: hidden;color:darkblue;cursor: pointer;">Inventory Additional</button>
							<hr>
						</div>-->
						
					</div>
					
					<div id="Report-Trends" class="admindiv" style="height;90%">
						<div style="background-color:#e9e9e9;width:10%;float: left;height:90%;margin-right: 5px;">
							<button id="btnIOReport" onclick="reportOperation(this.id,'Inventory')" style="width: 100%;height:30px;border-style: hidden;color:darkblue;cursor: pointer;margin-top:6px;">Inventory</button>
							<hr>
							<button id="btnAnalReport" onclick="reportOperation(this.id,'InventoryAnal')" style="width: 100%;height:30px;border-style: hidden;color:darkblue;cursor: pointer;">Inventory Analysis</button>
							<hr>
							<button id="btnWeeklyPercentageReport" onclick="reportOperation(this.id,'WeeklyPercentageReport')" style="width: 100%;height:30px;border-style: hidden;color:darkblue;cursor: pointer;">Weekly Percentage Change</button>
							<hr>
							<button id="btnSalesReport" onclick="reportOperation(this.id,'SalesReport')" style="width: 100%;height:30px;border-style: hidden;color:darkblue;cursor: pointer;">Sales Report</button>
							<hr>
						</div>
						<div id="viewreport" style="width:89%;float: left;height:90%"></div>
						<!--div id="IReportDiv" style="width:89%;float: left;"></div-->
						<!--div id="IAReportDiv" style="width:89%;float: left;display:none"></div-->
					</div>
					<?php } ?>
				</div>
	
				<!-- The Modal -->
				<div id="chartModal" class="modal" style="w3-animate-zoom">
				  <!-- Modal content -->
				  <div class="modal-content">
					<span class="close">&times;</span>
					<div id="modalChart"></div> 
					<div id="modalTable1">
						<table id="modalTable">
							<tbody></tbody>
						</table>
					</div> 	
				  </div>
				</div>
				<div  id="chartModaldata"  class="modal" style="w3-animate-top">
					<!-- Modal content -->
					<div class="modal-content" style="height: 450px;
					overflow-y: scroll;">
						<span id="chartModaldata_Close" class="close">&times;</span>
					<div id="modalChartdata"></div> 
						<div id="modalTabledata1">

							<center>
								<table class="table table-bordered">
									<thead>
										<tr >
											<th >sku</th>
											<th >ordered_product_sales</th>
										</tr>
									</thead>
								<tbody>
									<?php
									$i=0;
									if($user_role==1)
									{
									$Q="SELECT sku,Round(IFNULL(sum(ordered_product_sales),0),2) as ordered_product_sales From `tbldashboard` where 1=1 " .$whereDate."   group by sku order by ordered_product_sales desc";
									}
									else{
									$Q="SELECT tblusersku.sku as sku , Round(IFNULL(sum(ordered_product_sales),0),2)  as ordered_product_sales FROM tbldashboard,tblusersku where tblusersku.sku=tbldashboard.sku " .$whereDate." and tblusersku.user_id=".$user_id." group by tblusersku.sku Order by ordered_product_sales desc";

									}
									$R = mysql_query($Q)or die(mysql_error());
									$nums_row=mysql_num_rows($R); 
									while($row = mysql_fetch_array($R)){

									$i=$i+1
									?>
									<td><?php echo $row['sku']; ?></td>
									<td><?php echo $row['ordered_product_sales'];?></td>
									</tr>
									<?php }
									?>
								</tbody>
								</table>
							</center>
						</div> 	
					</div>
				</div>
				
				<!--UnitOREDER-->
				<div  id="chartModalUnitdata"  class="modal" style="w3-animate-top">
					<!-- Modal content -->
					<div class="modal-content" style="height: 454px;
					overflow-y: scroll;">
						<span id="chartModalUnitdata_Close" class="close">&times;</span>
					<div id="modalChartdatau"></div> 
						<div id="modalTabledatau">

							<center>
								<table class="table table-bordered">
									<thead>
										<tr >
											<th >sku</th>
											<th >Unit Ordered</th>
										</tr>
									</thead>
								<tbody>
									<?php
									$i=0;
									if($user_role==1)
									{
									$Q="SELECT sku,Round(IFNULL(sum(units_ordered),0),2) as ordered_product_sales From `tbldashboard` where 1=1 " .$whereDate."   group by sku order by ordered_product_sales desc";
									}
									else{
									$Q="SELECT tblusersku.sku as sku , Round(IFNULL(sum(units_ordered),0),2)  as ordered_product_sales FROM tbldashboard,tblusersku where tblusersku.sku=tbldashboard.sku " .$whereDate." and tblusersku.user_id=".$user_id." group by tblusersku.sku Order by ordered_product_sales desc";

									}
									$R = mysql_query($Q)or die(mysql_error());
									$nums_row=mysql_num_rows($R); 
									while($row = mysql_fetch_array($R)){

									$i=$i+1
									?>
									<td><?php echo $row['sku']; ?></td>
									<td><?php echo $row['ordered_product_sales'];?></td>
									</tr>
									<?php }
									?>
								</tbody>
								</table>
							</center>
						</div> 	
					</div>
				</div>
			</div>
<script>
$(function () {
            setTimeout(function () {
                var WindowWidth = $(window).width();
                if (WindowWidth <= 565) {
                    $('.custom-select a').css('display', 'none');
                    $('.custom-select select').css('display', 'block');
                }
            }, 5000);
        });
var modal1 = document.getElementById('chartModalUnitdata');
// Get the <span> element that closes the modal
var span = document.getElementById('chartModalUnitdata_Close');
// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal1.style.display = "none";
}
$(function(){
$('#chartModalUnitdata').click(function(){
$('#chartModalUnitdata').css('display','none');
});
});
//
var modal1 = document.getElementById('chartModaldata');
// Get the <span> element that closes the modal
var span = document.getElementById('chartModaldata_Close');
// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal1.style.display = "none";
}
$(function(){
$('#chartModaldata').click(function(){
$('#chartModaldata').css('display','none');
});
});

// Get the modal
var modal = document.getElementById('chartModal');
// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];
// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}
// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
	<footer>
	<?php include("copyright.html");?>  
	</footer>
</body>
</html>