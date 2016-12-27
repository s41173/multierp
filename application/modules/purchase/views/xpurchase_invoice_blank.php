<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title> Multiadv Purchase Order - PO-00<?php echo isset($pono) ? $pono : ''; ?></title>
<style type="text/css" media="all">

	#logo { margin:0 0 0 75px;}
	#logotext{ font-size:12px; text-align:center; margin:0; }
	p { margin:0; padding:0; font-size:14px;}
	#pono{ font-size:18px; padding:0; margin:0 5px 10px 0; text-align:left;}
	
	table.product
	{ border-collapse:collapse; width:100%; margin:0px 0 0 0px; }
	
	table.product,table.product th
	{	border: 1px solid black; font-size:14px; font-weight:bold; padding:5px 0 5px 0; }
	
	table.product,table.product td
	{	border: 0px solid black; font-size:14px; font-weight:normal; padding:7px 0 7px 0; text-align:center; }
	
	table.product td.left { text-align:left; padding:7px 5px 7px 15px; }
	table.product td.right { text-align:right; padding:7px 10px 7px 10px; }
	
	table.product td#no{ width:1cm;}
	table.product td#name{ width:9.5cm;}
	table.product td#qty{ width:1.5cm;}
	table.product td#price{ width:3cm;}
	table.product td#total{ width:4.2cm;}
	
</style>
</head>

<body onLoad="window.print()">

<div style="width:20.5cm; font-family:Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0; border:0px solid red;">
	
	<div style="border:0px solid; float:right; width:230px;">
		
		<p style="padding:90px 0 0 40px;"> <!--Medan, --> &nbsp; <?php echo isset($podate) ? $podate : ''; ?> </p> <br>
		<!--<p> Kepada Yth, </p>  -->
		<p style="padding:35px 0 0 0px;"> <b> <?php echo isset($vendor) ? $vendor : ''; ?> </b> </p>
		<p style="padding:5px 0 5px 0px;"> <?php echo isset($address) ? $address : ''; ?> - <?php echo isset($city) ? $city : ''; ?> </p> 
		<p style="padding:5px 0 5px 0px;"> <?php echo isset($phone) ? $phone : ''; ?> </p>
		
	</div>
	
	<div style="clear:both; "></div>
	
	<!--<h2 style="font-size:18px; font-weight:normal; text-align:center; margin:5px 0px 10px 0px; padding:0 0 0 25px;"> PURCHASE ORDER </h2> <div style="clear:both; "></div>  -->

	
	<div style="clear:both; "></div>
	
	<div style="margin:60px 0 0 0px; border:0px dotted #000;">
		
		<div style="width:auto; height:4.5cm; border:0px solid red;">
		<table class="product">

		 <!--<tr> 
			<th> No </th> <th> Jenis Barang </th> <th> Banyak </th> <th> Harga Satuan </th> <th> Pajak </th> <th> Total Harga </th>
		 </tr> -->
		 
		 <?php
		 	
			if ($items)
			{
				$i=1;
				foreach ($items as $res)
				{
					echo "
					
					 <tr> 
						<td id=\"no\"> ".$i." </td>
						<td id=\"name\" class=\"left\"> ".$res->name." </td> 
						<td id=\"qty\"> ".$res->qty.' '.$res->unit." </td> 
						<td id=\"price\" class=\"right\"> ".number_format($res->price)." </td> 
						<td id=\"total\" class=\"right\"> ".number_format($res->amount + $res->tax)." </td>   
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
		</table>		
		</div>
		
		<table class="product" style="margin:15px 0 0 0;">
			 <tr> <td id="no"></td> <td id="name" class="left"> Biaya Lain </td> <td colspan="3"></td>   
			 <td id="total" class="right"> <?php echo number_format($cost); ?> </td> </tr>
			 
		     <tr> <td id="no"></td> <td id="name" class="left"> Down Payment </td> <td colspan="3"></td> 
			 <td id="total" class="right"> <?php echo number_format($p1); ?> </td> </tr>
			 
		     <tr> <td colspan="5"></td>  <td id="total" class="right"> <?php echo number_format($p2); ?> </td> </tr>
		</table>
		
		<div style="float:left; width:300px; border:0px solid #000;">  
			<p style="margin:0; padding:5px 0 0 25px;"> <!--Keterangan : --> <?php echo $desc; ?> </p>
		</div>
		
		<div style="float:right;">
			
			<table>
				<!--<p> &nbsp; &nbsp; Dipesan Oleh, &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Disetujui Oleh, </p> <br> <br> <br> <br> -->
				<p style="text-align:right; padding:130px 280px 0 0;"> <?php echo $user; ?> </p>
				<!--<p> &nbsp; &nbsp; &nbsp; Purchasing  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  Direktur Utama </p> -->
			</table>
			<br>
		</div>
		
		<div style="clear:both; ">
		
	</div>	
	
</div>

</body>
</html>
