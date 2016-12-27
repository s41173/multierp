<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Account Classification Details </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>
										
			<tr>
            
            <td> <label for="tno"> No : </label> <br /> 
            <input type="text" class="required" name="tno" id="tno" size="5" title="No" onkeyup="checkdigit(this.value, 'tno')"
			value="<?php echo set_value('tno', isset($default['no']) ? $default['no'] : ''); ?>" /> &nbsp;  </td>  
			
            <td> <label for="tname"> Name : </label> <br /> <input type="text" class="required" name="tname" size="35" title="Name" 
			value="<?php echo set_value('tname', isset($default['name']) ? $default['name'] : ''); ?>" /> &nbsp;  </td>  
            
            <td> <label for="ctype"> Acc Type : </label> <br /> 
           		 <select name="ctype">
                 	<option value="harta"> Harta </option>
                    <option value="modal"> Modal </option>
                    <option value="kewajiban"> Kewajiban </option>
                    <option value="pendapatan"> Pendapatan </option>
                    <option value="biaya"> Biaya </option>
                 </select>
           
            &nbsp;  </td>
            
            <td colspan="3"> <br />
            	<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> 
            	<input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " /> 
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

