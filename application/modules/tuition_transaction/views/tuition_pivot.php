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
	table th{ background-color:#EFEFEF; padding:4px 0px 4px 0px; border-top:1px solid #000000; border-bottom:0px solid #000000;}
    p{ font-family:"Arial", Times, serif; font-size:12px; margin:0; padding:0;}
	legend{font-family:"Arial", Times, serif; font-size:13px; margin:0; padding:0; font-weight:600;}
	.tablesum{ font-size:13px;}
	.strongs{ font-weight:normal; font-size:12px; border-top:0px dotted #000000; }
	.poder{ border-bottom:0px solid #000000; color:#0000FF;}
</style>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'js/pivot/' ?>pivot.css">
	<script type="text/javascript" src="<?php echo base_url().'js/pivot/' ?>jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/pivot/' ?>jquery-ui-1.9.2.custom.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/pivot/' ?>jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/pivot/' ?>pivot.js"></script>  
    
    <script type="text/javascript">
        $(document).ready(function () {
          	
			var input = $("#input")
			$("#output").pivotUI(input);
			$("#input").hide();
        });
    </script>

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
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Tuition Transaction - Pivot Table </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:0px dotted #000000; ">
        
		<table id="input" border="0" width="100%" >
        
           <thead>	
		   <tr>
 	       <th> No </th> <th> Period </th> <th> Tuitions </th> <th> Date </th> <th> Fee </th> <th> Department </th> <th> Grade </th> 
           <th> Student </th> <th> School </th> <th> Practical </th> <th> OSIS </th> <th> Computer </th> <th> Cost </th> 
           <th> BOS </th> <th> Foundation </th> <th> Balance </th> <th> Status </th> <th> Month </th> <th> User </th>
		   </tr>
           </thead>
		    
          <tbody>  
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
			  
		    function month_stts($val)
			{
				$res=0;
				switch ($val)
				{
				  case 'p1':$res = 1; break;
				  case 'p2':$res = 2; break;
				  case 'p3':$res = 3; break;
				  case 'p4':$res = 4; break;
				  case 'p5':$res = 5; break;
				  case 'p6':$res = 6; break;
				  case 'p7':$res = 7; break;
				  case 'p8':$res = 8; break;
				  case 'p9':$res = 9; break;
				  case 'p10':$res = 10; break;
				  case 'p11':$res = 11; break;
				  case 'p12':$res = 12; break;
				}
				return $res;
			}
			  
		  function month($val)
		  {
			  $ps = new Payment_status_lib();
			  return $ps->months_name(month_stts($val));
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
					   <td class=\"strongs\" align=\"right\">".$res->school_fee."</td>
					   <td class=\"strongs\" align=\"right\">".$res->practical."</td> 
					   <td class=\"strongs\" align=\"right\">".$res->osis."</td>
					   <td class=\"strongs\" align=\"right\">".$res->computer."</td> 
					   <td class=\"strongs\" align=\"right\">".$res->cost."</td>
					   <td class=\"strongs\" align=\"right\">".$res->aid_goverment."</td> 
					   <td class=\"strongs\" align=\"right\">".$res->aid_foundation."</td>
					   <td class=\"strongs\" align=\"right\">".$res->amount."</td> 
					   <td class=\"strongs\" align=\"center\">".status($res->type)."</td>
					   <td class=\"strongs\" align=\"center\">".month($res->month)."</td>
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
          </tbody>
		   
		</table>
	</div>
    

    <div style='margin-top: 30px;' id="output"> </div>

</div>

</body>
</html>
