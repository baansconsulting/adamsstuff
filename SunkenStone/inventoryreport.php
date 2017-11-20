<?php include("config.php");
$legend="";
$exportpath="#";
$reportType="";
$fieldCount=0;
$query="";
if (isset($_REQUEST['report']))
{    
	$reportType=$_REQUEST['report'];
	if ($_REQUEST['report']=='Inventory')
	{
		//$query = mysql_query('CALL `pro_insertintoinventoryOnhandInbound`()');
		$query='CALL `pro_insertintoinventoryOnhandInbound`()';
		$legend="OnHand and Inbound";
		$exportpath="exportreport.php?filename=inventoryOnHandInBound.csv&pro=pro_insertintoinventoryOnhandInbound";
	}
	else if ($_REQUEST['report']=='InventoryAnal')
	{
		//$query = mysql_query('CALL `Pro_InventoryAnalysis`()');
		$query='CALL `Pro_InventoryAnalysis`()';
		$legend="Inventory Analysis";
		$exportpath="exportreport.php?filename=inventoryanalysis.csv&pro=Pro_InventoryAnalysis";
	}
	else if ($_REQUEST['report']=='WeeklyPercentageReport')
	{
		//$query = mysql_query('CALL `WeeklyPercentageChange`()');
		$query='CALL `WeeklyPercentageChange`()';
		$legend="Weekly Percentage Report";
		$exportpath="exportreport.php?filename=weeklypercentage.csv&pro=WeeklyPercentageChange";
	}
	else if ($_REQUEST['report']=='SalesReport')
	{
		//$query = mysql_query('CALL `Sales_Report`()');
		$query='CALL `Sales_Report`()';
		$legend="Sales Report";
		$exportpath="exportreport.php?filename=salesreport.csv&pro=Sales_Report";
	}
	
	$result = mysql_query($query);
  
	if ($result==false)
	{
		$result=mysql_query("select 'Data Not Found' as `Data`");
	}
	else if(mysql_num_rows($result)<=0)
	{
		$result=mysql_query("select 'Data Not Found' as `Data`");
	}
	$fieldCount= mysql_num_fields($result);
}
?>
<html>
<body>
<head>
<style>
.datagrid table 
{
 border-collapse: collapse; text-align: left; width: 100%; 
 } 
 
 .datagrid 
 {
 -webkit-border-radius: 3px;  
 -moz-border-radius: 3px;
 border-radius: 3px; 
 }
 
 .datagrid table td, .datagrid table th 
 { 
 
}
 
 .datagrid table thead th 
 {
 background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #8C8C8C), color-stop(1, #7D7D7D) );
 background:-moz-linear-gradient( center top, #8C8C8C 5%, #7D7D7D 100% );
 filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#8C8C8C', endColorstr='#7D7D7D');
 background-color:#8C8C8C; 
 color:#FFFFFF; 
 font-size: 12px; 
 font-weight: bold; 
 border-left: 0px solid #A3A3A3;
text-align: center;
border: 1px solid #cac3c3;
 min-width:65px; 
 padding:7px 6px 6px 6px;
 } 
 
 .datagrid table tbody td 
 { 
 /*color: #7D7D7D;*/ 
 font-family: monospace;
 border-left: 1px solid #cac3c3;font-size: 12px;
 font-weight: normal; 
 text-align: right;
border: 1px solid #cac3c3;
padding:6px 4px 6px 5px;
 }
 
.alt{
background-color:rgba(224, 221, 221, 0.53);
 }
 .td-pink{background-color:rgb(236, 163, 191);}
 .td-green{background-color:darkseagreen;}
 .td-blue{background-color:#a4b4ea;}
.td-orange{background-color:blanchedalmond}
 
 </style>
 
 </head>
		<div style="width: 100%;margin-top: 10px;">
			<div style="float:left;font-size:14px"><b><?php echo $legend; ?></b></div>
			<div style="width: 99%;text-align:right">
				<a href="<?php echo $exportpath; ?>" title="export to excel" style="text-decoration:none">
					<img src="images/export-excel2.png" style="height: 30px;"/>
				</a>
			</div>
		</div>
		<div class="datagrid" style="height: 520px; width:100%;overflow-x:auto;overflow-y:auto;white-space: nowrap;">
			<table>
				<thead>
					<tr >
						<?php 
							$i=0;
							while ($i < $fieldCount) {
								$meta = mysql_fetch_field($result, $i);
								  if($i==0){ echo "<th style='text-align: left;'>".$meta->name."</th>";}
								  else{echo "<th style='text-align: right;'>".$meta->name."</th>";}
								  
							$i++;}	?>		
					</tr>
				</thead>
				
				<tbody>
					<?php
					$flag=true;
						while($row = mysql_fetch_row($result))
						{  
							if($flag==true)
						   {
							echo "<tr>";
							$flag=false;
							}
							else
							{
							echo "<tr class='alt'>";
							$flag=true;
							}
							for($j=0;$j<$i;$j++)
							{  if($j==0)
							   { 
								   
							   ?>
								<td style="text-align:left !important;" ><?php echo $row[$j]; ?></td>
							<?php 
							   }
							   if($j>0)
							   { 
								$cls="";
								if ($reportType=='InventoryAnal' && ($j==6 || $j==10))
								{
									/*if((($row[$j]>(($row[$j-1]*(1+0.25))+1)) && ($row[$j-1]>0) && ($row[$j]>0)) || (($row[$j-1]<=0) && ($row[$j]>0)))
									{
										$cls="td-green";
									}
									else if((($row[$j])<($row[$j-1]*(1+0.25)+1)) && (($row[$j-1])<($row[$j]))) 
									{
										$cls="td-blue";
									}
									else if($row[$j]<($row[$j-1]*1-0.25))
									{
										$cls="td-pink";
									}*/
									if(($row[$j-1])==($row[$j]))
									{
										$cls="";
									}									
									else if(((1.25*$row[$j-1])>=$row[$j]) && ($row[$j-1]<$row[$j]))
									{
										$cls="td-green";
									}
									else if((1.25*($row[$j-1]))<($row[$j]))
									{
										$cls="td-blue";
									}
									else if(((1-0.25)*($row[$j-1]))>($row[$j]))
									{
										$cls="td-pink";
									}
									else if((((1-0.25)*($row[$j-1]))<=($row[$j])) && (($row[$j-1])>($row[$j])))
									{
										$cls="td-orange";
									}
									else
									{
										$cls="";
									}
								}
							   ?>
								<td class=<?php echo $cls;?> ><?php echo $row[$j]; ?></td>
							<?php }
							};
							echo"</tr>";
							
						}
					?>	
				</tbody>
			</table>
		</div>
	</div>
	<script>
	
	</script>
	</body>
</html>	