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
<script type="text/javascript" src="<?php echo base_url();?> js/complete.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/sortir.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/validate.js"></script> 
<script type='text/javascript' src='<?php echo base_url();?>js/jquery.validate.js'></script>  

<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";
var site = "<?php echo site_url();?>";

$(document).ready(function(){
			
	$('#cdeptfact').change(function() {

	   $.ajax({
		type: 'POST',
		url: uri +'get_faculty_id/all',
		data: $(this).serialize(),
		success: function(data)
		{
		   document.getElementById("facultybox").innerHTML = data;
//		   window.location.reload(false);
		}
		})
		return false;
	   
	});
	
	$('#cdeptfact, #clevel').change(function() {

	   var dept = $("#cdeptfact").val();
	   var grade = $("#clevel").val();	
	
	   $.ajax({
		type: 'POST',
		url: uri +'get_fee_type',
		data: "cdept="+ dept + "&cgrade=" + grade,
		success: function(data)
		{
		   document.getElementById("feebox").innerHTML = data;
		}
		})
		return false;
	   
	});
			
	
/* end document */		
});

</script>

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

<body onUnload="window.opener.location.reload(true);">

<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Report - Grade </legend>
	    <form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>" target="_blank">
			
			<table>
			                                          
          	        <tr>                 
         <td> <label for="tdesc"> Department </label> </td> <td>:</td> 
         <td> <?php $js = 'id="cdeptfact"'; echo form_dropdown('cdept', $dept, isset($default['dept']) ? $default['dept'] : '', $js); ?> </td> 
					</tr> 
                    
                    <tr>                 
                    <td> <label for="cgrade"> Level </label> </td> <td>:</td> 
                    <td> 
                    	<select name="clevel" id="clevel" class="">
                        <option value="" selected> -- Select -- </option>
 <option value="1" <?php echo set_select('clevel', '1', isset($default['level']) && $default['level'] == '1' ? TRUE : FALSE); ?> /> I </option>
 <option value="2" <?php echo set_select('clevel', '2', isset($default['level']) && $default['level'] == '2' ? TRUE : FALSE); ?> /> II </option>
 <option value="3" <?php echo set_select('clevel', '3', isset($default['level']) && $default['level'] == '3' ? TRUE : FALSE); ?> /> III </option>
                        </select>
                    </td> 
					</tr> 
                    
                    <tr>                 
                    <td> <label for="cfaculty"> Faculty </label> </td> <td>:</td> 
                    <td> <div style=" float:left; margin-right:5px;" id="facultybox"></div> </td>   
					</tr> 
                    
                    <tr>                 
                    <td> <label for="cfee"> Fee Type </label> </td> <td>:</td> 
                    <td> <div style="float:left;" id="feebox"></div> </td> 
                    </tr> 
                                   
            <tr>
			<td colspan="3"> <br /> 
             <input type="submit" name="submit" class="button" title="Process Button" value="Search" />
             <input type="reset" name="reset" class="button" title="Reset Button" value=" Cancel " /> </td>
			</tr> 
					
			</table>	
					
	    </form>			  
	</fieldset>
</div>
</body>

