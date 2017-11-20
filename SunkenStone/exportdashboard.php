<?php session_start();
if((!isset($_SESSION['sku_user_email'])) || (!isset($_SESSION['sku_role'])) || (!$_SESSION['sku_logged']==1))
{
	header('Location:index.php');
}
else
{
$filename="dashboard.csv";
header( 'Content-Type: text/csv;charset=utf-8' );
header( 'Content-Disposition: attachment;filename='.$filename);
include("config.php");
}

$user_id=$_SESSION['sku_user_id'];
if($user_id==1)
{
	$where=" where 1=1";
}
else
{
$where=" where tbldashboard.`sku` in (select sku from tblusersku where user_id=".$user_id. ")";
}
 $query = "SELECT DATE_FORMAT(`date_range`,'%d/%m/%Y') as Date, `parent_asin` as '(Parent) ASIN', `child_asin` as '(Child) ASIN', `title` as Title,
 `skuname` as SKU, `sessions` as Sessions, `session_percentage` as 'Session Percentage', `page_views` as 'Page Views', `page_views_percentage` as 'Page Views Percentage', 
 `buy_box_percentage` as 'Buy Box Percentage', `units_ordered` as 'Units Ordered', `units_ordered_b2b` as 'Units Ordered - B2B', `unit_session_percentage` as 'Unit Session Percentage',
 `unit_session_percentage_b2b` 'Unit Session Percentage - B2B', `ordered_product_sales` as 'Ordered Product Sales', `ordered_product_sales_b2b` 'Ordered Product Sales - B2B', 
 `total_order_items` as 'Total Order Items', `total_order_items_b2b` as 'Total Order Items - B2B', `selling_price` as 'Selling Price' 
 FROM `tbldashboard` left join tblskumaster on `tbldashboard`.sku=tblskumaster.sku".$where." order by `date_range` desc";
 
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$fp = fopen('php://output', 'w');
	if($row)
	{
		fputcsv($fp, array_keys($row));
		// reset pointer back to beginning
		mysql_data_seek($result, 0);
	}
	
	while($row = mysql_fetch_assoc($result))
	{
		fputcsv($fp, $row);
	}
	fclose($fp);
	exit;   
?>