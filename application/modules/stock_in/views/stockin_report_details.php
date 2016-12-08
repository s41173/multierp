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
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Stock - In Report Details </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:1px dotted #000000; ">
	
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Date </th> <th> Code </th> <th> Purchase </th> <th> Vendor / Staff </th> <th> Log </th> 
		   </tr>
		   
		   <!--<tr>
		   		<td class="strongs"> 1 </td>
				<td class="strongs"> Wed, 12 Jan 1989 </td>
				<td class="strongs"> PO-001 </td>
				<td class="strongs"> PT. Dswip Kreasindo </td>
				<td class="strongs" align="right"> 1.000.000 </td>
				<td class="strongs" align="right"> 100.000 </td>
				<td class="strongs" align="right"> 1.100.000 </td>
				<td class="strongs" align="right"> 600.000 </td>
				<td class="strongs" align="right"> 500.000 </td>
		   		<td class="strongs" align="center"> Settled </td>
		   </tr> -->
		   
		  <?php 
			    
			  function get_name($pid){ $res = new Products_lib(); return $res->get_name($pid); }
		      function get_unit($pid){ $res = new Products_lib(); return $res->get_unit($pid); }
				
			  function poder($po)
			  {
				 $CI =& get_instance();
				 $poder = $CI->Stock_in_model->get_purchase_list($po)->result();
				 $i=1;
			
				foreach ($poder as $res)
				{
				   echo "
				   <tr>
				   <td class=\"poder\"> </td>
				   <td class=\"poder\"> </td>
				   <td class=\"poder\">$i</td>
				   <td class=\"poder\">".get_name($res->product)."</td>
				   <td class=\"poder\">".$res->qty.' '.get_unit($res->product)."</td>
				   </tr>";
				   $i++;
				} 
			
			  }
			  
		  
		     $i=1; 
			  if ($reports)
			  {
				foreach ($reports as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".tgleng($res->dates)."</td> 
					   <td class=\"strongs\"> BTB-00".$res->no."</td>
					   <td class=\"strongs\"> PO-00".$res->purchase."</td> 
   					   <td class=\"strongs\">".$res->staff."</td> 
					   <td class=\"strongs\" align=\"center\">".$res->log."</td> 
				   </tr>";
				   poder($res->purchase); echo "<br/>";
				   $i++;
				}
			 }  
			 
		  ?>
		   
		</table>
	</div>
	
	<div style="border:0px solid red; float:left; margin:15px 0px 0px 0px;">
		<p> Prepared By : <br/> <br/> <br/>  <br/> <br/>
		    (_______________________) 
		</p>
	</div>
	
	<div style="border:0px solid red; float:left; margin:15px 0px 0px 40px;">
		<p> Approval By : <br/> <br/> <br/>  <br/> <br/>
		    (_______________________) 
		</p>
	</div>

</div>

</body>
</html>
