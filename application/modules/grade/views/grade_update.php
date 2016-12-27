<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'development-bundle/themes/base/ui.all.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/jquery.fancybox-1.3.4.css'; ?>");</style>

<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.autocomplete.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/register.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/datetimepicker_css.js"></script>


<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";
var site = "<?php echo site_url();?>";
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
				if (data == "true")
				{
					location.reload(true);
				}
				else
				{
					// alert(data);
					document.getElementById("errorbox").innerHTML = data;
				}
				
			}
		})
		return false;
	});	
			
	$('#cdeptfact').change(function() {

	   $.ajax({
		type: 'POST',
		url: uri +'get_faculty_id',
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

function open_window()
{
	var type = 'honor';
	if (type != "")
	{
		if (type == 'salary'){ var urlemployee = (site+'/employees/get_list/non'); }
		else if (type == 'honor') { var urlemployee = (site+'/employees/get_list/academic/name'); }
    	window.open(urlemployee,'Employee','height=400,width=600,scrollbars=yes,resizable=yes');
    }
	else { alert("Select Employee Type First....!!"); }
	
}

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
	
	<fieldset class="field"> <legend> Update - Grade </legend>
	    <form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
			
			<table>
			                                          
          	        <tr>                 
         <td> <label for="tdesc"> Department </label> </td> <td>:</td> 
         <td> <input type="text" readonly name="tdept" size="10" title="Department" 
             value="<?php echo set_value('tdept', isset($default['dept']) ? $default['dept'] : ''); ?>" /> </td> 
					</tr> 
                    
                    <tr>                 
                    <td> <label for="cgrade"> Level </label> </td> <td>:</td> 
                    <td> <?php $js = 'class="required", id="clevel"'; echo form_dropdown('clevel', $level, isset($default['level']) ? $default['level'] : '', $js); ?>
                    </td> 
					</tr>  
                    
                    <tr>                 
                    <td> <label for="cgrade"> Group </label> </td> <td>:</td> 
                    <td> 
                    	 <select name="cgrade" id="cgrade" class="required" style="float:left;"> 
<option value="I" <?php echo set_select('cgrade', 'I', isset($default['grade']) && $default['grade'] == 'I' ? TRUE : FALSE); ?> /> I </option> 
<option value="II" <?php echo set_select('cgrade', 'II', isset($default['grade']) && $default['grade'] == 'II' ? TRUE : FALSE); ?> /> II </option> 
<option value="III"<?php echo set_select('cgrade', 'III', isset($default['grade']) && $default['grade'] == 'III' ? TRUE : FALSE); ?> /> III </option><option value="IV" <?php echo set_select('cgrade', 'IV', isset($default['grade']) && $default['grade'] == 'IV' ? TRUE : FALSE); ?> /> IV </option> 
<option value="V" <?php echo set_select('cgrade', 'V', isset($default['grade']) && $default['grade'] == 'V' ? TRUE : FALSE); ?> /> V </option>  
<option value="VI" <?php echo set_select('cgrade', 'VI', isset($default['grade']) && $default['grade'] == 'VI' ? TRUE : FALSE); ?> /> VI </option> 
<option value="VII"<?php echo set_select('cgrade', 'VII', isset($default['grade']) && $default['grade'] == 'VII' ? TRUE : FALSE); ?> /> VII </option><option value="VIII" <?php echo set_select('cgrade', 'VIII', isset($default['grade']) && $default['grade'] == 'VIII' ? TRUE : FALSE); ?> /> VIII
</option> 
<option value="IX" <?php echo set_select('cgrade', 'IX', isset($default['grade']) && $default['grade'] == 'IX' ? TRUE : FALSE); ?> /> IX </option>  
<option value="X" <?php echo set_select('cgrade', 'X', isset($default['grade']) && $default['grade'] == 'X' ? TRUE : FALSE); ?> /> X </option> 
<option value="XI"<?php echo set_select('cgrade', 'XI', isset($default['grade']) && $default['grade'] == 'XI' ? TRUE : FALSE); ?> /> XI </option> 
<option value="XII"<?php echo set_select('cgrade', 'XII', isset($default['grade']) && $default['grade'] == 'XII' ? TRUE : FALSE); ?> /> XII </option>
                         </select> - 
<?php $js = 'id="cfaculty"'; echo form_dropdown('cfaculty', $faculty, isset($default['faculty']) ? $default['faculty'] : '', $js); ?> -
<input style="margin-left:5px;" type="text" name="tno" size="5" required value="<?php echo set_value('tno', isset($default['no']) ? $default['no'] : '0'); ?>">  </td>   
					</tr> 
                    
        <tr>                 
        <td> <label for="cfee"> Fee Type </label> </td> <td>:</td> 
		<td> <?php $js = 'id="cfee"'; echo form_dropdown('cfee', $fee, isset($default['fee']) ? $default['fee'] : '', $js); ?> </td> 
		</tr> 
                    
        <tr>                 
        <td> <label for="cstts"> Practice </label> </td> <td>:</td> 
        <td> <select name="cstts"> 
     <option value="1" <?php echo set_select('cstts', '1', isset($default['stts']) && $default['stts'] == '1' ? TRUE : FALSE); ?> /> Y </option>
     <option value="0" <?php echo set_select('cstts', '0', isset($default['stts']) && $default['stts'] == '0' ? TRUE : FALSE); ?> /> N </option>
             </select> 
        </td> 
        </tr> 
        
        <tr>                 
        <td> <label for="tinstructor"> Instructor </label> </td> <td>:</td> 
        <td> <input type="text" readonly name="tinstructor" id="tsearch" size="20" title="Name" 
             value="<?php echo set_value('tinstructor', isset($default['instructor']) ? $default['instructor'] : ''); ?>" />
             <input type="button" value="[ ... ]" onClick="open_window();" /> 
        </td> 
        </tr> 
        
        <tr>                 
        <td> <label for="tcapacity"> Capacity </label> </td> <td>:</td> 
        <td> <input type="text" name="tcapacity" id="tcapacity" size="3" title="Capacity" onKeyUp="checkdigit(this.value,'tcapacity');"
             value="<?php echo set_value('tcapacity', isset($default['capacity']) ? $default['capacity'] : ''); ?>" /> </td> 
        </tr> 
        
        <tr>                 
        <td> <label for="tdesc"> Description </label> </td> <td>:</td> 
        <td> <textarea name="tdesc" rows="3"><?php echo set_value('tdesc', isset($default['desc']) ? $default['desc'] : ''); ?></textarea>
        </td> 
        </tr> 
                      
            <tr>
			<td colspan="3"> <br /> 
             <input type="submit" name="submit" class="button" title="Process Button" value=" Save " />
             <input type="reset" name="reset" class="button" title="Reset Button" value=" Cancel " /> </td>
			</tr> 
					
			</table>	
					
	    </form>			  
	</fieldset>
</div>
</body>

