<script type="text/javascript" src="<?php echo base_url();?>public/javascripts/FusionCharts.js"></script>
	
<?php 

$atts = array(
		  'class'      => 'refresh',
		  'title'      => 'add po',
		  'width'      => '800',
		  'height'     => '600',
		  'scrollbars' => 'yes',
		  'status'     => 'yes',
		  'resizable'  => 'yes',
		  'screenx'    =>  '\'+((parseInt(screen.width) - 800)/2)+\'',
		  'screeny'    =>  '\'+((parseInt(screen.height) - 600)/2)+\'',
		);
		
$atts1 = array(
	  'class'      => 'refresh',
	  'title'      => 'Purchase Invoice',
	  'width'      => '600',
	  'height'     => '400',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 400)/2)+\'',
);

$atts2 = array(
	  'class'      => 'refresh',
	  'title'      => 'Purchase Report',
	  'width'      => '550',
	  'height'     => '350',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 550)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 350)/2)+\'',
);

?>

<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Sales Order </legend>
	<form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
					
					<td> <label for="tname">NO : SO-00</label> <br /> <input type="text" class="" id="tno" name="tno" size="8" title="No" 
					value="<?php echo set_value('tno', isset($default['no']) ? $default['no'] : ''); ?>" onkeyup="checkdigit(this.value, 'tno')" /> &nbsp; &nbsp; </td> 
					
					<td> <label for="tname"> Cust </label> <br /> 
					    <input type="text" readonly="readonly" name="tcust" id="tcust" size="25" title="Customer" /> 
				<!--		<a class="refresh" id="tombol" href="<?php //echo site_url("vendor/get_list/"); ?>"> [ ... ] </a> -->
						<?php echo anchor_popup(site_url("customer/get_list/"), '[ ... ]', $atts1); ?> &nbsp; &nbsp;
					</td> 
					
					<td> <label for=""> Date </label> <br />
					     <input type="Text" name="tdate" id="d1" title="Start date" size="10" class="form_field" /> 
				         <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> &nbsp; &nbsp;
					</td> 
					
					<td colspan="3" align="right">  <br />
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
		   <?php echo anchor_popup(site_url("sales/add"), 'CREATE NEW SO', $atts); ?>
		   <?php echo anchor(site_url("ar_payment"), 'AR - PAYMENT', $atts); ?>
		   <?php echo anchor_popup(site_url("sales/report"), 'SALES REPORT', $atts2); ?>
		   </td> 
		</tr>
	</tbody>
	</table>
	
	<div class="clear"></div> <br />
	
	<fieldset class="field"> <legend> Sales Order Chart </legend>
		
		<form name="search_form" class="myform" method="post" action="<?php echo ! empty($form_action_graph) ? $form_action_graph : ''; ?>">
			<table>
				<tr> <td> <label for="tname"> Currency : </label> </td> 
				     <td> <?php $js = 'class="required"'; echo form_dropdown('ccurrency', $currency, isset($default['currency']) ? $default['currency'] : '', $js); ?> </td> 
					 <td> <input type="submit" class="button" value="SUBMIT" /> </td>
			    </tr>
			</table>
		</form> <br />
		
		<?php  echo ! empty($graph) ? $graph : '';  ?>
	
	</fieldset>

		
	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>

	
</div>

