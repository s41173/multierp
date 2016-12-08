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
		serviceUrl: site+'/tuition_transaction/autocomplete',
		// fungsi ini akan dijalankan ketika user memilih salah satu hasil request
		onSelect: function (suggestion) {
			 //alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
			res = suggestion.data.split("|"); 
			document.getElementById("tdept").value = res[2];
			document.getElementById("tgrade").value = res[3]; 
			document.getElementById("tsid").value = res[0]; 
			get_fee(res[4],res[5]);
		}
	});	
	
	/*$('#tstudentsearch').autocomplete({
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
			get_fee(res[4],res[5]);
		}
	});	*/
	
	// School Fee Type
	function get_fee(dept,grade)
	{		
		$.ajax({
		type: 'POST',
		url: site +"/ajax/get_fee_type/val",
		data: "cdept="+ dept + "&cgrade=" + grade,
		success: function(data)
		{
			document.getElementById("feebox").innerHTML = data;
		}
		})
		return false;
	}
	
	
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
	
	<fieldset class="field"> <legend> Tuition Over - Transaction </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>" >
				
      <table>
       
      <tr> <td> <label for="ctype"> Over Payment Type </label> </td> <td>:</td> 
      <td> <?php $js = 'id="ctype"'; echo form_dropdown('ctype', $type, isset($default['type']) ? $default['type'] : '', $js); ?> </td> </tr>     
                    
      <tr>	
      <td> <label for="tdate"> Date </label> </td> <td>:</td>
      <td> <input type="Text" name="tdate" id="d1" readonly title="Invoice date" size="10" class="required" /> 
           <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/>
      </td>
      </tr>
          
      <tr>
      <td> <label for="tstudent"> Student </label> </td>  <td>:</td>
      <td> <input type="text" id="tstudentsearch" name="tvalue" size="30" /> - 
           <input type="sid" id="tsid" name="tsid" readonly size="2" /> </td>
      </tr>
      
      <tr>
      <td> <label for="tdept"> Dept / Grade </label> </td>  <td>:</td>
      <td> <input type="text" readonly id="tdept" name="tdept" size="6" /> - 
           <input type="text" readonly name="tgrade" id="tgrade" size="9" /> </td>
      </tr>
      
      <tr> 
      <td> <label for="cfee"> Fee Type </label> </td> <td>:</td>
      <td> <div id="feebox"></div> </td>
      </tr>
					
      <tr>
      <td> <label for="tnote"> Note </label> </td>  <td>:</td>
      <td> <textarea name="tnote" cols="45" rows="3"></textarea> </td>
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

