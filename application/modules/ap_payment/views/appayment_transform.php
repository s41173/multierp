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
	
	$('#bget').click(function() {
		
		var pid = $("#titem").val();
		var type = $("#ttype").val();
		var add = "";
		
		if (type == 'PO'){ add = site +"/ap_payment/get_po"; }else { add = site +"/ap_payment/get_printing" }
		
		$.ajax({
		type: 'POST',
		url: add,
		data: "po="+ pid,
		success: function(data)
		{
//		   res = data.split("|");
		   document.getElementById("tnominal").value = data;
		   document.getElementById("tdiscount").value = '0';
		   document.getElementById("tamounts").value = data;
		}
		})
		return false;
		
	});
	
	$('#tdiscount').keyup(function() {
		
		var discount = parseFloat($("#tdiscount").val());
		var nominal = parseFloat($("#tnominal").val());
		var res = nominal-discount;
		
		if (res < 0)
		{
 		  document.getElementById("tdiscount").value = '0';	
		  document.getElementById("tamounts").value = nominal;
		}
		else { document.getElementById("tamounts").value = res; }
			
	});

/* end document */		
});
	
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
	  'scrollbars' => 'no',
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
	
	<fieldset class="field" style="float:left;">  <legend> AP - Payment </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
						<td> <label for="tvendor"> Vendor </label> </td> <td>:</td>
						<td> <input type="text" class="required" readonly name="tvendor" id="tcust" size="25" title="Name"
						value="<?php echo set_value('tvendor', isset($default['vendor']) ? $default['vendor'] : ''); ?>" /> &nbsp; 
						<?php //echo anchor_popup(site_url("vendor/get_list/"), '[ ... ]', $atts1); ?>
					</tr>
					
					<tr>	
						<td> <label for="ctype"> Type </label> </td> <td>:</td>
	                    <td> <select name="ctype" id="ctype">
                        	 <option value=""> -- </option> 
                             <option value="1" <?php echo set_select('ctype', '1', isset($default['type']) && $default['type'] == '1' ? TRUE : FALSE); ?> > Tax </option> 
                             <option value="0" <?php echo set_select('ctype', '0', isset($default['type']) && $default['type'] == '0' ? TRUE : FALSE); ?> > Non </option> 
                             </select> - &nbsp;
                             <input type="text" name="tvoucherno" id="tvoucherno" value="<?php echo set_value('tvoucherno', isset($default['voucher']) ? $default['voucher'] : ''); ?>" size="5" onKeyUp="checkdigit(this.value, 'tvoucherno')" />
                        </td>
					</tr>
                    
            <tr>
			<td> <label for="tno"> Trans Type </label></td> <td>:</td> 
			<td> <input type="text" name="ttype" id="ttype" size="10" title="Transaction Type" readonly
                 value="<?php echo set_value('ttype', isset($default['transtype']) ? $default['transtype'] : ''); ?>" /> <br />  </td> 
			</tr>        
                   
            <tr>
			<td> <label for="tno"> No </label></td> <td>:</td> 
			<td> <input type="text" name="tno" size="5" title="No" readonly
                 value="<?php echo set_value('tno', isset($default['no']) ? $default['no'] : ''); ?>" /> <br />  </td> 
			</tr>
					

		 <tr>	
		 <td> <label for="tdate"> Date </label> </td> <td>:</td>
		 <td>  
	     <input type="Text" name="tdate" id="d1" title="Invoice date" size="10" class="required"
	     value="<?php echo set_value('tdate', isset($default['date']) ? $default['date'] : ''); ?>" /> 
		 <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> &nbsp; 
         <br />
		 </td>
		 </tr>
					

			<tr> <td> <label for="cacc"> Account </label> </td> <td>:</td> <td>  
		  <select name="cacc" class="required">
	      <option value="bank" <?php echo set_select('cacc', 'bank', isset($default['acc']) && $default['acc'] == 'bank' ? TRUE : FALSE); ?> /> Bank </option>
          <option value="cash" <?php echo set_select('cacc', 'cash', isset($default['acc']) && $default['acc'] == 'cash' ? TRUE : FALSE); ?> /> Cash </option>
	      <option value="pettycash" <?php echo set_select('cacc', 'pettycash', isset($default['acc']) && $default['acc'] == 'pettycash' ? TRUE : FALSE); ?> /> Petty Cash </option>
		  </select>
			<br />  </td> </tr>
					
			<tr>	
			
			<td> <label for="tcurrency"> Currency </label> </td> <td>:</td>
			<td> <input type="text" class="required" readonly name="tcurrency" size="10" title="Currency"
  	             value="<?php echo set_value('tcurrency', isset($default['currency']) ? $default['currency'] : ''); ?>" /> &nbsp; <br /> </td>
			</tr>
            
            <tr>
				<td> <label for="trate"> Rate </label></td> <td>:</td> 
				<td><input type="text" name="trate" size="10" title="Rate" readonly
                    value="<?php echo set_value('trate', isset($default['rate']) ? $default['rate'] : ''); ?>" /> <br />  </td> 
			</tr>
					

            <tr>	
                <td> <label for="tuser"> AP - Dept </label> </td> <td>:</td>
                <td> <input type="text" class="required" readonly name="tuser" size="15" title="User"
                value="<?php echo set_value('tuser', isset($default['user']) ? $default['user'] : ''); ?>" /> &nbsp; <br /> </td>
            </tr>
					
					
				</table>  
	</fieldset>   
	
	<fieldset class="field" style="float:left; margin-left:15px;"> <legend> Payment Details </legend>
		
		<table>
			
            <tr>
				<td> <label for="tlate"> Late Charges </label></td> <td>:</td> 
				<td><input type="text" id="tlate" name="tlate" size="10" title="Late Charges" 
			    value="<?php echo set_value('tlate', isset($default['late']) ? $default['late'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tlate')" />  </td> 
			</tr>
            
            <tr>
				<td> <label for="ttdiscount"> Total Discount </label></td> <td>:</td> 
				<td><input type="text" id="ttdiscount" readonly name="ttdiscount" size="10" title="Total Discount" 
			    value="<?php echo set_value('ttdiscount', isset($default['tdiscount']) ? $default['tdiscount'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'ttdiscount')" />  </td> 
			</tr>
            
			<tr>
				<td> <label for="tbalance"> Balance </label></td> <td>:</td> 
				<td><input type="text" id="tbalance" disabled="disabled" name="tbalance" readonly size="10" title="Balance" 
			    value="<?php echo set_value('tbalance', isset($default['balance']) ? $default['balance'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tbalance')" /> </td> 
			</tr>
			
		</table>
		
	</fieldset>
	
	<fieldset class="field" style="float:left; margin-left:15px;"> <legend> Check Details </legend>
		
		<table>
			
            <tr>
				<td> <label for="tcheck"> Post - Dated </label> </td>  <td>:</td>
				<td> <?php echo form_checkbox('cpost', 1, set_value('cpost', isset($default['status']) ? $default['status'] : 'FALSE')); ?> &nbsp; <br /> </td>
			</tr>
            
			<tr>
				<td> <label for="tcheck"> Check - No </label> </td>  <td>:</td>
				<td>
                 <select name="ccheck_type" class="required">
	<option value="check" <?php echo set_select('ccheck_type', 'check', isset($default['check_type']) && $default['check_type'] == 'check' ? TRUE : FALSE); ?> /> Check </option>
	<option value="giro" <?php echo set_select('ccheck_type', 'giro', isset($default['check_type']) && $default['check_type'] == 'giro' ? TRUE : FALSE); ?> /> Giro </option>
			</select> - 
                <input type="text" name="tcheck" size="15" title="Check No"
				value="<?php echo set_value('tcheck', isset($default['check']) ? $default['check'] : ''); ?>" /> &nbsp; <br /> </td>
			</tr>
            
            <tr>
            <td> <label for="tcheck"> Check - Acc </label> </td>  <td>:</td>
            <td>
            <input type="text" size="20" name="tcheckacc" value="<?php echo set_value('tcheckacc', isset($default['checkacc']) ? $default['checkacc'] : ''); ?>"> - 
            <input type="text" size="15" name="tcheckaccno" id="tcheckaccno" onKeyUp="checkdigit(this.value, 'tcheckaccno')" value="<?php echo set_value('tcheckaccno', isset($default['checkaccno']) ? $default['checkaccno'] : ''); ?>">
            </td>
            </tr>
			
			<tr>	
			<td> <label for="cbank"> COA </label> </td> <td>:</td>
			<td> <?php $js = 'class=""'; echo form_dropdown('cbank', $bank, isset($default['bank']) ? $default['bank'] : '', $js); ?> &nbsp; <br /> </td>
			</tr>
			
			<tr>
				<td> <label for="tbalancecek"> Balance </label></td> <td>:</td> 
				<td><input type="text" id="tbalancecek" name="tbalancecek" size="10" title="Balance" 
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
                <?php if ($default['transtype'] == 'PRINTING'){ $type = 'vinyl'; }else { $type = 'purchase'; } ?>
				<label for="titem"> Transaction: </label> <br />
				<input type="text" class="required" readonly name="titem" id="titem" size="5" title="Transaction Code" />
				<?php echo anchor_popup(site_url($type."/get_list_all/".$default['currency'].'/'.$venid.'/'), '[ ... ]', $atts1); ?> &nbsp;
                <input type="button" id="bget" value="GET"> &nbsp; &nbsp;
				</td>
                
                <td> 
                    <label for="tamount"> Balance </label> <br />
				    <input type="text" id="tnominal" readonly name="tnominal" size="10" title="Nominal Balance" onKeyUp="checkdigit(this.value, 'tnominal')" /> <br />  
                </td> 
                
                <td> 
                    <label for="tdiscount"> Discount </label> <br />
				    <input type="text" id="tdiscount" name="tdiscount" size="10" title="Discount" onKeyUp="checkdigit(this.value, 'tdiscount')" /> <br />  
                </td> 
                
				<td> 
                    <label for="tamount"> Amount </label> <br />
				    <input type="text" id="tamounts" name="tamount" size="10" title="Amount" onKeyUp="checkdigit(this.value, 'tamounts');" /> <br />  
                </td> 
				
				<td> <br />
					<input type="submit" name="submit" class="" title="POST" value="POST" />
                    <input type="reset" name="submit" class="" title="Cancel" value="RESET" /> 
				</td>
			</tr>
		</table>
		
		<div class="clear"></div>
		<?php echo ! empty($table) ? $table : ''; ?>
		
	</form>
	</fieldset>
	
	<div class="clear"></div>
	
</div>

</body>