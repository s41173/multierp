<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title> Faktur Pajak - SO-00<?php echo isset($pono) ? $pono : ''; ?></title>

<style media="print">

	@page{ size:auto; margin:0;  }
	body{ font-family: Arial, Helvetica, sans-serif; font-size:9pt;}
	p {padding:0; margin:0; float:left; font-size:10.5pt; font-family: Arial, Helvetica, sans-serif;}
	.clear{ clear:both;}
	
	.container{ width:23cm; height:30cm; border:0px solid #000;}
	
	table.tabdata td.no { text-align: center; padding:3px 0cm 3px 0.5cm; width:1cm; }
	table.tabdata td.name { text-align:left; padding:3px 5px 3px 0.5cm; width:14cm; }
	table.tabdata td.amount { text-align:right; padding:3px 0.5cm 3px 5px; width:7cm; }
	
</style>

<style media="screen">

	body{ font-family: Arial, Helvetica, sans-serif; font-size:0.75em;}
	p {padding:0; margin:0; float:left; font-size:0.875em; font-family: Arial, Helvetica, sans-serif;}
	.clear{ clear:both;}
	
	.container{ width:23cm; height:30cm; border:0px solid #000;}
	
	table.tabdata td.no { text-align: center; padding:3px 0cm 3px 0.5cm; width:1cm; }
	table.tabdata td.name { text-align:left; padding:3px 5px 3px 0.5cm; width:14cm; }
	table.tabdata td.amount { text-align:right; padding:3px 0.5cm 3px 5px; width:7cm; }
	
</style>

</head>

<body onLoad="window.print();">

<div class="container">
	
	<p style="margin-left:9.0cm; margin-top:3.4cm; font-size:15pt; float:left;"> <?php echo $tax_serial; ?> </p> <div class="clear"></div>
<!--	<p style="margin-left:8cm; margin-top:0.5cm; font-size:15pt; float:left;">  </p> <div class="clear"></div> -->
	<p style="margin-left:7.5cm; margin-top:6.8cm; font-size:14pt; float:left; line-height:0.5cm;"> <?php echo $c_name; ?> </p> <div class="clear"></div>
	<p style="margin-left:7.5cm; height:1cm; margin-top:0.3cm; font-size:14pt; line-height:0.5cm; float:left;"> 
	<?php echo $c_address; ?> <?php echo $c_city; ?> <?php echo $c_zip; ?> </p> <div class="clear"></div>
	
	<p style="margin-left:7.5cm; margin-top:0.8cm; font-size:14pt; float:left;"> <?php echo $c_npwp; ?> </p> <div class="clear"></div>
	
	<div style="width:100%; height:9.5cm; margin:2.5cm 0 0 0.5cm; border-bottom:0px solid red;">
	<table class="tabdata" style="width:22cm; font-size:15pt" border="0">
		
    <tr> 
    <td class="no"> </td> <td class="name"> <?php echo $tax_notes; ?> </td> 
    <td class="amount"> <?php echo number_format($totalcost,0,",","."); ?> ,-  </td> 
    </tr>
    
    <tr>

	<td class="no"> </td> <td class="name"> <?php echo $work_desc; ?> </td> 
    <td class="amount"> <?php echo $work_cost; ?> </td> 
    
    </tr>
	
	</table>
	</div> <div class="clear"></div>
	
	<p style="margin-right:1cm; margin-top:1.3cm; font-size:14pt; float:right;"> <?php echo number_format($total,0,",","."); ?> ,- </p> <div class="clear"></div>
	<p style="margin-right:1cm; margin-top:0.5cm; font-size:14pt; float:right;"> <?php echo number_format($discount,0,",","."); ?> ,- </p> <div class="clear"></div>
	<p style="margin-right:1cm; margin-top:0.5cm; font-size:14pt; float:right;"> <?php echo number_format($p1,0,",","."); ?> ,- </p> <div class="clear"></div>
	<p style="margin-right:1cm; margin-top:0.6cm; font-size:14pt; float:right;"> <?php echo number_format($total-$discount,0,",","."); ?> ,- </p> <div class="clear"></div>
	<p style="margin-right:1cm; margin-top:0.5cm; font-size:14pt; float:right;"> <?php echo number_format($tax,0,",","."); ?> ,- </p> <div class="clear"></div>
	
	<p style="margin-right:1.7cm; margin-top:2.85cm; font-size:14pt; float:right;"> <?php echo $fakdate; ?> </p> <div class="clear"></div>
	

</div>

</body>
</html>
