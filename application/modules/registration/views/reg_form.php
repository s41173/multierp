<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'development-bundle/themes/base/ui.all.css'; ?>");</style>


<script type="text/javascript" src="<?php echo base_url();?>js/register.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/datetimepicker_css.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/development-bundle/ui/ui.core.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/hoverIntent.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/complete.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.maskedinput-1.3.min.js"></script>
<script type='text/javascript' src='<?php echo base_url();?>js/jquery.autocomplete.js'></script>
<script type="text/javascript" src="<?php echo base_url();?>js/validate.js"></script> 
<script type='text/javascript' src='<?php echo base_url();?>js/jquery.validate.js'></script>   


<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";
var site = "<?php echo site_url();?>";

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

function enb()
{
	if (document.getElementById("ck").checked == true)
	{
		document.getElementById("tregfee").readOnly = false;
		document.getElementById("tdevfee").readOnly = false;
		document.getElementById("tschoolfee").readOnly = false;
		document.getElementById("tosisfee").readOnly = false;
		document.getElementById("tpracticefee").readOnly = false;
		document.getElementById("totherfee").readOnly = false;
	} 
	else 
	{
		document.getElementById("tregfee").readOnly = true;
		document.getElementById("tdevfee").readOnly = true;
		document.getElementById("tschoolfee").readOnly = true;
		document.getElementById("tosisfee").readOnly = true;
		document.getElementById("tpracticefee").readOnly = true;
		document.getElementById("totherfee").readOnly = true;
	}
}

function enb_uni()
{
	if (document.getElementById("ckadd").checked == true)
	{		
		document.getElementById("tunischool").readOnly = false;
        document.getElementById("tunipractice").readOnly = false;
        document.getElementById("tscout").readOnly = false;
	} 
	else 
	{
		document.getElementById("tunischool").readOnly = true;
        document.getElementById("tunipractice").readOnly = true;
        document.getElementById("tscout").readOnly = true;
	}
}

function resets()
{
	document.getElementById("tregfee").value = 0;
    document.getElementById("tdevfee").value = 0;
    document.getElementById("tschoolfee").value = 0;
    document.getElementById("tosisfee").value = 0;
    document.getElementById("tpracticefee").value = 0;
    document.getElementById("totherfee").value = 0;
	
	document.getElementById("tunischool").value = 0;
    document.getElementById("tunipractice").value = 0;
    document.getElementById("tscout").value = 0;
    document.getElementById("tadd").value = 0;
	document.getElementById("tunitotal").value = 0;
	document.getElementById("tunip1").value = 0;
	
	document.getElementById("tunistel").value = 0;
    document.getElementById("tpractstel").value = 0;
    document.getElementById("tscoutstel").value = 0;
	document.getElementById("ttotal").value = 0;
	document.getElementById("tp1").value = 0;
	document.getElementById("tp2").value = 0;
	
	document.getElementById("ck").checked = false;
	document.getElementById("ckadd").checked = false;
	
	//document.getElementById("submit").disabled = true;
}

function calculate_cost()
{
	var p1 = parseFloat($("#tp1").val());
	var p3 = parseFloat($("#ttotal").val());
	document.getElementById("tp2").value = p3-p1;
}

function total_cost()
{
	var p1 = parseFloat($("#tregfee").val());
	var p2 = parseFloat($("#tdevfee").val());
	var p3 = parseFloat($("#tschoolfee").val());
	var p4 = parseFloat($("#tosisfee").val());
	var p5 = parseFloat($("#tpracticefee").val());
	var p6 = parseFloat($("#totherfee").val());
	
	var p8 = parseFloat($("#tp2").val());
	
	var res = p1+p2+p3+p4+p5+p6;
	document.getElementById("ttotal").value = res;
	document.getElementById("tp1").value = res;
	
	var p7 = parseFloat($("#tp1").val());
	document.getElementById("tp2").value = parseFloat(res-p7);
	calculate_cost();
}

function total_uni()
{
	var p1 = parseFloat($("#tunischool").val());
	var p2 = parseFloat($("#tunistel").val());
	var p3 = parseFloat($("#tunipractice").val());
	var p4 = parseFloat($("#tpractstel").val());
	var p5 = parseFloat($("#tscout").val());
	var p6 = parseFloat($("#tscoutstel").val());
	var p7 = parseFloat($("#tadd").val());
	
	var uni       = p1*p2;
	var practical = p3*p4;
	var scout     = p5*p6;
	
	document.getElementById("tunitotal").value = uni+practical+scout+p7;
	document.getElementById("tunip1").value = uni+practical+scout+p7;
	
}

// ajax =======================================================
$(document).ready(function(){
	
	$('#cdept,#clevel').change(function() {		
		
		var dept = $("#cdept").val();
		var level = $("#clevel").val();
		
		$.ajax({
		type: 'POST',
		url: uri +'get_regcost',
		data: "dept="+ dept + "&level=" + level,
		success: function(data)
		{
		   res = data.split("|");
		   document.getElementById("tregfee").value = res[0];
		   document.getElementById("tdevfee").value = res[1];
		   document.getElementById("tschoolfee").value = res[2];
		   document.getElementById("tosisfee").value = res[3];
		   document.getElementById("tpracticefee").value = res[4];
		   document.getElementById("totherfee").value = res[5];
		   total_cost();
		}
		})
		return false;
	});
	
	// uniform
	$('#cdept').change(function() {		
		
		var dept = $("#cdept").val();
		var gender = "";
		
		if (document.getElementById("rm").checked == true){ gender = $("#rm").val();} 
		else if (document.getElementById("rf").checked == true) { gender = $("#rf").val(); }
		
		$.ajax({
		type: 'POST',
		url: uri +'get_unicost',
		data: "dept="+ dept + "&gender=" + gender,
		success: function(data)
		{
		   res = data.split("|");
		   document.getElementById("tunischool").value = res[0];
		   document.getElementById("tunipractice").value = res[1];
		   document.getElementById("tscout").value = res[2];
//			   document.getElementById("tadd").value = res[3];
		   document.getElementById("tunistel").value = res[4];
		   document.getElementById("tpractstel").value = res[4];
		   document.getElementById("tscoutstel").value = res[4];
		   
		   total_uni();
		}
		})
		return false;
	});
	
	// faculty
	$('#cdept').change(function() {		
		
		$.ajax({
		type: 'POST',
		url: uri +'get_faculty_id',
		data: $(this).serialize(),
		success: function(data)
		{
		   document.getElementById("facultybox").innerHTML = data;
		}
		})
		return false;
	});
	
	// regid
	$('#bid').click(function() {		
		$.ajax({
		type: 'POST',
		url: uri +'get_reg_id',
		data: $(this).serialize(),
		success: function(data)
		{
		   document.getElementById("tregid").value = data;
		   document.getElementById("submit").disabled = false;
		}
		})
		return false;
	});
	
	// regid
	/*$('#submit').click(function() {		
		document.getElementById("submit").disabled = true;
	});*/
	
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

<body onLoad="cek_session(); resets();" onUnload="window.opener.location.reload(true);">
<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
<!-- awal tabs -->
<form name="admin_form" id="ajaxform" class="myform" method="post" action="<?php echo $form_action; ?>">
<div class="leftcol" style="width:49%; float:left;">
  
 <fieldset class="field"> <legend> Student Details </legend>
    <ul class="tabs">
        <li><a href="#tab1">Personal</a></li>
        <li><a href="#tab2">Parents & Trustee</a></li>
        <li><a href="#tab3">Score Information</a></li>
    </ul>
    
     <div class="tab_container">
     
        <div id="tab1" class="tab_content">
			
            <table>
            					
				<tr> <td> <label for="tname"> Name </label> </td> <td>: </td> 
                <td> <input type="text" class="required" name="tname" id="tname" title="Name - max 100" size="32" maxlength="100" value="<?php echo set_value('tname', isset($default['name']) ? $default['name'] : ''); ?>" />  </td> 
                </tr>	
                				
				<tr> <td> <label for="tborn"> Born </label> </td> <td>:</td> 
                <td> <input type="text" class="required" name="tborn" title="Born place" size="15" maxlength="50" value="<?php echo set_value('tborn', isset($default['born']) ? $default['born'] : ''); ?>" />
                     <input type="Text" name="tborndate" id="d1" title="Enter Born date" size="10" readonly class="required" value="<?php echo set_value('tborndate', isset($default['borndate']) ? $default['borndate'] : ''); ?>" /> 
                     <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> 
                </td> </tr>					
				
				<tr> <td> <label for="rgenre"> Gender </label> </td> <td>:</td> 
					 <td> Male <input name="rgenre" id="rm" checked type="radio" class="required" title="" onClick="resets();"
                      value="m" <?php echo set_radio('rgenre', 'm', isset($default['genre']) && $default['genre'] == 'm' ? TRUE : FALSE); ?> /> 
                     Female <input name="rgenre" id="rf" class="required" type="radio" onClick="resets();" 
                     value="f" <?php echo set_radio('rgenre', 'f', isset($default['genre']) && $default['genre'] == 'f' ? TRUE : FALSE); ?> />  
					</td> 
				</tr>
                
				<tr> <td> <label for="taddress">Address</label> </td> <td>:</td> 
                <td> <textarea class="required" name="taddress" title="Student Address" cols="25" rows="3"><?php echo set_value('taddress', isset($default['address']) ? $default['address'] : ''); ?></textarea> </td>
                </tr>	
                
                <tr> <td> <label for="tzip">Zip Code</label> </td> <td>:</td> 
                <td> <input type="text" class="required" name="tzip" id="tzip" title="Zip Code" size="5" value="<?php echo set_value('tzip', isset($default['zip']) ? $default['zip'] : ''); ?>" /> 
                <?php echo anchor_popup(site_url("city/get_list/"), '[ ... ]', $atts1); ?>
                </td> </tr>		
                	
				<tr> <td> <label for="tphone">Phone / Mobile </label> </td> <td>:</td> 
                <td> <input type="text" class="required" name="tphone" id="tphone" title="Student Phone No - max 15 digits" size="15" maxlength="15" onKeyUp="checkdigit(this.value, 'tphone')" value="<?php echo set_value('tphone', isset($default['phone']) ? $default['phone'] : ''); ?>" /> /
                     <input type="text" class="required" name="tmobile" id="tmobiles" title="Student Mobile - max 15 digits" size="15" maxlength="15" onKeyUp="checkdigit(this.value, 'tmobiles')" value="<?php echo set_value('tmobile', isset($default['mobile']) ? $default['mobile'] : ''); ?>" />
                </td> </tr>	
                
                <tr> <td> <label for="temail"> Email </label> </td> <td>:</td> 
                <td> <input type="text" class="" name="temail" title="Email" size="25" value="<?php echo set_value('temail', isset($default['email']) ? $default['email'] : ''); ?>" /> 
                </td> </tr>					
				
				<tr> <td><label for="creligion">Religion</label></td> <td>:</td> 
				 <td>
					  <select name="creligion" class="required" title="Religion">
						 <option value="Muslim" <?php echo set_select('creligion', 'Moeslim', isset($default['religion']) && $default['religion'] == 'Moeslim' ? TRUE : FALSE); ?> /> Moeslim </option>
						 <option value="Christian" <?php echo set_select('creligion', 'Christian', isset($default['religion']) && $default['religion'] == 'Christian' ? TRUE : FALSE); ?> /> Christian </option>
						 <option value="Hindu" <?php echo set_select('creligion', 'Hindu', isset($default['religion']) && $default['religion'] == 'Hindu' ? TRUE : FALSE); ?> /> Hindu </option>
						 <option value="Buddha" <?php echo set_select('creligion', 'Buddha', isset($default['religion']) && $default['religion'] == 'Buddha' ? TRUE : FALSE); ?> /> Buddha </option>
						 <option value="Other" <?php echo set_select('creligion', 'Other', isset($default['religion']) && $default['religion'] == 'Other' ? TRUE : FALSE); ?> /> Other </option>
					  </select>
					  <br />  <?php echo form_error('creligion', '<p class="field_error">', '</p>');?> 
				 </td>  </tr>
                 
                 <tr> <td> <label for="rcitizen"> Citizen </label> </td> <td>:</td> 
					 <td> WNI <input name="rcitizen" id="rwni" checked type="radio" class="required" title="" onClick="resets();"
                      value="wni" <?php echo set_radio('rcitizen', 'wni', isset($default['citizen']) && $default['citizen'] == 'wni' ? TRUE : FALSE); ?> /> 
                     WNA <input name="rcitizen" id="rwna" class="required" type="radio" onClick="resets();" 
                     value="wna" <?php echo set_radio('rcitizen', 'wna', isset($default['citizen']) && $default['citizen'] == 'wna' ? TRUE : FALSE); ?> />  
					</td> 
				</tr>
                
                <tr> <td><label for="ccondition"> Condition </label></td> <td>:</td> 
				 <td>
					  <select name="ccondition" class="required" title="Condition">
						 <option value="simple" <?php echo set_select('ccondition', 'simple', isset($default['condition']) && $default['condition'] == 'simple' ? TRUE : FALSE); ?> /> Simple </option>
						 <option value="poor" <?php echo set_select('ccondition', 'poor', isset($default['condition']) && $default['condition'] == 'poor' ? TRUE : FALSE); ?> /> Poor </option>
						 <option value="enough" <?php echo set_select('ccondition', 'enough', isset($default['condition']) && $default['condition'] == 'enough' ? TRUE : FALSE); ?> /> Enough </option>
						 <option value="rich" <?php echo set_select('ccondition', 'rich', isset($default['condition']) && $default['condition'] == 'rich' ? TRUE : FALSE); ?> /> Rich </option>
						 <option value="other" <?php echo set_select('ccondition', 'other', isset($default['condition']) && $default['condition'] == 'other' ? TRUE : FALSE); ?> /> Other </option>
					  </select>
					  <br />  <?php echo form_error('ccondition', '<p class="field_error">', '</p>');?> 
				 </td> </tr>
                 
                 <tr> <td> <label for="tnisn">NIS</label> </td> <td>:</td> 
                 <td> <input type="text" class="required" readonly name="tnis" id="tnis" title="NIS" size="10" value="<?php echo set_value('tnis', isset($default['nis']) ? $default['nis'] : ''); ?>" />  </td>
                 </tr>	
                 
				 <tr> <td> <label for="tnisn">NISN</label> </td> <td>:</td> 
                 <td> <input type="text" class="required" name="tnisn" id="tnisn" title="NISN No - max 20 digits" size="15" maxlength="20" onKeyUp="checkdigit(this.value, 'tnisn')" value="<?php echo set_value('tnisn', isset($default['nisn']) ? $default['nisn'] : ''); ?>" />  </td>
                 </tr>	
                 				
				 <tr> <td> <label for="tnpsn">NPSN</label> </td> <td>:</td> 
                 <td> <input type="text" class="required" name="tnpsn" id="tnpsn" title="NPSN No - max 20 digits" size="15" maxlength="20" onKeyUp="checkdigit(this.value, 'tnpsn')" value="<?php echo set_value('tnpsn', isset($default['npsn']) ? $default['npsn'] : ''); ?>" /> </td> 
                 </tr>		
                 			
				 <tr> <td> <label for="tcertificate">Certificate No</label> </td> <td>:</td> 
                 <td> <input type="text" class="required" name="tcertificate" id="tcertificate" title="Certificate No - max 25 digits" size="20" maxlength="25" onKeyUp="checkdigit(this.value, 'tcertificate')" value="<?php echo set_value('tcertificate', isset($default['certificate']) ? $default['certificate'] : ''); ?>" />  </td> 
                 </tr>		
                 
				 <tr> <td> <label for="tskhun">SKHUN</label> </td> <td>:</td>
                 <td> <input type="text" class="required" name="tskhun" id="tskhun" title="SKHUN No - max 25 digits" size="10" maxlength="15" onKeyUp="checkdigit(this.value, 'tskhun')" value="<?php echo set_value('tskhun', isset($default['skhun']) ? $default['skhun'] : ''); ?>" /> </td> 
                 </tr>	
                 
			</table>
            
        </div>
        
        <div id="tab2" class="tab_content">
			
            <table>
				<tr> <td> <label for="tfname"> Father Name</label> </td> <td>:</td> 
                <td> <input type="text" class="required" name="tfname" id="tfname" title="Father Name - max 50" size="25" maxlength="50" value="<?php echo set_value('tfname', isset($default['fname']) ? $default['fname'] : ''); ?>" />  </td> 
                </tr>	
                
				<tr> <td> <label for="tfjob"> Father Job</label> </td> <td>:</td> 
                <td> <input type="text" class="required" name="tfjob" id="tfjob" title="Father Job - max 50" size="25" maxlength="50" value="<?php echo set_value('tfjob', isset($default['fjob']) ? $default['fjob'] : ''); ?>" />  </td>
                </tr>	
                				
				<tr> <td> <label for="tfaddress"> Father Address</label> </td> <td>:</td> 
                <td> <textarea class="required" name="tfaddress" title="Father Address" cols="25" rows="3"><?php echo set_value('tfaddress', isset($default['faddress']) ? $default['faddress'] : ''); ?></textarea> </td>
                </tr>	
                
				<tr> <td> <label for="tfphone"> Father Phone / Mobile </label> </td> <td>:</td> 
                <td> <input type="text" class="" name="tfphone" id="tfphone" title="Father Phone No - max 15 digits" size="15" maxlength="15" onKeyUp="checkdigit(this.value, 'tfphone')" value="<?php echo set_value('tfphone', isset($default['fphone']) ? $default['fphone'] : ''); ?>" /> / 
                     <input type="text" class="" name="tfmobile" id="tfmobile" title="Father Mobile No - max 15 digits" size="15" maxlength="15" onKeyUp="checkdigit(this.value, 'tfmobile')" value="<?php echo set_value('tfmobile', isset($default['fmobile']) ? $default['fmobile'] : ''); ?>" /> </td> 
                </tr>
                
                <tr> <td> <label for="tfincome"> Father Income </label> </td> <td>:</td> 
                <td> <input type="text" class="" name="tfincome" id="tfincome" title="Parents Income No - max 15 digits" size="15" maxlength="15" onKeyUp="checkdigit(this.value, 'tfincome')" value="<?php echo set_value('tfincome', isset($default['fincome']) ? $default['fincome'] : '0'); ?>" /> </td> 
                </tr>	
                
                <!-- Mother -->
                <tr> <td colspan="3"> <hr> </td> </tr>
                										
				<tr> <td> <label for="tmname"> Mother Name</label> </td> <td>:</td> 
                <td> <input type="text" class="form_field" name="tmname" id="tmname" title="Mother Name - max 50" size="25" maxlength="50" value="<?php echo set_value('tmname', isset($default['mname']) ? $default['mname'] : ''); ?>" /> </td> 
                </tr>	
                
				<tr> <td> <label for="tmjob"> Mother Job</label> </td> <td>:</td> 
                <td> <input type="text" class="" name="tmjob" id="tmjob" title="Mother Job - max 50" size="25" maxlength="50" value="<?php echo set_value('tmjob', isset($default['mjob']) ? $default['mjob'] : ''); ?>" /> </td> 
                </tr>	
                				
				<tr> <td> <label for="tmaddress"> Mother Address</label> </td> <td>:</td> 
                <td> <textarea name="tmaddress" title="Mother Address" cols="25" rows="3"><?php echo set_value('tmaddress', isset($default['maddress']) ? $default['maddress'] : ''); ?></textarea> </td>
                </tr>	
                
				<tr> <td> <label for="tmphone"> Mother Phone / Mobile </label> </td> <td>:</td> 
                <td> <input type="text" class="" name="tmphone" id="tmphone" title="Mother Phone No - max 15 digits" size="15" maxlength="15" onKeyUp="checkdigit(this.value, 'tmphone')" value="<?php echo set_value('tmphone', isset($default['mphone']) ? $default['mphone'] : ''); ?>" /> / 
                     <input type="text" class="" name="tmmobile" id="tmmobile" title="Mother Mobile No - max 15 digits" size="15" maxlength="15" onKeyUp="checkdigit(this.value, 'tmmobile')" value="<?php echo set_value('tmmobile', isset($default['mmobile']) ? $default['mmobile'] : ''); ?>" /> </td> 
                </tr>
                
                <tr> <td> <label for="tmincome"> Mother Income </label> </td> <td>:</td> 
                <td> <input type="text" class="" name="tmincome" id="tmincome" title="Mother Income No - max 15 digits" size="15" maxlength="15" onKeyUp="checkdigit(this.value, 'tmincome')" value="<?php echo set_value('tmincome', isset($default['mincome']) ? $default['mincome'] : '0'); ?>" /> </td> 
                </tr>		
                
                <!-- Trustee -->
                <tr> <td colspan="3"> <hr> </td> </tr>
                										
				<tr> <td> <label for="ttrusteename">Trustee Name</label> </td> <td>:</td> 
                <td> <input type="text" class="form_field" name="ttrusteename" id="ttrusteename" title="Trustee Name - max 50" size="25" maxlength="50" value="<?php echo set_value('ttrusteename', isset($default['trusteename']) ? $default['trusteename'] : ''); ?>" /> </td> 
                </tr>	
                
				<tr> <td> <label for="ttrusteejob">Trustee Job</label> </td> <td>:</td> 
                <td> <input type="text" class="form_field" name="ttrusteejob" id="ttrusteejob" title="Trustee Job - max 50" size="25" maxlength="50" value="<?php echo set_value('ttrusteejob', isset($default['trusteejob']) ? $default['trusteejob'] : ''); ?>" /> </td> 
                </tr>	
                				
				<tr> <td> <label for="ttrusteeaddress">Trustee Address</label> </td> <td>:</td> 
                <td> <textarea name="ttrusteeaddress" title="Trustee Address" cols="25" rows="3"><?php echo set_value('ttrusteeaddress', isset($default['trusteeaddress']) ? $default['trusteeaddress'] : ''); ?></textarea> </td>
                </tr>	
                
				<tr> <td> <label for="ttrusteephone">Trustee Phone</label> </td> <td>:</td> 
                <td> <input type="text" class="form_field" name="ttrusteephone" id="ttrusteephone" title="Trustee Phone No - max 15 digits" size="15" maxlength="15" onKeyUp="checkdigit(this.value, 'ttrusteephone')" value="<?php echo set_value('ttrusteephone', isset($default['trusteephone']) ? $default['trusteephone'] : ''); ?>" /> </td> 
                </tr>											
			</table>
            
        </div>
        
         <div id="tab3" class="tab_content">
			
            <table>
				<tr> <td> <label for="tmath"> Math Score </label> </td> <td>:</td> 
                <td> <input type="text" class="required" name="tmath" id="tmath" title="Math Score" size="5" maxlength="5" value="<?php echo set_value('tmath', isset($default['math']) ? $default['math'] : ''); ?>" />  </td> 
                </tr>	
                
				<tr> <td> <label for="tindo"> Indonesia Score </label> </td> <td>:</td> 
                <td> <input type="text" class="required" name="tindo" id="tindo" title="Indonesia Score" size="5" maxlength="5" value="<?php echo set_value('tindo', isset($default['indo']) ? $default['indo'] : ''); ?>" />  </td>
                </tr>	
                
                <tr> <td> <label for="tphysics"> Physics Score </label> </td> <td>:</td> 
                <td> <input type="text" class="required" name="tphysics" id="tphysics" title="Physics Score" size="5" maxlength="5" value="<?php echo set_value('tphysics', isset($default['physics']) ? $default['physics'] : ''); ?>" />  </td>
                </tr>	
                				
				<tr> <td> <label for="tenglish"> English Score </label> </td> <td>:</td> 
                <td> <input type="text" class="required" name="tenglish" id="tenglish" title="English Score" size="5" maxlength="5" value="<?php echo set_value('tenglish', isset($default['english']) ? $default['english'] : ''); ?>" /> </td> 
                </tr>	
                										
				<tr> <td> <label for="tchemical"> Chemical Score </label> </td> <td>:</td> 
                <td> <input type="text" class="required" name="tchemical" id="tchemical" title="Chemical Score" size="5" maxlength="5" value="<?php echo set_value('tchemical', isset($default['chemical']) ? $default['chemical'] : ''); ?>" /> </td> 
                </tr>	
									
			</table>
            
        </div>
        
     </div>
 </fieldset>
 
 <fieldset class="field"> <legend> Uniform Form </legend>
		<table>
        
			<tr> <td> <label for="tunischool">School</label> </td> <td>:</td>
            <td> <input type="text" class="required" name="tunischool" id="tunischool" title="School Uniform Fee - max 10 digits" size="15" maxlength="10" readonly onKeyUp="checkdigit(this.value, 'tunischool'); total_uni();" value="<?php echo set_value('tunischool', isset($default['unischool']) ? $default['unischool'] : ''); ?>" /> 
			Stel : <input type="text" class="required" name="tunistel" id="tunistel" title="Uniform Stel - max 2 digits" size="1" maxlength="2"                    onKeyUp="checkdigit(this.value, 'tunistel'); total_uni();" 
                   value="<?php echo set_value('tunistel', isset($default['unistel']) ? $default['unistel'] : ''); ?>" />
			       <input type="checkbox" id="ckadd" class="form_field" title="Additional Cost" onClick="enb_uni();" />
			</td> </tr>					
			
			<tr> <td> <label for="tunipractice">Practice</label> </td> <td>:</td> 
            <td> <input type="text" class="required" name="tunipractice" id="tunipractice" title="Practice Uniform Fee" size="15" maxlength="10" readonly onKeyUp="checkdigit(this.value, 'tunipractice'); total_uni();" value="<?php echo set_value('tunipractice', isset($default['unipractice']) ? $default['unipractice'] : ''); ?>" /> 
			Stel : <input type="text" class="required" name="tpractstel" id="tpractstel" title="Practice Stel - max 2 digits" size="1" maxlength="2" onKeyUp="checkdigit(this.value, 'tpractstel'); total_uni();;" value="<?php echo set_value('tpractstel', isset($default['practstel']) ? $default['practstel'] : ''); ?>" />    </td> </tr>
            
            <tr> <td> <label for="tscout"> Scout </label> </td> <td>:</td> 
            <td> <input type="text" class="required" name="tscout" id="tscout" title="Scout Fee" size="15" maxlength="10" readonly onKeyUp="checkdigit(this.value, 'tscout'); total_uni();" value="<?php echo set_value('tscout', isset($default['scout']) ? $default['scout'] : ''); ?>" /> 
			Stel : <input type="text" class="required" name="tscoutstel" id="tscoutstel" title="Scout Stel - max 2 digits" size="1" maxlength="2" onKeyUp="checkdigit(this.value, 'tscoutstel'); total_uni();" value="<?php echo set_value('tscoutstel', isset($default['scoutstel']) ? $default['scoutstel'] : ''); ?>" />    </td> </tr>		
			
		  <tr> <td> <label for="tadd"> Additional </label> </td> <td>:</td> <td> 
<input type="text" class="required" name="tadd" id="tadd" title="Add Uniform Fee - max 10 digits" size="15" maxlength="10" 
onKeyUp="checkdigit(this.value, 'tadd'); total_uni();" value="<?php echo set_value('tadd', isset($default['add']) ? $default['add'] : '0'); ?>" /> 
		  </td> </tr>								
			
			<tr> <td> <label for="tunitotal">Total</label> </td> <td>:</td> 
            <td>  <input type="text" class="required" name="tunitotal" id="tunitotal" readonly title="Uniform Total Fee - max 10 digits" size="15" maxlength="10" onKeyUp="checkdigit(this.value, 'tunitotal')" value="<?php echo set_value('tunitotal', isset($default['unitotal']) ? $default['unitotal'] : ''); ?>" /> </td> </tr>	
            				
			<tr> <td> <label for="tunip1">I Payment</label> </td> <td>:</td> 
            <td> <input type="text" class="required" name="tunip1" id="tunip1" title="I Payment - max 10 digits" size="15" maxlength="10" onKeyUp="checkdigit(this.value, 'tunip1')" value="<?php echo set_value('tunip1', isset($default['unip1']) ? $default['unip1'] : ''); ?>" /> </td> 
            </tr>					
            
		</table>
	</fieldset>
  
</div>


<div class="rightcol" style="width:49%; float:right;">
  
 <fieldset class="field"> <legend> Registration Form </legend>
		<table>
			<tr> <td> <label for="tregid">Registration ID</label> </td> <td>:</td> 
            <td> REG-0 <input type="text" class="required" name="tregid" id="tregid" title="Registration ID" size="3" readonly value="<?php echo set_value('tregid', isset($default['regid']) ? $default['regid'] : ''); ?>" />  </td> </tr>		
            			
			<tr> <td> <label for="tregdate">Registration Date</label> </td> <td>:</td> 
            <td> <input type="Text" name="tregdate" id="d2" title="Registration date" size="10" readonly class="required" value="<?php echo set_value('tregdate', isset($default['regdate']) ? $default['regdate'] : ''); ?>" /> <img src="<?php echo base_url();?>/jdtp-images/cal.gif" 
            onClick="javascript:NewCssCal('d2','yyyymmdd')" style="cursor:pointer"/> </td> 
            </tr>	
            				
            <tr> <td> <label for="cdept">Department</label> </td> <td>:</td>
            <td> <?php $js = 'id="cdept"'; echo form_dropdown('cdept', $dept, isset($default['dept']) ? $default['dept'] : '', $js); ?> </td>
            </tr>
            
            <tr> <td> <label for="clevel"> Level </label> </td> <td>:</td>
            <td> <?php $js = 'id="clevel"'; echo form_dropdown('clevel', $level, isset($default['level']) ? $default['level'] : '', $js); ?> </td>
            </tr>
			 
			<tr> <td><label for="cfaculty">Faculty</label></td> <td>:</td> 
            <td> <div id="facultybox"></div>  </td> 
            </tr>
		</table>
	</fieldset>
    
    <!-- Cost Details -->
    <fieldset class="field"> <legend> Cost Details </legend>
		<table>
        
			<tr> <td> <label for="tregfee"> Registration </label> </td> <td>:</td> 
            <td> <input type="text" class="required" name="tregfee" id="tregfee" title="Registration Fee" readonly size="15" maxlength="10" 
                onKeyUp="checkdigit(this.value, 'tregfee'); total_cost();" value="<?php echo set_value('tregfee', isset($default['regfee']) ? $default['regfee'] : ''); ?>" /> <input type="checkbox" id="ck" class="form_field" title="Enable/Disable Fee" onClick="enb()" />  </td> 
            </tr>		
            			
			<tr> <td> <label for="tdevfee"> Development </label> </td> <td>:</td>
            <td> <input type="text" class="required" name="tdevfee" id="tdevfee" title="Development Fee" readonly size="15" maxlength="10" onKeyUp="checkdigit(this.value, 'tdevfee'); total_cost();" value="<?php echo set_value('tdevfee', isset($default['devfee']) ? $default['devfee'] : ''); ?>" /> </td> 
            </tr>	
            				
			<tr> <td> <label for="tschoolfee"> School </label> </td> <td>:</td> 
            <td> <input type="text" class="required" name="tschoolfee" id="tschoolfee" title="School Fee" readonly size="15" maxlength="10" 
                 onKeyUp="checkdigit(this.value, 'tschoolfee'); total_cost();" 
                 value="<?php echo set_value('tschoolfee', isset($default['schoolfee']) ? $default['schoolfee'] : ''); ?>" /> </td> 
            </tr>		
            			
			<tr> <td> <label for="tosisfee"> OSIS </label> </td> <td>:</td>
            <td> <input type="text" class="required" name="tosisfee" id="tosisfee" title="OSIS Fee" size="15" readonly maxlength="10" 
                 onKeyUp="checkdigit(this.value, 'tosisfee'); total_cost();" value="<?php echo set_value('tosisfee', isset($default['osisfee']) ? $default['osisfee'] : ''); ?>" /> </td> </tr>	
            				
			<tr> <td> <label for="tpracticefee"> Practice </label> </td> <td>:</td> 
            <td> <input type="text" class="required" name="tpracticefee" id="tpracticefee" title="Practice Fee" readonly size="15" 
                 maxlength="10" onKeyUp="checkdigit(this.value, 'tpracticefee'); total_cost();"
                 value="<?php echo set_value('tpracticefee', isset($default['practicefee']) ? $default['practicefee'] : ''); ?>" /> </td> 
            </tr>					
            
			<tr> <td> <label for="totherfee">Others</label> </td> <td>:</td> <td> <input type="text" class="required" name="totherfee"
            id="totherfee" title="Other Fee" size="15" readonly maxlength="10" onKeyUp="checkdigit(this.value, 'totherfee'); total_cost();" 
            value="<?php echo set_value('totherfee', isset($default['otherfee']) ? $default['otherfee'] : ''); ?>" /> </td> 
            </tr>		
            			
			<tr> <td> <label for="tnotes">Notes</label> </td> <td>:</td>
            <td> <textarea name="tnotes" title="Notes" cols="25" rows="2"><?php echo set_value('tnotes', isset($default['notes']) ? $default['notes'] : ''); ?></textarea> </td>
            </tr>	
            
		</table>
	    <hr>
		<table>
        
			<tr> <td> <label for="ttotal">Total</label> </td> <td>:</td> <td> <input type="text" class="required" name="ttotal" id="ttotal" title="Total - max 10 digits" size="15" maxlength="10" readonly onKeyUp="checkdigit(this.value, 'ttotal')" value="<?php echo set_value('ttotal', isset($default['total']) ? $default['total'] : ''); ?>" />  </td> 
            </tr>
            					
			<tr> <td> <label for="tp1">I Payment</label> </td> <td>:</td> <td> <input type="text" class="required" name="tp1" id="tp1" title="I Payment - max 10 digits" size="15" maxlength="10" onKeyUp="checkdigit(this.value, 'tp1'); calculate_cost();" value="<?php echo set_value('tp1', isset($default['p1']) ? $default['p1'] : ''); ?>" />  </td>
            </tr>	
            				
			<tr> <td> <label for="tp2">II Payment</label> </td> <td>:</td> 
            <td> <input type="text" class="required" name="tp2" id="tp2" title="II Payment" size="15" maxlength="10" readonly 
                 value="<?php echo set_value('tp2', isset($default['p2']) ? $default['p2'] : ''); ?>" /> 
            </td> </tr>
            					
		  <tr> <td><label for="cpayment">Payment Type</label></td> <td>:</td> 
          <td> <?php $js = 'id=""'; echo form_dropdown('cpayment', $payment, isset($default['payment']) ? $default['payment'] : '', $js); ?>  </td> 
            </tr>
		</table>
	</fieldset>
    
    <fieldset class="field"> <legend> Registration Process </legend>
		<p>
			<input type="submit" name="submit" id="submit" class="button" title="Process" value="SAVE" />
			<input type="button" name="reset" class="button" title="Reset Button" value="CANCEL" onClick="window.close()" />
		</p>
	</fieldset>
  
</div>

</form>
<!-- batas tab -->    

<div class="clear"></div>    
</div>

</body>
