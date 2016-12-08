<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'development-bundle/themes/base/ui.all.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/jquery.fancybox-1.3.4.css'; ?>");</style>

<script type="text/javascript" src="<?php echo base_url();?>js/register.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/datetimepicker_css.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/development-bundle/ui/ui.core.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tools.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/hoverIntent.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/complete.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/sortir.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/validate.js"></script> 
<script type='text/javascript' src='<?php echo base_url();?>js/jquery.validate.js'></script>  

<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";
</script>

<style>
        .refresh{ border:1px solid #AAAAAA; color:#000; padding:2px 5px 2px 5px; margin:0px 2px 0px 2px; background-color:#FFF;}
		.refresh:hover{ background-color:#CCCCCC; color: #FF0000;}
		.refresh:visited{ background-color:#FFF; color: #000000;}	
</style>

<?php 
		
$atts1 = array(
	  'class'      => 'refresh',
	  'title'      => 'add cust',
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
	
	<fieldset class="field"> <legend> Inventory </legend>
    <table>
     
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>" >
			
        <tr>
            <td> <label for="tno"> No </label> </td>  <td>:</td>
            <td> <input type="text" class="required" readonly="readonly" name="tno" size="5" title="Name" value="<?php echo $no; ?>" /> </td>
        </tr>	    
            
        <tr> 
		<td> <label for="ccur"> Currency </label> </td> <td>:</td>
        <td>  
        <?php $js = 'class="required"'; echo form_dropdown('ccur', $currency, isset($default['cur']) ? $default['cur'] : '', $js); ?>
        </td>
		</tr>    
            	
		<tr> 
		<td> <label for="ccategory"> Category </label> </td> <td>:</td>
        <td>  
        <?php $js = 'class="required"'; echo form_dropdown('ccategory', $category, isset($default['category']) ? $default['category'] : '', $js); ?>
        </td>
		</tr>
                    
        <tr> 
		<td> <label for="croom"> Room </label> </td> <td>:</td>
        <td>  
        <?php $js = 'class="required"'; echo form_dropdown('croom', $room, isset($default['room']) ? $default['room'] : '', $js); ?>
        </td>
		</tr>
					
		<tr>
            <td> <label for="tname"> Name </label> </td>  <td>:</td>
            <td> <input type="text" class="required" name="tname" size="35" title="Name" /> </td>
        </tr>	
        
        <tr>
            <td> <label for="tdesc"> Desc </label> </td>  <td>:</td>
            <td> <textarea class="required" name="tdesc" cols="30" rows="3"></textarea> </td>
        </tr>	
            		
        <tr>	
             <td> <label for="tdate"> Purchase Date </label> </td> <td>:</td>
             <td>  
               <input type="Text" name="tdate" id="d1" title="Invoice date" size="10" class="required" /> 
               <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/>
            </td>
        </tr>
					
        <tr>
            <td> <label for="tprice"> Price </label> </td>  <td>:</td>
            <td>  <input type="text" id="tprice" name="tprice" size="15" title="Price" onKeyUp="checkdigit(this.value, 'tprice')" /> </td>
        </tr>
					
					   
				</table>
				<p style="margin:15px 0 0 0; float:right;">
					<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> 
					<input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " />
				</p>	
			</form>			  
	</fieldset>
</div>

