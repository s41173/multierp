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
	  'width'      => '800',
	  'height'     => '600',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 800)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 600)/2)+\'',
);

?>

<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Cost Type </legend>
    	
        <form name="modul_form" class="myform" id="xajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 

<td> <label for="ccategory"> Group : </label> <br /> <?php $js = 'class="required"'; echo form_dropdown('cgroup', $group, isset($default['group']) ? $default['group'] : '', $js); ?> </td>	
                                                                                                                                                                                                                  
<td> <label for="taccount"> Status : </label> <br />  
     <select name="cstatus" class="required">
         <option value=""> -- </option>
         <option value="1"> Publish </option>
         <option value="0"> Unpublish </option>
     </select>
</td> 
										
<td colspan="3" align="right"> <br />  
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
	</form>	
	
	<table align="right" style="margin:10px 0px 0 0; padding:3px; " width="100%" bgcolor="#D9EBF5">
	<tbody>
		<tr> 
		   <td align="right"> 
		   <?php echo anchor(site_url("groupasset"), 'GROUP ASSET', $atts2); ?>
		   <?php echo anchor_popup(site_url("asset/add"), 'CREATE NEW', $atts); ?>
		   </td> 
		</tr>
	</tbody>
	</table>
	

	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>
	
</div>

