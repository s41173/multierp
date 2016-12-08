<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title> <?php echo isset($title) ? $title : ''; ?>  </title>
<style media="all">
	table{ font-family:"Arial", Times, serif; font-size:9pt;}
	table th{ font-family:arial; font-size:9pt;}
	table tr.footer th { border-bottom:1pt solid black; }
	h4{ font-family:"Arial", Times, serif; font-size:14pt; font-weight:600; margin:0;}
	.clear{clear:both;}
	table th{ background-color:#CCC; color:#000; padding:4px; }
    p{ font-family:"Arial", Times, serif; font-size:12px; margin:0; padding:0;}
	legend{font-family:"Arial", Times, serif; font-size:13px; margin:0; padding:0; font-weight:600;}
	.tablesum{ font-size:13px;}
	.strongs{ font-weight:normal; text-align:center; font-size:9pt; border:0px dotted #000000; text-transform: capitalize; }
	.number{ font-weight:normal; text-align:right; font-size:9pt; border:0px dotted #000000; text-transform: capitalize; }
	.left{ font-weight:normal; text-align:left; font-size:9pt; border:0px dotted #000000; text-transform: capitalize; }
	.pro{ font-weight:normal; text-align:left; font-size:9pt; text-transform: capitalize; color:#00C; padding-top:10px; }
	.brand{ font-weight:normal; text-align:right; font-size:9pt; text-transform: capitalize; color:#F00; }
	.poder{ border-bottom:0px solid #000000; color:#0000FF;}
</style>
</head>

<body onLoad="">

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0">
 			<tr> <td> Currency </td> <td> : </td> <td> <?php echo $currency; ?> </td> </tr>       
            <tr> <td> Period </td> <td> : </td> <td> <?php echo tglin($start).' - '.tglin($end); ?> </td> </tr>
            <tr> <td> Run Date </td> <td> : </td> <td> <?php echo date('d-m-Y'); ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:500px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> </h4>
           <p style="margin:5px; padding:0;"> <?php echo $address; ?> <br> Telp. <?php echo $phone1.' - '.$phone2; ?> <br>
               Website : <?php echo $website; ?> &nbsp; &nbsp; Email : <?php echo $email; ?> </p>
	   </div>
	</center> <hr>
    
    <p style="text-align:center; font-size:14pt; font-weight:bold;"> Stock Card - Report </p>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border:0px solid #000; ">
	
		<table border="0" width="100%">
		   
           <!--<tr> <td class="pro"> SKU-001 &nbsp; | &nbsp; Kamus Bhs.Inggris </td> <td class="brand" colspan="11"> Paku / Newton </td> </tr> 
           <tr> header
  	       <th> Date </th> <th> Trans Code </th> <th> Unit </th> <th> IN </th> <th> Unit Price </th> <th> Amount </th> 
           <th> OUT </th> <th> Unit Price </th> <th> Amount </th>
           <th> Qty </th> <th> Unit Price </th> <th> Amount </th>
		   </tr>
           
           <tr>  opening
           <td class="strongs"> 01-01-1989 </td> <td class="strongs"> OB </td> <td class="strongs"> Pcs </td> 
           <td class="strongs"> </td> <td class="number"> </td> <td class="number"> </td>
           <td class="strongs"> </td> <td class="number"> </td> <td class="number"> </td>
           <td class="strongs"> 4 </td> <td class="number"> 48.000,- </td> <td class="number"> 518.000,- </td>
           </tr>
           
           <tr>  
           <td class="strongs"> 12-01-1989 </td> <td class="strongs"> PO-004 </td> <td class="strongs"> Pcs </td> 
           <td class="strongs"> 12 </td> <td class="number"> 15.000,- </td> <td class="number"> 215.000,- </td>
           <td class="strongs"> 8 </td> <td class="number"> 18.000,- </td> <td class="number"> 515.000,- </td>
           <td class="strongs"> 4 </td> <td class="number"> 48.000,- </td> <td class="number"> 518.000,- </td>
           </tr>
           
           <tr class="footer"> footer
  	       <th align="center" colspan="3"> Total Kamus Bahasa : </th> <th> 0 </th> <th align="right"> 10.000,- </th> 
           <th align="right"> 13.000,- </th> <th> 34 </th> 
           <th align="right"> 12.000,- </th> <th align="right"> 125.000,- </th> <th> 34 </th>
           <th align="right"> 185.000,- </th> <th align="right"> 2.125.000,- </th> 
		   </tr>-->
		    
		  <?php 
		  
			  function cek_price($qty,$price){ if ($qty > 0){ return $price; } else { return 0; } }
			  
			  function get_opening_qty($pid,$start)
			  {
				 $pro = new Products_lib();
				 $ps = new Period_lib();
				 $ledger = new Stock_ledger_lib();
				 $wt = new Warehouse_transaction();
				 
				 $qtyledger = $ledger->get_trans($pid,split_date($start,'n'),split_date($start,'Y'),'openqty');
				 $qtytrans = $wt->get_sum_transaction_open_balance($pid,$start);
				 
				 return intval($qtyledger+$qtytrans);
			  }
			  
			  function get_opening_balances($pid,$start)
			  {
				 $pro = new Products_lib();
				 $ps = new Period_lib();
				 $ledger = new Stock_ledger_lib();
				 $wt = new Warehouse_transaction();
				 
				 $qtyledger = $ledger->get_trans($pid,split_date($start,'n'),split_date($start,'Y'),'open_balance');
				 $qtytransin = $wt->get_sum_transaction_open_amount($pid,$start,'in');
				 $qtytransout = $wt->get_sum_transaction_open_amount($pid,$start,'out');
				 $result = $qtytransin-$qtytransout;
				 $result = @intval($result/get_opening_qty($pid,$start));
				 $qtyledger = @intval($qtyledger/get_opening_qty($pid,$start));
				 return $qtyledger+$result;
				 
			  }
			  
			  function calculate_hpp($lastqty=0,$lastbalance=0,$in=0,$out=0,$inbalance=0,$outbalance=0)
			  {
				  $res = 0;
				  if ($in > 0)
				  {
					//  $lastqty = $lastqty + $in;
					  $res = $in * $inbalance;
					  $res = $lastbalance + $res;
				  }
				  elseif ($out > 0)
				  {
					//  $lastqty = $lastqty - $out;
					  $res = $out * $outbalance;
					  $res = $lastbalance - $res;
				  }
				  
				  if ($lastqty == 0){ $res = $res; }else { $res = @intval($res / $lastqty); }
				  return $res;
			  }
			  
			  function get_last_qty($qty,$in,$out)
			  {
				  if ($in > 0){ return intval($qty + $in); } else { return intval($qty-$out); }
			  }
			  
			  function transaction($product,$start,$end)
			  {
				  $wt = new Warehouse_transaction();
				  $pro = new Products_lib();
				  $result = $wt->get_transaction($product,$start,$end)->result();
				  $num_rows = $wt->get_transaction($product,$start,$end)->num_rows();
				  $open = get_opening_qty($product,$start);
				  $hpp = get_opening_balances($product,$start);
				  $totpricein = 0;
				  $totpriceout = 0;
				  $openbalance = 0;
				  
				  if ($num_rows > 0)
				  {
					  $openbalance = intval($open * $hpp);
					  echo "
					  <tr>  
                      <td class=\"strongs\">".tglin($start)."</td> <td class=\"strongs\"> <b> Opening Balance </b> </td> 
					  <td class=\"strongs\">".$pro->get_unit($product)."</td> 
                      <td class=\"strongs\"> </td> <td class=\"number\"> </td> <td class=\"number\"> </td>
                      <td class=\"strongs\"> </td> <td class=\"number\"> </td> <td class=\"number\"> </td>
                      <td class=\"strongs\">".$open."</td> <td class=\"number\"> ".number_format($hpp).",- </td> 
					  <td class=\"number\"> ".number_format($openbalance).",- </td>
                      </tr>
					  ";
					  
					  $tot_in=0; $tot_out=0; $pricein=0; $priceout=0; $lastqty=0;
					  
					  // fungsi untuk get balance hpp
					  $restqty = $open;
					  $restbalance = $openbalance;
					  
					  foreach ($result as $res)
				      {
						$amountin = $res->in * $res->price;
						$amountout = $res->out * $res->price;
						
						$transqty = get_last_qty($restqty,$res->in,$res->out);
						$transhpp = calculate_hpp($transqty,$restbalance,$res->in,$res->out,cek_price($res->in,$res->price),cek_price($res->out,$res->price));
						
						$restqty = $transqty;
						
						if ($restqty == 0 && $transhpp < 0){ $restbalance = $transhpp; }else { $restbalance = $restqty * $transhpp;	   }
						
					    echo "
						 <tr>  
						 <td class=\"strongs\">".tglin($res->dates)."</td> <td class=\"left\">".$res->code."</td> 
						 <td class=\"strongs\">".$pro->get_unit($res->product)."</td> 
						 <td class=\"strongs\">".$res->in."</td> <td class=\"number\">".number_format(cek_price($res->in,$res->price)).",-</td> 
						 <td class=\"number\">".number_format($amountin).",-</td>
						 <td class=\"strongs\">".$res->out."</td> 
						 <td class=\"number\">".number_format(cek_price($res->out,$res->price))."</td> 
						 <td class=\"number\">".number_format($amountout)."</td>
						 <td class=\"strongs\">".$transqty."</td> <td class=\"number\">".number_format($transhpp).",- </td> 
						 <td class=\"number\">".number_format($restbalance).",- </td>
						 </tr>
							  ";
							 
							  
							  $tot_in = $tot_in + $res->in;
							  $pricein = $pricein + cek_price($res->in,$res->price);
							  $tot_out = $tot_out + $res->out;
							  $priceout = $priceout + cek_price($res->out,$res->price);
							  $lastqty = $res->balance;

							  
							  $totpricein = $totpricein + $amountin;
							  $totpriceout = $totpriceout + $amountout;
				     }
					 
					 echo "
					<tr class=\"footer\">
					<th align=\"center\" colspan=\"3\"> Total ".$pro->get_name($res->product)." : </th> 
					<th>".$tot_in."</th> <th align=\"right\">".number_format($pricein).",- </th> 
					<th align=\"right\">".number_format($totpricein).",- </th>
					<th>".$tot_out."</th> 
					<th align=\"right\">".number_format($totpriceout).",- </th> <th align=\"right\">".number_format($tot_out * $priceout).",- </th> 
					<th>".$restqty."</th>
					<th align=\"right\">".number_format($transhpp).",- </th> <th align=\"right\">".number_format($restbalance).",- </th>
					</tr>
					  ";  
				  }	  
			  }	
		  		  
		      $i=1; 
			  if ($reports)
			  {
				foreach ($reports as $res)
				{	
				   echo " 
				 <tr> <td class=\"pro\"> PRO-00".$res->id."&nbsp; | &nbsp;".$res->name."</td> 
					  <td class=\"brand\" colspan=\"11\">".$res->category." / ".$res->brand."</td> 
				 </tr> 
				 
				 <tr>
  	             <th> Date </th> <th> Trans Code </th> <th> Unit </th> <th> IN </th> <th> Unit Price </th> <th> Amount </th> 
                 <th> OUT </th> <th> Unit Price </th> <th> Amount </th>
                 <th> Qty </th> <th> Unit Price </th> <th> Amount </th> <!-- header -->
		         </tr>
				   ";
				 transaction($res->id,$start,$end);  
				   
				   $i++;
				}
			  } 
			  
		  ?>
          
		   
		</table>
	</div>

</div>

</body>
</html>
