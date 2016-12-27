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

<body onunload="refreshparent();">

<div id="webadmin">
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Order Project </legend>
	<form name="modul_form" class="myform" id="forms" method="post" action="<?php echo $form_action; ?>" >
				<table>
					<tr> 
						<td> <label for="tcust"> Customer </label> </td> <td>:</td>
						<td> <input type="text" class="required" readonly="readonly" name="tcust" id="tcust" size="25" title="Name" 
						value="<?php echo set_value('tcust', isset($default['customer']) ? $default['customer'] : ''); ?>" /> &nbsp; 
						<?php echo anchor_popup(site_url("customer/get_list/"), '[ ... ]', $atts1); ?>
					</tr>
					
					<tr>	
						<td> <label for="tno"> PRJ-00 </label> </td> <td>:</td>
	     <td> <input type="text" class="required" readonly="readonly" name="tno" size="4" title="Name" value="<?php echo isset($code) ? $code : ''; ?>" /> &nbsp; <br /> </td>
					</tr>
					
					<tr>	
						 <td> <label for="tdate"> Date </label> </td> <td>:</td>
						 <td>  
						   <input type="Text" name="tdate" id="d1" title="Invoice date" size="10" class="required" 
						   value="<?php echo set_value('tdate', isset($default['date']) ? $default['date'] : ''); ?>" /> 
				           <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> &nbsp; <br />
						</td>
					</tr>
					
			
					<tr>
						<td> <label for="tnote"> Note </label> </td>  <td>:</td>
						<td>  <input type="text" class="required" name="tnote" size="56" title="Note" 
						value="<?php echo set_value('tnote', isset($default['note']) ? $default['note'] : ''); ?>"/> &nbsp; <br /> </td>
					</tr>
					
					<tr>	
						<td> <label for="tuser"> Staff </label> </td> <td>:</td>
	                    <td> <input type="text" class="required" name="tstaff" size="15" title="Staff"
						value="<?php echo set_value('tstaff', isset($default['staff']) ? $default['staff'] : ''); ?>" /> &nbsp; <br /> </td>
					</tr>
					
					<tr>	
						<td> <label for="tlocation"> Location </label> </td> <td>:</td>
<td> <textarea name="tlocation" rows="3" cols="40"><?php echo set_value('tlocation', isset($default['location']) ? $default['location'] : ''); ?></textarea> &nbsp; <br /> </td>
					</tr>
					
					<tr>	
						<td> <label for="tdesc"> Desc </label> </td> <td>:</td>
                <td> <textarea name="tdesc" rows="2" cols="40"><?php echo set_value('tdesc', isset($default['desc']) ? $default['desc'] : ''); ?></textarea> &nbsp; <br /> </td>
					</tr>
					   
				</table>
				<p style="margin:15px 0 0 0; float:right;">
					<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> 
					<input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " />
				</p>	
			</form>			  
	</fieldset>
</div>

</body>