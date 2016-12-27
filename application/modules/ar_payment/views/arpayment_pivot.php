<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title> <?php echo isset($title) ? $title : ''; ?>  </title>
<style media="all">
	table{ font-family:"Tahoma", Times, serif; font-size:11px;}
	h4{ font-family:"Tahoma", Times, serif; font-size:14px; font-weight:600;}
	.clear{clear:both;}
	table th{ background-color:#EFEFEF; padding:4px 0px 4px 0px; border-top:1px solid #000000; border-bottom:1px solid #000000;}
    p{ font-family:"Tahoma", Times, serif; font-size:12px; margin:0; padding:0;}
	legend{font-family:"Tahoma", Times, serif; font-size:13px; margin:0; padding:0; font-weight:600;}
	.tablesum{ font-size:13px;}
	.strongs{ font-weight:normal; font-size:12px; border-top:1px dotted #000000; }
	.poder{ border-bottom:0px solid #000000; color:#0000FF;}
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
	
	<div style="border:0px solid red; float:right;">
		<table border="0">
      		<tr> <td> Currency </td> <td> : </td> <td> <?php echo $currency; ?> </td> </tr>
			<tr> <td> Account </td> <td> : </td> <td> <?php echo $acc; ?> </td> </tr>
		</table>
	</div>


	<center>
	   <div style="border:0px solid green; width:330px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> AR - Payment Report - Transaction </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:1px dotted #000000; ">
		
        <div id='jqxWidget'>
        <div style='margin-top: 10px;' id="output"> </div>
        </div>
        
		<table id="input" border="0" width="100%">
		   
           <thead>
           <tr>
 	       <th> No </th> <th> Date </th> <th> Code </th> <th> Sales </th> <th> Customer </th> <th> Cur </th> <th> Acc </th> <th> Amount </th> <th> Tax </th> <th> Cost </th> <th> Balance </th> <th> Log </th> 
		   </tr>
           </thead>
		   
          <tbody> 
		  <?php 
		  
		      function acc($val=null)
			  {
				switch ($val)
				{
					case 'pettycash': $val = 'Petty cash'; break;
					case 'cash': $val = 'Cash'; break;
					case 'bank': $val = 'Bank'; break;
				}
				return $val;
			  }
			  
			  function status($val) { if ($val==1){ $val = 'settled'; } else{ $val = 'debt'; } return $val; }
			  function tax($val) { if ($val==1){ $val = 'tax'; } else{ $val = 'non tax'; } return $val; }
			  		  
		      $i=1; 
			  if ($reports)
			  {
				foreach ($reports as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".tglin($res->dates)."</td> 
					   <td class=\"strongs\"> CR-00".$res->no."</td>
					   <td class=\"strongs\"> SO-00".$res->transno."</td>
   					   <td class=\"strongs\">".$res->prefix.' '.$res->name."</td> 
					   <td class=\"strongs\">".$res->currency."</td> 
					   <td class=\"strongs\">".acc($res->acc)."</td> 
					   <td class=\"strongs\" align=\"right\">".$res->amount."</td>
					   <td class=\"strongs\" align=\"right\">".$res->tax."</td> 
					   <td class=\"strongs\" align=\"right\">".$res->cost."</td>
					   <td class=\"strongs\" align=\"right\">".intval($res->cost+$res->amount+$res->tax)."</td>
					   <td class=\"strongs\" align=\"center\">".$res->log."</td> 
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
