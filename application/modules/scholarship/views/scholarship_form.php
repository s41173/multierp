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
				{ location.reload(true); }
				else
				{   // alert(data);
					document.getElementById("errorbox").innerHTML = data;
				}
			}
		})
		return false;
	});	
	
		// get deptid and grade students
	
	$('#cdept,#cgrade').click(function() {
		
		var dept = $("#cdept").val();
		var grade = $("#cgrade").val();
		
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
<body onLoad="cek_session();" onUnload="window.opener.location.reload(true);">

<div id="webadmin">
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Scholarship - Transaction </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>" >
				<table>
          
      <tr>	
	  <td> <label for="tname"> Currency </label> </td> <td>:</td>
	  <td> <?php $js = 'id=""'; echo form_dropdown('ccurrency', $currency, isset($default['currency']) ? $default['currency'] : '', $js); ?></td>
	  </tr>  
      
      
       <tr> 
       <td> <label for="tvalue"> Department </label> </td> <td>:</td> 
       <td> <?php $js = 'id="cdept"'; echo form_dropdown('cdept', $dept, isset($default['dept']) ? $default['dept'] : '', $js); ?> -
          <select id="cgrade" name="cgrade"> 
          <option value="1">I</option> 
          <option value="2">II</option> 
          <option value="3">III</option> 
          </select>  
       </td>
       </tr> 
       
      <tr>	
	  <td> <label for="tname"> Name </label> </td> <td>:</td>
	  <td> <input type="text" class="required" name="tname" size="35"> </td>
	  </tr>   
      
                				 
      <tr> 
      <td> <label for="cfee"> Fee Type </label> </td> <td>:</td>
      <td> <div style="float:left; margin-right:10px;" id="feebox"> </div>
      </tr>
                    
      <tr>
      <td> <label for="tnote"> Period (Month) </label> </td>  <td>:</td>
      <td> <input type="text" class="required" name="tperiod" id="tperiod" size="2" title="Period" />
      </td>
      </tr>
      
      <tr>
      <td> <label for="tdesc"> Description </label> </td>  <td>:</td>
      <td> <textarea name="tdesc" cols="35" rows="3"></textarea>
      </td>
      </tr>
      			   
        </table>
        <p style="margin:15px 0 0 0; float:right;">
            <input type="submit" name="submit" class="button" title="" value=" Save " /> 
            <input type="reset" name="reset" class="button" title="" value=" Cancel " />
        </p>	
    </form>			  
	</fieldset>
</div>
</body>

