<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'development-bundle/themes/base/ui.all.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/jquery.fancybox-1.3.4.css'; ?>");</style>

<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/datetimepicker_css.js"></script> 

<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";
</script>

<script type="text/javascript">
	
$(document).ready(function(){
		
	$('#ajaxform').submit(function() {
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function(data) {
			// $('#result').html(data);
			if (data == "true"){ location.reload(true);}
			else{ document.getElementById("errorbox").innerHTML = data; }
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

<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Graduation - Update </legend>
	    <form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
			
			<table>
			                
            <tr>
            <td> <label for="dept"> Department : </label> </td>  <td>:</td>
			<td> <input type="text" readonly="readonly" name="tdept" size="7" title="Department"
                 value="<?php echo set_value('tdept', isset($default['dept']) ? $default['dept'] : ''); ?>" /> </td> 	
            </tr>
            
            <tr>
            <td> <label for="dept"> Financial : </label> </td>  <td>:</td>
			<td> <input type="text" readonly="readonly" name="tfinance" size="10" title="Department"
                 value="<?php echo set_value('tdept', isset($default['financial_year']) ? $default['financial_year'] : ''); ?>" /> </td> 	
            </tr>
            
            <tr>
            <td> <label for="dept"> Generation : </label> </td>  <td>:</td>
			<td> <input type="text" readonly="readonly" name="tgeneration" size="4" title="Generation"
                 value="<?php echo set_value('tgeneration', isset($default['year']) ? $default['year'] : ''); ?>" /> </td> 	
            </tr>
                
            <tr>
                <td> <label for="tname"> Name </label> </td>  <td>:</td>
                <td> <input type="text" class="required" readonly="readonly" name="tname" size="25" title="Student"
                     value="<?php echo set_value('tname', isset($default['student']) ? $default['student'] : ''); ?>" />
                </td>
            </tr>
            
            <tr>
                <td> <label for="tdate"> Date </label> </td>  <td>:</td>
                <td> <input type="text" class="required" readonly="readonly" name="tdate" size="10" title="Date"
                     value="<?php echo set_value('tdate', isset($default['dates']) ? $default['dates'] : ''); ?>" /> &nbsp; <br /> </td>
            </tr>
                
            <tr>
                <td> <label for="tcertificate"> Certificate Code </label> </td>  <td>:</td>
                <td> <input type="text" class="required" name="tcertificate" size="25" title="Certificate"
                     value="<?php echo set_value('tcertificate', isset($default['certificate']) ? $default['certificate'] : ''); ?>" /> &nbsp; <br /> </td>
            </tr>  
            
             <tr>
                <td> <label for="ttaking"> Taking Date </label> </td>  <td>:</td>
                <td> <input type="text" class="required" id="d1" readonly="readonly" name="ttaking" size="10" title="Taking Date"
                     value="<?php echo set_value('ttaking', isset($default['takingdates']) ? $default['takingdates'] : ''); ?>" />
                     <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> &nbsp; <br /> 
                </td>
            </tr>  
            
            <tr>
                <td> <label for="tparent"> Parents / Guardians </label> </td>  <td>:</td>
                <td> <input type="text" class="required" name="tparent" size="25" title="Student Parent"
                     value="<?php echo set_value('tparent', isset($default['parent']) ? $default['parent'] : ''); ?>" /> &nbsp; <br /> </td>
            </tr>
                
                <tr>

			<td colspan="3"> <br /> <input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> <input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " /> </td>
			</tr> 
					
			</table>	
					
	    </form>			  
	</fieldset>
</div>

