<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title> <?php echo isset($title) ? $title : ''; ?>  </title>
<style media="all">
	table{ font-family:arial; font-size:12pt;}
	h4{ font-family:"Times New Roman", Times, serif; font-size:14px; font-weight:600;}
	.clear{clear:both;}
	table th{ background-color:#EFEFEF; padding:4px 0px 4px 0px; border-top:1px solid #000000; border-bottom:1px solid #000000;}
    p{ font-family:Arial; font-size:11pt; margin:0; padding:0;}
	legend{font-family:arial; font-size:12pt; margin:0; padding:0; font-weight:600;}
	.tablesum{ font-size:13px;}
	.strongs{ font-weight:normal; font-size:12px; border-top:0px dotted #000000; }
	.poder{ border-bottom:0px solid #000000; color:#0000FF;}
	.gross{ color:#900;}
	.net{ color:#009;}
</style>
</head>

<body onLoad="">

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0" style="font-size:9pt;">
			<tr> <td> No </td> <td> : </td> <td> <?php echo 'JT-00'.$pono; ?> </td> </tr>
			<tr> <td> Date </td> <td> : </td> <td> <?php echo $dates; ?> </td> </tr>
			<tr> <td> Currency </td> <td> : </td> <td> <?php echo $cur; ?> </td> </tr>
		</table>
	</div>
	
	<div style="border:0px solid red; float:right;">
		<table border="0" style="font-size:9pt;">
			<tr> <td> Print Date </td> <td> : </td> <td> <?php echo tgleng(date('Y-m-d')); ?> </td> </tr>
            <tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($pname) ? $pname : ''; ?> <br> DAILY INCOME STATEMENT </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:1px dotted #000000; ">
		
        <!-- SMP -->
		<fieldset> <legend> I - TINGKAT SMP </legend>
		<table border="0" width="100%">
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> SPP - Terima Di Muka </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smp_spp_depan); ?>,- </td> 
           </tr>
           
            <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> SPP - Bulan Berjalan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smp_spp_berjalan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> SPP - Tunggakan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smp_spp_piutang); ?>,- </td> 
           </tr>
           
           <tr> 
           <td class="strongs"> </td>  <td class="strongs gross"> <b> Total - SPP </b> </td> 
           <td></td> <td></td> 
           <td class="strongs gross" align="right"> <b> <?php echo number_format($smp_spp_depan+$smp_spp_berjalan+$smp_spp_piutang); ?>,- </b> </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> OSIS - Terima Di Muka </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smp_osis_depan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> OSIS </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smp_osis_berjalan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> OSIS - Tunggakan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smp_osis_piutang); ?>,- </td> 
           </tr>
           
         <tr> 
         <td class="strongs"> </td>  <td class="strongs gross"> <b> Total - OSIS </b> </td> 
         <td></td> <td></td> 
         <td class="strongs gross" align="right"> <b> <?php echo number_format($smp_osis_depan+$smp_osis_berjalan+$smp_osis_piutang); ?>,- </b> </td>
         </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Komputer - Terima Di Muka </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smp_com_depan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Komputer </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smp_com_berjalan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Komputer - Tunggakan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smp_com_piutang); ?>,- </td> 
           </tr>
           
           <tr> 
           <td class="strongs"> </td>  <td class="strongs gross"> <b> Total - Komputer </b> </td> 
           <td></td> <td></td> 
           <td class="strongs gross" align="right"> <b> <?php echo number_format($smp_com_depan+$smp_com_berjalan+$smp_com_piutang); ?>,- </b> </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Praktek - Terima Di Muka </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smp_praktek_depan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Praktek </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smp_praktek_berjalan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Praktek - Tunggakan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smp_praktek_piutang); ?>,- </td> 
           </tr>
           
<tr> 
<td class="strongs"> </td>  <td class="strongs gross"> <b> Total - Praktek </b> </td> 
<td></td> <td></td> 
<td class="strongs gross" align="right"> <b> <?php echo number_format($smp_praktek_depan+$smp_praktek_berjalan+$smp_praktek_piutang); ?>,- </b> </td 
></tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Bantuan Di Muka </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smp_bantuan_depan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Bantuan Berjalan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smp_bantuan_berjalan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Bantuan Tunggakan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smp_bantuan_piutang); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> </td>  <td class="strongs gross"> <b> Total - Bantuan </b> </td> 
               <td></td> <td></td> 
<td class="strongs gross" align="right"> <b> <?php echo number_format($smp_bantuan_depan+$smp_bantuan_berjalan+$smp_bantuan_piutang); ?>,- </b> </td> 
           </tr>
           
<tr> 
<td class="strongs"> </td>  <td class="strongs gross"> <b> Gross Income </b> </td> 
<td></td> <td></td> 
<td class="strongs gross" align="right"> <b> <?php echo number_format($smp_total_piutang+$smp_total_berjalan+$smp_total_depan); ?>,- </b> </td> 
</tr>
           
           <tr> 
               <td class="strongs"> </td>  <td class="strongs net"> <b> Net Income </b> </td> 
               <td></td> <td></td> 
<td class="strongs net" align="right"> 
<b> 
<?php echo number_format($smp_total_piutang+$smp_total_berjalan+$smp_total_depan-$smp_bantuan_depan-$smp_bantuan_berjalan-$smp_bantuan_piutang); ?>,-</b> </td> 
           </tr>
		   
		</table>
		</fieldset> <br>
		<!-- SMP -->	
        
        <!-- SMA -->
		<fieldset> <legend> II - TINGKAT SMA </legend>
		<table border="0" width="100%">
          <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> SPP - Terima Di Muka </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($sma_spp_depan); ?>,- </td> 
           </tr>
           
            <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> SPP - Bulan Berjalan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($sma_spp_berjalan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> SPP - Tunggakan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($sma_spp_piutang); ?>,- </td> 
           </tr>
           
           <tr> 
           <td class="strongs"> </td>  <td class="strongs gross"> <b> Total - SPP </b> </td> 
           <td></td> <td></td> 
           <td class="strongs gross" align="right"> <b> <?php echo number_format($sma_spp_depan+$sma_spp_berjalan+$sma_spp_piutang); ?>,- </b> </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> OSIS - Terima Di Muka </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($sma_osis_depan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> OSIS </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($sma_osis_berjalan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> OSIS - Tunggakan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($sma_osis_piutang); ?>,- </td> 
           </tr>
           
         <tr> 
         <td class="strongs"> </td>  <td class="strongs gross"> <b> Total - OSIS </b> </td> 
         <td></td> <td></td> 
         <td class="strongs gross" align="right"> <b> <?php echo number_format($sma_osis_depan+$sma_osis_berjalan+$sma_osis_piutang); ?>,- </b> </td>
         </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Komputer - Terima Di Muka </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($sma_com_depan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Komputer </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($sma_com_berjalan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Komputer - Tunggakan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($sma_com_piutang); ?>,- </td> 
           </tr>
           
           <tr> 
           <td class="strongs"> </td>  <td class="strongs gross"> <b> Total - Komputer </b> </td> 
           <td></td> <td></td> 
           <td class="strongs gross" align="right"> <b> <?php echo number_format($sma_com_depan+$sma_com_berjalan+$sma_com_piutang); ?>,- </b> </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Praktek - Terima Di Muka </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($sma_praktek_depan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Praktek </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($sma_praktek_berjalan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Praktek - Tunggakan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($sma_praktek_piutang); ?>,- </td> 
           </tr>
           
<tr> 
<td class="strongs"> </td>  <td class="strongs gross"> <b> Total - Praktek </b> </td> 
<td></td> <td></td> 
<td class="strongs gross" align="right"> <b> <?php echo number_format($sma_praktek_depan+$sma_praktek_berjalan+$sma_praktek_piutang); ?>,- </b> </td 
></tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Bantuan Di Muka </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($sma_bantuan_depan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Bantuan Berjalan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($sma_bantuan_berjalan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Bantuan Tunggakan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($sma_bantuan_piutang); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> </td>  <td class="strongs gross"> <b> Total - Bantuan </b> </td> 
               <td></td> <td></td> 
<td class="strongs gross" align="right"> <b> <?php echo number_format($sma_bantuan_depan+$sma_bantuan_berjalan+$sma_bantuan_piutang); ?>,- </b> </td> 
           </tr>
           
<tr> 
<td class="strongs"> </td>  <td class="strongs gross"> <b> Gross Income </b> </td> 
<td></td> <td></td> 
<td class="strongs gross" align="right"> <b> <?php echo number_format($sma_total_piutang+$sma_total_berjalan+$sma_total_depan); ?>,- </b> </td> 
</tr>
           
           <tr> 
               <td class="strongs"> </td>  <td class="strongs net"> <b> Net Income </b> </td> 
               <td></td> <td></td> 
<td class="strongs net" align="right"> 
<b> 
<?php echo number_format($sma_total_piutang+$sma_total_berjalan+$sma_total_depan-$sma_bantuan_depan-$sma_bantuan_berjalan-$sma_bantuan_piutang); ?>,-</b> </td> 
           </tr>
		   
		</table>
		</fieldset> <br>
		<!-- SMA -->
        
        <!-- STM -->
		<fieldset> <legend> III - TINGKAT SMK-TI </legend>
		<table border="0" width="100%">
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> SPP - Terima Di Muka </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($stm_spp_depan); ?>,- </td> 
           </tr>
           
            <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> SPP - Bulan Berjalan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($stm_spp_berjalan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> SPP - Tunggakan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($stm_spp_piutang); ?>,- </td> 
           </tr>
           
           <tr> 
           <td class="strongs"> </td>  <td class="strongs gross"> <b> Total - SPP </b> </td> 
           <td></td> <td></td> 
           <td class="strongs gross" align="right"> <b> <?php echo number_format($stm_spp_depan+$stm_spp_berjalan+$stm_spp_piutang); ?>,- </b> </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> OSIS - Terima Di Muka </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($stm_osis_depan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> OSIS </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($stm_osis_berjalan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> OSIS - Tunggakan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($stm_osis_piutang); ?>,- </td> 
           </tr>
           
         <tr> 
         <td class="strongs"> </td>  <td class="strongs gross"> <b> Total - OSIS </b> </td> 
         <td></td> <td></td> 
         <td class="strongs gross" align="right"> <b> <?php echo number_format($stm_osis_depan+$stm_osis_berjalan+$stm_osis_piutang); ?>,- </b> </td>
         </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Komputer - Terima Di Muka </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($stm_com_depan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Komputer </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($stm_com_berjalan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Komputer - Tunggakan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($stm_com_piutang); ?>,- </td> 
           </tr>
           
           <tr> 
           <td class="strongs"> </td>  <td class="strongs gross"> <b> Total - Komputer </b> </td> 
           <td></td> <td></td> 
           <td class="strongs gross" align="right"> <b> <?php echo number_format($stm_com_depan+$stm_com_berjalan+$stm_com_piutang); ?>,- </b> </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Praktek - Terima Di Muka </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($stm_praktek_depan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Praktek </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($stm_praktek_berjalan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Praktek - Tunggakan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($stm_praktek_piutang); ?>,- </td> 
           </tr>
           
<tr> 
<td class="strongs"> </td>  <td class="strongs gross"> <b> Total - Praktek </b> </td> 
<td></td> <td></td> 
<td class="strongs gross" align="right"> <b> <?php echo number_format($stm_praktek_depan+$stm_praktek_berjalan+$stm_praktek_piutang); ?>,- </b> </td 
></tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Bantuan Di Muka </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($stm_bantuan_depan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Bantuan Berjalan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($stm_bantuan_berjalan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Bantuan Tunggakan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($stm_bantuan_piutang); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> </td>  <td class="strongs gross"> <b> Total - Bantuan </b> </td> 
               <td></td> <td></td> 
<td class="strongs gross" align="right"> <b> <?php echo number_format($stm_bantuan_depan+$stm_bantuan_berjalan+$stm_bantuan_piutang); ?>,- </b> </td> 
           </tr>
           
<tr> 
<td class="strongs"> </td>  <td class="strongs gross"> <b> Gross Income </b> </td> 
<td></td> <td></td> 
<td class="strongs gross" align="right"> <b> <?php echo number_format($stm_total_piutang+$stm_total_berjalan+$stm_total_depan); ?>,- </b> </td> 
<?php //echo number_format($stm_total_piutang+$stm_total_berjalan+$stm_total_depan); ?>
</tr>
           
           <tr> 
               <td class="strongs"> </td>  <td class="strongs net"> <b> Net Income </b> </td> 
               <td></td> <td></td> 
<td class="strongs net" align="right"> 
<b> 
<?php echo number_format($stm_total_piutang+$stm_total_berjalan+$stm_total_depan-$stm_bantuan_depan-$stm_bantuan_berjalan-$stm_bantuan_piutang); ?>,-</b> </td> 
           </tr>
		   
		</table>
		</fieldset> <br>
		<!-- STM -->
        
        <!-- SMEA -->
		<fieldset> <legend> IV - TINGKAT SMK-BM </legend>
		<table border="0" width="100%">
          <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> SPP - Terima Di Muka </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smea_spp_depan); ?>,- </td> 
           </tr>
           
            <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> SPP - Bulan Berjalan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smea_spp_berjalan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> SPP - Tunggakan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smea_spp_piutang); ?>,- </td> 
           </tr>
           
           <tr> 
           <td class="strongs"> </td>  <td class="strongs gross"> <b> Total - SPP </b> </td> 
           <td></td> <td></td> 
       <td class="strongs gross" align="right"> <b> <?php echo number_format($smea_spp_depan+$smea_spp_berjalan+$smea_spp_piutang); ?>,- </b> </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> OSIS - Terima Di Muka </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smea_osis_depan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> OSIS </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smea_osis_berjalan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> OSIS - Tunggakan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smea_osis_piutang); ?>,- </td> 
           </tr>
           
         <tr> 
         <td class="strongs"> </td>  <td class="strongs gross"> <b> Total - OSIS </b> </td> 
         <td></td> <td></td> 
  <td class="strongs gross" align="right"> <b> <?php echo number_format($smea_osis_depan+$smea_osis_berjalan+$smea_osis_piutang); ?>,- </b> </td>
         </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Komputer - Terima Di Muka </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smea_com_depan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Komputer </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smea_com_berjalan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Komputer - Tunggakan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smea_com_piutang); ?>,- </td> 
           </tr>
           
           <tr> 
           <td class="strongs"> </td>  <td class="strongs gross"> <b> Total - Komputer </b> </td> 
           <td></td> <td></td> 
     <td class="strongs gross" align="right"> <b> <?php echo number_format($smea_com_depan+$smea_com_berjalan+$smea_com_piutang); ?>,- </b> </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Praktek - Terima Di Muka </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smea_praktek_depan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Praktek </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smea_praktek_berjalan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Praktek - Tunggakan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smea_praktek_piutang); ?>,- </td> 
           </tr>
           
<tr> 
<td class="strongs"> </td>  <td class="strongs gross"> <b> Total - Praktek </b> </td> 
<td></td> <td></td> 
<td class="strongs gross" align="right">
<b> <?php echo number_format($smea_praktek_depan+$smea_praktek_berjalan+$smea_praktek_piutang); ?>,- </b> </td></tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Bantuan Di Muka </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smea_bantuan_depan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Bantuan Berjalan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smea_bantuan_berjalan); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> ** </td>  <td class="strongs"> Bantuan Tunggakan </td> 
               <td> : </td> <td class="strongs" align="right"> <?php echo number_format($smea_bantuan_piutang); ?>,- </td> 
           </tr>
           
           <tr> 
               <td class="strongs"> </td>  <td class="strongs gross"> <b> Total - Bantuan </b> </td> 
               <td></td> <td></td> 
<td class="strongs gross" align="right"> 
<b> <?php echo number_format($smea_bantuan_depan+$smea_bantuan_berjalan+$smea_bantuan_piutang); ?>,- </b> 
</td> 
           </tr>
           
<tr> 
<td class="strongs"> </td>  <td class="strongs gross"> <b> Gross Income </b> </td> 
<td></td> <td></td> 
<td class="strongs gross" align="right"> <b> <?php echo number_format($smea_total_piutang+$smea_total_berjalan+$smea_total_depan); ?>,- </b> </td> 
</tr>
           
           <tr> 
               <td class="strongs"> </td>  <td class="strongs net"> <b> Net Income </b> </td> 
               <td></td> <td></td> 
<td class="strongs net" align="right"> 
<b> 
<?php 
echo number_format($smea_total_piutang+$smea_total_berjalan+$smea_total_depan-$smea_bantuan_depan-$smea_bantuan_berjalan-$smea_bantuan_piutang);
 ?>,-</b> </td> 
           </tr>
		   
		</table>
		</fieldset>
		<!-- SMEA -->
        
        <!-- PRAKTEK -->
		<fieldset> <legend> TOTAL </legend>
		<table border="0" width="100%">           
           <!-- TOTAL -->
            <tr> 
               <td> </td>  <td class="strongs"> </td> 
               <td> </td> <td style="font-size:11pt; font-weight:bold;" align="right"> Grand Total </td> 
               <td style="font-size:11pt; font-weight:bold;" align="right"> <br> <?php echo number_format($total); ?>,-  </td> 
           </tr>
           <!-- TOTAL -->
		   
		</table>
		</fieldset>
		<!-- PRAKTEK -->
		
	</div>

</div>

</body>
</html>
