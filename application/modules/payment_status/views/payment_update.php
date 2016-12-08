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
	
	<fieldset class="field"> <legend> Payment Status </legend>
    <table>
     
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>" >
			      
 <tr> <td> Name </td> <td>:</td> <td> <b> <?php echo isset($name) ? $name : ''; ?> </b> </td> </tr>
 <tr> <td> NIS </td> <td>:</td> <td> <b> <?php echo isset($nis) ? $nis : ''; ?> </b> </td> </tr>
 <tr> <td> Department </td> <td>:</td> <td> <b> <?php echo isset($dept) ? $dept : ''; ?> </b> </td> </tr>
 <tr> <td> Grade / Faculty </td> <td>:</td> <td> <b> <?php echo isset($grade) ? $grade : ''.'/'.isset($faculty) ? $faculty : ''; ?> </b> </td> </tr>
 <tr> <td> School Year </td> <td>:</td> <td> <b> <?php echo isset($year) ? $year : ''; ?> </b> </td> </tr>
        
  <tr>
  <td> <label for="p1"> I </label> </td>  <td>:</td>
  <td>  <input type="Text" name="tp1" id="d1" title="Payment date I" size="10" class=""
         value="<?php echo set_value('tp1', isset($default['p1']) ? $default['p1'] : ''); ?>" /> 
		<img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/>
  </td>
  </tr>	
  
  <tr>
  <td> <label for="p2"> II </label> </td>  <td>:</td>
  <td> <input type="Text" name="tp2" id="d2" title="Payment date II" size="10"
       value="<?php echo set_value('tp2', isset($default['p2']) ? $default['p2'] : ''); ?>" /> 
		<img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d2','yyyymmdd')" style="cursor:pointer"/>
  </td>
  </tr>	
  
  <tr>
  <td> <label for="p1"> III </label> </td>  <td>:</td>
  <td> <input type="Text" name="tp3" id="d3" title="Payment date III" size="10"
       value="<?php echo set_value('tp3', isset($default['p3']) ? $default['p3'] : ''); ?>" /> 
		<img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d3','yyyymmdd')" style="cursor:pointer"/>
  </td>
  </tr>	
  
  <tr>
  <td> <label for="p1"> IV </label> </td>  <td>:</td>
  <td> <input type="Text" name="tp4" id="d4" title="Payment date IV" size="10"
       value="<?php echo set_value('tp4', isset($default['p4']) ? $default['p4'] : ''); ?>" /> 
	   <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d4','yyyymmdd')" style="cursor:pointer"/>
  </td>
  </tr>	
  
  <tr>
  <td> <label for="p1"> V </label> </td>  <td>:</td>
  <td> <input type="Text" name="tp5" id="d5" title="Payment date V" size="10"
       value="<?php echo set_value('tp5', isset($default['p5']) ? $default['p5'] : ''); ?>" /> 
		<img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d5','yyyymmdd')" style="cursor:pointer"/>
  </td>
  </tr>	
  
  <tr>
  <td> <label for="p1"> VI </label> </td>  <td>:</td>
  <td> <input type="Text" name="tp6" id="d6" title="Payment date VI" size="10"
       value="<?php echo set_value('tp6', isset($default['p6']) ? $default['p6'] : ''); ?>" /> 
	   <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d6','yyyymmdd')" style="cursor:pointer"/>
  </td>
  </tr>	
  
  <tr>
  <td> <label for="p1"> VII </label> </td>  <td>:</td>
  <td> <input type="Text" name="tp7" id="d7" title="Payment date VII" size="10"
       value="<?php echo set_value('tp7', isset($default['p7']) ? $default['p7'] : ''); ?>" /> 
		<img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d7','yyyymmdd')" style="cursor:pointer"/>
  </td>
  </tr>	
        
  <tr>
  <td> <label for="p1"> VIII </label> </td>  <td>:</td>
  <td> <input type="Text" name="tp8" id="d8" title="Payment date VIII" size="10"
       value="<?php echo set_value('tp8', isset($default['p8']) ? $default['p8'] : ''); ?>" /> 
		<img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d8','yyyymmdd')" style="cursor:pointer"/>
  </td>
  </tr>	
  
  <tr>
  <td> <label for="p1"> IX </label> </td>  <td>:</td>
  <td> <input type="Text" name="tp9" id="d9" title="Payment date IX" size="10"
       value="<?php echo set_value('tp9', isset($default['p9']) ? $default['p9'] : ''); ?>" /> 
	   <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d9','yyyymmdd')" style="cursor:pointer"/>
  </td>
  </tr>	
  
  <tr>
  <td> <label for="p1"> X </label> </td>  <td>:</td>
  <td> <input type="Text" name="tp10" id="d10" title="Payment date X" size="10"
       value="<?php echo set_value('tp10', isset($default['p10']) ? $default['p10'] : ''); ?>" /> 
	   <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d10','yyyymmdd')" style="cursor:pointer"/>
  </td>
  </tr>	
  
  <tr>
  <td> <label for="p1"> XI </label> </td>  <td>:</td>
  <td> <input type="Text" name="tp11" id="d11" title="Payment date XI" size="10"
       value="<?php echo set_value('tp11', isset($default['p11']) ? $default['p11'] : ''); ?>" /> 
	   <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d11','yyyymmdd')" style="cursor:pointer"/>
  </td>
  </tr>	
  
  <tr>
  <td> <label for="p1"> XII </label> </td>  <td>:</td>
  <td> <input type="Text" name="tp12" id="d12" title="Payment date XII" size="10"
       value="<?php echo set_value('tp12', isset($default['p12']) ? $default['p12'] : ''); ?>" /> 
	   <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d12','yyyymmdd')" style="cursor:pointer"/>
  </td>
  </tr>		       
        </table>
        <p style="margin:15px 0 0 0; float:right;">
            <input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> 
            <input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " />
        </p>	
        </form>			  
	</fieldset>
</div>

