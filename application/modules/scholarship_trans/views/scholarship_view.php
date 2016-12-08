<?php 

$atts = array(
		  'class'      => 'refresh',
		  'title'      => 'add po',
		  'width'      => '600',
		  'height'     => '400',
		  'scrollbars' => 'yes',
		  'status'     => 'yes',
		  'resizable'  => 'yes',
		  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
		  'screeny'    =>  '\'+((parseInt(screen.height) - 400)/2)+\'',
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
	  'class'      => 'refresh',
	  'title'      => 'Purchase Report',
	  'width'      => '550',
	  'height'     => '400',
	  'scrollbars' => 'no',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 550)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 400)/2)+\'',
);

?>

<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Scholarship Transaction </legend>
	<form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr>  
			
            <td> <label for=""> Academic Year : </label> <br />
            <?php $js = 'class=""'; echo form_dropdown('cfinancial', $finance, isset($default['financial']) ? $default['financial'] : '', $js); ?> &nbsp;
            </td> 
            
            <td> <label for=""> Department : </label> <br />
                 <?php $js = 'class=""'; echo form_dropdown('cdept', $dept, isset($default['dept']) ? $default['dept'] : '', $js); ?> &nbsp;
            </td> 
            		  
            
            <td> <label for=""> Level : </label> <br />
              <select id="cgrade" name="cgrade"> 
              <option value="">--</option> 
              <option value="1">I</option> 
              <option value="2">II</option> 
              <option value="3">III</option> 
              </select> &nbsp;
            </td> 
            
            <td> <label for=""> Type : </label> <br />
                 <?php $js = 'class=""'; echo form_dropdown('ctype', $type, isset($default['type']) ? $default['type'] : '', $js); ?> &nbsp;
            </td> 
            
            <td> <label for=""> Period : </label> <br />
                 <select name="cperiod"> 
                 <option value=""> -- </option> 
                 <option value="1"> 1 </option> 
                 <option value="2"> 2 </option> 
                 <option value="3"> 3 </option> 
                 <option value="4"> 4 </option> 
                 <option value="5"> 5 </option> 
                 <option value="6"> 6 </option> 
                 <option value="7"> 7 </option> 
                 <option value="8"> 8 </option> 
                 <option value="9"> 9 </option> 
                 <option value="10"> 10 </option> 
                 <option value="11"> 11 </option> 
                 <option value="12"> 12 </option> 
                 </select> &nbsp;
            </td> 
            
            <td> <label for=""> Until : </label> <br />
                 <select name="cuntil"> 
                 <option value=""> -- </option> 
                 <option value="7"> Jan </option> 
                 <option value="8"> Feb </option> 
                 <option value="9"> Mar </option> 
                 <option value="10"> Apr </option> 
                 <option value="11"> May </option> 
                 <option value="12"> Jun </option> 
                 <option value="1"> Jul </option> 
                 <option value="2"> Aug </option> 
                 <option value="3"> Sep </option> 
                 <option value="4"> Oct </option> 
                 <option value="5"> Nov </option> 
                 <option value="6"> Dec </option> 
                 </select>
            </td> 
            
            <td> <label for=""> Status : </label> <br />
                 <select name="cstts"> 
                 <option value=""> -- </option> 
                 <option value="1"> Active </option> 
                 <option value="0"> Inactive </option> 
                 </select> &nbsp;
            </td> 
					
					<td colspan="3" align="right"> <br />
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
           <?php echo anchor_popup(site_url("scholarship"), 'SCHOLARSHIP TYPE', $atts); ?>
		   <?php echo anchor_popup(site_url("scholarship_trans/add"), 'CREATE NEW', $atts); ?>
		   <?php echo anchor_popup(site_url("scholarship_trans/report"), 'REPORT', $atts2); ?>
		   </td> 
		</tr>
	</tbody>
	</table>

		
	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>

	
</div>

