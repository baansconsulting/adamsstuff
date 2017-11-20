<?php include("head.php");//session_start();
// if((!isset($_SESSION['sku_user_email'])) || (!isset($_SESSION['sku_role'])) || (!$_SESSION['sku_logged']==1) || (!$_SESSION['sku_role']==1))
// {
	// header('Location:index.php');
// }
//include('config.php');
date_default_timezone_set('GMT');
 ?>
<html>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
	<!--link rel="stylesheet" href="css/jquery-ui.css"/-->
	<link rel="stylesheet" href="css/Styles.css"/>
	<link rel="stylesheet" href="css/admindiv.css"/>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.js" type="text/javascript"></script>
	<script src="js/jquery-ui-1.10.3.min.js"></script> 
	<script src="js/main.js"></script> 
	<link rel="stylesheet" href="css/main.css"/>
	<script type="text/javascript">
	$(document).ready(function() {	
			$( function() {
				$('#uploaddate').datepicker({dateFormat: 'mm/dd/yy'});
			  } );
		});
	
	</script>
	<style>
	.choose
	{
		border: 1px solid #DDDDDD;
		border-radius: 2px;
		background-color: white;
	}
	</style>
</head>
<body style="height: initial;">
	<div id="top" class="top" style="min-height:0px;"/> 
		<div id="welcome" class="topTitle" style="font-size: 35px;">
			Welcome To SKU Analysis
		</div>
		
	<div>
		<form class="form-horizontal well" enctype="multipart/form-data"method="post" name="upload_excel" enctype="multipart/form-data" onsubmit="return validateUpload();">
			<fieldset>
				<legend>Import CSV file</legend>
				<div class="control-group">
					<div class="controls">
						<label>Date:<span style="color:red">*</span></label>
						<input type="text" name="uploaddate" id="uploaddate" class="input-large">
						<label style="margin-left:1%">CSV File:<span style="color:red">*</span></label>
						<input type="file" name="file" id="file" class="choose">
						<button type="submit" id="submit" name="Import" class="btn btn-primary button-loading" data-loading-text="Loading...">Upload</button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
	</div>
<?php
if(isset($_POST["Import"]))
{		
		$flag=true;
		$filename=$_FILES["file"]["name"];
		//$filename=substr($filename,0,strlen($filename)-4);
		$uploaddate=date('Y-m-d',strtotime($_POST["uploaddate"]));
		echo '<script language="javascript">
						document.getElementById("uploaddate").value="'.$_POST["uploaddate"].'";</script>';
						
		$checkFile="select 'x' from tbldashboard where file_name='".$filename."'";
		$result  = mysql_query($checkFile);
		$rowcount = mysql_num_rows($result);
		if ($rowcount>0)
		{
			echo $filename." already exist";
		}
		else 
		{
			echo "<table><tr><td><div id=\"progress\" style=\"width:500px;border:1px solid #ccc;\"></div></td><td><div id=\"progress_percentage\"></div></td></tr></table>
			<div id=\"row_information\" style=\"width\"></div>";
			$user_id=$_SESSION['sku_user_id'];
			ini_set('max_execution_time', 7200); //7200 seconds = 120 minutes
			
			$csvname=$_FILES["file"]["tmp_name"];
			$rw=0;
			
			//here we define the total columns of the csv
			$allowedColNum=17;
			$newFilecolnum=13; //31 july asign column count static
			
			$fp = file($csvname);
			$highestRow=count($fp);
			//echo $highestRow;
			if($_FILES["file"]["size"] > 0)
			{
				$file = fopen($csvname, "r");
				
				mysql_query("delete from tbltempdashboard where user_id=".$user_id);			
				while (($row = fgetcsv($file, 10000, ",")) !== FALSE)
				{
					if($rw>0)
					{
						//here count the columns to match the file // count($row) is the number of columns
						$numcols = count($row);
						//echo "column is ::".$numcols."<br>";
						
						// fail out of the loop if columns are incorrect
						// if ($numcols != $allowedColNum) {
							// $flag=false;
							// echo "<script type=\"text/javascript\">
									// alert(\"Invalid File:Please Upload valid CSV File. or Please make sure that columns are in correct order.\");
									// </script>";							
							// break;
						// }
						// else {
						// //It wiil insert a row to our subject table from our csv file
						// $sqlquery2=") values('".$uploaddate."',";
						// $sqlquery1="INSERT INTO `tbltempdashboard`(date_range,`parent_asin`, `child_asin`, `title`, `sku`, `sessions`, `session_percentage`, `page_views`, `page_views_percentage`, `buy_box_percentage`, `units_ordered`, `units_ordered_b2b`, `unit_session_percentage`, `unit_session_percentage_b2b`, `ordered_product_sales`, `ordered_product_sales_b2b`, `total_order_items`, `total_order_items_b2b`,selling_price,user_id";
						// for($i=0;$i<$numcols;$i++)
						// {
							// $sqlquery2=$sqlquery2."'".str_replace("'","''",$row[$i])."',";
						// }
						// $sql = $sqlquery1.$sqlquery2."0,".$user_id.")";
						// //we are using mysql_query function. it returns a resource on true else False on error
						// $result = mysql_query($sql);
						// }
						if($numcols ==$allowedColNum)
						{
						//echo "old";
							//It wiil insert a row to our subject table from our csv file
							$sqlquery2=") values('".$uploaddate."',";
							$sqlquery1="INSERT INTO `tbltempdashboard`(date_range,`parent_asin`, `child_asin`, `title`, `sku`, `sessions`, `session_percentage`, `page_views`, `page_views_percentage`, `buy_box_percentage`, `units_ordered`, `units_ordered_b2b`, `unit_session_percentage`, `unit_session_percentage_b2b`, `ordered_product_sales`, `ordered_product_sales_b2b`, `total_order_items`, `total_order_items_b2b`,selling_price,user_id";
						}
						elseif($numcols==$newFilecolnum)
						{
						//echo "new";
							$sqlquery2=") values('".$uploaddate."',";
							$sqlquery1="INSERT INTO `tbltempdashboard`(date_range,`parent_asin`, `child_asin`, `title`, `sku`, `sessions`, `session_percentage`, `page_views`, `page_views_percentage`, `buy_box_percentage`, `units_ordered`, `unit_session_percentage`,`ordered_product_sales`,  `total_order_items`,selling_price,user_id";
						}
						else
						{
							$flag=false;
							echo "<script type=\"text/javascript\">
									alert(\"Invalid File:Please Upload valid CSV File. or Please make sure that columns are in correct order.\");
									</script>";							
							break;
						}
						for($i=0;$i<$numcols;$i++)
						{
							$sqlquery2=$sqlquery2."'".str_replace("'","''",$row[$i])."',";
						}
						$sql = $sqlquery1.$sqlquery2."0,".$user_id.")";
						//we are using mysql_query function. it returns a resource on true else False on error
						$result = mysql_query($sql);
						if(! $result )
						{
							$flag=false;
							echo "<script type=\"text/javascript\">
									alert(\"Invalid Row:Please check row.\");
								</script>";
								break;
						} 
								
						$percent = intval($rw/($highestRow-1) * 100)."%";
						 echo '<script language="javascript">
						document.getElementById("progress").innerHTML="<div id=\'pbar\' style=\"width:'.$percent.';background-color:#6495ed;\">&nbsp;</div>";
						document.getElementById("progress_percentage").innerHTML="'.$percent.'";
						document.getElementById("row_information").innerHTML="'.($rw+1).'/'.($highestRow). ' row(s) processed.";
						</script>';
						// Send output to browser immediately
						flush();
						//sleep(1);
					}
					$rw=$rw+1;
				}
				fclose($file);
				echo '<script language="javascript">
						document.getElementById("pbar").style.backgroundColor = "#8BC34A";
						document.getElementById("row_information").innerHTML="please wait..";
						</script>';
				flush();
				if($flag)
				{
					$query="SELECT `date_range`, `parent_asin`, `child_asin`, `title`, `sku`, `sessions`, `session_percentage`, `page_views`, `page_views_percentage`, `buy_box_percentage`, `units_ordered`, case when `units_ordered_b2b` IS NULL or `units_ordered_b2b` = '' then 0 else  `units_ordered_b2b` end as `units_ordered_b2b`, `unit_session_percentage`, case when `unit_session_percentage_b2b` IS NULL or `unit_session_percentage_b2b`= '' then 0 else `unit_session_percentage_b2b` end as `unit_session_percentage_b2b` , `ordered_product_sales`, case when `ordered_product_sales_b2b` IS NULL or `ordered_product_sales_b2b`='' then 0 else `ordered_product_sales_b2b` end as `ordered_product_sales_b2b` ,`total_order_items`,case when `total_order_items_b2b` IS NULL or `total_order_items_b2b`='' then 0 else `total_order_items_b2b` end as `total_order_items_b2b` FROM `tbltempdashboard` WHERE user_id=".$user_id;
					
					$insert="INSERT INTO `tbldashboard`(`date_range`, `parent_asin`, `child_asin`, `title`, `sku`, `sessions`, `session_percentage`, `page_views`, `page_views_percentage`, `buy_box_percentage`, `units_ordered`, `units_ordered_b2b`, `unit_session_percentage`, `unit_session_percentage_b2b`, `ordered_product_sales`, `ordered_product_sales_b2b`, `total_order_items`, `total_order_items_b2b`, `selling_price`,file_name) values"; 
					$values="";
					$masterInsert="";
					$R = mysql_query($query);
					$nums_row=mysql_num_rows($R); 
					if($nums_row>0)
					{
						while($row = mysql_fetch_array($R))
						{
							$orderProductSale=getNumeric($row['ordered_product_sales']);
							$unitOrder=getNumeric($row['units_ordered']);
							$values="('".$row['date_range']."','".str_replace("'", "''", $row['parent_asin'])."','".str_replace("'", "''", $row['child_asin'])."','".str_replace("'", "''", $row['title'])."','".str_replace( "'", "''", $row['sku'])."',".
							getNumeric($row['sessions']).",".getNumeric($row['session_percentage']).",".getNumeric($row['page_views']).",".getNumeric($row['page_views_percentage']).",".
							getNumeric($row['buy_box_percentage']).",".$unitOrder.",".getNumeric($row['units_ordered_b2b']).",".getNumeric($row['unit_session_percentage']).",".
							getNumeric($row['unit_session_percentage_b2b']).",".getNumeric($row['ordered_product_sales']).",".getNumeric($row['ordered_product_sales_b2b']).",".
							getNumeric($row['total_order_items']).",".getNumeric($row['total_order_items_b2b']).",".$orderProductSale.",'".$filename."')";
							$masterInsert=$insert.rtrim($values,",");
							//echo $masterInsert;
							mysql_query($masterInsert);
						} 
					}
					
					//update sku master
					$Q = "insert into tblskumaster(sku,skuname) SELECT Distinct tbldashboard.sku,concat(tbldashboard.sku,'_Name') as skuname FROM tbldashboard where tbldashboard.sku not in(select tblskumaster.sku from tblskumaster where  tblskumaster.sku=tbldashboard.sku)";
					$R = mysql_query($Q);
					echo '<script language="javascript">
							document.getElementById("row_information").innerHTML="'.$filename.'";opener.location.reload();</script>';
						// This is for the buffer achieve the minimum size in order to flush data
							echo str_repeat(' ',1024*64);
						// Send output to browser immediately
							flush();
				}
			}
		}
		if($flag)
		{?>
		<center style="background-color: lightgray;">
			<table style="margin-top: 40px;width: 16%;text-align: center;"><tr>
				<td><button id="goback" style="" onclick="window.close();">Go Back</button></td>
				<td><button onclick="redirect('Dashboard','<?php echo $filename; ?>');" style="">View</button></td>
				<td><button onclick="deleteCSV('<?php echo $filename; ?>',1,'tbldashboard','salescsvfile');" style="">Delete</button></td>
			</tr>
			</table>
		</center>
		
	<?php 
		}
	}	
	function getNumeric($string)
	{
		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		return preg_replace('/[^a-zA-Z0-9\s\.]/', '', $string); // Removes special chars.
	}
	
	unset($POST); ?>
</body>				
</html>
