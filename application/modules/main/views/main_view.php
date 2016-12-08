
<div id="webadmin">

	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<table style="width:100%;">
		<tr> <td rowspan="3" style="width:30px; background-color:#CCCCCC; "></td> <td style="font-size:18px; font-weight:bold; padding:10px; ">WEB-ADMIN - 1.0.2 - 
		<?php echo $name; ?> System</td> </tr>
		<tr> <td style="background-color:#CCCCCC; color:#FFFFFF; padding:5px 5px 5px 10px; font-weight:bold; "> <div class="garis"> </div> </td> </tr>
		<tr> <td style="background-color:#F2F2F2; padding:5px 5px 5px 10px;"> IP Adress : <b style="color:#790F0F;"> <?php echo $this->input->ip_address(); ?> - <?php echo $user_agent; ?> </b> |  
		Last Login : <b style="color:#790F0F;"> <?php echo $this->session->userdata('waktu'); ?> </b> |  
		Period : <b style="color:#790F0F;"> <?php echo $month.' '.$year; ?> </b>  </td> </tr>
	</table>  
	
	<hr>
	
	<div id="iconplace">
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/vendor/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/member.png';?>">
				<p>Vendor / Supplier</p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/customer/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/customer.png';?>">
				<p>Customer</p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/purchase/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/purchase.png';?>">
				<p>Purchase</p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/assembly/';?>">
				<img alt="Assembly Menu" src="<?php echo base_url().'images/assembly.jpg';?>">
				<p> Assembly Process </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/sales/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/sales.png';?>">
				<p> Sales </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/journal/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/settlement.png';?>">
				<p> Journal Transaction </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/product/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/inventory.png';?>">
				<p> Inventory </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/warehouse_transaction/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/backup.png';?>">
				<p> Warehouse Transaction </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/checkin/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/receipt.png';?>">
				<p> Check - In </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/checkout/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/receipt.png';?>">
				<p> Check - Out </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/admin/';?>">
				<img alt="user" src="<?php echo base_url().'images/user.png';?>">
				<p>User</p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/roles/';?>">
				<img alt="modul" src="<?php echo base_url().'images/role.png';?>">
				<p>Role</p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/log/';?>">
				<img alt="log" src="<?php echo base_url().'images/log.png';?>">
				<p>History</p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/component/';?>">
				<img alt="modul" src="<?php echo base_url().'images/modul.png';?>">
				<p>Component</p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/configuration/';?>">
				<img alt="configuration" src="<?php echo base_url().'images/config.png';?>">
				<p>Configuration</p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/installation/';?>">
				<img alt="setting" src="<?php echo base_url().'images/setting.png';?>">
				<p> Installation </p>
			</a>
		  </center>
		</div>
		
		<div class="clear"></div>
		
	</div>
	
	<style type="text/css">
		
		.modboxleft{ float:left; width:500px; border:1px solid #CCCCCC; margin:30px 1% 0 0; height:300px;}
        .modboxleft h4{ font-family:Tahoma; font-size:14px; width:100%; background-color:#97b9f1; color:#3B5998; padding:5px 0 5px 5px; }
		.modboxright{ float:right; width:50%; border:1px solid #CCCCCC; margin:30px 0 0 0; height:300px; overflow:auto; }
		.modboxright table{ margin-bottom:5px;}
	    .modboxright h4{ font-family:Tahoma; font-size:14px; width:100%; background-color:#3B5998; color:#fff; padding:5px 0 5px 5px; }
		.checkmodbox{ width:47%; border:1px solid #CCCCCC; height:250px; float:left; margin:30px 15px 10px 10px; overflow:auto;}
	    .checkmodbox h4{ font-family:Tahoma; font-size:14px; width:100%; background-color:#3B5998; color:#fff; padding:5px 0 5px 5px; }
		
		@media screen and (max-width: 1280px)
		{
			.modboxright{ float:left; max-width:480px;}
			.checkmodbox{ margin:30px 20px 10px 0px;}
		}
		
		@media screen and (max-width: 1024px)
		{
			.modboxright{ float:left; max-width:280px;}
			.checkmodbox{ margin:30px 20px 10px 0px;}
		}
		
			/* layar 800 dan tablet */
		@media screen and (max-width: 800px)
		{
			.modboxright{ min-width:600px; float:left;}
			.checkmodbox{ min-width:600px; float:left; }
		}
		
		@media screen and (max-width: 640px)
		{
			.modboxright{ min-width:500px; float:left;}
			.checkmodbox{ min-width:500px; float:left; }
		}
	
	</style>
	
	<script type="text/javascript" src="<?php echo base_url();?>public/javascripts/FusionCharts.js"></script>
	
	<div class="modboxleft">
		
		<h4> Account Receivable Due - Chart (IDR) </h4>
		
		<?php  echo ! empty($graph) ? $graph : '';  ?>
	</div>
	
	<div class="modboxright">
		<h4> Account Receivable Due (IDR) </h4>
		<?php  echo ! empty($salestable) ? $salestable : '';  ?>
	</div>
	
	<div class="modboxleft">
		
		<h4> Payable Due Chart (IDR) </h4>
		<?php  echo ! empty($graph1) ? $graph1 : '';  ?>
	</div>
	
	<div class="modboxright">
		<h4> Payable Due (IDR) </h4>
		<?php  echo ! empty($purchasetable) ? $purchasetable : '';  ?>
	</div> 
	
	<div class="checkmodbox"> 
		<h4> Received Post-Dated Checks </h4>
		<?php  echo ! empty($checkintable) ? $checkintable : '';  ?>
	</div>
	
	<div class="checkmodbox"> 
		<h4> Post-Dated Checks Issuance </h4>
		<?php  echo ! empty($checkouttable) ? $checkouttable : '';  ?>
	</div>
	
	<div class="checkmodbox"> 
		<h4> Post-Dated Payment Checks Issuance </h4>
		<?php  echo ! empty($checkouttablepayment) ? $checkouttablepayment : '';  ?>
	</div>
	
	<div class="checkmodbox"> 
		<h4> Stock Minimum </h4>
		<?php  echo ! empty($producttable) ? $producttable : '';  ?>
	</div>
	
	
	
	<div class="clear"></div>
	
</div>