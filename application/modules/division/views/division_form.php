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
	
	<fieldset class="field"> <legend> Add - Division </legend>
    <table>
     
	<form name="modul_form" id="ajaxform" class="myform" method="post" action="<?php echo $form_action; ?>">
			       
         <tr>
            <td> <label for="tname"> Division Name </label> </td>  <td>:</td>
            <td> <input type="text" class="required" name="tname" size="30" title="Division Name"
                 value="<?php echo set_value('tname', isset($default['name']) ? $default['name'] : ''); ?>" /> 
            </td>
        </tr>
        
         <tr> 
		<td> <label for="crole"> Role </label> </td> <td>:</td>
        <td> <select name="crole"> 
<option value="honor"<?php echo set_select('crole', 'honor', isset($default['role']) && $default['role'] == 'honor' ? TRUE : FALSE); ?>> Honor 
</option>
<option value="staff"<?php echo set_select('crole', 'staff', isset($default['role']) && $default['role'] == 'staff' ? TRUE : FALSE); ?>> Staff 
</option>
<option value="officer"<?php echo set_select('crole', 'officer', isset($default['role']) && $default['role'] == 'officer' ? TRUE : FALSE); ?>> Officer </option>
<option value="manager"<?php echo set_select('crole', 'manager', isset($default['role']) && $default['role'] == 'manager' ? TRUE : FALSE); ?>> 
Manager </option>
<option value="director"<?php echo set_select('crole', 'director', isset($default['role']) && $default['role'] == 'director' ? TRUE : FALSE); ?>> 
Director </option>
             </select> </td>
		</tr> 	
                     				
		<tr>
        <td> <label for="tbasic"> Basic Salary </label> </td>  <td>:</td>
        <td> <input type="text" class="required" name="tbasic" id="tbasic" size="15" title="Basic" onKeyUp="checkdigit(this.value,'tbasic')"
        value="<?php echo set_value('tbasic', isset($default['basic']) ? $default['basic'] : ''); ?>" /> 
        </td>
        </tr>	
        
      <tr>
      <td> <label for="tconsumption"> Comsuption / Meal </label> </td>  <td>:</td>
      <td> <input type="text" class="required" name="tconsumption" id="tconsumption" size="15" title="Comsuption" 
           onKeyUp="checkdigit(this.value,'tconsumption')" 
           value="<?php echo set_value('tconsumption', isset($default['consumption']) ? $default['consumption'] : ''); ?>" /> 
      </td>
      </tr>
        
        <tr>
        <td> <label for="ttransport"> Transportation </label> </td>  <td>:</td>
        <td> <input type="text" class="required" name="ttransport" id="ttransport" size="15" title="Transportation" 
             onKeyUp="checkdigit(this.value,'ttransport')"
             value="<?php echo set_value('ttransport', isset($default['transport']) ? $default['transport'] : ''); ?>" /> 
        </td>
        </tr>
        
        <tr>
        <td> <label for="tovertime"> Overtime </label> </td>  <td>:</td>
        <td> <input type="text" class="required" name="tovertime" id="tovertime" size="15" title="Overtime" 
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