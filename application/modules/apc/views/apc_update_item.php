<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'development-bundle/themes/base/ui.all.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/jquery.fancybox-1.3.4.css'; ?>");</style>



<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";
</script>

<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> AP - Cash Transaction Item </legend>
	<form name="modul_form" class="myform" id="sajaxform" method="post" action="<?php echo $form_action_item; ?>">
				<table>
					
                    <tr> 
					<td> <label for="tname"> Cost Type </label></td> <td>:</td> 
                    <td> <?php $js = 'class="required"'; echo form_dropdown('ccost', $cost, isset($default['cost']) ? $default['cost'] : '', $js); ?>  </td> 
                    </tr>
                    
                    <tr> 
					<td> <label for="tname">Notes</label></td> <td>:</td> 
                    <td> <input type="text" class="required" name="tnotes" size="50" title="Notes" value="<?php echo set_value('tnotes', isset($default['notes']) ? $default['notes'] : ''); ?>" />  </td> 
                    </tr>
                    
                    <tr> 
					<td> <label for="tname"> Staff </label></td> <td>:</td> 
                    <td> <input type="text" class="required" name="tstaff" size="15" title="Staff" value="<?php echo set_value('tstaff', isset($default['staff']) ? $default['staff'] : ''); ?>" />  </td> 
                    </tr>
                    
                    <tr> 
					<td> <label for="tname"> Amount </label></td> <td>:</td> 
                    <td> <input type="text" class="required" name="tamount" size="10" title="Amount" value="<?php echo set_value('tamount', isset($default['amount']) ? $default['amount'] : ''); ?>" />  </td> 
                    </tr>
                    
                    <tr>
                    <td colspan="3"> 
                    <input type="submit" name="submit" class="button" title="" value=" Save " /> 
                    <input type="reset" name="reset" class="button" title="" value=" Cancel " /> 
                    </td>
                    </tr>   
				</table>	
			</form>			  
	</fieldset>
</div>

