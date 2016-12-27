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
		url: uri +'get_faculty',
		data: $(this).serialize(),
		success: function(data)
		{
		   document.getElementById("facultybox").innerHTML = data;
		}
		})
		return false;
	});
			
	$('#cdept').change(function() {

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
			
	$('#bget').click(function() {

	   var dept = $("#cdept").val();
	   var faculty = $("#cfacultys").val();
	   
	   $.ajax({
		type: 'POST',
		url: uri +'get_grade',
		data: "dept="+ dept + "&faculty=" + faculty,
		success: function(data)
		{
		   document.getElementById("gradebox").innerHTML = data;
		}
		})
		return false;
	   
	});
	
/* end document */		
});
	
</script>

<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Add - Student </legend>
	    <form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
			
			<table>
            		
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
            <div id="facultybox" style="float:left; margin-right:5px;"></div>
            <input type="button" id="bget" value="GET" />
            </td>
            </tr>
            
            <tr> 
            <td> <label for="cgrade"> Grade </label> </td> <td>:</td>
            <td> 
            <div id="gradebox"></div> 
            </td>
            </tr>      
        
            <tr> 
            <td> <label for="cgenre"> Genre </label> </td> <td>:</td>
            <td> <select name="cgenre"> 
    <option value="m"<?php echo set_select('cgenre', 'm', isset($default['genre']) && $default['genre'] == 'm' ? TRUE : FALSE); ?>> Male </option> <option value="f"<?php echo set_select('cgenre', 'f', isset($default['genre']) && $default['genre'] == 'f' ? TRUE : FALSE); ?>> Female </option> </select> </td>
            </tr> 
            
             <tr> 					
             <td> <label for=""> Join Date : </label> </td> <td>:</td>
             <td>
                  <input type="Text" name="tdate" id="d1" title="Start date" size="10" class="form_field" value="<?php echo isset($dates) ? $dates : ''; ?>" /> 
                  <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/>
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

