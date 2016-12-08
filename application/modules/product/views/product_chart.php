<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'development-bundle/themes/base/ui.all.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/jquery.fancybox-1.3.4.css'; ?>");</style>

<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?php echo base_url().'js/' ?>canvasjs.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.maskedinput-1.3.min.js"></script>


<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";
var site = "<?php echo site_url();?>";
</script>

<script type="text/javascript">
	
$(document).ready(function(){
	
	$("#tyear,#tyear1").mask("2099");
	
	var ins = "<?php echo $ins?>";
	
    var outs = "<?php echo $outs?>";
	var url6 = (function () {
    var url6 = null;
    $.ajax({
        'async': false,
        'global': false,
        'url': outs,
        'dataType': "json",
        'success': function (data) {
            url6 = data;
        }
    });
		return url6;
	})(); 
	
	var types = "<?php echo $type;?>";   
	
	if (types == 0)
	{
		var url2 = "<?php echo $chart;?>";
		$.getJSON(url2, function (result) {
	
			var chart2 = new CanvasJS.Chart("chartContainer", {
				theme: "theme1",//theme1
				axisX:{title: "Stock Category - Monthly", },
				animationEnabled: true, 
				data: [
					{
						type: "column",
						dataPoints: result
					}
				]
			});
	
			chart2.render();
		});
	}
	else if (types == 1)
	{
		var url3 = "<?php echo $ins;?>";
		$.getJSON(url3, function (result) {
	
			var chart3 = new CanvasJS.Chart("chartContainer", {
				theme: "theme1",//theme1
				axisX:{title: "Stock IN vs OUT - Monthly", },
				animationEnabled: true, 
				data: [
					{
						type: "column",
						dataPoints: result
					},
					{
					type: "column",
					dataPoints: url6
				  }
				]
			});
	
			chart3.render();
		});
	}
	else if (types == 2)
	{
		var url4 = "<?php echo $annual;?>";
		$.getJSON(url4, function (result) {
	
			var chart4 = new CanvasJS.Chart("chartContainer", {
				theme: "theme1",//theme1
				axisX:{title: "Stock List - Annual", },
				animationEnabled: true, 
				data: [
					{
						type: "column",
						dataPoints: result
					}
				]
			});
	
			chart4.render();
		});
	}
	
	


/* end document */		
});

	
</script>

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

<body onLoad="cek_session();" onUnload="">
<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Inventory Chart </legend>
	 <form name="chartform1" method="post">
        <table>
        <tr> <td> <label> Period </label> : &nbsp;
                  <?php $js = 'class="required"'; echo form_dropdown('cmonth', $combo, isset($default['month']) ? $default['month'] : '', $js); ?> &nbsp;                   
                  <input type="text" class="required" name="tyear" id="tyear" value="<?php echo $years; ?>" size="4">  &nbsp;
        </td>
        <td>
          <select name="ctype"> 
          <option value="0"> Stock Category - Monthly </option> 
          <option value="1"> Stock IN vs OUT - Monthly </option> 
          <option value="2"> Stock List - Annual </option> 
          </select> 
        </td> 
        <td> <input class="field" type="submit" value="SUBMIT" /> </td>
        </tr>
        </table>
      </form>
    
     <div id="chartContainer" style="height: 400px; width: 100%;"> </div>  	  
	</fieldset>
</div>
</body>
