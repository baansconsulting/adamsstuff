<?php include("head.php");
	class usercreation {
	function getRecord($query) {
		$result = mysql_query($query);
		while($row=mysql_fetch_assoc($result)) {
			$resultset[] = $row;
		}		
		if(!empty($resultset))
			return $resultset;
	}
	function numRows($query) {
		$result  = mysql_query($query);
		$rowcount = mysql_num_rows($result);
		return $rowcount;	
	}
	
	function runQuery($query) {
		$result = mysql_query($query);
		if (!$result) {
			//die('Invalid query: ' . mysql_error());
			return false;
		} else {
			return $result;
		}
	}
}
	
	//$successUpdate="<span style='color: green;'><strong>Success!</strong>User update successfully..</span>";
	//$successCreate="<span style='color: green;'><strong>Success!</strong>User created successfully..</span>";
	//$failed="<span style='color: red;'><strong>Error!</strong>Sorry? There are problem to given access of These SKU's for this user..</span>";
	$successUpdate="<span style='color: green;'><strong>Success!</strong> updation successfull..</span>";
	$successCreate="<span style='color: green;'><strong>Success!</strong>User created successfully..</span>";
	$failed="<span style='color: red;'><strong>Error!</strong>Sorry? There are problem to given access of These SKU's for this user..</span>";

	if ((isset($_REQUEST['action'])) && ($_REQUEST['action']=="validate"))
	{
		$uc = new usercreation();
		$query="select * from tblusers where user_email='".str_replace("'","''",$_REQUEST['email'])."'";
		$result = $uc->numRows($query);
		if($result>0)
		{
			echo "<span style='color: red';>User already exists</span>";
		}
		else
		{
			echo true;
		}
	}
	else if(isset($_POST['update']))
	{
		$uc = new usercreation();
				
		$user_fname=str_replace("'","''",$_REQUEST['txtFirstName']);
		$user_lname=str_replace("'","''",$_REQUEST['txtLastName']);
		$user_id=str_replace("'","''",$_REQUEST['txtUserId']);
		$user_SKU=$_REQUEST['sSKU'];
		$status=$_REQUEST['cboStatus'];
		$user_email=str_replace("'","''",$_REQUEST['txtEmailId']);
		$user_password=str_replace("'","''",$_REQUEST['txtPassword']);
		$query="INSERT INTO `tblusers`(`first_name`, `last_name`, `user_email`, `user_password`, `status`, `user_role`) VALUES ('";
		$query=$query.$user_fname."','".$user_lname."','".$user_email."','".$user_password."',".$status.",0)";
		$result = $uc->runQuery($query);
		//echo $query."<br>";
		if($result)
		{
			$Q="select user_id from tblusers where user_email='".$user_email."'";
			//echo $Q."<br>";
			$result=$uc->runQuery($Q);
			$row = mysql_fetch_array($result);
			$query="insert into tbltemp(user_id,sku,from_date,to_date) values(".$row['user_id'].",'".reset($user_SKU)."','".date('Y-m-d', strtotime("-30 days"))."','". date("Y-m-d") ."')";
			//echo $query."<br>";
			$result=$uc->runQuery($query);
			$value="";
			$query="insert into tblusersku(user_id,sku)values";
			if (count($user_SKU) > 0) 
			{
				for ($i = 0; $i < count($user_SKU); $i++) 
				{
					$value=$value."(".$row['user_id'].",'".$user_SKU[$i]."'),";
				}
			
			}
			if($value!="")
			{
				$query=$query.$value;
				$query=rtrim($query,",");
				//echo $query."<br>";
				$result=$uc->runQuery($query);
				if(!$result)
				{
					$_SESSION['create_massage']=$failed;
					echo "<script type=\"text/javascript\">location.href = 'editusers.php';</script>";
				}
				else
				{
					$_SESSION['create_massage']=$successCreate; 
					echo "<script type=\"text/javascript\">location.href = 'editusers.php';</script>";
				}
			}
			else
			{
				$_SESSION['create_massage']=$successCreate;
				echo "<script type=\"text/javascript\">location.href = 'editusers.php';</script>";
			}
		}
		else
		{
			$_SESSION['create_massage']="<span style='color: red;'><strong>Error!</strong>Sorry? There are problem to created this user..</span>";
			echo "<script type=\"text/javascript\">location.href = 'editusers.php';</script>";
		}
	}
	
	else if ((isset($_REQUEST['q'])) && ($_REQUEST['q']=="update_userDetails"))
	{
		$uc = new usercreation();
		$user_fname=str_replace("'","''",$_REQUEST['fname']);
		$user_lname=str_replace("'","''",$_REQUEST['lname']);
		$user_email=str_replace("'","''",$_REQUEST['email']);
		$status=$_REQUEST['status'];
		
		$query="select * from tblusers where user_email='".$user_email."'";
		$result = $uc->numRows($query);
		if($result>0)
		{
			$query="update tblusers set first_name='".$user_fname."',last_name='".$user_lname."',status=".$status." where user_email='".$user_email."'";
			$result = $uc->runQuery($query);
			if($result)
			{
				echo $successUpdate;
			}
			else
			{
				echo "<span style='color: red;'><strong>Error!</strong>Sorry? There are problem to updation for this user..</span>";
			}
		}
	}
	
	else if ((isset($_REQUEST['q'])) && ($_REQUEST['q']=="update_userPass"))
	{
		$uc = new usercreation();
		$user_email=str_replace("'","''",$_REQUEST['email']);
		$user_password=str_replace("'","''",$_REQUEST['uPass']);
		$query="select * from tblusers where user_email='".$user_email."'";
		$result = $uc->numRows($query);
		if($result>0)
		{
			$query="update tblusers set user_password='".$user_password."' where user_email='".$user_email."'";
			$result = $uc->runQuery($query);
			if($result)
			{
				echo $successUpdate;
			}
			else
			{
				echo "<span style='color: red;'><strong>Error!</strong>Sorry? There are problem to updation for this user..</span>";
			}
		}
	}
	else if(isset($_POST['update_userskus']))
	{
		$uc = new usercreation();
		$user_SKU=$_POST['sSKU1'];
		$user_email=str_replace("'","''",$_POST['txtEmailId3']);
		$Q="select user_id from tblusers where user_email='".$user_email."'";
		$result=$uc->runQuery($Q);
		$row = mysql_fetch_array($result);
		$user_id=$row['user_id'];
		$query="DELETE FROM `tblusersku` WHERE `user_id`=".$user_id;
		$result = $uc->runQuery($query);
		$value="";
		$query="insert into tblusersku(user_id,sku)values";
		if (count($user_SKU) > 0) 
		{
			for ($i = 0; $i < count($user_SKU); $i++) 
			{
				$value=$value."(".$user_id.",'".$user_SKU[$i]."'),";
			}
		
		}
		if($value!="")
		{
			$query=$query.$value;
			$query=rtrim($query,",");
			$result=$uc->runQuery($query);
			if(!$result)
			{
				$_SESSION['create_massage']=$failed;
				echo "<script type=\"text/javascript\">location.href = 'editusers.php?q=".base64_encode($user_email)."';</script>";
			}
			else
			{
				$_SESSION['create_massage']=$successUpdate;
				echo "<script type=\"text/javascript\">location.href = 'editusers.php?q=".base64_encode($user_email)."';</script>";
			}
		}
		else
		{
			$_SESSION['create_massage']=$successUpdate;
			echo "<script type=\"text/javascript\">location.href = 'editusers.php?q=".base64_encode($user_email)."';</script>";
		}
	}
	//echo $query;
?>