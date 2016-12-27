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
<body onLoad="cek_session();" onUnload="window.opener.location.reload(false);">

<div id="webadmin">
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Add - Employee </legend>
    <table>
     
	<form name="modul_form" id="form" class="myform" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data">
			      
        <tr>
        	<td> <label for="csection"> Section </label> </td>  <td>:</td>
            <td> <select name="csection"> 
            <option value="1"<?php echo set_select('csection', '1', isset($default['section']) && $default['section'] == '1' ? TRUE : FALSE); ?>> 
              Academic 
            </option> 
            <option value="0"<?php echo set_select('csection', '0', isset($default['section']) && $default['section'] == '0' ? TRUE : FALSE); ?>>
             Non Academic </option> 
                 </select> </td>
        </tr> 
        
        <tr> 
		<td> <label for="cdept"> Department </label> </td> <td>:</td>
        <td>  
        <?php $js = 'class="required"'; echo form_dropdown('cdept', $dept, isset($default['dept']) ? $default['dept'] : '', $js); ?>
        </td>
		</tr>   
        
         <tr>
            <td> <label for="tnip"> NIP </label> </td>  <td>:</td>
            <td> <input type="text" readonly class="required" name="tnip" size="15" title="NIP" 
                 value="<?php echo set_value('tnip', isset($default['nip']) ? $default['nip'] : ''); ?>" /> 
            </td>
        </tr>	
        
        <tr>
            <td> <label for="tname"> Name </label> </td>  <td>:</td>
            <td> <input type="text" class="required" name="tname" size="35" title="Name"
                 value="<?php echo set_value('tname', isset($default['name']) ? $default['name'] : ''); ?>" /> 
            </td>
        </tr>	
             				
		<tr>
            <td> <label for="tfirst"> First Title </label> </td>  <td>:</td>
            <td> <input type="text" class="" name="tfirst" size="15" title="First Title"
                 value="<?php echo set_value('tfirst', isset($default['first']) ? $default['first'] : ''); ?>" /> 
            </td>
        </tr>	
        
        <tr>
            <td> <label for="tend"> End Title </label> </td>  <td>:</td>
            <td> <input type="text" class="" name="tend" size="15" title="End Title"
                 value="<?php echo set_value('tend', isset($default['end']) ? $default['end'] : ''); ?>" /> 
            </td>
        </tr>
        
        <tr>
            <td> <label for="tnickname"> Nick Name </label> </td>  <td>:</td>
            <td> <input type="text" class="" name="tnickname" size="25" title="Nick Name"
                 value="<?php echo set_value('tnickname', isset($default['nickname']) ? $default['nickname'] : ''); ?>" /> 
            </td>
        </tr>	
         
        <tr> 
		<td> <label for="cgenre"> Genre </label> </td> <td>:</td>
        <td> <select name="cgenre"> 
<option value="m"<?php echo set_select('cgenre', 'm', isset($default['genre']) && $default['genre'] == 'm' ? TRUE : FALSE); ?>> Male </option> <option value="f"<?php echo set_select('cgenre', 'f', isset($default['genre']) && $default['genre'] == 'f' ? TRUE : FALSE); ?>> Female </option> </select> </td>
		</tr>  
        
        <tr>
            <td> <label for="tdob"> Date Of Birth </label> </td>  <td>:</td>
            <td> <input type="text" class="" name="tbornplace" size="15" title="Born Place"
                 value="<?php echo set_value('tbornplace', isset($default['bornplace']) ? $default['bornplace'] : ''); ?>" /> - 
                 <input type="Text" name="tborndate" id="d1" title="Born date" size="10" class="form_field"
                 value="<?php echo set_value('tborndate', isset($default['borndate']) ? $default['borndate'] : ''); ?>" /> 
                 <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/>
            </td>
        </tr>	
        
        <tr>
        	<td> <label for="creligion"> Religion </label> </td> <td>:</td>
            <td> <select name="creligion" class="required"> 
<option value="moeslim"
<?php echo set_select('creligion', 'moeslim', isset($default['religion']) && $default['religion'] == 'moeslim' ? TRUE : FALSE); ?>> 
Moeslim 
</option> 

<option value="christian"
<?php echo set_select('creligion', 'christian', isset($default['religion']) && $default['religion'] == 'christian' ? TRUE : FALSE); ?>> 
Christian </option> 

<option value="catholic"<?php echo set_select('creligion', 'catholic', isset($default['religion']) && $default['religion'] == 'catholic' ? TRUE : FALSE); ?> > Catholic </option> 

<option value="hindu"<?php echo set_select('creligion', 'hindu', isset($default['religion']) && $default['religion'] == 'hindu' ? TRUE : FALSE); ?>> Hindu </option> 

<option value="buddha"<?php echo set_select('creligion', 'buddha', isset($default['religion']) && $default['religion'] == 'buddha' ? TRUE : FALSE); ?>> Buddha </option> 

<option value="others"<?php echo set_select('creligion', 'others', isset($default['religion']) && $default['religion'] == 'others' ? TRUE : FALSE); ?>> Others </option> 
                 </select> 
            </td>
        </tr> 
        
        <tr>
        	<td> <label for="tethnic"> Ethnic </label> </td> <td>:</td>
            <td> <input type="text" name="tethnic" size="15" title="Ethnic"
                 value="<?php echo set_value('tethnic', isset($default['ethnic']) ? $default['ethnic'] : ''); ?>" /> </td>
        </tr>  
        
        <tr>
        	<td> <label for="rmarried"> Marital status </label> </td> <td>:</td>
            <td> <input type="radio" class="required" name="rmarried" value="yes" 
			     <?php echo set_radio('rmarried', 'yes', isset($default['married']) && $default['married'] == 'yes' ? TRUE : FALSE); ?> /> Yes  
                 <input type="radio" name="rmarried" value="no" 
				 <?php echo set_radio('rmarried', 'no', isset($default['married']) && $default['married'] == 'no' ? TRUE : FALSE); ?> /> No
                 <input type="radio" class="required" name="rmarried" value="" 
				 <?php echo set_radio('rmarried', '', isset($default['married']) && $default['married'] == '' ? TRUE : FALSE); ?> /> (No Data)
            </td>
        </tr> 
        
        <tr>
        	<td> <label for="tidno"> ID - No </label> </td> <td>:</td>
            <td> <input type="text" name="tidno" size="20" title="ID - No"
                 value="<?php echo set_value('tidno', isset($default['idno']) ? $default['idno'] : ''); ?>" /> </td>
        </tr> 
        
        <tr>
        	<td> <label for="taddress"> Address </label> </td> <td>:</td>
            <td> <textarea name="taddress" cols="40" rows="3" class="required"><?php echo set_value('taddress', isset($default['address']) ? $default['address'] : ''); ?></textarea> </td>
        </tr>   
        
        <tr>
        	<td> <label for="tphone"> Phone </label> </td> <td>:</td>
            <td> <input type="text" class="required" name="tphone" size="15" title="Phone"
                 value="<?php echo set_value('tphone', isset($default['phone']) ? $default['phone'] : ''); ?>" /> </td>
        </tr> 
        
        <tr>
        	<td> <label for="tmobile"> Mobile </label> </td> <td>:</td>
            <td> <input type="text" class="required" name="tmobile" size="15" title="Mobile"
                 value="<?php echo set_value('tmobile', isset($default['mobile']) ? $default['mobile'] : ''); ?>" /> </td>
        </tr> 
        
        <tr>
        	<td> <label for="temail"> Email </label> </td> <td>:</td>
            <td> <input type="text" class="" name="temail" size="35" title="Email"
                 value="<?php echo set_value('temail', isset($default['email']) ? $default['email'] : ''); ?>" /> </td>
        </tr> 
        
        <tr>
        	<td> <label for="timage"> Image </label> </td> <td>:</td>
            <td> <img width="150" alt="<?php echo $default['nip']; ?>" src="<?php echo $default['image']; ?>"> </td>
        </tr>
        
        <tr>
        	<td> <label for="timage"> Change Image </label> </td> <td>:</td>
            <td> <input type="file" name="userfile" size="30" title="Image" /> <small> (*) max size 150kb </small> </td>
        </tr> 
        
        <tr>
        	<td> <label for="tdesc"> Description </label> </td> <td>:</td>
            <td> <textarea name="tdesc" cols="40" rows="2" class="required"><?php echo set_value('tdesc', isset($default['desc']) ? $default['desc'] : ''); ?></textarea> </td>
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