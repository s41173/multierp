	
	<script type="text/javascript">	
	function refreshparent() { opener.location.reload(true); }
    </script>
	
<?php 
		
$atts = array(
	  'class'      => 'refresh',
	  'title'      => 'Checkout Invoice',
	  'width'      => '650',
	  'height'     => '300',
	  'scrollbars' => 'no',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 650)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 300)/2)+\'',
);

?>

<body>

<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Check In  </legend>
	<form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
					
					<td> <label for="tname">Check NO : </label> <br>  <input type="text" class="" id="tno" name="tno" size="10" title="No" 
					value="<?php echo set_value('tno', isset($default['no']) ? $default['no'] : ''); ?>" /> &nbsp; &nbsp; </td> 
					
					<td> <label for=""> Period : </label> <br>
					     <input type="Text" name="tstart" id="d1" title="Start date" size="10" class="form_field" /> 
				         <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> &nbsp; - &nbsp;
						 <input type="Text" name="tend" id="d2" title="End date" size="10" class="form_field" /> 
				         <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onClick="javascript:NewCssCal('d2','yyyymmdd')" style="cursor:pointer"/> &nbsp;
					</td> 
					
					<td> <label for=""> Type : </label> <br>
					     <select name="ctype">
						 	<option value="sales" selected="selected"> Sales </option>
							<option value="nsales"> Non Tax - Sales </option>
						 </select> &nbsp;
					</td> 
					
					<td colspan="3" align="right">  <br>
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
		   <?php echo anchor_popup(site_url("checkin/report"), 'CHECKIN REPORT', $atts); ?>
		   </td> 
		</tr>
	</tbody>
	</table>
			
	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>

	
</div>
</body>
