<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title> Purchase Invoice </title>

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

function close_window()
{
	window.close();
}

</script>
</head>

<body>

<?php echo ! empty($table) ? $table : ''; ?>

<table class="tablemaster">
	
<tr>
<td> <h3 style="color:#000; font-family:Tahoma;"> Honor - Recapitulation </h3> </td> 
<td> <input type="button" value="Preview" onClick="window.open('<?php echo site_url('payroll/honor_invoice/'.$pono.'/'); ?>','mywindow', 
     'scrollbars = yes','width=800,height=600'), window.close();">  </td>
</tr>

<tr>
<td> <h3 style="color:#000; font-family:Tahoma;"> Salary - Recapitulation </h3> </td> 
<td> <input type="button" value="Preview" onClick="window.open('<?php echo site_url('payroll/salary_invoice/'.$pono.'/'); ?>','mywindow',
 'scrollbars = yes','width=800,height=600'), window.close();">  </td>
</tr>

<tr>
<td> <h3 style="color:#000; font-family:Tahoma;"> Finance - Recapitulation </h3> </td> 
<td> <input type="button" value="Preview" onClick="window.open('<?php echo site_url('payroll/finance_invoice/'.$pono.'/'); ?>','mywindow',
'scrollbars = yes','width=500,height=550'), window.close();">  </td>
</tr>
	
</table>

</body>

</html>