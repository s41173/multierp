
<div id="webadmin">

	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<table style="width:100%;">
		<tr> <td rowspan="3" style="width:30px; background-color:#CCCCCC; "></td> <td style="font-size:18px; font-weight:bold; padding:10px; ">WEB-ADMIN - 1.0.2 - 
		<?php echo $name; ?> System</td> </tr>
		<tr> <td style="background-color:#CCCCCC; color:#FFFFFF; padding:5px 5px 5px 10px; font-weight:bold; "> <div class="garis"> </div> </td> </tr>
		<tr> <td style="background-color:#F2F2F2; padding:5px 5px 5px 10px;"> IP Adress : <b style="color:#790F0F;"> <?php echo $this->input->ip_address(); ?> - <?php echo $user_agent; ?> </b> |  
		Last Login : <b style="color:#790F0F;"> <?php echo $this->session->userdata('waktu'); ?> </b> </td> </tr>
	</table>  
	
	<hr>
	
	<div id="iconplace">
		
        <div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/division/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/division.png';?>">
				<p> Division </p>
			</a>
		  </center>
		</div>
		
        
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/employees/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/employee.png';?>">
				<p> Employee </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/loan/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/loan.png';?>">
				<p> Employee's Loan </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/experience/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/experience.png';?>">
				<p> Experience </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/honorfee/';?>">
				<img alt="Assembly Menu" src="<?php echo base_url().'images/payroll.png';?>">
				<p> Employee's Honor </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/overtime/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/overtime.png';?>">
				<p> Overtime </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/attendance/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/attendance.png';?>">
				<p> Attendance </p>
			</a>
		  </center>
		</div>
        
        <div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/honor_absence/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/calendar.png';?>">
				<p> Honor Attendance </p>
			</a>
		  </center>
		</div>
        
        <div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/payroll/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/payroll_trans.png';?>">
				<p> Payroll Transactions </p>
			</a>
		  </center>
		</div>
        
         <div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/loan_trans/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/loan_trans.png';?>">
				<p> Loan Transactions </p>
			</a>
		  </center>
		</div>
		
        
		
		<div class="clear"></div>
		
	</div>
	
	<div class="clear"></div>
	
</div>