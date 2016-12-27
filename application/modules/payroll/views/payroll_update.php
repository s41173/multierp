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

function cek_session()
{
	$(document).ready(function(){
		$.ajax({
			type: 'POST',
			url: uri +'cek_session',
			data: $(this).serialize(),
			success: function(data){ if (data == 'FALSE'){ window.close(); } }
		})
		return false;	
	}); 
}



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
<body onLoad="cek_session();" onUnload="window.opener.location.reload(true);">

<div id="webadmin">
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Edit - Payroll Journal </legend>
    <table>
     
	<form name="modul_form" id="ajaxform" class="myform" method="post" action="<?php echo $form_action; ?>">	
          
         <tr>                 
         <td> <label for="tdesc"> Date </label> </td> <td>:</td> 
         <td> <input type="Text" name="tdate" id="d1" title="Start date" size="10" class="form_field"
              value="<?php echo set_value('tdates', isset($default['dates']) ? $default['dates'] : ''); ?>" /> 
              <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> 
         </td> 
		 </tr> 
                  
         <tr> <td> <label for="cacc"> Account </label> </td> <td>:</td> <td>  
			<select name="cacc" class="required">
<option value="bank" <?php echo set_select('cacc', 'bank', isset($default['acc']) && $default['acc'] == 'bank' ? TRUE : FALSE); ?> /> Bank </option>
<option value="cash" <?php echo set_select('cacc', 'cash', isset($default['acc']) && $default['acc'] == 'cash' ? TRUE : FALSE); ?> /> Cash </option>
<option value="pettycash" <?php echo set_select('cacc', 'pettycash', isset($default['acc']) && $default['acc'] == 'pettycash' ? TRUE : FALSE); ?> /> Petty Cash </option>
			</select>
              </td>
         </tr>
        
         <tr>
            <td> <label for="tnote"> Note </label> </td>  <td>:</td>
            <td> <input type="text" class="required" name="tnote" size="35" title="Note" 
                 value="<?php echo set_value('tnote', isset($default['notes']) ? $default['notes'] : ''); ?>" /> &nbsp; <br /> 
            </td>
         </tr>
         
        </table>
        <p style="margin:15px 0 0 0; float:right;">
            <input type="submit" name="submit" class="button" title="" value=" Save " /> 
            <input type="reset" name="reset" class="button" title="" value=" Cancel " />
        </p>	
        </form>			  
	</fieldset>
</div>

</body>