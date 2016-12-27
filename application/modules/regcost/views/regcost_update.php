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
	
	<fieldset class="field"> <legend> Update - Registration Cost </legend>
    <table>
     
	<form name="modul_form" id="ajaxform" class="myform" method="post" action="<?php echo $form_action; ?>">	
                    				
		 <tr>
         <td> <label for="cdept"> Department </label> </td>  <td>:</td>
         <td> <input type="text" size="10" readonly value="<?php echo set_value('tdept', isset($default['dept']) ? $default['dept'] : ''); ?>">
         </td>
         </tr>	
         
         <tr>
         <td> <label for="tlevel"> Level </label> </td>  <td>:</td>
         <td> <input type="text" size="1" readonly value="<?php echo set_value('tlevel', isset($default['level']) ? $default['level'] : ''); ?>">
         </td>
         </tr>	
        
         <tr>
         <td> <label for="tregistration"> Registration </label> </td>  <td>:</td>
         <td> <input type="text" class="required" name="tregistration" id="tregistration" size="10" title="Registration" 
              onKeyUp="checkdigit(this.value,'tregistration')" 
              value="<?php echo set_value('tregistration', isset($default['registration']) ? $default['registration'] : ''); ?>" /> 
         </td>
         </tr>
         
         <tr>
         <td> <label for="tdevelopment"> Development </label> </td>  <td>:</td>
         <td> <input type="text" class="required" name="tdevelopment" id="tdevelopment" size="10" title="Development" 
              onKeyUp="checkdigit(this.value,'tdevelopment')" 
              value="<?php echo set_value('tdevelopment', isset($default['development']) ? $default['development'] : ''); ?>" /> 
         </td>
         </tr>
         
         <tr>
         <td> <label for="tschool"> School </label> </td>  <td>:</td>
         <td> <input type="text" class="required" name="tschool" id="tschool" size="10" title="School Fee" 
              onKeyUp="checkdigit(this.value,'tschool')" 
              value="<?php echo set_value('tschool', isset($default['school']) ? $default['school'] : ''); ?>" /> 
         </td>
         </tr>
         
         <tr>
         <td> <label for="tosis"> OSIS </label> </td>  <td>:</td>
         <td> <input type="text" class="required" name="tosis" id="tosis" size="10" title="OSIS Fee" 
              onKeyUp="checkdigit(this.value,'tosis')" 
              value="<?php echo set_value('tosis', isset($default['osis']) ? $default['osis'] : ''); ?>" /> 
         </td>
         </tr>
         
         <tr>
         <td> <label for="tcomputer"> Computer </label> </td>  <td>:</td>
         <td> <input type="text" class="required" name="tcomputer" id="tcomputer" size="10" title="Computer Fee" 
              onKeyUp="checkdigit(this.value,'tcomputer')" 
              value="<?php echo set_value('tcomputer', isset($default['computer']) ? $default['computer'] : ''); ?>" /> 
         </td>
         </tr>
         
         <tr>
         <td> <label for="tpractice"> Practice </label> </td>  <td>:</td>
         <td> <input type="text" class="required" name="tpractice" id="tpractice" size="10" title="Practice Fee" 
              onKeyUp="checkdigit(this.value,'tpractice')" 
              value="<?php echo set_value('tpractice', isset($default['practice']) ? $default['practice'] : ''); ?>" /> 
         </td>
         </tr>
         
         <tr>
         <td> <label for="tother"> Other Cost </label> </td>  <td>:</td>
         <td> <input type="text" class="required" name="tother" id="tother" size="10" title="Other Fee" 
              onKeyUp="checkdigit(this.value,'tother')" 
              value="<?php echo set_value('tother', isset($default['other']) ? $default['other'] : ''); ?>" /> 
         </td>
         </tr>
         
         <tr>
         <td> <label for="tp1"> P1 </label> </td>  <td>:</td>
         <td> <input type="text" class="required" name="tp1" id="tp1" size="10" title="P1" 
              onKeyUp="checkdigit(this.value,'tp1')" 
              value="<?php echo set_value('tp1', isset($default['p1']) ? $default['p1'] : ''); ?>" /> 
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