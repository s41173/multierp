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
	
	<fieldset class="field"> <legend> Student </legend>
    <table>
     
	<form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>" >
			      
             				
		<tr>
            <td> <label for="tname"> Name </label> </td>  <td>:</td>
            <td> <input type="text" class="required" name="tname" size="35" title="Name"
                  value="<?php echo set_value('tname', isset($default['name']) ? $default['name'] : ''); ?>" /> 
            </td>
        </tr>	
        
        <tr>
            <td> <label for="tnis"> NISN </label> </td>  <td>:</td>
            <td> <input type="text" class="required" name="tnis" size="15" title="NISN"
                  value="<?php echo set_value('tnis', isset($default['nis']) ? $default['nis'] : ''); ?>" /> 
            </td>
        </tr>	
        
        <tr> 
		<td> <label for="cdept"> Department </label> </td> <td>:</td>
        <td>  
        <?php $js = 'class="required" id="cdept"'; echo form_dropdown('cdept', $dept, isset($default['dept']) ? $default['dept'] : '', $js); ?>
        </td>
		</tr>   
        
        <tr> 
		<td> <label for="cfaculty"> Faculty </label> </td> <td>:</td>
        <td>  
        <?php $js = 'class="required"'; echo form_dropdown('cfaculty', $faculty, isset($default['faculty']) ? $default['faculty'] : '', $js); ?>
        </td>
		</tr>
        
        <tr> 
		<td> <label for="cgrade"> Grade </label> </td> <td>:</td>
        <td> 
        <?php $js = 'class="required"'; echo form_dropdown('cgrade', $grade, isset($default['grade']) ? $default['grade'] : '', $js); ?>
        </td>
		</tr>      
        
        <tr> 
		<td> <label for="cgenre"> Genre </label> </td> <td>:</td>
        <td> <select name="cgenre"> 
<option value="m"<?php echo set_select('cgenre', 'm', isset($default['genre']) && $default['genre'] == 'm' ? TRUE : FALSE); ?>> Male </option> <option value="f"<?php echo set_select('cgenre', 'f', isset($default['genre']) && $default['genre'] == 'f' ? TRUE : FALSE); ?>> Female </option> </select> </td>
		</tr>      
        		
                    
            </table>
            <p style="margin:15px 0 0 0; float:right;">
                <input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> 
                <input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " />
            </p>	
        </form>			  
	</fieldset>
</div>

