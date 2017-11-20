<?php session_start();

$user_msg ="";

$not_a_user="";

if(isset($_SESSION['sku_logged']) && $_SESSION['sku_logged'] ==1 && isset($_SESSION['sku_user_email']))
{

//echo '<center><b>you are now logout  from your user section. you will be redirected to login page shortly IF not then follow the below link <br/><a href="login.php">click here</a></b></center>';
session_destroy();
header('refresh:0; URL=login.php');
}
else
{
    header('refresh: 0; URL:login.php');
    echo '<b>you are not authorize to view this page. you will be redirected to login page shortly IF not then follow the below link <br/> <a href="login.php">click here</a></b>';
} ?>




