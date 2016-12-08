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
			if (data == "true"){ window.close(); }
			else{ document.getElementById("errorbox").innerHTML = data; }
			}
		})
		return false;
	});	

/* end document */		
});
	
</script>

<body onLoad="cek_session();" onUnload="window.opener.location.reload(true);">
<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Void Transaction </legend>
	    <form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
			
			<table>   
             
           <tr> 
           <td> <label for="tdesc"> User / Log </label> </td> <td>:</td> 
           <td> <input type="text" name="tuser" size="15" readonly value="<?php echo $user.' / '.$log; ?>"> </td> 
           </tr>  
           
           <tr> 
           <td> <label for="tdesc"> Date </label> </td> <td>:</td> 
           <td> <input type="text" name="tdate" size="10" readonly value="<?php echo $date; ?>"> </td> 
           </tr>   
                   
           <tr> 
           <td> <label for="tdesc"> Void Desc </label> </td> <td>:</td> 
           <td> <textarea class="required" name="tdesc" rows="4" cols="35"></textarea> </td> 
           </tr>  
                                          
           <tr> <td colspan="2"></td>
           <td>
             <input type="submit" name="submit" class="" title="Process Button" value="Submit" />
           </tr> 
					
			</table>	
					
	    </form>			  
	</fieldset>
</div>
</body>
