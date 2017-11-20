var profileValidate=true;
var goPost=false;
function comfirmationOnOff(bSwitch)
{
	if(bSwitch==true)
	{
		$("#modalAgency").css("display", "Block");
	}
	else{
		$("#modalAgency").css("display", "None");
	}
}
function checkDate()
{
	var fDate=$('#txtFromDate').val();
	var tDate=$('#txtToDate').val();
	var fd = new Date(fDate);
	var td = new Date(tDate);
	var fdy="";
	var tdy="";
	var d="If you continue the Weekly Chart will show incomplete data. Press Yes to continue. No to change the date";
	var weekday = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
	fdy = weekday[fd.getDay()];
	tdy = weekday[td.getDay()];
	if((fdy!="Sunday") && (fDate!=""))
	{
		document.getElementById("datemsg").innerHTML=fDate+" is not a Sunday. "+d;
		comfirmationOnOff(true);
		 goPost=false;
	}
	else if((tdy!="Saturday") && (tDate!=""))
	{
		 document.getElementById("datemsg").innerHTML=tDate+" is not a Saturday. "+d;
		 comfirmationOnOff(true);
		 goPost=false;
	}	
	else
	{
		goPost=true;
	}
	return goPost;
	//return true;
}
function getFilteredData()
{
	var fDate=$('#txtFromDate').val();
	var tDate=$('#txtToDate').val();
	var sku=$('#sku').val();
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} 
	else {
		// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		   //if(xmlhttp.responseText=="true")
		   //{
			 //drawVisualization();
			 return true;
		  // }
		}
	};
	xmlhttp.open("GET","update.php?sku="+sku+"&fromdate="+fDate+"&todate="+tDate,true);
	xmlhttp.send();		
	return true;
}

function selectedSKU(skuID)
{
	document.getElementById("i").innerHTML="<div style=\"position: fixed;" + 
		"z-index: 999;"+
		"height: 100%;"+
		"width: 100%;"+
		"top:0;"+
		"left:0;"+
		"background-color:black;"+
		"filter: alpha(opacity=60);"+
		"opacity: 0.3;"+
		"-moz-opacity: 0.8;\">"+
	"<div style=\"z-index: 1000;"+
		"margin: 18% 44%;"+
		"padding: 10px;"+
		"width: 130px; "+
		"border-radius: 10px;"+
		"filter: alpha(opacity=100);"+
		"opacity: 1;"+
		"-moz-opacity: 1;\">"+
		"<img src=\"images/bluespinner.gif\" style=\" height: 128px;"+
		"width: 128px;\" />"+
	"</div>"+
"</div>";
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} else {
		// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			window.location.href ="index.php";
		}
	};
	xmlhttp.open("GET","update.php?setTempID="+skuID,true);
	xmlhttp.send();		
	return true;
}	

function validateUpload()
{
	if($("#uploaddate").val()=="")
	{
		alert("please select date");
		$("#uploaddate").focus();
		return false;
	}
	if($("#file").val()=="")
	{
		alert("please select file");
		return false;
	}
	return true;
}

function viewCSV(src,file)
{
	if(file=="sales")
	{
		if($(src).val()=="0")
		{
			$(src).focus();
			return false;
		}
	redirect('Dashboard',$(src).val());
	}
	else if (file=="inventory")
	{
		if($(src).val()=="0")
		{
			$(src).focus();
			return false;
		}
	redirect('Inventory',$(src).val());
	}
	else if (file=="InventoryAddi")
	{
		if($(src).val()=="0")
		{
			$(src).focus();
			return false;
		}
		redirect('InventoryAddi',$(src).val());
	}
	
}

function redirect(page,filename)
{
	
	popUp=window.open("handsontable.php?page="+page+"&file="+filename,"Window1","location=no, titlebar=no, scrollbars=yes,width=1165px,height=610px,screenX=90px,screenY=25px"); 
	//popUp=window.open("handsontable.php?page="+page,'_blank'); 
}
var upload = false;
var popUp=false;
function openUpload(page)
{
	//popUp=window.open("upload.php","Window1","location=no, titlebar=no, scrollbars=yes,width=1165px,height=610px,screenX=90px,screenY=25px"); 
	if(page=="sales")
	{
		if(upload && !upload.closed)
		{  
			upload.focus();
			upload.close();
		}
		upload=window.open("uploadsales.php",'_blank');
	}
	else if(page=="inventory")
	{
		if(upload && !upload.closed)
		{  
			upload.focus();
			upload.close();
		}
		upload=window.open("uploadinventory.php",'_blank');
	}
}

function winClose()
{
	if(upload && !upload.closed)
	{	
		upload.focus();
		upload.close();
	}
	if (popUp!=false)
	{
		popUp.close();
	}
}

function formValidate(action)
{
	var fName=$('#txtFirstName').val().trim();
	var lName=$('#txtLastName').val().trim();
	var email=$('#txtEmailId').val().trim();
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
	if(email=="")
	{
		document.getElementById("umsg").innerHTML="<div class='sp'>Please enter user email</div>";
		$('#txtEmailId').css({'border-color':'red'});
		$('#txtEmailId').focus();
		return false;
	}
	else{$('#txtEmailId').css({'border-color':'initial'});}
	//if((action=="validate")||(action==1))
	//{
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
	//}
	var valA = email.indexOf("@");
    var valB = email.lastIndexOf(".");
    if (valA<1 || valB<valA+2 || valB+2>=email.length) {
        document.getElementById("umsg").innerHTML="<div class='sp'>e-mail address is invalid</div>";
		$('#txtEmailId').css({'border-color':'red'});
		return false;
    }
	return true;
}
function UserCreation()
{
	profileValidate=formValidate();
	if(!profileValidate==true){return false;}
	document.getElementById("umsg").innerHTML="<img src='Images/loader.gif'>";
	var email=$('#txtEmailId').val().trim();
	if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
               if(xmlhttp.responseText==1)
			   {	
				nextUsersSku();
			   }
			   else
			   {
				document.getElementById("umsg").innerHTML=xmlhttp.responseText;
			   }
            }
        };
		xmlhttp.open("GET","usercreation.php?action=validate&email="+email,true);
		xmlhttp.send();	
		return true;
}
function nextUsersSku(){
	$('#form').css({'display':'none'});
	$('#skudata').css({'display':'block'});
	document.getElementById("umsg").innerHTML=$('#txtFirstName').val()+" "+$('#txtLastName').val();
	$("#umsg").addClass("usr");
	return true;
}

function back(){
	$('#skudata').css({'display':'none'});
	$('#form').css({'display':'block'});
	}
function selectAll() 
{ 
	if( $('#sSKU').has('option').length <= 0 )
	{
		 document.getElementById("umsg").innerHTML="<div class='sp'>select SKU's</div>";
		 $('#sSKU').css({'border-color':'red'});
		 $('#sSKU').focus();
		 return false;
	}
	var sSKU=document.getElementById("sSKU");
	
	for (var i = 0; i < sSKU.options.length; i++) 
	{ 
		 sSKU.options[i].selected = true; 
	}
return true;
}

function removeCSV(src,table,id)
{
	if($(src).val()=="0")
	{
		$(src).focus();
		return false;
	}
	deleteCSV($(src).val(),0,table,id);
}

function deleteCSV(filename,rld,table,id)
{
	if(confirm("Do You Want To Delete "+filename)==false){
	return false;
	}
	var bflag=true;
	screenFade();
 if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} else {
		// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		   if(xmlhttp.responseText==1)
		   {
			 if(rld==1)
			 {
				window.opener.location.reload();
				$("#tabsProfile a[href='#Admin-Trends'").click();
			 }
			 else
			 {
				//$("#"+id+" option:selected").remove();
				document.getElementById("i").innerHTML="";
				alert("File delete successfully");
				bflag=false;
				location.reload();
			 }
			 if(bflag==true){
				document.getElementById("i").innerHTML="";
				alert("File delete successfully");
			 }
			}
		   else
		   {
			document.getElementById("i").innerHTML="";
			alert("Sorry? There are problem to delete this file");
		   }
		}
	};
	xmlhttp.open("GET","update.php?filename="+filename+"&table="+table,true);
	xmlhttp.send();		
	return true;
}

function screenFade()
{
	document.getElementById("i").innerHTML="<div style=\"position: fixed;" + 
		"z-index: 999;"+
		"height: 100%;"+
		"width: 100%;"+
		"top:0;"+
		"left:0;"+
		"background-color:black;"+
		"filter: alpha(opacity=60);"+
		"opacity: 0.3;"+
		"-moz-opacity: 0.8;\">"+
	"<div style=\"z-index: 1000;"+
		"margin: 18% 44%;"+
		"padding: 10px;"+
		"width: 130px; "+
		"border-radius: 10px;"+
		"filter: alpha(opacity=100);"+
		"opacity: 1;"+
		"-moz-opacity: 1;\">"+
		"<img src=\"images/bluespinner.gif\" style=\" height: 128px;"+
		"width: 128px;\" />"+
	"</div>"+
"</div>";
}
