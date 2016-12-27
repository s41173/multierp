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
	
	<fieldset class="field"> <legend> Receipt Type </legend>
    <table>
     
	<form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>" >
			      
        <tr> 
		<td> <label for="ccur"> Currency </label> </td> <td>:</td>
        <td>  
        <?php $js = 'class="required"'; echo form_dropdown('ccur', $currency, isset($default['cur']) ? $default['cur'] : '', $js); ?>
        </td>
		</tr>  
        
        <tr> 
		<td> <label for="cdept"> Department </label> </td> <td>:</td>
        <td>  
        <?php $js = 'class="required"'; echo form_dropdown('cdept', $dept, isset($default['dept']) ? $default['dept'] : '', $js); ?>
        </td>
		</tr>     
            				
		<tr>
            <td> <label for="tname"> Name </label> </td>  <td>:</td>
            <td> <input type="text" class="required" name="tname" size="35" title="Name"
                  value="<?php echo set_value('tname', isset($default['name']) ? $default['name'] : ''); ?>" /> 
            </td>
        </tr>	
        
        <tr>
            <td> <label for="tdesc"> Desc </label> </td>  <td>:</td>
  <td> <textarea class="required" name="tdesc" cols="30" rows="3"><?php echo set_value('tdesc', isset($default['desc']) ? $default['desc'] : ''); ?>       </textarea> 
  </td>
        </tr>	
        
        <tr>
            <td> <label for="titem"> SPP - Bayar Dimuka </label> </td> <td>:</td>
            <td>
             <input type="text" class="required" readonly name="tspp1" id="titem1" size="10" title="Name"
             value="<?php echo set_value('tspp1', isset($default['spp1']) ? $default['spp1'] : ''); ?>" /> &nbsp;
             <?php echo anchor_popup(site_url("accountc/get_list/null/null/titem1"), '[ ... ]', $atts1); ?> &nbsp; &nbsp; </td>  
        </tr>  
        
        <tr>
            <td> <label for="titem"> SPP - Bulan Berjalan </label> </td> <td>:</td>
            <td>
             <input type="text" class="required" readonly name="tspp2" id="titem2" size="10" title="Name"
             value="<?php echo set_value('tspp2', isset($default['spp2']) ? $default['spp2'] : ''); ?>" /> &nbsp;
             <?php echo anchor_popup(site_url("accountc/get_list/null/null/titem2"), '[ ... ]', $atts1); ?> &nbsp; &nbsp; </td>    
        </tr>  
        
        <tr>
            <td> <label for="titem"> SPP - Tunggakan </label> </td> <td>:</td>
            <td>
             <input type="text" class="required" readonly name="tspp3" id="titem3" size="10" title="Name"
             value="<?php echo set_value('tspp3', isset($default['spp3']) ? $default['spp3'] : ''); ?>" /> &nbsp;
             <?php echo anchor_popup(site_url("accountc/get_list/null/null/titem3"), '[ ... ]', $atts1); ?> &nbsp; &nbsp; </td>    
        </tr>   
        
        <tr>
            <td> <label for="titem"> OSIS </label> </td> <td>:</td>
            <td>
             <input type="text" class="required" readonly name="tosis1" id="titem4" size="10" title="Name"
             value="<?php echo set_value('tosis1', isset($default['osis1']) ? $default['osis1'] : ''); ?>" /> &nbsp;
             <?php echo anchor_popup(site_url("accountc/get_list/null/null/titem4"), '[ ... ]', $atts1); ?> &nbsp; &nbsp; </td>    
        </tr>   
        
        <tr>
            <td> <label for="titem"> OSIS - Tunggakan </label> </td> <td>:</td>
            <td>
             <input type="text" class="required" readonly name="tosis2" id="titem5" size="10" title="Name"
             value="<?php echo set_value('tosis2', isset($default['osis2']) ? $default['osis2'] : ''); ?>" /> &nbsp;
             <?php echo anchor_popup(site_url("accountc/get_list/null/null/titem5"), '[ ... ]', $atts1); ?> &nbsp; &nbsp; </td>    
        </tr> 
        
        <tr>
            <td> <label for="titem"> Komputer </label> </td> <td>:</td>
            <td>
             <input type="text" class="required" readonly name="tkom1" id="titem6" size="10" title="Name"
             value="<?php echo set_value('tkom1', isset($default['kom1']) ? $default['kom1'] : ''); ?>" /> &nbsp;
             <?php echo anchor_popup(site_url("accountc/get_list/null/null/titem6"), '[ ... ]', $atts1); ?> &nbsp; &nbsp; </td>    
        </tr>
        
        <tr>
            <td> <label for="titem"> Komputer - Tunggakan </label> </td> <td>:</td>
            <td>
             <input type="text" class="required" readonly name="tkom2" id="titem7" size="10" title="Name"
             value="<?php echo set_value('tkom2', isset($default['kom2']) ? $default['kom2'] : ''); ?>" /> &nbsp;
             <?php echo anchor_popup(site_url("accountc/get_list/null/null/titem7"), '[ ... ]', $atts1); ?> &nbsp; &nbsp; </td>    
        </tr> 
        
        <tr>
            <td> <label for="titem"> Praktek </label> </td> <td>:</td>
            <td>
             <input type="text" class="required" readonly name="tpraktek1" id="titem8" size="10" title="Name"
             value="<?php echo set_value('tpraktek1', isset($default['praktek1']) ? $default['praktek1'] : ''); ?>" /> &nbsp;
             <?php echo anchor_popup(site_url("accountc/get_list/null/null/titem8"), '[ ... ]', $atts1); ?> &nbsp; &nbsp; </td>    
        </tr> 
        
        <tr>
            <td> <label for="titem"> Praktek - Tunggakan </label> </td> <td>:</td>
            <td>
             <input type="text" class="required" readonly name="tpraktek2" id="titem9" size="10" title="Name"
             value="<?php echo set_value('tpraktek2', isset($default['praktek2']) ? $default['praktek2'] : ''); ?>" /> &nbsp;
             <?php echo anchor_popup(site_url("accountc/get_list/null/null/titem9"), '[ ... ]', $atts1); ?> &nbsp; &nbsp; </td>    
        </tr> 
        
        <tr>
            <td> <label for="titem"> Bantuan </label> </td> <td>:</td>
            <td>
             <input type="text" class="required" readonly name="tdiscount" id="titem10" size="10" title="Name"
             value="<?php echo set_value('tdiscount', isset($default['discount']) ? $default['discount'] : ''); ?>" /> &nbsp;
             <?php echo anchor_popup(site_url("accountc/get_list/null/null/titem10"), '[ ... ]', $atts1); ?> &nbsp; &nbsp; </td>    
        </tr>    		
                    
            </table>
            <p style="margin:15px 0 0 0; float:right;">
                <input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> 
                <input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " />
            </p>	
        </form>			  
	</fieldset>
</div>

