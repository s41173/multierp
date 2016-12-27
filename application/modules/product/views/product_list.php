<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'development-bundle/themes/base/ui.all.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/jquery.fancybox-1.3.4.css'; ?>");</style>

<script type="text/javascript" src="<?php echo base_url();?>js/register.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/datetimepicker_css.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/development-bundle/ui/ui.core.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/hoverIntent.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/complete.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/sortir.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/validate.js"></script> 
<script type='text/javascript' src='<?php echo base_url();?>js/jquery.validate.js'></script>  
<script type='text/javascript' src='<?php echo base_url();?>js/jquery.autocomplete.js'></script>

<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";
var site = "<?php echo site_url();?>";
</script>


<div id="webadmin">
<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>

<fieldset class="field"> <legend> Inventory </legend>
	<form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
					
					<td> <label for="cbrand"> Brand </label> </td> <td>:</td>
			        <td> <?php $js = 'class=""'; echo form_dropdown('cbrand', $brand, isset($default['brand']) ? $default['brand'] : '', $js); ?> &nbsp; <br /> </td>
					
					<td> <label for="ccategory"> Category </label> </td> <td>:</td>
			<td> <?php $js = 'class=""'; echo form_dropdown('ccategory', $category, isset($default['category']) ? $default['category'] : '', $js); ?> &nbsp; <br /> </td>
										
					</tr> 
					
					<tr>
						<td colspan="6"> Name : <input type="text" name="tsearch" size="40" id="tproductsearch" />   </td>
						<td colspan="3" align="right"> 
						<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value="Search" /> 
						<input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " /> 
						</td>
					</tr>
					
				</table>	
			</form>			  
	</fieldset> <div class="clear"></div>
<?php echo ! empty($table) ? $table : ''; ?>
</div>

<div class="buttonplace">  </div>

