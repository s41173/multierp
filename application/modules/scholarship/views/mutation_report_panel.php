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
			
	$('#cdept').change(function() {
	
	  var dept = $("#cdept").val();	
		
	   $.ajax({
		type: 'POST',
		url: uri +'get_grade',
		data: "dept="+ dept,
		success: function(data)
		{
		   document.getElementById("facultybox").innerHTML = data;
//		   window.location.reload(false);
		}
		})
		return false;
	   
	});
			
/* end document */		
});
	
</script>

<style>
        .refresh{ border:1px solid #AAAAAA; color:#000; padding:2px 5px 2px 5px; margin:0px 2px 0px 2px; background-color:#FFF;}
		.refresh:hover{ background-color:#CCCCCC; color: #FF0000;}
		.refresh:visited{ background-color:#FFF; color: #000000;}	
		#cdept{ float:left; margin-right:5px;}
</style>

<?php 
		
$atts1 = array(
	  'class'      => 'refresh',
	  'title'      => 'add cust',
	  'width'      => '600',
	  'height'     => '400',
	  'scrollbars' => 'no',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 400)/2)+\'',
);

?>

<?php $flashmessage = $this->session->flashdata('message'); ?>

<div id="webadmin">
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Terminantion - Report </legend>
	<form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>" target="_blank" >
				<table>
					
			<tr>	
			<td> <label for="tname"> Currency </label> </td> <td>:</td>
			<td> <?php $js = 'class="required"'; echo form_dropdown('ccurrency', $currency, isset($default['currency']) ? $default['currency'] : '', $js); ?> &nbsp; <br /> </td>
			</tr>
					
					<tr>	
						 <td> <label for="tstart"> Period </label> </td> <td>:</td>
						 <td>  
						   <input type="Text" name="tstart" id="d1" title="Start date" size="10" class="required" /> 
				           <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> &nbsp; - &nbsp;
						   
						   <input type="Text" name="tend" id="d2" title="End date" size="10" class="required" /> 
				           <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d2','yyyymmdd')" style="cursor:pointer"/> &nbsp; <br />
						</td> 						
					</tr>
                    
            <tr>	
			<td> <label for="cdept"> Department </label> </td> <td>:</td>
            <td> <?php $js = 'id="cdept"'; echo form_dropdown('cdept', $dept, isset($default['dept']) ? $default['dept'] : '', $js); ?>
            <div id="facultybox" style="float:left;"></div> </td>
			</tr>        
                    
            <tr> <td> <label for="cacc"> Account </label> </td> <td>:</td> <td>  
			<select name="cacc" class="required">
            <option value="" selected="selected" /> -- All -- </option>
            <option value="bank" /> Bank </option>
            <option value="cash" /> Cash </option>
            <option value="pettycash" /> Petty Cash </option>
			</select>
			<br />  </td> </tr>        
					
			
			<tr>	
			<td> <label for="ccategory"> Type </label> </td> <td>:</td>
            <td> <?php $js = 'class="required"'; echo form_dropdown('ctype', $type, isset($default['type']) ? $default['type'] : '', $js); ?> </td>
			</tr>
            
             <td> <label for=""> Payment : </label> </td> <td>:</td>
             <td> <select name="cstts"> 
                  <option value=""> -- </option> 
                  <option value="1"> Settled </option> 
                  <option value="0"> Credit </option> 
                  </select> &nbsp;
             </td> 
            
           	<tr>	
			<td> <label for="cformat"> Format </label> </td> <td>:</td>
			<td>  
			 <select name="cformat">
				<option selected="selected" value="0"> HTML </option>
				<option value="1"> Pdf </option>
			 </select> &nbsp; <br /> </td>
			</tr>
						
					   
				</table>
				<p style="margin:15px 0 0 0; float:left;">
					<input type="submit" name="submit" class="button" title="SUBMIT" value=" SUBMIT " /> 
				</p>	
			</form>			  
	</fieldset>
</div>

