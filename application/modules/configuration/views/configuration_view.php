
<div id="webadmin"> 

	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>

<form name="config_form" class="" id="form" method="post" action="<?php echo $form_action_add; ?>" enctype="multipart/form-data">
	
		<ul class="tabs">
			<li><a href="#tab1">Primary Details </a></li>
			<li><a href="#tab2">Bank Details</a></li>
			<li><a href="#tab3">Site Configuration</a></li>
            <li><a href="#tab4">Management</a></li>
		</ul>
		
		 <div class="tab_container">
			<div id="tab1" class="tab_content">
				<fieldset class="field"> <legend>Primary Details</legend>
					<table border="0">
						<tr> <td> <label for="tname">Name</label> </td> <td>:</td> <td> <input type="text" class="required" name="tname" title="Name" size="32" maxlength="50" value="<?php echo set_value('tname', isset($default['name']) ? $default['name'] : ''); ?>" /> <br />  <?php echo form_error('tname', '<p class="field_error">', '</p>');?> </td></tr>
						<tr> <td> <label for="taddress">Address</label> </td> <td>:</td> <td> 
<textarea class="required" name="taddress" title="Address" cols="25" rows="3"><?php echo set_value('taddress', isset($default['address']) ? $default['address'] : ''); ?>
</textarea> <br /> <?php echo form_error('taddress', '<p class="field_error">', '</p>');?> </td></tr>		

 						<tr> <td> <label for="tnpwp">NPWP</label> </td> <td>:</td> <td> <input type="text" class="" name="tnpwp" title="Npwp" size="30" maxlength="50" value="<?php echo set_value('tnpwp', isset($default['npwp']) ? $default['npwp'] : ''); ?>" /> <br />  <?php echo form_error('tnpwp', '<p class="field_error">', '</p>');?> </td></tr>
						
						<tr> <td> <label for="tcp">Contact Person</label> </td> <td>:</td> <td> <input type="text" class="" name="tcp" title="CP" size="30" maxlength="50" 
	value="<?php echo set_value('tcp', isset($default['cp']) ? $default['cp'] : ''); ?>" /> <br />  <?php echo form_error('tcp', '<p class="field_error">', '</p>');?> </td></tr>	

						<tr> <td> <label for="tcity">City</label> </td> <td>:</td> <td><input type="text" title="City" maxlength="25" class="required" name="tcity" size="18" value="<?php echo set_value('tcity', isset($default['city']) ? $default['city'] : ''); ?>" /> <br />  <?php echo form_error('tcity', '<p class="field_error">', '</p>');?> </td> </tr>
						
						<tr> <td> <label for="tzip">Zip Code</label> </td> <td>:</td> <td><input type="text" title="Zip Code" maxlength="25" class="required" name="tzip" size="18" value="<?php echo set_value('tzip', isset($default['zip']) ? $default['zip'] : ''); ?>" /> <br />  <?php echo form_error('tzip', '<p class="field_error">', '</p>');?> </td> </tr>
						
						<tr> <td> <label for="tphone1">Phone1</label> </td> <td>:</td> <td> <input type="text" name="tarea1" id="tarea1" title="Area Code" onkeyup="checkdigit(this.value, 'tarea1')" size="3" class="required" value="<?php echo set_value('tarea1', isset($default['area1']) ? $default['area1'] : ''); ?>" />-<input type="text" title="Phone no max 15 character" class="required" name="tphone1" id="tphone1" size="10" maxlength="15" onkeyup="" value="<?php echo set_value('tphone1', isset($default['phone1']) ? $default['phone1'] : ''); ?>" /> <br />  <?php echo form_error('tphone1', '<p class="field_error">', '</p>');?> <?php echo form_error('tarea1', '<p class="field_error">', '</p>');?> </td> </tr>
						
						<tr> <td> <label for="tphone2">Phone2</label> </td> <td>:</td> <td> <input type="text" name="tarea2" id="tarea2" title="Area Code" onkeyup="checkdigit(this.value, 'tarea2')" size="3" class="required" value="<?php echo set_value('tarea2', isset($default['area2']) ? $default['area2'] : ''); ?>" />-<input type="text" title="Phone no max 15 character" class="required" name="tphone2" id="" size="10" maxlength="15" onkeyup="" value="<?php echo set_value('tphone2', isset($default['phone2']) ? $default['phone2'] : ''); ?>" /> <br />  <?php echo form_error('tphone2', '<p class="field_error">', '</p>');?> <?php echo form_error('tarea2', '<p class="field_error">', '</p>');?> </td> </tr>
						
						<tr> <td> <label for="tmail">Email</label></td> <td>:</td> <td><input type="text" class="required email" name="tmail" size="32" title="Type mail" value="<?php echo set_value('tmail', isset($default['mail']) ? $default['mail'] : ''); ?>" /> <br /> <?php echo form_error('tmail', '<p class="field_error">', '</p>');?></td> </tr>	
											
						<tr> <td> <label for="tbillmail">Billing Email</label></td> <td>:</td> <td><input type="text" class="required email" name="tbillmail" size="32" title="Type mail" value="<?php echo set_value('tbillmail', isset($default['billingmail']) ? $default['billingmail'] : ''); ?>" /> <br /> <?php echo form_error('tbillmail', '<p class="field_error">', '</p>');?></td> </tr>	
											
						<tr> <td> <label for="ttechmail">Technical Email</label></td> <td>:</td> <td><input type="text" class="required email" name="ttechmail" size="32" title="Type mail" value="<?php echo set_value('ttechmail', isset($default['techmail']) ? $default['techmail'] : ''); ?>" /> <br /> <?php echo form_error('ttechmail', '<p class="field_error">', '</p>');?></td> </tr>		
						<tr> <td> <label for="tccmail">CC Email</label></td> <td>:</td> <td><input type="text" class="required email" name="tccmail" size="32" title="Type mail" value="<?php echo set_value('tccmail', isset($default['ccmail']) ? $default['ccmail'] : ''); ?>" /> <br /> <?php echo form_error('tccmail', '<p class="field_error">', '</p>');?></td> </tr>							
					</table>
				</fieldset>
			</div>
			
			<div id="tab2" class="tab_content"> 
				<fieldset class="field"> <legend>Bank Details</legend>
					<table border="0">
						<tr> <td> <label for="taccount_name">Account Name</label> </td> <td>:</td> <td> <input type="text" class="form_field" name="taccount_name" title="Account Name" size="32" maxlength="50" value="<?php echo set_value('taccount_name', isset($default['account_name']) ? $default['account_name'] : ''); ?>" /> <br />  <?php echo form_error('taccount_name', '<p class="field_error">', '</p>');?> </td></tr>
						<tr> <td> <label for="taccount_no">Account No</label> </td> <td>:</td> <td> <input type="text" class="form_field" name="taccount_no" title="Account No" size="25" maxlength="50" value="<?php echo set_value('taccount_no', isset($default['account_no']) ? $default['account_no'] : ''); ?>" /> <br />  <?php echo form_error('taccount_no', '<p class="field_error">', '</p>');?> </td></tr>
						<tr> <td> <label for="tbank">Bank Name</label> </td> <td>:</td> <td> <textarea name="tbank" title="Bank Details" cols="25" rows="3"><?php echo set_value('tbank', isset($default['bank']) ? $default['bank'] : ''); ?></textarea> <br /> <?php echo form_error('tbank', '<p class="field_error">', '</p>');?> </td></tr>			
					</table>
				</fieldset>
			</div>
		  </div>	
		  
		  <div id="tab3" class="tab_content"> 
				<fieldset class="field"> <legend>Site Configuration</legend>
					<table border="0">
						<tr> <td> <label for="tsitename"> Site Name </label> </td> <td>:</td> <td> <input type="text" class="form_field" name="tsitename" title="Site Name" size="74" maxlength="100" value="<?php echo set_value('tsitename', isset($default['sitename']) ? $default['sitename'] : ''); ?>" /> <br />  <?php echo form_error('tsitename', '<p class="field_error">', '</p>');?> </td></tr>
						
						<tr> <td> <label for="tmetadesc"> Site Meta Description </label> </td> <td>:</td> <td> 
						<textarea name="tmetadesc" title="Meta Description" cols="55" rows="5"><?php echo set_value('tmetadesc', isset($default['metadesc']) ? $default['metadesc'] : ''); ?></textarea> <br /> <?php echo form_error('tmetadesc', '<p class="field_error">', '</p>');?> </td></tr>			
						<tr> <td> <label for="tmetakey"> Site Meta Keywords </label> </td> <td>:</td> <td> <textarea name="tmetakey" title="Meta Keyword" cols="55" rows="5"><?php echo set_value('tmetakey', isset($default['metakey']) ? $default['metakey'] : ''); ?></textarea> <br /> <?php echo form_error('tmetakey', '<p class="field_error">', '</p>');?> </td></tr>			
						<tr> <td><label for="">Image</label> </td> <td>:</td> <td> <img width="250" height="170" src="<?php echo set_value('tket', isset($default['image']) ? $default['image'] : ''); ?>" title="<?php echo set_value('tket', isset($default['image']) ? $default['image'] : ''); ?>"> </td> </tr>
						
					<tr> <td> <label for="userfile">Change image</label> </td> <td>:</td> <td> <input type="file" title="Upload image" name="userfile" size="50" /> <br /> 
					<?php echo isset($error) ? $error : ''; ?> <small>*) Leave it blank if not upload images.</small> </td> </tr>
						
					</table>
				</fieldset>
				
		  </div>
          
          <div id="tab4" class="tab_content"> 
				<fieldset class="field"> <legend> Management Configuration </legend>
					<table border="0">
						<tr> <td> <label for="tmanager"> Manager </label> </td> <td>:</td> <td> <input type="text" class="form_field" name="tmanager" title="Manager" size="30" maxlength="100" value="<?php echo set_value('tmanager', isset($default['manager']) ? $default['manager'] : ''); ?>" /> <br />  <?php echo form_error('tmanager', '<p class="field_error">', '</p>');?> </td></tr>
                        <tr> <td> <label for="taccounting"> Accounting </label> </td> <td>:</td> <td> <input type="text" class="form_field" name="taccounting" title="Accounting" size="30" maxlength="100" value="<?php echo set_value('taccounting', isset($default['accounting']) ? $default['accounting'] : ''); ?>" /> <br />  <?php echo form_error('taccounting', '<p class="field_error">', '</p>');?> </td></tr>
					</table>
				</fieldset>
				
				<p>
				<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value="Save" />  
				<input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " />
			    </p>
				
		  </div>
          
          
		  </div> 
</form>

<div class="clear"></div>

<div style="margin:5px;" id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
<form name="period_form" id="ajaxform" method="post" action="<?php echo $form_action_period; ?>">

<fieldset class="field"> <legend> Accounting Period </legend>
    <table border="0">
        
        <tr> <td> <label for="cbeginmonth"> Begin-Period </label> </td> <td>:</td> 
        <td> <?php $js = 'class="required"'; echo form_dropdown('cbeginmonth', $monthcombo, isset($default['beginmonth']) ? $default['beginmonth'] : '', $js); ?> -
             <input type="text" name="tbeginyear" class="required" id="tyear1" size="3" maxlength="4" value="<?php echo set_value('tbeginyear', isset($default['beginyear']) ? $default['beginyear'] : ''); ?>" /> 
        </td></tr>
        
        
        <tr> <td> <label for="cmonthperiod"> Period </label> </td> <td>:</td> 
        <td> <?php $js = 'class="required"'; echo form_dropdown('cmonthperiod', $monthcombo, isset($default['monthperiod']) ? $default['monthperiod'] : '', $js); ?> -
 			 <input type="text" name="tyearperiod" class="required" id="tyear" size="3" maxlength="4" value="<?php echo set_value('tyearperiod', isset($default['yearperiod']) ? $default['yearperiod'] : ''); ?>" /> 
        </td></tr>		
        
        <tr> <td> <label for="cyearend"> Year-End </label> </td> <td>:</td> 
        <td> <?php echo form_dropdown('cyearend', $monthcombo, isset($default['yearend']) ? $default['yearend'] : ''); ?> 
             <br />  <?php echo form_error('cyearend', '<p class="field_error">', '</p>');?> 
        </td></tr>	
    </table>
    
    <p>
    <input type="submit" name="submit" class="" title="" value="Save" />  
    <input type="reset" name="reset" class="" title="" value=" Cancel " />
    </p>
    
</fieldset>

</form>

</div>

<!-- links -->
<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>
