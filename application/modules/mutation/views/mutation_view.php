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
	  'height'     => '350',
	  'scrollbars' => 'no',
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
	
	<fieldset class="field"> <legend> Student Mutation </legend>
	<form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
					
<td> <label for="tname"> No : </label> <br /> <input type="text" id="tno" name="tno" size="5" title="No" 
value="<?php echo set_value('tno', isset($default['no']) ? $default['no'] : ''); ?>" onkeyup="checkdigit(this.value, 'tno')" /> &nbsp; &nbsp; </td> 
					
            <td> <label for=""> Date : </label> <br />
                 <input type="Text" name="tdate" id="d1" title="Start date" size="10" class="form_field" /> 
                 <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/>
                 &nbsp;
            </td> 
            
            <td> <label for=""> Type : </label> <br />
                 <?php $js = 'class=""'; echo form_dropdown('ctype', $type, isset($default['type']) ? $default['type'] : '', $js); ?> &nbsp;
            </td> 
            
             <td> <label for=""> Payment : </label> <br />
                 <select name="cstts"> 
                 <option value=""> -- </option> 
                 <option value="1"> Settled </option> 
                 <option value="0"> Credit </option> 
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
           <?php echo anchor_popup(site_url("mutation_type"), 'MUTATION TYPE', $atts); ?>
           <?php echo anchor_popup(site_url("mconfig"), 'CONFIGURATION', $atts); ?>
		   <?php echo anchor_popup(site_url("mutation/add"), 'CREATE NEW', $atts); ?>
		   <?php echo anchor_popup(site_url("mutation/report"), 'REPORT', $atts2); ?>
		   </td> 
		</tr>
	</tbody>
	</table>

		
	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>

	
</div>

