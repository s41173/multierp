<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title> <?php echo isset($title) ? $title : ''; ?>  </title>
<style media="all">
	table{ font-family:"Times New Roman", Times, serif; font-size:11px;}
	h4{ font-family:"Times New Roman", Times, serif; font-size:14px; font-weight:600;}
	.clear{clear:both;}
	table th{ background-color:#EFEFEF; padding:4px 0px 4px 0px; border-top:1px solid #000000; border-bottom:1px solid #000000;}
    p{ font-family:"Times New Roman", Times, serif; font-size:12px; margin:0; padding:0;}
	legend{font-family:"Times New Roman", Times, serif; font-size:13px; margin:0; padding:0; font-weight:600;}
	.tablesum{ font-size:13px;}
	.strongs{ font-weight:normal; font-size:12px; border-top:1px dotted #000000; }
	.poder{ border-bottom:0px solid #000000; color:#0000FF;}
</style>
</head>

<body>

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0">
      		<tr> <td> Currency </td> <td> : </td> <td> <?php echo $currency; ?> </td> </tr>
			<tr> <td> Period </td> <td> : </td> <td> <?php echo tgleng($start); ?> to <?php echo tgleng($end); ?> </td> </tr>
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo $rundate; ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Journal - Report </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:1px dotted #000000; ">
	
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Date </th> <th> Code </th> <th> PJ </th> <th> DP </th> <th> GJ </th> <th> CD </th> <th> CG </th> <th> TR </th> <th> SJ </th> <th> DS </th> <th> CSJ </th> <th> CDS </th>
		   <th> CR </th> <th> CCR </th> <th> SAJ </th> <th> PRJ </th> <th> ARJ </th> <th> RF </th> <th> AJ </th>
		   </tr>
		   
		  <?php 
			  		  
		      $i=1; 
			  if ($reports)
			  {
				foreach ($reports as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".tgleng($res->dates)."</td> 
					   <td class=\"strongs\"> DT-00".$res->id."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->PJ)."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->DP)."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->GJ)."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->CD)."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->CG)."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->TR)."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->SJ)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->DS)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->CSJ)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->CDS)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->CR)."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->CCR)."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->SAJ)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->PRJ)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->ARJ)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->RF)."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->AJ)."</td> 
				   </tr>";
				   $i++;
				}
			 }  
		  ?>
		   
		</table>
	</div>
	

	
	<div style="border:0px solid red; float:left; margin:25px 0px 0px 0px;">
		<p> Prepared By : <br/> <br/> <br/>  <br/> <br/>
		    (_______________________) 
		</p>
	</div>
	
	<div style="border:0px solid red; float:left; margin:25px 0px 0px 40px;">
		<p> Approval By : <br/> <br/> <br/>  <br/> <br/>
		    (_______________________) 
		</p>
	</div>

</div>

</body>
</html>
