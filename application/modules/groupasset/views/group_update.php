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
	
	<fieldset class="field"> <legend> Control </legend>
	    <form name="modul_form" class="myform" id="ajaxform3" method="post" action="<?php echo $form_action; ?>">
			
			<table>
			                
                 <tr>
                
                    <td> <label for="tname"> Code </label> </td>  <td>:</td>
            <td> <input type="text" class="required" name="tcode" size="10" title="Code"
                 value="<?php echo set_value('tcode', isset($default['code']) ? $default['code'] : ''); ?>" /> &nbsp; <br />
            </td>
                </tr>
                
                <tr>
                
                    <td> <label for="tname"> Name </label> </td>  <td>:</td>
                    <td> <input type="text" class="required" name="tname" size="35" title="Desc"
                         value="<?php echo set_value('tname', isset($default['name']) ? $default['name'] : ''); ?>" /> &nbsp; <br /> </td>
                </tr>
                
                <tr>
                
                    <td> <label for="tperiod"> Period </label> </td>  <td>:</td>
                    <td> <input type="text" class="required" name="tperiod" size="2" title="Period"
                         value="<?php echo set_value('tperiod', isset($default['period']) ? $default['period'] : ''); ?>" /> &nbsp; <br /> </td>
                </tr>
                
                <tr>
                	<td> <label for="titem"> Accumulation </label> </td> <td>:</td>
                    <td>
<input type="text" class="required" readonly name="titem" id="titem" size="5" title="Accumulation" value="<?php echo set_value('titem', isset($default['accumulation']) ? $default['accumulation'] : ''); ?>" /> &nbsp;
				     <?php echo anchor_popup(site_url("accountc/get_list/"), '[ ... ]', $atts1); ?> &nbsp; </td>  
                </tr>
                <tr>
                
                <tr>
                	<td> <label for="titem"> Depreciation </label> </td> <td>:</td>
                    <td>
<input type="text" class="required" readonly name="titem2" id="titem2" size="5" title="Depreciation" value="<?php echo set_value('titem2', isset($default['depreciation']) ? $default['depreciation'] : ''); ?>" /> &nbsp;
				     <?php echo anchor_popup(site_url("accountc/get_list/null/IDR/titem2"), '[ ... ]', $atts1); ?> &nbsp; </td>  
                </tr>
                <tr>

			<td colspan="3"> <br /> <input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> <input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " /> </td>
			</tr> 
					
			</table>	
					
	    </form>			  
	</fieldset>
</div>

