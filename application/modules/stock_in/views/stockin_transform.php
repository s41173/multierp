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
	
	<fieldset class="field" style="float:left;">  <legend> STOCK - IN </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>
				
					<tr>	
						<td> <label for="tno"> BTB-00 </label> </td> <td>:</td>
	                    <td> <input type="text" class="required" readonly name="tno" id="tno" size="5" title="Name" onKeyUp="checkdigit(this.value, 'tno')"
		                     value="<?php echo isset($code) ? $code : ''; ?>" /> &nbsp; <br /> </td>
					</tr>
					
					<tr>	
						 <td> <label for="tdate"> Date </label> </td> <td>:</td>
						 <td>  
						   <input type="Text" name="tdate" id="d1" title="Date" size="10" class="required"
						   value="<?php echo set_value('tdate', isset($default['date']) ? $default['date'] : ''); ?>" /> 
				           <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> &nbsp; <br />
						</td>
					</tr>
					

					<tr>
						<td> <label for="tpo"> PO-00 : </label>  </td> <td>:</td>
						<td> <input type="text" class="required" readonly name="tpo" id="titem" size="3" title="Transaction Code"
						     value="<?php echo set_value('tno', isset($po) ? $po : ''); ?>" />
                             <?php echo anchor_popup(site_url("purchase/item_list/".$po), '[ View ]', $atts1); ?> &nbsp; <br />
						</td>
					</tr>
			
					<tr>	
						<td> <label for="tstaff"> Vendor / Staff </label> </td> <td>:</td>
	                    <td> <input type="text" class="required" name="tstaff" size="20" title="Staff"
						     value="<?php echo set_value('tstaff', isset($default['staff']) ? $default['staff'] : ''); ?>" /> &nbsp; <br /> </td>
					</tr>

					<tr>	
						<td> <label for="tuser"> Warehouse - Dept </label> </td> <td>:</td>
	                    <td> <input type="text" class="required" readonly name="tuser" size="15" title="User" 
						     value="<?php echo isset($user) ? $user : ''; ?>" /> &nbsp; <br /> </td>
					</tr>

					<tr> <td> <label for="tdesc"> Description </label> </td> <td>:</td> 
	<td> <textarea name="tdesc" class="" title="Description" cols="45" rows="3"><?php echo set_value('tdesc', isset($default['desc']) ? $default['desc'] : ''); ?></textarea> 
	<br /> </td></tr>	
	
	               <tr>
				   	<td> <input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> 
					     <input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " />
					</td>
				   </tr>
					
					
				</table>  
	</fieldset>  	
	</form>		
	
	<div class="clear"></div>
	
	<fieldset class="field"> <legend> Item Transaction </legend>
	<form name="modul_form" class="myform" id="ajaxform2" method="post" action="<?php echo $form_action_item; ?>">
		<table>
			<tr>				
				<td> <label for="tproduct"> Product </label>  <br />
				     <input type="text" class="required" readonly name="titem" id="tproduct" size="30" title="Name" /> &nbsp;
				     <?php echo anchor_popup(site_url("product/get_list/"), '[ ... ]', $atts1); ?> &nbsp; &nbsp; </td>
				
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
    
	
</div>

</body>