
<div id="webadmin">

	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend>Add New Admin Menu</legend>
			<form name="admin_form" id="form" class="myform" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data">
				<table>
					<tr> <td><label for="tname">Name</label></td> <td>:</td> <td><input type="text" class="required" name="tname" size="35" title="Menu name" value="<?php echo set_value('tname', isset($default['name']) ? $default['name'] : ''); ?>" /> <br /> </td> </tr>
					
					<tr> <td><label for="cparent">Parent Item</label></td> <td>:</td> 
					  <td> <?php $js = 'class="", size="10" '; echo form_dropdown('cparent', $query_menu, isset($default['menu']) ? $default['menu'] : '', $js); ?> </td> 
 			        </tr>
					
				   <tr> <td><label for="cmodul">Modul</label></td> <td>:</td> 
					  <td>  
                     <?php  $js = "class=\"required\", onChange=\"geturl(this.value, 'turl')\" "; 
					        echo form_dropdown('cmodul', $query_modul, isset($default['modul']) ? $default['modul'] : '', $js);
					 ?>
					  </td> 
 			       </tr>
				   
				   <tr> <td><label for="turl">Url</label></td> <td>:</td> <td> <input type="text" class="required" name="turl" id="turl" size="30" title="URL" value="<?php echo set_value('turl', isset($default['url']) ? $default['url'] : ''); ?>" /> <br /> </td> </tr>
				   
				   <tr> <td> <label for="tmenuorder">Order</label> </td> <td>:</td> <td> <input type="text" name="tmenuorder" id="tmenuorder" title="Menu Order" onkeyup="checkdigit(this.value, 'tmenuorder')" size="1" class="required" value="<?php echo set_value('tmenuorder', isset($default['menuorder']) ? $default['menuorder'] : ''); ?>" />
				    </td> </tr>
				   
				   <tr> <td><label for="tclass">Class</label></td> <td>:</td> <td> <input type="text" class="" name="tclass" size="10" title="Class" value="<?php echo set_value('tclass', isset($default['class']) ? $default['class'] : ''); ?>" /> <br /> </td> </tr>
				   
				   <tr> <td><label for="tid">ID</label></td> <td>:</td> <td> <input type="text" class="" name="tid" size="10" title="ID" value="<?php echo set_value('tid', isset($default['id']) ? $default['id'] : ''); ?>" /> <br /> </td> </tr>
				   
<tr> <td><label for="ctarget"> Target </label></td> <td>:</td> 
					 <td>
<select name="ctarget" class="required" title="Position">
<option selected="selected" value="_parent" <?php echo set_select('ctarget', '_parent', isset($default['target']) && $default['target'] == '_parent' ? TRUE : FALSE); ?> /> Parent </option>
<option value="_blank" <?php echo set_select('ctarget', '_blank', isset($default['target']) && $default['target'] == '_blank' ? TRUE : FALSE); ?> /> Blank </option>
<option value="_self" <?php echo set_select('ctarget', '_self', isset($default['target']) && $default['target'] == '_self' ? TRUE : FALSE); ?> /> Self </option>
<option value="_top" <?php echo set_select('ctarget', '_top', isset($default['target']) && $default['target'] == '_top' ? TRUE : FALSE); ?> /> Top </option>
</select> <br />   </td>  </tr>
				
				<tr> <td> <label for="userfile">Change image</label> </td> <td>:</td> <td> 
				<input type="file" title="Upload image" name="userfile" size="35" /> <br /> 
				<?php echo isset($error) ? $error : ''; ?> <small>*) Leave it blank if not upload images.</small> </td> </tr>
				   
				</table>				  
			<p>
				<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " />
				<input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " />
			</p>
		  </form>
	</fieldset>
</div>

<div id="webadmin2">
	
    <form name="search_form" class="myform" method="post" action="<?php echo $form_action_del; ?>">
     <?php echo ! empty($table) ? $table : ''; ?>
	 <div class="paging"> <?php echo ! empty($pagination) ? $pagination : ''; ?> </div>
	 <p class="cek"> <?php echo ! empty($radio1) ? $radio1 : ''; echo ! empty($radio2) ? $radio2 : ''; ?> <input type="submit" name="button" class="button_delete" title="Process Button" value="Delete All" />  </p> 
	</form>	
	
	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>
</div>
