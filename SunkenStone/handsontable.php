<?php include("head.php");?>
<style>
.welc
{
	font-size: 35px;
    text-align: -webkit-center;
    background-color: #f1f1f1;
    font-family: -webkit-body;
}
</style>
<div class="welc" style="font-size: 35px;" width="100%">
	Welcome To SKU Analysis
</div>	
<html>
<head>
<style>
.top
{
min-height: 85px;
background-color: #F5F5F5;
border: 1px solid #E3E3E3;
color:#676767;
}

.topTitle
{
width: 90%;
font-size: 20px;
text-align: center;
padding-left: 5%;
padding-right: 5%;
margin-bottom: 1%;
color: #676767;
padding-top:1%;
background-color: #F5F5F5;
}

.filter-lable
{
width:100%;
/*min-height:54px;*/
color: #800000;
font-weight:bold;
/*float:right;*/
line-height: 1.5;
border: 1px solid #E3E3E3;
display:block
}

 .styled-text {
   width:13%;
   /*overflow: hidden;*/
   background: #F8F8F8;
   border: 1px solid #DDDDDD;
   height: 30px;
   /*border-radius:2px;*/
   /*margin-top:2px;*/
 }
</style>
<link rel="stylesheet" href="css/admindiv.css"/>
</head>
<title>SKU Analysis</title>
<body>
<?php
$sql="";
//include("config.php");
if((!isset($_REQUEST['page'])) || (!isset($_REQUEST['file'])))
{
	echo "<script type=\"text/javascript\">location.href = 'index.php';</script>";
}
if($_REQUEST['page']=="Skumaster")
{
$sql = "SELECT `ID`, sku,skuname,`UnitCost`, `UnitPrice`, `OrderLeadTime`,`OrderLeadTime_China`, round((`StockoutProbability`),2) as StockoutProbability, `Hide_On_Inventory`  FROM `tblskumaster` order by skuname";
$_SESSION['table']="tblskumaster";
}
else
{
	if($_REQUEST['page']=="Dashboard")
	{
		if($_REQUEST['file']=="Dashboard")
		{
			$where=" where 1=1";
			//$where = "where file_name='BusinessReport-8-03-16'";
		}
		else
		{
			$page=$_REQUEST['file'];
			$where=" where file_name='".$page."'";
			//$where=" where date_range='2016-08-12'";
		}
		$sql = "SELECT `ID`, DATE_FORMAT(date_range, '%m/%d/%Y') as `date_range`, `parent_asin`, `child_asin`, `sku`, `sessions`, round((`session_percentage`/100),4) as session_percentage, `page_views`, round((`page_views_percentage`/100),4) as `page_views_percentage`, round((`buy_box_percentage`/100),4) as `buy_box_percentage`, `units_ordered`, `units_ordered_b2b`, round((`unit_session_percentage`/100),4) as `unit_session_percentage`, round((`unit_session_percentage_b2b`/100),4) as `unit_session_percentage_b2b`, `ordered_product_sales`, `ordered_product_sales_b2b`, `total_order_items`, `total_order_items_b2b`,`additional` FROM `tbldashboard`".$where;
		$_SESSION['table']="tbldashboard";
	}
	else if($_REQUEST['page']=="Inventory")
	{
		if($_REQUEST['file']=="Inventory")
		{
			$where=" where 1=1";
		}
		else
		{
			$page=$_REQUEST['file'];
			$where=" where file_name='".$page."'";
			//$where=" where date_range='2016-08-12'";
		}
	$sql = "SELECT `ID`, DATE_FORMAT(date_range, '%m/%d/%Y') as `date_range`, `sku`, `fnsku`, `asin`,`product_name`, `condition`, `your_price`, `mfn_listing_exists`, `mfn_fulfillable_quantity`, `afn_listing_exists`, `afn_warehouse_quantity`, `afn_fulfillable_quantity`, `afn_unsellable_quantity`, `afn_reserved_quantity`, `afn_total_quantity`, `per_unit_volume`, `afn_inbound_working_quantity`, `afn_inbound_shipped_quantity`, `afn_inbound_receiving_quantity` FROM `tblinventory`".$where;
	$_SESSION['table']="tblinventory"; 
	}
	else if($_REQUEST['page']=="InventoryAddi")
	{
		if($_REQUEST['file']=="InventoryAddi")
		{
			$where=" and 1=1";
		}
		else
		{	
			$date = $_REQUEST['file'];
			$where=" and date_format(date_range,'%m-%d-%Y')='".$date."'";
			//$where=" where date_range='2016-08-12'";
		}
	//$sql="SELECT `ID`, DATE_FORMAT(date_range, '%m/%d/%Y') as `date_range`,`sku`, `onhand`, `inbound`, `Additional` FROM `tblinventoryonhandinbound`".$where." order by sku asc";
	$sql="SELECT t.`ID`, DATE_FORMAT(date_range, '%m/%d/%Y') as `date_range`,t.`sku`,`skuname`, `onhand`, `inbound`, `Additional` FROM `tblinventoryonhandinbound` as t , `tblskumaster` as m WHERE m.`sku`=t.`sku` ".$where." order by sku asc";
	$_SESSION['table']="tblinventoryonhandinbound"; 
	}
}
//echo $sql;
$_SESSION['Query']=$sql;
$result=mysql_query($sql);
$nums_row=mysql_num_rows($result); 
if($nums_row==0){echo "No Data avaialable";}
?>
<!--script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.js" type="text/javascript"></script-->

<script src="https://docs.handsontable.com/pro/1.0.0-beta3/bower_components/handsontable-pro/dist/handsontable.full.min.js"></script>
<script src="http://docs.handsontable.com/0.16.1/scripts/jquery.min.js"></script> 

<link rel="stylesheet" href="http://docs.handsontable.com/0.16.1/scripts/removeRow-demo/handsontable.removeRow.css">
<link type="text/css" rel="stylesheet" href="https://docs.handsontable.com/pro/1.0.0-beta3/bower_components/handsontable-pro/dist/handsontable.full.min.css">

<script src="js/handsontable.removeRow.js"></script>
<div>
	<div id="DataTable" class="hot htRemoveRow handsontable htRowHeaders htColumnHeaders"></div>
	<div id="myDiv"></div>
	<div id="myDiv2"></div>
  </div>
<script>
	$(document).ready(function() {
		document.oncontextmenu = document.body.oncontextmenu = function() {return false;}
	}); 

var rawDataObj=[];
var DataObj1 = [];
var FullDataObj=[];
var ColHeaders=[];
var uniqueID=[];
var blankRow=0;
var blankCol=0;
<?php
$maxCol=20;
$iTempCol=0;
$i = 0;
while ($i < mysql_num_fields($result)) {
    $meta = mysql_fetch_field($result, $i);
	if($i==0){?>DataObj1="";<?php }else{?>DataObj1="<?php echo str_replace("_"," ",$meta->name);?>";<?php } ?>
	rawDataObj=rawDataObj.concat(DataObj1);
	<?php
    $i++;
	if($i==$maxCol)
	{
		break;
	}
} ?>
ColHeaders=ColHeaders.concat(rawDataObj);
rawDataObj=[];	
<?php
$maxRow=0;
while ($row = mysql_fetch_array($result,MYSQLI_NUM)) 
{	$maxRow=$maxRow+1;
	$iTempCol=0;
	foreach($row as $r) 
	{
		$value=str_replace('"','',$r);
	?>
		DataObj1=["<?php echo $value;?>"];
		rawDataObj=rawDataObj.concat(DataObj1);
<?php 
		$iTempCol=$iTempCol+1;
		if($iTempCol==$maxCol)
		{
			break;
		}
	} ?>
		FullDataObj=FullDataObj.concat([rawDataObj]);
		rawDataObj=[];
<?php 
} 
?>
var colProperties=[];
var colPro=[];
var maxRow=<?php echo $maxRow;?>;
var maxCol=<?php echo $iTempCol;?>;
<?php if($_REQUEST['page']=="Dashboard")
{ ?>
for(var i=0; i<=maxCol-1;i++)
{
	if (i==0)
	{
		colPro=[{type: 'numeric',readOnly: true}];
	}
	else if(i==1)
	{
		colPro=[{type:'date',readOnly: true}];
	}
	else if((i==5)||(i==7)||(i==10)||(i==11)||(i==16 )||(i==17) ||(i==18))
	{
		colPro=[{type:'numeric',format: '0,0'}];
	}
	else if((i==6)||(i==8)||(i==12)||(i==13))
	{
		colPro=[{type:'numeric',format:'0.00%'}];
		//colPro=[{type:'numeric',format:'0.00'}];
	}
	else if((i==9))
	{
		colPro=[{type:'numeric',format:'0%'}];
		//colPro=[{type:'numeric',format:'0.00'}];
	}
	else if((i==14)||(i==15)||(i==16))
	{
		//colPro=[{type:'numeric',format:'$0,0'}];
		colPro=[{type:'numeric',format:'$0.00'}];
	}
	else{colPro=[{}];}
	colProperties=colProperties.concat(colPro);
}
<?php }
else if($_REQUEST['page']=="Skumaster")
{ ?>	
	for(var i=0; i<=maxCol-1;i++)
	{
		if (i==0)
		{
			colPro=[{type: 'numeric',readOnly: true}];
		}
		else if (i==1)
		{
			colPro=[{readOnly: true}];
		}
		else if((i==3)||(i==4))
		{
			colPro=[{type:'numeric',format:'0.00'}];
		}
		else if((i==5)||(i==6))
		{
			colPro=[{type:'numeric'}];
		}
		else if(i==7)
		{
			colPro=[{type:'numeric',format:'0%'}];
		}
		else if(i==8)
		{
			colPro=[{type:'checkbox',checkedTemplate: 1,uncheckedTemplate:0}];
		}
		else{colPro=[{}];}
		colProperties=colProperties.concat(colPro);
	}
<?php } 
else if($_REQUEST['page']=="Inventory")
{?>
	for(var i=0; i<=maxCol-1;i++)
	{
		if (i==0)
		{
			colPro=[{type: 'numeric',readOnly: true}];
		}
		else if(i==1)
		{
			colPro=[{type:'date',readOnly: true}];
		}
		else if((i==7))
		{
			colPro=[{type:'numeric',format:'0.00'}];
		}
		else if ((i==9)||(i==11)||(i==12)||(i==13)||(i==14)||(i==15)||(i==16)||(i==17)||(i==18)||(i==19)||(i==20))
		{
			colPro=[{type: 'numeric'}];
		}
		else{colPro=[{}];}
		colProperties=colProperties.concat(colPro);
		// colPro=[{type:'numeric',format: '0,0'}];
	}
<?php }
else if($_REQUEST['page']=="InventoryAddi")
{?>
	for(var i=0; i<=maxCol-1;i++)
	{
		if (i==0)
		{
			colPro=[{type: 'numeric',readOnly: true}];
		}
		else if(i==1)
		{
			colPro=[{type:'date',readOnly: true}];
		}
		else if ((i==2)||(i==3))
		{
			colPro=[{readOnly: true}];
		}
		else if ((i==4)||(i==5)||(i==6))
		{
			colPro=[{type: 'numeric'}];
		}
		else{colPro=[{}];}
		colProperties=colProperties.concat(colPro);
		// colPro=[{type:'numeric',format: '0,0'}];
	}
<?php } ?>
document.addEventListener("DOMContentLoaded", function() {  
  var dataObj = FullDataObj;	 
  var hot = new Handsontable(DataTable, {
    //data: dataObj,
	colHeaders: ColHeaders,
    //removeRowPlugin: true,	 
	minSpareRows: blankRow,
	maxCols:maxCol,
	allowInsertColumn:false,
	allowInsertRow:false,
    manualColumnResize: true,
    manualRowResize: true,
	autoColumnSize: true,
	filters: true,
	columnSorting: true,
    sortIndicator: true,
	height: 560,
	columns: colProperties,
    dropdownMenu: ['filter_by_condition', 'filter_action_bar'],
	cells: function (row, col, prop) {
      var cellProperties = {};
      if (col === 0) {
        cellProperties.renderer = firstColRenderer; // uses function directly
      }
      return cellProperties;
    },	  
	afterChange: function(changes,source){
	if(source==="loadData"){
	return;
	}
	else{
		var rowIndex=[];
		var rowNumber=[];
		var rowNumbers=[];
		for(var i=0;i<changes.length;i++)
		{
			rowIndex=changes[i];
			rowNumber=rowIndex[0];
			rowNumbers=rowNumbers.concat(rowNumber);
			rowNumber=[];
		}
		var rows = rowNumbers;
		var rowNumbers = [];
		$.each(rows, function(i, element){
			if($.inArray(element, rowNumbers) === -1) rowNumbers.push(element);
		});
		updateData(hot,rowNumbers);
	}
	},
  });
  hot.loadData(dataObj);
function firstColRenderer(instance, td, row, col, prop, value, cellProperties) 
{
	Handsontable.renderers.TextRenderer.apply(this, arguments);
	//td.style.fontWeight = 'bold';
	td.style.color = '#eeeeee';
	td.style.background = '#eeeeee';
	td.style.textAlign = 'center';
}
  function bindDumpButton() {
      if (typeof Handsontable === "undefined") {
        return;
      }
  
      Handsontable.Dom.addEvent(document.body, 'click', function (e) {
  
        var element = e.target || e.srcElement;
  
        if (element.nodeName == "BUTTON" && element.name == 'dump') {
          var name = element.getAttribute('data-dump');
          var instance = element.getAttribute('data-instance');
          var hot = window[instance];
          console.log('data of ' + name, hot.getData());
        }
      });
    }
  bindDumpButton();
});

function updateData(hot,rowNumbers)
{
	var rowDataTemp=[];
	var FullRowData=[];
	//var totalCols=hot.countCols();
	var rowsID=[];
	
	
	var pageReload=false;
	var newFullRowData;
	fade();
	for(var i=0;i<rowNumbers.length;i++)
	{
		if(hot.isEmptyRow(rowNumbers[i]))
		{}
		else
		{
			rowDataTemp=hot.getDataAtRow(rowNumbers[i]);
			FullRowData=FullRowData.concat([rowDataTemp]);
			
			rowDataTemp=[];
			rowsID=rowsID.concat(hot.getDataAtCell(rowNumbers[i],0));
			if (hot.getDataAtCell(rowNumbers[i],0)==null){pageReload=true;}
		}
	}
	var httpReq;
    if (window.XMLHttpRequest)
    {
	//If the browser if IE7+[or]Firefox[or]Chrome[or]Opera[or]Safari
      httpReq=new XMLHttpRequest();
    }
   else
    {
	//If browser is IE6, IE5
      httpReq=new ActiveXObject("Microsoft.XMLHTTP");
    }

	httpReq.onreadystatechange=function()
	{
	  if (httpReq.readyState==4)
	  {
		 var rs = document.getElementById("myDiv");
		if(httpReq.responseText==1)
		{
			document.getElementById("myDiv2").innerHTML="";
			document.getElementById("upd").innerHTML="<strong>Success!</strong> record update successfully..";
			$("#success").trigger('click');
			rs.innerHTML=httpReq.responseText;
		}
		else
		{
			document.getElementById("myDiv2").innerHTML="";
			document.getElementById("er").innerHTML="<strong>Error!</strong> record update failed..";
			$("#error").trigger('click');
			rs.innerHTML=httpReq.responseText;
		}
		if(pageReload==true){location.reload();}
	   }
	}

if (rowsID.length>0)
{
	//16 May
	for(var i=0;i<FullRowData.length;i++)
	{
		var newField=FullRowData[i];
		for(var count=0;count<newField.length;count++)
		{   
		    var isstring = (typeof newField[count] === "string");
			if (isstring)
			{
				var index=newField[count].indexOf('&');
				if(index>=0)
				{
					newField[count]=newField[count].substr(0,index)+"%26"+newField[count].substr(index+1);
				}
			}
		}
	}
	var main="?rowsID="+rowsID+"&rowsData="+FullRowData
	var data=main+"&Task=InsertUpdate";
	httpReq.open("POST","updatedeleteHandson.php"+data,true);
	httpReq.send();
	//return false;
}
	 
}
function deleteHandsRow(rowID)
{
var httpReq;
var rowArr=(FullDataObj[rowID]);
var rID;
//fade();
	for(var i=0;i<rowArr.length;i++)
	{
		if (i==0){
			rID=rowArr[i];
		break;
		}
	}
	if (window.XMLHttpRequest)
    {
	//If the browser if IE7+[or]Firefox[or]Chrome[or]Opera[or]Safari
      httpReq=new XMLHttpRequest();
    }
   else
    {
	//If browser is IE6, IE5
      httpReq=new ActiveXObject("Microsoft.XMLHTTP");
    }
	httpReq.onreadystatechange=function()
	{
	  if (httpReq.readyState==4)
	  {
		if(httpReq.responseText==1)
		{
			document.getElementById("myDiv2").innerHTML="";
			document.getElementById("upd").innerHTML="<strong>Success!</strong> record delete successfully..";
			$("#success").trigger('click');
		}
		else
		{
			document.getElementById("myDiv2").innerHTML="";
			document.getElementById("er").innerHTML="<strong>Error!</strong> record can't delete..";
			$("#error").trigger('click');
		}
	   }
	}
var rows="?rowID="+rID+"&Task=Delete";
httpReq.open("POST","updatedeleteHandson.php"+rows,true);
httpReq.send();
}

function fade()
{
document.getElementById("myDiv2").innerHTML="<div style=\"position: fixed;" + 
			"z-index: 999;"+
            "height: 100%;"+
            "width: 100%;"+
            "top: 0;"+
            "left:0;"+
            "background-color:black;"+
            "filter: alpha(opacity=60);"+
			"opacity: 0.3;"+
            "-moz-opacity: 0.8;\">"+
        "<div style=\"z-index: 1000;"+
            "margin: 100px auto;"+
            "padding: 10px;"+
            "width: 130px; "+
            "border-radius: 10px;"+
            "filter: alpha(opacity=100);"+
            "opacity: 1;"+
            "-moz-opacity: 1;\">"+
            "<img src=\"images/bluespinner.gif\" style=\" height: 128px;"+
            "width: 128px;\" />"+
        "</div>"+
    "</div>"
}
</script>
<?php	
//}
// else{
	// echo "Sorry? No Data available at this time";
// }
?>
 <meta charset="utf-8">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class="container">
  <button class="btn btn-info btn-lg" data-toggle="modal" id="success" data-target="#successs" style="display:none"></button>
  <button class="btn btn-info btn-lg" data-toggle="modal" id="error" data-target="#errorr" style="display:none"></button>
  <div class="modal fade" id="successs" role="dialog" >
    <div class="modal-dialog" style="width: 320px;margin: 0px auto;line-height: 0;">
      <div class="modal-content">
        <div class="modal-body" style="padding: 5px;">
		  <div class="alert alert-success" id="upd" style="margin-bottom:0px;"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="errorr" role="dialog" >
    <div class="modal-dialog" style="width: 320px;margin: 0px auto;line-height: 0;">
      <div class="modal-content">
        <div class="modal-body" style="padding: 5px;">
		  <div class="alert alert-danger" id="er" style="margin-bottom:0px;"></div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>