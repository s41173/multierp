<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'development-bundle/themes/base/ui.all.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/jquery.fancybox-1.3.4.css'; ?>");</style>

<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.autocomplete.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/register.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/datetimepicker_css.js"></script>

<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";
var site = "<?php echo site_url();?>";
</script>

<script type="text/javascript">	
	function refreshparent() { opener.location.reload(true); }
	function set_item(val){ if (val == 1){ document.getElementById("tproduct").readOnly = true; }else { document.getElementById("tproduct").readOnly = false; } }
	
	$(document).ready(function(){
	
	$('#ajaxform,#ajaxform2').submit(function() {
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function(data) {
			// $('#result').html(data);
			if (data == "true"){ location.reload(true);}
			else{ document.getElementById("errorbox").innerHTML = data; }
			}
		})
		return false;
	});	
	
	$('#cover').change(function() {
		
		var pid = $("#cover").val();
		
		$.ajax({
		type: 'POST',
		url: site +"/purchase/get_over",
		data: "tno="+ pid,
		success: function(data)
		{
//		   res = data.split("|");
		   document.getElementById("toveramount").value = data;
		}
		})
		return false;
		
	});
	
	$('#tp1').keyup(function() {
				
		var cost = parseFloat($("#tcosts").val());
		var totaltax = parseFloat($('#ttotaltax').val());
		var total = parseFloat(cost + totaltax);
		var p1 = parseFloat($(this).val());
		var over = parseFloat($('#toveramount').val());
		var res = parseFloat(total - p1 - over);
		$('#tbalance').val(res);
	});
	
	$('#tcosts').keyup(function() {
	
		var cost = parseFloat($(this).val());
		var totaltax = parseFloat($('#ttotaltax').val());
		var total = parseFloat(cost + totaltax);
		var p1 = parseFloat($('#tp1').val());
		var over = parseFloat($('#toveramount').val());
		var res = parseFloat(total - p1 - over);
		$('#tbalance').val(res);
	});

/* end document */		
});
	
</script>
	
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
	
	<fieldset class="field" style="float:left;"> <legend> Purchasing </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
						<td> <label for="tvendor"> Vendor </label> </td> <td>:</td>
						<td> <input type="text" class="required" name="tvendor" id="tcust" size="25" title="Name"
						value="<?php echo set_value('tvendor', isset($default['vendor']) ? $default['vendor'] : ''); ?>" /> &nbsp; 
						<?php echo anchor_popup(site_url("vendor/get_list/"), '[ ... ]', $atts1); ?>
					</tr>
					
					<tr>	
						<td> <label for="tno"> No - PO-00 </label> </td> <td>:</td>
	     <td> <input type="text" class="required" readonly name="tno" size="4" title="Name" value="<?php echo isset($code) ? $code : ''; ?>" /> &nbsp; <br /> </td>
					</tr>
					
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
			<td> <label for="tname"> Currency </label> </td> <td>:</td>
			<td> <?php $js = 'class="required"'; echo form_dropdown('ccurrency', $currency, isset($default['currency']) ? $default['currency'] : '', $js); ?> &nbsp; <br /> </td>
			</tr>
            
            <tr> <td> <label for="cacc"> Account </label> </td> <td>:</td> <td>  
			<select name="cacc" class="required">
	<option value="bank" <?php echo set_select('cacc', 'bank', isset($default['acc']) && $default['acc'] == 'bank' ? TRUE : FALSE); ?> /> Bank </option>
	<option value="cash" <?php echo set_select('cacc', 'cash', isset($default['acc']) && $default['acc'] == 'cash' ? TRUE : FALSE); ?> /> Cash </option>
	<option value="pettycash" <?php echo set_select('cacc', 'pettycash', isset($default['acc']) && $default['acc'] == 'pettycash' ? TRUE : FALSE); ?> /> Petty Cash </option>
			</select>
			<br />  </td> </tr>	
					
        <tr>
            <td> <label for="tnote"> Note </label> </td>  <td>:</td>
            <td>  <input type="text" class="required" name="tnote" size="56" title="Note"
            value="<?php echo set_value('tnote', isset($default['note']) ? $default['note'] : ''); ?>" /> &nbsp; <br /> </td>
        </tr>
					
		 <tr>	
         <td> <label for="tshipping"> Shipping Date </label> </td> <td>:</td>
         <td>  
           <input type="Text" name="tshipping" id="d2" title="shipping date" size="10" class="required"
           value="<?php echo set_value('tshipping', isset($default['shipping']) ? $default['shipping'] : ''); ?>" /> 
           <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d2','yyyymmdd')" style="cursor:pointer"/> &nbsp; <br />
        </td>
		</tr>
					
        <tr>	
            <td> <label for="tuser"> Purchasing Dept </label> </td> <td>:</td>
            <td> <input type="text" class="required" readonly name="tuser" size="15" title="User"
            value="<?php echo set_value('tuser', isset($default['user']) ? $default['user'] : ''); ?>" /> &nbsp; <br /> </td>
        </tr>
					
					<tr> <td> <label for="tdesc"> Description </label> </td> <td>:</td> 
<td> <textarea name="tdesc" class="required" title="Description" cols="45" rows="3"><?php echo set_value('tdesc', isset($default['desc']) ? $default['desc'] : ''); ?></textarea> &nbsp; <br /> </td></tr>	
					   
				</table>  
	</fieldset>
	
	<fieldset class="field" style="float:left; margin-left:15px;"> <legend> Payment Details </legend>
		
		<table>
			
			<tr>
				<td> <label for="tcosts"> Landed Costs </label></td> <td>:</td> 
				<td><input type="text" id="tcosts" name="tcosts" size="10" title="Landed Costs" 
					value="<?php echo set_value('tcosts', isset($default['costs']) ? $default['costs'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tcosts')" /> <br />  </td> 
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
				<td> <label for="tover"> Credit / Debit </label></td> <td>:</td> 
				<td> <?php $js = 'id="cover"'; echo form_dropdown('cover', $over, isset($default['over']) ? $default['over'] : '', $js); ?> 
                </td> 
			</tr>
            
            <tr>
				<td> </td> <td></td> 
				<td> <input type="text" readonly id="toveramount" name="toveramount" size="10" title="Credit / Debit" 
			         value="<?php echo set_value('toveramount', isset($default['overamount']) ? $default['overamount'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'toveramount')" /> 
                </td> 
			</tr>
			
			<tr>
				<td> <label for="tp1"> Down Payment </label></td> <td>:</td> 
				<td> <input type="text" id="tp1" name="tp1" size="10" title="Down Payment" 
			        value="<?php echo set_value('tp1', isset($default['p1']) ? $default['p1'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tp1')" /> <br />  </td> 
			</tr>
			
			<tr>
				<td> <label for="tbalance"> Balance </label></td> <td>:</td> 
				<td><input type="text" id="tbalance" disabled="disabled" name="tbalance" readonly size="10" title="Balance" 
			    value="<?php echo set_value('tbalance', isset($default['balance']) ? $default['balance'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tbalance')" /> <br />  </td> 
			</tr>
			
		</table>
		
	</fieldset>
	
	
	
	<p style="margin:10px 0 0 10px; float:left;">
		<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> 
		<input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " />
	</p>	
	</form>		
	
	<div class="clear"></div>
	
	<fieldset class="field"> <legend> Item Transaction </legend>
	<form name="modul_form" class="myform" id="ajaxform2" method="post" action="<?php echo $form_action_item; ?>">
		<table>
			<tr>
				
				<td> <label for="tproduct"> Product </label>  <br />
				     <input type="text" class="required" readonly name="titem" id="tproduct" size="35" title="Name" /> &nbsp;
				     <?php echo anchor_popup(site_url("product/get_list/"), '[ ... ]', $atts1); ?> &nbsp; &nbsp; </td>
				
				<td>  
					<label for="tqty"> Qty : </label> <br />
					<input type="text" name="tqty" id="stqty" size="3" title="Qty" onKeyUp="checkdigit(this.value, 'stqty')" /> &nbsp;
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