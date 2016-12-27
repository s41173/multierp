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
			get_scholarship(res[2],res[3],res[4]);
		}
	});	
	
	function get_scholarship(dept,grade,sid)
	{
		$.ajax({
		type: 'POST',
		url: site+'/scholarship/get_combo',
		data: "cdept="+ dept + "&cgrade=" + grade,
		success: function(data)
		{
			document.getElementById("feebox").innerHTML = data;
		}
		})
		return false;
	}
	
	// get deptid and grade students
	
	$('#tstudentsearch').keyup(function() {
		document.getElementById("tid").value = '';
		document.getElementById("tnis").value = '';
	});
	
	$('#bget').click(function() {
		
		var sc = $("#cscholarship").val();
		var date = $("#d1").val();
		var sid = $("#tsid").val();
		
		if (!date){ alert('Date must filled...!!');}
		else
		{
			$.ajax({
			type: 'POST',
			url: site+'/scholarship/get_period',
			data: "cscholar="+ sc + "&tdate=" + date + "&sid=" + sid,
			success: function(data)
			{
				res = data.split("|"); 
				document.getElementById("tmonth").value = res[0];
				document.getElementById("tstart").value = res[1];
				document.getElementById("tarmonth").value = res[2];
				document.getElementById("startmonth").value = res[3];
			}
			})
			return false;
		}
	});
	
	// get end  - until
	
	$('#bgetend').click(function() {
		
		var request   = parseInt($("#tmonth").val());
		var masaaktif = parseInt($("#tarmonth").val());
		var start     = parseInt($("#tstart").val());
		var financial = $("#tfinanceyear").val();
		var res = 0;
		
		if (!request){ alert('Scholarship must filled...!!');}
		else
		{
			if (request >= masaaktif){ res = masaaktif; }else { res = request; }
			
			$.ajax({
			type: 'POST',
			url: site+'/scholarship/get_end_month/',
			data: "end="+ res + "&tstart=" + start + "&tfinancial=" + financial,
			success: function(data)
			{
//				var until = res + start;
				result = data.split("|"); 
				document.getElementById("tend").value = result[0];
				document.getElementById("tuntilmonth").value = result[1];
			}
			})
			return false;
			
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
<body onLoad="cek_session();" onUnload="window.opener.location.reload(true);">

<div id="webadmin">
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Scholarship - Transaction </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>" >
				<table>
     
      <tr>
      <td> <label for="tfinanceyear"> Financial Year </label> </td> <td>:</td>
      <td> <input type="text" id="tfinanceyear" name="tfinanceyear" readonly size="8" 
           value="<?php echo set_value('tfinanceyear', isset($default['financial']) ? $default['financial'] : ''); ?>" /> </td>
      </tr>
     
      <tr>
      <td> <label for="tstudent"> Student </label> </td> <td>:</td>
      <td> <input type="text" id="tstudentsearch" name="tvalue" size="35" /> - 
           <input type="text" id="tsid" name="tsid" readonly size="2" /> </td>
      </tr>   
      
      <tr>	
      <td> <label for="tdate"> Date </label> </td> <td>:</td>
      <td> <input type="Text" readonly name="tdate" id="d1" title="Invoice date" size="10" class="required" /> 
           <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/>
      </td>
      </tr>
                				 
      <tr> 
      <td> <label for="ctype"> Scholarship </label> </td> <td>:</td>
      <td> <div style="float:left;" id="feebox"> </div> &nbsp; 
           <input type="text" name="tmonth" id="tmonth" size="1"> 
           <input type="button" id="bget" name="GET" value="GET"> </td>
      </tr>
      
      <tr> 
      <td> <label for="ctype"> Receivable (Month) </label> </td> <td>:</td>
      <td> <input type="text" name="tarmonth" id="tarmonth" size="1" readonly>  </td>
      </tr>
      
      <tr> 
      <td> <label for="ctype"> Start (Month) </label> </td> <td>:</td>
      <td> <input type="text" name="tstart" id="tstart" size="1" readonly> - <input type="text" id="startmonth" size="10" readonly> </td>
      </tr>
                               
      <tr>
      <td> <label for="tdept"> Dept / Grade </label> </td> <td>:</td>
      <td> <input type="text" readonly id="tdept" name="tdept" size="6" /> - 
           <input type="text" readonly name="tgrade" id="tgrade" size="9" /> 
      </td>
      </tr>
					
      <tr>
      <td> <label for="tnote"> Note </label> </td> <td>:</td>
      <td> <input type="text" class="required" id="tnote" name="tnote" size="42" title="Note" /> </td>
      </tr> 
      
      <tr>
      <td> <label for="tdept"> Until (Period) </label> </td> <td>:</td>
      <td> <input type="text" name="tend" id="tend" size="1" readonly> /
           <input type="text" id="tuntilmonth" name="tuntilmonth" readonly size="10" /> - &nbsp;
           <input type="button" id="bgetend" value="GET END">
      </td>
      </tr>
      			  
        </table>
        <p style="margin:15px 0 0 0; float:right;">
            <input type="submit" name="submit" class="button" title="" value="Save" /> 
            <input type="reset" name="reset" class="button" title="" value="Cancel" />
        </p>	
    </form>			  
	</fieldset>
</div>
</body>

