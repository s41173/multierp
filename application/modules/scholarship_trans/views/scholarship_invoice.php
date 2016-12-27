<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> Scholarship Receipt - SCT-00<?php echo isset($pono) ? $pono : ''; ?> </title>
</head>

<style type="text/css">
	body{ font-family:Arial, Helvetica, sans-serif; font-size:11pt;}
	#container{ height:auto; width:100%; border:0px solid red; margin:20px;}
	.clear{ clear:both;}
</style>

<body>
<div id="container">
<h2 align="center" style="padding:0; margin:0;"> <?php echo isset($company) ? $company : ''; ?> </h2>
<p align="center" style="font-weight:bold; font-size:12pt; margin-top:10px; padding:0"> <?php echo $address; ?> <br /> 
Telp. <?php echo $phone1.' - '.$phone2; ?>, Kode Pos 20514 
<br /> MEDAN </p>
<hr />
<h3 align="center"> <u> SURAT PEMBERIAN BANTUAN BELAJAR ( SPBB ) </u> </h3>
<p align="center"> Nomor : <?php echo $letterno; ?> </p>
<br />
<p> Pada hari ini senin tanggal <?php echo $tanggal; ?> bulan <?php echo $bulan; ?> tahun <?php echo $tahun; ?>, yang bertanda tengan dibawah ini: </p> <br />

<table>
	<tr> <td> Nama </td> <td>:</td> <td> <b> <?php echo $studentname; ?> </b> </td> </tr>
    <tr> <td> NIS </td> <td>:</td> <td> <?php echo $nis; ?> </td> </tr>
    <tr> <td> Department </td> <td>:</td> <td> <?php echo $dept; ?> </td> </tr>
	<tr> <td> Kelas </td> <td>:</td> <td> <?php echo $grade; ?> </td> </tr>
    <tr> <td> Alamat </td> <td>:</td> <td> <?php echo $saddress; ?> </td> </tr>
    <tr> <td> Wali Siswa </td> <td>:</td> <td> <?php echo $teacher; ?> </td> </tr>
    <tr> <td> No. KTP </td> <td>:</td> <td>  </td> </tr>
</table>
<br /> <br />

<p> Telah diberikan bantuan belajar berupa : </p>

<table>
	<tr> <td>1.</td> <td> Tahun Ajaran </td> <td>:</td> <td> <?php echo $year; ?> </td> </tr>
    <tr> <td>2.</td> <td> Jenis Bantuan </td> <td>:</td> <td> <?php echo $scholarship; ?> </td> </tr>
    <tr> <td>3.</td> <td> Terhitung Sejak </td> <td>:</td> <td> <?php echo $start; ?> </td> </tr>
	<tr> <td>4.</td> <td> Sampai Dengan </td> <td>:</td> <td> <?php echo $end; ?> </td> </tr>
    <tr> <td>5.</td> <td> Jumlah </td> <td>:</td> <td> <?php echo number_format($amount); ?>,- </td> </tr>
</table>

<p> Demikianlah surat pemberian bantuan belejar ini diperbuat agar dapat dipergunakan sebagai mana mestinya, <br /> 
pemberian bantuan ini dapat dibatalkan secara sepihak oleh pihak sekolah. Apabila terdapat tindakan penyimpangan oleh siswa yang dinyatakan tidak pantas oleh sekolah. </p> <br />

<table width="100%" style="text-align:center; font-size:11pt;">
	<tr> <td> Diterima Oleh, </td> <td> Diketahui Oleh, </td> <td> Disetujui Oleh, </td> </tr> 
    <tr> <td> <br /> <br /> <br /> <br /> (.......................) <br /> Wali Siswa </td> 
         <td> <br /> <br /> <br /> <br /> (.......................) <br /> Koordinator Sekolah <br /> <?php echo $coordinator; ?> </td> 
         <td> <br /> <br /> <br /> <br /> (.......................) <br /> Ketua Yayasan <br /> <?php echo $chairman; ?> </td> 
    </tr> 
</table>
<div class="clear"></div> <br />
<hr />
<p style="text-align:right; font-size:8pt; font-style:italic; margin-right:10px;"> Log : <?php echo $log; ?> / <?php echo $stts; ?> </p>
</div>
</body>
</html>

