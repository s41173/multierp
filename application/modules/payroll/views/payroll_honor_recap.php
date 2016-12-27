<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title> <?php echo isset($title) ? $title : ''; ?>  </title>
<style media="all">
	table{ font-family:Tahoma; font-size:12pt;}
	h4{ font-family:"Times New Roman", Times, serif; font-size:14px; font-weight:600;}
	.clear{clear:both;}
	table th{ background-color:#EFEFEF; padding:4px 0px 4px 0px; border-top:1px solid #000000; border-bottom:1px solid #000000;}
    p{ font-family:Arial; font-size:11pt; margin:0; padding:0;}
	legend{font-family:arial; font-size:12pt; margin:0; padding:0; font-weight:600;}
	.tablesum{ font-size:13px;}
	.strongs{ font-weight:normal; font-size:10pt; border-top:0px dotted #000000; margin:0; padding:0; }
	.balance{ font-weight:normal; font-size:11pt; border-top:0px dotted #000000; margin:0; padding:0; }
	.balances{ font-weight: bold; font-size:11pt; border-top:0px dotted #000000; margin:0; padding:0; }
	.poder{ border-bottom:0px solid #000000; color:#0000FF;}
	.gross{ color:#900;}
	.net{ color:#009;}
	h4{ font-size:14pt; margin:0; font-family:Arial, Helvetica, sans-serif; text-align:center;}
	p{ font-size:10pt;}
	fieldset{ border:none; border-bottom:1px solid #000; padding:0; margin:0; }
</style>
</head>

<body onLoad="window.print()">

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0" style="font-size:9pt;">
			<tr> <td> No </td> <td> : </td> <td> <?php echo $pono; ?> </td> </tr>
			<tr> <td> Date </td> <td> : </td> <td> <?php echo $date; ?> </td> </tr>
			<tr> <td> Currency </td> <td> : </td> <td> <?php echo $cur; ?> </td> </tr>
		</table>
	</div>
	
	<div style="border:0px solid red; float:right;">
		<table border="0" style="font-size:9pt;">
			<tr> <td> Print Date </td> <td> : </td> <td> <?php echo tgleng(date('Y-m-d')); ?> </td> </tr>
            <tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
            <tr> <td> Period </td> <td> : </td> <td> <?php echo $period; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:500px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> </h4>
           <p style="margin:5px; padding:0; font-size:10pt;"> <?php echo $address; ?> <br> Telp. <?php echo $phone1.' - '.$phone2; ?> <br>
               Website : <?php echo $website; ?> &nbsp; &nbsp; Email : <?php echo $email; ?> </p>
	   </div>
	</center> <hr>
    
    <h4> Honor Recapitulation - Report </h4>
   
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:1px dotted #000000; ">
		
        <!-- SMP -->
		<fieldset> <legend> I - TINGKAT SMP </legend>
		<table border="0" width="100%">
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Honor Guru </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smp_salary); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Tunjangan Kepsek </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smp_principal); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Tunjangan PKS / Wali Kelas / Kajur </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smp_tunjangan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Honor Petugas Piket </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smp_picket); ?>,- </td> 
           </tr>
             
           <tr> 
           <td class="strongs"> </td>  <td class="strongs gross"> <b> Total </b> </td> 
           <td></td> <td></td> 
           <td class="strongs gross" align="right"> <b> <?php echo number_format($smp_total); ?>,- </b> </td> 
           </tr>
		</table>
		</fieldset> <br>
		<!-- SMP -->	
        
        <!-- SMA -->
		<fieldset> <legend> II - TINGKAT SMA </legend>
		<table border="0" width="100%">
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Honor Guru </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($sma_salary); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Tunjangan Kepsek </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($sma_principal); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Tunjangan PKS / Wali Kelas / Kajur </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($sma_tunjangan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Honor Petugas Piket </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($sma_picket); ?>,- </td> 
           </tr>
             
           <tr> 
           <td class="strongs"> </td>  <td class="strongs gross"> <b> Total </b> </td> 
           <td></td> <td></td> 
           <td class="strongs gross" align="right"> <b> <?php echo number_format($sma_total); ?>,- </b> </td> 
           </tr>
		</table>
		</fieldset> <br>
		<!-- SMA -->	
        
        <!-- STM -->
		<fieldset> <legend> III - TINGKAT SMK-TI </legend>
		<table border="0" width="100%">
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Honor Guru </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($stm_salary); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Tunjangan Kepsek </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($stm_principal); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Tunjangan PKS / Wali Kelas / Kajur </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($stm_tunjangan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Honor Petugas Piket </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($stm_picket); ?>,- </td> 
           </tr>
             
           <tr> 
           <td class="strongs"> </td>  <td class="strongs gross"> <b> Total </b> </td> 
           <td></td> <td></td> 
           <td class="strongs gross" align="right"> <b> <?php echo number_format($stm_total); ?>,- </b> </td> 
           </tr>
		</table>
		</fieldset> <br>
		<!-- STM -->	
        
        <!-- SMK-BM -->
		<fieldset> <legend> IV - TINGKAT SMK-BM </legend>
		<table border="0" width="100%">
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Honor Guru </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smk_salary); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Tunjangan Kepsek </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smk_principal); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Tunjangan PKS / Wali Kelas / Kajur </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smk_tunjangan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Honor Petugas Piket </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smk_picket); ?>,- </td> 
           </tr>
             
           <tr> 
           <td class="strongs"> </td>  <td class="strongs gross"> <b> Total </b> </td> 
           <td></td> <td></td> 
           <td class="strongs gross" align="right"> <b> <?php echo number_format($smk_total); ?>,- </b> </td> 
           </tr>
		</table>
		</fieldset> <br>
		<!-- SMK-BM -->	
        
        <!-- PRAKTEK -->
		<fieldset> <legend> Summary </legend>
		<table border="0" align="right" width="70%" style="font-size:10pt;">           
           <!-- TOTAL -->           
           <tr> 
               <td> </td> <td class="balance" align="right"> Consumption (+) </td> <td>:</td>
               <td align="right"> <?php echo number_format($consumption); ?>,-  </td> 
           </tr>
           
           <tr> 
               <td> </td> <td class="balance" align="right"> Overtime (+) </td> <td>:</td>
               <td align="right"> <?php echo number_format($overtime); ?>,-  </td> 
           </tr>
           
           <tr> 
               <td> </td> <td class="balance" align="right"> Bonus (+) </td> <td>:</td>
               <td align="right"> <?php echo number_format($bonus); ?>,-  </td> 
           </tr>
           
           <tr> 
               <td> </td> <td class="balance" align="right"> Transport (+) </td> <td>:</td>
               <td align="right"> <?php echo number_format($transport); ?>,-  </td> 
           </tr>
           
           <tr> 
               <td> </td> <td class="balance" align="right"> Loan (-) </td> <td>:</td>
               <td align="right"> <?php echo number_format($loan); ?>,-  </td> 
           </tr>
           
           <tr> 
               <td> </td> <td class="balance" align="right"> Late Charges (-) </td> <td>:</td>
               <td align="right"> <?php echo number_format($late); ?>,-  </td> 
           </tr>
           
           <tr> 
               <td> </td> <td class="balance" align="right"> Tax (-) </td> <td>:</td>
               <td align="right"> <?php echo number_format($tax); ?>,-  </td> 
           </tr>
           
           <tr> 
               <td> </td> <td class="balance" align="right"> Insurance (-) </td> <td>:</td>
               <td align="right"> <?php echo number_format($insurance); ?>,-  </td> 
           </tr>
           
           <tr> 
               <td> </td> <td class="balance" align="right"> Other Deduction (-) </td> <td>:</td>
               <td align="right"> <?php echo number_format($other); ?>,-  </td> 
           </tr>
           
           <tr> 
               <td> </td> <td class="balances" align="right"> Total </td> <td> : </td>
               <td class="balances" align="right"> <?php echo number_format($amount); ?>,-  </td> 
           </tr>
           <!-- TOTAL -->
		   
		</table>
		</fieldset>
		<!-- PRAKTEK -->
		
	</div>

	<!--SIGN-->
    
    <div style="border:0px solid red; float:right; margin:20px 30px 0px 0px;">
		<p style="text-align:center;"> Reviewed By : </p> <br>
        <p style="text-align:center;margin-top:60px; text-decoration:underline; text-transform:uppercase;"> <?php echo $director; ?> </p>
        <p style="text-align:center;"> CHAIRMAN </p>
	</div>
    
    <div style="border:0px solid red; float:right; margin:20px 45px 0px 0px;">
		<p style="text-align:center;"> Approved By : </p> <br>
        <p style="text-align:center;margin-top:60px; text-decoration:underline; text-transform:uppercase;"> <?php echo $coordinator; ?> </p>
        <p style="text-align:center;"> COORDINATOR </p>
	</div>
    
    <div style="border:0px solid red; float:right; margin:20px 45px 0px 0px;">
		<p style="text-align:center;"> Prepared By : </p> <br>
        <p style="text-align:center;margin-top:60px; text-decoration:underline; text-transform:uppercase;"> <?php echo $user; ?> </p>
        <p style="text-align:center; text-transform:capitalize;"> HCM - ADMIN </p>
	</div>
    
    <!--SIGN-->

</div>

</body>
</html>
