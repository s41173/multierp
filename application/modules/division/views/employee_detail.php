<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title> <?php echo isset($title) ? $title : ''; ?>  </title>
<style media="all">
	table{ font-family:"Verdana", Times, serif; font-size:9pt; text-transform:capitalize;}
	table th{ font-family:arial; font-size:10pt;}
	h4{ font-family:"Arial", Times, serif; font-size:14pt; font-weight:600; margin:0;}
	.clear{clear:both;}
	table th{ background-color:#000; color:#fff; padding:4px 0px 4px 0px; border-top:1px solid #000000; border-bottom:1px solid #000000;}
    p{ font-family:"Arial", Times, serif; font-size:12px; margin:0; padding:0;}
	legend{font-family:"Arial", Times, serif; font-size:13px; margin:0; padding:0; font-weight:600;}
	.tablesum{ font-size:13px;}
	.strongs{ font-weight:normal; font-size:12px; border-top:1px dotted #000000; border-right:1px dotted #000; text-transform: capitalize; }
	.poder{ border-bottom:0px solid #000000; color:#0000FF;}
	.leftcol{ width:30%; border:0px solid #CCC; float:left; margin:5px; }
	.rightcol{ width:66%; border:2px solid #000; margin:5px; float:right; }
	
	table tr.title{ background-color:#000; color:#fff; font-size:10pt; }
	table td{ padding:3px;}
	img{ border:1px solid #CCC; padding:5px;}
	.ptitle{ font-size:11pt; font-family:Verdana, Geneva, sans-serif;}
</style>

<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.3.2.js"></script>
<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";

function cek_session()
{
	$(document).ready(function(){
		$.ajax({
			type: 'POST',
			url: uri +'cek_session',
			data: $(this).serialize(),
			success: function(data){ if (data == 'FALSE'){ window.close(); } }
		})
		return false;	
	}); 
}

</script>

</head>

<body onLoad=" cek_session(); window.print()">

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0">
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo date('d-m-Y'); ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:500px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> </h4>
           <p style="margin:5px; padding:0;"> <?php echo $paddress; ?> <br> Telp. <?php echo $phone1.' - '.$phone2; ?> <br>
               Website : <?php echo $website; ?> &nbsp; &nbsp; Email : <?php echo $email; ?> </p>
	   </div>
	</center> <hr>
    
    <p style="text-align:center; font-size:14pt; font-weight:bold; margin:0 0 0 10px;"> Employee Detail </p>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border:0px solid #000;">
    
    <p style="font-size:10pt; font-weight:bold; margin:0 0 0 8px;"> Section : <?php echo isset($section) ? $section : ''; ?> </p>
    
    <div class="leftcol">
        <img width="230px;" alt="<?php echo $nip; ?>" src="<?php echo $image; ?>"> <br>
        <p style=" text-align:center; margin:10px 0 0 0;"> Signature <br> <br> <br> <br> <br> <b>(<?php echo isset($name) ? $name : ''; ?>)</b> </p>
    </div>
    
    <div class="rightcol">
        <table width="100%">
        	<tr class="title"> <td colspan="6"> <p class="ptitle"> A. PERSONAL EMPLOYEE DETAILS </p> </td> </tr>
            <tr> <td> </td> <td> 1. </td> <td> NIP </td> <td>:</td> <td> <?php echo isset($nip) ? $nip : ''; ?> </td> </tr>
            <tr> <td> </td> <td> 2. </td> <td> Department </td> <td>:</td> <td> <?php echo isset($dept) ? $dept : ''; ?> </td> </tr>
            <tr> <td> </td> <td> 3. </td> <td> Nama Pegawai </td> </tr>
            <tr> <td colspan="2"></td> <td> a. Lengkap </td> <td>:</td> <td> <?php echo isset($name) ? $name : ''; ?> </td> </tr>
            <tr> <td colspan="2"></td> <td> a. Panggilan </td> <td>:</td> <td> <?php echo isset($nickname) ? $nickname : ''; ?> </td> </tr>
            <tr> <td> </td> <td> 4. </td> <td> Jenis Kelamin </td> <td>:</td> <td> <?php echo isset($genre) ? $genre : ''; ?> </td> </tr>
            <tr> <td> </td> <td> 5. </td> <td> Tempat Lahir </td> <td>:</td> <td> <?php echo isset($bornplace) ? $bornplace : ''; ?> </td> </tr>
            <tr> <td> </td> <td> 6. </td> <td> Tanggal Lahir </td> <td>:</td> <td> <?php echo isset($borndate) ? $borndate : ''; ?> </td> </tr>
            <tr> <td> </td> <td> 7. </td> <td> Agama </td> <td>:</td> <td> <?php echo isset($religion) ? $religion : ''; ?> </td> </tr>
            <tr> <td> </td> <td> 8. </td> <td> Suku </td> <td>:</td> <td> <?php echo isset($ethnic) ? $ethnic : ''; ?> </td> </tr>
            <tr> <td> </td> <td> 9. </td> <td> Nomor Identitas </td> <td>:</td> <td> <?php echo isset($idno) ? $idno : ''; ?> </td> </tr>
            <tr> <td> </td> <td> 10. </td> <td> Status </td> <td>:</td> <td> <?php echo isset($marital) ? $marital : ''; ?> </td> </tr>
            
            <tr class="title"> <td colspan="6"> <p class="ptitle"> B. RESIDENCE INFORMATION </p> </td> </tr>
            <tr> <td> </td> <td> 11. </td> <td> Address </td> <td>:</td> <td> <?php echo isset($address) ? $address : ''; ?> </td> </tr>
            <tr> <td> </td> <td> 12. </td> <td> Phone </td> <td>:</td> <td> <?php echo isset($phone) ? $phone : ''; ?> </td> </tr>
            <tr> <td> </td> <td> 13. </td> <td> Mobile </td> <td>:</td> <td> <?php echo isset($mobile) ? $mobile : ''; ?> </td> </tr>
            <tr> <td> </td> <td> 14. </td> <td> Email </td> <td>:</td> <td> <?php echo isset($email) ? $email : ''; ?> </td> </tr>
            
            <tr class="title"> <td colspan="6"> <p class="ptitle"> C. OTHERS INFORMATION </p> </td> </tr>
            <tr> <td> </td> <td> 15. </td> <td> Description </td> <td>:</td> <td> <?php echo isset($desc) ? $desc : ''; ?> </td> </tr>
        </table> 
    </div> <div class="clear"></div>
	
	</div>

</div>

</body>
</html>
