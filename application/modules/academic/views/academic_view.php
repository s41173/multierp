
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
		  <center>	<a href="<?php echo base_url().'index.php/employees/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/employee.png';?>">
				<p> Employee </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/generations/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/generation.png';?>">
				<p> Generation </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/semesters/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/semester.png';?>">
				<p> Semester </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/dept/';?>">
				<img alt="Assembly Menu" src="<?php echo base_url().'images/department.png';?>">
				<p> Department </p>
			</a>
		  </center>
		</div>
        
        <div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/level/';?>">
				<img alt="Assembly Menu" src="<?php echo base_url().'images/level.png';?>">
				<p> Level </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/financial_year/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/school_year.png';?>">
				<p> School Year </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/grade/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/class.png';?>">
				<p> Grade / Class </p>
			</a>
		  </center>
		</div>
        
        <div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/graduation/';?>">
				<img alt="Graduation" src="<?php echo base_url().'images/graduation.png';?>">
				<p> Graduation </p>
			</a>
		  </center>
		</div>
		
		
		<div class="clear"></div>
		
	</div>
	
	<div class="clear"></div>
	
</div>