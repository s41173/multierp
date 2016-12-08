<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title> Multiadv Purchase Return - PR-00<?php echo isset($pono) ? $pono : ''; ?></title>
<style media="all">

	body{ font-family:Tahoma, Geneva, sans-serif;}
	#logo { margin:0 10px 0 0px; float:left;}
	#logotext{ font-size:1em; width:450px; text-align:center; margin:30px 0 0 10px; }
	p { margin:0; padding:0; font-size:1.05em;}
	#pono{ font-size:1.4em; padding:0; margin:0 5px 10px 0; text-align:left;}
	
	table.product
	{ border-collapse:collapse; width:100%; margin-bottom:5px; }
	
	table.product,table.product th
	{	border: 1px solid black; font-size:1.05em; font-weight:bold; padding:3px 0 3px 0; }
	
	table.product,table.product td
	{	border: 1px solid black; font-size:1.05em; font-weight:normal; padding:3px 0 3px 0; text-align:center; }
	
	table.product td.left { text-align:left; padding:3px 5px 3px 10px; }
	table.product td.right { text-align:right; padding:3px 10px 3px 5px; }
	
</style>
</head>

<body onLoad="">

<div style="width:750px; font-family:Arial, Helvetica, sans-serif; font-size:12px;"> 

	<div style="border:0px solid; width:410px; height:145px; float:left;">
		<img id="logo" align="middle" width="150" src="<?php echo isset($logo) ? $logo : ''; ?>"> <br>
		<p id="logotext"> 
		  <?php echo $paddress; ?> Kotamadya Medan - <?php echo $p_zip; ?> <br> Telp. (061) 7867972, Fax. (061) 7871838 <br>
		  E-Mail : <?php echo $p_email; ?> - Website : <?php echo $p_sitename; ?>
		</p>
		<p style="float:left; margin:0; padding:20px 0 0 5px; font-weight:bold;"> NPWP : <?php echo $p_npwp; ?> </p>
	</div>
	
	<div style="border:0px solid; float:right;">
		
		<h3 id="pono"> No : PR-00<?php echo $pono; ?> &nbsp; &nbsp; <?php echo 'IDR'; ?> </h3>
		<p> Medan, &nbsp; <?php echo isset($podate) ? $podate : ''; ?> </p> <br>
		<p> Kepada Yth, </p> 
		<p style="margin:8px 0 0 0;"> <b> <?php echo isset($vendor) ? $vendor : ''; ?> </b> </p>
		<p> <?php echo isset($address) ? $address : ''; ?> - <?php echo isset($city) ? $city : ''; ?> </p> 
		<p> <?php echo isset($phone) ? $phone : ''; ?> </p>
		
	</div>
	
	<div style="clear:both; "></div>
	
	<h2 style="font-size:18px; font-weight:normal; text-align:center; margin:5px 0px 10px 0px; padding:0 0 0 25px;"> PURCHASE RETURN </h2> <div style="clear:both; "></div> 

	
	<div style="clear:both; "></div>
	
	<div style="margin:10px; border-bottom:1px dotted #000;">
		<?php //echo ! empty($table) ? $table : ''; ?>
		
		<table class="product">

		 <tr> 
			<th> No </th> <th> Product </th> <th> Qty </th> <th> Unit Price </th> <th> Tax </th> <th> Balance </th>
		 </tr>
		 
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
						<td> ".$i." </td>
						<td class=\"left\"> ".get_name($res->product)." </td> 
						<td> ".$res->qty.' '.get_unit($res->product)." </td> 
						<td class=\"right\"> ".number_format($res->price)." </td> 
						<td class=\"right\"> ".number_format($res->tax)." </td> 
						<td class=\"right\"> ".number_format($res->amount + $res->tax)." </td>   
					 </tr>
					
					"; $i++;
				}
			}
			
		 ?>
		 
		 
<!--		 <tr> 
			<td> 1 </td>
			<td class="left"> Dodol Perbaungan Medan Barat </td> 
			<td> 10 </td> 
			<td class="right"> 10.000 </td> 
			<td class="right"> 1.000 </td> 
			<td class="right"> 11.000 </td>   
		 </tr> -->

		 <tr> <td></td> <td class="left"> Costs </td> <td colspan="3"></td>   <td class="right"> <?php echo number_format($cost); ?> </td> </tr>
		 <tr> <td colspan="5"></td>  <td class="right"> <?php echo number_format($balance); ?> </td> </tr>
			
		</table>
		
		<div style="float:left; width:300px; border:0px solid #000;">  
			<p style="margin:0; padding:5px 0 0 0;">  </p>
		</div>
		
		<div style="float:right;">
			
            <style>
			.sig{ font-size:12px; width:100%; float:right; text-align:center;}
			.sig td{ width:155px;}
		    </style>
            
			<table border="0" class="sig">
				<tr> <td> Approved By : </td> <td> Review By : </td> <td> Returned By: </td> </tr>
			</table> <br> <br> <br> <br> <br> 
			
			<table border="0" class="sig">
				<tr> <td> Manager </td> <td> Accounting </td> <td> Purchasing <br> (<?php echo $user; ?>) </td> </tr>
			</table>
            
			<br>
		</div>
		
		<div style="clear:both; ">
		
	</div>	
	
</div>

</body>
</html>
