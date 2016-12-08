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
            <tr> <td> Department </td> <td> : </td> <td> <?php echo $dept; ?> </td> </tr>
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo $rundate; ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Registration - Financial Pivottable </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:1px dotted #000000; ">
	
    	<div id='jqxWidget'>
        <div style='margin-top:10px;' id="output"> </div>        
        </div>
    
		<table id="input" width="100%">
		   <thead>
           <tr>
 	       <th> No </th> <th> Date </th> <th> Order No </th> <th> Periode </th> <th> Department </th> <th> Student </th> <th> Faculty </th> 
           <th> Registration </th> <th> Development </th> <th> School </th> <th> OSIS </th> <th> Practical </th> <th> Cost </th> <th> Total </th> <th> P1 </th> <th> P2 </th>
           <th> P2-Date </th> <th> P-Status </th> <th> P-Type </th> <th> Log </th>
		   </tr>
           </thead>
		    
          <tbody>  
		  <?php 	
		  
		  	  function dept($val){ $res = new Dept_lib(); return $res->get_name($val);  }
			  
			  function student($val){ $st = new Student_lib(); return $st->get_name($val); }
			  
			  function faculty($student)
			  {
				  $st = new Student_lib();
				  $faculty = new Faculty_lib();
				  $student = $faculty->get_name($st->get_faculty($student)); 
				  return $student;
			  }
		  
		      $i=1; 
			  if ($result)
			  {
				foreach ($result as $res)
				{	
				   echo " 
				   <tr> 
				       <td>".$i."</td> 
					   <td>".tglin($res->dates)."</td> 
					   <td> REG-0".$res->no."</td> 
					   <td>".$res->financial_year."</td> 
   					   <td>".dept($res->dept_id)."</td> 
					   <td>".student($res->student_id)."</td>
					   <td>".faculty($res->student_id)."</td>
					   <td>".$res->register."</td>
					   <td>".$res->development."</td> 
					   <td>".$res->school."</td>
					   <td>".$res->osis."</td>
					   <td>".$res->practice."</td>
					   <td>".$res->others."</td>
					   <td>".$res->total."</td>
					   <td>".$res->p1."</td>
					   <td>".$res->p2."</td>
					   <td>".$res->p2date."</td>
					   <td>".$res->payment_status."</td>
					   <td>".$res->payment_type."</td>
   					   <td>".$res->log."</td>
				   </tr>";
				   $i++;
				}
			  }  
		  ?>
		  </tbody> 
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
