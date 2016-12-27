<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title> Purchase Return - PR-00<?php echo isset($pono) ? $pono : ''; ?></title>
<style media="all">

	#logo { margin:0 0 0 75px;}
	#logotext{ font-size:12px; text-align:center; margin:0; }
	p { margin:0; padding:0; font-size:13px;}
	#pono{ font-size:18px; padding:0; margin:0 5px 10px 0; text-align:left;}
	
	table.product
	{ border-collapse:collapse; width:100%; }
	
	table.product,table.product th
	{	border: 1px solid black; font-size:13px; font-weight:bold; padding:3px 0 3px 0; }
	
	table.product,table.product td
	{	border: 1px solid black; font-size:13px; font-weight:normal; padding:3px 0 3px 0; text-align:center; }
	
	table.product td.left { text-align:left; padding:3px 5px 3px 10px; }
	table.product td.right { text-align:right; padding:3px 10px 3px 5px; }
	
</style>
</head>

<body onLoad="window.print();">

<div style="width:750px; font-family:Arial, Helvetica, sans-serif; font-size:12px;"> 

	<div style="border:0px solid; width:410px; height:145px; float:left;">
		<img id="logo" align="middle" width="250" src="<?php echo isset($logo) ? $logo : ''; ?>"> <br>
		<p id="logotext"> 
		  <?php echo $paddress; ?> Kotamadya Medan - <?php echo $p_zip; ?> <br> Telp. (061) 7867972, Fax. (061) 7871838 <br>
		  E-Mail : multiadv@indosat.net.id - Website : www.multiadv.com
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
			<th> No </th> <th> Jenis Barang </th> <th> Banyak </th> <th> Harga Satuan </th> <th> Pajak </th> <th> Total Harga </th>
		 </tr>
		 
		 <?php
		 	
			if ($items)
			{
				$i=1;
				foreach ($items as $res)
				{
					echo "
					
					 <tr> 
						<td> ".$i." </td>
						<td class=\"left\"> ".$res->name." </td> 
						<td> ".$res->qty.' '.$res->unit." </td> 
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

		 <tr> <td></td> <td class="left"> Biaya Lain </td> <td colspan="3"></td>   <td class="right"> <?php echo number_format($cost); ?> </td> </tr>
		 <tr> <td colspan="5"></td>  <td class="right"> <?php echo number_format($balance); ?> </td> </tr>
			
		</table>
		
		<div style="float:left; width:300px; border:0px solid #000;">  
			<p style="margin:0; padding:5px 0 0 0;">  </p>
		</div>
		
		<div style="float:right;">
			
			<table>
				<p> &nbsp; &nbsp; Dipesan Oleh, &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Disetujui Oleh, </p> <br> <br> <br> <br>
				<p style="text-align:right;"> ( <?php echo $user; ?> ) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; (_______________) </p>
				<p> &nbsp; &nbsp; &nbsp; Purchasing  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  Direktur Utama </p>
			</table>
			<br>
		</div>
		
		<div style="clear:both; ">
		
	</div>	
	
</div>

</body>
</html>
