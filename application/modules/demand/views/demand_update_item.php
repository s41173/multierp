<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'development-bundle/themes/base/ui.all.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/jquery.fancybox-1.3.4.css'; ?>");</style>
<script type="text/javascript" src="<?php echo base_url();?>js/datetimepicker_css.js"></script>



<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";
</script>

<?php 
		
$atts1 = array(
	  'class'      => 'refresh',
	  'title'      => 'add cust',
	  'width'      => '600',
	  'height'     => '500',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 500)/2)+\'',
);

?>

<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Demand Transaction Item </legend>
	<form name="modul_form" class="myform" id="sajaxform" method="post" action="<?php echo $form_action_item; ?>">
				<table>
					
                    <tr> 
					<td> <label for="tname"> Product </label></td> <td>:</td> 
 <td> <input type="text" class="required" readonly="readonly" name="tproduct" id="tproduct" size="30" title="Name" value="<?php echo set_value('tproduct', isset($default['product']) ? $default['product'] : ''); ?>" /> &nbsp;
<?php echo anchor_popup(site_url("product/get_list/"), '[ ... ]', $atts1); ?> &nbsp; &nbsp;  
                    </td> 
                    </tr>
                    
                    <tr> 
					<td> <label for="tname">Qty</label></td> <td>:</td> 
<td> <input type="text" name="tqty" id="tqtys" size="4" title="Qty" onKeyUp="checkdigit(this.value, 'tqtys')" value="<?php echo set_value('tqty', isset($default['qty']) ? $default['qty'] : ''); ?>" /> &nbsp; </td> 
                    </tr>
                    
                    <tr> 
					<td> <label for="tname"> Description </label></td> <td>:</td> <td>
<textarea name="tdesc" cols="55"><?php echo set_value('tdesc', isset($default['desc']) ? $default['desc'] : ''); ?></textarea>
                    </td>
                    </tr>
                    
                    <tr> 
					<td> <label for="tname"> Demand Date </label></td> <td>:</td> 
                    <td> <input type="Text" name="tdemanddate" id="d2" title="Demand date" size="10" class="required"
				   value="<?php echo set_value('tdemanddate', isset($default['demanddate']) ? $default['demanddate'] : ''); ?>" /> 
				   <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d2','yyyymmdd')" style="cursor:pointer"/> &nbsp; </td> 
                    </tr>
                    
                    <tr> 
					<td> <label for="tname"> Vendor </label></td> <td>:</td> 
<td> <input type="text" class="required" readonly="readonly" name="tvendor" id="tcust" size="25" title="Name" value="<?php echo set_value('tvendor', isset($default['vendor']) ? $default['vendor'] : ''); ?>" /> &nbsp; 
					<?php echo anchor_popup(site_url("vendor/get_list/"), '[ ... ]', $atts1); ?> </td> 
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

