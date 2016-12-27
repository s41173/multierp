<div id="webadmin">
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<fieldset class="field"> <legend>Search Log</legend>
			<form name="log_form" class="myform" method="post" action="<?php echo $form_action; ?>">
		          <label class="label" for="ttgl"> Period Date :</label>
				  
    		       <input type="Text" name="tstart" id="d1" title="Start date" size="15" class="form_field" /> 
				   <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/>
				   
				   <input type="submit" name="submit" class="button" value="Search" />
				   <input type="submit" name="submit" class="button" value="Delete" />
				   <input type="reset" name="submit" class="button" value="Clear" />
				  <?php echo form_error('ttgl', '<p class="field_error">', '</p>');?>
		  </form>
	</fieldset>
</div>

<div id="webadmin2">
	
    <?php echo ! empty($table) ? $table : ''; ?>
	<div class="paging"> <?php echo ! empty($pagination) ? $pagination : ''; ?> </div>
	
	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>
</div>