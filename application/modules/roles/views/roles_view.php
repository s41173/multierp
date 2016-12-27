
<div id="webadmin">

	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend>Add New Modul</legend>
			<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> <td><label for="tname">Name</label></td> <td>:</td> <td><input type="text" class="required" name="tname" size="40" title="Type Role Name Without Space" value="<?php echo set_value('tname', isset($default['name']) ? $default['name'] : ''); ?>" /> <br /> </td>  </tr>
					
					<tr> <td><label for="crules">Rules</label></td> <td>:</td> 
					 <td>
						  <select name="crules" class="required" title="Rules">
			<option value="1" <?php echo set_select('crules', '1', isset($default['rules']) && $default['rules'] == '1' ? TRUE : FALSE); ?> /> Read </option>
			<option value="2" <?php echo set_select('crules', '2', isset($default['rules']) && $default['rules'] == '2' ? TRUE : FALSE); ?> /> Read / Write </option>
			<option value="4" <?php echo set_select('crules', '4', isset($default['rules']) && $default['rules'] == '4' ? TRUE : FALSE); ?> /> Approval </option>
			<option value="3" <?php echo set_select('crules', '3', isset($default['rules']) && $default['rules'] == '3' ? TRUE : FALSE); ?> /> Full Control </option>
						  </select>
						  <br /> 
					 </td>  </tr>
					
				    <tr> <td><label for="tdesc">Description</label></td> <td>:</td> <td> <textarea name="tdesc" title="Role Description" cols="25" rows="2"><?php echo set_value('tdesc', isset($default['desc']) ? $default['desc'] : ''); ?></textarea> <br/> </td> </tr>					 
				</table>	
							  
			<p>
				<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " />
				<input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " />
			</p>
		  </form>
	</fieldset>
</div>

<div id="webadmin2">
	
    <?php echo ! empty($table) ? $table : ''; ?>
	<div class="paging"> <?php echo ! empty($pagination) ? $pagination : ''; ?> </div>
	
	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>
</div>
