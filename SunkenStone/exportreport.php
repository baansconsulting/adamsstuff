<?php session_start();
if((!isset($_SESSION['sku_user_email'])) || (!isset($_SESSION['sku_role'])) || (!$_SESSION['sku_logged']==1))
{
	header('Location:index.php');
}
else 
{
	if ((isset($_REQUEST['filename'])) && (isset($_REQUEST['pro'])))
	{
		$filename = $_REQUEST['filename'];
		header( 'Content-Type: text/csv;charset=utf-8' );
		header( 'Content-Disposition: attachment;filename='.$filename);
		include("config.php");
		$pro="CALL `" .$_REQUEST['pro']."`()";
		$result = mysql_query($pro);
		$i=0;
		while ($i < mysql_num_fields($result)) {
			$meta = mysql_fetch_field($result, $i);
			$header[$i] = $meta->name;
			$i++;
		} 
		$fp = fopen('php://output', 'w');
			fputcsv($fp, $header);
		while($row = mysql_fetch_row($result)) {
			fputcsv($fp, $row);
		}
		fclose($fp);
		exit; 
	}
}
?>