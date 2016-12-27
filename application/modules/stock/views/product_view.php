	
<?php 

$atts = array(
		  'class'      => 'refresh',
		  'title'      => 'add po',
		  'width'      => '600',
		  'height'     => '500',
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
	
	<fieldset class="field"> <legend> Inventory </legend>
	<form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
                    
                    <td> <label for="ctype"> Type : </label> <br />
                         <select name="ctype">
                         <option value=""> -- </option>
                         <option value="material"> Material </option>
                         <option value="tool"> Tools </option>
                         </select>
			        </td>
                    
					<td> <label for="cbrand"> Brand : </label> <br />
			            <?php $js = 'class=""'; echo form_dropdown('cbrand', $brand, isset($default['brand']) ? $default['brand'] : '', $js); ?> &nbsp; 
                    </td>
					
					<td> <label for="ccategory"> Category : </label> <br />
		            <?php $js = 'class=""'; echo form_dropdown('ccategory', $category, isset($default['category']) ? $default['category'] : '', $js); ?> &nbsp; </td>
					
					<td> <label for="tname"> Vendor : </label> <br /> 
					    <input type="text" readonly="readonly" name="tvendor" id="tcust" size="20" title="Customer" /> 
				<!--		<a class="refresh" id="tombol" href="<?php //echo site_url("vendor/get_list/"); ?>"> [ ... ] </a> -->
						<?php echo anchor_popup(site_url("vendor/get_list/"), '[ ... ]', $atts1); ?> &nbsp;
					</td> 
					
                    <td colspan="4"> Name ( Keyword ) : &nbsp; <br /> 
					                 <input type="text" name="tsearch" size="30" id="tproductsearch" />  
               					     <input type="submit" name="submit" class="button" title="" value="Search" /> 
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
	  <p class="cek"> <?php echo ! empty($radio1) ? $radio1 : ''; echo ! empty($radio2) ? $radio2 : ''; ?> <input type="submit" name="button" class="button_delete" title="Process Button" value="Delete All" />  </p> 
	</form>	
	
	<table align="right" style="margin:10px 0px 0 0; padding:3px; " width="100%" bgcolor="#D9EBF5">
	<tbody>
		<tr> 
		   <td align="right"> 
		   <?php echo anchor(site_url("warehouse"), 'WAREHOUSE STORAGE', $atts2); ?>
		   <?php echo anchor_popup(site_url("product/import"), 'IMPORT', $atts2); ?>
		   <?php echo anchor_popup(site_url("product/add"), 'ADD NEW', $atts); ?>
		   <?php echo anchor_popup(site_url("product/report"), 'REPORT', $atts2); ?>
           <?php echo anchor(site_url("reststock"), 'REST STOCK', $atts2); ?>
		   </td> 
		</tr>
	</tbody>
	</table>

		
	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>

	
</div>

