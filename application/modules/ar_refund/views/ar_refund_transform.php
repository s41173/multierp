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
		if (res == 1) { document.getElementById("tamount").disabled = true; } else { document.getElementById("tamount").disabled = false; }
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
	
	<fieldset class="field" style="float:left;">  <legend> AR - Credit Refund </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
						<td> <label for="tvendor"> Customer </label> </td> <td>:</td>
						<td> <input type="text" class="required" readonly="readonly" name="tcustomer" id="tcust" size="25" title="Name"
						value="<?php echo set_value('tcustomer', isset($default['customer']) ? $default['customer'] : ''); ?>" /> &nbsp; 
						<?php //echo anchor_popup(site_url("vendor/get_list/"), '[ ... ]', $atts1); ?>
					</tr>
					
					<tr>
			   <td> <label for="tnotes"> Notes </label> </td>  <td>:</td>
			   <td>  <input type="text" class="required" readonly="readonly" name="tnotes" size="45" title="Notes"
			         value="<?php echo set_value('tnotes', isset($default['notes']) ? $default['notes'] : ''); ?>" /> &nbsp; <br /> </td>
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
			   <td>  <input type="text" class="required" readonly="readonly" name="tacc" size="15" title="Acc"
			         value="<?php echo set_value('tacc', isset($default['acc']) ? $default['acc'] : ''); ?>" /> &nbsp; <br /> </td>
			</tr>
					
			<tr>	
			
			<td> <label for="tcurrency"> Currency </label> </td> <td>:</td>
			<td> <input type="text" class="required" readonly="readonly" name="tcurrency" size="10" title="Currency"
  	             value="<?php echo set_value('tcurrency', isset($default['currency']) ? $default['currency'] : ''); ?>" /> &nbsp; <br /> </td>
			</tr>
					

					<tr>	
						<td> <label for="tuser"> AR - Dept </label> </td> <td>:</td>
						<td> <input type="text" class="required" readonly="readonly" name="tuser" size="15" title="User"
						value="<?php echo set_value('tuser', isset($default['user']) ? $default['user'] : ''); ?>" /> &nbsp; <br /> </td>
					</tr>
					
					
				</table>  
	</fieldset>   
	
	<fieldset class="field" style="float:left; margin-left:15px;"> <legend> Payment Details </legend>
		
		<table>
			
			<tr>
				<td> <label for="tbalance"> Balance </label></td> <td>:</td> 
				<td><input type="text" id="tbalance" disabled="disabled" name="tbalance" readonly="readonly" size="10" title="Balance" 
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
				<td><input type="text" id="tbalancecek" readonly="readonly" name="tbalancecek" size="10" title="Balance" 
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
					<?php $js = 'class="required"'; echo form_dropdown('cover', $sover, isset($default['over']) ? $default['over'] : '', $js); ?> &nbsp;
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