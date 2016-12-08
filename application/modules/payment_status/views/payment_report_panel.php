<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'development-bundle/themes/base/ui.all.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/jquery.fancybox-1.3.4.css'; ?>");</style>

<link rel="stylesheet" href="<?php echo base_url().'js/jxgrid/' ?>css/jqx.base.css" type="text/css" />

<script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxcore.js"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxbuttons.js"></script>

<style>
        .refresh{ border:1px solid #AAAAAA; color:#000; padding:2px 5px 2px 5px; margin:0px 2px 0px 2px; background-color:#FFF;}
		.refresh:hover{ background-color:#CCCCCC; color: #FF0000;}
		.refresh:visited{ background-color:#FFF; color: #000000;}	
</style>

<script type="text/javascript">
            $(document).ready(function () {
                // Create Push Button.
                $(".jqxButton").jqxButton({ width: 'auto', height: '25'});
                // Create Submit Button.
            });
</script>

<?php 
		
$atts1 = array(
	  'class'      => 'refresh',
	  'title'      => 'add cust',
	  'width'      => '600',
	  'height'     => '400',
	  'scrollbars' => 'no',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 400)/2)+\'',
);

?>

<?php $flashmessage = $this->session->flashdata('message'); ?>

<div id="webadmin">
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Payment Status - Report </legend>
	<form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>" target="_blank" >
				<table>
					
			<tr>	
			<td> <label for="cdept"> Department </label> </td> <td>:</td>
			<td> <?php $js = 'class=""'; echo form_dropdown('cdept', $dept, isset($default['dept']) ? $default['dept'] : '', $js); ?> </td>
			</tr>
            
    <tr>	
	<td> <label for="cfaculty"> Faculty </label> </td> <td>:</td>
	<td> <?php $js = 'class=""'; echo form_dropdown('cfaculty', $faculty, isset($default['faculty']) ? $default['faculty'] : '', $js); ?> </td>
	</tr>
    
    <tr>	
	<td> <label for="cgrade"> Grade </label> </td> <td>:</td>
	<td> <?php $js = 'class=""'; echo form_dropdown('cgrade', $grade, isset($default['grade']) ? $default['grade'] : '', $js); ?> </td>
	</tr>
    
    <tr>	
	<td> <label for="cyear"> Academic Year </label> </td> <td>:</td>
	<td> <?php $js = 'class=""'; echo form_dropdown('cyear', $cyear, isset($default['year']) ? $default['year'] : '', $js); ?> </td>
	</tr>
    
    <tr>	
	<td> <label for="cperiod"> Period </label> </td> <td>:</td>
	<td> 
    	 <select name="cperiod">
<option value="7" <?php echo set_select('cperiod', '7', isset($default['period']) && $default['period'] == '7' ? TRUE : FALSE); ?> > Jul </option>
<option value="8" <?php echo set_select('cperiod', '8', isset($default['period']) && $default['period'] == '8' ? TRUE : FALSE); ?> > Aug </option>
<option value="9" <?php echo set_select('cperiod', '9', isset($default['period']) && $default['period'] == '9' ? TRUE : FALSE); ?> > Sep </option>
<option value="10" <?php echo set_select('cperiod', '10', isset($default['period']) && $default['period'] == '10' ? TRUE : FALSE); ?> > Oct </option>
<option value="11" <?php echo set_select('cperiod', '11', isset($default['period']) && $default['period'] == '11' ? TRUE : FALSE); ?> > Nov </option>
<option value="12" <?php echo set_select('cperiod', '12', isset($default['period']) && $default['period'] == '12' ? TRUE : FALSE); ?> > Dec </option>
<option value="1" <?php echo set_select('cperiod', '1', isset($default['period']) && $default['period'] == '1' ? TRUE : FALSE); ?> > Jan </option>
<option value="2" <?php echo set_select('cperiod', '2', isset($default['period']) && $default['period'] == '2' ? TRUE : FALSE); ?> > Feb </option>
<option value="3" <?php echo set_select('cperiod', '3', isset($default['period']) && $default['period'] == '3' ? TRUE : FALSE); ?> > Mar </option>
<option value="4" <?php echo set_select('cperiod', '4', isset($default['period']) && $default['period'] == '4' ? TRUE : FALSE); ?> > Apr </option>
<option value="5" <?php echo set_select('cperiod', '5', isset($default['period']) && $default['period'] == '5' ? TRUE : FALSE); ?> > May </option>
<option value="6" <?php echo set_select('cperiod', '6', isset($default['period']) && $default['period'] == '6' ? TRUE : FALSE); ?>> Jun </option>
         </select>
    </td>
	</tr>
     
    <tr>	
	<td> <label for="ctype"> Report Type </label> </td> <td>:</td>
	<td> <select name="ctype"> 
         <option value="0"> 1.Students Recapitulation </option> 
         <option value="1"> 2.Receivable </option> 
         <option value="2"> 3.Recapitulation Summary </option> 
         <option value="3"> 4.Front Payment </option> 
         <option value="4"> 5.School Fee Recap </option> 
         <option value="5"> 6.Practice Recap </option> 
         <option value="6"> 7.OSIS Recap </option> 
         <option value="7"> 8.Computer Recap </option> 
        <!-- <option value="8"> 9.Income Receivable </option> -->
         <option value="9"> 10.Pivot Receivable </option>
         <option value="10"> 11. Gross Income </option> 
         </select> 
    </td>
	</tr>
    
    <tr>	
    <td> <label for="cformat"> Format </label> </td> <td>:</td>
    <td>  
     <select name="cformat">
        <option selected="selected" value="0"> HTML </option>
        <option value="1"> Pdf </option>
     </select> &nbsp; <br /> </td>
    </tr>
    
    <tr>
    <td colspan="2"></td>
    <td>
    <input type="submit" name="submit" class="jqxButton" title="SUBMIT" value="Submit" /> 
    </td>
    </tr>
    							   
	</table>
					
	</form>			  
	</fieldset>
</div>

