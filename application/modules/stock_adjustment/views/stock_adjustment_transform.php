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
	function refreshparent() { opener.location.reload(true); }
	
	$(document).ready(function(){
			
		$('#ajaxform,#ajaxform2').submit(function() {
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
		
		$('#ctype').change(function() {
			
			var type = $("#ctype").val();
			
			if (type == 'out'){  document.getElementById("tamount").readOnly = true;	}
			else{ document.getElementById("tamount").readOnly = false; }
			
		});
		
	/* end document */		
	});
	
</script>

<style>
        .refresh{ border:1px solid #AAAAAA; color:#000; padding:2px 5px 2px 5px; margin:0px 2px 0px 2px; background-color:#FFF;}
		.refresh:hover{ background-color:#CCCCCC; color: #FF0000;}
		.refresh:visited{ background-color:#FFF; color: #000000;}	
		#warning{ margin:10px 0 0 5px; padding:0; float:left; font-size:11px; font-family:Tahoma; color:#FF0000;}
</style>

<?php 
		
$atts1 = array(
	  'class'      => 'refresh',
	  'title'      => 'add cust',
	  'width'      => '600',
	  'height'     => '500',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 500)/2)+\'',
);

?>

<body onUnload="refreshparent();">  
<div id="webadmin">
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field" style="float:left;"> <legend> Stock Adjustment </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>
					
					<tr>	
						<td> <label for="tno"> No - BPBG-00 </label> </td> <td>:</td>
	     <td> <input type="text" class="required" readonly name="tno" size="4" title="Name" value="<?php echo isset($code) ? $code : ''; ?>" /> &nbsp; <br /> </td>
					</tr>
					
					<tr>	
						 <td> <label for="tdate"> Date </label> </td> <td>:</td>
						 <td>  
						   <input type="Text" name="tdate" id="d1" title="Invoice date" size="10" class="required"
						   value="<?php echo set_value('tdate', isset($default['date']) ? $default['date'] : ''); ?>" /> 
				           <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> &nbsp; <br />
						</td>
					</tr>
					
					<tr>
						<td> <label for="tcurrency"> Currency </label> </td>  <td>:</td>
						<td>  <input type="text" readonly name="tcurrency" size="10" title="Currency"
						value="<?php echo set_value('tcurrency', isset($default['currency']) ? $default['currency'] : ''); ?>" /> &nbsp; <br /> </td>
					</tr>
					
					
					<tr>
						<td> <label for="tnote"> Note </label> </td>  <td>:</td>
						<td>  <input type="text" class="required" name="tnote" size="56" title="Note"
						value="<?php echo set_value('tnote', isset($default['note']) ? $default['note'] : ''); ?>" /> &nbsp; <br /> </td>
					</tr>
					
					<tr>
						<td> <label for="tstaff"> Workshop Staff </label> </td>  <td>:</td>
						<td>  <input type="text" class="required" name="tstaff" size="20" title="Staff"
						      value="<?php echo set_value('tstaff', isset($default['staff']) ? $default['staff'] : ''); ?>" /> &nbsp; <br /> </td>
					</tr>
					

					<tr>	
						<td> <label for="tuser"> Warehouse Dept </label> </td> <td>:</td>
	     <td> <input type="text" class="required" readonly name="tuser" size="15" title="User" value="<?php echo isset($user) ? $user : ''; ?>" /> &nbsp; <br /> </td>
					</tr>
					   
				</table>  
	</fieldset>  	
	
	<p style="margin:10px 0 0 10px; float:left;">
		<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> 
		<input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " />
	</p>	
	</form>		
	
	<div class="clear"></div>
	
	<fieldset class="field"> <legend> Item Transaction </legend>
	<form name="modul_form" class="myform" id="ajaxform2" method="post" action="<?php echo $form_action_item; ?>">
		<table>
			<tr>				
				<td> <label for="tproduct"> Product </label>  <br />
				     <input type="text" class="required" readonly name="tproduct" id="tproduct" size="30" title="Name" /> &nbsp;
				     <?php echo anchor_popup(site_url("product/get_list/".$default['currency'].'/'), '[ ... ]', $atts1); ?> &nbsp; </td>
				
                <td>  
					<label for="ctype"> Type : </label> <br />
					<select name="ctype" id="ctype">
                    <option value="in">IN</option>
                    <option value="out">OUT</option>
                    </select> &nbsp;
				</td>
                
				<td>  
					<label for="tqty"> Qty : </label> <br />
					<input type="text" name="tqty" id="tqty" size="4" title="Qty" onKeyUp="checkdigit(this.value, 'tqty')" /> &nbsp;
				</td>
                
                <td>  
					<label for="tamount"> Amount : </label> <br />
					<input type="text" name="tamount" id="tamount" size="10" title="Amount" onKeyUp="checkdigit(this.value, 'tamount')" /> &nbsp;
				</td>
                
                <td> <label for="titem"> Account </label>  <br />
				     <input type="text" class="required" readonly name="titem" id="titem" size="10" title="Name" />
				     <?php echo anchor_popup(site_url("accountc/get_list/"), '[ ... ]', $atts1); ?> &nbsp;
                </td>
                				
				<td> <br />
					<input type="submit" name="submit" class="button" title="POST" value="POST" /> 
				</td>
			</tr>
		</table>
		
		<div class="clear"></div>
		<?php echo ! empty($table) ? $table : ''; ?>
		
		<!--<p id="warning"> Warning : Issued Item Quantity Must Be <strong>" NEGATIVE "</strong> </p>-->
		
	</form>
	</fieldset>
	
</div>

</body>