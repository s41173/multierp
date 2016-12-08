
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
		  <center>	<a href="<?php echo base_url().'index.php/brand/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/brand.png';?>">
				<p> Product Brand </p>
			</a>
		  </center>
		</div>
		
        
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/category/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/stockcategory.png';?>">
				<p> Product Category </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/product/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/stock.png';?>">
				<p> Product </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/stock_in/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/in.png';?>">
				<p> Stock - IN </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/stock_out/';?>">
				<img alt="Assembly Menu" src="<?php echo base_url().'images/out.png';?>">
				<p> Stock - OUT </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/return_stock/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/return.png';?>">
				<p> Return Stock </p>
			</a>
		  </center>
		</div>
		
		<div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/opname/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/opname.png';?>">
				<p> Stock Opname </p>
			</a>
		  </center>
		</div>
        
        <div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/warehouse_transaction/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/warehouse_transaction.png';?>">
				<p> Warehouse Transaction </p>
			</a>
		  </center>
		</div>
        
        <div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/stock_adjustment/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/stock_adjustment.png';?>">
				<p> Stock Adjustment </p>
			</a>
		  </center>
		</div>
        
         <div class="icon">
		  <center>	<a href="<?php echo base_url().'index.php/exstock/';?>">
				<img alt="Admin Menu" src="<?php echo base_url().'images/exchange.png';?>">
				<p> Exchange Stock </p>
			</a>
		  </center>
		</div>
		
        
		
		<div class="clear"></div>
		
	</div>
	
	<div class="clear"></div>
	
</div>