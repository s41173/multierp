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
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo date('d-m-Y'); ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Students - Pivot Table </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:0px dotted #000000; ">
        
		<table id="input" border="0" width="100%" >
        
           <thead>	
		   <tr>
           <th> No </th> <th> Student </th> <th> NISN </th> <th> Department </th> <th> Faculty </th> <th> Grade </th> <th> Gender </th>
           </tr>
           </thead>
		    
          <tbody>  
		  <?php 
		  
		     function status($val)
			  { if ($val == 0){ $val = 'debt'; } else { $val = 'settled'; } return $val; }	
		  
		  	  function student($id)
			  {
				  $st = new Student_lib();
				  return $st->get_name($id).' - '.$st->get_nisn($id);
			  }
			  
			  function cekdate($date=null){ if ($date){ return tglin($date); }else { return '-'; }}
			  
			  function dept($sid)
			  {
				 $st = new Student_lib();
				 $dept = new Dept_lib();
				 
				 return $dept->get_name($st->get_dept($sid));
			  }
			  
			  function faculty($sid)
			  {
				 $st = new Student_lib();
				 $fac = new Faculty_lib();
				 
				 return $fac->get_code($st->get_faculty($sid));
			  }
		 
		  
		      $i=1; 
			  if ($students)
			  {
				foreach ($students as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".$res->name."</td>
					   <td class=\"strongs\">".$res->nisn."</td>
					   <td class=\"strongs\">".dept($res->students_id)."</td>
					   <td class=\"strongs\">".faculty($res->students_id)."</td>
					   <td class=\"strongs\">".$res->grade."</td> 
					   <td class=\"strongs\">".$res->genre."</td> 
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
