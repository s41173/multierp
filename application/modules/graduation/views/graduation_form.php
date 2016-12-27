<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'development-bundle/themes/base/ui.all.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/jquery.fancybox-1.3.4.css'; ?>");</style>

<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.autocomplete.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/register.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/datetimepicker_css.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/sortir.js"></script>


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
	
	$('#cdept2').change(function() {

	   $.ajax({
		type: 'POST',
		url: uri +'get_faculty_id/null/cfacultys2',
		data: $(this).serialize(),
		success: function(data)
		{
		   document.getElementById("facultybox2").innerHTML = data;
		}
		})
		return false;
	   
	});
			
	$('#bget').click(function() {

	   var dept = $("#cdept").val();
	   var faculty = $("#cfacultys").val();
	   
	   $.ajax({
		type: 'POST',
		url: uri +'get_grade/val',
		data: "dept="+ dept + "&faculty=" + faculty,
		success: function(data)
		{
		   document.getElementById("gradebox").innerHTML = data;
		}
		})
		return false;
	   
	});
	
	$('#bget2').click(function() {

	   var dept = $("#cdept2").val();
	   var faculty = $("#cfacultys2").val();
	   
	   $.ajax({
		type: 'POST',
		url: uri +'get_grade/val',
		data: "dept="+ dept + "&faculty=" + faculty,
		success: function(data)
		{
		   document.getElementById("gradebox2").innerHTML = data;
		}
		})
		return false;
	   
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
	
	<fieldset class="field"> <legend> Create - Graduation Student </legend>
	    <form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>">
			
		 <table>
         
         <tr> 
         <td> <label for="cdept"> Financial Year </label> </td> <td>:</td> 
         <td> <input type="text" size="12" readonly value="<?php echo $financialyear; ?>"> </td>
         </tr>
         
         <tr> 
         <td> <label for="cdept"> Generation </label> </td> <td>:</td> <td> <input type="text" size="4" readonly value="<?php echo $generation; ?>"> </td>
         </tr>
         
         <tr> 					
         <td> <label for=""> Date : </label> </td> <td>:</td>
         <td>
              <input type="Text" name="tdate" id="d1" title="Start date" size="10" class="form_field" value="<?php echo isset($dates) ? $dates : ''; ?>" /> 
              <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/>
         </td> 
         </tr>
         
         <tr>
         <td> <label for="cdept"> Department </label> </td> <td>:</td> 
         <td> <?php $js = 'id="cdept"'; echo form_dropdown('cdept', $dept, isset($default['dept']) ? $default['dept'] : '', $js); ?>  </td>
         </tr>
            
         <tr>
         <td> <label for="cfaculty"> Faculty </label> </td> <td>:</td>
         <td> <div id="facultybox" style="float:left; margin-right:5px;"></div> <input type="button" id="bget" value="GET" /> </td>
         </tr>  
         
         <tr>
         <td> <label for="cfaculty"> Grade </label> </td> <td>:</td>
         <td> <div id="gradebox"  style="float:left; margin-right:5px;"></div> </td>
         </tr>   
         
         <tr>
         <td colspan="2"></td>  <td> <input type="submit" name="submit" value="Search" />  </td>
         </tr>

        </table>	
					
	    </form>			  
	</fieldset>
</div>

<div id="webadmin2">
	    
    <form name="search_form" class="myform" method="post" action="<?php echo ! empty($form_action_select) ? $form_action_select : ''; ?>">
        
     <?php echo ! empty($table) ? $table : ''; ?>
	 <div class="paging"> <?php echo ! empty($pagination) ? $pagination : ''; ?> </div>
	 <p class="cek"> <?php echo ! empty($radio1) ? $radio1 : ''; echo ! empty($radio2) ? $radio2 : ''; ?> 
     <input style="margin:0 0 0 5px;" type="submit" name="submit" class="" title="Process Button" value="TRANSFER TO GRADUATION" />
     </p> 
    <!-- Mutation Form -->	
    </form>		  
    <!-- Mutation Form -->

	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>

</div>


</body>
