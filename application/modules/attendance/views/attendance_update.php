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
<body onLoad="cek_session();" onUnload="window.opener.location.reload(false);">

<div id="webadmin">
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Update - Employee Attendance </legend>
    <table>
     
	<form name="modul_form" id="ajaxform" class="myform" method="post" action="<?php echo $form_action; ?>">
			
         <tr>
         <td> <label for="tnip"> Employee </label> </td> <td>:</td>
         <td> <input type="text" readonly name="tnip" size="30" title="Name"
              value="<?php echo set_value('tnip', isset($default['employee']) ? $default['employee'] : ''); ?>" />
         </td>
         </tr>
                     				         
         <tr>
         <td> <label for="tmonth"> Month </label> </td> <td>:</td>
         <td> <input type="text" readonly name="tmonth" size="5" title="Name"
              value="<?php echo set_value('tmonth', isset($default['month']) ? $default['month'] : ''); ?>" /> - 
              <input type="text" readonly name="tyear" size="4" title="Year"
              value="<?php echo set_value('tyear', isset($default['year']) ? $default['year'] : ''); ?>">
         </td>
         </tr>
        
          <tr>
          <td> <label for="tpresence"> Presence </label> </td>  <td>:</td>
          <td> <input type="text" class="required" name="tpresence" id="tpresence" size="5" title="Presence" 
			   onKeyUp="checkdigit(this.value,'tpresence')" 
               value="<?php echo set_value('tpresence', isset($default['presence']) ? $default['presence'] : ''); ?>" /> 
          </td>
          </tr>
          
          <tr>
          <td> <label for="tlate"> Late </label> </td>  <td>:</td>
          <td> <input type="text" class="required" name="tlate" id="tlate" size="5" title="Late" 
               onKeyUp="checkdigit(this.value,'tlate')" value="<?php echo set_value('tlate', isset($default['late']) ? $default['late'] : ''); ?>" /> 
          </td>
          </tr>
          
          <tr>
          <td> <label for="tovertime"> Overtime </label> </td>  <td>:</td>
          <td> <input type="text" class="required" name="tovertime" id="tovertime" size="5" title="Overtime" 
               onKeyUp="checkdigit(this.value,'tovertime')" 
               value="<?php echo set_value('tovertime', isset($default['overtime']) ? $default['overtime'] : ''); ?>" /> 
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