<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title> Stock -In - BTB-00<?php echo isset($no) ? $no : ''; ?></title>

<style type="text/css" media="all">

	body{ font-size:0.75em; font-family:Arial, Helvetica, sans-serif; margin:0; padding:0;}
	#container{ width:21cm; height:11.6cm; border:0pt solid #000;}
	.clear{ clear:both;}
	#tablebox{width:20cm; border:0pt solid red; float:left; margin:0.1cm 0 0 0.4cm;}
	
	table.tab1
	{ border-collapse:collapse; width:100%; }
	
	table.tab1,table.tab1 th
	{	border: 1px solid black; font-size:0.95em; font-weight:bold; padding:4px 0 4px 0; }
	
	table.tab1,table.tab1 td
	{	border: 1px solid black; font-size:0.95em; font-weight:normal; padding:3px 0 3px 0; text-align:center; }
	
	table.tab1 td.no { text-align:center; }
	table.tab1 td.name { text-align:left; padding:3px 0 4px 5px; }
	table.tab1 td.qty { text-align:center; padding:3px; }
	table.tab1 td.unit { text-align:center; padding:3px; }
	table.tab1 td.desc { text-align:left; padding:3px; }
	table.tab1 td.sku { text-align:left; padding:3px; }
	
	#logobox{ width:5.5cm; height:1cm; border:0pt solid blue; margin:0.8cm 0 0 0.5cm; float:left;}
	#venbox{ width:8.3cm; height:1.9cm; border:0pt solid green; margin:0.0cm 0.5cm 0 0.5cm; float:left;}
	#signbox{ height:2.2cm; width:18.2cm; float:right; border:0pt solid red; margin:0.20cm 0.6cm 0 0cm; }
	#titbox{ height:0.5cm; width:19.7cm; border:0pt solid blue; margin:0cm 0 0 0.5cm;}
</style>

</head>

<body bgcolor="#FFFFFF"; onload="">

<div id="container">
	
	<div id="venbox">
		<table width="100%" style="font-size:1em; margin:0;">
			<tr> <td> Date </td> <td>:</td> <td> <?php echo $podate; ?> </td> </tr>
			<tr> <td style="width:2.3cm;"> Supplier </td> <td>:</td> <td> <?php echo $vendor; ?> </td> </tr>
			<tr> <td> No. PO </td> <td>:</td> <td> <?php echo $po; ?> </td> </tr>
		</table>
	</div>
	
	<div class="clear"></div>
	
	<div id="titbox"> 
		<p style="padding:0; margin:0; font-size:1.05em; float:left;"> No. BTB : </p>
		<p style="padding:0; margin:0 0 0 0.5cm; font-weight:bold; font-size:1.5em; float:left;"> <?php echo "00".$no; ?> </p>
		<p style="padding:0; margin:0 0 0 3.3cm; font-weight:bold; font-size:1.3em; float:left;"> STOCK-IN TRANSACTION (BTB) </p>
	</div>
	
	<div id="tablebox">
	
		<table class="tab1" border="0">
<tr> <th class="no"> No </th> <th class="sku"> SKU </th> <th class="name"> Product </th> <th class="qty"> Qty </th> <th class="unit"> Unit </th> <th class="desc"> Desc </th> </tr>		

<!--<tr> 
    <td class="no"> 1 </td>  
	<td class="name"> Pembelian Color Merah Pelangi </td> 
	<td class="qty"> 1 </td> 
	<td class="unit"> Pcs </td>  
	<td class="desc"> 1.000.000 </td> 
</tr> -->

 <?php
		 	
			if ($items)
			{
				function get_name($pid){ $res = new Products_lib(); return $res->get_name($pid); }
				function get_unit($pid){ $res = new Products_lib(); return $res->get_unit($pid); }
				
				$i=1;
				foreach ($items as $res)
				{
					echo "
					
					 <tr> 
						<td class=\"no\"> ".$i." </td>
						<td class=\"no\"> PRO-0".$res->product." </td>
						<td class=\"name\">".get_name($res->product)." </td> 
						<td class=\"qty\"> ".$res->qty." </td>
						<td class=\"unit\"> ".get_unit($res->product)." </td>  
						<td class=\"desc\">  </td>
					 </tr>
					
					"; $i++;
				}
			}
			
?>

		</table>
	</div>  <div class="clear"></div>
	
	<div id="signbox"> 
		
		<div style="height:2.15cm; width:3cm; border:0pt solid blue; float:left; padding:0;"> 
			<p style="padding:0; margin:0; font-size:1em; text-align:center;"> Delivered By : </p> 
			<p style="padding:0.7cm 0 0 0; text-align:center;"> ( <?php echo $staff; ?> ) <br /> Toko </p> 
		</div>
		
		<div style="height:2.15cm; width:3cm; border:0pt solid blue; float:left; padding:0; margin:0 0 0 4.7cm;"> 
			<p style="padding:0; margin:0; font-size:1em; text-align:center;"> Prepared By : </p> 
			<p style="padding:0.7cm 0 0 0; text-align:center;"> ( <?php echo $user; ?> ) <br /> Logistik </p> 
		</div>
        
        <div style="height:2.15cm; width:3cm; border:0pt solid blue; float:right; padding:0; margin:0 0 0 0cm;"> 
			<p style="padding:0; margin:0; font-size:1em; text-align:center;"> Approved By : </p> 
			<p style="padding:0.7cm 0 0 0; text-align:center;"> ( &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ) <br /> Manager </p> 
		</div>
		
		<div style="height:2.15cm; width:3cm; border:0pt solid blue; float:right; padding:0; margin:0 0 0 0cm;"> 
			<p style="padding:0; margin:0; font-size:1em; text-align:center;"> Review By : </p> 
			<p style="padding:0.7cm 0 0 0; text-align:center;"> ( &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ) <br /> Accounting </p> 
		</div>
		
	</div> 
	
</div>

</body>
</html>
