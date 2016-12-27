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
	
	function get_fee_type(dept,grade)
	{
		$.ajax({
		type: 'POST',
		url: uri +'get_fee_type/value',
		data: "cdept="+ dept + "&cgrade=" + grade,
		success: function(data)
		{
			document.getElementById("feebox").innerHTML = data;
		}
		})
		return false;
	}
	
	// get deptid and grade students
	
	$('#bgetperiod').click(function() {
		
		var sid = $("#tsid").val();
		var year = $("#cyear").val();
		var fee = $("#cfee").val();
		
		$.ajax({
		type: 'POST',
		url: site +'/mutation/get_miss_payment',
		data: "sid="+ sid + "&year=" + year,
		success: function(data)
		{
		   document.getElementById("tperiod").value = data;
		   get_amount(fee,data);
		}
		})
		return false;
	});
	
	function get_amount(fee,period)
	{
		$.ajax({
		type: 'POST',
		url: site +'/mutation/calculate_mutation',
		data: "fee="+ fee + "&period=" + period,
		success: function(data)
		{
		  document.getElementById("tamount").value = data;
		}
		})
		return false;
	}
	
	$('#cyear').change(function() {
	   document.getElementById("tperiod").value = '';
	   document.getElementById("tamount").value = '';
	});
	
	$('#tstudentsearch').keyup(function() {
		document.getElementById("tid").value = '';
		document.getElementById("tnis").value = '';
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
          
      <tr>	
	  <td> <label for="tname"> Currency </label> </td> <td>:</td>
	  <td> <?php $js = 'id=""'; echo form_dropdown('ccurrency', $currency, isset($default['currency']) ? $default['currency'] : '', $js); ?> </td>
	  </tr>  
      
      <tr> <td> <label for="cacc"> Account </label> </td> <td>:</td> 
      <td>  
			<select name="cacc" class="required">
<option value="bank" <?php echo set_select('cacc', 'bank', isset($default['acc']) && $default['acc'] == 'bank' ? TRUE : FALSE); ?> /> Bank </option>
<option value="cash" <?php echo set_select('cacc', 'cash', isset($default['acc']) && $default['acc'] == 'cash' ? TRUE : FALSE); ?> /> Cash </option>
<option value="pettycash" <?php echo set_select('cacc', 'pettycash', isset($default['acc']) && $default['acc'] == 'pettycash' ? TRUE : FALSE); ?> /> Petty Cash </option>
			</select>
      </td> </tr>  
      
      <tr>	
	  <td> <label for="tname"> Financial Year </label> </td> <td>:</td>
	  <td> <?php $js = 'id="cyear"'; echo form_dropdown('cyear', $year, isset($default['year']) ? $default['year'] : '', $js); ?></td>
	  </tr>  
                				 
      <tr> 
      <td> <label for="ctype"> Type </label> </td> <td>:</td>
      <td> <?php $js = 'class="required"'; echo form_dropdown('ctype', $type, isset($default['type']) ? $default['type'] : '', $js); ?> &nbsp;
      </tr>
                    
      <tr>	
      <td> <label for="tdate"> Date </label> </td> <td>:</td>
      <td> <input type="Text" name="tdate" id="d1" title="Invoice date" size="10" class="required" 
            value="<?php echo set_value('tdate', isset($default['date']) ? $default['date'] : ''); ?>" /> 
           <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/>
      </td>
      </tr>
          
      <tr>
      <td> <label for="tstudent"> Student </label> </td>  <td>:</td>
      <td> <input type="text" id="tstudentsearch" name="tvalue" readonly="readonly" size="35"
           value="<?php echo set_value('tvalue', isset($default['studentname']) ? $default['studentname'] : ''); ?>" /> - 
           <input type="sid" id="tsid" name="tsid" readonly="readonly" size="2"
           value="<?php echo set_value('tsid', isset($default['sid']) ? $default['sid'] : ''); ?>" /> </td>
      </tr>
      
      <tr>
      <td> <label for="tdept"> Dept / Grade </label> </td>  <td>:</td>
      <td> <input type="text" readonly="readonly" id="tdept" name="tdept" size="6"
           value="<?php echo set_value('tdept', isset($default['dept']) ? $default['dept'] : ''); ?>" /> - 
           <input type="text" readonly="readonly" name="tgrade" id="tgrade" size="9"
           value="<?php echo set_value('tgrade', isset($default['grade']) ? $default['grade'] : ''); ?>" /> </td>
      </tr>
					
      <tr>
      <td> <label for="tdocno"> Teacher / Guardian </label> </td>  <td>:</td>
      <td> <input type="text" class="" name="tteacher" size="20" title="Teacher"
           value="<?php echo set_value('tteacher', isset($default['teacher']) ? $default['teacher'] : ''); ?>" /> </td>
      </tr>
					
      <tr>
      <td> <label for="tnote"> Note </label> </td>  <td>:</td>
      <td> <input type="text" class="required" name="tnote" size="45" title="Note"
           value="<?php echo set_value('tnote', isset($default['note']) ? $default['note'] : ''); ?>" /> </td>
      </tr>
      
      <tr> 
      <td> <label for="cfee"> Fee Type </label> </td> <td>:</td>
      <td> <?php $js = 'id="cfee"'; echo form_dropdown('cfee', $fee, isset($default['fee']) ? $default['fee'] : '', $js); ?> </td>
      </tr>
      
      <tr> <td> <label for="cstts"> Payment </label> </td> <td>:</td> 
      <td>  
			<select name="cstts" class="required">
 <option value="0" <?php echo set_select('cstts', '0', isset($default['stts']) && $default['stts'] == '0' ? TRUE : FALSE); ?> /> Credit </option>
 <option value="1" <?php echo set_select('cstts', '1', isset($default['stts']) && $default['stts'] == '1' ? TRUE : FALSE); ?> /> Settled </option>
			</select>
      </td> </tr>  
      
      <tr>
      <td> <label for="tnote"> Period (Month) </label> </td>  <td>:</td>
      <td> <input type="text" class="required" readonly="readonly" name="tperiod" id="tperiod" size="1" title="Period"
           value="<?php echo set_value('tperiod', isset($default['period']) ? $default['period'] : ''); ?>" />
           <input type="button" id="bgetperiod" value="GET" />
      </td>
      </tr>
      
      <tr>
      <td> <label for="tamount"> Amount </label> </td> <td>:</td>
      <td> <input type="text" class="required" id="tamount" name="tamount" size="10" title="Amount"
           value="<?php echo set_value('tamount', isset($default['amount']) ? $default['amount'] : ''); ?>" />
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

