<?php session_start();
if((!isset($_SESSION['sku_user_email'])) || (!isset($_SESSION['sku_role'])) || (!$_SESSION['sku_logged']==1))
{
	header('Location:index.php');
}
else
{
	include('config.php');
}
class usercreation {
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
	if(!isset($_SESSION['sku_user_id']))
	{
		echo "<script type=\"text/javascript\">location.href = 'index.php';</script>";
	}
	$uc = new usercreation();
	$user_id=$_SESSION['sku_user_id'];
	$successUpdate="<span style='color: green;'><strong>Success!</strong>User update successfully..</span>";
	$successCreate="<span style='color: green;'><strong>Success!</strong>User created successfully..</span>";
	$failed="<span style='color: red;'><strong>Error!</strong>Sorry? There are problem to given access of These SKU's for this user..</span>";	
	
	if(isset($_POST['update']))
	{
		$successUpdate="<span style='color: green;'><strong>Success!</strong>User update successfully..</span>";
		$successCreate="<span style='color: green;'><strong>Success!</strong>User created successfully..</span>";
		$failed="<span style='color: red;'><strong>Error!</strong>Sorry? There are problem to given access of These SKU's for this user..</span>";
		
		$user_fname=str_replace("'","''",$_POST['txtFirstName']);
		$user_lname=str_replace("'","''",$_POST['txtLastName']);
		$user_pass=str_replace("'","''",$_POST['txtPassword']);
		//$user_lname=str_replace("'","''",$_REQUEST['txtLastName']);
		if(empty($user_id)|| $user_id==""){$user_id=0;}
		$query="select * from tblusers where user_id=".$user_id;
		$result = $uc->numRows($query);
		if($result>0)
		{
			$query="update tblusers set first_name='".$user_fname."',last_name='".$user_lname."',user_password='".$user_pass."' where user_id=".$user_id;
			//echo $query."<br>";
			$result = $uc->runQuery($query);
				if(!$result)
				{
					$_SESSION['create_massage']=$failed;
					//echo "<script type=\"text/javascript\">location.href = 'profile.php';</script>";
				}
				else
				{
					$_SESSION['create_massage']=$successUpdate;
					$_SESSION["sku_user_name"]=str_replace("''","'",ucfirst($user_fname)." ".ucfirst($user_lname));
				}
		}	
		else
		{
			$_SESSION['create_massage']=$failed;
		}
	}
	$select="select user_id,first_name,last_name, user_email,user_password from tblusers where user_id=".$user_id;
	$result = mysql_query($select);
	$data=mysql_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="css/Styles.css"/>
	<link rel="stylesheet" href="css/admindiv.css"/>
	<link rel="stylesheet" href="css/main.css"/>
	<link rel="stylesheet" href="css/bootstrap.min.css"/>
 <link rel="stylesheet" href="css/w3.css"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<style>
		.inputbox{
			width: 70%;
			padding: 10px;
			border: #F0F0F0 1px solid;
			border-radius: 4px;
			/*background-color: #FFF;*/
			border-color: initial;
			margin-top: 10px;
			}
		.lbl
		{
		    font-size: 14px;
			/*text-align:-webkit-right;*/
			width: 25%;
			font-family: sans-serif;
		}
		.sp
		{
			color:red;
			text-align: center;
		}
		.upper
		{
		background-color: #e9e9e9;
		padding-bottom: 20px;
		padding-top: 20px;
		}
		.usr
		{
			height: 30px;
			/* display: block; */
			font-size: 16px;
			font-weight: bold;
			text-transform: capitalize;
			text-align: left;
		}
		
		
	#initials_table {
    background: #f3f3f3;
    border: 1px solid #aaa;
    margin-bottom: 10px;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px;
	}

	#initials_table td {
		padding: 8px !important;
	}

	#initials_table a {
		border: 1px solid #aaa;
		background: #fff;
		padding: 4px 8px;
		-moz-border-radius: 5px;
		-webkit-border-radius: 5px;
		border-radius: 5px;
		background-image: url(./themes/svg_gradient.php?from=ffffff&to=cccccc);
	background-size: 100% 100%;
	background: -webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#cccccc));
	background: -webkit-linear-gradient(top, #ffffff, #cccccc);
	background: -moz-linear-gradient(top, #ffffff, #cccccc);
	background: -ms-linear-gradient(top, #ffffff, #cccccc);
	background: -o-linear-gradient(top, #ffffff, #cccccc);}
	</style>
	
	<script>
	$(document).ready(function() {
		$('#cboUsers').hide();
		document.oncontextmenu = document.body.oncontextmenu = function() {return false;}
		$('#profile').css("background-color","darkred");
		});
	function formValidate()
	{
		var fName=$('#txtFirstName').val().trim();
		var lName=$('#txtLastName').val().trim();
		var pass=$('#txtPassword').val().trim();
		if(fName=="")
		{
			document.getElementById("umsg").innerHTML="<div class='sp'>Please enter first name</div>";
			$('#txtFirstName').css({'border-color':'red'});
			$('#txtFirstName').focus();
			return false; 
		}
		else{$('#txtFirstName').css({'border-color':'initial'});}
		if(lName=="")
		{
			document.getElementById("umsg").innerHTML="<div class='sp'>Please enter last name</div>";
			$('#txtLastName').css({'border-color':'red'});
			$('#txtLastName').focus();
			return false;
		}
		else{$('#txtLastName').css({'border-color':'initial'});}
		
		if(pass=="")
		{
			document.getElementById("umsg").innerHTML="<div class='sp'>Please enter password</div>";
			$('#txtPassword').css({'border-color':'red'});
			$('#txtPassword').focus();
			return false;
		}
		else if(pass.length<=5 || pass.length>20)
		{
			document.getElementById("umsg").innerHTML="<div class='sp'>password length should be 6-20 charcter's</div>";
			$('#txtPassword').css({'border-color':'red'});
			$('#txtPassword').focus();
			return false;
		}
		else{$('#txtPassword').css({'border-color':'initial'});}
		if(pass!=$('#confirmpassword').val().trim())
		{
			document.getElementById("umsg").innerHTML="<div class='sp'>Password does't match</div>";
			$('#confirmpassword').css({'border-color':'red'});
			$('#confirmpassword').focus();
			return false;
		}
		else{$('#confirmpassword').css({'border-color':'initial'});}
		return true;
	}
	</script>
</head>
<body >
<div class="gcontainer" style="overflow-x: hidden;margin-right: 7px">
	<div style="margin-bottom: 10px;margin-right: -25px;"id="menuHead"><?php include("menus.php"); ?></div>

<div class="well">
		<Form method="post" onsubmit="return formValidate()" style="height: 85%">
		<div class="row">
			<div class="col-lg-2 col-sm-2 col-md-2 pull-right">
				<a href="index.php" style="color:white;margin-right:12px" class="btn btn-primary button-loading removepadding pull-right "><span class="fa fa-back"></span>Go Back</a>
			</div>
		</div>
		<div id="form" style="display:block;">
			<br>
			<center>
			<div id="umsg" style="height: 6px;text-align: center;"><?php if(isset($_SESSION['create_massage'])){echo $_SESSION['create_massage']; $_SESSION['create_massage']="";} ?></div>
			<table width="50%">
				<tr>
					<td class="lbl" >Email ID<span class="sp">&nbsp;*</span></td>
					<td >
						<input type="email" class="inputbox" name="txtEmailId"ID="txtEmailId" value="<?php echo $data['user_email'];?>" disabled autocomplete="off"></input>
					</td>
				</tr>
				<tr>
					<td class="lbl">First Name<span class="sp">&nbsp;*</span></td>
					<td >
						<input type="text" class="inputbox" ID="txtFirstName" name="txtFirstName" value="<?php echo $data['first_name'];?>"></input>
					</td>
				</tr>
				<tr>
					<td class="lbl">Last Name<span class="sp">&nbsp;*</span></td>
					<td >
						<input type="text" class="inputbox" ID="txtLastName" name="txtLastName" value="<?php echo $data['last_name'];?>"></input>
					</td>
				</tr>							
				<tr>
					<td class="lbl">Password<span class="sp">&nbsp;*</span></td>
					<td class="">
						<input type="Password" class="inputbox" ID="txtPassword" name="txtPassword" value="<?php echo $data['user_password'];?>" autocomplete="off"></input>
					</td>
				</tr>
				<tr>
					<td class="lbl">Confirm Password<span class="sp">&nbsp;*</span></td>
					<td class="">
						<input type="Password" class="inputbox" ID="confirmpassword" value="<?php echo $data['user_password'];?>" autocomplete="off"></input>
					</td>
				</tr>
				 <tr>
					<td>
						<input style="font-size: 13px;" type="hidden" class="inputbox" ID="txtUserId" name="txtUserId" value="<?php echo $data['user_id'];?>"></input>
					</td>
					 <td style="font-size: 13px;">
						<label><strong>Note:</strong> all <span class="sp">*</span> fields are mandatory.</label>
					 </td>
				 </tr>
			</table></center>
			<footer style="padding: 8px 8px; border-top: 1px solid #eee;background-color: #f5f5f5;" >
			   <center>
				<input type="submit" name="update" value="Save" class="btn btn-primary button-loading" style="margin-left: 0%;"></button>
			   </center>
			</footer>
		</div>
		</form>
		</div>
		</div>
	<footer>
     <?php include("copyright.html");?>  
	</footer>
</body>

</html>

