<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title> <?php echo $title; ?> </title>
<style media="all">
	 h1{ margin:5px; padding:0;}
	 h3{ font-size:16px;}
	.clear{clear:both;}
	.table1{ font-size:14px;}
	.table2{ font-size:14px;}
	.table3{ font-size:12px;}
	body{ font-size:15px;}
	p{ font-size:13px; margin:0; padding:0;}
	.batas{ margin-top:15px; height:5px; border-bottom:1px dotted #000000; width:100%;}
</style>
</head>

<body onLoad="">
<div style="width:750px; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">

	<div style="border:0px solid; width:90px; height:110px; margin:5px; float:left;">
		<img src="<?php echo base_url()."images/receiptlogo.png"; ?>">
	</div>
	
	<div style="width:630px; height:110px; border-bottom:1px solid #000000; float:left; margin-left:5px;">
	   <h1> <?php echo $pname; ?> <br> <?php echo $paddress; ?> TEL.<?php echo $p_phone1.'&nbsp;'.$p_city; ?>  <br> SMP-SMA-SMK.TR-SMK.BM </h1>
	</div>
	
	<center> <h3> BUKTI PEMBAYARAN PENDAFTARAN SISWA BARU / PINDAHAN <br/> &nbsp; &nbsp; &nbsp; TAHUN AJARAN <?php echo $financial; ?> </h3> </center>
	<div class="clear"></div>
	
	<div style="float:left; border:0px solid red; ">
		<table class="table1" border="0" width="514">
		  <tr>
			<td width="135">No Registrasi </td>
			<td width="10">:</td>
			<td width="249">REG-0<?php echo $noreg; ?></td>
			<td width="32">&nbsp;</td>
			<td width="66">&nbsp;</td>
		  </tr>
		  <tr>
			<td>Sudah terima dari </td>
			<td>:</td>
			<td><?php echo $name; ?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>
		  <tr>
			<td>Alamat</td>
			<td>:</td>
			<td colspan="3"><?php echo $address; ?></td>
		  </tr>
		  <tr>
			<td>Masuk Sekolah </td>
			<td>:</td>
			<td><?php echo $startdate; ?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>
		  <tr>
			<td>Tingkatan</td>
			<td>:</td>
			<td><?php echo $department ?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>
		  <tr>
			<td>Kelas / Jurusan </td>
			<td>:</td>
			<td> <?php echo $faculty; ?> </td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>
		  <tr>
			<td rowspan="6">Untuk Pembayaran </td>
			<td>&nbsp;</td>
			<td>Uang Pendaftaran </td>
			<td>Rp.</td>
			<td><div align="right"><?php echo number_format($regfee); ?></div></td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td>Uang Pembangunan</td>
			<td>Rp.</td>
			<td><div align="right"><?php echo number_format($devfee); ?></div></td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td>Uang Sekolah </td>
			<td>Rp.</td>
			<td><div align="right"><?php echo number_format($schoolfee); ?></div></td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td>Uang OSIS </td>
			<td>Rp.</td>
			<td><div align="right"><?php echo number_format($osisfee); ?></div></td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td>Uang Praktek </td>
			<td>Rp.</td>
			<td><div align="right"><?php echo number_format($practicefee); ?></div></td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td>Dan Lain-Lain </td>
			<td>Rp.</td>
			<td><div align="right"><?php echo number_format($otherfee); ?></div></td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>Jumlah</td>
			<td>Rp.</td>
			<td><div align="right"><strong><?php echo number_format($total) ?></strong></div></td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>Pembayaran I </td>
			<td>Rp.</td>
			<td><div align="right"><?php echo number_format($p1) ?></div></td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>Pembayaran II</td>
			<td>Rp.</td>
			<td><div align="right"><?php echo number_format($p2); ?></div></td>
		  </tr>
		</table>
	</div>
	
	<div style="float:right; border:0px solid green; width:180px; margin:240px 10px 5px 0px;">
		<p> <strong>Keterangan :</strong> <br>
		    <?php echo $notes ?>
		</p>
	</div>
	
	<div class="clear"></div>
	
	<div style="width:160px; float:left; border:0px solid red; margin:10px 5px 5px 0px;">
		<table border="0" width="250" class="table3">
			<tr> <td> II Payment </td> <td>:</td> <td> <?php echo $p2date; ?> </td> </tr>
			<tr> <td> Payment Type </td> <td>:</td> <td> <?php echo $payment ?> </td> </tr>
			<tr> <td> Paid Status </td> <td>:</td> <td> <?php echo $paid ?> </td> </tr>
			<tr> <td> Issued By </td> <td>:</td> <td> <?php echo "Log-".$log ?> </td> </tr>
		</table>
	</div> 
	
	<div class="clear"></div> <div class="batas"></div>
    <h3 align="left"> RINCIAN PEMBAYARAN PAKAIAN SERAGAM </h3>
	
	<div style="float:left; border:0px solid red; ">
		<table class="table1" border="0">
		  <tr>
			<td width="135">No Registrasi </td>
			<td width="10">:</td>
			<td width="138">REG-0<?php echo $noreg; ?></td>
		  </tr>
		  <tr>
			<td>Tingkatan</td>
			<td>:</td>
			<td><?php echo $department ?></td>
		  </tr>
		</table>
	</div>
	<div class="clear"></div>
	
	<div style="float:left; width:auto;">
		<table border="1" class="table2" width="520">
		  <tr>
			<td width="20">NO</td>
			<td width="95">JUMLAH STEL </td>
			<td width="116">KETERANGAN</td>
			<td width="51"><div align="center">L/P</div></td>
			<td width="104">HARGA / SERAGAM </td>
		  </tr>
		  <tr>
			<td>1.</td>
			<td><div align="center"> <?php echo $unistel; ?> </div></td>
			<td>Pakaian Seragam </td>
			<td><div align="right"> <?php echo number_format($total_uniform); ?> </div></td>
			<td><div align="right"> <?php echo number_format($unistel * $total_uniform); ?> </div></td>
		  </tr>
		  <tr>
			<td>2.</td>
			<td><div align="center"> <?php echo $practstel; ?> </div></td>
			<td>Pakaian Praktek </td>
			<td><div align="right"> <?php echo number_format($total_practice); ?> </div></td>
			<td><div align="right"> <?php echo number_format($practstel * $total_practice); ?> </div></td>
		  </tr>
		  <tr>
			<td>3.</td>
			<td><div align="center"> </div></td>
			<td> Tambahan </td>
			<td><div align="center"> </div></td>
			<td><div align="right"> <?php echo number_format($add); ?> </div></td>
		  </tr>
          <tr>
			<td>4.</td>
			<td><div align="center"> <?php echo $scoutstel; ?> </div></td>
			<td> Pramuka </td>
			<td><div align="right"> <?php echo number_format($total_scout); ?> </div></td>
			<td><div align="right"> <?php echo number_format($scoutstel*$total_scout); ?> </div></td>
		  </tr>
		</table>
		
		<table width="210" border="1" align="right" class="table2">
		  <tr> <td width="63"><div align="center">Jumlah</div></td> <td width="131"><div align="right"><strong>Rp. <?php echo number_format($unitotal); ?> </strong></div></td>  </tr>
       </table>
	</div>
	
	<div style="width:420px; float:left; border:0px solid red; margin-top:20px;">
		<p> Calon siswa/i wajib memperhatikan bukti pembayaran ini waktu masuk sekolah.</p>
		<table class="table3">
			<tr> <td align="center"> NB: </td> 
			<td> Uang yang telah dibayar tidak <br/> dapat dikembalikan dengan <br/> alasan apapun. </td> </tr>
		</table> <br/>
		<p style="font-size:11px; font-style:italic; "> *) Form is printed by system no signature required. </p>
		<input type="button" value="Print" onClick="window.print()" />
	</div>

	<div style="width:200px; float:right; border:0px solid red; margin:24px 5px 5px 5px;">
		  <p align="center">Medan, <?php echo date('d-F-Y'); ?></p> <br/> <br/> <br/> <br/> <br/>
		  <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(___________________)  </p>
	</div>
	
</div>

</body>
</html>
