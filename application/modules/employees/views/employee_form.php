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
<script type="text/javascript" src="<?php echo base_url();?>js/complete.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/sortir.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/validate.js"></script> 
<script type='text/javascript' src='<?php echo base_url();?>js/jquery.validate.js'></script>  

<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";

function cek_session()
{
	$(document).ready(function(){
		$.ajax({
			type: 'POST',
			url: uri +'cek_session',
			data: $(this).serialize(),
			success: function(data){ if (data == 'FALSE'){ window.close(); } }
		})
		return false;	
	}); 
}



</script>

<style>
        .refresh{ border:1px solid #AAAAAA; color:#000; padding:2px 5px 2px 5px; margin:0px 2px 0px 2px; background-color:#FFF;}
		.refresh:hover{ background-color:#CCCCCC; color: #FF0000;}
		.refresh:visited{ background-color:#FFF; color: #000000;}	
</style>

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
	
	<fieldset class="field"> <legend> Add - Employee </legend>
    <table>
     
	<form name="modul_form" id="form" class="myform" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data">
			      
        <tr>
        	<td> <label for="csection"> Section </label> </td>  <td>:</td>
            <td> <select name="csection"> <option value="academic"> Academic </option> <option value="non"> Non Academic </option> </select> </td>
        </tr> 
        
        <tr>
        <td> <label for="cdivision"> Division </label> </td>  <td>:</td>
        <td> <?php echo form_dropdown('cdivision', $division, isset($default['division']) ? $default['division'] : ''); ?> </td>
        </tr> 
        
        <tr>
         <td> <label for="crole"> Role </label> </td> <td>:</td>
         <td> <select class="required" name="crole">
                <option value="honor"> Honor </option>
                <option value="staff"> Staff </option>
                <option value="officer"> Officer </option>
                <option value="manager"> Manager </option>
                <option value="director"> Director </option>
              </select> &nbsp;
          </td>
         </tr>
        
        <tr>
         <td> <label for="tname"> Time Work </label> </td> <td>:</td>
         <td> <select class="required" name="ctime">
                <option value="0"> 0 </option>
                <option value="1-5"> 1-5 </option>
                <option value=">5"> >5 </option>
              </select> &nbsp;
          </td>
         </tr>
        
        <tr> 
		<td> <label for="cdept"> Department </label> </td> <td>:</td>
        <td>  
        <?php $js = 'class="required"'; echo form_dropdown('cdept', $dept, isset($default['dept']) ? $default['dept'] : '', $js); ?>
        </td>
		</tr>   
        
         <tr>
            <td> <label for="tnip"> NIP </label> </td>  <td>:</td>
            <td> <input type="text" class="required" name="tnip" size="15" title="NIP" /> 
            </td>
        </tr>	
        
         <tr>
            <td> <label for="tatt"> Attendance Code </label> </td>  <td>:</td>
            <td> <input type="text" class="required" name="tatt" size="15" title="Att Code" /> 
            </td>
        </tr>	
        
        <tr>
            <td> <label for="tname"> Name </label> </td>  <td>:</td>
            <td> <input type="text" class="required" name="tname" size="35" title="Name" /> 
            </td>
        </tr>	
             				
		<tr>
            <td> <label for="tfirst"> First Title </label> </td>  <td>:</td>
            <td> <input type="text" class="" name="tfirst" size="15" title="First Title" /> 
            </td>
        </tr>	
        
        <tr>
            <td> <label for="tend"> End Title </label> </td>  <td>:</td>
            <td> <input type="text" class="" name="tend" size="15" title="End Title" /> 
            </td>
        </tr>
        
        <tr>
            <td> <label for="tnickname"> Nick Name </label> </td>  <td>:</td>
            <td> <input type="text" class="" name="tnickname" size="25" title="Nick Name" /> 
            </td>
        </tr>	
         
        <tr> 
		<td> <label for="cgenre"> Genre </label> </td> <td>:</td>
        <td> <select name="cgenre"> 
<option value="m"<?php echo set_select('cgenre', 'm', isset($default['genre']) && $default['genre'] == 'm' ? TRUE : FALSE); ?>> Male </option> <option value="f"<?php echo set_select('cgenre', 'f', isset($default['genre']) && $default['genre'] == 'f' ? TRUE : FALSE); ?>> Female </option> </select> </td>
		</tr>  
        
        <tr>
            <td> <label for="tdob"> Date Of Birth </label> </td>  <td>:</td>
            <td> <input type="text" class="" name="tbornplace" size="15" title="Born Place" /> - 
                 <input type="Text" name="tborndate" id="d1" title="Born date" size="10" class="form_field" /> 
                 <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/>
            </td>
        </tr>	
        
        <tr>
        	<td> <label for="creligion"> Religion </label> </td> <td>:</td>
            <td> <select name="creligion" class="required"> 
                 <option value="moeslim"> Moeslim </option> 
                 <option value="christian"> Christian </option> 
                 <option value="catholic"> Catholic </option> 
                 <option value="hindu"> Hindu </option> 
                 <option value="buddha"> Buddha </option> 
                 <option value="others"> Others </option> 
                 </select> 
            </td>
        </tr> 
        
        <tr>
        	<td> <label for="tethnic"> Ethnic </label> </td> <td>:</td>
            <td> <input type="text" name="tethnic" size="15" title="Ethnic" /> </td>
        </tr>  
        
        <tr>
        	<td> <label for="rmarried"> Marital status </label> </td> <td>:</td>
            <td> <input type="radio" class="required" name="rmarried" value="yes" /> Yes  <input type="radio" name="rmarried" value="no" /> No
                 <input type="radio" class="required" name="rmarried" value="" /> ( No Data )
            </td>
        </tr> 
        
        <tr>
        	<td> <label for="tidno"> ID - No </label> </td> <td>:</td>
            <td> <input type="text" name="tidno" size="20" title="ID - No" /> </td>
        </tr> 
        
        <tr>
        	<td> <label for="taddress"> Address </label> </td> <td>:</td>
            <td> <textarea name="taddress" cols="40" rows="3" class="required"></textarea> </td>
        </tr>   
        
        <tr>
        	<td> <label for="tphone"> Phone </label> </td> <td>:</td>
            <td> <input type="text" class="required" name="tphone" size="15" title="Phone" /> </td>
        </tr> 
        
        <tr>
        	<td> <label for="tmobile"> Mobile </label> </td> <td>:</td>
            <td> <input type="text" class="required" name="tmobile" size="15" title="Mobile" /> </td>
        </tr> 
        
        <tr>
        	<td> <label for="temail"> Email </label> </td> <td>:</td>
            <td> <input type="text" class="" name="temail" size="35" title="Email" /> </td>
        </tr> 
        
        <tr>
        	<td> <label for="timage"> Image </label> </td> <td>:</td>
            <td> <small> (*) max size 150kb </small> <br /> <input type="file" name="userfile" size="30" title="Image" /> </td>
        </tr> 
        
        <tr>
        	<td> <label for="taccname"> Account Name </label> </td> <td>:</td>
            <td> <input type="text" class="" name="taccname" size="30" title="Account Name" /> </td>
        </tr>
        
        <tr>
        	<td> <label for="taccno"> Account - No </label> </td> <td>:</td>
            <td> <input type="text" class="" name="taccno" size="20" title="Account No" /> </td>
        </tr>
        
        <tr>
        	<td> <label for="tbank"> Bank </label> </td> <td>:</td>
            <td> <textarea name="tbank" cols="35" rows="3"></textarea> </td>
        </tr>
        
        <tr>
        	<td> <label for="tdesc"> Description </label> </td> <td>:</td>
            <td> <textarea name="tdesc" cols="40" rows="2" class="required"></textarea> </td>
        </tr>  
        		
                    
            </table>
            <p style="margin:15px 0 0 0; float:right;">
                <input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> 
                <input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " />
            </p>	
        </form>			  
	</fieldset>
</div>

</body>