	
<script type="text/javascript">	
function refreshparent() { opener.location.reload(true); }
</script>
	
<?php 

$atts = array(
		  'class'      => 'refresh',
		  'title'      => 'add po',
		  'width'      => '800',
		  'height'     => '500',
		  'scrollbars' => 'no',
		  'status'     => 'yes',
		  'resizable'  => 'yes',
		  'screenx'    =>  '\'+((parseInt(screen.width) - 800)/2)+\'',
		  'screeny'    =>  '\'+((parseInt(screen.height) - 500)/2)+\'',
		);
		
$atts1 = array(
	  'class'      => 'refresh',
	  'title'      => 'AP Invoice',
	  'width'      => '600',
	  'height'     => '400',
	  'scrollbars' => 'no',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 400)/2)+\'',
);

$atts2 = array(
	  'class'      => 'refresh',
	  'title'      => 'AP - Report',
	  'width'      => '500',
	  'height'     => '400',
	  'scrollbars' => 'no',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 500)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 400)/2)+\'',
);

?>

<body>

<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Account Payable  </legend>
	<form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
					
					<td> <label for="tname">NO : GJ-00 </label> <br /> <input type="text" class="" id="tno" name="tno" size="8" title="No" 
					value="<?php echo set_value('tno', isset($default['no']) ? $default['no'] : ''); ?>" onKeyUp="checkdigit(this.value, 'tno')" /> &nbsp; &nbsp; </td> 
					
					<td> <label for="tname"> Cust : </label> <br>
					    <input type="text" readonly="readonly" name="tcust" id="tcust" size="25" title="Customer" /> 
				<!--		<a class="refresh" id="tombol" href="<?php //echo site_url("vendor/get_list/"); ?>"> [ ... ] </a> -->
						<?php echo anchor_popup(site_url("vendor/get_list/"), '[ ... ]', $atts1); ?> &nbsp; &nbsp;
					</td> 
					
					<td> <label for=""> Date </label> <br>
					     <input type="Text" name="tdate" id="d1" title="Start date" size="10" class="form_field" /> 
				         <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> &nbsp;
					</td> 
					
					<td colspan="3" align="right">  <br>
					<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value="Search" /> 
					<input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " /> 
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
		   <?php echo anchor_popup(site_url("ap/add"), 'CREATE NEW AP', $atts); ?>
		   <?php echo anchor_popup(site_url("ap/report"), 'AP - REPORT', $atts2); ?>
		   </td> 
		</tr>
	</tbody>
	</table>

		
	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>

	
</div>
</body>
