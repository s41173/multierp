<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title> Faktur Pajak - <?php echo isset($pono) ? $pono : ''; ?></title>
<style media="all">

	body{ font-family: Arial, Helvetica, sans-serif; font-size:1em;}
	p { margin:0; padding:0; font-size:1em; font-family: Arial, Helvetica, sans-serif;}
	.clear{ clear:both;}
	.row{ height:30px; border-bottom:1px solid #000; width:100%;}
	.row p{ font-size:1.05em; padding:7px 0 0 15px;}
	.bigrow{ height:80px; border-bottom:1px solid #000; width:100%; }
	.bigrow table{ font-size:1.05em; margin:15px 0 0 13px;}
	.datarow{ height:auto; border-bottom:1px solid #000; width:100%;}
	.datacontent{ height:8cm; width:100%;}
	.titlebox{ height:50px; border-right:1px solid #000; border-bottom:1px solid #000; float:left; text-align:center; }
	.titleboxright{ height:50px; float:left; border-bottom:1px solid #000; text-align:center; }
	.titlebox p, .titleboxright p { font-size:1.05em; font-weight:normal; padding:0;}
	
	.contentbox{ height:25px; border-right:1px solid #000; border-bottom:0px solid #000; float:left; text-align:center; }
	.contentboxright{ height:25px; float:left; border-bottom:0px solid #000; text-align:center; }
	.contentbox p, .contentboxright p { font-size:1.05em; font-weight:normal; padding:0;}
	
	.bigrow1{ height:280px; border-bottom:0px solid #000; width:100%; }
	
	
	 table.tbl-operation { margin:0px 0 10px 0;}
					  
	  table.tbl-operation td {
		border: 1px solid #000;
		line-height: 26px;
		padding: 0 10px;
		text-align: center;
		font-size:0.8em; font-family:Arial, Helvetica, sans-serif;
	}

		td {
			display: table-cell;
			padding: 1px;
			text-align: inherit;
			vertical-align: inherit;
		}
	
	table.tbl-operation td.left { text-align:left;}
	
</style>
</head>

<body onLoad="window.print()">

<div style="width:750px;">
<table border="0" style="font-size:8px; float:right; width:265px;">
	<tr> <td style="width:70px;"> Lembar Ke-3 : </td> <td colspan="2" style="line-height:0px; padding:0;"> Untuk Arsip </td> </tr>
</table>
<div class="clear"></div>
<h2 style="padding:0; margin:0 0 5px 0; text-align:center; font-size:20px;"> FAKTUR PAJAK </h2>
</div>

<div style="width:750px; font-family:Arial, Helvetica, sans-serif; font-size:12px; border:1px solid #000;">

	<div class="row"> <p> Kode dan No Seri Faktur Pajak : </p> </div>
	<div class="row"> <p> Pengusaha Kena Pajak &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : </p> </div>
	
	<div class="bigrow">
		<table>
			<tr> <td style="width:140px;"> Nama </td> <td>:</td> <td> <b> <?php echo $name; ?> </b> </td> </tr>
			<tr> <td> Alamat </td> <td>:</td> <td> <b> JL.B.ZEIN HAMID NO.21-A, TITI KUNING-MEDAN JOHOR, MEDAN-20146 </b> </td> </tr>
			<tr> <td> N.P.W.P </td> <td>:</td> <td> <b> <?php echo $npwp; ?> </b> </td> </tr>
		</table>
	</div>
	
	<div class="row"> <b> <p> Pembeli Barang Kena Pajak/Penerima Jasa Kena Pajak </p> </b> </div>
	
	<div class="bigrow">
		<table>
			<tr> <td style="width:140px;"> Nama </td> <td>:</td> <td> <b> <?php echo $c_name; ?> </b> </td> </tr>
			<tr> <td> Alamat </td> <td>:</td> <td> <b> <?php echo $c_address; ?>, <?php echo $c_city; ?>-<?php echo $c_zip; ?> </b> </td> </tr>
			<tr> <td> N.P.W.P </td> <td>:</td> <td> <b> <?php echo $c_npwp; ?> </b> </td> </tr>
		</table>
	</div>
	
	<div class="datarow">
	
	<!-- title -->
		<div class="titlebox" style="width:45px;"> <p style="padding:10px 0 0 0;"> <b> No. Urut </b> </p> </div>
		<div class="titlebox" style="width:430px;"> <p style="padding:20px 0 0 0;"> <b> Nama Barang Kena Pajak/Jasa Kena Pajak </b> </p> </div>
		<div class="titleboxright" style="width:273px;"> <p style="padding:3px 0 0 0; font-size:12px;"> <b> Harga Jual/Penggantian/Uang <br> Muka/Termin <br> (Rp) </b> </p> </div>
	<!-- title -->
	
	<div class="clear"></div>
	
	<div class="datacontent">
    <!-- data content -->
	
	<div class="contentbox" style="width:45px;"> <p style="padding:7px 0 0 0;"> 1 </p> </div>
	<div class="contentbox" style="width:430px;"> <p style="padding:7px 0 0 15px; text-align:left;"> <?php echo $p_name; ?> </p> </div>
	<div class="contentboxright" style="width:273px;"> <p style="padding:7px 15px 0 0; text-align:right;"> <?php echo number_format($bruto,0,",","."); ?> ,- </p> </div>
    
    <div class="contentbox" style="width:45px;"> <p style="padding:7px 0 0 0;"> </p> </div>
	<div class="contentbox" style="width:430px;"> <p style="padding:7px 0 0 15px; text-align:left;"> </p> </div>
	<div class="contentboxright" style="width:273px;"> <p style="padding:7px 15px 0 0; text-align:right;"> </p> </div>
    
    <div class="contentbox" style="width:45px;"> <p style="padding:7px 0 0 0;"> </p> </div>
	<div class="contentbox" style="width:430px;"> <p style="padding:7px 0 0 15px; text-align:left;"> </p> </div>
	<div class="contentboxright" style="width:273px;"> <p style="padding:7px 15px 0 0; text-align:right;"> </p> </div>
    
    <div class="contentbox" style="width:45px;"> <p style="padding:7px 0 0 0;"> </p> </div>
	<div class="contentbox" style="width:430px;"> <p style="padding:7px 0 0 15px; text-align:left;"> </p> </div>
	<div class="contentboxright" style="width:273px;"> <p style="padding:7px 15px 0 0; text-align:right;"> </p> </div>
    
    <div class="contentbox" style="width:45px;"> <p style="padding:7px 0 0 0;"> </p> </div>
	<div class="contentbox" style="width:430px;"> <p style="padding:7px 0 0 15px; text-align:left;"> </p> </div>
	<div class="contentboxright" style="width:273px;"> <p style="padding:7px 15px 0 0; text-align:right;"> </p> </div>
    
    <div class="contentbox" style="width:45px;"> <p style="padding:7px 0 0 0;"> </p> </div>
	<div class="contentbox" style="width:430px;"> <p style="padding:7px 0 0 15px; text-align:left;"> </p> </div>
	<div class="contentboxright" style="width:273px;"> <p style="padding:7px 15px 0 0; text-align:right;"> </p> </div>
    
    <div class="contentbox" style="width:45px;"> <p style="padding:7px 0 0 0;"> </p> </div>
	<div class="contentbox" style="width:430px;"> <p style="padding:7px 0 0 15px; text-align:left;"> </p> </div>
	<div class="contentboxright" style="width:273px;"> <p style="padding:7px 15px 0 0; text-align:right;"> </p> </div>
    
    <div class="contentbox" style="width:45px;"> <p style="padding:7px 0 0 0;"> </p> </div>
	<div class="contentbox" style="width:430px;"> <p style="padding:7px 0 0 15px; text-align:left;"> </p> </div>
	<div class="contentboxright" style="width:273px;"> <p style="padding:7px 15px 0 0; text-align:right;"> </p> </div>
    
    <div class="contentbox" style="width:45px;"> <p style="padding:7px 0 0 0;"> </p> </div>
	<div class="contentbox" style="width:430px;"> <p style="padding:7px 0 0 15px; text-align:left;"> </p> </div>
	<div class="contentboxright" style="width:273px;"> <p style="padding:7px 15px 0 0; text-align:right;"> </p> </div>
    
    <div class="contentbox" style="width:45px;"> <p style="padding:7px 0 0 0;"> </p> </div>
	<div class="contentbox" style="width:430px;"> <p style="padding:7px 0 0 15px; text-align:left;"> </p> </div>
	<div class="contentboxright" style="width:273px;"> <p style="padding:7px 15px 0 0; text-align:right;"> </p> </div>
    
    <div class="contentbox" style="width:45px;"> <p style="padding:7px 0 0 0;"> </p> </div>
	<div class="contentbox" style="width:430px;"> <p style="padding:7px 0 0 15px; text-align:left;"> </p> </div>
	<div class="contentboxright" style="width:273px;"> <p style="padding:7px 15px 0 0; text-align:right;"> </p> </div>
    
    <div class="contentbox" style="width:45px;"> <p style="padding:7px 0 0 0;"> </p> </div>
	<div class="contentbox" style="width:430px;"> <p style="padding:7px 0 0 15px; text-align:left;"> </p> </div>
	<div class="contentboxright" style="width:273px;"> <p style="padding:7px 15px 0 0; text-align:right;"> </p> </div>

    
    <!-- data content -->
    </div>
	

	<div class="contentbox" style="width:476px; border-top:1px solid #000;"> 
	<p style="padding:5px 0 0 15px; text-align:left;"> <b> Harga Jual/Penggantian/Uang Muka/Termin *) </b> </p> </div>
	<div class="contentboxright" style="width:273px; border-top:1px solid #000;"> <p style="padding:5px 15px 0 0; text-align:right;"> 
	<?php echo number_format($bruto,0,",","."); ?> ,- </p> </div>
	
	
	<div class="contentbox" style="width:476px; border-top:1px solid #000;"> 
	<p style="padding:5px 0 0 15px; text-align:left;"> <b> Dikurangi Potongan Harga </b> </p> </div>
	<div class="contentboxright" style="width:273px; border-top:1px solid #000;"> <p style="padding:5px 15px 0 0; text-align:right;"> 
	<?php echo number_format($discount,0,",","."); ?> ,- </p> </div>
	
	<div class="contentbox" style="width:476px; border-top:1px solid #000;"> 
	<p style="padding:5px 0 0 15px; text-align:left;"> <b> Dikurangi Uang Muka yang telah diterima </b> </p> </div>
	<div class="contentboxright" style="width:273px; border-top:1px solid #000;"> <p style="padding:5px 15px 0 0; text-align:right;"> 
	<?php echo number_format($p1,0,",","."); ?> ,- </p> </div>
	
	<div class="contentbox" style="width:476px; border-top:1px solid #000;"> 
	<p style="padding:5px 0 0 15px; text-align:left;"> <b> Dasar Pengenaan Pajak </b> </p> </div>
	<div class="contentboxright" style="width:273px; border-top:1px solid #000;"> <p style="padding:5px 15px 0 0; text-align:right;"> 
	<?php echo number_format($netto,0,",","."); ?> ,- </p> </div>
	
	<div class="contentbox" style="width:476px; border-top:1px solid #000;"> 
	<p style="padding:5px 0 0 15px; text-align:left;"> <b> PPN = 10% Dasar Pengenaan Pajak </b> </p> </div>
	<div class="contentboxright" style="width:273px; border-top:1px solid #000;"> <p style="padding:5px 15px 0 0; text-align:right;"> 
	<?php echo number_format($tax,0,",","."); ?> ,- </p> </div>
	
	<div class="clear"></div>
	</div> 
	
	<!-- Pajak Penjualan Atas Barang Mewah -->
	<div class="bigrow1"> 
		
		<p style="text-align:left; font-weight:bold; padding:35px 0 0 15px; margin:0; font-size:13px;"> Pajak Penjualan Atas Barang Mewah </p>
		
		<div style="width:325px; height:196px; border:0px solid red; margin:10px 0 0 15px; float:left;"> 
			<table border="0" cellpadding="0" cellspacing="0" class="tbl-operation" width="100%">
			
				<tr> <td style="width:55px;"> <b> TARIF </b>  </td> <td> <b> DPP </b> </td> <td> <b> PPnBM </b> </td> </tr>
				<tr> <td style="width:55px;"> ........% </td> <td> ........% </td> <td> Rp. .................... </td>  </tr>
				<tr> <td style="width:55px;"> ........% </td> <td> ........% </td> <td> Rp. .................... </td>  </tr>
				<tr> <td style="width:55px;"> ........% </td> <td> ........% </td> <td> Rp. .................... </td>  </tr>
				<tr> <td style="width:55px;"> ........% </td> <td> ........% </td> <td> Rp. .................... </td>  </tr>
				<tr> <td style="width:55px;"> ........% </td> <td> ........% </td> <td> Rp. .................... </td>  </tr>
				<tr> <td colspan="2" class="left"> <b> Jumlah </b> </td> <td> Rp. .................... </td>  </tr>
			
			</table>
		</div>
		
		<p style="padding:20px 0px 0 145px; font-size:12px; font-weight:bold; float:left;"> Medan, tanggal : &nbsp; <?php echo date('d  M  Y'); ?> </p> <br>
		
		<p style="padding:135px 0px 0 145px; font-size:18px; font-weight:normal; float:left;"> <u> <?php echo $cp; ?> </u> </p>
	
	</div>
		
</div> <div class="clear"></div>
<p style="font-family:Arial, Helvetica, sans-serif; font-size:13px; padding:5px 0 0 15px;"> *) Coret yang tidak perlu </p>

</body>
</html>
