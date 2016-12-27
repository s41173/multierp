<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title> AP-Payment - CD-00<?php echo isset($pono) ? $pono : ''; ?></title>

<style type="text/css" media="all">

	body{ font-size:0.75em; font-family:Arial, Helvetica, sans-serif; margin:0; padding:0;}
	#container{ width:21.5cm; height:11cm; border:0pt solid #000;}
	.clear{ clear:both;}
	#tablebox{ height:5.0cm; width:21cm; border:0pt solid red; float:left; margin:1.3cm 0 0 0.5cm;}
	#tablebox2{ height:0.5cm; width:21cm; border:0pt solid red; float:left; margin:0.0cm 0 0 0.5cm;}
	
	.tab1 { margin:0 0 0 0cm; border:0pt solid blue; width:100%; font-size:1.125em; }
	table.tab1 td { padding:0.1cm 0.1cm 1.5mm 0.1cm; }
	
	table.tab1 td.no { text-align:center; width:1cm; }
	table.tab1 td.name { text-align:left; width:12cm; }
	table.tab1 td.total { text-align:right; width:5.8cm; }
	
</style>

</head>

<body bgcolor="#FFFFFF" onload="window.print()">

<div id="container">
	
	<table style="float:left; margin:1.2cm 0 0 3.3cm;">
		<tr> <td> <?php echo $vendor; ?> <br /> <?php echo $ven_bank; ?> </td> </tr>
	</table>
	
	<table style="float:right; margin:1cm 0.0cm 0 0; padding:0;">
		<tr> <td style="line-height:0.5cm"> <?php echo $podate; ?> <br /> <?php echo $bank; ?> </td> </tr>
	</table>
	
	<div class="clear"></div>
	
	<div id="tablebox">
	
		<table class="tab1" border="0">
		
			<!--<tr> <td class="no"> 1 </td>  <td class="name"> Pembelian Color Merah Pelangi </td>  <td class="total"> 1.000.000 </td> </tr> -->
			
			<?php
			
				if ($items)
				{
					$i=1;
					foreach ($items as $res)
					{
						echo "
						
						 <tr> 
							<td class=\"no\"> ".$i." </td>
							<td class=\"name\"> ".$res->code."-00".$res->no." - ".$res->notes." &nbsp; - &nbsp; ".tgleng($res->dates)." </td> 
							<td class=\"total\"> ".number_format($res->amount)." </td>  
						 </tr>
						
						"; $i++;
					}
				}
			
			?>
			
		</table>
	</div>  <div class="clear"></div>
	
	<div id="tablebox2">
		<table class="tab1">
			<tr> <td class="no">  </td> 
			     <td class="name"> </td> 
				 <td class="total"> <?php echo $amount; ?> </td> 
			</tr>
		</table>
	</div> <div class="clear"></div>
	
	<div style="width:14.5cm; height:1cm; border:0pt solid red; margin:0.3cm 0 0 2.5cm; padding:0;">  
		<p style="padding:0; margin:0; float:left; line-height:0.6cm"> <?php echo $terbilang; ?> </p>
	</div>
	
</div>

</body>
</html>
