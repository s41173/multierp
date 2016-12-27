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
	
/* end document */		
});	

function calculate_aid()
{
	var p1 = parseFloat($("#treg").val());
	var p2 = parseFloat($("#tdev").val());
	var p3 = parseFloat($("#tschool").val());
	var p4 = parseFloat($("#tosis").val());
	var p5 = parseFloat($("#tcom").val());
	var p6 = parseFloat($("#tpractice").val());
	var p7 = parseFloat($("#tother").val());
	
    var p8 = parseFloat($("#taid").val());
	var res = p1+p2+p3+p4+p5+p6+p7;
	
	document.getElementById("tp1").value = res-p8;
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
	
	<fieldset class="field"> <legend> School Fee - Setting </legend>
	    <form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
			
			<table>
			           
   <tr>                 
   <td> <label for="tname"> Name </label> </td> <td>:</td> 
   <td> <input type="text" name="tname" size="25" value="<?php echo set_value('tname', isset($default['name']) ? $default['name'] : ''); ?>" /> </td> 
   </tr>    
                               
          	        <tr>                 
                    <td> <label for="tdesc"> Department </label> </td> <td>:</td> 
                    <td> <?php $js = 'class=""'; echo form_dropdown('cdept', $dept, isset($default['dept']) ? $default['dept'] : '', $js); ?> </td> 
					</tr> 
                    
                    <tr>                 
                    <td> <label for="cgrade"> Grade </label> </td> <td>:</td> 
                    <td> <?php $js = 'class="required"'; echo form_dropdown('cgrade', $level, isset($default['grade']) ? $default['grade'] : '', $js); ?>
                    </td> 
					</tr> 
                    
                    <tr>                 
                    <td> <label for="tdesc"> Registration </label> </td> <td>:</td> 
<td> <input type="text" id="treg" name="treg" size="10" title="Registration Costs" 
value="<?php echo set_value('treg', isset($default['reg']) ? $default['reg'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'treg'); calculate_aid()" /> </td> 
					</tr> 
                    
                    <tr>                 
                    <td> <label for="tdesc"> Development </label> </td> <td>:</td> 
<td> <input type="text" id="tdev" name="tdev" size="10" title="Development Costs" 
value="<?php echo set_value('tdev', isset($default['dev']) ? $default['dev'] : '0'); ?>" onKeyUp="checkdigit(this.value, 'tdev'); calculate_aid()" /> </td> 
					</tr> 
                    
                    <tr>                 
                    <td> <label for="tdesc"> School </label> </td> <td>:</td> 
<td> <input type="text" id="tschool" name="tschool" size="10" title="School Costs" 
value="<?php echo set_value('tschool', isset($default['school']) ? $default['school'] : '0'); ?>" onKeyUp="checkdigit(this.value,'tschool'); calculate_aid()" /> </td>
					</tr> 
                    
                    <tr>                 
                    <td> <label for="tdesc"> OSIS </label> </td> <td>:</td> 
<td> <input type="text" id="tosis" name="tosis" size="10" title="OSIS Costs" 
value="<?php echo set_value('tosis', isset($default['osis']) ? $default['osis'] : '0'); ?>" onKeyUp="checkdigit(this.value,'tosis'); calculate_aid()" /> </td>
					</tr> 
                    
                    <tr>                 
                    <td> <label for="tdesc"> Computer </label> </td> <td>:</td> 
<td> <input type="text" id="tcom" name="tcom" size="10" title="Computer Costs" 
value="<?php echo set_value('tcom', isset($default['com']) ? $default['com'] : '0'); ?>" onKeyUp="checkdigit(this.value,'tcom'); calculate_aid()" /> </td>
					</tr> 
                    
                    <tr>                 
                    <td> <label for="tdesc"> Practice </label> </td> <td>:</td> 
<td> <input type="text" id="tpractice" name="tpractice" size="10" title="Practice Costs" 
value="<?php echo set_value('tpractice', isset($default['practice']) ? $default['practice'] : '0'); ?>" onKeyUp="checkdigit(this.value,'tpractice'); calculate_aid()" /> </td>
					</tr> 
                    
                    <tr>                 
                    <td> <label for="tdesc"> Others </label> </td> <td>:</td> 
<td> <input type="text" id="tother" name="tother" size="10" title="Other Costs" 
value="<?php echo set_value('tother', isset($default['other']) ? $default['other'] : '0'); ?>" onKeyUp="checkdigit(this.value,'tother'); calculate_aid()" /> </td>
					</tr> 
                    
                     <tr>                 
                    <td> <label for="taid"> Foundation - Aid </label> </td> <td>:</td> 
<td> <input type="text" id="taid" name="taid" size="10" title="Aid" 
value="<?php echo set_value('taid', isset($default['aid']) ? $default['aid'] : '0'); ?>" onKeyUp="checkdigit(this.value,'taid'); calculate_aid()" /> </td>
					</tr> 
                    
                    <tr>                 
                    <td> <label for="tdesc"> P1 </label> </td> <td>:</td> 
<td> <input type="text" id="tp1" name="tp1" size="10" title="P1 Costs" 
value="<?php echo set_value('tp1', isset($default['p1']) ? $default['p1'] : '0'); ?>" onKeyUp="checkdigit(this.value,'tp1')" /> </td>
					</tr> 
                    
                    <tr>                 
                    <td> <label for="cdef"> Default </label> </td> <td>:</td> 
                    <td> <?php echo form_checkbox('cdef', '1', isset($default['def']) ? $default['def'] : '0'); ?> </td>
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

