<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'development-bundle/themes/base/ui.all.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/jquery.fancybox-1.3.4.css'; ?>");</style>

<script type="text/javascript" src="<?php echo base_url();?>js/register.js"></script>
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
	  'height'     => '400',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 400)/2)+\'',
);

?>

<body onUnload="window.opener.location.reload(true);">

<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Contract - Void Transaction </legend>
	<form name="modul_form" class="myform" id="sajaxform" method="post" action="<?php echo $form_action_item; ?>">
				<table>
					
                    <tr>
						<td> <label for="tdocno"> Doc - No </label> </td>  <td>:</td>
						<td> <input type="text" class="required" name="tdocno" size="20" title="DocNo" value="<?php echo set_value('tdocno', isset($default['docno']) ? $default['docno'] : ''); ?>" /> &nbsp; <br /> </td>
					</tr>
                    
                    <tr> 
					<td> <label for="tno"> Contract - No </label> </td> <td>:</td> 
                    <td> CO-00 <input type="text" readonly name="tno" size="1" title="Contract No" value="<?php echo set_value('tno', isset($default['no']) ? $default['no'] : ''); ?>" /> </td> 
                    </tr>
                    
                    <tr> 
					<td> <label for="tno"> Deal Dates </label> </td> <td>:</td> 
                    <td> <input type="text" readonly name="tdates" size="9" title="Contract-Deal Dates" value="<?php echo set_value('tdates', isset($default['dates']) ? $default['dates'] : ''); ?>" /> </td> 
                    </tr>
                    
                    <tr> 
					<td> <label for="tno"> Type </label> </td> <td>:</td> 
                    <td> <input type="text" readonly name="ttype" size="3" title="Contract-Type" value="<?php echo set_value('ttype', isset($default['type']) ? $default['type'] : ''); ?>" /> </td> 
                    </tr>
                    
                    <tr> 
					<td> <label for="tamount"> Amount </label></td> <td>:</td> 
                    <td> <input type="text" class="required" name="tamount" id="tamount" size="10" title="Amount" onKeyUp="checkdigit(this.value, 'tamount')" value="<?php echo set_value('tamount', isset($default['amount']) ? $default['amount'] : ''); ?>" /> </td> 
                    </tr>
                    
                    <tr> 
					<td> <label for="ttax"> Tax </label></td> <td>:</td> 
                    <td> <input type="text" class="required" name="ttax" id="ttax" size="10" title="Tax" onKeyUp="checkdigit(this.value, 'ttax')" value="<?php echo set_value('ttax', isset($default['tax']) ? $default['tax'] : ''); ?>" /> </td> 
                    </tr>
                    
                    <tr> 
					<td> <label for="tbalance"> Balance </label></td> <td>:</td> 
                    <td> <input type="text" readonly="readonly" class="required" name="tbalance" id="tbalance" size="10" title="Balance" value="<?php echo set_value('tbalance', isset($default['balance']) ? $default['balance'] : ''); ?>" />  </td> 
                    </tr>
                    
                    <tr>	
						 <td> <label for="tvoiddate"> Void Date </label> </td> <td>:</td>
						 <td>  
						   <input type="Text" name="tvoiddate" id="d3" title="Void date" size="10" class="required"
						   value="<?php echo set_value('tvoiddate', isset($default['voiddate']) ? $default['voiddate'] : ''); ?>" /> 
				           <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d3','yyyymmdd')" style="cursor:pointer"/> &nbsp; <br />
						</td>
					</tr>
                    
                    <tr> 
					<td> <label for="tvoid"> Void-Desc </label></td> <td>:</td> 
                    <td> <textarea name="tdesc" cols="30" rows="3"><?php echo set_value('tdesc', isset($default['desc']) ? $default['desc'] : ''); ?></textarea> </td> 
                    </tr>
                    
                    <tr> <td colspan="2"></td>
                    <td> 
                    <input type="submit" name="submit" class="" title="" value=" Save " /> 
                    <input type="reset" name="reset" class="" title="" value=" Cancel " /> 
                    </td>
                    </tr>   
				</table>	
			</form>			  
	</fieldset>
</div>
</body>