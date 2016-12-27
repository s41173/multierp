<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title> Sales Order - SO-00<?php echo isset($pono) ? $pono : ''; ?></title>

<style type="text/css" media="all">
	
	@page{ size:auto; margin:0; margin: 25mm 25mm 25mm 25mm;  }
	body{ font-size:1em; font-family:"Times New Roman", Times, serif; margin:0; padding:0;}
	#container{ width:18.5cm; height:14.7cm; border:0pt solid #000;}
	#kolkiri{ width:3cm; height:13cm; border:0pt solid red; float:left; margin:0.3cm 0 0cm 0.5cm;}
	#kolkanan{ height:13cm; width:13.5cm; border:0pt solid blue; float:right; margin:0.3cm 1cm 0cm 0cm;}

	.tab1 { margin:0cm 0 0 3cm; border:0pt solid blue; width:11cm;}
	table.tab1 td { padding:0 0 0.0cm 0; margin:0; }
	
	.tab2 { margin:0.0cm 0 0 2.2cm; border:0pt solid red; width:12cm;}
	table.tab2 td { padding:0cm 0 0.1cm 0; }
	
	table.tab2 td.right { text-align:right; }
	
	.clear{ clear:both;}
	
</style>

</head>

<body bgcolor="#FFFFFF" onload="window.print()">

<div id="container">
	
	<div id="kolkiri">  </div>
	
	<div id="kolkanan"> 
	
<p style="padding:0.1cm 0 0.0cm 1.5cm; margin:0 0 0.3cm 0; float:left;"> <?php echo $pono; ?> </p>
<div class="clear"></div>

<div style="border:0pt solid red; width:auto; height:5.9cm;">
<table class="tab1" border="0">
<!--	<tr> <td> 07 / I / P / 2013 </td> </tr> -->
	<tr> <td> &nbsp;&nbsp; * &nbsp; <?php echo $customer; ?> &nbsp; * </td> </tr>
	<tr> <td> <p style="padding:0 0 0 0; margin:0.5cm 0 0 0; line-height:0.8cm; height:1.5cm;"> 
	     # &nbsp; <?php echo $terbilang; ?> &nbsp; # </p> </td> </tr>
	<tr> <td> <p style="padding:0.0cm 0 0 0.2cm; margin:0.4cm 0 0 0; line-height:0.6cm;"> <?php echo $notes; ?> </p> </td> </tr>
</table>
</div>
		
		<div class="clear"></div>
		
		<table class="tab2" border="0" width="11.7cm">
		
			<tr> <td> 
			     <p style="padding:0; margin:0; float:left; width:0.4cm; text-align:right;"> <?php echo $size; ?> </p> 
				 <sup style="float:left; padding:0; margin:0; font-size:0.7em;"><?php echo $sup; ?></sup>
				 <p style="padding:0 0 0 0; margin:0 0 0 2.1cm; float:left;"> <?php echo $coloumn; ?> </p>
				 <p style="padding:0 0 0 0; margin:0 0 0 2.5cm; float:left;"> <?php echo $price ?> ,- </p> 
				 </td> <td class="right"> <?php echo number_format($bruto,0,",","."); ?> ,-  </td> </tr>
				 
			<tr> <td> <p style="padding:0; margin:0; text-align:right; width:0.4cm; float:left;"> <?php echo $discountpercent; ?> </p> 
			<p style="padding:0 0 0 1.5cm; margin:0; float:left;"> <?php echo $disdesc; ?> </p> </td> 
			<td class="right"> <?php echo number_format($discount,0,",","."); ?> ,- </td> </tr>
			
			<tr> <td> <p style="padding:0; margin:0; text-align:right; width:0.4cm;"> <?php echo 100-$discountpercent; ?> </p> </td> 
			<td class="right"> <?php echo number_format($netto,0,",","."); ?> ,- </td> </tr>
			<tr> <td> <p style="padding:0; margin:0; text-align:right; width:0.4cm;"> 10 </p> </td> 
			      <td class="right"> <p style="padding:0.1cm 0 0.1cm 0; margin:0;"> <?php echo number_format($tax,0,",","."); ?> ,- </p> </td> </tr>
			<tr> <td>  </td> <td class="right"> <?php echo number_format($cost,0,",","."); ?> ,- </td> </tr>
			<tr> <td>  </td> <td class="right"> <p style="padding:0.1cm 0 0 0; margin:0;"> <?php echo number_format($total,0,",","."); ?> ,- </p> </td> </tr>
			<tr> <td>  </td> <td class="right"> <p style="padding:0.6cm 0 0 0; margin:0;"> <?php echo $podate; ?> </p> </td> </tr>
		
		</table>
		
		<p style="float:left; font-size:1.2em; padding:1.2cm 0 0 2cm; font-weight:normal; margin:0;"> <?php echo number_format($total,0,",","."); ?> ,- </p>
		
	</div>


</div>

</body>
</html>
