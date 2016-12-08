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

<body onLoad="window.print()">

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">

     <div style="border:0px solid red; float:left;">
            <table border="0">
                <tr> <td> Period </td> <td> : </td> <td> <?php echo $start; ?> to <?php echo $end; ?> </td> </tr>
                <tr> <td> Account </td> <td> : </td> <td> <?php echo $acc; ?> </td> </tr>
                <tr> <td> Run Date </td> <td> : </td> <td> <?php echo $rundate; ?> </td> </tr>
                <tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
            </table>
      </div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Reconciliation Report </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
    
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:1px dotted #000000; ">
	
		<table border="0" width="100%">
		   <tr> <th> No </th> <th> Date </th> <th> Ref </th> <th> Notes </th> <th> Debit </th> <th> Credit </th> </tr>
		   
		  <?php 
		  
		  
		      $i=1; 
			  if ($ledgers)
			  {
				foreach ($ledgers as $ledger)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".tglin($ledger->dates)."</td> 
					   <td class=\"strongs\">".$ledger->code.'-'.$ledger->no."</td> 
					   <td class=\"strongs\">".$ledger->notes."</td> 
					   <td align=\"right\" class=\"strongs\">".number_format($ledger->debit)."</td> 
					   <td align=\"right\" class=\"strongs\">".number_format($ledger->credit)."</td> 
				   </tr>";
				   $i++;
				}
			  }  
		  ?>
		   
		</table>
	</div>
    
    <div class="clear"></div>
    
    <div style="border:0px solid red; float:left;">
            <table border="0" width="200" style="font-size:9pt; margin-top:0.5cm;">

             <tr>
				<td colspan="2"> <label> Current Balance </label> </td> <td>:</td> <td> <b> <?php echo number_format($current); ?> </b> </td>
			</tr>
            
            <tr>
				<td colspan="2"> <label> Difference </label> </td> <td>:</td> <td> <b> <?php echo number_format($diff); ?> </b> </td>
			</tr>
                
            </table>
      </div>
    
    <div style="border:0px solid red; float:right;">
            <table border="0" align="right" width="180" style="font-size:9pt; margin-top:0.5cm;">
                
            <tr> 
            <td align="right"> <label> Debit : </label> <br /> <b> <?php echo number_format($debit); ?> </b> </td> 
			<td align="right"> <label> Credit : </label> <br /> <b> <?php echo number_format($credit); ?> </b> <br/> </td> 
            </tr>
               
            </table>
      </div>
      
      <div class="clear"></div>
	

	<div style="border:0px solid red; float:left; margin:15px 0px 0px 0px;">
		<p> Prepared By : <br/> <br/> <br/>  <br/> <br/>
		    (_______________________) 
		</p>
	</div>

</div>

</body>
</html>
