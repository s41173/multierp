<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title> Sales Order - SO-00<?php echo isset($pono) ? $pono : ''; ?></title>
<style media="all">

	#container{ width:18.5cm; height:15cm; font-family:Arial, Helvetica, sans-serif; font-size:12px; border:0px solid #000000; margin:0; }
	#logobox{ float:left; width:125px; height:498px; border:0px solid #FF0000; margin:0 }
	#content{ float:left; width:530px; height:500px; border:0px solid blue; margin:10px 0 10px 5px; }
	#content table{ font-family:"Times New Roman", Times, serif; font-style:italic; font-size:15px; margin:10px 0 0 0;}
	.clear{ clear:both;}
	h4{ font-family:"Times New Roman", Times, serif; font-size:16px; margin:15px 0 0 0; padding:0 0 0 5px;}
	
	.tab1 p{font-style:normal; padding:5px;}
	.tab2 p{font-style:normal; padding:0 0 0.1cm 0; margin:0;}
	
</style>
</head>

<body onLoad="window.print()">

<div id="container">
	
	<div id="logobox"> </div>
	
	<div id="content">
		
		<table class="tab1" border="0">
			<tr> <td> </td> <td></td> <td> <p> <?php echo $pono; ?> </p> </td>  </tr>
			<tr> <td> </td> <td></td> <td> <p> <?php echo $customer; ?> </p> </td>  </tr>
			<tr> <td> </td> <td></td> <td> <p style="padding:0.3cm 0 0 0; line-height:0.8cm"> <?php echo $terbilang; ?> </p> </td>  </tr>
			<tr> <td style="width:125px;"> <b> </b> </td> <td><p></p></td> <td> <p style="padding:0.5cm 0 0 0;"> <?php echo $notes; ?> </p> </td>  </tr>
		</table>
		<div class="clear"></div>
		
		
		<table class="tab2" style="margin:1.4cm 0px 0 2cm;" width="500px">
 	<tr> <td> <p> </p> </td> <td><p></p></td> <td><p> <?php echo $size; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $coloumn; ?> 
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	
	<?php echo $price ?> ,- </p></td> 
	<td>  </td> <td align="right"><p> <?php echo number_format($bruto,0,",","."); ?> ,- </p></td> </tr>
	<tr> <td> <p> </p> </td> <td><p></p></td> <td><p> <?php echo $discountpercent; ?> </p></td> <td>  </td> <td align="right"><p> 
	<?php echo number_format($discount,0,",","."); ?> ,- </p></td> </tr>
	<tr> <td> <p> </p> </td> <td><p></p></td> <td><p> <?php echo 100-$discountpercent; ?> </p></td> <td>  </td> 
	<td align="right"><p> <?php echo number_format($netto,0,",","."); ?> ,- </p></td> </tr>
	<tr> <td> <p> </p> </td> <td><p></p></td> <td><p> 10 </p></td> <td>  </td> 
	<td align="right"><p> <?php echo number_format($tax,0,",","."); ?> ,- </p></td> </tr>
	<tr> <td> <p> </p> </td> <td><p></p></td> <td><p> </p></td> <td>  </td> 
	<td align="right"><p> <?php echo number_format($cost,0,",","."); ?> ,- </p></td> </tr>
	<tr> <td>  </td> <td></td> <td align="right"><p> </p></td> <td>  </td> 
	           <td align="right"><p style="padding:0.2cm 0 0 0;"> <b> <?php echo number_format($total,0,",","."); ?> ,- </b> </p></td> </tr>
		</table>
		
		<p style="float:right; font-family:'Times New Roman', Times, serif; font-size:15px; margin:1.2cm 0 0 0;"> <?php echo $podate; ?> </p> 
		<div class="clear"></div>
		
		<div style="border-top:0px solid #000; border-bottom:0px solid #000; width:210px; height:40px; margin:0.5cm 0 0 8px;">
		<p style="font-family:'Times New Roman', Times, serif; font-size:18px; margin:0; padding:9px 0 0 2.5cm;"> <b> <?php echo number_format($total,0,",","."); ?> ,- </b> </p>
		</div>  <div class="clear"></div>
		
	</div>
	
</div>

</body>
</html>
