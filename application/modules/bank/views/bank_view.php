<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Bank Details </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>
					
			<tr> 
         	<td> <label for="ccurrency"> Currency </label></td> <td>:</td> 
			<td> <?php $js = 'class="required"'; echo form_dropdown('ccurrency', $currency, isset($default['currency']) ? $default['currency'] : '', $js); ?> &nbsp; <br />  </td>
			</tr> 
					
			<tr>
			<td> <label for="tname"> Acc Name </label></td> <td>:</td> <td><input type="text" class="required" name="tname" size="22" title="Acc Name" 
			value="<?php echo set_value('tname', isset($default['name']) ? $default['name'] : ''); ?>" /> &nbsp; <br />  </td>  </tr>
					
			<tr>
			<td> <label for="tno"> Acc No </label></td> <td>:</td> <td><input type="text" class="required" name="tno" size="15" title="Acc No" 
			value="<?php echo set_value('tno', isset($default['no']) ? $default['no'] : ''); ?>" /> &nbsp; <br />  </td>  </tr>
					
			<tr>
			<td> <label for="tbank"> Acc Bank </label></td> <td>:</td> <td><input type="text" class="required" name="tbank" size="26" title="Acc Bank" 
			value="<?php echo set_value('tbank', isset($default['bank']) ? $default['bank'] : ''); ?>" /> &nbsp; <br />  </td> </tr>
					
			<tr>
			<td colspan="3"> <input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> <input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " /> </td>
			</tr> 
			
				</table>	
			</form>			  
	</fieldset>
</div>


<div id="webadmin2">
	
	<form name="search_form" class="myform" method="post" action="<?php echo ! empty($form_action_del) ? $form_action_del : ''; ?>">
     <?php echo ! empty($table) ? $table : ''; ?>
	 <div class="paging"> <?php echo ! empty($pagination) ? $pagination : ''; ?> </div>
	</form>	
		
	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>
</div>

