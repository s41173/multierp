<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title> AP-Payment - GJ-00<?php echo isset($pono) ? $pono : ''; ?></title>
<style media="all">

	body{ padding:0;}
	#container{ width:18.5cm; font-family:Arial, Helvetica, sans-serif; font-size:10pt; margin:0; padding:0; border:0px solid #000;}
	#logo { margin:0 0 0 75px;}
	#logotext{ font-size:12px; text-align:center; margin:0; }
	p { margin:0; padding:0; font-size:9pt;}
	#pono{ font-size:18px; padding:0; margin:0 5px 10px 0; text-align:left;}
	
	table.product
	{ border-collapse:collapse; width:18cm; }
	
	table.product,table.product th
	{ border: 1px solid black; font-size:10pt;  font-weight:bold; padding:4px 0 4px 0; }
	
	table.product,table.product td
	{ border: 1px solid black; font-size:10pt; font-weight:normal; padding:3px 0 3px 0; text-align:center; }
	
	table.product td.left { text-align:left; padding:3px 5px 3px 10px; }
	table.product td.right { text-align:right; padding:3px 10px 3px 5px; }
	
	table.product td.no { text-align:center; width:1cm; }
	table.product td.desc { text-align:left; width:7.5cm; padding:3px; }
	table.product td.staff { text-align:left; width:3cm; padding:3px; }
	table.product td.code { text-align:center; width:2.5cm; padding:3px; }
	table.product td.amount { text-align:right; width:3.5cm; padding:3px 5px 3px 3px; }
	
</style>
</head>

<body onLoad="">

<div id="container"> 
	
	<h2 style="font-size:18px; font-weight:normal; text-align:center; text-decoration:underline;"> CASH REQUEST VOUCHER </h2> 
    <div style="clear:both; "></div> 
	
	<div style="width:5cm; border:0px solid #000; float:left;">
		<table style="font-size:9pt;">
 		    <tr> <td> Vendor </td> <td>:</td> <td> <?php echo $vendor; ?> </td> </tr>
            <tr> <td colspan="2"></td> <td> <?php echo $venbank; ?> </td> </tr>
            <tr> <td> Transcode </td> <td>:</td> <td> RC-00<?php echo $pono; ?> </td> </tr>
            <tr> <td> Status </td> <td>:</td> <td> <?php echo $stts; ?> </td> </tr>
		</table>
	</div>
	
	<div style="border:0px solid red; float:right; width:7.5cm;">
		<table style="font-size:9pt;">
			<tr> <td> Date </td> <td>:</td> <td> <?php echo $podate; ?> </td> </tr>
			<tr> <td> Account </td> <td>:</td> <td> <?php echo $acc; ?> </td> </tr>
			<tr> <td> Notes </td> <td>:</td> <td> <?php echo $notes; ?> </td> </tr>
		</table>
	</div>
	
	<div style="clear:both; "></div>
	
	<div style="margin:3px 0 0 0; border-bottom:0px dotted #000;">
		
		<table class="product">

		 <tr> <th> No </th>  <th> Description </th> <th> Code </th> <th> Amount </th> </tr>
		 
		 <!--<tr> <td> 1 </td> <td class="left"> PO-0021 - Pembelian Alat Kantor &nbsp; GD4523 </td> <td class="right"> 1.000.000 </td> </tr>  -->
		 
		 <?php
		 	
			$cost = new Cost_lib();
			$acc = new Account_lib();
		 	
			if ($items)
			{
				$i=1;
				foreach ($items as $res)
				{
					echo "
					
					 <tr> 
						<td class=\"no\"> ".$i." </td>
						<td class=\"desc\"> ".$res->notes." </td>
						<td class=\"staff\"> ".$acc->get_code($cost->get_acc($res->cost))." </td> 
						<td class=\"amount\"> ".number_format($res->amount)." </td>  
					 </tr>
					
					"; $i++;
				}
			}
			
		 ?>
		 
		 
		<tr> <td colspan="2"></td> <td class="right"> <b> Total : </b> </td>
             <td class="right"> <b> <?php echo number_format($amount); ?> </b> </td> 
        </tr>
			
		</table>
		
		<div style="float:left; width:600px; border:0px solid #000; margin:5px 0 5px 0;">  
			<table style="font-size:10pt;">
				<tr> <td> In Words </td> <td>:</td> 
				     <td> <?php echo $terbilang; ?> </td> 
				</tr>
			</table>
		</div>
		
		<div style="clear:both; "></div>
		
		<div style="width:620px; border:0px solid #000; float:right; margin:3px 0px 0 0;">
		<style>
			.sig{ font-size:9pt; width:100%; float:right; text-align:center;}
			.sig td{ width:155px;}
		</style>
			<table border="0" class="sig">
				<tr> <td> Disetujui : </td> <td> Diketahui : </td> <td> Dibuat Oleh : </td> </tr>
			</table> <br> <br> <br> <br> <br> 
			
			<table border="0" class="sig">
				<tr> <td> Direktur <br> (<?php echo $manager; ?>) </td> <td> Accounting <br> (<?php echo $accounting; ?>) </td>  <td> <br> (<?php echo $user; ?>) </td> </tr>
			</table>
		</div>
		
		<!--<div style="float:right;">
			
			<table>
				<p> &nbsp; &nbsp; Dipesan Oleh, &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Disetujui Oleh, </p> <br> <br> <br> <br>
				<p style="text-align:right;"> ( <?php echo $user; ?> ) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; (_______________) </p>
				<p> &nbsp; &nbsp; &nbsp; Purchasing  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  Direktur Utama </p>
			</table>
			<br>
		</div> -->
	
</div>

</body>
</html>
