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
	  'height'     => '400',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 400)/2)+\'',
);

?>

<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Student List </legend>	
    	
        <form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
                    
            <td> <label for="cdept"> Department : </label> <br />
		        <?php $js = 'class=""'; echo form_dropdown('cdept', $dept, isset($default['dept']) ? $default['dept'] : '', $js); ?> <br /> </td>
                
    <td> <label for="cfaculty"> Faculty : </label> <br />
	<?php $js = 'class=""'; echo form_dropdown('cfaculty', $faculty, isset($default['faculty']) ? $default['faculty'] : '', $js); ?> &nbsp; </td>
    
     <td> <label for="cgrade"> Grade : </label> <br />
	<?php $js = 'class=""'; echo form_dropdown('cgrade', $grade, isset($default['grade']) ? $default['grade'] : '', $js); ?> &nbsp; </td>
        		
            <td> <label for="tvalue"> Field : </label> <br />
                 <select name="ctype"> <option value="0"> NISN </option> <option selected="selected" value="1"> Name </option> </select>		
	        </td>
                
                 <td> <label for="tvalue"> </label> <br />
	             <input type="text" id="tstudentinactive" name="tvalue" size="45" /> </td>
                						
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
	 <p class="cek"> <?php echo ! empty($radio1) ? $radio1 : ''; echo ! empty($radio2) ? $radio2 : ''; ?> 
     <input style="margin:2px;" type="submit" name="submit" title="Process Button" value="REMOVE" />
     </p> 
	</form>	
	
	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>

	
</div>

