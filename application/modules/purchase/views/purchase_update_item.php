<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'development-bundle/themes/base/ui.all.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/jquery.fancybox-1.3.4.css'; ?>");</style>



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
	
	<fieldset class="field"> <legend> Purchase - Product Item </legend>
	<form name="modul_form" class="myform" id="sajaxform" method="post" action="<?php echo $form_action_item; ?>">
				<table>
					
                    <tr> 
					<td> <label for="tproduct"> Product </label> </td> <td>:</td> 
                    <td> <input type="text" class="required" readonly name="titem" id="tproduct" size="35" title="Name" value="<?php echo set_value('titem', isset($default['item']) ? $default['item'] : ''); ?>" />
				         <?php echo anchor_popup(site_url("product/get_list/"), '[ ... ]', $atts1); ?> </td> 
                    </tr>
                    
                    <tr> 
					<td> <label for="tname"> Qty </label></td> <td>:</td> 
                    <td> <input type="text" class="required" name="tqty" id="tsqty" size="3" title="Qty" onKeyUp="checkdigit(this.value, 'stqty')" value="<?php echo set_value('tqty', isset($default['qty']) ? $default['qty'] : ''); ?>" /> </td> 
                    </tr>
                    
                    <tr> 
					<td> <label for="tname"> Unit Price </label></td> <td>:</td> 
                    <td> <input type="text" class="required" name="tamount" id="tamount" size="10" title="Amount" onKeyUp="checkdigit(this.value, 'tamount')" value="<?php echo set_value('tamount', isset($default['amount']) ? $default['amount'] : ''); ?>" />  </td> 
                    </tr>
                    
                    <tr> 
					<td> <label for="tname"> Tax </label></td> <td>:</td> 
                    <td> <?php $js = 'class="required"'; echo form_dropdown('ctax', $tax, isset($default['tax']) ? $default['tax'] : '', $js); ?> &nbsp;  </td> 
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

