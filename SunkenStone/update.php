<?php
	session_start();
	if(! $_SESSION['sku_logged']==1)
	{
		header('Location:login.php');
	}
	IF((!isset($_REQUEST['filename'])) && (!isset($_SESSION['sku_role'])) && (!$_SESSION['sku_role']==1) && (!isset($_REQUEST['sku'])) && (!isset($_REQUEST['fromdate'])) && (!isset($_REQUEST['usrdlt'])))
	{
		header('Location:login.php');
		//echo "<script type=\"text/javascript\">location.href = 'login.php';</script>";
	}
	include('config.php');
	IF((isset($_REQUEST['filename'])) && (isset($_SESSION['sku_role'])) && ($_SESSION['sku_role']==1))
	{
		$filename=$_REQUEST['filename'];
		$table=$_REQUEST['table'];
		if($table=="tblinventory")
		{
			$R=mysql_query("select distinct date_range from tblinventory where file_name='".$filename."'");
			$nums_row=mysql_num_rows($R); 
			if($nums_row>0)
			{
				while($row = mysql_fetch_array($R))
				{
					$result=mysql_query("delete from tblinventoryonhandinbound WHERE `date_range`='".$row['date_range']."'");
				}
			}
			$result=mysql_query("delete from tblinventory where file_name='".$filename."'");
			if($result)
			{
				echo true;
			}
			else
			{
				echo false;
			}
		}
		else
		{
			$result=mysql_query("delete from ".$table." where file_name='".$filename."'");
			if($result)
			{
				echo true;
			}
			else
			{
				echo false;
			}
		}
	}
	else if(isset($_REQUEST["usrdlt"])&& $_SESSION['sku_role']==1)
	{
		$usersid=rtrim($_REQUEST["usrdlt"],",");
		$result=mysql_query("delete from tblusers where user_id in(".$usersid.");");
		if($result)
		{
			$result=mysql_query("delete from tblusersku where user_id in(".$usersid.");");
			if($result)
			{
				echo true;
				
			}
			else
			{
				echo false;
			}
		}
		else
		{
			echo false;
		}
	}
	else if(isset($_REQUEST['sku']) && isset($_REQUEST['fromdate']) && isset($_REQUEST['todate']))
	{
		if(((isset($_SESSION["temp_sku_user_id"])) && isset($_SESSION['sku_user_id'])) || (isset($_SESSION['sku_user_id'])))
		{
			$user_id=$_SESSION['sku_user_id'];
		}
		else if (isset($_SESSION["temp_sku_user_id"]))
		{
			$user_id=$_SESSION["temp_sku_user_id"];
		}
		else{
			$user_id=0;
		}
		$query="update tbltemp set sku='".$_REQUEST['sku']."'";
		IF($_REQUEST['fromdate']!="")
		{
			$fdate=date("Y-m-d", strtotime($_REQUEST['fromdate']));
			//echo $fdate;
			$query=$query.",from_date='".$fdate."'";
		}
		else
		{
			$query=$query.",from_date='0000-00-00'";
		}
		IF($_REQUEST['todate']!="")
		{
			$tdate=date("Y-m-d", strtotime($_REQUEST['todate']));
			$query=$query.",to_date='".$tdate."'";
		}
		else
			{
				$query=$query.",to_date='0000-00-00'";
			}	
		//$query = rtrim($query,',');
		$query.=" WHERE user_id=".$user_id;
		//echo $query;
		$result=mysql_query($query);
		if($result)
		{
			echo "true";
			
		}
		else
		{
			echo "false";
		}
	}
	else if(isset($_REQUEST['setTempID']))
	{
		if($_REQUEST['setTempID']==$_SESSION['sku_user_id'])
		{
			unset($_SESSION["temp_sku_user_id"]);
		}
		else
		{
			$_SESSION["temp_sku_user_id"]=$_REQUEST['setTempID'];
		}
	}
?>