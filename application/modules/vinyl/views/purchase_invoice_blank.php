<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title> PT Delica Indonesia Purchase Order - PO-00<?php echo isset($pono) ? $pono : ''; ?></title>

<style type="text/css" media="screen">

	body{ font-size:0.95em; font-family:Arial, Helvetica, sans-serif; margin:0; padding:0;}
	#container{ width:20.5cm; height:16cm; border:0pt solid #000;}
	.clear{ clear:both;}
	#tablebox{ height:4.7cm; width:19.6cm; border:0pt solid red; float:right; margin:1.6cm 0.1cm 0 0;}
	#tablebox2{ height:2.5cm; width:19.6cm; border:0pt solid blue; float:right; margin:0cm 0.1cm 0 0;}
	
	.tab1 { margin:0 0 0 0cm; border:0pt solid blue; width:100%;}
	table.tab1 td { padding:0.1cm 0.1cm 1.5mm 0.1cm; }
	
	table.tab1 td.no { text-align:center; width:1cm; }
	table.tab1 td.name { text-align:left; width:9.2cm; }
	table.tab1 td.qty { text-align:center; width:1.5cm; font-size:0.9em; padding-left:0.2cm; }
	table.tab1 td.price { text-align:right; width:3cm; padding-right:0.5cm; }
	table.tab1 td.total { text-align:right; width:4cm; }
	
</style>

<style type="text/css" media="print">

	body{ font-size:11pt; font-family:Arial, Helvetica, sans-serif; margin:0; padding:0;}
	#container{ width:20.5cm; height:16cm; border:0pt solid #000;}
	.clear{ clear:both;}
	#tablebox{ height:4.7cm; width:19.6cm; border:0pt solid red; float:right; margin:1.6cm 0.1cm 0 0;}
	#tablebox2{ height:2.5cm; width:19.6cm; border:0pt solid blue; float:right; margin:0cm 0.1cm 0 0;}
	
	.tab1 { margin:0 0 0 0cm; border:0pt solid blue; width:100%;}
	table.tab1 td { padding:0.1cm 0.1cm 1.5mm 0.1cm; }
	
	table.tab1 td.no { text-align:center; width:1cm; }
	table.tab1 td.name { text-align:left; width:9.2cm; }
	table.tab1 td.qty { text-align:center; width:1.5cm; font-size:11pt; padding-left:0.2cm; }
	table.tab1 td.price { text-align:right; width:3cm; padding-right:0.5cm; }
	table.tab1 td.total { text-align:right; width:4cm; }
	
</style>

</head>

<body bgcolor="#FFFFFF" onload="window.print()">

<div id="container">
	
	<p style="padding:2.0cm 1.0cm 0 0; margin:0; float:right;"> <?php echo isset($podate) ? $podate : ''; ?> </p> <div class="clear"></div>
	<p style="padding:1.4cm 0.5cm 0 0; margin:0; float:right; text-align:right; line-height:0.5cm;"> <?php echo isset($vendor) ? $vendor : ''; ?> <br /> 
	<?php echo isset($address) ? $address : ''; ?> - <?php echo isset($city) ? $city : ''; ?> <br /> <?php echo isset($phone) ? $phone : ''; ?> </p>
	<div class="clear"></div>
	
	<div id="tablebox">
	
		<table class="tab1" border="0">
		
<!--			<tr> <td class="no"> 1 </td> 
			     <td class="name"> Pembelian Color Merah Pelangi </td> 
				 <td class="qty"> 100 pcs </td> 
				 <td class="price"> 10.000 </td> 
				 <td class="total"> 1.000.000 </td> 
			</tr> -->

			<?php
		 	
			if ($items)
			{
				$i=1;
				foreach ($items as $res)
				{
					echo "
					
					 <tr> <td class=\"no\"> ".$i." </td> 
						 <td class=\"name\"> ".$res->name." </td> 
						 <td class=\"qty\"> ".$res->qty." ".$res->unit."</td> 
						 <td class=\"price\"> ".number_format($res->price)." </td> 
						 <td class=\"total\"> ".number_format($res->amount + $res->tax)." </td> 
					</tr>
					
					"; $i++;
				}
			}
			
		 ?>				
		</table>
	</div>
	
	<div class="clear"></div>
	
	<div id="tablebox2">
	<table class="tab1">
		<tr> <td class="no">  </td> 
			 <td class="name"> Biaya Lain </td> 
			 <td class="qty"> </td> 
			 <td class="price">  </td> 
			 <td class="total"> <?php echo number_format($cost); ?> </td> 
		</tr>
		
		<tr> <td class="no">  </td> 
			 <td class="name"> Down Payment </td> 
			 <td class="qty"> </td> 
			 <td class="price">  </td> 
			 <td class="total"> <?php echo number_format($p1); ?> </td> 
		</tr>
		
		<tr> <td class="no">  </td> 
			 <td class="name"> </td> 
			 <td class="qty"> </td> 
			 <td class="price">  </td> 
			 <td class="total"> <?php echo number_format($p2); ?> </td> 
		</tr> 
	</table>
	</div> <div class="clear"></div>
	
	<p style="padding:0.2cm 0cm 0 3.5cm; width:8cm; margin:0; float:left;"> <?php echo $desc; ?> </p>

	<p style="padding:3.2cm 7.1cm 0 0; margin:0; float:right;"> <?php echo $user; ?> </p>
	
</div>

</body>
</html>
