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
					
					<tr> <td> <label for="tname"> Name</label> </td> <td>:</td> 
						<td> <input type="text" class="required" name="tname" title="Name" size="32" maxlength="50" value="<?php echo set_value('tname', isset($default['name']) ? $default['name'] : ''); ?>" /> <br />  </td></tr>
						
						<tr> <td> <label for="taddress">Address</label> </td> <td>:</td> <td> <textarea name="taddress" class="required" title="Member Address" cols="45" rows="3"><?php echo set_value('taddress', isset($default['address']) ? $default['address'] : ''); ?></textarea> <br /> </td></tr>	
								
						<tr> <td> <label for="tphone">Phone</label> </td> <td>:</td> <td> <input type="text" title="Phone no max 15 character" class="required" name="tphone" id="tphone" size="15" maxlength="15" value="<?php echo set_value('tphone', isset($default['phone']) ? $default['phone'] : ''); ?>" /> <br />  </td> </tr>
						
						<tr> <td> <label for="tcity">City</label> </td> <td>:</td> 
						<td> <?php $js = 'class="required"'; echo form_dropdown('ccity', $city, isset($default['city']) ? $default['city'] : '', $js); ?> <br/> </td> </tr>
						
						<tr> <td> <label for="tmail">Email</label></td> <td>:</td> <td><input type="text" class="required email" name="tmail" size="32" title="Type mail" value="<?php echo set_value('tmail', isset($default['mail']) ? $default['mail'] : ''); ?>" /> <br /> </td> </tr>
						
						<tr> <td> <label for="tid"> Yahoo Id </label></td> <td>:</td> <td><input type="text" class="" name="tid" size="15" title="Type id" value="<?php echo set_value('tid', isset($default['id']) ? $default['id'] : ''); ?>" /> <br /> </td> </tr>
						
						<tr> <td><label for="tusername">Username</label></td> <td>:</td> <td><input type="text" class="required" name="tusername" size="25" title="Type username" value="<?php echo set_value('tusername', isset($default['user_name']) ? $default['user_name'] : ''); ?>" /> <br />  </td>  </tr>
						
					    <tr> <td><label for="tpassword">Password</label></td> <td>:</td> <td><input type="password" class="required" name="tpassword" size="25" title="Type password" value="<?php echo set_value('tpassword', isset($default['password']) ? $default['password'] : ''); ?>" /> <br /> </td> </tr>
						
						<tr> <td><label for="crole">Role</label></td> <td>:</td> 
						<td>  <?php $js = 'class="required"'; echo form_dropdown('crole', $roles, isset($default['role']) ? $default['role'] : '', $js); ?> <br/> </td> </tr>
						
					    <tr> <td> <label for="rstatus"> Status </label> </td> <td>:</td> <td> TRUE <input name="rstatus" class="required" type="radio" value="1" <?php echo set_radio('rstatus', '1', isset($default['status']) && $default['status'] == '1' ? TRUE : FALSE); ?> /> FALSE <input name="rstatus" class="required" type="radio" value="0" <?php echo set_radio('rstatus', '0', isset($default['status']) && $default['status'] == '0' ? TRUE : FALSE); ?> />  
						  <br/> </td> </tr>
						  
						<tr> <td> <input type="submit" name="submit" class="button" value="Save" /> <input type="reset" name="reset" class="button" value=" Cancel " /> </td> </tr>  
						
					</table>
				</fieldset>
			</div>
			

		  </div>	
	</form>
</div>

<div class="buttonplace"> <input type="button" value="Window Close" onclick="window.close();" /> </div>

