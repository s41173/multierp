<script type="text/javascript" src="<?php echo base_url();?>public/javascripts/FusionCharts.js"></script>

<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";
var site = "<?php echo site_url();?>";
</script>

<script type="text/javascript">
	
$(document).ready(function(){
		
	$('#cdept,#cgrade').change(function() {
		
		var dept = $("#cdept").val();
		var grade = $("#cgrade").val();	
		
		$.ajax({
		type: 'POST',
		url: uri +'get_fee_type',
		data: "cdept="+ dept + "&cgrade=" + grade,
		success: function(data)
		{
			document.getElementById("feebox").innerHTML = data;
		}
		})
		return false;
		
	});
	
	
/* end document */		
});

</script>
	
<?php 

$atts = array(
		  'class'      => 'refresh',
		  'title'      => 'add po',
		  'width'      => '800',
		  'height'     => '600',
		  'scrollbars' => 'no',
		  'status'     => 'yes',
		  'resizable'  => 'yes',
		  'screenx'    =>  '\'+((parseInt(screen.width) - 800)/2)+\'',
		  'screeny'    =>  '\'+((parseInt(screen.height) - 600)/2)+\'',
		);
		
$atts1 = array(
	  'class'      => 'fancy',
	  'title'      => '',
	  'width'      => '550',
	  'height'     => '600',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 550)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 600)/2)+\'',
);

$atts2 = array(
	  'class'      => 'fancy',
	  'title'      => '',
	  'width'      => '600',
	  'height'     => '350',
	  'scrollbars' => 'no',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 350)/2)+\'',
);

?>

<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Tuition Fee - Transaction </legend>
	<form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
					
					<td> <label for=""> Date </label> <br />
					     <input type="Text" readonly="readonly" name="tdate" id="d1" title="Start date" size="10" class="form_field" /> 
				         <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> &nbsp;
					</td> 
                    
         <td> <label for="cdept"> Department / Grade : </label> <br />
         <?php $js = 'id="cdept"'; echo form_dropdown('cdept', $dept, isset($default['dept']) ? $default['dept'] : '', $js); ?> -
         <select id="cgrade" name="cgrade"> <option value=""> -- All -- </option> <option value="1">I</option> <option value="2">II</option> 
         <option value="3">III</option> </select> 
         <br /> 
         </td>
                
                <td> <label for="cfee"> Fee Type : </label> <br />
		        <div style="float:left; margin-right:5px;" id="feebox"> <br /> </td>
                
                <td> <label> Type : </label> <br />
                      <select name="cptype"> 
                      <option value="" selected="selected"> -- </option>
                      <option value="0"> B </option> 
                      <option value="1"> N </option> 
                      <option value="2"> F </option> 
                      </select> &nbsp;
                </td>
                    
                <td> <label for="tvalue"> Field : </label> <br />
                     <select name="ctype"> <option value="0"> NISN </option> <option selected="selected" value="1"> Name </option> </select>		
	            </td>
                
                 <td> <label for="tvalue"> </label> <br />
	             <input type="text" id="tstudentsearch" name="tvalue" size="30" /> 
                 </td>
                 	
					<td colspan="3" align="right">  <br />
					<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value="Search" /> 
					</td>
					
					</tr> 
				</table>	
			</form>			  
	</fieldset>
</div>


<div id="webadmin2">
	
	<form name="search_form" class="myform" method="post" action="<?php echo ! empty($form_action_del) ? $form_action_del : ''; ?>">
     <?php echo ! empty($table) ? $table : ''; ?>
	 <div class="paging"> <?php echo ! empty($pagination) ? $pagination : ''; ?> </div>
	</form>	
	
	<table align="right" style="margin:10px 0px 0 0; padding:3px; " width="100%" bgcolor="#D9EBF5">
	<tbody>
		<tr> 
		   <td align="right"> 
		   <?php echo anchor(site_url("tuition_transaction/add"), 'CREATE PAYMENT', $atts1); ?>
           <?php echo anchor(site_url("tuition_transaction/report"), 'TRANSACTION REPORT', $atts2); ?>
		   </td> 
		</tr>
	</tbody>
	</table>
	
	<div class="clear"></div> <br />
		
	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>

	
</div>

