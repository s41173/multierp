<script type="text/javascript" src="<?php echo base_url();?>public/javascripts/FusionCharts.js"></script>
<?php 

$atts = array(
		  'class'      => 'refresh',
		  'title'      => '',
		  'width'      => '800',
		  'height'     => '600',
		  'scrollbars' => 'yes',
		  'status'     => 'yes',
		  'resizable'  => 'yes',
		  'screenx'    =>  '\'+((parseInt(screen.width) - 800)/2)+\'',
		  'screeny'    =>  '\'+((parseInt(screen.height) - 600)/2)+\'',
		);
		
$atts1 = array(
	  'class'      => 'fancy',
	  'title'      => '',
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
	  'title'      => '',
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
	
	<fieldset class="field"> <legend> Payment Status </legend>	
    	
        <form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
             
            <td> <label for="cstts"> Status : </label> <br />
		        <select name="cstts"> <option value="1"> Active </option> <option value="0"> Inactive </option> </select> &nbsp;
            </td>  
             
            <td> <label for="cyear"> Academic Year : </label> <br />
		        <?php $js = 'class=""'; echo form_dropdown('cyear', $finance_year, isset($default['year']) ? $default['year'] : '', $js); ?> &nbsp;
            </td> 
                    
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
	             <input type="text" id="tstudentsearch" name="tvalue" size="30" /> </td>
                						
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
           <?php echo anchor(site_url("payment_status/migration"), 'MIGRATION', $atts2); ?>
           <?php echo anchor(site_url("payment_status/report"), 'REPORT', $atts1); ?>
		   </td> 
		</tr>
	</tbody>
	</table> <br />
    
     <fieldset class="field"> <legend> Payment Status - Chart </legend>
        
        <form name="search_form" class="myform" method="post" action="<?php echo ! empty($form_action_graph) ? $form_action_graph : ''; ?>">
            <table>
                <tr> <td> <label for="tname"> Department : </label> <br /> <?php $js = 'class=""'; echo form_dropdown('cdeptgraph', $dept, isset($default['deptgraph']) ? $default['deptgraph'] : '', $js); ?> </td> 
                     <td> <label for="tname"> Financial Year : </label> <br /> <?php $js = 'class=""'; echo form_dropdown('cyeargraph', $finance_year, isset($default['yeargraph']) ? $default['yeargraph'] : '', $js); ?> </td> 
                     <td> <br /> <input type="submit" class="button" value="SUBMIT" /> </td>
                </tr>
            </table>
        </form> <br />
        
        <?php  echo ! empty($graph) ? $graph : '';  ?>
    
    </fieldset>

	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>

	
</div>

