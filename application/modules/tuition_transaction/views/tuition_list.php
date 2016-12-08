<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title> <?php echo isset($title) ? $title : ''; ?>  </title>

<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'development-bundle/themes/base/ui.all.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/jquery.fancybox-1.3.4.css'; ?>");</style>

 <script src="<?php echo base_url();?>js/PrinceFilter/jquery-1.7.2.min.js" type="text/javascript"></script>
 <script src="<?php echo base_url();?>js/PrinceFilter/princeFilter.JQuery.js" type="text/javascript"></script>
 <style type="text/css">@import url("<?php echo base_url().'js/PrinceFilter/prettifys.css'; ?>");</style>
 
  <script type="text/javascript">
	$(document).ready(function () {
		$('#tblData1').princeFilter();
	});
  </script>

</head>

<body> 

<?php

	$atts = array(
	  'class'      => 'buttons',
	  'title'      => 'Details',
	  'width'      => '600',
	  'height'     => '300',
	  'scrollbars' => 'no',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 300)/2)+\'',
	);
	
	function get_month_from_period($monthperiod)
	{
		$ps = new Payment_status_lib();
		return $ps->months_from_period($monthperiod);
	}

?>

<div id="webadmin">

    <!-- Pembayaran -->
    
    <h3 style="font-size:12pt;" align="left"> Pendapatan Realisasi : </h3>
	<table border="0" width="100%" class="tablemaster">
    	<thead>
    	<tr> <th> Tuition </th> <th> Nominal </th> <th> Qty </th> <th> Amount </th> </tr>
        </thead>
        
        <tbody>
        
        <?php
        
			function counter($dept,$grade,$monthperiod,$financialyear,$type=null,$scholar=null,$fee)
			{
				$tt = new Tuition_lib();
				return $tt->total_paid($dept,$grade,$monthperiod,$financialyear,$type,$scholar,$fee);
			}
			
			function cek_spp($spp,$aid)
			{ if ($aid > $spp){ return 0; }else{ return intval($spp - $aid); }}
		
			if ($fee)
			{
				$tot = 0;
				$totamount = 0;
				foreach ($fee as $res)
				{
					echo "
	<tr>
	<td class=\"strongs\" align=\"left\"> ".$res->name." </td>
	<td class=\"strongs\" align=\"right\"> ".number_format(cek_spp($res->school,$res->aid))." </td>
	<td class=\"strongs\" align=\"center\"> ".counter($dept,$grade,$monthperiod,$year,null,null,$res->id)." </td>
	<td class=\"strongs\" align=\"right\"> ".
	number_format(intval(cek_spp($res->school,$res->aid)*counter($dept,$grade,$monthperiod,$year,null,null,$res->id))).
	",- </td> 
	</tr>
					";
					$tot = intval($tot + counter($dept,$grade,$monthperiod,$year,null,null,$res->id));
					$totamount = $totamount + intval(cek_spp($res->school,$res->aid)*counter($dept,$grade,$monthperiod,$year,null,null,$res->id));
				}
			}
			
		?>
        <tr> 
<th> <?php echo anchor_popup(site_url("tuition_transaction/get_details/".$dept."/".$grade."/".$monthperiod."/".$year), '[ Details ]', $atts) ?> </th>
        <th align="right"> Total : </th> 
        <th class="red" align="center"> <?php echo $tot; ?> </th> 
        <th class="red" align="right"> <?php echo number_format($totamount); ?>,- </th> </tr>
        </tbody>
    </table>
    <!-- Pembayaran -->
    
    <br>
    
    <!-- Piutang -->
    
    <h3 style="font-size:12pt;" align="left"> Piutang Realisasi : </h3>
	<table border="0" width="100%" class="tablemaster">
    	<thead>
    	<tr> <th> Tuition </th> <th> Nominal </th> <th> Qty </th> <th> Amount </th> </tr>
        </thead>
        
        <tbody>
        
        <?php
        
			function counters($dept,$grade,$monthperiod,$year,$fee)
			{
				$ps = new Payment_status_lib();
				return $ps->get_miss_recapitulation_based_fee($dept,$grade,$monthperiod,$year,$fee);
			}
		
			if ($fee)
			{
				$tot = 0;
				$totamount = 0;
				foreach ($fee as $res)
				{
					echo "
	<tr>
	<td class=\"strongs\" align=\"left\"> ".$res->name." </td>
	<td class=\"strongs\" align=\"right\"> ".number_format(cek_spp($res->school,$res->aid))." </td>
	<td class=\"strongs\" align=\"center\"> ".counters($dept,$grade,$monthperiod,$year,$res->id)." </td>
	<td class=\"strongs\" align=\"right\"> "
	.number_format(intval(cek_spp($res->school,$res->aid)*counters($dept,$grade,$monthperiod,$year,$res->id))).
	",- </td> 
	</tr>
					";
					$tot = intval($tot + counters($dept,$grade,$monthperiod,$year,$res->id));
					$totamount = $totamount + intval(cek_spp($res->school,$res->aid)*counters($dept,$grade,$monthperiod,$year,$res->id));
				}
			}
			
		?>
        <tr> 
<th> <?php echo anchor_popup(site_url("payment_status/get_details/".$dept."/".$grade."/".$monthperiod."/".$year), '[ Details ]', $atts) ?> </th>
        <th align="right"> Total : </th> 
        <th class="red" align="center"> <?php echo $tot; ?> </th> 
        <th class="red" align="right"> <?php echo number_format($totamount); ?>,- </th> </tr>
        </tbody>
    </table>
    <!-- Piutang -->
    
    <br>
    
    <!-- Penyesuaian Hutang -->
    
    <h3 style="font-size:12pt;" align="left"> Penyesuaian Hutang : </h3>
	<table border="0" width="100%" class="tablemaster">
    	<thead>
    	<tr> <th> Tuition </th> <th> Nominal </th> <th> Qty </th> <th> Amount </th> </tr>
        </thead>
        
        <tbody>
        
        <?php
        
			function hutang($dept,$grade,$monthperiod,$financialyear,$fee)
			{
				$ps = new Payment_status_lib();
				$feelib = new Regcost_lib();
				$month = $ps->months_from_period($monthperiod);
				$result = $ps->get_front_recapitulation($dept,$grade,$month,$financialyear,'detail');
				$num=0;
				
				if ($result)
				{
					foreach($result as $res)
					{ if ($feelib->get_by_student($res->student_id) == $fee){ $num = $num + 1; }}
				}
				return $num;
			}
		
			if ($fee)
			{
				$tot = 0;
				$totamount = 0;
				foreach ($fee as $res)
				{
					echo "
	<tr>
	<td class=\"strongs\" align=\"left\"> ".$res->name." </td>
	<td class=\"strongs\" align=\"right\"> ".number_format(cek_spp($res->school,$res->aid))." </td>
	<td class=\"strongs\" align=\"center\"> ".hutang($dept,$grade,$monthperiod,$year,$res->id)." </td>
	<td class=\"strongs\" align=\"right\"> "
	.number_format(intval(cek_spp($res->school,$res->aid)*hutang($dept,$grade,$monthperiod,$year,$res->id))).
	",- </td> 
	</tr>
					";
					$tot = intval($tot + hutang($dept,$grade,$monthperiod,$year,$res->id));
					$totamount = $totamount + intval(cek_spp($res->school,$res->aid)*hutang($dept,$grade,$monthperiod,$year,$res->id));
				}
			}
			
		?>
        <tr> 
<th> 
<?php echo anchor_popup(site_url("payment_status/front_adjustment/".$dept."/".$grade."/".get_month_from_period($monthperiod)."/".$year), '[ Details ]', $atts) ?> </th>
        <th align="right"> Total : </th> 
        <th class="red" align="center"> <?php echo $tot; ?> </th> 
        <th class="red" align="right"> <?php echo number_format($totamount); ?>,- </th> </tr>
        </tbody>
    </table>
    <!-- Penyesuaian Hutang --> <br>
    
    
    
    
</div>
</body>

