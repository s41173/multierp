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

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Stock - Out Report Category Details </h4>
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
 	       <th> No </th> <th> Date </th> <th> Code </th> <th> SKU </th> <th> Product </th> <th> Qty </th> <th> Amount </th> <th> Staff </th> 
		   </tr>
           </thead>
		   
          <tbody> 
		  <?php 
		  
		     $i=1; 
			  if ($reports_category)
			  {
				  function product($pid)
				  {
					 $pro = new Products_lib();
					 return $pro->get_name($pid); 
				  }
				  
				foreach ($reports_category as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".tglin($res->dates)."</td> 
					   <td class=\"strongs\"> BPBG-00".$res->no."</td>
					   <td class=\"strongs\"> PRO-00".$res->product."</td>
					   <td class=\"strongs\">".product($res->product)."</td>
   					   <td class=\"strongs\">".$res->qty."</td> 
  					   <td class=\"strongs\">".intval($res->qty*$res->price)."</td> 
					   <td class=\"strongs\" align=\"center\">".$res->staff."</td> 
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
