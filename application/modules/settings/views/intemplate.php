<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title><?php echo isset($title) ? $title : ''; ?></title>

<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'development-bundle/themes/base/ui.all.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/jquery.fancybox-1.3.4.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/flexigrid.pack.css'; ?>");</style> <!-- flexigrid -->

<script type="text/javascript" src="<?php echo base_url();?>js/register.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/menu.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/datetimepicker_css.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/development-bundle/ui/ui.core.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tools.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/hoverIntent.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/complete.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/sortir.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.maskedinput-1.3.min.js"></script>
<script type='text/javascript' src='<?php echo base_url();?>js/jquery.autocomplete.js'></script>
<script type="text/javascript" src="<?php echo base_url();?>js/validate.js"></script> 
<script type='text/javascript' src='<?php echo base_url();?>js/jquery.validate.js'></script>  

<script type='text/javascript' src='<?php echo base_url();?>js/liveclock_lite.js'></script>   <!-- clock -->
<script type='text/javascript' src='<?php echo base_url();?>js/flexigrid.pack.js'></script>   <!-- flexigrid -->

<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";
</script>
  
</head>
<body onLoad="show_time()">
<div id="loading" style="display:none">Loading...</div>
    <div style="opacity: 0.7; position: absolute; top: 198px; left: 338px; display: none;" class="tooltip"></div>
	<div class="container_12">
			
			<!-- awal container -->
			<div class="grid_12" id="container">
			
					<!-- awal left panel -->
					<div class="grid_3" id="leftpanel"> 
						<div id="logo"></div>
						<div id="menuplace">
						  <!-- awal menu -->
							<?php $this->load->view('staticnavigation'); ?>
						 <!-- akhir menu -->
						</div>
						
						<div class="clear"></div>
						<!-- tempatinfo -->
				    <div id="tinfo"> <p> <b>D'Swip Panel</b> <br/> Copyright &copy; <?php echo date('Y'); ?> <br /> All Right Reserved </p> <p id="time">  </p> </div>
						<!-- tempatinfo -->
						
						<div class="clear"></div>
						
					</div>
					<!-- akhir left panel -->
					
					<div class="grid_9" id="rightpanel"> 
					   <!-- awal right panel -->
						<div class="grid_9" id="topheader">
							<h3 class="dashboard"> D'swip CMS - <?php echo isset($h2title) ? $h2title : ''; ?> </h3> 
						</div>
						<div class="clear"></div>
						
						<?php $this->load->view($main_view); ?>
						
						 <!-- akhir right panel -->
					</div>
					
					<!-- <div class="clear"></div> <div class="batas"></div> -->
					
					<div class="clear"></div>
        	</div>
			<!-- akhir container -->
	</div>

</body>
</html>
