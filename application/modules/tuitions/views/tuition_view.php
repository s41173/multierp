<script type="text/javascript" src="<?php echo base_url();?>public/javascripts/FusionCharts.js"></script>
	
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
	  'class'      => 'refresh',
	  'title'      => 'Purchase Invoice',
	  'width'      => '600',
	  'height'     => '400',
	  'scrollbars' => 'no',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 400)/2)+\'',
);

$atts2 = array(
	  'class'      => 'fancy',
	  'title'      => '',
	  'width'      => '550',
	  'height'     => '300',
	  'scrollbars' => 'no',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 550)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 300)/2)+\'',
);

?>

<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Tuition Fee </legend>
	<form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
					
					<td> <label for=""> Date </label> <br />
					     <input type="Text" name="tdate" id="d1" title="Start date" size="10" class="form_field" /> 
				         <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> &nbsp; &nbsp;
					</td> 
					
					<td colspan="3" align="right">  <br />
					<input type="submit" name="submit" class="button" title="" value="Search" /> 
					<input type="reset" name="reset" class="button" title="" value=" Cancel " /> 
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
		   <?php echo anchor(site_url("tuition_transaction"), 'TRANSACTION', $atts); ?>
		   <?php echo anchor(site_url("tuitions/report"), 'REPORT', $atts2); ?>
           <?php echo anchor(site_url("tuitions/void_report"), 'VOID REPORT', $atts2); ?>
		   </td> 
		</tr>
	</tbody>
	</table>
	
	<div class="clear"></div> <br />
	
	<fieldset class="field"> <legend> C-Sales Order Chart </legend>
		
		<form name="search_form" class="myform" method="post" action="<?php echo ! empty($form_action_graph) ? $form_action_graph : ''; ?>">
			<table>
				<tr> <td> <label for="tname"> Currency : </label> <br /> <?php $js = 'class=""'; echo form_dropdown('ccurrency', $currency, isset($default['currency']) ? $default['currency'] : '', $js); ?> </td> 
				     <td> <label for="tname"> Financial Year : </label> <br /> <?php $js = 'class=""'; echo form_dropdown('cyear', $year, isset($default['year']) ? $default['year'] : '', $js); ?> </td> 
					 <td> <br /> <input type="submit" class="button" value="SUBMIT" /> </td>
			    </tr>
			</table>
		</form> <br />
		
		<?php  echo ! empty($graph) ? $graph : '';  ?>
	
	</fieldset>

		
	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>

	
</div>

