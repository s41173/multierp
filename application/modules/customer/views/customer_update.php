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
<script type="text/javascript" src="<?php echo base_url();?> js/complete.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/sortir.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/validate.js"></script> 
<script type='text/javascript' src='<?php echo base_url();?>js/jquery.validate.js'></script>  

<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";
</script>


<div id="webadmin">

<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>

<div class="errorbox"> <?php echo validation_errors(); ?> </div>
	
<form name="admin_form" id="form" class="myform" method="post" action="<?php echo $form_action; ?>">

		
		  <div class="tab_container">
			<div id="tab1" class="tab_content">
				<fieldset class="field">
					<table border="0">
					
						<tr> <td> <label for="tname"> Company (*) </label> </td> <td>:</td> 
<td> <input type="text" class="" name="tpre" title="Prefix" size="3" maxlength="5" value="<?php echo set_value('tpre', isset($default['prefix']) ? $default['prefix'] : ''); ?>" />     <input type="text" class="required" name="tname" title="Name" size="35" maxlength="100" 
     value="<?php echo set_value('tname', isset($default['name']) ? $default['name'] : ''); ?>" /> 
						<br />  </td></tr>
						
						<tr> <td> <label for="ctype">Type</label> </td> <td>:</td> <td>  
			<select name="ctype" class="required">
			<option value="company" <?php echo set_select('ctype', 'company', isset($default['type']) && $default['type'] == 'company' ? TRUE : FALSE); ?> /> Company </option>
			<option value="personal" <?php echo set_select('ctype', 'personal', isset($default['type']) && $default['type'] == 'personal' ? TRUE : FALSE); ?> /> Personal </option>
			</select>
			<br />  </td> </tr>
						
						<tr> <td> <label for="tcontact">Contact (*)</label></td> <td>:</td> 
						<td> <input type="text" class="" name="tcontact" size="25" title="Contact Person" 
						value="<?php echo set_value('tcontact', isset($default['contact']) ? $default['contact'] : ''); ?>" /> <br /> </td> </tr>
						
						<tr> <td> <label for="tnpwp">NPWP</label></td> <td>:</td> 
						<td> <input type="text" class="" name="tnpwp" size="25" title="NPWP" 
						value="<?php echo set_value('tnpwp', isset($default['npwp']) ? $default['npwp'] : ''); ?>" /> <br /> </td> </tr>
					
								
						<tr> <td> <label for="tphone">Phone (*) </label> </td> <td>:</td> 
						<td> <input type="text" title="Phone no" class="required" name="tphone" id="tphone" size="15" maxlength="15"
						value="<?php echo set_value('tphone', isset($default['phone']) ? $default['phone'] : ''); ?>"  /> / 
						<input type="text" title="Phone no" class="" name="tphone2" id="tphone2" size="15" maxlength="15" 
						value="<?php echo set_value('tphone2', isset($default['phone2']) ? $default['phone2'] : ''); ?>"  />
					    <br />  </td> </tr>
						
						<tr> <td> <label for="tmobile">Mobile</label> </td> <td>:</td> 
			            <td> <input type="text" title="Mobile" name="tmobile" id="tmobile" size="15" maxlength="15" onkeyup="checkdigit(this.value, 'tmobile')"
						value="<?php echo set_value('tmobile', isset($default['mobile']) ? $default['mobile'] : ''); ?>" /> <br />
			            </td> </tr>
						
						<tr> <td> <label for="tfax">Fax</label> </td> <td>:</td> 
						<td> <input type="text" title="Fax" class="" name="tfax" id="tphone3" size="15" maxlength="15"
						value="<?php echo set_value('tfax', isset($default['fax']) ? $default['fax'] : ''); ?>" /> <br />  </td> </tr>
						
						<tr> <td> <label for="tmail">Email</label></td> <td>:</td> 
						<td><input type="text" class="email" name="tmail" size="32" title="Type mail"
						value="<?php echo set_value('tmail', isset($default['mail']) ? $default['mail'] : ''); ?>" /> <br /> </td> </tr>
						
						
						<tr> <td> <label for="taddress">Address (*) </label> </td> <td>:</td> 
						<td> <textarea name="taddress" class="required" title="Address" cols="45" rows="3"><?php echo set_value('taddress', isset($default['address']) ? $default['address'] : ''); ?></textarea> <br /> </td></tr>	
						
						<tr> <td> <label for="tshipaddress">Shipping Address (*) </label> </td> <td>:</td> 
						<td> <textarea name="tshipaddress" class="required" title="Shipping Address" cols="45" rows="3"><?php echo set_value('tshipaddress', isset($default['shipaddress']) ? $default['shipaddress'] : ''); ?></textarea> <br /> </td></tr>	
						
						<tr> <td> <label for="tcity">City</label> </td> <td>:</td> 
						<td> <?php $js = 'class="required"'; echo form_dropdown('ccity', $city, isset($default['city']) ? $default['city'] : '', $js); ?> <br/> </td> </tr>
					
						<tr> <td> <label for="turl"> Website (Url) </label></td> <td>:</td> 
						<td> <input type="text" name="turl" size="30" title="Url" 
						value="<?php echo set_value('turl', isset($default['url']) ? $default['url'] : ''); ?>" /> <br /> </td> </tr>
						
						<tr> <td> <label for="tzip">Zip Code (*)</label> </td> <td>:</td> 
			            <td> <input type="text" title="Zip Code" name="tzip" id="tzip" size="5" maxlength="10" onkeyup="checkdigit(this.value, 'tzip')"
						value="<?php echo set_value('tzip', isset($default['zip']) ? $default['zip'] : ''); ?>" /> <br />
			            </td> </tr>
						
						<tr> <td> <label for="tnotes"> Notes </label> </td> <td>:</td> 
						<td> <textarea name="tnotes" title="Notes" cols="45" rows="3"><?php echo set_value('tnotes', isset($default['notes']) ? $default['notes'] : ''); ?> </textarea> <br /> </td></tr>	
						
						<tr> <td> <label for="taccname"> Acc Name </label> </td> <td>:</td> 
						<td> <input type="text" name="taccname" size="35" title="Acc Name"
						value="<?php echo set_value('taccname', isset($default['accname']) ? $default['accname'] : ''); ?>" /> <br /> </td></tr>
						
						<tr> <td> <label for="taccno"> Acc No </label> </td> <td>:</td> 
						<td> <input type="text" name="taccno" size="20" title="Acc No"
						value="<?php echo set_value('taccno', isset($default['accno']) ? $default['accno'] : ''); ?>" /> <br /> </td></tr>
					
						<tr> <td> <label for="tbank"> Bank </label> </td> <td>:</td> 
   <td> <textarea name="tbank" class="" title="Bank" cols="45" rows="3"><?php echo set_value('tbank', isset($default['bank']) ? $default['bank'] : ''); ?></textarea> <br /> </td>   </tr>	
						
						<tr> <td> <input type="submit" name="submit" class="button" value="Save" /> <input type="reset" name="reset" class="button" value=" Cancel " /> </td> </tr>  
						
					</table>
				</fieldset>
			</div>
			

		  </div>	
	</form>
</div>

<div class="buttonplace"> <input type="button" value="Window Close" onclick="window.close()" /> </div>

