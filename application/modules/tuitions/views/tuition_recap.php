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
</style>
</head>

<body>

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0">
    		<tr> <td> Currency </td> <td> : </td> <td> <?php echo $currency; ?> </td> </tr>
			<tr> <td> Period </td> <td> : </td> <td> <?php echo tglin($start); ?> &nbsp; to &nbsp; <?php echo tglin($end); ?> </td> </tr>
            <tr> <td> Status </td> <td> : </td> <td> <?php echo status($status); ?> </td> </tr>
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo $rundate; ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Tuition Fee - Recapitulation </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:1px dotted #000000; ">
	
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Date </th> <th> Order No </th> <th> Department </th> <th> School Fee </th> <th> Practical </th> <th> Computer </th> 
           <th> OSIS </th> <th> Aid - BOS </th> <th> Aid - Foundation </th> <th> Status </th>
		   </tr>
		    
		  <?php 
		  
		  	  function status($val)
			  { if ($val == 0){ $val = 'B'; } elseif ($val == 1){ $val = 'N'; } elseif ($val == 2){ $val = 'F'; } return $val; }	
		  
		  	  function get_total($currency,$dept,$starts,$ends,$status,$type)
			  {
				 $tt = new Tuition_lib();
			     $total = $tt->total_student($currency,$dept,$starts,$ends,$status,$type);	
				 return $total;
			  }
		  	
		  
		      $i=1; 
			  if ($tuitions)
			  {
				foreach ($tuitions as $res)
				{	
				   $total[0] = get_total($res->currency,$res->dept_id,$start,$end,$res->type,'school_fee');
				   $total[1] = get_total($res->currency,$res->dept_id,$start,$end,$res->type,'practical');
				   $total[2] = get_total($res->currency,$res->dept_id,$start,$end,$res->type,'computer');
				   $total[3] = get_total($res->currency,$res->dept_id,$start,$end,$res->type,'osis');
				   $total[4] = get_total($res->currency,$res->dept_id,$start,$end,$res->type,'aid_goverment');
				   $total[5] = get_total($res->currency,$res->dept_id,$start,$end,$res->type,'aid_foundation');
				  
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".tglin($res->dates)."</td> 
					   <td class=\"strongs\"> TJ-00".$res->no."</td> 
   					   <td class=\"strongs\">".$res->dept."</td> 
					   <td class=\"strongs\" align=\"center\">".$total[0]."</td>
					   <td class=\"strongs\" align=\"center\">".$total[1]."</td>
					   <td class=\"strongs\" align=\"center\">".$total[2]."</td>
					   <td class=\"strongs\" align=\"center\">".$total[3]."</td>
					   <td class=\"strongs\" align=\"center\">".$total[4]."</td>
					   <td class=\"strongs\" align=\"center\">".$total[5]."</td>
					   <td class=\"strongs\" align=\"center\">".status($res->type)."</td> 
				   </tr>";
				   $i++;
				}
			  }  
		  ?>
		   
		</table>
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
