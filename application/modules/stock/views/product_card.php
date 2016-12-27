<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title> <?php echo isset($title) ? $title : ''; ?>  </title>
<style media="all">
	table{ font-family:"Arial", Times, serif; font-size:11px;}
	table th{ font-family:arial; font-size:10pt;}
	h4{ font-family:"Arial", Times, serif; font-size:14pt; font-weight:600; margin:0;}
	.clear{clear:both;}
	table th{ background-color:#000; color:#fff; padding:4px 0px 4px 0px; border-top:1px solid #000000; border-bottom:1px solid #000000;}
    p{ font-family:"Arial", Times, serif; font-size:12px; margin:0; padding:0;}
	legend{font-family:"Arial", Times, serif; font-size:13px; margin:0; padding:0; font-weight:600;}
	.tablesum{ font-size:13px;}
	.strongs{ font-weight:normal; font-size:12px; border-top:1px dotted #000000; border-right:1px dotted #000; text-transform: capitalize; }
	.poder{ border-bottom:0px solid #000000; color:#0000FF;}
</style>
</head>

<body onLoad="window.print()">

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0">
            <tr> <td> Run Date </td> <td> : </td> <td> <?php echo date('d-m-Y'); ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>
    
    <div style="border:0px solid red; float:right;">
		<table border="0">
            <tr> <td> Stock Code </td> <td> : </td> <td> <?php echo $code; ?> </td> </tr>
            <tr> <td> Stock Name </td> <td> : </td> <td> <?php echo $name; ?> </td> </tr>
            <tr> <td> Category / Brand </td> <td> : </td> <td> <?php echo $category.' / '.$brand; ?> </td> </tr>
            <tr> <td> Unit </td> <td> : </td> <td> <?php echo $unit; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:500px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> </h4>
           <p style="margin:5px; padding:0;"> <?php echo $address; ?> <br> Telp. <?php echo $phone1.' - '.$phone2; ?> <br>
               Website : <?php echo $website; ?> &nbsp; &nbsp; Email : <?php echo $email; ?> </p>
	   </div>
	</center> <hr>
    
    <p style="text-align:center; font-size:14pt; font-weight:bold;"> STOCK CARD </p>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border:1px solid #000; ">
	
		<table border="0" width="100%">
		   <tr>
  	       <th> No </th> <th> Date </th> <th> IN </th> <th> OUT </th> <th> Balance </th> <th> User / Log </th> <th> Signature </th>
		   </tr>
           
		  <?php 
		  	  
		      $i=1; 
			  if ($trans)
			  {
				foreach ($trans as $res)
				{	
				   echo " 
				   <tr> 
				       <td align=\"center\" class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".tglin($res->dates)."</td>
					   <td align=\"center\" class=\"strongs\">".$res->in."</td>
					   <td align=\"center\" class=\"strongs\">".$res->out."</td>
					   <td align=\"center\" class=\"strongs\">".$res->balance."</td>
					   <td align=\"center\" class=\"strongs\">".$res->log."</td>
					   <td align=\"center\" class=\"strongs\"> &nbsp; &nbsp; </td>
				   </tr>";
				   $i++;
				}
			  } 
			  
		  ?>
          
		</table>
	</div>

</div>

</body>
</html>
