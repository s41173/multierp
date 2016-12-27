<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title> School Fee Payment Status - <?php echo isset($name) ? $name : ''; ?></title>

<style type="text/css" media="all">

	body{ font-size:0.75em; font-family:Arial, Helvetica, sans-serif; margin:0; padding:0;}
	#container{ width:21cm; height:11.6cm; border:0pt solid #000;}
	.clear{ clear:both;}
	#tablebox{ height:6.45cm; width:20cm; border:0pt solid red; float:left; margin:0.1cm 0 0 0.4cm;}
	
	table.tab1
	{ border-collapse:collapse; width:100%; }
	
	table.tab1,table.tab1 th
	{	border: 1px solid #fff; font-size:9pt; font-weight:bold; padding:6px 0 6px 0; color:#fff; background-color:#000; }
	
	table.tab1,table.tab1 td
	{	border: 1px solid black; font-size:8.5pt; font-weight:normal; padding:3px; text-align:center; color:#000; background-color:#fff; }
		
	#logobox{ width:5.5cm; height:1cm; border:0pt solid blue; margin:0.8cm 0 0 0.5cm; float:left;}
	#venbox{ width:7.5cm; height:2.0cm; border:0pt solid green; margin:0.0cm 0cm 0.8cm 0.5cm; float:left;}
	#title{ text-align:center; font-size:17pt;}
</style>

</head>

<body bgcolor="#FFFFFF"; onload="window.print()">

<div id="container">

	<h2 id="title"> <?php echo isset($company) ? $company : ''; ?> </h2> <hr />
	
	
    <p style="padding:0; font-weight:bold; font-size:1.3em; text-align:center;"> KETERANGAN SISWA </p>
    
    <div id="venbox">
	<table width="100%" style="font-size:1em; margin:0; text-align:left; font-weight:bold;">
	  <tr> <td> Name </td> <td>:</td> <td> <?php echo isset($name) ? $name : ''; ?> </td> </tr>
	  <tr> <td> NIS </td> <td>:</td> <td> <?php echo isset($nis) ? $nis : ''; ?> </td> </tr>
	  <tr> <td> Department </td> <td>:</td> <td> <?php echo isset($dept) ? $dept : ''; ?> </td> </tr>
      <tr> <td> Grade / Faculty </td> <td>:</td> <td> <?php echo isset($grade) ? $grade : ''; ?> / <?php echo isset($faculty) ? $faculty : ''; ?> </td> </tr>
	</table>
	</div>
	
	<div id="tablebox">
	
	</div>  <div class="clear"></div>
	
	
</div>

</body>
</html>
