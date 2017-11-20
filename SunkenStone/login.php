<?php
?>
<html>
<head>
<link href="css/login.css" rel="stylesheet">
<script>
var status="";
function Validate()
{
	var tt;
	status="<span style=\"color:red;\">";
	document.getElementById('message').style.border="1px solid red";
	if (document.getElementById("user").value.trim()=="")
	{
		document.getElementById("message").innerHTML=status+"Please Enter User Name !</span>";
		document.getElementById("user").focus();
		return false;
	}
	if (document.getElementById("pw").value.trim()=="")
	{
		document.getElementById("pw").focus();
		document.getElementById("message").innerHTML=status+"Please Enter Password !</span>";
		return false;	
	}
	else
	{	
		document.getElementById('message').style.border="initial";
		document.getElementById('message').style.background="initial";
		document.getElementById("message").innerHTML="<img src='images/loader.gif' style='margin-left: 100px;'>";
		if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
           var xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
           var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				var rs=xmlhttp.responseText;
				if(rs==1)
				{	
					rs="";
					window.location.href = 'index.php';
				}
				else
				{
				document.getElementById('message').style.border="1px solid red";
				document.getElementById("message").innerHTML=status+rs+"</span>";
				document.getElementById('message').style.background="antiquewhite";
				}
			}
		}
		xmlhttp.open("POST","loginback.php?email="+document.getElementById("user").value+"&pw="+document.getElementById("pw").value,true);
		xmlhttp.send();
		return false;
	}
}

</script>
<style>

	.home {
    border: 1px solid transparent;
    background: #e55700;
    text-decoration: none;
    color: #fff;
    font-weight: 600;
    padding: 10px 23px;
    font-size: 13px;
    font-family: initial;
	cursor:pointer;
	}
	.headercss
	{
		background-color: #1e1e1e;
		border-color: #00335e;
	}
	
    overflow-x: hidden;
	
</style>
</head>
<title>SKU Login</title>
<link rel="stylesheet" href="css/bootstrap.min.css"/>
<link rel="stylesheet" href="css/w3.css"/>
<script src="js/bootstrap.min.js"></script>
<body style="background-color:white;">
	<section id="header">
		<header  class="header ">
			<div class="container">
				<div class"inner-header" >
					<div class="row">
						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-8">
							<img src="images/SunkenStone_Logo75px.png" class="img-responsive">
						</div>
						<div style="margin-top:38px;" class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<a href="https://sunkenstone.com" class="home">HOME</a>
						</div>
					</div>			
				</div> 
			</div>
		</header>
	</section>
	<hr style="border-top: 1px solid #378bff;">
	<section id="wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="row">
					<div class="col-lg-6 hidden-md hidden-sm hidden-xs">
						<img src="images/Office-copy.png" style="height: 70%;width: 100%;" class="img-responsive">
					</div>
					
					<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
						<div class="panel panel-primary">
							<div class="panel-heading">Login</div>
							<div class="panel-body">
								<form autocomplete="off">
									<div class="row">
									<div id="message" class="msg"></div>
										<div class="form-group col-lg-12 col-sm-12 col-md-12 col-xs-12">
											<label for="username"><span class="text-danger" >*</span>Username:</label>
											<div class="input-group">
											<input type="text" id="user" class="form-control" name="user"class="styled-text" placeholder="example@gmail.com" >
												<span class="input-group-btn">
														<label class="btn btn-primary" style="padding-top: 25px"><span class="fa fa-user"></span></label>
													</span>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-lg-12 col-sm-12 col-md-12 col-xs-12">
											<label for="username"><span class="text-danger" >*</span>Password:</label>
											<div class="input-group">
												<input type="password" id="pw"  class="form-control" name="pw" class="styled-text" placeholder="**********">
												<span class="input-group-btn">
														<label class="btn btn-primary" style="padding-top: 25px"><span class="fa fa-user"></span></label>
													</span>
											</div>
										</div>
									</div>
									<div class="row ">
										<div class="form-group col-lg-12 col-sm-12 col-md-12 ">
											<button  onClick="return Validate();"class="btn btn-primary">Login</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					</div>
				</div>
			</div>
		</div>
	</section>


<?php include("copyright.html");?>
</body>
</html>