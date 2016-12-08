<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title> Graduation Receipt <?php echo isset($pono) ? $pono : ''; ?></title>
<style media="all">

	#logo { margin:0 0 0 75px;}
	#logotext{ font-size:12px; text-align:center; margin:0; }
	p { margin:0; padding:0; font-size:11px;}
	#pono{ font-size:18px; padding:0; margin:0 5px 10px 0; text-align:left;}
	
	table.product
	{ border-collapse:collapse; width:100%; }
	
	table.product,table.product th
	{	border: 1px solid black; font-size:13px; font-weight:bold; padding:4px 0 4px 0; }
	
	table.product,table.product td
	{	border: 1px solid black; font-size:12px; font-weight:normal; padding:3px 0 3px 0; text-align:center; }
	
	table.product td.left { text-align:left; padding:3px 5px 3px 10px; }
	table.product td.right { text-align:right; padding:3px 10px 3px 5px; }
	
</style>
</head>

<body onLoad="">

<div style="width:750px; font-family:Arial, Helvetica, sans-serif; font-size:12px;"> 
	
	<h2 style="font-size:18px; font-weight:normal; text-align:center; text-decoration:underline;"> RECEIPT OF CERTIFICATE </h2> 
    <div style="clear:both; "></div> 
	
	<div style="width:350px; border:0px solid #000; float:left;">
		<table style="font-size:11px;">
 		    <tr> <td> Student </td> <td>:</td> <td> <?php echo $student; ?> </td> </tr>
            <tr> <td> Department </td> <td>:</td> <td> <?php echo $dept; ?> </td> </tr>
            <tr> <td> Faculty / Grade </td> <td>:</td> <td> <?php echo $faculty.' / '.$grade; ?> </td> </tr>
            <tr> <td> Certificate Code </td> <td>:</td> <td> <?php echo $certificate; ?> </td> </tr>
		</table>
	</div>
	
	<div style="width:200px; border:0px solid red; float:right;">
		<table style="font-size:11px;">
            <tr> <td> Date </td> <td>:</td> <td> <?php echo tglin($taking); ?> </td> </tr>
            <tr> <td> Generation </td> <td>:</td> <td> <?php echo $year; ?> </td> </tr>
            <tr> <td> Financial Year </td> <td>:</td> <td> <?php echo $financial_year; ?> </td> </tr>
            <tr> <td> Credit(Month) </td> <td>:</td> <td> <?php echo $credit; ?> </td> </tr>
		</table>
	</div>
	
	<div style="clear:both; "></div>
	
	<div style="margin:10px 0 0 0; border-bottom:0px dotted #000;">
		
		<table class="product">

		 <tr> <th> No </th>  <th> Description </th> <th> Amount </th> </tr>
		 
		 <tr> <td> 1 </td> <td class="left"> School Fee </td> <td class="right"> <?php echo number_format($school); ?> </td> </tr>
         <tr> <td> 2 </td> <td class="left"> OSIS </td> <td class="right"> <?php echo number_format($osis); ?> </td> </tr>
         <tr> <td> 3 </td> <td class="left"> Computer </td> <td class="right"> <?php echo number_format($computer); ?> </td> </tr>
         <tr> <td> 4 </td> <td class="left"> Practice </td> <td class="right"> <?php echo number_format($practice); ?> </td> </tr>
         <tr> <td> 5 </td> <td class="left"> Cost </td> <td class="right"> <?php echo number_format($cost); ?> </td> </tr>
         <tr> <td> 6 </td> <td class="left"> Aid (-) </td> <td class="right"> <?php echo number_format($aid); ?> </td> </tr>

		<tr> <td></td> <td class="right"> <b> Total : </b> </td>
             <td class="right"> <b> <?php echo number_format($amount); ?> </b> </td> 
        </tr>
			
		</table>
		
		<div style="float:left; width:600px; border:0px solid #000; margin:5px 0 5px 0;">  
			<table style="font-size:11px;">
				<tr> <td> In Words </td> <td>:</td> <td> <?php echo $terbilang; ?> </td> </tr>
                <tr> <td colspan="3"> Log : <?php echo $log.' / '.$stts; ?> </td> </tr>
			</table>
		</div>
		
		<div style="clear:both; "></div>
		
		<div style="width:620px; border:0px solid #000; float:right; margin:3px 0px 0 0;">
		<style>
			.sig{ font-size:11px; width:100%; float:right; text-align:center;}
			.sig td{ width:155px;}
		</style>
			<table border="0" class="sig">
				<tr> <td> Disetujui : </td> <td> Diketahui : </td> <td> Dibayar Oleh : </td> <td> Yang Menerima : </td> </tr>
			</table> <br> <br> <br> <br> <br> 
			
			<table border="0" class="sig">
				<tr> 
                <td> Manager </td> 
                <td> Accounting </td> 
                <td> <?php echo $student.' / '.$parent; ?> </td> 
                <td> <?php echo $user; ?> </td> 
                </tr>
			</table>
		</div>
		
</div>

</body>
</html>
