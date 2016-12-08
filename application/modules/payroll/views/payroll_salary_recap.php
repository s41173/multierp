<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title> <?php echo isset($title) ? $title : ''; ?>  </title>
<style media="all">
	
	table{ font-family:"Arial", Times, serif; font-size:11px;}
	table th{ font-family:arial; font-size:10pt;}
	h4{ font-family:"Arial", Times, serif; font-size:14pt; font-weight:600; margin:0;}
	.clear{clear:both;}
	table th{ background-color:#000; color:#fff; padding:4px 0px 4px 0px; border-top:1px solid #000000; border-bottom:1px solid #000000;}
    p{ font-family:"Arial", Times, serif; font-size:12px; margin:0; padding:0;}
	legend{font-family:"Arial", Times, serif; font-size:13px; margin:0; padding:0; font-weight:600;}
	.tablesum{ font-size:13px;}
	
	legend{font-family:arial; font-size:12pt; margin:0; padding:0; font-weight:600;}
	.strongs{ font-weight:normal; font-size:10pt; border-top:0px dotted #000000; margin:0; padding:0; }
	.bold{ font-weight:bold; text-align:right; background-color:#000; color:#fff; padding:5px;}
	.balance{ font-weight:normal; font-size:11pt; border-top:0px dotted #000000; margin:0; padding:0; }
	.balances{ font-weight: bold; font-size:11pt; border-top:0px dotted #000000; margin:0; padding:0; }
	.poder{ border-bottom:0px solid #000000; color:#0000FF;}
	.gross{ color:#900;}
	.net{ color:#009;}
	h4{ font-size:14pt; margin:0; font-family:Arial, Helvetica, sans-serif; text-align:center;}
	p{ font-size:10pt;}
	fieldset{ border:none; border-bottom:1px solid #000; padding:0; margin:0; }
</style>
</head>

<body onLoad="window.print()">

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0" style="font-size:9pt;">
			<tr> <td> No </td> <td> : </td> <td> <?php echo $pono; ?> </td> </tr>
			<tr> <td> Date </td> <td> : </td> <td> <?php echo $date; ?> </td> </tr>
			<tr> <td> Currency </td> <td> : </td> <td> <?php echo $cur; ?> </td> </tr>
		</table>
	</div>
	
	<div style="border:0px solid red; float:right;">
		<table border="0" style="font-size:9pt;">
			<tr> <td> Print Date </td> <td> : </td> <td> <?php echo tgleng(date('Y-m-d')); ?> </td> </tr>
            <tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
            <tr> <td> Period </td> <td> : </td> <td> <?php echo $period; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:500px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> </h4>
           <p style="margin:5px; padding:0; font-size:10pt;"> <?php echo $address; ?> <br> Telp. <?php echo $phone1.' - '.$phone2; ?> <br>
               Website : <?php echo $website; ?> &nbsp; &nbsp; Email : <?php echo $email; ?> </p>
	   </div>
	</center> <hr>
    
    <h4> Salary Recapitulation - Report </h4>
   
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:1px dotted #000000; ">
		
 	<table border="0" width="100%">
	<tr> <th> No </th> <th> Division </th> <th> Salary </th> <th> Consumption </th> <th> Transport </th> <th> Overtime </th> <th> Bonus </th> 
  	     <th> Loan </th> <th> Tax </th> <th> Insurance </th> <th> Late Charge </th> <th> Other </th> <th> Amount </th>
    </tr>
		    
		  <?php 
			  
			  function get_role($val)
			  {
				$res = null;
				switch ($val)
				{
					case 1: $res = 'CHAIRMAN'; break;
					case 2: $res = 'VICE CHAIRMAN'; break;
					case 3: $res = 'SECRETARY'; break;
					case 4: $res = 'DEPUTY SECRETARY'; break;
					case 5: $res = 'TREASURE'; break;
					case 6: $res = 'DEPUTY TREASURE'; break;
					case 7: $res = 'COORDINATOR'; break;
					default: $res = NULL;
				}
				return $res;
			  }
			  
			  function get_amount($payid,$division,$no)
			  {
				  $trans = new Payroll_trans_lib();
				  $res = $trans->get_salary_amount($payid,$division,'salary')->row_array();
				  
				  switch ($no)
				  {
					  case 1: $val = intval($res['basic_salary'] + $res['experience']); break;
					  case 2: $val = intval($res['consumption']); break;
					  case 3: $val = intval($res['transportation']); break;
					  case 4: $val = intval($res['overtime']); break;
					  case 5: $val = intval($res['bonus']); break;
					  case 6: $val = intval($res['loan']); break;
					  case 7: $val = intval($res['tax']); break;
					  case 8: $val = intval($res['insurance']); break;
					  case 9: $val = intval($res['late']); break;
					  case 10: $val = intval($res['other_discount']); break;
					  case 11: $val = intval($res['amount']); break;
					  default: $val=0;
				  }
				  return $val;
			  }
		  		  
		      $i=1; 
			  if ($results)
			  {
				$tot_salary = 0;
				$tot_consumption = 0;
				$tot_transportation = 0;
				$tot_overtime = 0;
				$tot_bonus = 0;
				$tot_loan = 0;
				$tot_tax = 0;
				$tot_insurance = 0;
				$tot_late = 0;
				$tot_other = 0;
				$tot_amount = 0;
				
				foreach ($results as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\" align=\"center\">".$i."</td> 
					   <td class=\"strongs\" align=\"left\">".$res->name."</td>
					   <td class=\"strongs\" align=\"right\"> ".number_format(get_amount($payroll_id,$res->id,1))." </td>
					   <td class=\"strongs\" align=\"right\"> ".number_format(get_amount($payroll_id,$res->id,2))." </td>
					   <td class=\"strongs\" align=\"right\"> ".number_format(get_amount($payroll_id,$res->id,3))." </td>
					   <td class=\"strongs\" align=\"right\"> ".number_format(get_amount($payroll_id,$res->id,4))." </td>
					   <td class=\"strongs\" align=\"right\"> ".number_format(get_amount($payroll_id,$res->id,5))." </td>
					   <td class=\"strongs\" align=\"right\"> ".number_format(get_amount($payroll_id,$res->id,6))." </td>
					   <td class=\"strongs\" align=\"right\"> ".number_format(get_amount($payroll_id,$res->id,7))." </td>
					   <td class=\"strongs\" align=\"right\"> ".number_format(get_amount($payroll_id,$res->id,8))." </td>
					   <td class=\"strongs\" align=\"right\"> ".number_format(get_amount($payroll_id,$res->id,9))." </td>
					   <td class=\"strongs\" align=\"right\"> ".number_format(get_amount($payroll_id,$res->id,10))." </td>
					   <td class=\"strongs\" align=\"right\"> ".number_format(get_amount($payroll_id,$res->id,11))." </td>
				   </tr>";
				   
				   $tot_salary = $tot_salary + get_amount($payroll_id,$res->id,1);
				   $tot_consumption = $tot_consumption + get_amount($payroll_id,$res->id,2);
				   $tot_transportation = $tot_transportation + get_amount($payroll_id,$res->id,3);
				   $tot_overtime = $tot_overtime + get_amount($payroll_id,$res->id,4);
				   $tot_bonus = $tot_bonus + get_amount($payroll_id,$res->id,5);
				   $tot_loan = $tot_loan + get_amount($payroll_id,$res->id,6);
				   $tot_tax = $tot_tax + get_amount($payroll_id,$res->id,7);
				   $tot_insurance = $tot_insurance + get_amount($payroll_id,$res->id,8);
				   $tot_late = $tot_late + get_amount($payroll_id,$res->id,9);
				   $tot_other = $tot_other + get_amount($payroll_id,$res->id,10);
				   $tot_amount = $tot_amount + get_amount($payroll_id,$res->id,11);
				   
				   $i++;
				}
			  }  
			  
		  ?>
          
    <tr class="border_top">  
    <td class="strongs bold" colspan="2"> Total : </td>
    <td class="strongs bold"> <?php echo number_format($tot_salary); ?> </td>
    <td class="strongs bold"> <?php echo number_format($tot_consumption); ?> </td>
    <td class="strongs bold"> <?php echo number_format($tot_transportation); ?> </td>
    <td class="strongs bold"> <?php echo number_format($tot_overtime); ?> </td>
    <td class="strongs bold"> <?php echo number_format($tot_bonus); ?> </td>
    <td class="strongs bold"> <?php echo number_format($tot_loan); ?> </td>
    <td class="strongs bold"> <?php echo number_format($tot_tax); ?> </td>
    <td class="strongs bold"> <?php echo number_format($tot_insurance); ?> </td>
    <td class="strongs bold"> <?php echo number_format($tot_late); ?> </td>
    <td class="strongs bold"> <?php echo number_format($tot_other); ?> </td>
    <td class="strongs bold"> <?php echo number_format($tot_amount); ?> </td>
    </tr>
          
		</table>
	</div>

	<!--SIGN-->
    
    <div style="border:0px solid red; float:right; margin:20px 30px 0px 0px;">
		<p style="text-align:center;"> Reviewed By : </p> <br>
        <p style="text-align:center;margin-top:60px; text-decoration:underline; text-transform:uppercase;"> <?php echo $director; ?> </p>
        <p style="text-align:center;"> CHAIRMAN </p>
	</div>
    
    <div style="border:0px solid red; float:right; margin:20px 45px 0px 0px;">
		<p style="text-align:center;"> Approved By : </p> <br>
        <p style="text-align:center;margin-top:60px; text-decoration:underline; text-transform:uppercase;"> <?php echo $manager; ?> </p>
        <p style="text-align:center;"> FINANCE MANAGER </p>
	</div>
    
    <div style="border:0px solid red; float:right; margin:20px 45px 0px 0px;">
		<p style="text-align:center;"> Prepared By : </p> <br>
        <p style="text-align:center;margin-top:60px; text-decoration:underline; text-transform:uppercase;"> <?php echo $user; ?> </p>
        <p style="text-align:center; text-transform:capitalize;"> HCM - ADMIN </p>
	</div>
    
    <!--SIGN-->

</div>

</body>
</html>
