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
	.bold{ font-weight:bold;}
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
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> AR - Tuition Fee Report </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:1px dotted #000000; ">
	
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Date </th> <th> Order No </th> <th> Department </th> <th> Student </th> <th> Academic Year </th> <th> School Fee </th>
           <th> Practical </th> <th> Osis </th> <th> Computer </th> <th> Balance </th>
		   </tr>
		    
		  <?php 
		  
		  	  function dept($id)
			  {
				  $dept = new Dept_lib();
				  $st = new Student_lib();
				  return $dept->get_name($st->get_dept($id));
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
					   <td class=\"strongs\"> TRJ-00".$res->id."</td> 
   					   <td class=\"strongs\">".dept($res->student_id)."</td>
					   <td class=\"strongs\">".$res->name."</td> 
					   <td align=\"center\" class=\"strongs\">".$res->financial_year."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->school_fee)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->practical)."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->osis)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->computer)."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->amount)."</td> 
				   </tr>";
				   $i++;
				}
			  }  
		  ?>
		   
           <tr> 
              <td class="strongs bold"> TOTAL : </td>
              <td class="strongs bold" colspan="6" align="right"> <?php echo number_format($totals['school_fee']); ?>  </td> 
              <td class="strongs bold" align="right"> <?php echo number_format($totals['practical']); ?>  </td> 
              <td class="strongs bold" align="right"> <?php echo number_format($totals['osis']); ?>  </td> 
              <td class="strongs bold" align="right"> <?php echo number_format($totals['computer']); ?>  </td> 
              <td class="strongs bold" align="right"> <?php echo number_format($totals['amount']); ?>  </td> 
           </tr>
           
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
