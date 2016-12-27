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
	  'scrollbars' => 'no',
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
	
	<fieldset class="field" style="float:left;"> <legend> Create Transaction Journal </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>
					
					
		 <tr>	
		 <td> <label for="tno"> No - DT-00 </label> </td> <td>:</td>
		 <td> <input type="text" class="required" readonly name="tno" size="4" title="Name" value="<?php echo isset($code) ? $code : ''; ?>" /> &nbsp; <br /> </td>
		 </tr>
					
					<tr>	
						 <td> <label for="tdate"> Date </label> </td> <td>:</td>
						 <td>  
						   <input type="Text" name="tdate" id="d1" title="Invoice date" size="10" class="required" readonly
						   value="<?php echo set_value('tdate', isset($default['date']) ? $default['date'] : ''); ?>" /> 
						   <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> &nbsp; <br />
						</td>
					</tr>
					
			<tr>	
			<td> <label for="tcur"> Currency </label> </td> <td>:</td>
			<td> <input type="text" name="tcur" readonly size="10" title="Currency" 
			     value="<?php echo set_value('tcur', isset($default['currency']) ? $default['currency'] : ''); ?>" /> &nbsp; <br /> </td>
			</tr>
					   
				</table>  
	</fieldset>  
	
	<fieldset class="field" style="float:left; margin-left:15px;"> <legend> Payment Details </legend>
		
		<table>
			
			<tr>
				<td> <label for="tgj"> GJ - General Journal </label></td> <td>:</td> 
				<td> <input type="text" id="tgj" disabled="disabled" name="tgj" size="10" title="General Journal" 
				     value="<?php echo set_value('tgj', isset($default['gj']) ? $default['gj'] : ''); ?>" /> <br />  </td> 
			</tr>
			
			<tr>
				<td> <label for="tdp"> DP - Down Payment </label></td> <td>:</td> 
				<td><input type="text" id="tdp" name="tdp" disabled="disabled" readonly size="10" title="Down Payment" 
					value="<?php echo set_value('tdp', isset($default['dp']) ? $default['dp'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tdp')" /> <br />  </td> 
			</tr>
			
			<tr>
				<td> <label for="tpj"> PJ - Purchase Journal </label></td> <td>:</td> 
				<td><input type="text" id="tpj" name="tpj" disabled="disabled" readonly size="10" title="Purchase Journal" 
					value="<?php echo set_value('tpj', isset($default['pj']) ? $default['pj'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tpj')" /> <br />  </td> 
			</tr>
			
    <tr>
    <td> <label for="tsj"> SJ - Sales Journal </label></td> <td>:</td> 
    <td><input type="text" id="tsj" name="tsj" disabled="disabled" readonly size="10" title="Sales Journal" 
        value="<?php echo set_value('tsj', isset($default['sj']) ? $default['sj'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tsj')" /> <br />  </td>
    </tr>
            
    <tr>
    <td> <label for="tds"> DS - Sales Down Payment </label></td> <td>:</td> 
    <td><input type="text" id="tds" name="tds" disabled="disabled" readonly size="10" title="Sales Down Payment" 
    value="<?php echo set_value('tds', isset($default['ds']) ? $default['ds'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tds')" /> <br />  </td>
    </tr>
            
    <tr>
	<td> <label for="tcsj"> CSJ - Cash Sales Journal </label></td> <td>:</td> 
    <td><input type="text" id="tcsj" name="tcsj" disabled="disabled" readonly size="10" title="Sales Journal" 
	value="<?php echo set_value('tcsj', isset($default['csj']) ? $default['csj'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tcsj')" /> <br />  </td>
	</tr>
    
    <tr>
	<td> <label for="tcds"> CDS - Cash Sales Down Payment </label></td> <td>:</td> 
	<td><input type="text" id="tcds" name="tcds" disabled="disabled" readonly size="10" title="Sales Down Payment" 
	value="<?php echo set_value('tcds', isset($default['cds']) ? $default['cds'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tcds')" /> <br />  </td> 
	</tr>
	
			<tr>
				<td> <label for="tcd"> CD - Account Payable </label></td> <td>:</td> 
				<td><input type="text" id="tcd" name="tcd" disabled="disabled" readonly size="10" title="Account Payable" 
					value="<?php echo set_value('tcd', isset($default['cd']) ? $default['cd'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tcd')" /> <br />  </td> 
			</tr>
			
			<tr>
				<td> <label for="tcg"> CG - Account Payable ( Cash ) </label></td> <td>:</td> 
				<td><input type="text" id="tcg" name="tcg" disabled="disabled" readonly size="10" title="Account Payable (Cash)" 
					value="<?php echo set_value('tcg', isset($default['cg']) ? $default['cg'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tcg')" /> <br />  </td> 
			</tr>
			
			<tr>
				<td> <label for="tcr"> CR - Account Receivable </label></td> <td>:</td> 
				<td><input type="text" id="tcr" name="tcr" disabled="disabled" readonly size="10" title="Account Receivable" 
				value="<?php echo set_value('tcr', isset($default['cr']) ? $default['cr'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tcr')" /> <br />                  </td> 
			</tr>
            
   <tr>
   <td> <label for="tccr"> CCR - Cash : Account Receivable </label></td> <td>:</td> 
   <td><input type="text" id="tccr" name="tccr" disabled="disabled" readonly size="10" title="Account Receivable" 
    value="<?php echo set_value('tccr', isset($default['ccr']) ? $default['ccr'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tccr')" /> <br />  </td> 
   </tr>
			
			<tr>
			<td> <label for="ttr"> TR - Account Transfer </label></td> <td>:</td> 
			<td><input type="text" id="ttr" name="ttr" disabled="disabled" readonly size="10" title="Account Transfer" 
			value="<?php echo set_value('ttr', isset($default['tr']) ? $default['tr'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'ttr')" /> <br />  </td 
			</tr>
			
			<tr>
				<td> <label for="tsaj"> SAJ - Sales Adjustment Journal </label></td> <td>:</td> 
				<td><input type="text" id="tsaj" name="tsaj" disabled="disabled" readonly size="10" title="Sales Adjustment Journal" 
					value="<?php echo set_value('tsaj', isset($default['saj']) ? $default['saj'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tsaj')" /> <br />  </td> 
			</tr>
			
			<tr>
				<td> <label for="tsaj"> PRJ - Purchase Return Journal </label></td> <td>:</td> 
				<td><input type="text" id="tprj" name="tprj" disabled="disabled" readonly size="10" title="Purchase Return Journal" 
					value="<?php echo set_value('tprj', isset($default['prj']) ? $default['prj'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tprj')" /> <br />  </td> 
			</tr>
			
			<tr>
				<td> <label for="tarj"> ARJ - Account Receivable Payment Journal </label></td> <td>:</td> 
				<td><input type="text" id="tarj" name="tarj" disabled="disabled" readonly size="10" title="AR-Payment Journal" 
					value="<?php echo set_value('tarj', isset($default['arj']) ? $default['arj'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tarj')" /> <br />  </td> 
			</tr>
			
			<tr>
				<td> <label for="trf"> RF - Customer Credit Refund Journal </label></td> <td>:</td> 
				<td><input type="text" id="trf" name="trf" disabled="disabled" readonly size="10" title="Refund Journal" 
					value="<?php echo set_value('trf', isset($default['rf']) ? $default['rf'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'trf')" /> <br />  </td> 
			</tr>
			
			<tr>
				<td> <label for="taj"> AJ - Assembly Journal </label></td> <td>:</td> 
				<td><input type="text" id="taj" name="taj" disabled="disabled" readonly size="10" title="Assembly Journal" 
					value="<?php echo set_value('taj', isset($default['aj']) ? $default['aj'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'taj')" /> <br />  </td> 
			</tr>
			
		</table>
		
	</fieldset>
	
	
	
	<p style="margin:10px 0 0 10px; float:left;">
		<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> 
		<input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " />
	</p>	
	</form>		
	
	<div class="clear"></div>
	
	<fieldset class="field"> <legend> Transaction </legend>
	<form name="modul_form" class="myform" id="ajaxform2" method="post" action="<?php echo $form_action_item; ?>">
		<!--<table>
			<tr>
				<td>  
					<label for="tname"> Name : </label> <br />
					<input type="text" name="tname" size="45" title="Transaction Name" /> &nbsp;
				</td>
				
				<td>  
					<label for="tqty"> Type : </label> <br />
					<select name="ctype">
						<option value="AR"> AR - ( Account Receivable ) </option>
						<option value="AP"> AP - ( Account Payable ) </option>
					</select> &nbsp;
				</td>
			
				<td>  
					<label for="tamount"> Amount : </label> <br />
					<input type="text" name="tamount" id="tamount" size="10" title="Amount" onKeyUp="checkdigit(this.value, 'tamount')" /> &nbsp;
				</td>
				
				<td> <br />
					<input type="submit" name="submit" class="button" title="POST" value="POST" /> 
				</td>
			</tr>
		</table> -->
		
		<div class="clear"></div>
		<?php echo ! empty($table) ? $table : ''; ?>
		
	</form>
	</fieldset>
	
</div>
</body>
