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
	
	<fieldset class="field"> <legend> Currency </legend>
	    <form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>">
			
			<table>
			
			<tr>
			<td> <label for="tcode"> Code </label></td> <td>:</td> <td><input type="text" class="required" name="tcode" size="5" title="Code" 
			value="<?php echo set_value('tcode', isset($default['code']) ? $default['code'] : ''); ?>" /> &nbsp; <br />  </td>  </tr>
					
			<tr>
			<td> <label for="tname"> Name </label></td> <td>:</td> <td><input type="text" class="required" name="tname" size="30" title="Name" 
			value="<?php echo set_value('tname', isset($default['name']) ? $default['name'] : ''); ?>" /> &nbsp; <br />  </td>  </tr>
					
			<tr>
			<td> <label for="tdesc"> Description </label></td> <td>:</td> <td>
  <textarea name="tdesc" rows="2" cols="35"><?php echo set_value('tdesc', isset($default['desc']) ? $default['desc'] : ''); ?></textarea> &nbsp; <br />            </td> </tr>
  
  			<tr>
			<td colspan="3"> <br />
                <input type="submit" name="submit" class="button" value="Save" /> 
                <input type="reset" name="reset" class="button" value="Cancel" /> 
            </td>
			</tr> 
					
			</table>	
					
	    </form>			  
	</fieldset>
</div>

