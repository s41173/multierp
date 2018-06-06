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

function setbr()
{
	var nilai = document.getElementById("tnote").value;
	document.getElementById("tnote").value = nilai + " <br /> ";
}
</script>

<script type="text/javascript">	
	function refreshparent() { opener.location.reload(true); }
    
     $(document).ready(function () {
         
         $('#ajaxform,#ajaxform2,#ajaxform3,#ajaxform4').submit(function() {
				$.ajax({
					type: 'POST',
					url: $(this).attr('action'),
					data: $(this).serialize(),
					success: function(data) {
						// $('#result').html(data);
						if (data == "true")
						{
							location.reload(true);
						}
						else
						{
							// alert(data);
							document.getElementById("errorbox").innerHTML = data;
						}
						
					}
				})
				return false;
        });
         
         $('#cdisctype').change(function() {		
             var type = $(this).val();
             if (type == '0'){
                $("#tdiscount").attr("readonly", false);
                $("#tdiscountnominal").attr("readonly", true);
                $("#tdiscountnominal").val("0");
             }else{
                $("#tdiscount").attr("readonly", true);
                $("#tdiscount").val("0");
                $("#tdiscountnominal").attr("readonly", false); 
             }
         });
         
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
	
	<fieldset class="field" style="float:left;"> <legend> Non Tax Sales Order </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
						<td> <label for="tcustomer"> Customer </label> </td> <td>:</td>
						<td> <input type="text" class="required" readonly name="tcustomer" id="tcust" size="25" title="Name"
						value="<?php echo set_value('tcustomer', isset($default['customer']) ? $default['customer'] : ''); ?>" /> &nbsp; 

					</tr>
                    
                     <tr> 
						<td> <label for="tcontract"> Contract - CO-00 </label> </td> <td>:</td>
						<td> <input type="text" readonly class="required" name="tcontract" id="tcust" size="4" title="Contract"
                        value="<?php echo set_value('tcontract', isset($default['contract']) ? $default['contract'] : ''); ?>" /> &nbsp; 
					</tr>
					
					<tr>	
						<td> <label for="tno"> No - NSO-00 </label> </td> <td>:</td>
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
					<label for="ctax"> Tax : </label> <br />
					<?php $js = 'class="required"'; echo form_dropdown('ctax', $tax, isset($default['ctax']) ? $default['ctax'] : '', $js); ?> &nbsp;
				</td>
				
				<td>  
					<label for="tdiscount"> Disc Type : </label> <br />
					<select name="cdisctype" id="cdisctype">
					    <option value="0"> (%) </option>
					    <option value="1"> (0) </option>
					</select>
				</td>
				
				<td>  
					<label for="tdiscount"> Disc (%) : </label> <br />
					<input type="text" name="tdiscount" id="tdiscount" size="2" maxlength="3" value="0" title="Discount" onKeyUp="checkdigit(this.value, 'tdiscount')" /> &nbsp;
				</td>
				
				<td>  
					<label for="tdiscount"> Disc-Nominal : </label> <br />
					<input type="text" name="tdiscountnominal" id="tdiscountnominal" readonly size="7" maxlength="9" value="0" title="Discount" onKeyUp="checkdigit(this.value, 'tdiscountnominal')" /> &nbsp;
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