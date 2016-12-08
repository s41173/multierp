<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title> Sales Invoice Form </title>

<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>

<script type="text/javascript" src="<?php echo base_url();?>js/register.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/datetimepicker_css.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/development-bundle/ui/ui.core.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tools.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/hoverIntent.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url();?> js/complete.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/sortir.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/validate.js"></script> 
<script type='text/javascript' src='<?php echo base_url();?>js/jquery.validate.js'></script>  

<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";
</script>
</head>

<body>

<?php echo ! empty($table) ? $table : ''; ?>

<table class="tablemaster">

<tr>
	<td> <h3 style="color:#000; font-family:Tahoma;"> Faktur Penjualan / Non PPN </h3> </td> 
	<td> <input type="button" value="Preview" onClick="window.open('<?php echo site_url('commission/print_nontax_invoice/'.$pono.'/'); ?>','mywindow','width=800,height=600,scrollbars=yes')">  </td>
</tr>

<tr>
	<td> <h3 style="color:#000; font-family:Tahoma;"> Faktur Penjualan / Non PPN ( Format ) </h3> </td> 
	<td> <input type="button" value="Preview" onClick="window.open('<?php echo site_url('commission/print_nontax_invoice/'.$pono.'/true/'); ?>','mywindow','width=800,height=600,scrollbars=yes')">  </td>
</tr>
	
</table>

</body>

</html>