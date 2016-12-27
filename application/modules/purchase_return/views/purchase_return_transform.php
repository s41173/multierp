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
	
	<fieldset class="field" style="float:left;"> <legend> Purchasing Return </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
						<td> <label for="tvendor"> Vendor </label> </td> <td>:</td>
						<td> <input type="text" readonly class="required" name="tvendor" id="tcust" size="25" title="Name"
						value="<?php echo set_value('tvendor', isset($default['vendor']) ? $default['vendor'] : ''); ?>" /> &nbsp; 
					</tr>
					
					<tr>	
						<td> <label for="tno"> No - PR-00 </label> </td> <td>:</td>
	     <td> <input type="text" class="required" readonly name="tno" size="4" title="PR - No" value="<?php echo isset($code) ? $code : ''; ?>" /> &nbsp; <br /> </td>
					</tr>
					
					<tr>	
						<td> <label for="tno"> PO-00 </label> </td> <td>:</td>
	     <td> <input type="text" class="required" readonly name="tpo" size="4" title="PO - No" value="<?php echo isset($po) ? $po : ''; ?>" /> &nbsp; <br /> </td>
					</tr>
					
					<tr>
						<td> <label for="tcur"> Currency </label> </td>  <td>:</td>
						<td> <input type="text" readonly name="tcur" size="15" title="Currency"
						     value="<?php echo set_value('tcur', isset($default['currency']) ? $default['currency'] : ''); ?>" /> &nbsp; <br /> </td>
					</tr>
                    
                     <tr> <td> <label for="cacc"> Account </label> </td> <td>:</td> <td>  
			<select name="cacc" class="required">
	<option value="bank" <?php echo set_select('cacc', 'bank', isset($default['acc']) && $default['acc'] == 'bank' ? TRUE : FALSE); ?> /> Bank </option>
	<option value="cash" <?php echo set_select('cacc', 'cash', isset($default['acc']) && $default['acc'] == 'cash' ? TRUE : FALSE); ?> /> Cash </option>
	<option value="pettycash" <?php echo set_select('cacc', 'pettycash', isset($default['acc']) && $default['acc'] == 'pettycash' ? TRUE : FALSE); ?> /> Petty Cash </option>
			</select>
			<br />  </td> </tr>	
					
					<tr>	
						 <td> <label for="tdate"> Invoice Date </label> </td> <td>:</td>
						 <td>  
						   <input type="Text" name="tdate" id="d1" title="Invoice date" size="10" class="required"
						   value="<?php echo set_value('tdate', isset($default['date']) ? $default['date'] : ''); ?>" /> 
				           <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> &nbsp; <br />
						</td>
					</tr>
					
					<tr>
						<td> <label for="tdocno"> Document No </label> </td>  <td>:</td>
						<td>  <input type="text" class="" name="tdocno" size="15" title="Document No"
						      value="<?php echo set_value('tdocno', isset($default['docno']) ? $default['docno'] : ''); ?>" /> &nbsp; <br /> </td>
					</tr>
					
					<tr>
						<td> <label for="tnote"> Note </label> </td>  <td>:</td>
						<td>  <input type="text" class="required" name="tnote" size="56" title="Note"
						value="<?php echo set_value('tnote', isset($default['note']) ? $default['note'] : ''); ?>" /> &nbsp; <br /> </td>
					</tr>
					
					<tr>	
						<td> <label for="tuser"> Purchasing Dept </label> </td> <td>:</td>
						<td> <input type="text" class="required" readonly name="tuser" size="15" title="User"
						value="<?php echo set_value('tuser', isset($default['user']) ? $default['user'] : ''); ?>" /> &nbsp; <br /> </td>
					</tr>
					   
				</table>  
	</fieldset> 
	
	<fieldset class="field" style="float:left; margin-left:15px;"> <legend> Payment Details </legend>
		
		<table>
			
			<tr>
				<td> <label for="tcosts"> Landed Costs </label></td> <td>:</td> 
				<td><input type="text" id="tcostss" name="tcosts" size="10" title="Landed Costs" 
					value="<?php echo set_value('tcosts', isset($default['costs']) ? $default['costs'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tcostss')" /> <br />  </td> 
			</tr>
			
			<tr>
				<td> <label for="ttax"> Total Tax </label></td> <td>:</td> 
				<td><input type="text" id="ttax" name="ttax" disabled="disabled" readonly size="10" title="Total Tax" 
					value="<?php echo set_value('ttax', isset($default['tax']) ? $default['tax'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'ttax')" /> <br />  </td> 
			</tr>
			
			<tr>
				<td> <label for="ttotaltax"> After Total Tax </label></td> <td>:</td> 
				<td><input type="text" id="ttotaltax" disabled="disabled" name="ttotaltax" readonly size="10" title="After Total Tax" 
			value="<?php echo set_value('ttotaltax', isset($default['totaltax']) ? $default['totaltax'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'ttotaltax')" /> <br />  </td> 
			</tr>
			
			<tr>
				<td> <label for="tbalance"> Balance </label></td> <td>:</td> 
				<td><input type="text" id="tbalance" disabled="disabled" name="tbalance" readonly size="10" title="Balance" 
			    value="<?php echo set_value('tbalance', isset($default['balance']) ? $default['balance'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tbalance')" /> <br />  </td> 
			</tr>
            
            <tr>
				<td> <label for="ccash"> Cash </label></td> <td>:</td> 
				<td> <?php echo form_checkbox('ccash', '1', $cstatus); ?> <br />  </td> 
			</tr>
			
		</table>
		
	</fieldset>
	
	
	
	<p style="margin:10px 0 0 10px; float:left;">
		<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> 
		<input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " />
	</p>	
	</form>		
	
	<div class="clear"></div>
	
	<fieldset class="field"> <legend> Purchasing Item </legend>
	<?php echo ! empty($table2) ? $table2 : ''; ?>
	</fieldset>
	
	<div class="clear"></div>
	
	<fieldset class="field"> <legend> Item Transaction </legend>
	<form name="modul_form" class="myform" id="ajaxform2" method="post" action="<?php echo $form_action_item; ?>">
		<table>
			<tr>
				
				<td> <label for="tproduct"> Product </label>  <br />
				     <?php $js = 'id="cpitem"'; echo form_dropdown('cproduct', $product, isset($default['product']) ? $default['product'] : '', $js); ?> &nbsp; </td>
				
				<td>  
					<label for="tqty"> Qty : </label> <br />
					<input type="text" readonly name="tqty" id="stqty" size="5" title="Qty" onKeyUp="checkdigit(this.value, 'stqty')" /> &nbsp;
				</td>
				
				<td>  
					<label for="treturn"> Return : </label> <br />
					<input type="text" name="treturn" id="treturn" size="4" title="Return" onKeyUp="checkdigit(this.value, 'treturn')" /> &nbsp;
				</td>
				
				<td>  
					<label for="tamount"> Unit Price : </label> <br />
					<input type="text" name="tamount" id="tamount" size="10" title="Amount" onKeyUp="checkdigit(this.value, 'tamount')" /> &nbsp;
				</td>
				
				<td>  
					<label for="ctax"> Tax : </label> <br />
					<?php $js = 'class="required"'; echo form_dropdown('ctax', $tax, isset($default['ctax']) ? $default['ctax'] : '', $js); ?> &nbsp;
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