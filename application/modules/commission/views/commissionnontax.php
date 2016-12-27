<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title> Non Tax Sales Invoice - SO-00<?php echo isset($pono) ? $pono : ''; ?></title>
<style media="all">

	body{ font-family:"Times New Roman", Times, serif; font-size:16px;}
	#logo { margin:0 0 0 75px;}
	#logotext{ font-size:12px; text-align:center; margin:0; }
	p { margin:0; padding:0; font-size:16px; font-family:"Times New Roman", Times, serif;}
	#pono{ font-size:18px; padding:0; margin:0 5px 10px 0; text-align:left;}
	table{ font-size:16px; font-family:"Times New Roman", Times, serif; padding:5px 0 5px 0;}
	.clear{ clear:both;}
	
</style>
</head>

<body onLoad="window.print()">

<div style="width:750px; font-family:Arial, Helvetica, sans-serif; font-size:12px; border:2px solid #000;">
	
	<p style="padding:15px 0 0 15px;"> No. <b> <?php echo $pono; ?> </b> </p>
	<table border="0" style=" margin:0 0 0 10px;">
		<tr> <td style="width:150px;"> Sudah terima dari </td> <td>:&nbsp;</td> <td> <?php echo $customer; ?> </td> </tr>
		<tr> <td> Uang Sebanyak </td> <td>:&nbsp;</td> <td> <?php echo $terbilang; ?> </td> </tr>
		<tr> <td> Untuk Pembayaran </td> <td>:&nbsp;</td> <td> <?php echo $notes; ?> </td> </tr>
	</table> <div class="clear"></div>
	
	<div style="width:100%; height:110px; border:0px solid red; margin:30px 0 20px 0;">
		
		<p style="float:right; padding:0 20px 0 0;"> Medan, <?php echo $podate; ?> </p>
		<p style="padding:60px 0 0 15px; font-size:18px;"> Jumlah Rp. &nbsp; <b> <?php echo number_format($total,0,",","."); ?> ,- </b> </p>
		
		
	</div>
		
</div>

</body>
</html>
