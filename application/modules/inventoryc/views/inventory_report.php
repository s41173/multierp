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
	.poder{ border-bottom:0px solid #000000; color:#0000FF;}
</style>
</head>

<body onLoad="">

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0">
        	<tr> <td> Category </td> <td> : </td> <td> <?php echo $category; ?> </td> </tr>
            <tr> <td> Period </td> <td> : </td> <td> <?php echo tglin($start); ?> &nbsp; - &nbsp; <?php echo tglin($end); ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Inventory Report </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:1px dotted #000000; ">
	
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Code </th> <th> Category </th> <th> Room </th> <th> Name </th> <th> Purchase Date </th> <th> Price </th>
		   </tr>
		   
		  <?php 
		  
		  
		      $i=1; 
			  $totalprice=0; 
			  if ($inventory)
			  { 
				foreach ($inventory as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\"> IN-0".$res->no."</td> 
					   <td class=\"strongs\">".$res->category."</td> 
					   <td class=\"strongs\">".$res->room."</td> 
					   <td class=\"strongs\">".$res->name."</td> 
					   <td class=\"strongs\">".tglin($res->buying)."</td> 
					   <td align=\"right\" class=\"strongs\">".number_format($res->price)."</td> 
				   </tr>";
				   $totalprice = intval($totalprice + $res->price);
				   $i++;
				}
			  }  
		  ?>
		   
           <tr> <td colspan="4"></td> <td class="strongs"> <b> Total : </b> </td> 
                <td class="strongs" align="right"> <b> <?php echo number_format($totalprice); ?> </b> </td> 
           </tr>
           
		</table>
	</div>
	

	<div style="border:0px solid red; float:left; margin:15px 0px 0px 0px;">
		<p> Prepared By : <br/> <br/> <br/>  <br/> <br/>
		    (_______________________) 
		</p>
	</div>

</div>

</body>
</html>
