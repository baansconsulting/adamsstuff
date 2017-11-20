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
		color: black
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
						
		$checkFile="select 'x' from tblinventory where file_name='".$filename."'";
		//echo $checkFile;
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
			$user_id=1;//$_SESSION['sku_user_id'];
			ini_set('max_execution_time', 7200); //7200 seconds = 120 minutes
			
			$csvname=$_FILES["file"]["tmp_name"];
			$rw=0;
			
			//here we define the total columns of the csv
			$allowedColNum=18;
			
			$fp = file($csvname);
			$highestRow=count($fp);
			//echo $highestRow;
			if($_FILES["file"]["size"] > 0)
			{
				$file = fopen($csvname, "r");
				
				mysql_query("delete from tblinventorytemp where user_id=".$user_id);			
				while (($row = fgetcsv($file, 10000, ",")) !== FALSE)
				{
					if($rw>0)
					{
						//here count the columns to match the file // count($row) is the number of columns
						$numcols = count($row);
						//echo "column is ::".$numcols."<br>";
						
						// fail out of the loop if columns are incorrect
						if ($numcols != $allowedColNum) {
							$flag=false;
							echo "<script type=\"text/javascript\">
									alert(\"Invalid File:Please Upload valid CSV File. or Please make sure that columns are in correct order.\");
									</script>";							
							break;
						}
						else {
						 //$timestamp = strtotime(str_replace('-', '/', $date));
						// $date = date('Y-m-d', $timestamp);

						//It wiil insert a row to our subject table from our csv file
						$sqlquery2=") values('".$uploaddate."',";
						$sqlquery1="INSERT INTO `tblinventorytemp`(date_range,`sku`, `fnsku`, `asin`, `product_name`, `condition`, `your_price`, `mfn_listing_exists`, `mfn_fulfillable_quantity`, `afn_listing_exists`, `afn_warehouse_quantity`, `afn_fulfillable_quantity`, `afn_unsellable_quantity`, `afn_reserved_quantity`, `afn_total_quantity`, `per_unit_volume`, `afn_inbound_working_quantity`, `afn_inbound_shipped_quantity`,afn_inbound_receiving_quantity,user_id";
						//echo $sqlquery1."<br>";
						for($i=0;$i<$numcols;$i++)
						{
							$sqlquery2=$sqlquery2."'".str_replace("'","''",$row[$i])."',";
						}
						//$sqlquery2=$sqlquery2.round((((float)str_replace("$","",$row[13]))/((float)str_replace("$","",$row[9]))),2);
						//$sqlquery2=rtrim($sqlquery2,",");
						$sql = $sqlquery1.$sqlquery2.$user_id.")";
						//echo $sql;
						//break;
						
						//we are using mysql_query function. it returns a resource on true else False on error
						$result = mysql_query($sql);
						}
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
						document.getElementById("row_information").innerHTML="please wait...";
						</script>';
				flush();
				if($flag)
				{
					$query="SELECT `date_range`, `sku`, `fnsku`, `asin`, `product_name`, `condition`, `your_price`, `mfn_listing_exists`, `mfn_fulfillable_quantity`, `afn_listing_exists`, `afn_warehouse_quantity`, `afn_fulfillable_quantity`, `afn_unsellable_quantity`, `afn_reserved_quantity`, `afn_total_quantity`, `per_unit_volume`, `afn_inbound_working_quantity`, `afn_inbound_shipped_quantity`,`afn_inbound_receiving_quantity` FROM `tblinventorytemp` WHERE user_id=".$user_id;
					$insert="INSERT INTO `tblinventory`(`date_range`, `sku`, `fnsku`, `asin`, `product_name`, `condition`, 
					`your_price`, `mfn_listing_exists`, `mfn_fulfillable_quantity`, `afn_listing_exists`, `afn_warehouse_quantity`, `afn_fulfillable_quantity`, 
					`afn_unsellable_quantity`, `afn_reserved_quantity`, `afn_total_quantity`, `per_unit_volume`, `afn_inbound_working_quantity`, `afn_inbound_shipped_quantity`,`afn_inbound_receiving_quantity`,file_name) values"; 
					$values="";
					$masterInsert="";
					$R = mysql_query($query);
					$nums_row=mysql_num_rows($R); 
					if($nums_row>0)
					{
						while($row = mysql_fetch_array($R))
						{
							$values="('".$row['date_range']."','".str_replace("'", "''", $row['sku'])."','".str_replace("'", "''", $row['fnsku'])."','".str_replace("'", "''", $row['asin'])."','".str_replace( "'", "''", $row['product_name'])."','".str_replace( "'", "''", $row['condition'])."',".
							getNumeric($row['your_price']).",'".str_replace("'", "''", $row['mfn_listing_exists'])."',".getNumeric($row['mfn_fulfillable_quantity']).",'".str_replace("'", "''", $row['afn_listing_exists'])."',".getNumeric($row['afn_warehouse_quantity']).",".getNumeric($row['afn_fulfillable_quantity']).",".
							getNumeric($row['afn_unsellable_quantity']).",".getNumeric($row['afn_reserved_quantity']).",".getNumeric($row['afn_total_quantity']).",".
							getNumeric($row['per_unit_volume']).",".getNumeric($row['afn_inbound_working_quantity']).",".getNumeric($row['afn_inbound_shipped_quantity']).",".
							getNumeric($row['afn_inbound_receiving_quantity']).",'".$filename."')";
							$masterInsert=$insert.rtrim($values,",");
							//echo $masterInsert;
							//echo "<br><br>";
							mysql_query($masterInsert);
						} 
					}
					
					//update sku master
					$Q = "insert into tblskumaster(sku,skuname) SELECT Distinct tblinventory.sku,concat(tblinventory.sku,'_Name') as skuname FROM tblinventory where tblinventory.sku not in(select tblskumaster.sku from tblskumaster where  tblskumaster.sku=tblinventory.sku)";
					$R = mysql_query($Q);
					if($R)
					{
						$Q="Insert into tblinventoryonhandinbound (sku,date_range,inbound,onhand,CreatedBy,Createdon)
							Select sku,date_range,(cast(afn_inbound_working_quantity as unsigned)+cast(afn_inbound_shipped_quantity as unsigned)+
							cast(afn_inbound_receiving_quantity as unsigned)) as inbound,
							(cast(afn_fulfillable_quantity as unsigned)+cast(afn_reserved_quantity as unsigned)) as onhand,'' as CreatedBy,now() as CreatedOn
							From tblinventory
							WHERE concat('',afn_inbound_working_quantity * 1) = afn_inbound_working_quantity 
							AND concat('',afn_inbound_shipped_quantity * 1) = afn_inbound_shipped_quantity 
							AND concat('',afn_inbound_receiving_quantity * 1) = afn_inbound_receiving_quantity
							AND concat('',afn_fulfillable_quantity * 1) = afn_fulfillable_quantity 
							AND concat('',afn_reserved_quantity * 1) = afn_reserved_quantity
							AND date_range ='".$uploaddate."';";
						$R = mysql_query($Q);
					} 
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
					<td><button onclick="redirect('Inventory','<?php echo $filename; ?>');" style="">View</button></td>
					<td><button onclick="deleteCSV('<?php echo $filename; ?>',1,'tblinventory','inventorycsvfile');" style="">Delete</button></td>
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
