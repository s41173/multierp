<?php
/*
uri	:	autocomplete-jquery
title	:	jQuery Autocomplete Input Text Field
view	:	experiment/autocomplete-jquery
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'development-bundle/themes/base/ui.all.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/jquery.fancybox-1.3.4.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/flexigrid.pack.css'; ?>");</style> <!-- flexigrid -->

<script type="text/javascript" src="<?php echo base_url();?>js/register.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tools.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/hoverIntent.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/sortir.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.maskedinput-1.3.min.js"></script>
<script type='text/javascript' src='<?php echo base_url();?>js/jquery.autocomplete.js'></script>
<script type="text/javascript" src="<?php echo base_url();?>js/validate.js"></script> 
<script type='text/javascript' src='<?php echo base_url();?>js/jquery.validate.js'></script>  

<script type='text/javascript' src='<?php echo base_url();?>js/liveclock_lite.js'></script>   <!-- clock -->
<script type='text/javascript' src='<?php echo base_url();?>js/flexigrid.pack.js'></script>   <!-- flexigrid -->


<script type='text/javascript'>
	var site = "<?php echo site_url();?>";
	$(function(){
		$('#autocomplete1').autocomplete({
			// serviceUrl berisi URL ke controller/fungsi yang menangani request kita
		    serviceUrl: site+'/autocomplete/search',
		    // fungsi ini akan dijalankan ketika user memilih salah satu hasil request
		    onSelect: function (suggestion) {
		        alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
		    }
		});	
	});
</script>

<div id="content">
	<div class='title'>
		jQuery Autocomplete Input Text Field
	</div>
	<h3>Instance 1</h3>
	Enter keyword : <input type="text" class='autocomplete' id="autocomplete1"/>
	<br /><br />
	<h3>Instance 2</h3>
	Enter keyword : <input type="text" class='autocomplete' id="autocomplete2"/>
</div>