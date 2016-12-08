<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title> <?php echo isset($title) ? $title : ''; ?>  </title>
<style media="all">
	table{ font-family:"Times New Roman", Times, serif; font-size:11px;}
	h4{ font-family:"Times New Roman", Times, serif; font-size:14px; font-weight:600;}
	.clear{clear:both;}
	table th{ background-color:#EFEFEF; padding:4px 0px 4px 0px; border-top:1px solid #000000; border-bottom:1px solid #000000;}
    p{ font-family:"Times New Roman", Times, serif; font-size:12px; margin:0; padding:0;}
	legend{font-family:"Times New Roman", Times, serif; font-size:13px; margin:0; padding:0; font-weight:600;}
	.tablesum{ font-size:13px;}
	.strongs{ font-weight:normal; font-size:12px; border-top:1px dotted #000000; }
	.right{ font-weight:normal; font-size:12px; border-top:1px dotted #000000; text-align:right; }
	.poder{ border-bottom:0px solid #000000; color:#0000FF;}
</style>
</head>

<body>

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0">
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo $rundate; ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> SO - Over Payment Report Details </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:1px dotted #000000; ">
	
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Customer </th> <th></th> <th></th> <th></th> <th> Amount </th>
		   </tr>
		   
		  <?php 
		  
		  function poder($cust)
		  {
			 $CI =& get_instance();
			 $poder = $CI->Sales_over_payment_model->report_cust($cust)->result();
			 $i=1;
		
			foreach ($poder as $res)
			{
			   echo "
			   <tr>
			   <td class=\"poder\"> </td>
			   <td class=\"poder\">$i</td>
               <td class=\"poder\"> SOV-00".$res->no."</td>
			   <td class=\"poder\"> SO-00".$res->sales_no." / CR-00".$res->ar_payment."</td>
			   <td class=\"poder\" align=\"right\">".number_format($res->balance)."</td>
			   <td class=\"poder\" align=\"right\">".number_format($res->over)."</td>
			   </tr>";
			   $i++;
			} 
		  }
		  
		  function totalcust($cust)
		  {
			 $CI =& get_instance();
			 $poder = $CI->Sales_over_payment_model->total_cust($cust);
			 return $poder['over'];
		  }
		  
		     $i=1; 
			  if ($reports)
			  {
				foreach ($reports as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
   					   <td class=\"strongs\">".$res->prefix.' '.$res->name."</td>
					   <td class=\"strongs\"> </td> 
					   <td class=\"strongs\"> </td> 
					   <td class=\"strongs\"> </td> 
					   <td class=\"right\">".number_format(totalcust($res->customer))."</td> 
				   </tr>";
				   poder($res->customer); echo "<br/>";
				   $i++;
				}
			 }  
			 
		  ?>
		   
		</table>
	</div>
	
	<div style="border:0px solid red; float:right; margin:15px 0px 0px 0px;">
	   <fieldset> <legend>Summary</legend>
			<table class="tablesum">			
				<tr> <td> Total </td> <td> : </td> <td align="right"> <?php echo number_format($total); ?> </td> </tr>
			</table>
		</fieldset>
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
