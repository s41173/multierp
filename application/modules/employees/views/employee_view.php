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
	  'height'     => '300',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 300)/2)+\'',
);

$atts2 = array(
	  'class'      => 'refresh',
	  'title'      => 'Purchase Report',
	  'width'      => '600',
	  'height'     => '600',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 600)/2)+\'',
);

?>

<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Employee </legend>	
    	
        <form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
            
    <td> <label for="cdivision"> Division : </label> <br />
	<?php $js = 'class=""'; echo form_dropdown('cdivision', $division, isset($default['division']) ? $default['division'] : '', $js); ?> &nbsp; </td>        

    <td> <label for="crole"> Role : </label> <br />
         <select class="" name="crole">
         <option value=""> -- </option>
         <option value="honor"> Honor </option>
         <option value="staff"> Staff </option>
         <option value="officer"> Officer </option>
         <option value="manager"> Manager </option>
         <option value="director"> Director </option>
         </select> &nbsp;
    </td>     
            
    <td> <label for="cdept"> Department : </label> <br />
	<?php $js = 'class=""'; echo form_dropdown('cdept', $dept, isset($default['dept']) ? $default['dept'] : '', $js); ?> &nbsp; </td>
                     		

            <td> <label for="tvalue"> Name : </label> <br />
	             <input type="text" name="tvalue" size="45" /> 
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
           <?php echo anchor_popup(site_url("employees/add"), 'ADD NEW', $atts2); ?>
           <?php echo anchor_popup(site_url("employees/import"), 'IMPORT', $atts1); ?>
           <?php echo anchor_popup(site_url("employees/report"), 'REPORT', $atts1); ?>
		   </td> 
		</tr>
	</tbody>
	</table>

	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>

	
</div>

