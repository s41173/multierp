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

<div id="webadmin">
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Add - District </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>" >
				<table>
                
                	<tr> 
						<td> <label for="tname"> Province </label> </td> <td>:</td>
						<td> <input type="text" class="required" name="tprovince" id="tprovince" size="20" title="Province" 
                             value="<?php echo set_value('tprovince', isset($default['province']) ? $default['province'] : ''); ?>" /> 
                        </td>
					</tr>
                    
                    <tr> 
						<td> <label for="tname"> City </label> </td> <td>:</td>
						<td> <input type="text" class="required" name="tcity" id="tcity" size="20" title="City" 
                             value="<?php echo set_value('tcity', isset($default['city']) ? $default['city'] : ''); ?>" /> 
                        </td>
					</tr>
                    
                    <tr> 
						<td> <label for="tname"> District </label> </td> <td>:</td>
						<td> <input type="text" class="required" name="tdistrict" id="tdistrict" size="20" title="District" 
                             value="<?php echo set_value('tdistrict', isset($default['district']) ? $default['district'] : ''); ?>" /> 
                        </td>
					</tr>
                    
                    <tr> 
						<td> <label for="tvillage"> Village </label> </td> <td>:</td>
						<td> <input type="text" class="required" name="tvillage" id="tvillage" size="20" title="Village" 
                             value="<?php echo set_value('tvillage', isset($default['village']) ? $default['village'] : ''); ?>" /> 
                        </td>
					</tr>
                    
                    <tr> 
						<td> <label for="tzip"> Zip Code </label> </td> <td>:</td>
						<td> <input type="text" class="required" name="tzip" id="tzip" size="5" title="Zip Code" onKeyUp="checkdigit(this.value, 'tzip')" 
                             value="<?php echo set_value('tzip', isset($default['zip']) ? $default['zip'] : ''); ?>" /> 
                        </td>
					</tr>
					
					   
				</table>
				<p style="margin:15px 0 0 0; float:right;">
					<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> 
					<input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " />
				</p>	
			</form>			  
	</fieldset>
</div>

