	<style>
		#tombol{ border:1px solid #AAAAAA; padding:2px 2px 2px 2px; margin:0px 2px 0px 2px;}
		#tombol:hover{ background-color:#CCCCCC; color:#000099;}
		
	    .refresh{ border:1px solid #AAAAAA; color:#000; padding:2px 5px 2px 5px; margin:0px 2px 0px 2px; background-color:#FFF;}
		.refresh:hover{ background-color:#CCCCCC; color: #FF0000;}
		.refresh:visited{ background-color:#FFF; color: #000000;}
		
	</style>
	
	<script type="text/javascript">
	
		var uri = "<?php echo site_url('installation/remove'); ?>";
		var uri1 = "<?php echo site_url('installation/backup'); ?>";
		
		function myFunction()
		{
		  var pin;
		  pin=prompt("Enter PIN..!","");
		
		  if (pin != '')
		  {
			window.location.href=uri+"/"+pin;
//			location.reload(true);
		  }
		  else { pin=prompt("Enter PIN..!",""); }
		}
		
		function myFunction1()
		{
		  var pin;
		  pin=prompt("Enter PIN..!","");
		
		  if (pin != '')
		  {
			window.location.href=uri1+"/"+pin;
//			location.reload(true);
		  }
		  else { pin=prompt("Enter PIN..!",""); }
		}
	
	</script>

<div id="webadmin">
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>

</div>

<div id="webadmin2">

     <?php echo ! empty($table) ? $table : ''; ?>
	 <div class="paging"> <?php echo ! empty($pagination) ? $pagination : ''; ?> </div>
	
	<?php  
	
		$atts = array(
			  'class'      => 'refresh',
			  'title'      => 'Checkout Invoice',
			  'width'      => '650',
			  'height'     => '300',
			  'scrollbars' => 'no',
			  'status'     => 'yes',
			  'resizable'  => 'yes',
			  'screenx'    =>  '\'+((parseInt(screen.width) - 650)/2)+\'',
			  'screeny'    =>  '\'+((parseInt(screen.height) - 300)/2)+\'',
		);
	
	?>
	
	<table align="right" style="margin:10px 0px 0 0; padding:3px; " width="100%" bgcolor="#D9EBF5">
	<tbody>
		<tr> 
		   <td align="right"> 
		   		<?php //echo anchor(site_url("installation/save"), 'SAVE & BACKUP', $atts); ?>
				<?php //echo anchor(site_url("installation/remove"), 'REMOVE CONFIGURATION', $atts); ?>
				<button class="" onclick="myFunction1()"> ANNUAL &amp; CLOSING </button>
				<button class="" onclick="myFunction()"> REMOVE CONFIGURATION </button>
		   </td> 
		</tr>
	</tbody>
	</table>
	
	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>
</div>



<!-- batas -->

