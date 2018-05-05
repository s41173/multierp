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
    	
        <form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
                    
<td> <label for="ccategory"> Code : </label> <br /> <input type="text" name="tcode" size="10" title="Code" /> </td>	
<td> <label for="tname"> Name </label> <br /> <input type="text" name="tname" size="35" title="Name" /> &nbsp; </td> 
<td> <label for="tperiod"> Period </label> <br /> <input type="text" name="tperiod" id="tperiod" size="3" maxlength="3" title="Desc" onkeyup="checkdigit(this.value, 'tperiod')" /> </td> 
					
<td> <label for="taccount"> Accumulation : </label> <br />  
     <input type="text" class="required" readonly name="titem" id="titem" size="5" title="Name"
     value="<?php echo set_value('titem', isset($default['account']) ? $default['account'] : ''); ?>" /> &nbsp;
     <?php echo anchor_popup(site_url("accountc/get_list/"), '[ ... ]', $atts1); ?> &nbsp;
</td> 
                                    
<td> <label for="taccount"> Depreciation : </label> <br />  
     <input type="text" class="required" readonly name="titem2" id="titem2" size="5" title="Name"
     value="<?php echo set_value('titem2', isset($default['account']) ? $default['account'] : ''); ?>" /> &nbsp;
     <?php echo anchor_popup(site_url("accountc/get_list/null/IDR/titem2"), '[ ... ]', $atts1); ?> &nbsp;
</td> 
										
<td colspan="3" align="right"> <br />  
   <input type="submit" name="submit" class="button" title="" value="Save" /> 
   <input type="reset" name="reset" class="button" title="" value="Reset" /> 
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
	

	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>
	
</div>

