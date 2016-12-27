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
         <td> <input type="text" readonly name="tname" size="25" title="Name"
              value="<?php echo set_value('tname', isset($default['employee']) ? $default['employee'] : ''); ?>" /> - 
              <input type="text" readonly name="tnip" size="5" title="Name"
              value="<?php echo set_value('tnip', isset($default['nip']) ? $default['nip'] : ''); ?>" />
         </td>
         </tr>
                     				
		 <tr>
         <td> <label for="cdept"> Department </label> </td>  <td>:</td>
         <td> <?php $js = 'class="required"'; echo form_dropdown('cdept', $dept, isset($default['dept']) ? $default['dept'] : '', $js); ?>
         </td>
         </tr>	
        
          <tr>
          <td> <label for="thour"> Worked Hours </label> </td>  <td>:</td>
          <td> <input type="text" class="required" name="thour" id="thour" size="5" title="Hours" 
               onKeyUp="checkdigit(this.value,'thour')" value="<?php echo set_value('thour', isset($default['hour']) ? $default['hour'] : ''); ?>" /> 
          </td>
          </tr>
          
          <tr> 
		  <td> <label for="ctime"> Time Work </label> </td> <td>:</td>
          <td> <select name="ctime"> 
   <option value="0"<?php echo set_select('ctime', '0', isset($default['time']) && $default['time'] == '0' ? TRUE : FALSE); ?>> 0 </option>
   <option value="1-5"<?php echo set_select('ctime', '1-5', isset($default['time']) && $default['time'] == '1-5' ? TRUE : FALSE); ?>> 1-5 </option>
   <option value=">5"<?php echo set_select('ctime', '>5', isset($default['time']) && $default['time'] == '>5' ? TRUE : FALSE); ?>> >5 </option>
             </select> </td>
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