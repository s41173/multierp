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

function setbr()
{
	var nilai = document.getElementById("tnote").value;
	document.getElementById("tnote").value = nilai + " <br /> ";
}
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
	
	<fieldset class="field" style="float:left;"> <legend> Sales </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
						<td> <label for="tcustomer"> Customer </label> </td> <td>:</td>
						<td> <input type="text" readonly class="required" name="tcustomer" id="tcust" size="35" title="Name"
						value="<?php echo set_value('tcustomer', isset($default['customer']) ? $default['customer'] : ''); ?>" /> &nbsp; 
					</tr>
                    
                    <tr> 
						<td> <label for="tcontract"> Contract - CO-00 </label> </td> <td>:</td>
						<td> <input type="text" readonly class="required" name="tcontract" id="tcust" size="4" title="Contract"
                        value="<?php echo set_value('tcontract', isset($default['contract']) ? $default['contract'] : ''); ?>" /> &nbsp; 
					</tr>
					
					<tr>	
						<td> <label for="tno"> No - SO-00 </label> </td> <td>:</td>
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
					
					<tr>
						<td> <label for="tnote"> Note </label> </td>  <td>:</td>
						<td>  <input type="text" class="required" name="tnote" id="tnote" size="60" title="Note"
						value="<?php echo set_value('tnote', isset($default['note']) ? $default['note'] : ''); ?>" /> &nbsp;
						<input type="button" value="BR" onClick="setbr()" /> <br /> </td>
					</tr>
					
					<tr>
						<td> <label for="tdisdesc"> Discount Desc </label> </td>  <td>:</td>
						<td>  <input type="text" class="" name="tdisdesc" size="30" title="Discount Desc" 
						value="<?php echo set_value('tdisdesc', isset($default['disdesc']) ? $default['disdesc'] : ''); ?>" /> &nbsp; <br /> </td>
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
						<td> <label for="tuser"> Sales Dept </label> </td> <td>:</td>
						<td> <input type="text" class="required" readonly name="tuser" size="15" title="User"
						value="<?php echo set_value('tuser', isset($default['user']) ? $default['user'] : ''); ?>" /> &nbsp; <br /> </td>
					</tr>
					
					<tr> <td> <label for="tdesc"> Description </label> </td> <td>:</td> 
				    <td> <textarea name="tdesc" class="required" title="Description" cols="45" rows="3"><?php echo set_value('tdesc', isset($default['desc']) ? $default['desc'] : ''); ?></textarea> &nbsp; <br /> </td></tr>
                    
             <tr>
                <td> <label for="ttaxnotes"> Tax Note </label> </td>  <td>:</td>
                <td> <input type="text" name="ttaxnotes" size="50" title="Tax Note"
                value="<?php echo set_value('ttaxnotes', isset($default['taxnotes']) ? $default['taxnotes'] : ''); ?>" /> &nbsp; <br /> </td>
            </tr>       	
                    
             <tr>
				<td> <label for="twcost"> Additional Costs </label></td> <td>:</td> 
				<td><input type="text" id="twcost" name="twcost" size="10" title="Landed Costs" 
					value="<?php echo set_value('twcost', isset($default['w_cost']) ? $default['w_cost'] : '0'); ?>" 
                    onKeyUp="checkdigit(this.value, 'twcost')" /> <br />  
                </td> 
			</tr>
                    
                    <tr> <td> <label for="twdesc"> Additional Cost Desc </label> </td> <td>:</td> 
<td> <textarea name="twdesc" class="required" title="Description" cols="35" rows="2"><?php echo set_value('twdesc', isset($default['w_notes']) ? $default['w_notes'] : ''); ?></textarea> &nbsp; <br /> </td></tr>

                    <tr>
                        <td> <label for="ttaxserial"> Tax Serial </label> </td>  <td>:</td>
                        <td>  <input type="text" class="required" name="ttaxserial" size="35" title="Tax Serial"
                        value="<?php echo set_value('ttaxserial', isset($default['tax_serial']) ? $default['tax_serial'] : ''); ?>" /> &nbsp; <br /> </td>
                    </tr>	
					   
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
				<td> <label for="tdiscount"> Discount </label></td> <td>:</td> 
				<td><input type="text" name="tdiscount" disabled="disabled" size="10" title="Discount" 
			        value="<?php echo set_value('tdiscount', isset($default['discount']) ? $default['discount'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tdiscount')" />
			    </td> 
			</tr>
			
			<tr>
				<td> <label for="ttax"> Tax </label></td> <td>:</td> 
				<td><input type="text" id="ttax" name="ttax" disabled="disabled" readonly size="10" title="Total Tax" 
					value="<?php echo set_value('ttax', isset($default['tax']) ? $default['tax'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'ttax')" /> <br />  </td> 
			</tr>
			
			<tr>
				<td> <label for="ttotaltax"> Total </label></td> <td>:</td> 
				<td><input type="text" id="ttotaltax" disabled="disabled" name="ttotaltax" readonly size="10" title="After Total Tax" 
			value="<?php echo set_value('ttotaltax', isset($default['totaltax']) ? $default['totaltax'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'ttotaltax')" /> <br />  </td> 
			</tr>
			
			<tr>
				<td> <label for="tp1"> Down Payment </label></td> <td>:</td> 
				<td><input type="text" id="tp1" name="tp1" size="10" title="Down Payment" 
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
			
				<td>  
					<label for="ctype"> Type : </label> <br />
					<select name="ctype" title="Product Type">
						<option selected="selected" value="0"> Other </option>
						<option value="1"> Newspaper Ad </option>
					</select>
					&nbsp;
				</td>
				
				<td>  
					<label for="tsize"> Size : </label> <br />
					<input type="text" name="tsize" id="tsize" size="5" title="Size" onKeyUp="checkdigit(this.value, 'tsize')" /> &nbsp;
				</td>
				
				<td>  
					<label for="tsup"> Sup : </label> <br />
					<input type="text" name="tsup" id="tsup" size="2" title="Sup" onKeyUp="checkdigit(this.value, 'tsup')" /> &nbsp;
				</td>
				
				<td>  
					<label for="tcoloumn"> Coloumn : </label> <br />
					<input type="text" name="tcoloumn" id="tcoloumn" size="8" title="Coloumn" onKeyUp="checkdigit(this.value, 'tcoloumn')" /> &nbsp;
				</td>
			
				<td>  
					<label for="tamount"> Unit Price : </label> <br />
					<input type="text" name="tamount" id="tamount" size="15" title="Amount" onKeyUp="checkdigit(this.value, 'tamount')" /> &nbsp;
				</td>
				
				<td>  
					<label for="tcount"> Count </label> <br />
					<input type="text" name="tcount" id="tcount" size="3" value="0" maxlength="2" title="Count" onKeyUp="checkdigit(this.value, 'tcount')" /> &nbsp;
				</td>
				
				<td>  
					<label for="ctax"> Tax : </label> <br />
					<?php $js = 'class="required"'; echo form_dropdown('ctax', $tax, isset($default['ctax']) ? $default['ctax'] : '', $js); ?> &nbsp;
				</td>
				
				<td>  
					<label for="tdiscount"> Disc(%) : </label> <br />
					<input type="text" name="tdiscount" id="tdiscount" size="2" maxlength="5" value="0" title="Discount" onKeyUp="checkdigit(this.value, 'tdiscount')" /> &nbsp;
				</td>
                
                <td>  
					<label for="cround"> Rounding : </label> <br />
					<select name="cround" title="Round">
						<option selected="selected" value="round"> Round (+) </option>
						<option value="floor"> Floor (-) </option>
					</select>
					&nbsp;
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