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
			else{ document.getElementById("errorbox").innerHTML = data; }
			}
		})
		return false;
	});	
	
	$('#tstudentsearch').autocomplete({
		// serviceUrl berisi URL ke controller/fungsi yang menangani request kita
		serviceUrl: site+'/tuition_transaction/autocomplete',
		// fungsi ini akan dijalankan ketika user memilih salah satu hasil request
		onSelect: function (suggestion) {
			// alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
			res = suggestion.data.split("|"); 
			document.getElementById("tid").value = res[0]; // sid
			document.getElementById("tnis").value = res[1]; // nisn
			document.getElementById("tdept").value = res[2]; // dept
			document.getElementById("tgrade").value = res[3]; // grade
			document.getElementById("tdeptid").value = res[4]; // deptid
			document.getElementById("tgradeid").value = res[5]; // gradeid
			get_fee(res[4],res[5],res[0]);
			get_receipt(res[4]);
			get_period(res[0]);
		}
	});	
	
	// School Fee Type
	function get_fee(dept,grade,sid)
	{		
		var year = $("#cyear").val();
			
		$.ajax({
		type: 'POST',
		url: site +"/tuition_transaction/get_fee_type",
		data: "cdept="+ dept + "&cgrade=" + grade + "&tsid=" + sid+ "&cyear=" + year,
		success: function(data)
		{
			res = data.split("|"); 
			document.getElementById("tfeeid").value = res[0];
			document.getElementById("tfee").value = res[1];
		}
		})
		return false;
	}
	
	function get_period(sid)
	{	
		$.ajax({
		type: 'POST',
		url: uri +'get_payment_front',
		data: "sid="+ sid,
		success: function(data)
		{
		   res = data.split("|");
		   document.getElementById("tperiod").value = res[0];
     	   document.getElementById("tperiodmonth").value = res[1];
		}
		})
		return false;
	}
	
	// receipt box type
	function get_receipt(dept)
	{		
			
		$.ajax({
		type: 'POST',
		url: site +"/ajax/getreceipt",
		data: "cdept="+ dept,
		success: function(data)
		{ document.getElementById("receiptbox").innerHTML = data; }
		})
		return false;
	}
	
	$('#bgetfee').click(function() {
		
		var fee = $("#tfeeid").val();
					
		$.ajax({
		type: 'POST',
		url: site +"/tuition_transaction/get_fee",
		data: "cfee="+ fee,
		success: function(data)
		{
		   res = data.split("|");
		   document.getElementById("tschool").value = res[0];
		   document.getElementById("tosis").value = res[1];
		   document.getElementById("tcom").value = res[2];
		   document.getElementById("tpractice").value = res[3];
		   document.getElementById("tfound").value = res[4];
		   calculate_aid();
		}
		})
		return false;
	});
	
	$('#bsid').click(function() {
		
		var value = $("#tstudentsearch").val();
		
		if (!value){ alert('Student Must Be Fiiled..!!');}
		else
		{
			$.ajax({
			type: 'POST',
			url: site +"/tuition_transaction/get_student_id",
			data: "value=" + value,
			success: function(data)
			{
			   res = data.split("|");
			   document.getElementById("tid").value = res[0]; // sid
			   document.getElementById("tnis").value = res[1]; // nisn
			   document.getElementById("tstudentsearch").value = res[2]; // name
			   
			   document.getElementById("tdept").value = res[3]; // dept
			   document.getElementById("tgrade").value = res[4]; // grade
			   document.getElementById("tdeptid").value = res[5]; // deptid
			   document.getElementById("tgradeid").value = res[6]; // gradeid
			   get_fee(res[5],res[6],res[0]);
			   get_receipt(res[5]);
			}
			})
			return false;
		}
		
	});

/* end document */		
});

function calculate_aid()
{
	
	var p1 = parseFloat($("#tschool").val());
	var p2 = parseFloat($("#tosis").val());
	var p3 = parseFloat($("#tcom").val());
	var p4 = parseFloat($("#tpractice").val());
	var p5 = parseFloat($("#tcost").val());
	var res = p1+p2+p3+p4+p5;
	
	var bos = parseFloat($("#tbos").val());
	var found = parseFloat($("#tfound").val());
	var aid = bos+found;
	
    document.getElementById("ttotal").value = res-aid;
}


function clear()
{
  document.getElementById("tschool").value = '0';
  document.getElementById("tosis").value = '0';
  document.getElementById("tcom").value = '0';
  document.getElementById("tpractice").value = '0';
  document.getElementById("tcost").value = '0';
  document.getElementById("tbos").value = '0';
  document.getElementById("tfound").value = '0';
  document.getElementById("ttotal").value = '0';
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

<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> AR - Tuition Transaction </legend>
	    <form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
			
		   <table>   
             
           <tr> <td> <label for="tvalue"> Note </label> </td> <td>:</td>
           <td> <input type="text" name="tnotes" size="35" /> </td>
           </tr>  
                   
           <tr> <td> <label for="tvalue"> Student </label> </td> <td>:</td> 
           <td> <input type="text" id="tstudentsearch" name="tvalue" size="27" /> - 
                <input type="button" id="bsid" value="ID" /> </td> </tr>  
           <tr> <td></td> <td></td> <td> <input type="text" readonly="readonly" id="tid" name="tid" size="3" /> - 
           <input type="text" readonly="readonly" id="tnis" name="tnis" size="15" /> </td> </tr>         
           
          <tr> <td> <label for="tvalue"> Department </label> </td> <td>:</td> 
          <td> <input type="text" readonly="readonly" id="tdept" name="tdept" size="8" /> 
               <input type="hidden" id="tdeptid" name="tdeptid" />
          </td>
          </tr>    
           
          <tr> <td> <label for="tvalue"> Grade </label> </td> <td>:</td> 
          <td> <input type="text" name="tgrade" id="tgrade" size="8" readonly="readonly" />
               <input type="hidden" id="tgradeid" name="tgradeid" />
          </td>
          </tr>      
                     
          <tr>                 
          <td> <label for="tdesc"> Fee Type </label> </td> <td>:</td> 
          <td> <input type="text" name="tfee" id="tfee" size="27" readonly="readonly" />
               <input type="hidden" name="tfeeid" id="tfeeid" /> - 
               <input type="button" id="bgetfee" value="GET" /> </td> 
          </tr> 
                              
          <tr> <td> <label for="tvalue"> Currency </label> </td> <td>:</td> 
          <td> <?php $js = 'id="ccur"'; echo form_dropdown('ccur', $currency, isset($default['currency']) ? $default['currency'] : '', $js); ?> </td>
          </tr> 
          
          <tr> <td> <label for="cyear"> Academic Year </label> </td> <td>:</td> 
          <td> <input type="text" name="cyear" size="10" readonly="readonly" value="<?php echo $year; ?>" /> </td>
          </tr> 
            
                  
            <tr>                 
            <td> <label for="tdesc"> Payment Date </label> </td> <td>:</td> 
            <td> <input type="Text" name="tdate" id="d1" title="Start date" size="10" class="form_field" /> 
            <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> </td> 
            </tr> 
                    
            <tr>                 
            <td> <label for="tdesc"> Receipt Type </label> </td> <td>:</td> 
            <td> <div id="receiptbox"> </div> </td> 
            </tr> 
                    
            <tr>                 
            <td> <label for="tdesc"> Periode </label> </td> <td>:</td> 
            <td> <input type="text" id="tperiod" readonly="readonly" name="tperiod" size="2" title="Periode" /> / 
                 <input type="text" id="tperiodmonth" readonly="readonly" size="3" title="Periode" />
                 <!--<input type="button" id="bperiod" value="GET" />-->
            </td> 
            </tr> 
                     
            <tr>                 
            <td> <label for="tdesc"> School </label> </td> <td>:</td> 
<td> <input type="text" id="tschool" name="tschool" readonly="readonly" size="10" title="School Costs" 
value="<?php echo set_value('tschool', isset($default['school']) ? $default['school'] : '0'); ?>" onKeyUp="checkdigit(this.value,'tschool'); calculate_aid()" /> </td>
            </tr> 
                    
                    <tr>                 
                    <td> <label for="tdesc"> OSIS </label> </td> <td>:</td> 
<td> <input type="text" id="tosis" name="tosis" readonly="readonly" size="10" title="OSIS Costs" 
value="<?php echo set_value('tosis', isset($default['osis']) ? $default['osis'] : '0'); ?>" onKeyUp="checkdigit(this.value,'tosis')" /> </td>
					</tr> 
                    
                    <tr>                 
                    <td> <label for="tdesc"> Computer </label> </td> <td>:</td> 
<td> <input type="text" id="tcom" readonly="readonly" name="tcom" size="10" title="Computer Costs" 
value="<?php echo set_value('tcom', isset($default['com']) ? $default['com'] : '0'); ?>" onKeyUp="checkdigit(this.value,'tcom')" /> </td>
					</tr> 
                    
                    <tr>                 
                    <td> <label for="tdesc"> Practice </label> </td> <td>:</td> 
<td> <input type="text" id="tpractice" name="tpractice" readonly="readonly" size="10" title="Practice Costs" 
value="<?php echo set_value('tpractice', isset($default['practice']) ? $default['practice'] : '0'); ?>" 
onKeyUp="checkdigit(this.value,'tpractice'); calculate_aid()" /> </td>
					</tr> 
                    
                    <tr>                 
                    <td> <label for="tcost"> Other Cost </label> </td> <td>:</td> 
<td> <input type="text" id="tcost" name="tcost" size="10" title="Other Costs" 
value="<?php echo set_value('tcost', isset($default['cost']) ? $default['cost'] : '0'); ?>" onKeyUp="checkdigit(this.value,'tcost'); calculate_aid()" /> </td>
					</tr> 
                    
                    <tr>                 
                    <td> <label for="tdesc"> Aid (BOS) </label> </td> <td>:</td> 
<td> <input type="text" id="tbos" name="tbos" size="10" title="Aid - BOS" 
value="<?php echo set_value('tbos', isset($default['bos']) ? $default['bos'] : '0'); ?>" onKeyUp="checkdigit(this.value,'tbos'); calculate_aid()" /> </td> </tr> 

					 <tr>                 
                    <td> <label for="tdesc"> Aid (Foundation) </label> </td> <td>:</td> 
<td> <input type="text" id="tfound" name="tfound" size="10" readonly="readonly" title="Aid - Foundation" 
value="<?php echo set_value('tfound', isset($default['found']) ? $default['found'] : '0'); ?>" onKeyUp="checkdigit(this.value,'tfound'); calculate_aid()" /> &nbsp; </td>
					</tr> 
                    
                    <tr>                 
                    <td> <label for="tdesc"> <b> Total </b> </label> </td> <td>:</td> 
<td> <input type="text" id="ttotal" readonly="readonly" name="ttotal" size="10" title="Total" 
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

