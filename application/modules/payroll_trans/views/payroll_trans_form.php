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

function open_window()
{
	var type = document.getElementById("ctype").value
	if (type != "")
	{
		if (type == 'salary'){ var urlemployee = (site+'/employees/get_list/non'); }
		else if (type == 'honor') { var urlemployee = (site+'/employees/get_list/academic'); }
    	window.open(urlemployee,'Employee','height=400,width=600,scrollbars=yes,resizable=yes');
    }
	else { alert("Select Employee Type First....!!"); }
	
}

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
				{ document.getElementById("errorbox").innerHTML = data;}
			}
		})
		return false;
	});	
	
	
	$('#ctype').change(function() {
	   
	   clear();
	   var type = $("#ctype").val();
	   if (type == 'salary')
	   {		   
		  document.getElementById("tsearch").value="";  
		  document.getElementById("ttransport").readOnly=true; 
		  document.getElementById("tbonus").readOnly=false; 
		  document.getElementById("tloan").readOnly=false; 
		  document.getElementById("ttax").readOnly=false; 
		  document.getElementById("tinsurance").readOnly=false; 
		  document.getElementById("tother").readOnly=false;
		  
		  document.getElementById("tprincipal").readOnly=true;  
		  document.getElementById("tpks").readOnly=true;  
		  document.getElementById("tkajur").readOnly=true;
		  document.getElementById("troom").readOnly=true; 
		  document.getElementById("tpicket").readOnly=true;     
	   }
	   else if(type == 'honor')
	   {   
		  document.getElementById("tsearch").value=""; 
		  document.getElementById("ttransport").readOnly=true; 
		  document.getElementById("tbonus").readOnly=false; 
		  document.getElementById("tloan").readOnly=false; 
		  document.getElementById("ttax").readOnly=false; 
		  document.getElementById("tinsurance").readOnly=false; 
		  document.getElementById("tother").readOnly=false;  
		  
		  document.getElementById("tprincipal").readOnly=false;  
		  document.getElementById("tpks").readOnly=false;  
		  document.getElementById("tkajur").readOnly=false;
		  document.getElementById("troom").readOnly=false; 
		  document.getElementById("tpicket").readOnly=false;   
	   }
	   else { document.getElementById("tsearch").value="";  }
	});
	
	// get button
	$('#bget').click(function() {
	   
	    var nip = $("#tsearch").val();
		var month = $("#tmonth").val();
		var year  = $("#tyear").val();
		var type = $("#ctype").val();
		var dept = $("#cdept").val();
		
		if (type == 'salary'){ var ur = uri +'get_salary'; }
		else if(type == 'honor'){ var ur = uri +'get_honor'; } 
		
		$.ajax({
		type: 'POST',
		url: ur,
		data: "nip="+ nip + "&month=" + month + "&year=" + year+ "&dept=" + dept,
		success: function(data)
		{
		   res = data.split("|");
		   document.getElementById("tbasic").value = res[0];
		   document.getElementById("tconsumption").value = res[1];
		   document.getElementById("ttransport").value = res[2];
		   document.getElementById("tovertime").value = res[3];
		   document.getElementById("texperience").value = res[4];
		   calculate_aid();
		}
		})
		return false;
	   
	});
	
	// get button
	$('#bgetloan').click(function() {
	   
	    var nip = $("#tsearch").val();
		
		$.ajax({
		type: 'POST',
		url: uri +'get_loan',
		data: "nip="+ nip,
		success: function(data)
		{
		   document.getElementById("tloan").value = data;
		   calculate_aid();
		}
		})
		return false;
	   
	});
	
/* end document */		
});

function clear()
{
	document.getElementById("tbasic").value=0; 
	document.getElementById("texperience").value=0; 
	document.getElementById("tovertime").value=0; 
	document.getElementById("tlate").value=0; 
    document.getElementById("tconsumption").value=0; 
	
    document.getElementById("ttransport").value=0; 
    document.getElementById("tbonus").value=0; 
    document.getElementById("tloan").value=0; 
    document.getElementById("ttax").value=0; 
    document.getElementById("tinsurance").value=0; 
    document.getElementById("tother").value=0;  
  
    document.getElementById("tprincipal").value=0;  
    document.getElementById("tpks").value=0;  
    document.getElementById("tkajur").value=0; 
	document.getElementById("troom").value=0; 
	document.getElementById("tpicket").value=0; 
	document.getElementById("ttotal").value=0; 
}

function calculate_aid()
{
	var p1 = parseFloat($("#tbasic").val());
	var p2 = parseFloat($("#texperience").val());
	var p3 = parseFloat($("#tovertime").val());
	var p4 = parseFloat($("#tconsumption").val());
	var p5 = parseFloat($("#ttransport").val());
	var p6 = parseFloat($("#tbonus").val());
	var p7 = parseFloat($("#tprincipal").val());
	var p8 = parseFloat($("#tpks").val());
	var p9 = parseFloat($("#tkajur").val());
	var p10 = parseFloat($("#troom").val());
	var p11 = parseFloat($("#tpicket").val());
	
	var p12 = parseFloat($("#tlate").val());
	var p13 = parseFloat($("#tloan").val());
	var p14 = parseFloat($("#ttax").val());
	var p15 = parseFloat($("#tinsurance").val());
	var p16 = parseFloat($("#tother").val());
	
	var res = p1+p2+p3+p4+p5+p6+p7+p8+p9+p10+p11;
	var loan = p12+p13+p14+p15+p16;
	
	document.getElementById("ttotal").value = res-loan;
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

<body onLoad="cek_session();" onUnload="window.opener.location.reload(true);">
<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Create - Payroll Transaction </legend>
	    <form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
			
			<table>
            		
                   <tr>
                   <td> <label for="ctype"> Type : </label> </td> <td>:</td>
                   <td> <select name="ctype" id="ctype">
                            <option value=""> -- All -- </option>
                            <option value="salary"> Salary </option>
                            <option value="honor"> Honor </option>
                         </select> &nbsp;
                         <input type="hidden" id="tmonth" value="<?php echo $month; ?>" />
                         <input type="hidden" id="tyear" value="<?php echo $year; ?>" />
                   </td>
                   </tr>
                   
                   <tr>
                   <td> <label for="cpayment"> Payment Type : </label> </td> <td>:</td>
                   <td> <select name="cpayment" id="cpayment">
                            <option value="transfer"> Transfer </option>
                            <option value="cash"> Cash </option>
                         </select> &nbsp;
                   </td>
                   </tr>
                    
                   <tr> <td> <label for="tvalue"> Department </label> </td> <td>:</td> 
                   <td> <?php $js = 'id="cdept"'; echo form_dropdown('cdept', $dept, isset($default['dept']) ? $default['dept'] : '', $js); ?> </td>
                   </tr> 
                   
                   <tr>
                   <td> <label for="tname"> Employee </label> </td> <td>:</td>
                   <td> <input type="text" readonly name="tnip" id="tsearch" size="10" title="Name" />
                        <input type="button" value="[ ... ]" onClick="open_window();" />
                   </td>
                   </tr>
                                          
                    <tr>                 
                    <td> <label for="tbasic"> Basic Salary </label> </td> <td>:</td> 
<td> <input type="text" readonly id="tbasic" name="tbasic" size="10" title="Basic Salary" 
value="<?php echo set_value('tbasic', isset($default['basic']) ? $default['basic'] : '0'); ?>" onKeyUp="checkdigit(this.value,'tbasic')" />
					     <input type="button" id="bget" value=" GET " /> </td>
					</tr> 
                    
                    <tr>                 
                    <td> <label for="texperience"> Experience Benefit </label> </td> <td>:</td> 
                    <td> <input type="text" id="texperience" readonly name="texperience" size="10" title="Experience" 
                         value="<?php echo set_value('texperience', isset($default['experience']) ? $default['experience'] : '0'); ?>" 
                         onKeyUp="checkdigit(this.value,'texperience')" /> </td> 
					</tr> 
                    
                    <tr>                 
                    <td> <label for="tovertime"> Overtime </label> </td> <td>:</td> 
					<td> <input type="text" id="tovertime" readonly name="tovertime" size="10" title="Other Costs" 
						 value="<?php echo set_value('tovertime', isset($default['overtime']) ? $default['overtime'] : '0'); ?>"
					     onKeyUp="checkdigit(this.value,'tovertime')"/> </td>
					</tr>
                    
                    <tr>                 
                    <td> <label for="tconsumption"> Consumption </label> </td> <td>:</td> 
                    <td> <input type="text" id="tconsumption" readonly name="tconsumption" size="10" title="Consumption Costs" 
                         value="<?php echo set_value('tconsumption', isset($default['consumption']) ? $default['consumption'] : '0'); ?>" 
                         onKeyUp="checkdigit(this.value,'tconsumption')" /> </td>
					</tr> 
                    
                    <tr>                 
                    <td> <label for="ttransport"> Transportation </label> </td> <td>:</td> 
                    <td> <input type="text" readonly id="ttransport" name="ttransport" size="10" title="Transport Costs" 
                         value="<?php echo set_value('ttransport', isset($default['transport']) ? $default['transport'] : '0'); ?>" 
                         onKeyUp="checkdigit(this.value,'ttransport'); calculate_aid()" /> </td>
					</tr> 
                    
                    <tr>                 
                    <td> <label for="tbonus"> Bonus </label> </td> <td>:</td> 
   		            <td> <input type="text" readonly id="tbonus" name="tbonus" size="10" title="Bonus" 
						 value="<?php echo set_value('tbonus', isset($default['bonus']) ? $default['bonus'] : '0'); ?>" 
					     onKeyUp="checkdigit(this.value,'tbonus'); calculate_aid()" /> </td>
					</tr> 
                    
                    <tr>                 
                    <td> <label for="tprincipal"> Principal Bonus </label> </td> <td>:</td> 
                    <td> <input type="text" readonly id="tprincipal" name="tprincipal" size="10" title="Principal Bonus" 
                         value="<?php echo set_value('tprincipal', isset($default['principal']) ? $default['principal'] : '0'); ?>" 
                         onKeyUp="checkdigit(this.value,'tprincipal'); calculate_aid()" /> 
                    </td>
					</tr>
                    
                    <tr>                 
                    <td> <label for="tpks"> Principal Helper (PKS) </label> </td> <td>:</td> 
                    <td> <input type="text" readonly id="tpks" name="tpks" size="10" title="PKS Bonus" 
                         value="<?php echo set_value('tpks', isset($default['pks']) ? $default['pks'] : '0'); ?>" 
                         onKeyUp="checkdigit(this.value,'tpks'); calculate_aid()" /> 
                    </td>
					</tr> 
                    
                    <tr>                 
                    <td> <label for="tkajur"> Head Department (Kajur) </label> </td> <td>:</td> 
                    <td> <input type="text" readonly id="tkajur" name="tkajur" size="10" title="Kajur Bonus" 
                         value="<?php echo set_value('tkajur', isset($default['kajur']) ? $default['kajur'] : '0'); ?>" 
                         onKeyUp="checkdigit(this.value,'tkajur'); calculate_aid()" /> 
                    </td>
					</tr> 
                    
                    <tr>                 
                    <td> <label for="troom"> Guardians (Wali kelas) </label> </td> <td>:</td> 
                    <td> <input type="text" readonly id="troom" name="troom" size="10" title="Home Room" 
                         value="<?php echo set_value('troom', isset($default['room']) ? $default['room'] : '0'); ?>" 
                         onKeyUp="checkdigit(this.value,'troom'); calculate_aid()" /> 
                    </td>
					</tr> 
                    
                    <tr>                 
                    <td> <label for="tpicket"> Picket </label> </td> <td>:</td> 
                    <td> <input type="text" id="tpicket" name="tpicket" size="10" title="Picket" 
                         value="<?php echo set_value('tpicket', isset($default['picket']) ? $default['picket'] : '0'); ?>" 
                         onKeyUp="checkdigit(this.value,'tpicket'); calculate_aid()" /> 
                    </td>
					</tr> 
                    
            </table>
            </fieldset>
                    
             <fieldset class="field"> <legend> Reduction </legend>     
              <table>
              		
                    <tr>                 
                    <td> <label for="tlate"> Late Charges </label> </td> <td>:</td> 
                    <td> <input type="text" id="tlate" name="tlate" size="10" title="Late Charges" 
                         value="<?php echo set_value('tlate', isset($default['late']) ? $default['late'] : '0'); ?>" 
                         onKeyUp="checkdigit(this.value,'tlate'); calculate_aid()" /> 
                    </td>
					</tr> 	
                        
                    <tr>                 
                    <td> <label for="tloan"> Loan </label> </td> <td>:</td> 
                    <td> <input type="text" readonly id="tloan" name="tloan" size="10" title="Loan" 
                         value="<?php echo set_value('tloan', isset($default['loan']) ? $default['loan'] : '0'); ?>" 
                         onKeyUp="checkdigit(this.value,'tloan'); calculate_aid()" /> 
                         <input type="button" id="bgetloan" value="GET LOAN">
                    </td>
					</tr> 
                    
                    <tr>                 
                    <td> <label for="ttax"> Tax </label> </td> <td>:</td> 
                    <td> <input type="text" readonly id="ttax" name="ttax" size="10" title="Tax" 
                         value="<?php echo set_value('ttax', isset($default['tax']) ? $default['tax'] : '0'); ?>" 
                         onKeyUp="checkdigit(this.value,'ttax'); calculate_aid()" /> 
                    </td>
					</tr>
                    
                    <tr>                 
                    <td> <label for="tinsurance"> Insurance </label> </td> <td>:</td> 
                    <td> <input type="text" readonly id="tinsurance" name="tinsurance" size="10" title="Insurance" 
                         value="<?php echo set_value('tinsurance', isset($default['insurance']) ? $default['insurance'] : '0'); ?>" 
                         onKeyUp="checkdigit(this.value,'tinsurance'); calculate_aid()" /> 
                    </td>
					</tr>
                    
                    <tr>                 
                    <td> <label for="tother"> Other </label> </td> <td>:</td> 
                    <td> <input type="text" readonly id="tother" name="tother" size="10" title="Other" 
                         value="<?php echo set_value('tother', isset($default['other']) ? $default['other'] : '0'); ?>" 
                         onKeyUp="checkdigit(this.value,'tother'); calculate_aid()" /> 
                    </td>
					</tr>
                    
                    <tr>                 
                    <td> <label for="tdesc"> <b> Total </b> </label> </td> <td>:</td> 
<td> <input type="text" id="ttotal" readonly name="ttotal" size="10" title="Total" 
value="<?php echo set_value('ttotal', isset($default['total']) ? $default['total'] : '0'); ?>" onKeyUp="checkdigit(this.value,'total')" /> </td>
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
