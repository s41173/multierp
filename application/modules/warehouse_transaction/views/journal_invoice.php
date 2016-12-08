<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title> <?php echo isset($title) ? $title : ''; ?>  </title>
<style media="all">
	table{ font-family:"Times New Roman", Times, serif; font-size:11px;}
	h4{ font-family:"Times New Roman", Times, serif; font-size:14px; font-weight:600;}
	.clear{clear:both;}
	table th{ background-color:#EFEFEF; padding:4px 0px 4px 0px; border-top:1px solid #000000; border-bottom:1px solid #000000;}
    p{ font-family:"Times New Roman", Times, serif; font-size:12px; margin:0; padding:0;}
	legend{font-family:"Times New Roman", Times, serif; font-size:13px; margin:0; padding:0; font-weight:600;}
	.tablesum{ font-size:13px;}
	.strongs{ font-weight:normal; font-size:12px; border-top:1px dotted #000000; }
	.poder{ border-bottom:0px solid #000000; color:#0000FF;}
</style>
</head>

<body onLoad="window.print()">

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0">
			<tr> <td> No </td> <td> : </td> <td> <?php echo 'DT-00'.$pono; ?> </td> </tr>
			<tr> <td> Date </td> <td> : </td> <td> <?php echo $dates; ?> </td> </tr>
			<tr> <td> Currency </td> <td> : </td> <td> <?php echo $cur; ?> </td> </tr>
		</table>
	</div>
	
	<div style="border:0px solid red; float:right;">
		<table border="0">
			<tr> <td> Status </td> <td> : </td> <td> <?php echo $status; ?> </td> </tr>
			<tr> <td> Print Date </td> <td> : </td> <td> <?php echo tgleng(date('Y-m-d')); ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Daily Journal Report </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:1px dotted #000000; ">
	
		<h2> AP - Account Payable </h2>
		
		<fieldset> <legend> PJ - Purchase Journal </legend>
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Code </th> <th> Notes </th> <th> Balance </th> 
		   </tr>
		   
		   <?php 
		   	
			  $i=1; 
			  if ($PJ)
			  {
				foreach ($PJ as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".$res->code.'-00'.$res->no."</td>
   					   <td class=\"strongs\">".$res->name."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->amount)."</td> 
				   </tr>";
				   $i++;
				}
			 }  
		   ?>
		   
		   <tr> <td></td> <td></td> <td align="right"> Total : </td> <td class="strongs" align="right"> <?php echo number_format($total_PJ) ?> </td> </tr>
		   
		</table>
		</fieldset>
		
		<!-- DP -->
		
		<fieldset> <legend> DP - Down Payment </legend>
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Code </th> <th> Notes </th> <th> Balance </th> 
		   </tr>
		   
		   <?php 
		   	
			  $i=1; 
			  if ($DP)
			  {
				foreach ($DP as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".$res->code.'-00'.$res->no."</td>
   					   <td class=\"strongs\">".$res->name."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->amount)."</td> 
				   </tr>";
				   $i++;
				}
			 }  
		   ?>
		   
		   <tr> <td></td> <td></td> <td align="right"> Total : </td> <td class="strongs" align="right"> <?php echo number_format($total_DP) ?> </td> </tr>
		   
		</table>
		</fieldset>
		
		
		<fieldset> <legend> GJ - General Journal </legend>
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Code </th> <th> Notes </th> <th> Balance </th> 
		   </tr>
		   
		   <?php 
		   	
			  $i=1; 
			  if ($GJ)
			  {
				foreach ($GJ as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".$res->code.'-00'.$res->no."</td>
   					   <td class=\"strongs\">".$res->name."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->amount)."</td> 
				   </tr>";
				   $i++;
				}
			 }  
		   ?>
		   
		   <tr> <td></td> <td></td> <td align="right"> Total : </td> <td class="strongs" align="right"> <?php echo number_format($total_GJ) ?> </td> </tr>
		   
		</table>
		</fieldset>
		
		<fieldset> <legend> CD - Purchase Payment </legend>
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Code </th> <th> Notes </th> <th> Balance </th> 
		   </tr>
		   
		   <?php 
		   	
			  $i=1; 
			  if ($CD)
			  {
				foreach ($CD as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".$res->code.'-00'.$res->no."</td>
   					   <td class=\"strongs\">".$res->name."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->amount)."</td> 
				   </tr>";
				   $i++;
				}
			 }  
		   ?>
		   
		   <tr> <td></td> <td></td> <td align="right"> Total : </td> <td class="strongs" align="right"> <?php echo number_format($total_CD) ?> </td> </tr>
		   
		</table>
		</fieldset>
		
		<fieldset> <legend> CG - General Transaction Payment </legend>
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Code </th> <th> Notes </th> <th> Balance </th> 
		   </tr>
		   
		   <?php 
		   	
			  $i=1; 
			  if ($CG)
			  {
				foreach ($CG as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".$res->code.'-00'.$res->no."</td>
   					   <td class=\"strongs\">".$res->name."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->amount)."</td> 
				   </tr>";
				   $i++;
				}
			 }  
		   ?>
		   
		   <tr> <td></td> <td></td> <td align="right"> Total : </td> <td class="strongs" align="right"> <?php echo number_format($total_CG) ?> </td> </tr>
		   
		</table>
		</fieldset>
		
		
		<fieldset> <legend> TR - Fund Transfer </legend>
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Code </th> <th> Notes </th> <th> Balance </th> 
		   </tr>
		   
		   <?php 
		   	
			  $i=1; 
			  if ($TR)
			  {
				foreach ($TR as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".$res->code.'-00'.$res->no."</td>
   					   <td class=\"strongs\">".$res->name."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->amount)."</td> 
				   </tr>";
				   $i++;
				}
			 }  
		   ?>
		   
		   <tr> <td></td> <td></td> <td align="right"> Total : </td> <td class="strongs" align="right"> <?php echo number_format($total_TR) ?> </td> </tr>
		   
		</table>
		</fieldset> <div class="clear"></div>
		
		<h2> AR - Account Receivable </h2>
		
		<fieldset> <legend> SJ - Sales Journal </legend>
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Code </th> <th> Notes </th> <th> Balance </th> 
		   </tr>
		   
		   <?php 
		   	
			  $i=1; 
			  if ($SJ)
			  {
				foreach ($SJ as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".$res->code.'-00'.$res->no."</td>
   					   <td class=\"strongs\">".$res->name."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->amount)."</td> 
				   </tr>";
				   $i++;
				}
			 }  
		   ?>
		   
		   <tr> <td></td> <td></td> <td align="right"> Total : </td> <td class="strongs" align="right"> <?php echo number_format($total_SJ) ?> </td> </tr>
		   
		</table>
		</fieldset>
		
		<fieldset> <legend> DS - Sales Down Payment </legend>
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Code </th> <th> Notes </th> <th> Balance </th> 
		   </tr>
		   
		   <?php 
		   	
			  $i=1; 
			  if ($DS)
			  {
				foreach ($DS as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".$res->code.'-00'.$res->no."</td>
   					   <td class=\"strongs\">".$res->name."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->amount)."</td> 
				   </tr>";
				   $i++;
				}
			 }  
		   ?>
		   
		   <tr> <td></td> <td></td> <td align="right"> Total : </td> <td class="strongs" align="right"> <?php echo number_format($total_DS) ?> </td> </tr>
		   
		</table>
		</fieldset>
		
		<fieldset> <legend> NSJ - Non Tax Sales Journal </legend>
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Code </th> <th> Notes </th> <th> Balance </th> 
		   </tr>
		   
		   <?php 
		   	
			  $i=1; 
			  if ($NSJ)
			  {
				foreach ($NSJ as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".$res->code.'-00'.$res->no."</td>
   					   <td class=\"strongs\">".$res->name."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->amount)."</td> 
				   </tr>";
				   $i++;
				}
			 }  
		   ?>
		   
		   <tr> <td></td> <td></td> <td align="right"> Total : </td> <td class="strongs" align="right"> <?php echo number_format($total_NSJ) ?> </td> </tr>
		   
		</table>
		</fieldset>
		
		<fieldset> <legend> NDS - Non Tax Sales Down Payment </legend>
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Code </th> <th> Notes </th> <th> Balance </th> 
		   </tr>
		   
		   <?php 
		   	
			  $i=1; 
			  if ($NDS)
			  {
				foreach ($NDS as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".$res->code.'-00'.$res->no."</td>
   					   <td class=\"strongs\">".$res->name."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->amount)."</td> 
				   </tr>";
				   $i++;
				}
			 }  
		   ?>
		   
		   <tr> <td></td> <td></td> <td align="right"> Total : </td> <td class="strongs" align="right"> <?php echo number_format($total_NDS) ?> </td> </tr>
		   
		</table>
		</fieldset>
		
		
		<fieldset> <legend> CR - Receivable </legend>
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Code </th> <th> Notes </th> <th> Balance </th> 
		   </tr>
		   
		   <?php 
		   	
			  $i=1; 
			  if ($CR)
			  {
				foreach ($CR as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".$res->code.'-00'.$res->no."</td>
   					   <td class=\"strongs\">".$res->name."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->amount)."</td> 
				   </tr>";
				   $i++;
				}
			 }  
		   ?>
		   
		   <tr> <td></td> <td></td> <td align="right"> Total : </td> <td class="strongs" align="right"> <?php echo number_format($total_CR) ?> </td> </tr>
		   
		</table>
		</fieldset>
		
		<fieldset> <legend> NCR - Non Tax Sales Receivable </legend>
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Code </th> <th> Notes </th> <th> Balance </th> 
		   </tr>
		   
		   <?php 
		   	
			  $i=1; 
			  if ($NCR)
			  {
				foreach ($NCR as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".$res->code.'-00'.$res->no."</td>
   					   <td class=\"strongs\">".$res->name."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->amount)."</td> 
				   </tr>";
				   $i++;
				}
			 }  
		   ?>
		   
		   <tr> <td></td> <td></td> <td align="right"> Total : </td> <td class="strongs" align="right"> <?php echo number_format($total_NCR) ?> </td> </tr>
		   
		</table>
		</fieldset> <div class="clear"></div>
		
		<h2> ADJUSMENT </h2>
		
		<fieldset> <legend> SAJ - Sales Adjustment Journal </legend>
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Code </th> <th> Notes </th> <th> Balance </th> 
		   </tr>
		   
		   <?php 
		   	
			  $i=1; 
			  if ($SAJ)
			  {
				foreach ($SAJ as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".$res->code.'-00'.$res->no."</td>
   					   <td class=\"strongs\">".$res->name."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->amount)."</td> 
				   </tr>";
				   $i++;
				}
			 }  
		   ?>
		   
		   <tr> <td></td> <td></td> <td align="right"> Total : </td> <td class="strongs" align="right"> <?php echo number_format($total_SAJ) ?> </td> </tr>
		</table>
		</fieldset>
		
		<fieldset> <legend> PRJ - Purchase Return Journal </legend>
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Code </th> <th> Notes </th> <th> Balance </th> 
		   </tr>
		   
		   <?php 
		   	
			  $i=1; 
			  if ($PRJ)
			  {
				foreach ($PRJ as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".$res->code.'-00'.$res->no."</td>
   					   <td class=\"strongs\">".$res->name."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->amount)."</td> 
				   </tr>";
				   $i++;
				}
			 }  
		   ?>
		   
		   <tr> <td></td> <td></td> <td align="right"> Total : </td> <td class="strongs" align="right"> <?php echo number_format($total_PRJ) ?> </td> </tr>
		</table>
		</fieldset>
		
	</div>
	
	
	<div style="border:0px solid red; float:left; margin:25px 0px 0px 0px;">
		<p> Prepared By : <br/> <br/> <br/>  <br/> <br/>
		    (_______________________) 
		</p>
	</div>
	
	<div style="border:0px solid red; float:left; margin:25px 0px 0px 40px;">
		<p> Approval By : <br/> <br/> <br/>  <br/> <br/>
		    (_______________________) 
		</p>
	</div>

</div>

</body>
</html>
