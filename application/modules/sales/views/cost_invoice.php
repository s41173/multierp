<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title> PT Delica Indonesia Purchase Order - PO-00<?php echo isset($pono) ? $pono : ''; ?></title>
<style media="all">

    #container{ width:20.5cm; font-family:Arial, Helvetica, sans-serif; font-size:12px; border:0px solid red; }
	#logo { margin:0 0 0 75px;}
	#logotext{ font-size:1em; text-align:center; margin:0; }
	p { margin:0; padding:0; font-size:1.05em;}
	#pono{ font-size:1.4em; padding:0; margin:0 5px 10px 0; text-align:left;}
	
	table.product
	{ border-collapse:collapse; width:100%; margin-bottom:5px; }
	
	table.product,table.product th
	{	border: 1px solid black; font-size:1.05em; font-weight:bold; padding:3px 0 3px 0; }
	
	table.product,table.product td
	{	border: 1px solid black; font-size:1.05em; font-weight:normal; padding:3px 0 3px 0; text-align:center; }
	
	table.product td.left { text-align:left; padding:3px 5px 3px 10px; width:15cm; }
	table.product td.right { text-align:right; padding:3px 10px 3px 5px; width:4cm; }
	
	#ptype{ font-size:8px; margin:0.5cm 0 0 0; font-family:Arial, Helvetica, sans-serif; text-transform:uppercase;}
	
</style>
</head>

<body onLoad="window.print()">

<div id="container">

	<div style="border:0px solid #000; width:10.8cm; height:3.8cm; float:left;">
		<img id="logo" align="middle" width="250" src="<?php echo isset($logo) ? $logo : ''; ?>"> <br>
		<p id="logotext"> 
		  <?php echo $paddress; ?> <br> Kotamadya Medan - <?php echo $p_zip; ?> &nbsp; Telp. (061) 80026062 <br>
		  E-Mail : info@delicaindonesia.com - Website : www.delicaindonesia.com
		</p>
		<p style="float:left; margin:0; padding:20px 0 0 5px; font-weight:bold;"> NPWP : <?php echo $p_npwp; ?> </p>
	</div>
	
	<div style="border:0px solid; float:right;">
		
		<h4 id="pono"> <?php echo $pono; ?> &nbsp; <?php echo 'IDR'; ?> </h4>
		<p> Medan, &nbsp; <?php echo isset($podate) ? $podate : ''; ?> </p> <br>
		<p> To, </p> 
		<p style="margin:8px 0 0 0;"> <b> <?php echo isset($customer) ? $customer : ''; ?> </b> </p>
		<p> <?php echo isset($address) ? $address : ''; ?> - <?php echo isset($city) ? $city : ''; ?> </p> 
		<p> <?php echo isset($phone) ? $phone : ''; ?> </p>
		
	</div>
	
	<div style="clear:both; "></div>
	
	<h2 style="font-size:1.4em; font-weight:normal; text-align:center; margin:5px 0px 5px 0px; padding:0 0 0 25px;"> SALES INVOICE </h2> 
    <div style="clear:both; "></div> 
	
	<div style="margin:10px 0px 10px 0px; border-bottom:1px dotted #000;">
		<?php //echo ! empty($table) ? $table : ''; ?>
		
		<table class="product">

		 <tr> 
			<th> No </th> <th> Keterangan </th> <th> Jumlah </th>
		 </tr>
		 
		 <?php
		 	
			/*if ($items)
			{
				$i=1;
				foreach ($items as $res)
				{
					echo "
					
					 <tr> 
						<td> ".$i." </td>
						<td class=\"left\"> ".$res->name." </td> 
						<td> ".$res->qty.' '.$res->unit." </td> 
						<td class=\"right\"> ".number_format($res->price)." </td> 
						<td class=\"right\"> ".number_format($res->tax)." </td> 
						<td class=\"right\"> ".number_format($res->amount + $res->tax)." </td>   
					 </tr>
					
					"; $i++;
				}
			}*/
			
		 ?>
		 
		 
		 <tr> 
			<td> 1 </td>
			<td class="left"> Dodol Perbaungan Medan Barat </td> 
			<td class="right"> 11.000,- </td>   
		 </tr> 

		 <tr> <td> 2 </td> <td class="left"> Materai </td> <td class="right"> <?php echo number_format($cost); ?>,- </td> </tr>
		 <tr> <td> </td> <td class="right"> Total : </td> <td class="right"> </td> </tr>
         
         <tr> <td> </td> <td class="right"> # Satu Juta Lima Ratus Empat Puluh Lima Ribu Tiga Ratus Rupiah # </td>
         <td class="right"> <b> 1.000.000,- </b> </td>
          </tr>
			
		</table>
		
		<div style="float:left; width:11.5cm; border:0px solid #000;">  
			<p style="margin:0; padding:5px 0 0 0;"> Description : <?php echo $desc; ?> </p>
			<p style="margin:0; padding:5px 0 0 0;"> Status / Log : <?php echo $approve.'/'.$status.'/'.$log; ?> </p>
            
            <p id="ptype">
            	- Pembayaran Dengan Giro / Cek Harus Mencamtumkan Nama : <br>
                &nbsp; <b> PT.DELICA INDONESIA, BANK PERMATA NO.REK : 300.000.0000 </b> <br>
                - Pembayaran Dianggap Sah Apabila BG Giro / Cek Cair Di Rekening Kami
            </p>
		</div>
        
		
		<div style="float:right;">
			
			<table>
				<br> <br> <br> <br> <br>
				<p style="text-align:right;"> ( <?php echo $user; ?> ) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; (_______________) </p>
				<p> &nbsp; Sales  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  Manager </p>
			</table>
			<br>
		</div>
		
		<div style="clear:both; ">
		
	</div>	
	
</div>

</body>
</html>
