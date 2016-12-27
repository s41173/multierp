<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title> Non Tax Sales Invoice - SO-00<?php echo isset($pono) ? $pono : ''; ?></title>

<style type="text/css" media="print">

	body{ font-size:13pt; font-family:Arial, Helvetica, sans-serif; margin:0; padding:0;}
	p{ font-size:14pt; }
	#container{ width:21cm; height:10.5cm; border:0pt solid #000; padding:0 0 0 0;}
	.clear{ clear:both;}
	
</style>

<style type="text/css" media="screen">

	body{ font-size:1.125em; font-family:Arial, Helvetica, sans-serif; margin:0; padding:0;}
	p{ font-size:1em; }
	#container{ width:23cm; height:auto; border:0pt solid #000; padding:0 0 0 0;}
	.clear{ clear:both;}
	
</style>

</head>

<body bgcolor="#FFFFFF" onload="window.print()">

<div id="container">
		
		<p style="padding:1.0cm 0 0 3.5cm; margin:0; float:left;"> <?php echo $pono; ?> </p> <div class="clear"></div>
		<p style="padding:0.8cm 0 0 8.0cm; margin:0; float:left;"> * &nbsp; <?php echo $customer; ?> &nbsp; * </p>
		<p style="padding:1.1cm 0 0 7.0cm; margin:0; float:left; width:13cm;"> # &nbsp; <?php echo $terbilang; ?> &nbsp; # </p>
		
		<div style="width:15cm; height:3.5cm; border:0pt solid red; float:left; margin:0.6cm 0 0 8cm;"> 
			<p style="padding:0; margin:0; float:left; line-height:1.2cm; height:3,0cm;"> <?php echo $notes; ?> </p>	
			<div class="clear"></div>
			<p style="padding:1.3cm 1.5cm 0 0cm; margin:0; float:right;"> Medan, <?php echo $podate; ?> </p>		
		</div> 
		<div class="clear"></div>
	
		<p style="padding:2.9cm 0 0 6.5cm; margin:0; float:left; font-size:1.2em;"> &nbsp; <b> <?php echo number_format($total,0,",","."); ?> ,- </b> </p>
		
</div>

</body>
</html>
