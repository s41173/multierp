<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title> <?php echo isset($title) ? $title : ''; ?>  </title>
<style media="all">
	table{ font-family:"arial", Times, serif; font-size:11px;}
	h4{ font-family:"arial", Times, serif; font-size:14px; font-weight:600;}
	.clear{clear:both;}
	table th{ background-color:#EFEFEF; padding:4px 2px 4px 2px; border-top:1px solid #000000; 
	          text-transform:uppercase; border-bottom:1px solid #000000;}
    p{ font-family:"arial", Times, serif; font-size:12px; margin:0; padding:0;}
	legend{font-family:"arial", Times, serif; font-size:13px; margin:0; padding:0; font-weight:600;}
	.tablesum{ font-size:13px;}
	.strongs{ font-weight:bold; }
	.right{ font-size:12px; border-top:1px dotted #000000; text-align:right;  padding:2px; border-right:1px solid #000; }
    .center{ font-size:12px; border-top:1px dotted #000; padding:2px; border-right:1px solid #000; text-align:center; }
	.left{ font-size:12px; border-top:1px dotted #000; padding:2px; border-right:1px solid #000; text-align:left;  }
	.poder{ border-bottom:0px solid #000000; color:#0000FF;}
	.blue{ color:#00F;}
	.red{ color:#F00;}
	a.button{ text-decoration:none; border:1px solid #CCC; padding:3px;}
	a.button:hover{ background-color:#EFEFEF; color:#F00;}
</style>

<link rel="stylesheet" type="text/css" href="<?php echo base_url().'css/' ?>jquery.fancybox-1.3.4.css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.fancybox-1.3.4.pack.js"></script>


<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";
var site = "<?php echo site_url();?>";
</script>

<script type="text/javascript">

	$(document).ready(function(){
		
		$("a.button").fancybox({
			'titlePosition'		: 'outside',
			'width': '500px',
			'overlayColor'		: '#000',
			'overlayOpacity'	: 0.9,
			'href': this.href
		});
		/* end document */		
    });

</script>

</head>

<body>

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0">
    		<tr> <td> Department </td> <td> : </td> <td> <?php echo isset($department) ? $department : ''; ?> </td> </tr>
			<tr> <td> Bulan </td> <td> : </td> <td> <?php echo isset($monthname) ? $monthname : ''; ?> </td> </tr>
            <tr> <td> Financial Year </td> <td> : </td> <td> <?php echo isset($year) ? $year : ''; ?> </td> </tr>
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo tglin(date('d-m-Y')); ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo isset($log) ? $log : ''; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Recapitulation Of Computer Fees </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:1px dotted #000000; ">
	
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <!-- 1 -->
           <th> Dept </th> <!-- 2 -->
           <th> Kelas </th> <!-- 3 -->
           <th> Jml Mrd Bln Lalu </th> <!-- 4 -->
           <th> Jml Mrd Baru </th> <!-- 5 -->
           <th> Berhenti </th> <!-- 7 -->
           <th> Jml Mrd Bulan Ini </th> <!-- 8 -->
           <th> Jml Trnksi <br> Bulan <br> Lalu </th> <!-- 9 -->
           <th> Piutang <br> Bulan Lalu </th> <!-- 10 -->
           <th> Penyesuaian Hutang </th> <!-- 11 -->
           <th> Jml Pembayaran BJ </th> <!-- 12 -->
           <th> Jml Pembayaran Tunggakan </th> <!-- 13 -->
           <th> Bayar Di Muka </th> <!-- 14 -->
           <th> Retur </th> <!-- 15 -->
           <th> Jml Piutang Bln Ini </th> <!-- 16 -->
           <th> Jumlah Pembayaran </th> <!-- 17 -->
           <th> Sisa Piutang Bln Ini </th> <!-- 18 -->
           <th> Hutang Bln Ini </th> <!-- 19 -->
           <th> Besar Uang SPP </th> <!-- 20 -->
           <th> Jml Trnsksi </th> <!-- 21 -->
           <th> Jml Realisasi </th> <!-- 22 -->
           <th> Keterangan </th> <!-- 23 -->
		   </tr>
           
           <?php
           

				$atts = array(
				  'class'      => 'buttons',
				  'title'      => 'Details',
				  'width'      => '450',
				  'height'     => '600',
				  'scrollbars' => 'yes',
				  'status'     => 'yes',
				  'resizable'  => 'yes',
				  'screenx'    =>  '\'+((parseInt(screen.width) - 450)/2)+\'',
				  'screeny'    =>  '\'+((parseInt(screen.height) - 600)/2)+\'',
				);
		
		   		// fungsi untuk melihat tunggakan 
				function tunggakan($dept,$grade,$monthperiod,$year)
			    { 
					$payment = new Payment_status_lib();
					return $payment->get_miss_recapitulation($dept,$grade,$monthperiod,$year);
			    }
				
				// fungsi untuk menyusun penyesuaian hutang
				function penyesuaian_hutang($dept,$grade,$month,$year)
			    { 
					$payment = new Payment_status_lib();
					return intval($payment->get_front_recapitulation($dept,$grade,$month,$year));
			    }
		   
				function get_fee($dept,$grade)
				{  
     			   $cost = new Regcost_lib();
				   $gr = new Grade_lib();
		           $cost = $cost->get_by_id($gr->get_fee($grade));
				   if ($cost){ return $cost->computer; }
				}
				
				// fungsi unruk menghitung total pembayar
				function paid($dept,$grade,$monthperiod,$financialyear,$type,$scholar)
				{
					$tt = new Tuition_lib();
					return $tt->total_paid($dept,$grade,$monthperiod,$financialyear,$type);
				}
				
				// fungsi untuk menghitung total realisasi
				function realisasi($dept,$grade,$month,$financialyear,$type=null,$scholar=null)
				{
					$tt = new Tuition_lib();
				    $res = $tt->total_realisasi_based_financial($dept,$grade,$month,$financialyear,$type,$scholar);
					return intval($res['computer']);
				}
				
				function retur($dept=null,$faculty=null, $grade=null, $month, $ayear)
				{
					$ps = new Payment_status_lib();
					$year = $ps->year_name($ps->months_periode($month),$ayear);
					
					$ar = new Ar_tuition_lib();
					$res = $ar->total_student($dept,$faculty,$grade,$month,$year,$ayear);
					return intval($res);
				}
		   		
					$grade = new Grade_lib();
					$grade = $grade->get($dept);
					$dept = new Dept_lib();
					
					// ============================
					$ps = new Period();
			        $st = new Student();
			        $payment = new Payment_status_lib();
			    //    $year = new Financial_lib();
			    //    $year = $year->get();
			        $tt = new Tuition_lib();
					$model = new Payment_status_model();
					$retur = new Ar_tuition_lib();
					$recap = new Student_recap_lib();
					$recap_trans = new Student_recap_trans_lib();
					
					$ps->get();
			        $now = 'p'.$payment->months_periode($month);
					
					$totsiswa = 0;
					$totprevsiswa = 0;
					$totinsiswa = 0;
					$totoutsiswa = 0;
					$totinactive = 0;
					$totjumlahsiswa = 0;
					$totpaiderbulanlalu = 0;
					$totpiutang_bulan_lalu = 0;
					$totpenyesuaianhutang = 0;
					$totpiutang_sekarang = 0;
					$totbn = 0;
					$totbb = 0;
					$totbf = 0;
					$totretur = 0;
					$totpiutang = 0;
					$totpaider = 0;
					$totharusbayar = 0;
					$tothutang = 0;
					$totpaideramount = 0;
					$totrealisasi = 0;
					
					// paider
					
					
					// =====================================================================================================================
					
					$i=1;
					foreach($grade as $res)
					{
	$prevsiswa = $recap->get_total_previous($res->dept_id,$res->grade_id,$month,$year);
	$insiswa =  $recap_trans->get_previous_trans('in', $res->dept_id, $res->grade_id, $month, $ps->year);
	$inactive = $st->where('dept_id', $res->dept_id)->where('active', 0)->where('grade_id', $res->grade_id)->count(); // jumlah siswa inactive
    $outsiswa = $recap_trans->get_previous_trans('out', $res->dept_id, $res->grade_id, $month, $ps->year);			
//    $jml_siswa = $st->where('dept_id', $res->dept_id)->where('active', 1)->where('grade_id', $res->grade_id)->count(); // jumlah siswa					
//    $jml_siswa = intval($prevsiswa+$insiswa-$inactive-$outsiswa);
    $jml_siswa = intval($prevsiswa+$insiswa-$outsiswa);
	$piutang_bulan_lalu = tunggakan($res->dept_id,$res->grade_id,$monthperiod-1, $year);
	$harus_bayar = tunggakan($res->dept_id,$res->grade_id,$monthperiod, $year);
	
	// retur
	$retur = retur($res->dept_id, null, $res->grade_id, $month, $year);
	
	// penyesuaian hutang
	$penyesuaian_hutang = penyesuaian_hutang($res->dept_id,$res->grade_id,$month, $year);
	
	// paider
	$bb = paid($res->dept_id,$res->grade_id,$monthperiod,$year,0,0);
	$bn = paid($res->dept_id,$res->grade_id,$monthperiod,$year,1,0);
	$bf = paid($res->dept_id,$res->grade_id,$monthperiod,$year,2,0);
    $paider = intval($penyesuaian_hutang + $bb + $bn + $bf - $retur);
	
	
	// paider bulan lalu
	$paiderbulanlalu = intval(paid($res->dept_id,$res->grade_id,$monthperiod-1,$year,0,0) +  
	                          paid($res->dept_id,$res->grade_id,$monthperiod-1,$year,1,0) +
	                          paid($res->dept_id,$res->grade_id,$monthperiod-1,$year,2,0));
	
	$piutang = intval($harus_bayar+$paider-$bf);
	if ($paider > $piutang){ $hutang = $paider - $piutang; }else { $hutang = 0; }
	$paider_amount = intval(($paider-$bf)*get_fee($res->dept_id,$res->grade_id));
	$realisasi = realisasi($res->dept_id,$res->grade_id,$month,$year);
	
					   
					   echo "
				   <tr> 
					   <td class=\"center\"> ".$i." </td>
					   <td class=\"center\"> ".$dept->get_name($res->dept_id)." </td>
					   <td class=\"center\"> ".$res->name." </td>
					   <td class=\"center\"> ".$prevsiswa." </td>
					   <td class=\"center\"> ".$insiswa." </td>
					   <td class=\"center\"> ".$outsiswa." </td>
					   <td class=\"center\"> ".$jml_siswa." </td>
                       <td class=\"center\"> ".$paiderbulanlalu." </td>
					   <td class=\"center\"> ".$piutang_bulan_lalu." </td> 
					    <td class=\"center\"> ".$penyesuaian_hutang." </td> 
                       <td class=\"center\"> ".$bn." </td>
					   <td class=\"center\"> ".$bb." </td>
					   <td class=\"center\"> ".$bf." </td>
					   <td class=\"center\"> ".$retur." </td>
					   <td class=\"center\"> ".$piutang." </td>
                       <td class=\"center\"> ".$paider." </td> 
					   <td class=\"center\"> ".$harus_bayar." </td> 
					   <td class=\"center\"> ".$hutang." </td> 
                       <td class=\"right\"> ".number_format(get_fee($res->dept_id,$res->grade_id))." </td>
                       <td class=\"right red\"> ".number_format($paider_amount)." </td> 
					   <td class=\"right blue\"> ".number_format($realisasi)." </td> 
                       <td class=\"center strongs\"> "
.anchor_popup(site_url("tuition_transaction/get_list/".$res->dept_id."/".$res->grade_id."/".$monthperiod."/".$year.'/null/null/computer'), '[ Details ]', $atts).
					   " </div> </td>
					   
				   </tr>
					   ";    
				   $i++;
					   
					   // total
					   $totprevsiswa = intval($totprevsiswa + $prevsiswa);
					   $totinsiswa = intval($totinsiswa + $insiswa);
					   $totinactive = intval($totinactive + $inactive);
					   $totoutsiswa = intval($totoutsiswa + $outsiswa);
					   $totjumlahsiswa = intval($totjumlahsiswa+$jml_siswa);
					   $totpiutang_bulan_lalu = intval($totpiutang_bulan_lalu + $piutang_bulan_lalu);
					   $totpaiderbulanlalu = intval($totpaiderbulanlalu + $paiderbulanlalu);
					   $totpenyesuaianhutang = intval($totpenyesuaianhutang + $penyesuaian_hutang);
					   $totbn = intval($totbn + $bn);
					   $totbf = intval($totbf + $bf);
					   $totbb = intval($totbb + $bb);
					   $totretur = intval($totretur + $retur);
					   $totpiutang = intval($totpiutang + $piutang);
					   $totpaider = intval($totpaider + $paider);
					   $totharusbayar = intval($totharusbayar+$harus_bayar);
					   $tothutang = intval($tothutang + $hutang);
					   $totpaideramount = intval($totpaideramount + $paider_amount);
					   $totrealisasi = intval($totrealisasi + $realisasi);
					   
					}
				
		   
		   ?>
           
           <!-- jenis penerimaan -->
           
          <!-- 
           
            <tr> 
               <td colspan="12" class="center strongs"></td>
               <td colspan="3" class="center strongs blue"> SMP I - Discount 100% </td> 	
               <td colspan="" class="center strongs blue"> 5 </td>
               <td colspan="" class="right strongs blue"> 165.000 </td> 
               <td colspan="" class="right strongs">  </td> 
               <td colspan="" class="right strongs red"> 825.000 </td> 
               <td colspan="" class="center strongs"></td>
		   </tr>-->
           
           <!-- jenis penerimaan -->
		   

		   <tr> 
			   <td colspan="3" class="center strongs"> JUMLAH </td> 
               <td class="center strongs"> <?php echo $totprevsiswa; ?> </td>
               <td class="center strongs"> <?php echo $totinsiswa; ?> </td>
               <td class="center strongs"> <?php echo $totoutsiswa; ?> </td>
               <td class="center strongs"> <?php echo $totjumlahsiswa; ?> </td>
               <td class="center strongs"> <?php echo $totpaiderbulanlalu; ?> </td>  
               <td class="center strongs"> <?php echo $totpiutang_bulan_lalu; ?> </td> 
               <td class="center strongs"> <?php echo $totpenyesuaianhutang; ?> </td>
               <td class="center strongs"> <?php echo $totbn; ?> </td>
               <td class="center strongs"> <?php echo $totbb; ?> </td>
               <td class="center strongs"> <?php echo $totbf; ?> </td>
               <td class="center strongs"> <?php echo $totretur; ?> </td>
               <td class="center strongs"> <?php echo $totpiutang; ?> </td>
               <td class="center strongs"> <?php echo $totpaider; ?> </td>
               <td class="center strongs"> <?php echo $totharusbayar; ?> </td>
               <td class="center strongs"> <?php echo $tothutang; ?> </td>
               <td class="center strongs"> </td>
               <td class="right strongs"> <?php echo number_format($totpaideramount); ?> </td>
               <td class="right strongs"> <?php echo number_format($totrealisasi); ?> </td>
               <td class="center strongs"></td>
		   </tr>
		   
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
