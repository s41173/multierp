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
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend>Add New Modul</legend>
			<form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> <td><label for="tname">Name</label></td> <td>:</td> <td><input type="text" class="required" name="tname" size="40" title="Type Role Name Without Space" value="<?php echo set_value('tname', isset($default['name']) ? $default['name'] : ''); ?>" /> <br /> </td>  </tr>
					
					<tr> <td><label for="crules">Rules</label></td> <td>:</td> 
					 <td>
						  <select name="crules" class="required" title="Rules">
			<option value="1" <?php echo set_select('crules', '1', isset($default['rules']) && $default['rules'] == '1' ? TRUE : FALSE); ?> /> Read </option>
			<option value="2" <?php echo set_select('crules', '2', isset($default['rules']) && $default['rules'] == '2' ? TRUE : FALSE); ?> /> Read / Write </option>
			<option value="4" <?php echo set_select('crules', '4', isset($default['rules']) && $default['rules'] == '4' ? TRUE : FALSE); ?> /> Approval </option>
			<option value="3" <?php echo set_select('crules', '3', isset($default['rules']) && $default['rules'] == '3' ? TRUE : FALSE); ?> /> Full Control </option>
						  </select>
						  <br /> 
					 </td>  </tr>
					
				    <tr> <td><label for="tdesc">Description</label></td> <td>:</td> <td> <textarea name="tdesc" title="Role Description" cols="25" rows="2"><?php echo set_value('tdesc', isset($default['desc']) ? $default['desc'] : ''); ?></textarea> <br/> </td> </tr>					 
				</table>	
							  
			<p>
				<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " />
				<input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " />
			</p>
		  </form>
	</fieldset>
</div>
