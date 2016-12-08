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
            
            <td> <label for="tno"> No : </label> <br /> 
            <input type="text" class="required" name="tno" id="tno" size="5" title="No" onkeyup="checkdigit(this.value, 'tno')"
			value="<?php echo set_value('tno', isset($default['no']) ? $default['no'] : ''); ?>" /> &nbsp;  </td>  
			
            <td> <label for="tname"> Name : </label> <br /> <input type="text" class="required" name="tname" size="35" title="Name" 
			value="<?php echo set_value('tname', isset($default['name']) ? $default['name'] : ''); ?>" /> &nbsp;  </td>  
            
            <td> <label for="ctype"> Acc Type : </label> <br /> 
           		 <select name="ctype">
                 
<option value="harta" <?php echo set_select('ctype', 'harta', isset($default['type']) && $default['type'] == 'harta' ? TRUE : FALSE); ?> > Harta </option>
<option value="modal" <?php echo set_select('ctype', 'modal', isset($default['type']) && $default['type'] == 'modal' ? TRUE : FALSE); ?> > Modal </option>
<option value="kewajiban" <?php echo set_select('ctype', 'kewajiban', isset($default['type']) && $default['type'] == 'kewajiban' ? TRUE : FALSE); ?> > Kewajiban </option> 
<option value="pendapatan" <?php echo set_select('ctype', 'pendapatan', isset($default['type']) && $default['type'] == 'pendapatan' ? TRUE : FALSE); ?> > Pendapatan </option>
<option value="biaya" <?php echo set_select('ctype', 'biaya', isset($default['type']) && $default['type'] == 'biaya' ? TRUE : FALSE); ?> > Biaya </option>
         
                 </select>
           
            &nbsp;  </td>
            
            <td colspan="3"> <br />
            	<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> 
            	<input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " /> 
            </td>
            
            </tr>
			
		  </table>		
					
	    </form>			  
	</fieldset>
</div>

