
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
		  <center>	<a href="<?php echo base_url().'index.php/registration/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/registration.jpg';?>">
				<p> Registration Process </p>
			</a>
		  </center>
		</div>
		
        
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/registration/replacement/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/student_replacement.jpg';?>">
				<p> Student Placement </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/regcost/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/pcost.png';?>">
				<p> Registration Cost </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/unicost/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/ucost.png';?>">
				<p> Uniform Cost </p>
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
		
		
		<div class="clear"></div>
		
	</div>
	
	<div class="clear"></div>
	
</div>