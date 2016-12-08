<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'development-bundle/themes/base/ui.all.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/jquery.fancybox-1.3.4.css'; ?>");</style>

<script type="text/javascript" src="<?php echo base_url();?>js/register.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/datetimepicker_css.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/development-bundle/ui/ui.core.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tools.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/hoverIntent.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/complete.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/sortir.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/validate.js"></script> 
<script type='text/javascript' src='<?php echo base_url();?>js/jquery.validate.js'></script>  

<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";
</script>

<script type="text/javascript">	
	function refreshparent() { opener.location.reload(true); }
	
	function set_amount_text()
	{
		var res = document.getElementById("ctype").value;
		if (res == 1) 
		{ 
		  document.getElementById("tamount").disabled = true; 
		  document.getElementById("ttax").disabled = true;
		  document.getElementById("ttax2").disabled = true; 
		  document.getElementById("tcost").disabled = true; 
		}
		else 
		{ 
		  document.getElementById("tamount").disabled = false; 
		  document.getElementById("ttax").disabled = false; 
  		  document.getElementById("ttax2").disabled = false; 
		  document.getElementById("tcost").disabled = false; 
		}
	}
	
</script>

<style>
        .refresh{ border:1px solid #AAAAAA; color:#000; padding:2px 5px 2px 5px; margin:0px 2px 0px 2px; background-color:#FFF;}
		.refresh:hover{ background-color:#CCCCCC; color: #FF0000;}
		.refresh:visited{ background-color:#FFF; color: #000000;}	
</style>

<?php 
		
$atts1 = array(
	  'class'      => 'refresh',
	  'title'      => 'add cust',
	  'width'      => '600',
	  'height'     => '400',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 400)/2)+\'',
);

?>

<body onUnload="refreshparent();">  
<div id="webadmin">
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field" style="float:left;">  <legend> AR - Customer Payment </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
						<td> <label for="tvendor"> Customer </label> </td> <td>:</td>
						<td> <input type="text" class="required" name="tcustomer" id="tcust" size="25" title="Name"
						value="<?php echo set_value('tcustomer', isset($default['customer']) ? $default['customer'] : ''); ?>" />
						<?php echo anchor_popup(site_url("customer/get_list/"), '[ ... ]', $atts1); ?>
					</tr>
					
					<tr>
			   <td> <label for="tdocno"> Docno </label> </td>  <td>:</td>
			   <td>  <input type="text" class="required" readonly name="tdocno" size="20" title="Doc No"
			         value="<?php echo set_value('tdocno', isset($default['docno']) ? $default['docno'] : ''); ?>" /> &nbsp; <br /> </td>
			       </tr>
					

					<tr>	
						 <td> <label for="tdate"> Date </label> </td> <td>:</td>
						 <td>  
						   <input type="Text" name="tdate" id="d1" title="Invoice date" size="10" class="required"
						   value="<?php echo set_value('tdate', isset($default['date']) ? $default['date'] : ''); ?>" /> 
				           <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> &nbsp; <br />
						</td>
					</tr>
					

			<tr>
			   <td> <label for="tacc"> Acc </label> </td>  <td>:</td>
			   <td>  <input type="text" class="required" readonly name="tacc" size="15" title="Acc"
			         value="<?php echo set_value('tacc', isset($default['acc']) ? $default['acc'] : ''); ?>" /> &nbsp; <br /> </td>
			</tr>
					
			<tr>	
			
			<td> <label for="tcurrency"> Currency </label> </td> <td>:</td>
			<td> <input type="text" class="required" readonly name="tcurrency" size="10" title="Currency"
  	             value="<?php echo set_value('tcurrency', isset($default['currency']) ? $default['currency'] : ''); ?>" /> &nbsp; <br /> </td>
			</tr>
					

					<tr>	
						<td> <label for="tuser"> AR - Dept </label> </td> <td>:</td>
						<td> <input type="text" class="required" readonly name="tuser" size="15" title="User"
						value="<?php echo set_value('tuser', isset($default['user']) ? $default['user'] : ''); ?>" /> &nbsp; <br /> </td>
					</tr>
					
					
				</table>  
	</fieldset>   
	
	<fieldset class="field" style="float:left; margin-left:15px;"> <legend> Payment Details </legend>
		
		<table>
			
			<tr>
				<td> <label for="tbalance"> Balance </label></td> <td>:</td> 
				<td><input type="text" id="tbalance" disabled="disabled" name="tbalance" readonly size="10" title="Balance" 
			    value="<?php echo set_value('tbalance', isset($default['balance']) ? $default['balance'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tbalance')" /> <br />  </td> 
			</tr>
			
		</table>
		
	</fieldset>
	
	<fieldset class="field" style="float:left; margin-left:15px;"> <legend> Check Details </legend>
		
		<table>
			
			<tr>
				<td> <label for="tcheck"> Check - No </label> </td>  <td>:</td>
				<td> <input type="text" name="tcheck" size="15" title="Check No"
				value="<?php echo set_value('tcheck', isset($default['check']) ? $default['check'] : ''); ?>" /> &nbsp; <br /> </td>
			</tr>
			
			<tr>	
			<td> <label for="cbank"> Bank </label> </td> <td>:</td>
			<td> <?php $js = 'class=""'; echo form_dropdown('cbank', $bank, isset($default['bank']) ? $default['bank'] : '', $js); ?> &nbsp; <br /> </td>
			</tr>
			
			<tr>
				<td> <label for="tbalancecek"> Balance </label></td> <td>:</td> 
				<td><input type="text" id="tbalancecek" readonly name="tbalancecek" size="10" title="Balance" 
			    value="<?php echo set_value('tbalancecek', isset($default['balancecek']) ? $default['balancecek'] : '0'); ?>" /> <br />  </td> 
			</tr>
			
			<tr>	
				 <td> <label for="tdue"> Due Date </label> </td> <td>:</td>
				 <td>  
				   <input type="Text" name="tdue" id="d3" title="Due date" size="10" class="required"
				   value="<?php echo set_value('tdue', isset($default['due']) ? $default['due'] : ''); ?>" /> 
				   <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d3','yyyymmdd')" style="cursor:pointer"/> &nbsp; <br />
				</td>
			</tr>
			
		</table>
		
	</fieldset>
	
	
	
	<p style="margin:10px 0 0 10px; float:right;">
		<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> 
		<input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " />
	</p>	
	</form>		
	
	<div class="clear"></div>
	
	<fieldset class="field"> <legend> Item Transaction </legend>
	<form name="modul_form" class="myform" id="ajaxform2" method="post" action="<?php echo $form_action_item; ?>">
		<table>
			<tr>
				<td>  
					<label for="titem"> Transaction No : </label> <br />
					<input type="text" class="required" readonly name="titem" id="titem" size="5" title="Transaction Code" />
					<?php echo anchor_popup(site_url("sales/get_list/".$default['currency'].'/'.$venid.'/'), '[ ... ]', $atts1); ?> &nbsp;
				</td>
				
				<td>
					<label for="titem"> Type &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Amount : </label> <br />
					<select id="ctype" name="ctype" onChange="set_amount_text();"> 
					<option selected="selected" value="1"> Cash </option> <option value="0"> Credit </option>
					</select> &nbsp;
					
					<input type="text" disabled="disabled" size="10" name="tamount" id="tamount" onKeyUp="checkdigit(this.value, 'tamount')" /> &nbsp;
				</td>
                
                <td>
					<label for="ttax"> Tax : </label> <br />
					<input type="text" disabled="disabled" size="10" name="ttax" id="ttax" onKeyUp="checkdigit(this.value, 'ttax')" /> &nbsp;
				</td>
                
                <td>
					<label for="ttax"> Cost : </label> <br />
					<input type="text" disabled="disabled" size="10" name="tcost" id="tcost" onKeyUp="checkdigit(this.value, 'tcost')" /> &nbsp;
				</td>
                
                <td>
					<label for="ttax2"> Other Tax : </label> <br />
					<input type="text" disabled="disabled" size="10" name="ttax2" id="ttax2" onKeyUp="checkdigit(this.value, 'ttax2')" /> &nbsp;
				</td>
                
                <td>
					<label for="tnotes"> Notes : </label> <br />
					<textarea name="tnotes" cols="30" rows="1"></textarea>
				</td>
				
				<td> <br />
					<input type="submit" name="submit" class="button" title="POST" value="POST" /> 
				</td>
			</tr>
            
		</table>
		
		<div class="clear"></div>
		<?php echo ! empty($table) ? $table : ''; ?>
		
	</form>
	</fieldset>
	
</div>

</body>