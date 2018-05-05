<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title> Sales Order - SO-00<?php echo isset($pono) ? $pono : ''; ?></title>

<style type="text/css" media="print">
	
		@page{ size:auto; margin:0;  }
	body{ font-size:11pt; font-family:Arial, Helvetica, sans-serif; margin:0; padding:0;}
	#container{ width:18.5cm; height:14.7cm; border:0pt solid #000;}
	#kolkiri{ width:3cm; height:13cm; border:0pt solid red; float:left; margin:0.3cm 0 0cm 0.5cm;}
	#kolkanan{ height:13cm; width:13.5cm; border:0pt solid blue; float:right; margin:0.8cm 1.1cm 0cm 0cm;}

	.tab1 { margin:0cm 0 0 4cm; border:0pt solid blue; width:11cm;}
	table.tab1 td { padding:0 0 0.0cm 0; margin:0; }
	
	.tab2 { margin:0.0cm 0 0 1.7cm; border:0pt solid red; width:12.3cm;}
	table.tab2 td { padding:0cm 0 0.1cm 0; }
	
	table.tab2 td.right { text-align:right; }
	
	.clear{ clear:both;}
	
</style>

<style type="text/css" media="screen">
	
	@page{ size:auto; margin:0;  }
	body{ font-size:11pt; font-family:Arial, Helvetica, sans-serif; margin:0; padding:0;}
	#container{ width:18.5cm; height:14.7cm; border:0pt solid #000;}
	#kolkiri{ width:3cm; height:13cm; border:0pt solid red; float:left; margin:0.3cm 0 0cm 0.5cm;}
	#kolkanan{ height:13cm; width:13.5cm; border:0pt solid blue; float:right; margin:0.8cm 1.1cm 0cm 0cm;}

	.tab1 { margin:0cm 0 0 4cm; border:0pt solid blue; width:11cm;}
	table.tab1 td { padding:0 0 0.0cm 0; margin:0; }
	
	.tab2 { margin:0.0cm 0 0 1.7cm; border:0pt solid red; width:12.3cm;}
	table.tab2 td { padding:0cm 0 0.1cm 0; }
	
	table.tab2 td.right { text-align:right; }
	
	.clear{ clear:both;}
	
</style>

</head>

<body bgcolor="#FFFFFF" onload="window.print()">

<div id="container">
	
	<div id="kolkiri">  </div>
	
	<div id="kolkanan"> 
	
<p style="padding:0.1cm 0 0.0cm 2.5cm; margin:0 0 0.3cm 0; float:left;"> <?php echo $pono; ?> </p>
<div class="clear"></div>

<div style="border:0pt solid red; width:auto; height:10.5cm;">
<table class="tab1" border="0">
<!--	<tr> <td> 07 / I / P / 2013 </td> </tr> -->
	<tr> <td> &nbsp;&nbsp; * &nbsp; <?php echo $customer; ?> &nbsp; * </td> </tr>
	<tr> <td> <p style="padding:0 0 0 0; margin:0.5cm 0 0 0; line-height:0.8cm; height:1.5cm;"> 
	     # <?php echo $terbilang; ?> # </p> </td> </tr>
	<tr> <td> <p style="padding:0.0cm 0 0 0.2cm; margin:0.7cm 0 0 0; line-height:1cm; border-bottom:0pt solid #000; height:5.8cm"> <?php echo $notes; ?> </p> </td> </tr>
	<tr> <td class=""> <p style="padding:0.6cm 0 0 7.5cm; margin:0;"> <?php echo $podate; ?> </p> </td> </tr>
</table>
</div>
		
		<div class="clear"></div>
		<p style="float:left; font-size:14pt; padding:1.2cm 0 0 3.5cm; font-weight:normal; margin:0;"> <?php echo number_format($total,0,",","."); ?> ,- </p>
		
	</div>
</div>

</body>
</html>
