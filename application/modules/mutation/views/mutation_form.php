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
		
		var since = $("#d2").val();
		res = since.split("-");
		var req = parseInt(res[1]);
	
		$.ajax({
		type: 'POST',
		url: site +'/mutation/get_miss_payment',
		data: "sid="+ sid + "&year=" + year + "&request=" + req,
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
<body onLoad="cek_session();" onUnload="window.opener.location.reload(true);">

<div id="webadmin">
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Mutation - Transaction </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>" >
				<table>
          
      <tr>	
	  <td> <label for="tname"> Currency </label> </td> <td>:</td>
	  <td> <?php $js = 'id=""'; echo form_dropdown('ccurrency', $currency, isset($default['currency']) ? $default['currency'] : '', $js); ?></td>
	  </tr>  
      
      <tr> <td> <label for="cacc"> Account </label> </td> <td>:</td> 
      <td>  
			<select name="cacc" class="required">
            <option value="bank" /> Bank </option>
            <option value="cash" /> Cash </option>
            <option value="pettycash" /> Petty Cash </option>
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
      <td> <input type="Text" readonly name="tdate" id="d1" title="Invoice date" size="10" class="required" /> 
           <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/>
      </td>
      </tr>
          
      <tr>
      <td> <label for="tstudent"> Student </label> </td>  <td>:</td>
      <td> <input type="text" id="tstudentsearch" name="tvalue" size="35" /> - 
           <input type="sid" id="tsid" name="tsid" readonly size="2" /> </td>
      </tr>
      
      <tr>
      <td> <label for="tdept"> Dept / Grade </label> </td>  <td>:</td>
      <td> <input type="text" readonly id="tdept" name="tdept" size="6" /> - 
           <input type="text" readonly name="tgrade" id="tgrade" size="9" /> </td>
      </tr>
					
      <tr>
      <td> <label for="tdocno"> Teacher / Guardian </label> </td>  <td>:</td>
      <td> <input type="text" class="" name="tteacher" size="20" title="Teacher" /> </td>
      </tr>
					
      <tr>
      <td> <label for="tnote"> Note </label> </td>  <td>:</td>
      <td> <input type="text" class="required" name="tnote" size="45" title="Note" /> </td>
      </tr>
      
      <tr> 
      <td> <label for="cfee"> Fee Type </label> </td> <td>:</td>
      <td> <div style="float:left; margin-right:10px;" id="feebox"> </div>
      </tr>
      
      <tr> <td> <label for="cstts"> Payment </label> </td> <td>:</td> 
      <td>  
			<select name="cstts" class="required">
            <option value="0" /> Credit </option>
            <option value="1" /> Settled </option>
			</select>
      </td> </tr>  
      
      <tr>	
      <td> <label for="tdate"> Since </label> </td> <td>:</td>
      <td> <input type="Text" readonly name="tsince" id="d2" title="Since date" size="10" class="required" /> 
           <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d2','yyyymmdd')" style="cursor:pointer"/>
      </td>
      </tr>
      
      <tr>
      <td> <label for="tnote"> Period (Month) </label> </td>  <td>:</td>
      <td> <input type="text" class="required" readonly name="tperiod" id="tperiod" size="1" title="Period" />
           <input type="button" id="bgetperiod" value="GET" />
      </td>
      </tr>
      
      <tr>
      <td> <label for="tamount"> Amount </label> </td>  <td>:</td>
      <td> <input type="text" class="required" id="tamount" name="tamount" size="10" title="Amount" />
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
</body>

