<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title> Sales Order - SO-00<?php echo isset($pono) ? $pono : ''; ?></title>
<style media="all">

	#container{ width:675px; height:525px; font-family:Arial, Helvetica, sans-serif; font-size:12px; border:15px solid #000000; margin:0; }
	#logobox{ float:left; width:125px; height:498px; border:0px solid #FF0000; margin:0 }
	#content{ float:left; width:530px; height:500px; border:0px solid blue; margin:10px 0 10px 5px; }
	#content table{ font-family:"Times New Roman", Times, serif; font-style:italic; font-size:15px; margin:10px 0 0 0;}
	.clear{ clear:both;}
	h4{ font-family:"Times New Roman", Times, serif; font-size:16px; margin:15px 0 0 0; padding:0 0 0 5px;}
	
	.tab1 p{font-style:normal; padding:5px;}
	.tab2 p{font-style:normal; padding:0px; margin:0;}
	
</style>
</head>

<body onLoad="window.print()">

<div id="container">
	
	<div id="logobox"> </div>
	
	<div id="content">
		
		<table class="tab1" border="0">
			<tr> <td> <b> No. </b> </td> <td><p>:</p></td> <td> <p> <?php echo $pono; ?> </p> </td>  </tr>
			<tr> <td> <b> Sudah terima dari </b> </td> <td><p>:</p></td> <td> <p> <?php echo $customer; ?> </p> </td>  </tr>
			<tr> <td> <b> Uang sebanyak </b> </td> <td><p>:</p></td> <td> <p> <?php echo $terbilang; ?> </p> </td>  </tr>
			<tr> <td style="width:125px;"> <b> Untuk pembayaran </b> </td> <td><p>:</p></td> 
			<td> <p> <?php echo $notes; ?> </p> </td>  </tr>
		</table>
		<div class="clear"></div>
		
		<h4> PERINCIAN : </h4>
		
		<table class="tab2" style="margin:5px 0px 0 3px;" width="500px">
	<tr> <td> <p> Ukuran </p> </td> <td><p>:</p></td> <td><p> <?php echo $size; ?> kolom x <?php echo $coloumn; ?> mm x <?php echo $symbol; ?> 
	<?php echo $price ?> ,- </p></td> 
	<td><p>= <?php echo $symbol; ?></p></td> <td align="right"><p> <?php echo number_format($bruto,0,",","."); ?> ,- </p></td> </tr>
	<tr> <td> <p> Discount </p> </td> <td><p>:</p></td> <td><p> <?php echo $discountpercent; ?> % </p></td> <td><p>= <?php echo $symbol; ?></p></td> <td align="right"><p> 
	<?php echo number_format($discount,0,",","."); ?> ,- </p></td> </tr>
	<tr> <td> <p> Sisa </p> </td> <td><p>:</p></td> <td><p> 65 % </p></td> <td><p>= <?php echo $symbol; ?></p></td> 
	<td align="right"><p> <?php echo number_format($netto,0,",","."); ?> ,- </p></td> </tr>
	<tr> <td> <p> PPN </p> </td> <td><p>:</p></td> <td><p> 10 % </p></td> <td><p>= <?php echo $symbol; ?></p></td> 
	<td align="right"><p> <?php echo number_format($tax,0,",","."); ?> ,- </p></td> </tr>
	<tr> <td> <p> Materai </p> </td> <td><p>:</p></td> <td><p> </p></td> <td><p>= <?php echo $symbol; ?></p></td> 
	<td align="right"><p> <?php echo number_format($cost,0,",","."); ?> ,- </p></td> </tr>
	<tr> <td>  </td> <td></td> <td align="right"><p> Total </p></td> <td><p>= <?php echo $symbol; ?></p></td> 
	           <td align="right"><p> <b> <?php echo number_format($total,0,",","."); ?> ,- </b> </p></td> </tr>
		</table>
		
		<p style="float:right; font-family:'Times New Roman', Times, serif; font-size:15px; margin:10px 30px 0 0;"> <i> Medan, &nbsp; </i> <?php echo $podate; ?> </p> 
		<div class="clear"></div>
		
		<div style="border-top:2px solid #000; border-bottom:2px solid #000; width:210px; height:40px; margin:5px 0 0 8px;">
			<p style="font-family:'Times New Roman', Times, serif; font-size:16px; margin:0; padding:9px 0 0 0;"> <i> Jumlah <?php echo $symbol; ?>  </i>
			 <?php echo number_format($total,0,",","."); ?> ,- </p>
		</div>  <div class="clear"></div>
		
		<div style="width:325px; height:50px; border:0px solid #000; margin:20px 0 0 6px; float:left;"> </div>
		<div style="width:150px; border:0px solid #000; margin:15px 0 0 20px; float:left;">
			<p style="margin:0; padding:20px 0 0 0;"> (...............................................) </p>
		</div>
		
	</div>
	
</div>

</body>
</html>
