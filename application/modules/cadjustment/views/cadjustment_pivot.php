<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title> <?php echo isset($title) ? $title : ''; ?>  </title>
<style media="all">
	table{ font-family:"Arial", Times, serif; font-size:11px;}
	h4{ font-family:"Arial", Times, serif; font-size:14px; font-weight:600;}
	.clear{clear:both;}
	table th{ background-color:#EFEFEF; padding:4px 0px 4px 0px; border-top:1px solid #000000; border-bottom:1px solid #000000;}
    p{ font-family:"Arial", Times, serif; font-size:12px; margin:0; padding:0;}
	legend{font-family:"Arial", Times, serif; font-size:13px; margin:0; padding:0; font-weight:600;}
	.tablesum{ font-size:13px;}
	.strongs{ font-weight:normal; font-size:12px; border-top:1px dotted #000000; }
	.poder{ border-bottom:0px solid #000000; color:#0000FF; font-size:9pt;}
	.red{ border-bottom:0px solid #000000; color:#900; font-size:10pt;}
</style>
 
    <link rel="stylesheet" type="text/css" href="<?php echo base_url().'js/pivot/' ?>pivot.css">
	<script type="text/javascript" src="<?php echo base_url().'js/pivot/' ?>jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/pivot/' ?>jquery-ui-1.9.2.custom.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/pivot/' ?>jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/pivot/' ?>pivot.js"></script>  
    
    <script type="text/javascript">
        $(document).ready(function () {
          	
			var input = $("#input")
			$("#output").pivotUI(input);
			$("#input").hide();
        });
    </script>
    
   

</head>

<body>

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0">
			<tr> <td> Period </td> <td> : </td> <td> <?php echo tglin($start); ?> to <?php echo tglin($end); ?> </td> </tr>
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo $rundate; ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	      <h4> <?php echo isset($company) ? $company : ''; ?> <br> Contract Adjustment - Report (Pivot Table) </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:0px dotted #000000; ">
	
    	<div id='jqxWidget'>
        <div style='margin-top: 10px;' id="output"> </div>
        </div>
       
       <table id="input" border="0" width="100%">
		   <thead>
           <tr>
 	       <th> No </th> <th> Order No </th> <th> Date </th> <th> Contract </th> <th> Notes </th> <th> User </th> <th> Balance </th> <th> Log </th> 
		   </tr>
           </thead>
		   
          <tbody> 
		  <?php 	
		  
		  	  function user($val)
			  {
				  $user = new Admin_lib();
				  return $user->get_username($val);
			  }
		  
		      $i=1; 
			  if ($cadjustments)
			  {
				foreach ($cadjustments as $sales)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
   					   <td class=\"strongs\"> COA-00".$sales->no."</td> 
					   <td class=\"strongs\">".tglin($sales->dates)."</td>
					   <td class=\"strongs\"> CO-00".$sales->contract_no."</td>  
					   <td class=\"strongs\">".$sales->notes."</td>
					   <td class=\"strongs\">".user($sales->user)."</td> 
					   <td class=\"strongs\" align=\"right\">".intval($sales->total)."</td>
					   <td class=\"strongs\" align=\"right\">".$sales->log."</td>  
				   </tr>";
				   $i++;
				}
			  }  
		  ?>
		 </tbody>  
		</table>
       
	</div>

</div>

</body>
</html>
