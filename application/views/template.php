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

<!-- CSS Grid -->
<link rel="stylesheet" href="<?php //echo base_url().'js/jxgrid/' ?>css/jqx.base.css" type="text/css" />
<!-- CSS Grid -->

<!-- JS Grid -->

<script type="text/javascript" src="<?php //echo base_url().'js/jxgrid/' ?>js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="<?php //echo base_url().'js/jxgrid/' ?>js/jqxcore.js"></script>
<script type="text/javascript" src="<?php //echo base_url().'js/jxgrid/' ?>js/jqxdata.js"></script>
<script type="text/javascript" src="<?php //echo base_url().'js/jxgrid/' ?>js/jqxbuttons.js"></script>
<script type="text/javascript" src="<?php //echo base_url().'js/jxgrid/' ?>js/jqxcheckbox.js"></script>
<script type="text/javascript" src="<?php //echo base_url().'js/jxgrid/' ?>js/jqxscrollbar.js"></script>
<script type="text/javascript" src="<?php //echo base_url().'js/jxgrid/' ?>js/jqxpanel.js"></script>
<script type="text/javascript" src="<?php //echo base_url().'js/jxgrid/' ?>js/jqxtree.js"></script>
<script type="text/javascript" src="<?php //echo base_url().'js/jxgrid/' ?>js/jqxlistbox.js"></script>
<script type="text/javascript" src="<?php //echo base_url().'js/jxgrid/' ?>js/jqxdropdownlist.js"></script>
<script type="text/javascript" src="<?php //echo base_url().'js/jxgrid/' ?>js/jqxmenu.js"></script>
<script type="text/javascript" src="<?php //echo base_url().'js/jxgrid/' ?>js/jqxgrid.js"></script>
<script type="text/javascript" src="<?php //echo base_url().'js/jxgrid/' ?>js/jqxgrid.sort.js"></script>
<script type="text/javascript" src="<?php //echo base_url().'js/jxgrid/' ?>js/jqxgrid.filter.js"></script>
<script type="text/javascript" src="<?php //echo base_url().'js/jxgrid/' ?>js/jqxgrid.columnsresize.js"></script>
<script type="text/javascript" src="<?php //echo base_url().'js/jxgrid/' ?>js/jqxgrid.selection.js"></script>
<script type="text/javascript" src="<?php //echo base_url().'js/jxgrid/' ?>js/jqxgrid.pager.js"></script>

<!-- JS Grid -->

<!-- JS Tree -->
    <link rel="stylesheet" href="<?php echo base_url().'js/easytree/' ?>skin-xp/ui.easytree.css" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.3.2.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/easytree/' ?>jquery.easytree.min.js"></script>
<!-- JS Tree -->

<script type="text/javascript" src="<?php echo base_url();?>js/register.js"></script>
<script type="text/javascript" src="<?php //echo base_url();?>js/menu.js"></script> <!-- accordion menu -->
<script type="text/javascript" src="<?php echo base_url();?>js/datetimepicker_css.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/development-bundle/ui/ui.core.js"></script>
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

<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";
var site = "<?php echo site_url();?>";
</script>
  
</head>
<body onLoad="show_time();">
<div id="loading" style="display:none">Loading...</div>
    <div style="opacity: 0.7; position: absolute; top: 198px; left: 338px; display: none;" class="tooltip"></div>
	<div class="container_12">
			
			<!-- awal container -->
			<div class="grid_12" id="container">
			
					<!-- awal left panel -->
					<div class="grid_3" id="leftpanel"> 
		            <div id="logo"> <img src="<?php echo base_url(); ?>images/property/logobw.png" alt="logo" /> </div>
						<div id="menuplace">
						  <!-- awal menu -->
							<?php $this->load->view('navigation'); ?>
						 <!-- akhir menu -->
						</div>
						
						<div class="clear"></div>
						<!-- tempatinfo -->
				<div id="tinfo"> <p> <b>D'Swip Panel</b> <br/> Copyright &copy; <?php echo date('Y'); ?> <br /> All Right Reserved </p> <p id="time">  </p> </div>
						<!-- tempatinfo -->
						
						<div class="clear"></div>
						<!-- menu2 -->
						<div id="menuplace2">
						  <!-- awal menu -->
							<?php $this->load->view('staticnavigation'); ?>
						 <!-- akhir menu -->
						</div>
						<!-- menu2 -->
						
					</div>
					<!-- akhir left panel -->
					
					<div class="grid_9" id="rightpanel"> 
					   <!-- awal right panel -->
						<div class="grid_9" id="topheader"> <p class="logout"> Logged is as <?php echo $this->session->userdata('username'); ?>, <b> 
						<?php echo anchor('login/process_logout', 'Logout?', array('onclick' => "return confirm('Are you sure want to log out?')"));?> </b> </p> 
							<h3 class="dashboard"> D'swip Dashboard - <?php echo isset($h2title) ? $h2title : ''; ?> </h3> <p class="desc">A little description explain what Dashboard is in one/two lines.</p>
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
