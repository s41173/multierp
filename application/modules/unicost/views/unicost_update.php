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
	
	<fieldset class="field"> <legend> Update - Uniform Cost </legend>
    <table>
     
	<form name="modul_form" id="ajaxform" class="myform" method="post" action="<?php echo $form_action; ?>">	
                    				
		 <tr>
         <td> <label for="cdept"> Department </label> </td>  <td>:</td>
         <td> <input type="text" size="10" readonly value="<?php echo set_value('tdept', isset($default['dept']) ? $default['dept'] : ''); ?>">
         </td>
         </tr>	
        
         <tr>
         <td> <label for="tmale"> Male </label> </td>  <td>:</td>
         <td> <input type="text" class="required" name="tmale" id="tmale" size="10" title="Male Cost" 
              onKeyUp="checkdigit(this.value,'tmale')" 
              value="<?php echo set_value('tmale', isset($default['male']) ? $default['male'] : ''); ?>" /> 
         </td>
         </tr>
         
         <tr>
         <td> <label for="tfemale"> Female </label> </td>  <td>:</td>
         <td> <input type="text" class="required" name="tfemale" id="tfemale" size="10" title="Female Cost" 
              onKeyUp="checkdigit(this.value,'tfemale')" 
              value="<?php echo set_value('tfemale', isset($default['female']) ? $default['female'] : ''); ?>" /> 
         </td>
         </tr>
         
         <tr>
         <td> <label for="tpractice"> Practice </label> </td>  <td>:</td>
         <td> <input type="text" class="required" name="tpractice" id="tpractice" size="10" title="Practice Cost" 
              onKeyUp="checkdigit(this.value,'tpractice')" 
              value="<?php echo set_value('tpractice', isset($default['practice']) ? $default['practice'] : ''); ?>" /> 
         </td>
         </tr>
         
         <tr>
         <td> <label for="tscout"> Scout </label> </td>  <td>:</td>
         <td> <input type="text" class="required" name="tscout" id="tscout" size="10" title="Scout Cost" 
              onKeyUp="checkdigit(this.value,'tscout')" 
              value="<?php echo set_value('tscout', isset($default['scout']) ? $default['scout'] : ''); ?>" /> 
         </td>
         </tr>
         
         <tr>
         <td> <label for="tadd"> Additional </label> </td>  <td>:</td>
         <td> <input type="text" class="required" name="tadd" id="tadd" size="10" title="Additional Cost" 
              onKeyUp="checkdigit(this.value,'tadd')" 
              value="<?php echo set_value('tadd', isset($default['add']) ? $default['add'] : ''); ?>" /> 
         </td>
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