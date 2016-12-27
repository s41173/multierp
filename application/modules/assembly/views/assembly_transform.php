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
	
	<fieldset class="field" style="float:left;"> <legend> Product Assembly </legend>
	
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>
					
	<tr>	
	<td> <label for="tno"> AP-00 </label> </td> <td>:</td>
    <td> <input type="text" class="required" readonly name="tno" id="tno" size="4" title="Name" value="<?php echo isset($code) ? $code : ''; ?>" /> &nbsp; <br /> </td>
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
    <td> <label for="tname"> Currency </label> </td> <td>:</td>
    <td> <?php $js = 'class="required"'; echo form_dropdown('ccurrency', $currency, isset($default['currency']) ? $default['currency'] : '', $js); ?> &nbsp; <br /> </td>
	</tr>				
					
	<tr>	
    <td> <label for="tproject"> Project </label> </td> <td>:</td>
	<td>  
	<input type="text" name="tproject" readonly id="titem" title="Project" size="5" 
	value="<?php echo set_value('tproject', isset($default['project']) ? $default['project'] : ''); ?>" /> 
    <?php echo anchor_popup(site_url("project/get_list/"), '[ ... ]', $atts1); ?> &nbsp;
	</td>
	</tr>	
		
	<tr>	
	<td> <label for="tproduct"> Product </label> </td> <td>:</td>
	<td>  
	<input type="text" name="tproduct" readonly id="tproduct" title="Product" size="20" class="required"
	value="<?php echo set_value('tproduct', isset($default['product']) ? $default['product'] : ''); ?>" /> 
	<?php echo anchor_popup(site_url("gproduct/get_list/"), '[ ... ]', $atts1); ?> &nbsp;
	<label for="tqty"> Qty : </label> <input type="text" name="tqty" id="tqtys" title="Qty" size="3" onKeyUp="checkdigit(this.value, 'tqtys')"
	value="<?php echo set_value('tqty', isset($default['qty']) ? $default['qty'] : ''); ?>" /> 
	</td>
	</tr>				
					
	<tr>
	<td> <label for="tdocno"> Document No </label> </td>  <td>:</td>
	<td>  <input type="text" class="" name="tdocno" size="15" title="Document No"
	      value="<?php echo set_value('tdocno', isset($default['docno']) ? $default['docno'] : ''); ?>" /> &nbsp; <br /> </td>
	</tr>
							
	<tr>
	<td> <label for="tnote"> Note </label> </td>  <td>:</td>
	<td> <input type="text" class="required" name="tnote" size="56" title="Note" 
    	 value="<?php echo set_value('tnote', isset($default['note']) ? $default['note'] : ''); ?>" /> &nbsp; <br /> </td>
	</tr>
	
	<tr>	
	<td> <label for="tuser"> Production Dept </label> </td> <td>:</td>
    <td> <input type="text" class="required" readonly name="tuser" size="15" title="User" value="<?php echo isset($user) ? $user : ''; ?>" /> &nbsp; <br /> </td>
	</tr>				
		
	<tr> 
	<td> <label for="tdesc"> Description </label> </td> <td>:</td> <td> 
	<textarea name="tdesc" class="required" title="Description" cols="45" rows="3"><?php echo set_value('tdesc', isset($default['desc']) ? $default['desc'] : ''); ?></textarea>    &nbsp; <br /> </td>
	</tr>					
					   
				</table>  
	</fieldset>
	
	<fieldset class="field" style="float:left; margin-left:15px;"> <legend> Payment Details </legend>
		
		<table>
        
            <tr>
				<td> <label for="tinstock"> In - Stock </label></td> <td>:</td> 
				<td> <input type="text" readonly name="tinstock" size="10" title="In-stock" 
					value="<?php echo set_value('tinstock', isset($default['instock']) ? $default['instock'] : '0'); ?>" /> <br /> 
                </td> 
			</tr>	
        	
			
			<tr>
				<td> <label for="tcosts"> Costs </label></td> <td>:</td> 
				<td> <input type="text" id="tcosts" readonly name="tcosts" size="10" title="Costs" 
					value="<?php echo set_value('tcosts', isset($default['costs']) ? $default['costs'] : '0'); ?>" 
                    onKeyUp="checkdigit(this.value, 'tcosts')" /> <br /> 
                </td> 
			</tr>	
            
            <tr>
				<td> <label for="ttotal"> Total </label></td> <td>:</td> 
				<td><input type="text" id="ttotal" readonly name="ttotal" size="10" title="Total" 
					value="<?php echo set_value('ttotal', isset($default['total']) ? $default['total'] : '0'); ?>" /> <br /> 
                </td> 
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
				
				<td> <label for="tproduct"> Product </label>  <br />
				     <input type="text" class="required" readonly name="titem" id="titemproduct" size="40" title="Name" /> &nbsp;
				     <?php echo anchor_popup(site_url("assembly/get_product/".$default['currency'].'/'), '[ ... ]', $atts1); ?> &nbsp; &nbsp; </td>
				
				<td>  
					<label for="tqty"> Qty : </label> <br />
					<input type="text" name="tqty" id="stqty" size="3" title="Qty" onKeyUp="checkdigit(this.value, 'stqty')" /> &nbsp;
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
	
	<div class="clear"></div>
    
    <fieldset class="field"> <legend> Cost Type </legend>
	<form name="modul_form" class="myform" id="ajaxform4" method="post" action="<?php echo $form_action_cost; ?>">
		<table>
			<tr>
				
				<td> <label for="tnotes"> Notes </label>  <br />
				     <input type="text" class="required" name="tnotes" size="35" title="Notes" /> &nbsp;
			    </td>
				
				<td>  
					<label for="tamount"> Amount : </label> <br />
					<input type="text" name="tamount" id="tamount" size="10" title="Amount" onKeyUp="checkdigit(this.value, 'tamount')" /> &nbsp;
				</td>
                				
				<td> <br />
					<input type="submit" name="submit" class="button" title="POST" value="POST" /> 
				</td>
			</tr>
		</table>
		
		<div class="clear"></div>
		<?php echo ! empty($table3) ? $table3 : ''; ?>
		
	</form>
	</fieldset>
	
	<div class="clear"></div>
	
	<fieldset class="field"> <legend> Rest Item </legend>
	<form name="modul_form" class="myform" id="ajaxform3" method="post" action="<?php echo $form_action_rest; ?>">
		<table>
			<tr>
				
				<td> <label for="tproduct"> Product </label>  <br />
				     <input type="text" class="required" readonly name="titem" id="tproduct2" size="40" title="Name" /> &nbsp;
				     <?php echo anchor_popup(site_url("product/get_list/".$default['currency'].'/tproduct2/'), '[ ... ]', $atts1); ?> &nbsp; &nbsp; </td>
				
				<td>  
					<label for="tqty"> Qty : </label> <br />
					<input type="text" name="tqty" id="stqty2" size="3" title="Qty" onKeyUp="checkdigit(this.value, 'stqty2')" /> &nbsp;
				</td>
				
				<td>
				   <label for="cunit"> Unit : </label> <br />
				   <?php $js = 'class="required"'; echo form_dropdown('cunit', $unit, isset($default['unit']) ? $default['unit'] : '', $js); ?>
				</td>
								
				<td> <br />
					<input type="submit" name="submit" class="button" title="POST" value="POST" /> 
				</td>
			</tr>
		</table>
		
		<div class="clear"></div>
		<?php echo ! empty($table2) ? $table2 : ''; ?>
		
	</form>
	</fieldset>
	
</div>

</body>