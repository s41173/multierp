<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'development-bundle/themes/base/ui.all.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/jquery.fancybox-1.3.4.css'; ?>");</style>

<script type="text/javascript" src="<?php echo base_url();?>js/register.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/datetimepicker_css.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/development-bundle/ui/ui.core.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tools.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/hoverIntent.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/complete.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/sortir.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/validate.js"></script> 
<script type='text/javascript' src='<?php echo base_url();?>js/jquery.validate.js'></script>  

<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";
</script>

<script type="text/javascript">	
	function refreshparent() { opener.location.reload(true); }
	function set_item(val){ if (val == 1){ document.getElementById("tproduct").readOnly = true; }else { document.getElementById("tproduct").readOnly = false; } }
</script>

<style>
        .refresh{ border:1px solid #AAAAAA; color:#000; padding:2px 5px 2px 5px; margin:0px 2px 0px 2px; background-color:#FFF;}
		.refresh:hover{ background-color:#CCCCCC; color: #FF0000;}
		.refresh:visited{ background-color:#FFF; color: #000000;}	
</style>

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

<body onUnload="refreshparent();">  
<div id="webadmin">
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field" style="float:left;"> <legend> Cash - OUT </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>
                
                	<tr> 
						<td> <label for="tcustomer"> Vendor </label> </td> <td>:</td>
						<td> <input type="text" readonly name="tcustomer" id="tcust" size="25" title="Name" 
                             value="<?php echo set_value('tcustomer', isset($default['vendor']) ? $default['vendor'] : ''); ?>" /> &nbsp; 
					</tr>
					
					<tr>	
						<td> <label for="tno"> No </label> </td> <td>:</td>
	     <td> <input type="text" class="required" readonly name="tno" size="4" title="Name" value="<?php echo isset($code) ? $code : ''; ?>" /> &nbsp; <br /> </td>
					</tr>
                    
					<tr>	
						 <td> <label for="tdate"> Date </label> </td> <td>:</td>
						 <td>  
						   <input type="Text" name="tdate" id="d1" title="Invoice date" size="10" class="required"
						   value="<?php echo set_value('tdate', isset($default['date']) ? $default['date'] : ''); ?>" /> 
				           <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> &nbsp; <br />
						</td>
					</tr>
                    
			<tr>	
   <td> <label for="tcur"> Currency </label> </td> <td>:</td>
  <td> <input type="text" size="5" name="tcur" readonly value="<?php echo set_value('tcur', isset($default['currency']) ? $default['currency'] : ''); ?>"> </td>
			</tr>
            
            <tr> <td> <label for="cacc"> Account </label> </td> <td>:</td> <td>  
			<?php $js = 'class="required"'; echo form_dropdown('cacc', $account, isset($default['acc']) ? $default['acc'] : '', $js); ?>
			<br />  </td> </tr>
					
        <tr>
            <td> <label for="tnote"> Note </label> </td>  <td>:</td>
            <td>  <input type="text" class="required" name="tnote" size="54" title="Note"
            value="<?php echo set_value('tnote', isset($default['note']) ? $default['note'] : ''); ?>" /> &nbsp; <br /> </td>
        </tr>
					
					<tr> <td> <label for="tdesc"> Description </label> </td> <td>:</td> 
<td> <textarea name="tdesc" class="required" title="Description" cols="40" rows="3"><?php echo set_value('tdesc', isset($default['desc']) ? $default['desc'] : ''); ?></textarea> &nbsp; <br /> </td></tr>	
					   
				</table>  
	</fieldset>
	
	<fieldset class="field" style="float:left; margin-left:15px;"> <legend> Total </legend>
		
		<table>			
			<tr>
				<td> <label for="tbalance"> Balance </label></td> <td>:</td> 
				<td><input type="text" id="tbalance" disabled="disabled" name="tbalance" readonly size="10" title="Balance" 
			    value="<?php echo set_value('tbalance', isset($default['balance']) ? $default['balance'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tbalance')" /> <br />  </td> 
			</tr>
		</table>
		
	</fieldset>
	
	
	
	<p style="margin:10px 0 0 10px; float:left;">
		<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> 
		<input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " />
	</p>	
	</form>		
	
	<div class="clear"></div>
	
	<fieldset class="field"> <legend> Item Transaction </legend>
	<form name="modul_form" class="myform" id="ajaxform2" method="post" action="<?php echo $form_action_item; ?>">
		<table>
			<tr>
								
				<td> <label for="titem"> Account </label>  <br />
				     <input type="text" class="required" readonly name="titem" id="titem" size="10" title="Name" /> &nbsp;
				     <?php echo anchor_popup(site_url("accountc/get_list/"), '[ ... ]', $atts1); ?> &nbsp; &nbsp; </td>
								
				<td>  
					<label for="tcredit"> Debit : </label> <br />
					<input type="text" name="tdebit" id="tdebit" size="10" title="Credit" onKeyUp="checkdigit(this.value, 'tcredit')" /> &nbsp;
				</td>
                
				<td> <br />
					<input type="submit" name="submit" class="button" title="POST" value="POST" /> 
				</td>
			</tr>
		</table>
		
		<div class="clear"></div>
		<?php echo ! empty($table) ? $table : ''; ?>
		
	</form>
        
	</fieldset>
    
</div>
</body>