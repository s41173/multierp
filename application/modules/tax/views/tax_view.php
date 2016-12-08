<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Tax </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
					<td> <label for="tname">Name</label></td> <td>:</td> <td><input type="text" class="required" name="tname" size="25" title="Name" 
					value="<?php echo set_value('tname', isset($default['name']) ? $default['name'] : ''); ?>" /> <br />  </td> 
					
					<td> <label for="tcode">Code</label></td> <td>:</td> <td><input type="text" class="required" name="tcode" size="5" title="Code" 
					value="<?php echo set_value('tcode', isset($default['code']) ? $default['code'] : ''); ?>" /> <br />  </td> 
					
					<td> <label for="tcode">Value (%)</label></td> <td>:</td> <td><input type="text" class="required" id="tvalue" name="tvalue" size="3" title="Value" 
					value="<?php echo set_value('tvalue', isset($default['value']) ? $default['value'] : ''); ?>" onkeyup="checkdigit(this.value, 'tvalue')" /> <br />  </td> 
					
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

