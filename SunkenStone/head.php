<?php session_start();
if((!isset($_SESSION['sku_user_email'])) || (!isset($_SESSION['sku_role'])) || (!$_SESSION['sku_logged']==1)||(!$_SESSION['sku_role']==1))
{
	header('Location:index.php');
}
else
{
	include('config.php');
}
?>