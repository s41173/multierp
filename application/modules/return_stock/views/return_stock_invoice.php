<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title> Return Stock - Out - BPBG-00<?php echo isset($no) ? $no : ''; ?></title>

<style type="text/css" media="all">

	body{ font-size:0.75em; font-family:Arial, Helvetica, sans-serif; margin:0; padding:0;}
	#container{ width:21cm; height:11.6cm; border:0pt solid #000;}
	.clear{ clear:both;}
	#tablebox{ width:20cm; border:0pt solid red; float:left; margin:0.1cm 0 0 0.4cm;}
	
	table.tab1
	{ border-collapse:collapse; width:100%; }
	
	table.tab1,table.tab1 th
	{	border: 1px solid black; font-size:0.95em; font-weight:bold; padding:4px 0 4px 0; }
	
	table.tab1,table.tab1 td
	{	border: 1px solid black; font-size:0.95em; font-weight:normal; padding:3px 0 3px 0; text-align:center; }
	
	table.price{ text-align:right; }
	
	#logobox{ width:5.5cm; height:1cm; border:0pt solid blue; margin:0.8cm 0 0 0.5cm; float:left;}
	#venbox{ width:5cm; height:1.5cm; border:0pt solid green; margin:0.0cm 0.6cm 0 0; float:right;}
	#signbox{ height:2.2cm; width:19.5cm; float:right; border:0pt solid red; margin:0.25cm 0.6cm 0 0cm; }
	#titbox{ height:0.5cm; width:19.7cm; border:0pt solid blue; margin:0cm 0 0 0.5cm;}
</style>

</head>

<body bgcolor="#FFFFFF"; onload="">

<div id="container">

	<div id="logobox">
		
	</div>
	
	<div id="venbox">
		<table width="100%" style="font-size:1em; margin:0; text-align:left;">
		    <tr> <td> No. BPB </td> <td>:</td> <td> <p style="padding:0; margin:0; font-weight:bold; font-size:1.5em; "> <?php echo '00'.$no; ?> </p> </td> </tr>
			<tr> <td> No. BPBG </td> <td>:</td> <td> <?php echo '00'.$stockout; ?> </td> </tr>
			<tr> <td> Dates </td> <td>:</td> <td> <?php echo $podate; ?> </td> </tr>
		</table>
	</div>
	
	<div class="clear"></div>
	
	<div id="titbox"> 
		<p style="padding:0; margin:0 0 0 7.5cm; font-weight:bold; font-size:1.3em; text-align:left;"> RETURN STOCK (BPB) </p>
	</div>
	
	<div id="tablebox">
	
		<table class="tab1" border="0">
<tr> 
<th class="no"> No </th> <th class="name"> Product </th> <th class="qty"> Qty </th> <th class="qty"> Amount </th> <th class="desc"> Description </th>  <th> Balance </th>
</tr>		

	<?php
        
        if ($items)
        {
            $i=1;
			$tot = 0;
            foreach ($items as $res)
            {
                echo "
                
                 <tr> 
                    <td class=\"no\"> ".$i." </td>
                    <td> ".$res->product." </td> 
                    <td> ".$res->qty.' '.$res->unit." </td>  
                    <td class=\"price\">".number_format($res->price)."</td>
                    <td>".$res->desc."</td>
                    <td class=\"price\">".number_format($res->price*$res->qty)."</td>
                 </tr>
                
                "; $tot = intval($tot+$res->price*$res->qty); $i++;
            }
        }
        
    ?>
		
        <tr>
        <td colspan="4"> </td> <td align="right"> <strong>Total :</strong> </td> <td> <strong> <?php echo number_format($tot); ?> </strong> </td>
        </tr>

		</table>
	</div>  <div class="clear"></div>
	
	<div id="signbox"> 
		
		<div style="height:2.15cm; width:3cm; border:0pt solid blue; float:left; padding:0;"> 
			<p style="padding:0; margin:0; font-size:11px; text-align:center;"> Prepared By </p> 
			<p style="padding:0.7cm 0 0 0; text-align:center;"> ( <?php echo $user; ?> ) <br /> Workshop </p> 
		</div>
		
		<div style="height:2.15cm; width:3cm; border:0pt solid blue; float:left; padding:0; margin:0 0 0 1.7cm;"> 
			<p style="padding:0; margin:0; font-size:1em; text-align:center;"> Review By </p> 
			<p style="padding:0.7cm 0 0 0; text-align:center;"> ( &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ) <br /> Accounting </p> 
		</div>
		
		<div style="height:2.15cm; width:3cm; border:0pt solid blue; float:left; padding:0; margin:0 0 0 2.5cm;"> 
			<p style="padding:0; margin:0; font-size:11px; text-align:center;"> Approved By </p> 
			<p style="padding:0.7cm 0 0 0; text-align:center;"> ( &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ) <br /> Manager </p> 
		</div>
		
		<div style="height:2.15cm; width:3cm; border:0pt solid blue; float:right; padding:0; margin:0 0 0 0cm;"> 
			<p style="padding:0; margin:0; font-size:11px; text-align:center;"> Received By </p> 
			<p style="padding:0.7cm 0 0 0; text-align:center;"> ( <?php echo $user; ?> ) <br /> Logistik </p> 
		</div>
		
		
		
	</div> 
	
</div>

</body>
</html>
