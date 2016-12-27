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

<div id="webadmin">
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Account Data </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>" >
				<table>
                	<tr> 
  <td> <label for="cclassification"> Classification </label> </td> <td>:</td>
  <td> <?php $js = 'id="cclassification"'; 
	   echo form_dropdown('cclassification', $classification, isset($default['classification']) ? $default['classification'] : '', $js); ?> </td> &nbsp; 
					</tr>
                
					<tr> 
  <td> <label for="ccurrency"> Currency </label> </td> <td>:</td>
  <td> <?php $js='class=""'; echo form_dropdown('ccurrency', $currency, isset($default['currency']) ? $default['currency'] : '', $js); ?> </td> &nbsp; 
					</tr>
					
					<tr>	
						<td> <label for="tno"> Code / No </label> </td> <td>:</td>
 	                    <td> <input type="text" class="required" readonly="readonly" name="tno" id="tcno" size="4" title="No" /> --
                             <input type="text" class="required" name="tcode" id="taccode" size="3" title="No" />
                             <br /> </td>
					</tr>
                    
                    <tr>
						<td> <label for="tname"> Name </label> </td>  <td>:</td>
						<td> <input type="text" class="required" name="tname" size="35" title="Name" /> &nbsp; <br /> </td>
					</tr>
                    
                     <tr>
						<td> <label for="talias"> Alias </label> </td>  <td>:</td>
						<td> <input type="text" class="" name="talias" size="25" title="Alias Name" /> &nbsp; <br /> </td>
					</tr>
                    
                    <tr>
                       <td> Active </td>  <td>:</td>
                       <td> <?php echo form_checkbox('cactive', '1', TRUE); ?> </td>
                    </tr>
                    
                    <tr>
                       <td> Bank / Cash </td>  <td>:</td>
                       <td> <?php echo form_checkbox('cbank', '1', FALSE); ?> </td>
                    </tr>
					
					<tr>
						<td> <label for="taccno"> Acc No </label> </td>  <td>:</td>
						<td> <input type="text" name="taccno" size="25" title="Acc No" /> &nbsp; <br /> </td>
					</tr>
					
					<tr> <td> <label for="tbank"> Bank </label> </td> <td>:</td> 
				    <td> <textarea name="tbank" title="Bank" cols="45" rows="3"></textarea> &nbsp; <br /> </td></tr>	
                    
                    <tr> 
  <td> <label for="ccity"> City </label> </td> <td>:</td>
  <td> <?php $js='class=""'; echo form_dropdown('ccity', $city, isset($default['city']) ? $default['city'] : '', $js); ?> </td> &nbsp; 
					</tr>
                    
                    <tr>
						<td> <label for="tphone"> Phone / Fax </label> </td>  <td>:</td>
						<td> <input type="text" name="tphone" size="15" title="Phone No" /> /
                             <input type="text" name="tfax" size="15" title="Fax No" /> 
                        &nbsp; <br /> </td>
					</tr>
                     
                    <tr>
						<td> <label for="tzip"> Zip (Code) </label> </td>  <td>:</td>
						<td> <input type="text" name="tzip" size="5" title="Zip No" /> &nbsp; <br /> </td>
					</tr>
                    
                    <tr>
						<td> <label for="tcontact"> Contact Person </label> </td>  <td>:</td>
						<td> <input type="text" name="tcontact" size="25" title="Contact Person" /> &nbsp; <br /> </td>
					</tr>
                    
                    <tr>
						<td> <label for="tbalancephone"> Balance Phone </label> </td>  <td>:</td>
						<td> <input type="text" name="tbalancephone" size="15" title="Balance Phone No" /> &nbsp; <br /> </td>
					</tr>
					   
				</table>
				<p style="margin:15px 0 0 0; float:right;">
					<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> 
					<input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " />
				</p>	
			</form>			  
	</fieldset>
</div>

