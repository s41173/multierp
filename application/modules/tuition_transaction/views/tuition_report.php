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
			<tr> <td> Period </td> <td> : </td> <td> <?php echo tglin($start); ?> &nbsp; to &nbsp; <?php echo tglin($end); ?> </td> </tr>
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo $rundate; ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Tuition Transaction Fee - Report </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:1px dotted #000000; ">
	
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Period </th> <th> Tuitions </th> <th> Date </th> <th> Fee Type </th> <th> Department </th> <th> Grade </th> 
           <th> Student </th> <th> School </th> <th> Practical </th> <th> OSIS </th> <th> Computer </th> <th> Cost </th> 
           <th> Aid Government - BOS </th> <th> Aid Foundation </th> <th> Balance </th> <th> Status </th> <th> User </th>
		   </tr>
		    
		  <?php 
		  
		  	  function status($val){ if ($val == 0){ $val = 'B'; } elseif ($val == 1){ $val = 'N'; } elseif ($val == 2){ $val = 'F'; } return $val; }	
			  
			  function dept($student)
			  {
				  $st = new Student_lib();
				  $dept = new Dept_lib();
				  $student = $dept->get_name($st->get_dept($student)); 
				  return $student;
			  }
			  
			  function student($val){ $st = new Student_lib(); return $st->get_name($val); }
			  function user($val){ $user = new Admin_lib(); return $user->get_username($val); }
			  function fee($val){ $fee = new Regcost_lib(); return $fee->get_name($val); }
			  function grade($val)
			  { 
			    $st = new Student_lib(); 
				$gr = new Grade_lib();
				return $gr->get_name($st->get_grade($val));
			  }
			  
			 $tot_school = 0;
			 $tot_practical = 0;
			 $tot_osis = 0;
			 $tot_computer = 0;
			 $tot_cost = 0;
			 $tot_aid_goverment = 0;
			 $tot_aid_foundation = 0;
			 $tot_amount = 0;
		  
		      $i=1; 
			  if ($tuitions)
			  {  
				foreach ($tuitions as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\" align=\"center\">".$res->financial_year."</td> 
					   <td class=\"strongs\" align=\"center\"> TJ-00".$res->tuition."</td> 
					   <td class=\"strongs\" align=\"center\">".tglin($res->dates)."</td>
					   <td class=\"strongs\" align=\"center\">".fee($res->fee_type)."</td> 
   					   <td class=\"strongs\" align=\"center\">".dept($res->student)."</td>
					   <td class=\"strongs\" align=\"center\">".grade($res->student)."</td>
					   <td class=\"strongs\">".strtoupper(student($res->student))."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->school_fee)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->practical)."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->osis)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->computer)."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->cost)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->aid_goverment)."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->aid_foundation)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->amount)."</td> 
					   <td class=\"strongs\" align=\"center\">".status($res->type)."</td>
					   <td class=\"strongs\" align=\"center\">".user($res->user)."</td> 
				   </tr>";
				   $i++;
				   
				   $tot_school = $tot_school + $res->school_fee;
				   $tot_practical = $tot_practical + $res->practical;
				   $tot_osis = $tot_osis + $res->osis;
				   $tot_computer = $tot_computer + $res->computer;
				   $tot_cost = $tot_cost + $res->cost;
				   $tot_aid_goverment = $tot_aid_goverment + $res->aid_goverment;
   				   $tot_aid_foundation = $tot_aid_foundation + $res->aid_foundation;
   				   $tot_amount = $tot_amount + $res->amount;
				}
			  }  
		  ?>
		   
		</table>
	</div>
	
	<div style="border:0px solid red; float:right; margin:15px 0px 0px 0px;">
	   <fieldset> <legend>Summary</legend>
			<table class="tablesum">			
	   <tr> <td> School Fee </td> <td> : </td> <td align="right"> <?php echo number_format($tot_school); ?> </td> </tr>
	   <tr> <td> Practical </td> <td> : </td> <td align="right"> <?php echo number_format($tot_practical); ?> </td> </tr>
	   <tr> <td> Osis </td> <td> : </td> <td align="right"> <?php echo number_format($tot_osis); ?> </td> </tr>
	   <tr> <td> Computer </td> <td> : </td> <td align="right"> <?php echo number_format($tot_computer); ?> </td> </tr>
	   <tr> <td> Cost </td> <td> : </td> <td align="right"> <?php echo number_format($tot_cost); ?> </td> </tr>
	   <tr> <td> Government Foundation (BOS) </td> <td> : </td> <td align="right"> <?php echo number_format($tot_aid_goverment); ?> </td> </tr>
       <tr> <td> Aid Foundation </td> <td> : </td> <td align="right"> <?php echo number_format($tot_aid_foundation); ?> </td> </tr>
	   <tr> <td> Balance </td> <td> : </td> <td align="right"> <?php echo number_format($tot_amount); ?> </td> </tr>
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
