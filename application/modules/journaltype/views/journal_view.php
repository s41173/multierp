<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Journal Type </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>
							
			<tr>
			<td> <label for="tcode"> Code </label></td> <td>:</td> <td><input type="text" class="required" name="tcode" size="5" title="Code" 
			value="<?php echo set_value('tcode', isset($default['code']) ? $default['code'] : ''); ?>" /> &nbsp; <br />  </td>  </tr>
					
			<tr>
			<td> <label for="tname"> Name </label></td> <td>:</td> <td><input type="text" class="required" name="tname" size="30" title="Name" 
			value="<?php echo set_value('tname', isset($default['name']) ? $default['name'] : ''); ?>" /> &nbsp; <br />  </td>  </tr>
					
			<tr>
			<td> <label for="tdesc"> Description </label></td> <td>:</td> <td>
  <textarea name="tdesc" rows="2" cols="35"><?php echo set_value('tdesc', isset($default['desc']) ? $default['desc'] : ''); ?></textarea> &nbsp; <br />            </td> </tr>
					
			<tr>
			<td colspan="3"> <br />
                <input type="submit" name="submit" class="button" value="Save" /> 
                <input type="reset" name="reset" class="button" value="Cancel" /> 
            </td>
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

