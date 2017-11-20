<style>
	.menu {
    border: 1px solid transparent;
    background: chocolate;
    text-decoration: none;
    color: #fff;
    font-weight: 600;
    padding: 3px 10px;
    font-size: 13px;
    font-family: initial;
	text-transform: uppercase;
	cursor:pointer;
	}
	#headers a:hover, #headers a:active 
	{
		/*background-color: darkred !important;*/
	}
	
	.menu:hover {
    background-color: darkred; 
    color: white; 
	}
	.cboUser
	{
		width: 25%;
		border: 1px solid #DDDDDD;
		border-radius: 2px;
		background: #F8F8F8;
		cursor: pointer;
		height:100%;
	}
	li { cursor: pointer; }

@media (max-width: 768px) {    
   .profileclass{
	 display:none!important
   }
}
@media (min-width: 768px) and (max-width: 991px) {
   .profileclass{
	 display:none!important
   }
}
@media (min-width: 992px) and (max-width: 1199px) {
.profileclass{
	 display:none!important
   }
}
</style>
<script>
function logout()
{
	winClose();
	document.location.href='logout.php'
}
jQuery(document).ready(function(){
        jQuery('#btntoggle').on('click', function(event) {        
             jQuery('#myNavbar').toggle('hide');
        });
    });
</script>
<!--<link rel="stylesheet" href="css/bootstrap.min.css"/>
 <link rel="stylesheet" href="css/w3.css"/>-->
     <script src="js/bootstrap.min.js"></script>
<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" id="btntoggle"><!-- data-toggle="collapse" data-target="#myNavbar">-->
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span> 
				</button>
				<a class="navbar-brand" href="#">
				<?php
				if(isset($_SESSION['sku_logged'])){
				//if(isset($_REQUEST['q'])){$temp_user_id=$_REQUEST['q'];}else{$temp_user_id=$_SESSION['sku_user_id'];}
					$ClientUserID=$_SESSION['sku_user_id'];
					$userroleid=$_SESSION['sku_role'];
					//if(isset($_REQUEST['q'])){$temp_user_id=$_REQUEST['q'];}else{$temp_user_id=$_SESSION['sku_user_id'];}
					if($userroleid==1)
					{
					$query="SELECT `user_id`, concat(`first_name`,' ',`last_name`) as name FROM `tblusers` WHERE `status`=1 order by name";
					}
					else
					{
						$query="SELECT `user_id`, concat(`first_name`,' ',`last_name`) as name FROM `tblusers` WHERE `status`=1 and user_id=" . $ClientUserID . " order by name";
					}
				//$query="SELECT `user_id`, concat(`first_name`,' ',`last_name`) as name FROM `tblusers` WHERE `status`=1 order by name";
				$result=mysql_query($query) or die(mysql_error());

				echo "<div style='text-align:left; float: left'>Welcome&nbsp;&nbsp;<span style=\"font-family: monospace;font-size: larger;color: #800000;\">".strtoupper($_SESSION["sku_user_name"])."</span></b></div>";
				echo "<div style='float:left;text-align:right;'>";
				if($_SESSION['sku_role']==1)
				{
					$user_id=0;

					if(isset($_SESSION["temp_sku_user_id"]))
					{
					$user_id=$_SESSION["temp_sku_user_id"];
					}
					else
					{
					//$user_id=$_SESSION['sku_user_id'];
					}
				}
				}
				?>

				</a>
			</div>
				</div>
			 <div class="collapse navbar-collapse" id="myNavbar">
				<ul class="nav navbar-nav">
				
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a>
						<select name="cboUsers" id="cboUsers" onchange="selectedSKU(this.value);" style="border-radius: 2px;">
						<option value="0" disabled selected='selected'><u>Select User</u></option>
						<?php 
						while($row=mysql_fetch_array($result))
						{
						?>
						<option value="<?php echo $row['user_id']; ?>"<?php if($row['user_id']==$user_id) echo 'selected="selected"';?>><?php echo ucwords($row['name']);?></option>
						<?php
						}
						?>
						</select>
					</a></li>
					<!--<input type='button' id="home" value='Home' class="menu menuHover" onclick="document.location.href='index.php';"/>-->
					<li><a href="https://sunkenstone.com">Home</a></li>
					<li class="profileclass" id="profileli"><a  onclick="document.location.href='profile.php';"><span class="fa fa-user"></span> Profile</a></li>
					<li class="profileclass"><a onclick="document.location.href='editusers.php';"><span class="fa fa-users"></span> Users</a></li>
					<li><a  onclick="document.location.href='login.php';"><span class="fa fa-sign-out"></span> LogOut</a></li>

				</ul>
			 </div>
	
	</nav>