<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
<form name="admin_form" id="ajaxform" class="myform" method="post" action="<?php echo $form_action; ?>">

		<ul class="tabs">
			<li><a href="#tab1"> General </a></li>
			<li><a href="#tab2"> Address &amp; Notes </a></li>
			<li><a href="#tab3"> Account &amp; Bank </a></li>
		</ul>
		
		  <div class="tab_container">
			<div id="tab1" class="tab_content">
				<fieldset class="field">
					<table border="0">
					
						<tr> <td> <label for="tname"> Company (*) </label> </td> <td>:</td> 
						<td> <input type="text" class="" name="tpre" title="Prefix" size="3" maxlength="5" /> 
    						 <input type="text" class="required" name="tname" title="Name" size="35" maxlength="100" /> 
						<br />  </td></tr>
						
						<tr> <td> <label for="ctype">Type</label> </td> <td>:</td> <td>  
						<select name="ctype" class="required">
							<option value="company" selected="selected"> Company </option>
							<option value="personal"> Personal </option>
						</select>
						<br />  </td> </tr>
						
						<tr> <td> <label for="tcontact">Contact (*)</label></td> <td>:</td> 
						<td> <input type="text" class="" name="tcontact" size="25" title="Contact Person" /> <br /> </td> </tr>
						
						<tr> <td> <label for="tnpwp">NPWP</label></td> <td>:</td> 
						<td> <input type="text" class="" name="tnpwp" size="25" title="NPWP" /> <br /> </td> </tr>
					
								
						<tr> <td> <label for="tphone">Phone (*) </label> </td> <td>:</td> 
						<td> <input type="text" title="Phone no" class="required" name="tphone" id="tphone" size="15" maxlength="15" /> / 
						<input type="text" title="Phone no" class="" name="tphone2" id="tphone2" size="15" maxlength="15" />
					    <br />  </td> </tr>
						
						<tr> <td> <label for="tmobile">Mobile</label> </td> <td>:</td> 
			            <td> <input type="text" title="Mobile" name="tmobile" id="tmobile" size="15" maxlength="15" onkeyup="checkdigit(this.value, 'tmobile')" /> <br />
			            </td> </tr>
						
						<tr> <td> <label for="tfax">Fax</label> </td> <td>:</td> 
						<td> <input type="text" title="Fax" class="required" name="tfax" id="tphone3" size="15" maxlength="15" /> <br />  </td> </tr>
						
						<tr> <td> <label for="tmail">Email</label></td> <td>:</td> 
						<td><input type="text" class="email" name="tmail" size="32" title="Type mail" /> <br /> </td> </tr>
					
						
					</table>
				</fieldset>
			</div>
			
			<div id="tab2" class="tab_content"> 
				<fieldset class="field"> 
					<table border="0">
					
						<tr> <td> <label for="taddress">Address (*) </label> </td> <td>:</td> 
						<td> <textarea name="taddress" class="required" title="Address" cols="45" rows="3"><?php echo set_value('taddress', isset($default['address']) ? $default['address'] : ''); ?></textarea> <br /> </td></tr>	
						
						<tr> <td> <label for="tshipaddress">Shipping Address (*) </label> </td> <td>:</td> 
						<td> <textarea name="tshipaddress" class="required" title="Shipping Address" cols="45" rows="3"> </textarea> <br /> </td></tr>	
						
						<tr> <td> <label for="tcity">City</label> </td> <td>:</td> 
						<td> <?php $js = 'class="required"'; echo form_dropdown('ccity', $city, isset($default['city']) ? $default['city'] : '', $js); ?> <br/> </td> </tr>
					
						<tr> <td> <label for="turl"> Website (Url) </label></td> <td>:</td> 
						<td> <input type="text" name="turl" size="30" title="Url" /> <br /> </td> </tr>
						
						<tr> <td> <label for="tzip">Zip Code (*)</label> </td> <td>:</td> 
			            <td> <input type="text" title="Zip Code" name="tzip" id="tzip" size="5" maxlength="10" onkeyup="checkdigit(this.value, 'tzip')" /> <br />
			            </td> </tr>
						
						<tr> <td> <label for="tnotes"> Notes </label> </td> <td>:</td> 
						<td> <textarea name="tnotes" title="Notes" cols="45" rows="3"> </textarea> <br /> </td></tr>	
						
					</table>
				</fieldset>
			</div>
			
			<div id="tab3" class="tab_content"> 
				<fieldset class="field"> 
					<table border="0">
					
						<tr> <td> <label for="taccname"> Acc Name </label> </td> <td>:</td> 
						<td> <input type="text" name="taccname" size="35" title="Acc Name" /> <br /> </td></tr>
						
						<tr> <td> <label for="taccno"> Acc No </label> </td> <td>:</td> 
						<td> <input type="text" name="taccno" size="20" title="Acc No" /> <br /> </td></tr>
					
						<tr> <td> <label for="tbank"> Bank </label> </td> <td>:</td> 
						<td> <textarea name="tbank" class="" title="Bank" cols="45" rows="3"><?php echo set_value('tbank', isset($default['bank']) ? $default['bank'] : ''); ?>                             </textarea> <br /> </td></tr>	
                        
                        <tr> <td> <label for="tswiftcode"> Swiftcode </label> </td> <td>:</td> 
						<td> <input type="text" name="tswiftcode" size="20" title="Swift Code" /> <br /> </td></tr>
						
						<tr> <td> <input type="submit" name="submit" class="button" value="Save" /> <input type="reset" name="reset" class="button" value=" Cancel " /> </td> </tr>
					</table>
				</fieldset>
			</div>
			
		  </div>	
	</form>
</div>

<div id="webadmin2">
	<fieldset class="field"> <legend> Vendor - Search </legend>
	<form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action_search; ?>">
				<table>
					<tr> 
					
                    <td colspan="4"> Name ( Keyword ) : &nbsp; <br /> 
					                 <input type="text" name="tsearch" size="40" id="tvendorsearch" />  
               					     <input type="submit" name="submit" class="" title="" value="Search" /> 
					</td>		
					</tr>
					
				</table>	
			</form>			  
	</fieldset>
    
    <?php echo ! empty($table) ? $table : ''; ?>
	<div class="paging"> <?php echo ! empty($pagination) ? $pagination : ''; ?> </div>
	
	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>
</div>

