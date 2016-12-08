<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title> <?php echo isset($title) ? $title : ''; ?>  </title>
<style media="all">
	table{ font-family:"Arial", Times, serif; font-size:9pt;}
	table th{ font-family:arial; font-size:9pt;}
	h4{ font-family:"Arial", Times, serif; font-size:14pt; font-weight:600; margin:0;}
	.clear{clear:both;}
	table th{ background-color:#000; color:#fff; padding:4px 0px 4px 0px; border-top:1px solid #000000; border-bottom:1px solid #000000;}
    p{ font-family:"Arial", Times, serif; font-size:12px; margin:0; padding:0;}
	legend{font-family:"Arial", Times, serif; font-size:13px; margin:0; padding:0; font-weight:600;}
	.tablesum{ font-size:13px;}
	.strongs{ font-weight:normal; font-size:12px; border-top:1px dotted #000000; border-right:1px dotted #000; text-transform:capitalize; }
	.poder{ border-bottom:0px solid #000000; color:#0000FF;}
	.red{ color:#00C; font-weight:bold; font-size:9pt;}
</style>
</head>

<body onLoad="window.print();">

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0">
            <tr> <td> Period </td> <td> : </td> <td> <?php echo get_month($month).' - '.$year; ?> </td> </tr>
            <tr> <td> Run Date </td> <td> : </td> <td> <?php echo date('d-m-Y'); ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:500px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> </h4>
           <p style="margin:5px; padding:0;"> <?php echo $address; ?> <br> Telp. <?php echo $phone1.' - '.$phone2; ?> <br>
               Website : <?php echo $website; ?> &nbsp; &nbsp; Email : <?php echo $email; ?> </p>
	   </div>
	</center> <hr>
    
    <p style="text-align:center; font-size:14pt; font-weight:bold;"> Payroll Transaction Report </p>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border:1px solid #000; ">
	
		<table id="table" border="0" width="100%">
		   
           <thead>
           <tr>
  	       <th> No </th> <th> Division </th> <th> Employee </th>  <th> Dept </th> <th> Payment </th> <th> Basic </th> <th> Experience </th> 
           <th> Consumption </th> <th> Transportation </th> <th> Overtime </th> <th> Bonus </th> <th> Principal </th> <th> PKS </th> 
           <th> Kajur </th> <th> Guardian's </th> <th> Picket </th> <th> Late </th> <th> Loan </th> <th> Tax </th> <th> Insurance </th> 
           <th> Other </th> <th> Amount </th> <th> User </th> <th> Log </th>
		   </tr>
           </thead>
		    
          <tbody>  
		  <?php 
		  
		  	  function employee($val)
			  {
				  $emp = new Employee_lib();
				  if ($val == 0) { $res = 'Non'; } else { $res = $emp->get_name($val); } return $res;
			  }	
			  
			  function dept($val)
			  { 
			    $dept = new Dept_lib(); 
				if ($val == 0) { $res = 'Non'; }else { $res = $dept->get_name($val); } return $res;
			  }
			  
			  function division($val)
			  {
				  $division = new Division_lib();
				  $employee = new Employee_lib();
				  $division_id = $employee->get_division_by_id($val);
				  return $division->get_name($division_id);
		      }
			  		  		  
		      $i=1; 
			  if ($results)
			  {
				 $tot_salary = 0; 
				 $tot_experience = 0; 
				 $tot_consumption = 0; 
				 $tot_transport = 0; 
				 $tot_overtime = 0; 
				 $tot_bonus = 0; 
				 $tot_principal = 0; 
				 $tot_principal_helper = 0; 
				 $tot_head = 0; 
				 $tot_home = 0; 
				 $tot_picket = 0; 
				 $tot_loan = 0; 
				 $tot_late = 0; 
				 $tot_tax = 0; 
				 $tot_insurance = 0; 
				 $tot_other = 0; 
				 $tot_amount = 0; 
				  
				foreach ($results as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\" align=\"center\">".$i."</td> 
					   <td class=\"strongs\">".division($res->employee_id)."</td>
					   <td class=\"strongs\">".employee($res->employee_id)."</td>
					   <td class=\"strongs\" align=\"righ\">".dept($res->dept)."</td>
					   <td class=\"strongs\" align=\"righ\">".$res->payment_type."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->basic_salary)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->experience)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->consumption)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->transportation)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->overtime)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->bonus)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->principal)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->principal_helper)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->head_department)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->home_room)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->picket)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->late)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->loan)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->tax)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->insurance)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->other_discount)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->amount)."</td>
					   <td class=\"strongs\" align=\"center\">".$res->user."</td>
					   <td class=\"strongs\" align=\"center\">".$res->log."</td>
				   </tr>";
				   $i++;
				   
				   // total
				   $tot_salary = $tot_salary + $res->basic_salary;
				   $tot_experience = $tot_experience + $res->experience;
				   $tot_consumption = $tot_consumption + $res->consumption;
				   $tot_transport = $tot_transport + $res->transportation;
				   $tot_overtime = $tot_overtime + $res->overtime;
				   $tot_bonus = $tot_bonus + $res->bonus;
				   $tot_principal = $tot_principal + $res->principal;
				   $tot_principal_helper = $tot_principal_helper + $res->principal_helper;
				   $tot_head   = $tot_head + $res->head_department;
				   $tot_home   = $tot_home + $res->home_room;
				   $tot_picket = $tot_picket + $res->picket;
				   $tot_loan   = $tot_loan + $res->loan;
				   $tot_late   = $tot_late + $res->late;
				   $tot_tax    = $tot_tax + $res->tax;
				   $tot_insurance = $tot_insurance + $res->insurance;
				   $tot_other = $tot_other + $res->other_discount;
				   $tot_amount = $tot_amount + $res->amount;
				}
			  }  
		  ?>
        
        <tr>  
        <td colspan="3"></td>
        <td class="strongs" align="center" colspan="2"> <b> Total : </b> </td>
        <td class="strongs red" align="right"> <?php echo number_format(isset($tot_salary) ? $tot_salary : 0); ?> </td>
        <td class="strongs red" align="right"> <?php echo number_format(isset($tot_experience) ? $tot_experience : 0); ?> </td>
        <td class="strongs red" align="right"> <?php echo number_format(isset($tot_consumption) ? $tot_consumption : 0); ?> </td>
        <td class="strongs red" align="right"> <?php echo number_format(isset($tot_transport) ? $tot_transport : 0); ?> </td>
        <td class="strongs red" align="right"> <?php echo number_format(isset($tot_overtime) ? $tot_overtime : 0); ?> </td>
        <td class="strongs red" align="right"> <?php echo number_format(isset($tot_bonus) ? $tot_bonus : 0); ?> </td>
        <td class="strongs red" align="right"> <?php echo number_format(isset($tot_principal) ? $tot_principal : 0); ?> </td>
        <td class="strongs red" align="right"> <?php echo number_format(isset($tot_principal_helper) ? $tot_principal_helper : 0); ?> </td>
        <td class="strongs red" align="right"> <?php echo number_format(isset($tot_head) ? $tot_head : 0); ?> </td>
        <td class="strongs red" align="right"> <?php echo number_format(isset($tot_home) ? $tot_home : 0); ?> </td>
        <td class="strongs red" align="right"> <?php echo number_format(isset($tot_picket) ? $tot_picket : 0); ?> </td>
        <td class="strongs red" align="right"> <?php echo number_format(isset($tot_late) ? $tot_late : 0); ?> </td>
        <td class="strongs red" align="right"> <?php echo number_format(isset($tot_loan) ? $tot_loan : 0); ?> </td>
        <td class="strongs red" align="right"> <?php echo number_format(isset($tot_tax) ? $tot_tax : 0); ?> </td>
        <td class="strongs red" align="right"> <?php echo number_format(isset($tot_insurance) ? $tot_insurance : 0); ?> </td>
        <td class="strongs red" align="right"> <?php echo number_format(isset($tot_other) ? $tot_other : 0); ?> </td>
        <td class="strongs red" align="right"> <?php echo number_format(isset($tot_amount) ? $tot_amount : 0); ?> </td>
        </tr> 
         
        </tbody>  
		</table>
	</div>

</div>

</body>
</html>
