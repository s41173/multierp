<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title> Fixed Asset Trans - <?php echo isset($code) ? $code : ''; ?></title>
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
    table.product td.signature { width:2cm; }
	
</style>
</head>

<body onLoad="">

<div style="width:750px; font-family:Arial, Helvetica, sans-serif; font-size:12px;"> 
	
	<h2 style="font-size:18px; font-weight:normal; text-align:center; text-decoration:underline;"> FIXED ASSET DEPRECIATION </h2> 
    <div style="clear:both; "></div> 
	
	<div style="width:350px; border:0px solid #000; float:left;">
		<table style="font-size:11px;">
 		 <tr> <td> Code </td> <td>:</td> <td> <p style="font-weight:bold; font-size:14px;"> <?php echo $code; ?> </p> </td> </tr>
         <tr> <td> Name </td> <td>:</td> <td> <?php echo $name; ?> </td> </tr>
         <tr> <td> Group </td> <td></td> <td> <?php echo $group; ?> </td> </tr>
         <tr> <td> Purchase </td> <td></td> <td> <?php echo $purchase; ?> </td> </tr>
         <tr> <td> Amount </td> <td></td> <td> <?php echo $amount; ?> </td> </tr>
		</table>
	</div>
	
	<div style="clear:both; "></div>
	
	<div style="margin:3px 0 0 0; border-bottom:0px dotted #000;">
		
		<table class="product">

		 <tr> <th> No </th> <th> Period </th> <th> Closing Dates </th> <th> Amount </th> </tr>
		 
		 <?php
		 	
			$group = new Group_asset_lib();
            $asset = new Asset_lib();
		 	
			if ($items)
			{
				$i=1;
				foreach ($items as $res)
				{
					echo "
					 <tr> 
						<td> ".$i." </td>
						<td class=\"left\"> ".$res->period." </td>
						<td class=\"left\"> ".tglin($res->closing_dates)." </td> 
						<td class=\"right\"> ".number_format($res->amount)." </td>
					 </tr>
					"; $i++;
				}
			}
			
		 ?>
			
		</table>
     </div>
</div>

</body>
</html>
