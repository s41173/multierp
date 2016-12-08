<style type="text/css">
	.descp{ padding:10px 0 5px 10px; margin:0; font-size:24px; font-family: Verdana, Arial, Helvetica, sans-serif; color:#025A9F; font-weight:bold;}
</style>

<div id="webadmin">
	
	<p class="descp"> Property / Configuration </p>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	

<form name="config_form" class="" id="form" method="post" action="<?php echo site_url('settings/process'); ?>" enctype="multipart/form-data">
	
		<ul class="tabs">
			<li><a href="#tab1">Primary Details </a></li>
			<li><a href="#tab4">Administrator - User </a></li>
		</ul>
		
		 <div class="tab_container">
			<div id="tab1" class="tab_content">
				<fieldset class="field"> <legend>Primary Details</legend>
					<table border="0">
						<tr> <td> <label for="tname">Name</label> </td> <td>:</td> <td> <input type="text" class="required" name="tname" title="Name" size="32" maxlength="50" value="<?php echo set_value('tname', isset($default['name']) ? $default['name'] : ''); ?>" /> <br />  <?php echo form_error('tname', '<p class="field_error">', '</p>');?> </td></tr>
						<tr> <td> <label for="taddress">Address</label> </td> <td>:</td> <td> 
<textarea class="required" name="taddress" title="Address" cols="25" rows="3"><?php echo set_value('taddress', isset($default['address']) ? $default['address'] : ''); ?>
</textarea> <br /> <?php echo form_error('taddress', '<p class="field_error">', '</p>');?> </td></tr>		

 						<tr> <td> <label for="tnpwp">NPWP</label> </td> <td>:</td> <td> <input type="text" class="" name="tnpwp" title="Npwp" size="30" maxlength="50" value="<?php echo set_value('tnpwp', isset($default['npwp']) ? $default['npwp'] : ''); ?>" /> <br />  <?php echo form_error('tnpwp', '<p class="field_error">', '</p>');?> </td></tr>
						
						<tr> <td> <label for="tcp">Contact Person</label> </td> <td>:</td> <td> <input type="text" class="" name="tcp" title="CP" size="30" maxlength="50" 
	value="<?php echo set_value('tcp', isset($default['cp']) ? $default['cp'] : ''); ?>" /> <br />  <?php echo form_error('tcp', '<p class="field_error">', '</p>');?> </td></tr>	

						<tr> <td> <label for="tcity">City</label> </td> <td>:</td> <td><input type="text" title="City" maxlength="25" class="required" name="tcity" size="18" value="<?php echo set_value('tcity', isset($default['city']) ? $default['city'] : ''); ?>" /> <br />  <?php echo form_error('tcity', '<p class="field_error">', '</p>');?> </td> </tr>
						
						<tr> <td> <label for="tzip">Zip Code</label> </td> <td>:</td> <td><input type="text" title="Zip Code" maxlength="25" class="required" name="tzip" size="18" value="<?php echo set_value('tzip', isset($default['zip']) ? $default['zip'] : ''); ?>" /> <br />  <?php echo form_error('tzip', '<p class="field_error">', '</p>');?> </td> </tr>
						
						<tr> <td> <label for="tphone1">Phone1</label> </td> <td>:</td> <td> <input type="text" name="tarea1" id="tarea1" title="Area Code" onkeyup="checkdigit(this.value, 'tarea1')" size="3" class="required" value="<?php echo set_value('tarea1', isset($default['area1']) ? $default['area1'] : ''); ?>" />-<input type="text" title="Phone no max 15 character" class="required" name="tphone1" id="tphone1" size="10" maxlength="15" onkeyup="checkdigit(this.value, 'tphone1')" value="<?php echo set_value('tphone1', isset($default['phone1']) ? $default['phone1'] : ''); ?>" /> <br />  <?php echo form_error('tphone1', '<p class="field_error">', '</p>');?> <?php echo form_error('tarea1', '<p class="field_error">', '</p>');?> </td> </tr>
						
						<tr> <td> <label for="tphone2">Phone2</label> </td> <td>:</td> <td> <input type="text" name="tarea2" id="tarea2" title="Area Code" onkeyup="checkdigit(this.value, 'tarea2')" size="3" class="required" value="<?php echo set_value('tarea2', isset($default['area2']) ? $default['area2'] : ''); ?>" />-<input type="text" title="Phone no max 15 character" class="required" name="tphone2" id="tmobile" size="10" maxlength="15" onkeyup="checkdigit(this.value, 'tmobile')" value="<?php echo set_value('tphone2', isset($default['phone2']) ? $default['phone2'] : ''); ?>" /> <br />  <?php echo form_error('tphone2', '<p class="field_error">', '</p>');?> <?php echo form_error('tarea2', '<p class="field_error">', '</p>');?> </td> </tr>
						
						<tr> <td> <label for="tmail">Email</label></td> <td>:</td> <td><input type="text" class="required email" name="tmail" size="32" title="Type mail" value="<?php echo set_value('tmail', isset($default['mail']) ? $default['mail'] : ''); ?>" /> <br /> <?php echo form_error('tmail', '<p class="field_error">', '</p>');?></td> </tr>	
											
						<tr> <td> <label for="tbillmail">Billing Email</label></td> <td>:</td> <td><input type="text" class="required email" name="tbillmail" size="32" title="Type mail" value="<?php echo set_value('tbillmail', isset($default['billingmail']) ? $default['billingmail'] : ''); ?>" /> <br /> <?php echo form_error('tbillmail', '<p class="field_error">', '</p>');?></td> </tr>	
											
						<tr> <td> <label for="ttechmail">Technical Email</label></td> <td>:</td> <td><input type="text" class="required email" name="ttechmail" size="32" title="Type mail" value="<?php echo set_value('ttechmail', isset($default['techmail']) ? $default['techmail'] : ''); ?>" /> <br /> <?php echo form_error('ttechmail', '<p class="field_error">', '</p>');?></td> </tr>		
						<tr> <td> <label for="tccmail">CC Email</label></td> <td>:</td> <td><input type="text" class="required email" name="tccmail" size="32" title="Type mail" value="<?php echo set_value('tccmail', isset($default['ccmail']) ? $default['ccmail'] : ''); ?>" /> <br /> <?php echo form_error('tccmail', '<p class="field_error">', '</p>');?></td> </tr>							
					</table>
				</fieldset>
			</div>
			
			 <div id="tab4" class="tab_content"> 
				<fieldset class="field"> <legend> User </legend>
					<table border="0">
					 <tr> <td> <label for="tsitename"> Username </label> </td> <td>:</td> 
				<td> <input type="text" class="form_field" readonly="readonly" name="tuser" title="User Name" size="15" maxlength="100" value="admin" /> <br /> </td></tr>
				
					 <tr> <td> <label for="tpass"> Password </label> </td> <td>:</td> 
				<td> <input type="password" class="required" name="tpass" title="Password" size="20" maxlength="100" value="admin" /> <br /> </td></tr>
						
					</table>
				</fieldset>
			</div>
			
		  </div>


	<table align="right" style="margin:10px 0px 0 0; padding:3px; " width="100%" bgcolor="#D9EBF5">
	<tbody>
		<tr> 
		   <td align="right"> 
			  <input type="submit" value="POST DATA" />
		   </td> 
		</tr>
	</tbody>
	</table>
</div>

</form>
