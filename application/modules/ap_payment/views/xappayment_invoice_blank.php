<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title> AP-Payment - CD-00<?php echo isset($pono) ? $pono : ''; ?></title>
<style media="all">

	#logo { margin:0 0 0 75px;}
	#logotext{ font-size:14px; text-align:center; margin:0; }
	p { margin:0; padding:0; font-size:13px;}
	#pono{ font-size:18px; padding:0; margin:0 5px 10px 0; text-align:left;}
	
	table.product
	{ border-collapse:collapse; width:20cm; }
	
	table.product,table.product th
	{	border: 0px solid black; font-size:14px; font-weight:bold; padding:4px 0 4px 0; }
	
	table.product,table.product td
	{	border: 0px solid black; font-size:14px; font-weight:normal; padding:3px 0 3px 0; text-align:center; }
	
	table.product td.left { text-align:left; padding:6px 5px 6px 10px; }
	table.product td.right { text-align:right; padding:6px 10px 6px 5px; }
	
	table.product td#no { width:0.5cm; }
	table.product td#name { width:12cm; }
	table.product td#jumlah { width:6cm; }
	
</style>
</head>

<body onLoad="window.print()">

<div style="width:21.5cm; font-family:Arial, Helvetica, sans-serif; font-size:12px; border:0px solid red; height:11cm;">
	
	<div style="width:350px; border:0px solid #000; float:left; margin:0.9cm 0 0 2.7cm;">
		<table style="font-size:13px;">
 		    <tr> <td> </td> <td></td> <td> <?php echo $vendor; ?> </td> </tr>
			<tr> <td> </td> <td> </td> <td> <?php echo $ven_bank; ?> </td> </tr>
		</table>
	</div>
	
	<div style="width:260px; border:0px solid red; float:right; margin:1.3cm 0 0 0;">
		<table style="font-size:12px; margin:0 0 0 80px;">
			<tr> <td> </td> <td></td> <td> <?php echo $podate; ?> </td> </tr>
			<tr> <td> </td> <td></td> <td> <?php echo $bank; ?> </td> </tr>
		</table>
	</div>
	
	<div style="clear:both; "></div>
	
	<div style="margin:1.5cm 0 0 0; border:0px dotted #000;">
		
		<div style="height:5cm; border:0px solid red;">
		<table class="product">

		 <!--<tr> <th> No </th>  <th> Keterangan </th> <th> Jumlah </th> </tr> -->
		 
		 <!--<tr> <td> 1 </td> <td class="left"> PO-0021 - Pembelian Alat Kantor &nbsp; GD4523 </td> <td class="right"> 1.000.000 </td> </tr>  -->
		 
		 <?php
		 	
			if ($items)
			{
				$i=1;
				foreach ($items as $res)
				{
					echo "
					
					 <tr> 
						<td id=\"no\"> ".$i." </td>
						<td id=\"name\" class=\"left\"> ".$res->code."-00".$res->no." - ".$res->notes." &nbsp; - &nbsp; ".tgleng($res->dates)." </td> 
						<td id=\"jumlah\" class=\"right\"> ".number_format($res->amount)." </td>  
					 </tr>
					
					"; $i++;
				}
			}
			
		 ?>
		 	
		</table>
		</div>
		
		<table class="product">
			<tr> <td></td> <td class="right"> <b> </b> </td> <td id="jumlah" class="right"> <?php echo $amount; ?> </td> </tr>
		</table>
		
		<div style="float:left; width:600px; border:0px solid #000; margin:0px 0 5px 2cm;">  
			<table style="font-size:13px; padding:0;">
				<tr> <td> <?php echo $terbilang; ?> </td> </tr>
			</table>
		</div>
		
		<div style="clear:both; "></div>
		
		<div style="width:620px; border:0px solid #000; float:right; margin:3px 0px 0 0;">
		<style>
			.sig{ font-size:11px; width:100%; float:right; text-align:center;}
			.sig td{ width:155px;}
		</style>
<!--			<table border="0" class="sig">
				<tr> <td> Disetujui : </td> <td> Diketahui : </td> <td> Dibayar Oleh : </td> <td> Yang Menerima : </td> </tr>
			</table> <br> <br> <br> <br> <br> 
			
			<table border="0" class="sig">
				<tr> <td> Direktur </td> <td> Accounting </td> <td> Kasir </td> <td> ___________________ </td> </tr>
			</table> -->
		</div>
		
		<!--<div style="float:right;">
			
			<table>
				<p> &nbsp; &nbsp; Dipesan Oleh, &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Disetujui Oleh, </p> <br> <br> <br> <br>
				<p style="text-align:right;"> ( <?php echo $user; ?> ) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; (_______________) </p>
				<p> &nbsp; &nbsp; &nbsp; Purchasing  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  Direktur Utama </p>
			</table>
			<br>
		</div> -->
		
		<div style="clear:both; ">
		
	</div>	
	
</div>

</body>
</html>
