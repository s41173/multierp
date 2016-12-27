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
            <tr> <td> Year </td> <td> : </td> <td> <?php echo $year; ?> </td> </tr>
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo $rundate; ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Payroll Summary Report </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:1px dotted #000000; ">
	
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Code </th> <th> Currency </th> <th> Period </th> <th> Date </th> <th> Honor </th> <th> Salary </th>
           <th> Bonus </th> <th> Consumption </th> <th> Transportation </th> <th> Overtime </th> <th> Late </th> <th> Loan </th>
           <th> Insurance </th> <th> Tax </th> <th> Other Deduction </th> <th> Balance </th> <th> Log </th>
		   </tr>
		    
		  <?php 
		  
		  
		      $i=1; 
			  if ($payroll)
			  {
				$honor = 0;
				$salary = 0;
				$bonus = 0;
				$consumption = 0;
				$transportation = 0;
				$overtime = 0;
				$late = 0;
				$loan = 0;
				$insurance = 0;
				$tax = 0;
				$other = 0;
				$balance = 0;
				 
				foreach ($payroll as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\"> TJ-00".$res->id."</td> 
					   <td class=\"strongs\">".$res->currency."</td> 
					   <td class=\"strongs\">".tglmonth($res->dates)."</td> 
					   <td class=\"strongs\">".tglin($res->dates)."</td> 
					   <td class=\"strongs\" align=\"right\">".number_format($res->total_honor)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->total_salary)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->total_bonus)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->total_consumption)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->total_transportation)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->total_overtime)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->total_late)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->total_loan)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->total_insurance)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->total_tax)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->total_other)."</td>
					   <td class=\"strongs\" align=\"right\">".number_format($res->balance)."</td>
					   <td class=\"strongs\" align=\"center\">".$res->log."</td> 
				   </tr>";
				   $i++;
				   
				   $honor          = $honor + $res->total_honor;
				   $salary         = $salary + $res->total_salary;
				   $bonus          = $bonus + $res->total_bonus;
				   $consumption    = $consumption + $res->total_consumption;
				   $transportation = $transportation + $res->total_transportation;
				   $overtime       = $overtime + $res->total_overtime;
				   $late           = $late + $res->total_late;
				   $loan           = $loan + $res->total_loan;
				   $insurance      = $insurance + $res->total_insurance;
				   $tax            = $tax + $res->total_tax;
				   $other          = $other + $res->total_other;
				   $balance        = $balance + $res->balance;
				}
			  }  
		  ?>
          
          <tr>
          	  <td colspan="5" class="strongs" align="right"> <b> Total : </b> </td>
              <td class="strongs" align="right"> <b> <?php echo number_format(isset($honor) ? $honor : '0'); ?> </b> </td>
              <td class="strongs" align="right"> <b> <?php echo number_format(isset($salary) ? $salary : '0'); ?> </b> </td>
              <td class="strongs" align="right"> <b> <?php echo number_format(isset($bonus) ? $bonus : '0'); ?> </b> </td>
              <td class="strongs" align="right"> <b> <?php echo number_format(isset($consumption) ? $consumption : '0'); ?> </b> </td>
              <td class="strongs" align="right"> <b> <?php echo number_format(isset($transportation) ? $transportation : '0'); ?> </b> </td>
              <td class="strongs" align="right"> <b> <?php echo number_format(isset($overtime) ? $overtime : '0'); ?> </b> </td>
              <td class="strongs" align="right"> <b> <?php echo number_format(isset($late) ? $late : '0'); ?> </b> </td>
              <td class="strongs" align="right"> <b> <?php echo number_format(isset($loan) ? $loan : '0'); ?> </b> </td>
              <td class="strongs" align="right"> <b> <?php echo number_format(isset($insurance) ? $insurance : '0'); ?> </b> </td>
              <td class="strongs" align="right"> <b> <?php echo number_format(isset($tax) ? $tax : '0'); ?> </b> </td>
              <td class="strongs" align="right"> <b> <?php echo number_format(isset($other) ? $other : '0'); ?> </b> </td>
              <td class="strongs" align="right"> <b> <?php echo number_format(isset($balance) ? $balance : '0'); ?> </b> </td>
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
