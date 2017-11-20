<?php include("head.php");
//include("config.php");
//$table="tbldashboard";
$table=$_SESSION['table'];
//$sql = "SELECT `ID`, `date_range`, `parent_asin`, `child_asin`, `sku`, `sessions`, `session_percentage`, `page_views`, `page_views_percentage`, `buy_box_percentage`, `units_ordered`, `units_ordered_b2b`, `unit_session_percentage`, `unit_session_percentage_b2b`, `ordered_product_sales`, `ordered_product_sales_b2b`, `total_order_items`, `total_order_items_b2b`, `selling_price`";
$sql=$_SESSION['Query'];
//$sql=$sql." FROM ". $table;
$result=mysql_query($sql);
$colIndex = 0;
$columnName= array();
$msg=false;
while ($colIndex < mysql_num_fields($result)) 
{
    $meta = mysql_fetch_field($result, $colIndex);
    $columnName[$colIndex]="`".($meta->name)."`";
    $colIndex++;
}

	if(((isset($_REQUEST['rowID'])) && ($_REQUEST['rowID']!='null')) && ($_REQUEST['Task']='Delete'))
	{
		$rowID=$_REQUEST['rowID'];
		$deleteQuery = "delete FROM ".$table." where ID=".$rowID;
		$result=mysql_query($deleteQuery);
		//echo $deleteQuery;
		if ($result){
			echo true;
			}
		else{
			echo false;
			} 
	}
	
	elseif(isset($_REQUEST['rowsID']) && (isset($_REQUEST['rowsData'])) && ($_REQUEST['Task']='InsertUpdate'))
	{
		$rowsData=explode(",",$_REQUEST['rowsData']);
		$rowsID=explode(",",$_REQUEST['rowsID']);
		$totalCols=sizeof($columnName);
		$j=1;
		$k=1;
		$col=1;
		//$colNum=1;
		$rowID="";
		$baseQueryUpdate="update ".$table ." set ";
		$baseQueryInsert="insert into " .$table ."(";
		$query="";
		$str="";
		$values="";
		
		//echo "Row count-".count($rowsID)."<br>";
		for($i=0;$i<count($rowsID);$i++)
		{
			$rowID=$rowsID[$i];
			//echo "row id-".$rowID."<br>";
			//update record
			if(!empty($rowID) || ($rowID!=""))
			{
				for($j=$k;$j<count($rowsData);$j++)
				{
					$result=mysql_query("select * from ".$table." where ID=".$rowID);
					if(mysql_num_rows($result)>0)
					{
						$date = DateTime::createFromFormat('m/d/Y', $rowsData[$j]);
						if ($date) 
						{
							$rowsData[$j]=$date -> format('Y-m-d');
							//echo $rowsData[$j];
						}	
						if (($table=='tblinventoryonhandinbound') && (($columnName[$col]=='`date_range`')||($columnName[$col]=='`sku`')||($columnName[$col]=='`skuname`'))) 
						{
							$str=$str;
						}
						else
						{
							$str=$str.$columnName[$col]."='".str_replace("'","''",$rowsData[$j])."',";
							//$colNum=$colNum+1;
						}
						
						$col=$col+1;
						
						if($col==$totalCols)
						{
							$query = $baseQueryUpdate.rtrim($str,',');
							$query= $query." where ID=".$rowID.";";
							//echo $query."<br>";
							$result=mysql_query($query);
							if ($result){
								$msg=true;
								}
							else{
								$msg= false;
								}  
							$query="";
							//$colNum=1;
							$col=1;
							$str="";
							$k=$j+2;
							break;
						}	
					}
				}
			}
			//insert record
		/*	else if($table="tblskumaster")
			{
				for($j=$k;$j<count($rowsData);$j++)
				{
					$str=$str.$columnName[$col].",";
					if($rowsData[$j]==""){$values=$values."NULL,";}
					else{$values=$values."'".str_replace("'","''",$rowsData[$j])."',";}
					$colNum=$colNum+1;
					$col=$col+1;
					if($col==$totalCols)
					{
						$query = $baseQueryInsert.rtrim($str,',').") values(".rtrim($values,',').");";
						//echo $query."<br>";
						$result=mysql_query($query);
						if ($result){
							echo true;
							}
						else{
							echo false;
							}  
						$query="";
						$values="";
						$colNum=1;
						$col=1;
						$str="";
						$k=$j+2;
						break;
					}	
				}
			} */
		}
		echo $msg;
	}
?>
