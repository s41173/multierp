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
				if (data == "true"){ location.reload(true);}
				else{ document.getElementById("errorbox").innerHTML = data;}
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
	
	<fieldset class="field"> <legend> Add - Recapitulation </legend>
	    <form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
			
			<table>
            		
			<tr>
            <td> <label for="cperiod"> Period </label> </td>  <td>:</td>
            <td> <select name="cmonth">
            	 <option value="1"> January </option>
                 <option value="2"> February </option>
                 <option value="3"> March </option>
                 <option value="4"> April </option>
                 <option value="5"> May </option>
                 <option value="6"> June </option>
                 <option value="7"> July </option>
                 <option value="8"> August </option>
                 <option value="9"> September </option>
                 <option value="10"> October </option>
                 <option value="11"> November </option>
                 <option value="12"> December </option>
                 </select> - <input type="text" id="tyear" name="tyear" size="3" maxlength="4" value="<?php echo $year; ?>" />
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
                <td> <label for="tqty"> Qty </label> </td>  <td>:</td>
                <td> <input type="text" class="required" name="tqty" size="3" title="Students Qty"
                      value="<?php echo set_value('tqty', isset($default['qty']) ? $default['qty'] : ''); ?>" /> 
                </td>
            </tr>
            
            <tr>
                <td> <label for="tqty"> AR - Last Month </label> </td>  <td>:</td>
                <td> <input type="text" class="" readonly="readonly" name="tarlast" size="3" title="Qty AR - Last Month"
                      value="<?php echo set_value('tarlast', isset($default['arlast']) ? $default['arlast'] : ''); ?>" /> 
                </td>
            </tr>
            
            <tr>
                <td> <label for="tqty"> Must Pay </label> </td>  <td>:</td>
                <td> <input type="text" class="" readonly="readonly" name="tmust" size="3" title="Qty Must Pay This Month"
                      value="<?php echo set_value('tmust', isset($default['must']) ? $default['must'] : ''); ?>" /> 
                </td>
            </tr>
            
            <tr>
                <td> <label for="tqty"> Retur </label> </td>  <td>:</td>
                <td> <input type="text" class="" readonly="readonly" name="tretur" size="3" title="Qty - Retur"
                      value="<?php echo set_value('tretur', isset($default['retur']) ? $default['retur'] : ''); ?>" /> 
                </td>
            </tr>	
            
            <tr>
                <td> <label for="tqty"> AR - Month </label> </td>  <td>:</td>
                <td> <input type="text" class="" readonly="readonly" name="tar" size="3" title="Qty AR - Month"
                      value="<?php echo set_value('tar', isset($default['ar']) ? $default['ar'] : ''); ?>" /> 
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

