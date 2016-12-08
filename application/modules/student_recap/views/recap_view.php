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
	  'width'      => '500',
	  'height'     => '250',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 500)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 250)/2)+\'',
);

$atts2 = array(
	  'class'      => 'refresh',
	  'title'      => 'Purchase Report',
	  'width'      => '450',
	  'height'     => '400',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 450)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 400)/2)+\'',
);

?>

<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
    
    <fieldset class="field"> <legend> Recapitulation </legend>
	<form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
					
                    <td> <label for=""> Department : </label> <br />
                    <?php $js = 'id="cdept"'; echo form_dropdown('cdept', $dept, isset($default['dept']) ? $default['dept'] : '', $js); ?> &nbsp;
                    </td>
                    
					<td> <label for="cperiod"> Period : </label> <br />
                         <select name="cmonth">
                         <option value="" selected="selected"> -- </option>
                         <option value="1"> January </option>
                         <option value="2"> February </option>
                         <option value="3"> March </option>
                         <option value="4"> April </option>
                         <option value="5"> May </option>
                         <option value="6"> June </option>
                         <option value="7"> July </option>
                         <option value="8"> August </option>
                         <option value="9"> September </option>
                         <option value="10"> October </option>
                         <option value="11"> November </option>
                         <option value="12"> December </option>
                         </select> - <input type="text" id="tyear" name="tyear" size="3" maxlength="4" />
                    </td>
					
					<td colspan="3" align="right">  <br />
					<input type="submit" name="submit" class="button" value="Search" /> 
					<input type="reset" name="reset" class="button" value=" Cancel " /> 
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
           <?php echo anchor_popup(site_url("student_recap/add"), 'ADD NEW', $atts2); ?>
           <?php echo anchor(site_url("student_recap/trans"), 'LOG TRANS', $atts2); ?>
           <?php echo anchor_popup(site_url("student_recap/report"), 'REPORT', $atts1); ?>
		   </td> 
		</tr>
	</tbody>
	</table>
    
    <fieldset class="field"> <legend> Student's Recapitulation - Chart </legend>
		
		<?php  echo ! empty($graph) ? $graph : '';  ?>
	
	</fieldset>

	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>

	
</div>

