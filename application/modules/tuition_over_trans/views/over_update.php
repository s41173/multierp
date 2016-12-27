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
	
	$('#tstudentsearch').autocomplete({
		// serviceUrl berisi URL ke controller/fungsi yang menangani request kita
		serviceUrl: site+'/students/autocomplete',
		// fungsi ini akan dijalankan ketika user memilih salah satu hasil request
		onSelect: function (suggestion) {
			 //alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
			res = suggestion.data.split("|"); 
			document.getElementById("tdept").value = res[0];
			document.getElementById("tgrade").value = res[1]; 
			document.getElementById("tsid").value = res[4]; 
			get_fee_type(res[2],res[3]);
		}
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
	
	<fieldset class="field"> <legend> Mutation - Transaction </legend>
	<form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>" >
	 <table>
          
      <tr> <td> <label for="ctype"> Over Payment Type </label> </td> <td>:</td> 
      <td> <?php $js = 'id="ctype"'; echo form_dropdown('ctype', $type, isset($default['type']) ? $default['type'] : '', $js); ?> </td> </tr>     
                    
      <tr>	
      <td> <label for="tdate"> Date </label> </td> <td>:</td>
      <td> <input type="Text" name="tdate" id="d1" readonly title="Invoice date" size="10" class="required" 
           value="<?php echo set_value('tdate', isset($default['date']) ? $default['date'] : ''); ?>" /> 
           <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/>
      </td>
      </tr>
          
      <tr>
      <td> <label for="tstudent"> Student </label> </td>  <td>:</td>
      <td> <input type="text" id="tstudentsearch" readonly="readonly" name="tvalue" size="30" 
           value="<?php echo set_value('tvalue', isset($default['studentname']) ? $default['studentname'] : ''); ?>"  /> - 
           <input type="sid" id="tsid" name="tsid" readonly size="2"
           value="<?php echo set_value('tsid', isset($default['sid']) ? $default['sid'] : ''); ?>" /> </td>
      </tr>
      
      <tr>
      <td> <label for="tdept"> Dept / Grade </label> </td>  <td>:</td>
      <td> <input type="text" readonly id="tdept" name="tdept" size="6" 
           value="<?php echo set_value('tdept', isset($default['dept']) ? $default['dept'] : ''); ?>" /> - 
           <input type="text" readonly name="tgrade" id="tgrade" size="9"
           value="<?php echo set_value('tgrade', isset($default['grade']) ? $default['grade'] : ''); ?>" /> </td>
      </tr>
      
      <tr> 
      <td> <label for="cfee"> Fee Type </label> </td> <td>:</td>
      <td> <?php $js = 'id=""'; echo form_dropdown('cfee', $fee, isset($default['fee']) ? $default['fee'] : '', $js); ?>
      </tr>
					
      <tr>
      <td> <label for="tnote"> Note </label> </td>  <td>:</td>
  <td> <textarea name="tnote" cols="45" rows="3"><?php echo set_value('tnote', isset($default['note']) ? $default['note'] : ''); ?></textarea> 
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

