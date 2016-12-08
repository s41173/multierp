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
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo $rundate; ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Graduation - Pivot Table </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:0px dotted #000000; ">
        
		<table id="input" border="0" width="100%" >
        
          <thead>	
		   <tr>
 	       <th> No </th> <th> Generation </th> <th> Student </th> <th> Department </th> <th> Grade </th> 
           <th> Date </th> <th> Credit </th> <th> School </th> <th> OSIS </th> <th> Computer </th> <th> Practice </th> <th> Cost </th> <th> Aid </th>
           <th> Amount </th> <th> Certificate </th> <th> Taking </th> <th> Status </th> <th> User </th> 
		   </tr>
           </thead>
		    
          <tbody>  
		  <?php 
		  
		  	  function status($val){ if ($val == 0){ $val = 'N'; } elseif ($val == 1){ $val = 'Y'; }return $val; }	
			  
			  function dept($student)
			  {
				  $st = new Student_lib();
				  $dept = new Dept_lib();
				  $student = $dept->get_name($st->get_dept($student)); 
				  return $student;
			  }
			  
			  function student($val){ $st = new Student_lib(); return $st->get_name($val); }
			  function user($val){ $user = new Admin_lib(); return $user->get_username($val); }
			  function grade($val)
			  { 
			    $st = new Student_lib(); 
				$gr = new Grade_lib();
				return $gr->get_name($st->get_grade($val));
			  }
			  
		  
		      $i=1; 
			  if ($result)
			  {  
				foreach ($result as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\" align=\"center\">".$res->year."</td> 
					   <td class=\"strongs\">".strtoupper(student($res->student_id))."</td> 
					   <td class=\"strongs\" align=\"center\">".dept($res->student_id)."</td>
					   <td class=\"strongs\" align=\"center\">".grade($res->student_id)."</td>
					   <td class=\"strongs\" align=\"center\">".tglin($res->dates)."</td>
					   <td class=\"strongs\" align=\"right\">".$res->credit."</td>
					   <td class=\"strongs\" align=\"right\">".$res->school."</td>
					   <td class=\"strongs\" align=\"right\">".$res->osis."</td>
				       <td class=\"strongs\" align=\"right\">".$res->computer."</td>
				       <td class=\"strongs\" align=\"right\">".$res->practice."</td>
   			           <td class=\"strongs\" align=\"right\">".$res->cost."</td>
					   <td class=\"strongs\" align=\"right\">".$res->aid."</td>
					   <td class=\"strongs\" align=\"right\">".$res->amount."</td> 
					   <td class=\"strongs\" align=\"right\">".$res->certificate_code."</td>
					    <td class=\"strongs\" align=\"right\">".tglin($res->taking_dates)."</td> 
					   <td class=\"strongs\" align=\"center\">".status($res->type)."</td>
					   <td class=\"strongs\" align=\"center\">".user($res->user)."</td> 
				   </tr>";
				   $i++;
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
