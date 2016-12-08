	
<?php 

 $atts = array(
		  'class'      => 'refresh',
		  'title'      => 'add po',
		  'width'      => '500',
		  'height'     => '300',
		  'scrollbars' => 'yes',
		  'status'     => 'yes',
		  'resizable'  => 'yes',
		  'screenx'    =>  '\'+((parseInt(screen.width) - 500)/2)+\'',
		  'screeny'    =>  '\'+((parseInt(screen.height) - 300)/2)+\'',
		);
		
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



<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Warehouse Transaction </legend>
	       <form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
					
					<td>  <label for="tproduct"> Product : </label>  <br />
				          <input type="text" readonly="readonly" name="tproduct" id="tproduct" size="30" title="Name" /> &nbsp;
 				          <?php echo anchor_popup(site_url("product/get_list/"), '[ ... ]', $atts1); ?> &nbsp;
					</td> 
					
					<td> <label for=""> Date : </label> <br />
					     <input type="Text" name="tdate" id="d1" title="Start date" size="10" class="form_field" /> 
				         <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> &nbsp;
					</td> 
					
					<td> <br />
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
		   <?php echo anchor_popup(site_url("warehouse_transaction/report"), 'TRANSACTION REPORT', $atts); ?>
		   </td> 
		</tr>
	</tbody>
</table>

		
	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>

	
</div>

