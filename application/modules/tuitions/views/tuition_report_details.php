<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title> <?php echo isset($title) ? $title : ''; ?>  </title>
<style media="all">
	table{ font-family:"Arial", Times, serif; font-size:11px;}
	h4{ font-family:"Arial", Times, serif; font-size:14px; font-weight:600;}
	.clear{clear:both;}
	table th{ background-color:#EFEFEF; padding:4px 0px 4px 0px; border-top:1px solid #000000; border-bottom:1px solid #000000;}
    p{ font-family:"Arial", Times, serif; font-size:12px; margin:0; padding:0;}
	legend{font-family:"Arial", Times, serif; font-size:13px; margin:0; padding:0; font-weight:600;}
	.tablesum{ font-size:13px;}
	.strongs{ font-weight:normal; font-size:12px; border-top:1px dotted #000000; }
	.poder{ border-bottom:0px solid #000000; color:#0000FF;}
	.red{ border-bottom:0px solid #000000; color:#900; font-size:9pt;}
</style>
</head>

<body>

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0">
    		<tr> <td> Currency </td> <td> : </td> <td> <?php echo $currency; ?> </td> </tr>
			<tr> <td> Period </td> <td> : </td> <td> <?php echo tglin($start); ?> &nbsp; to &nbsp; <?php echo tglin($end); ?> </td> </tr>
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo $rundate; ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Tuition Fee Report - Details </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:1px dotted #000000; ">
	
		<table border="0" width="100%">
		  <tr>
          <th> No </th> <th> Date </th> <th> Order No </th> <th> Department </th> <th> School Fee </th> <th> Practical </th> <th> Osis </th>
          <th> Computer </th> <th> Cost </th> <th> Aid Government - BOS </th> <th> Aid Foundation </th> <th> Balance </th> <th> Status </th> 
          </tr>
		   
		  <?php 
		  
		  	  function status($val)
			  { if ($val == 0){ $val = 'B'; } elseif ($val == 1){ $val = 'N'; } elseif ($val == 2){ $val = 'F'; } return $val; }	
			  
			  
			  function poder($po,$dept,$type)
			  {
				 $tt = new Tuition_lib();
				 $value = $tt->report($po,$dept,$type)->result();
				 $total = $tt->total_amount($po,$dept,$type);
				 
				 $i=1;
			
				foreach ($value as $res)
				{
				   echo "
				   <tr>
				   <td class=\"poder\"> </td>
				   <td class=\"poder\"> </td>
				   <td class=\"poder\">$i</td>
				   <td class=\"poder\">".$res->name."</td>
				   <td class=\"poder\" align=\"right\">".number_format($res->school_fee)."</td>
				   <td class=\"poder\" align=\"right\">".number_format($res->practical)."</td>
				   <td class=\"poder\" align=\"right\">".number_format($res->osis)."</td>
				   <td class=\"poder\" align=\"right\">".number_format($res->computer)."</td>
				   <td class=\"poder\" align=\"right\">".number_format($res->cost)."</td>
				   <td class=\"poder\" align=\"right\">".number_format($res->aid_goverment)."</td>
				   <td class=\"poder\" align=\"right\">".number_format($res->aid_foundation)."</td>
				   <td class=\"poder\" align=\"right\">".number_format($res->amount)."</td>
				   <td class=\"poder\" align=\"center\">".status($res->type)."</td>
				   </tr>";
				   $i++;
				} 
				
				echo "<tr> 
				<td class=\"red\"> </td> <td class=\"red\"> </td> <td class=\"red\"> </td> 
				<td class=\"red\"> Total : </td>
				<td class=\"red\" align=\"right\">".number_format($total['school_fee'])."</td>
				<td class=\"red\" align=\"right\">".number_format($total['practical'])."</td>
				<td class=\"red\" align=\"right\">".number_format($total['osis'])."</td>
				<td class=\"red\" align=\"right\">".number_format($total['computer'])."</td>
				<td class=\"red\" align=\"right\">".number_format($total['cost'])."</td>
				<td class=\"red\" align=\"right\">".number_format($total['aid_goverment'])."</td> 
				<td class=\"red\" align=\"right\">".number_format($total['aid_foundation'])."</td>
				<td class=\"red\" align=\"right\">".number_format($total['amount'])."</td> 
				
				</tr>";
			
			  }
			  
		  	  $i=1; 
			  if ($tuitions)
			  {
				foreach ($tuitions as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".tglin($res->dates)."</td> 
					   <td class=\"strongs\"> TJ-00".$res->no."</td> 
   					   <td class=\"strongs\">".$res->dept."</td> 
					   <td class=\"strongs\" align=\"right\"> </td>
					   <td class=\"strongs\" align=\"right\"> </td> 
					   <td class=\"strongs\" align=\"right\"> </td>
					   <td class=\"strongs\" align=\"right\"> </td> 
					   <td class=\"strongs\" align=\"right\"> </td> 
					   <td class=\"strongs\" align=\"right\"> </td>
					   <td class=\"strongs\" align=\"right\"> </td> 
					   <td class=\"strongs\" align=\"center\"> </td> 
				   </tr>";
				   poder($res->no,$res->dept_id,$res->type);
				   $i++;
				}
			  }  
		        
		  ?>
		   
		</table>
	</div>
	
	<div style="border:0px solid red; float:right; margin:15px 0px 0px 0px;">
	   <fieldset> <legend>Summary</legend>
			<table class="tablesum">			
		  <tr> <td> School Fee </td> <td> : </td> <td align="right"> <?php echo number_format($school_total); ?> </td> </tr>
		  <tr> <td> Practical </td> <td> : </td> <td align="right"> <?php echo number_format($practical_total); ?> </td> </tr>
		  <tr> <td> Osis </td> <td> : </td> <td align="right"> <?php echo number_format($osis_total); ?> </td> </tr>
		  <tr> <td> Computer </td> <td> : </td> <td align="right"> <?php echo number_format($computer_total); ?> </td> </tr>
	   	  <tr> <td> Cost </td> <td> : </td> <td align="right"> <?php echo number_format($cost_total); ?> </td> </tr>
          <tr> <td> Government Foundation (BOS) </td> <td> : &nbsp; </td> <td align="right"> <?php echo number_format($bos_total); ?> </td> </tr>
		  <tr> <td> Aid Foundation </td> <td> : &nbsp; </td> <td align="right"> <?php echo number_format($aid_total); ?> </td> </tr>
		  <tr> <td> Balance </td> <td> : </td> <td align="right"> <?php echo number_format($balance_total); ?> </td> </tr>
			</table>
		</fieldset>
	</div>


	<div style="border:0px solid red; float:left; margin:15px 0px 0px 0px;">
		<p> Prepared By : <br/> <br/> <br/>  <br/> <br/>
		    (_______________________) 
		</p>
	</div>
	
	<div style="border:0px solid red; float:left; margin:15px 0px 0px 40px;">
		<p> Approval By : <br/> <br/> <br/>  <br/> <br/>
		    (_______________________) 
		</p>
	</div>

</div>

</body>
</html>
