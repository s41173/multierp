<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title> <?php echo isset($title) ? $title : ''; ?>  </title>
<style media="all">
	table{ font-family:"Times New Roman", Times, serif; font-size:11px;}
	table th{ font-family:arial; font-size:10pt;}
	h4{ font-family:"Times New Roman", Times, serif; font-size:14px; font-weight:600;}
	.clear{clear:both;}
	table th{ background-color:#EFEFEF; padding:4px 0px 4px 0px; border-top:1px solid #000000; border-bottom:1px solid #000000;}
    p{ font-family:"Times New Roman", Times, serif; font-size:12px; margin:0; padding:0;}
	legend{font-family:"Times New Roman", Times, serif; font-size:13px; margin:0; padding:0; font-weight:600;}
	.tablesum{ font-size:13px;}
	.strongs{ font-weight:normal; font-size:12px; border-top:1px dotted #000000; text-transform:uppercase; }
	.poder{ border-bottom:0px solid #000000; color:#0000FF;}
</style>
</head>

<body>

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0">
    		<tr> <td> Department </td> <td> : </td> <td> <?php echo $department; ?> </td> </tr>
			<tr> <td> Faculty </td> <td> : </td> <td> <?php echo tgleng($faculty); ?> </td> </tr>
            <tr> <td> Grade </td> <td> : </td> <td> <?php echo tgleng($grade); ?> </td> </tr>
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo date('d-m-Y'); ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Fee Payment Status Report </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:1px dotted #000000; ">
	
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Student </th> <th> Department </th> <th> Faculty </th> <th> Grade </th>
		   </tr>
		    
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
					   <td class=\"strongs\">".dept($res->students_id)."</td>
					   <td class=\"strongs\">".faculty($res->students_id)."</td>
					   <td class=\"strongs\">".$res->grade."</td> 
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
