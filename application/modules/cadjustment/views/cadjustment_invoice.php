<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title> Contract Adjustment - COA-00<?php echo isset($pono) ? $pono : ''; ?></title>

<style type="text/css" media="all">

	body{ font-size:0.75em; font-family:Arial, Helvetica, sans-serif; margin:0; padding:0;}
	#container{ width:21cm; height:11.6cm; border:0pt solid #000;}
	.clear{ clear:both;}
	#tablebox{ width:20cm; border:0pt solid red; float:left; margin:0cm 0 0 0.4cm;}
		
	#logobox{ width:5.5cm; height:1cm; border:0pt solid blue; margin:0.8cm 0 0 0.5cm; float:left;}
	#venbox{ border:0pt solid green; margin:0.0cm 0cm 0.8cm 0.5cm; float:left; width:9.5cm;}
	#venbox2,#venbox3{ border:0pt solid green; margin:0.0cm 0.5cm 0.6cm 0.5cm; float:right; width:8.5cm;}
	#title{ text-align:center; font-size:17pt;}
	h4{ font-size:14pt; margin:0;}
</style>

    <link rel="stylesheet" href="<?php echo base_url().'js/jxgrid/' ?>css/jqx.base.css" type="text/css" />
	<script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jquery-1.11.1.min.js"></script>    
</head>

<body bgcolor="#FFFFFF"; onload="">

<div id="container">
	
    <center>
	   <div style="border:0px solid green; width:500px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> </h4>
           <p style="margin:5px; padding:0;"> <?php echo $address; ?> <br> Telp. <?php echo $phone1.' - '.$phone2; ?> <br>
               Website : <?php echo $website; ?> &nbsp; &nbsp; Email : <?php echo $email; ?> </p>
	   </div>
	</center> <hr>
	
    <p style="padding:0; font-weight:bold; font-size:1.3em; text-align:center;"> ORDER CONTRACT ADJUSTMENT </p>
    
    <div id="venbox">
	<table width="100%" style="font-size:1em; margin:0; text-align:left; font-weight:bold;">
	  <tr> <td> No </td> <td>:</td> <td> <?php echo isset($pono) ? $pono : ''; ?> </td> </tr>
      <tr> <td> Contract </td> <td>:</td> <td> <?php echo isset($contract) ? $contract : ''; ?> </td> </tr>
      <tr> <td> Doc-No </td> <td>:</td> <td> <?php echo isset($docno) ? $docno : ''; ?> </td> </tr>
      <tr> <td> Date </td> <td>:</td> <td> <?php echo $podate; ?> </td> </tr>
      <tr> <td> Notes </td> <td>:</td> <td> <?php echo isset($notes) ? $notes : ''; ?> </td> </tr>
      <tr> <td> Desc </td> <td>:</td> <td> <?php echo isset($desc) ? $desc : ''; ?> </td> </tr>

	</table>
	</div>
    
    <div id="venbox2">
	<table width="100%" style="font-size:1em; margin:0; text-align:left; font-weight:bold;">
	  <tr> <td> Amount </td> <td>: &nbsp;</td> <td align="left"> <?php echo number_format($total); ?>,- </td> </tr>
      <tr> <td> Approved </td> <td>:</td> <td> <?php echo isset($stts) ? $stts : ''; ?> </td> </tr>
      <tr> <td> User </td> <td>:</td> <td> <?php echo isset($user) ? $user : ''; ?> </td> </tr>
      <tr> <td> Log </td> <td>:</td> <td> <?php echo isset($log) ? $log : ''; ?> </td> </tr>
	</table> 
    
	</div>

    <div style="width:100%; border:0px solid #000; float:right; margin:3px 0px 0 0; border-top:1px solid #000;">
	<style>
        .sig{ font-size:11px; width:100%; float:right; text-align:center;}
        .sig td{ width:155px;}
    </style> <br />
        <table border="0" class="sig">
            <tr> <td> Approved By : </td> <td> Review By : </td> <td> Prepared By : </td> </tr>
        </table> <br> <br> <br> <br> <br> 
        
        <table border="0" class="sig">
            <tr> <td> Director </td> <td> Accounting </td> <td> (<?php echo $user; ?>) </td> </tr>
        </table>
    </div>
    
</div>

</body>
</html>

