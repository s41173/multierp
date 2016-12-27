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
	.desc{ font-weight:normal; font-size:12px; border-bottom:1px dotted #000000; border-right:1px solid #000; text-align:left; }
	.poder{ border-bottom:0px solid #000000; color:#0000FF;}
</style>
</head>

<body>

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0">
    		<tr> <td> Department </td> <td> : </td> <td> <?php echo isset($department) ? $department : ''; ?> </td> </tr>
			<tr> <td> Bulan </td> <td> : </td> <td> <?php echo isset($monthname) ? $monthname : ''; ?> </td> </tr>
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo tglin(date('d-m-Y')); ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo isset($log) ? $log : ''; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Income Receivable </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:1px dotted #000000; ">
	
		<table border="0" width="100%">
		   <tr>
 	       <th> No </th> <th> Kelas </th> <th> Jml Mrd <br> Bulan Ini </th> 
           <th> Penunggak <br> Bulan Dpn </th> <th> Biaya SPP <br> @ <?php echo number_format(get_fee($dept)); ?> </th> 
           <th> Biaya Praktikum <br> @ <?php echo number_format(get_practice($dept)); ?> </th>
           <th> Biaya OSIS <br> @ <?php echo number_format(get_osis($dept)); ?> </th>
           <th> Biaya Komputer <br> @ <?php echo number_format(get_computer($dept)); ?> </th> <th> Jumlah Nominal </th> <th> Keterangan </th>
		   </tr>
           
           <?php
           
		   		function hitung_tunggakan($dept,$faculty,$grade,$ayear)
				{
					$payment = new Payment_status_lib();
					$model = new Payment_status_model();
					$ps = new Period();
					$ps->get();
					
					$total_piutang = 0;
                    for ($x=1; $x<$payment->months_periode($ps->month); $x++)
                    { 
					  $total_piutang = $total_piutang + $model->unpaid_sum($dept,$faculty,$grade,$ayear,'p'.$x);

					} 
                    return $total_piutang;
			    }
				
				function calculate_fee($dept,$res)
				{
					$fee = new Fee();
					$fee = $fee->where('dept_id',$dept)->get();
					$tot = intval($fee->computer * $res);
					return $tot;
				}
				
				function get_fee($dept){ $fee = new Fee(); $fee = $fee->where('dept_id',$dept)->get(); return $fee->school; }
				function get_practice($dept){ $fee = new Fee(); $fee = $fee->where('dept_id',$dept)->get(); return $fee->practice; }
				function get_osis($dept){ $fee = new Fee(); $fee = $fee->where('dept_id',$dept)->get(); return $fee->osis; }
				function get_computer($dept){ $fee = new Fee(); $fee = $fee->where('dept_id',$dept)->get(); return $fee->computer; }
				
				function get_fee_aid($dept)
				{ $fee = new Fee(); $fee = $fee->where('dept_id',$dept)->get(); return $fee->aid; }
		   
		   		function get_fee_nonaid($dept)
				{ $fee = new Fee(); $fee = $fee->where('dept_id',$dept)->get(); return $fee->school; }
		   

					$grade = new Grade_lib();
					$grade = $grade->get($dept);
					
					// ============================
					$ps = new Period();
			        $st = new Student();
			        $payment = new Payment_status_lib();
			        $year = new Financial_lib();
			        $year = $year->get();
			        $tt = new Tuition_lib();
					$model = new Payment_status_model();
					
					$ps->get();
			        $now = 'p'.$payment->months_periode($ps->month);
					
					$totsiswa = 0;
					$totpiutang = 0;
					$totjml_pembayar_piutang_100 = 0;
					$totjml_diskon_100 = 0;
					$totjml_pembayar_didepan = 0;
					$totjml_pembayar_depan_100 = 0;
					$totjml_siswa_byr_piutang = 0;
					$totpiutang_bulan_ini = 0;
					$totjml_siswa_byr_bj = 0;
					$totjml_siswa_byr_depan = 0;
					$totjml_pembayar_piutang_50 = 0;
					$totjml_diskon_50 = 0;
					$totjml_pembayar_depan_50 = 0;
					$tothasil1 = 0;
					$tothasil2 = 0;
					$totpenunggak = 0;
					$totspp = 0;
					$totosis = 0;
					$totpractice = 0;
					$totcomputer = 0;
					$totgross = 0;
					$totbrutto = 0;
					// ============================
					
					$i=1;
					foreach($grade as $res)
					{
						
					   $jml_siswa = $st->where('dept_id', $dept)->where('active', 1)->where('grade_id', $res->grade_id)->count(); // jumlah siswa	
					   $total_piutang = hitung_tunggakan($dept,$faculty,$res->grade_id,$year);
					   $jml_pembayar_piutang_100 = $tt->total_paid_aid($dept, $res->grade_id, $ps->month, $ps->year, $year, 0, 0);
					   $jml_diskon_100 = $tt->total_paid_aid($dept, $res->grade_id, $ps->month, $ps->year, $year, 1, 0);
			 $jml_pembayar_didepan = $model->paid_onfront_sum($dept,$faculty,$res->grade_id,$year,$now,$ps->month,$ps->year);		   
		     $jml_pembayar_depan_100 = $tt->total_paid_aid($dept, $res->grade_id, $ps->month, $ps->year, $year, 2, 0);
			 $jml_siswa_byr_piutang = $tt->total_paid_ar($dept,$res->grade_id,$ps->month,$ps->year,$year); // jumlah siswa bayar piutang di bulan ini
			 $piutang_bulan_ini = $model->unpaid_sum($dept,$faculty,$res->grade_id,$year,$now); // piutang bulan ini
			 $jml_siswa_byr_bj = $tt->total_paid_ar($dept,$res->grade_id,$ps->month,$ps->year,$year,1); // jumlah siswa bayar BJ
			 $jml_siswa_byr_depan = $tt->total_paid_ar($dept,$res->grade_id,$ps->month,$ps->year,$year,2); // jumlah siswa bayar Depan
			 $jml_pembayar_piutang_50 = $tt->total_paid_aid($dept, $res->grade_id, $ps->month, $ps->year, $year, 0, 1);
			 $jml_diskon_50 = $tt->total_paid_aid($dept, $res->grade_id, $ps->month, $ps->year, $year, 1, 1);
			 $jml_pembayar_depan_50 = $tt->total_paid_aid($dept, $res->grade_id, $ps->month, $ps->year, $year, 2, 1);
					   
					   $hasil1 = intval($jml_siswa + $total_piutang);
					   $potongan100 = intval($jml_pembayar_piutang_100 + $jml_diskon_100 + $jml_pembayar_depan_100 + $jml_pembayar_didepan);
					   $hasil1 = $hasil1 - $potongan100;
					
					   $hasil_50 = intval($jml_pembayar_piutang_50 + $jml_diskon_50 + $jml_pembayar_depan_50);
					   $hasil_bruto = intval($jml_siswa_byr_bj + $jml_siswa_byr_piutang);
					   $hasil2 = intval($hasil_50 + $hasil_bruto);
										
					   $totgross = get_fee($dept) * intval($hasil1-$hasil2) + get_practice($dept) * intval($hasil1-$hasil2) + 
						            get_osis($dept) * intval($hasil1-$hasil2)+ get_computer($dept) * intval($hasil1-$hasil2);				
					   
					   echo "
				   <tr> 
					   <td class=\"center\"> $i </td> 
					   <td class=\"center\"> ".$res->name." </td>
					   <td class=\"center\"> ".$jml_siswa." </td>
                       <td class=\"center\"> ".intval($hasil1-$hasil2)." </td> 
                       <td class=\"right\"> ".number_format(get_fee($dept) * intval($hasil1-$hasil2))." </td>
					   <td class=\"right\"> ".number_format(get_practice($dept) * intval($hasil1-$hasil2))." </td>
					   <td class=\"right\"> ".number_format(get_osis($dept) * intval($hasil1-$hasil2))." </td>
					   <td class=\"right\"> ".number_format(get_computer($dept) * intval($hasil1-$hasil2))." </td>
                       <td class=\"right\"> ".number_format($totgross)."  </td> 
					   <td class=\"center\"> </td> 
				   </tr>
				   
				   
					   "; $i++;
					   
					   // total
					   $totsiswa = $totsiswa + $jml_siswa;
					   $totpiutang = $totpiutang + intval($total_piutang+$jml_siswa_byr_piutang);
					   $totjml_pembayar_piutang_100 = $totjml_pembayar_piutang_100 + $jml_pembayar_piutang_100;
					   $totjml_diskon_100 = $totjml_diskon_100 + $jml_diskon_100;
					   $totjml_pembayar_depan_100 = $totjml_pembayar_depan_100 + $jml_pembayar_depan_100;
					   $totjml_pembayar_didepan = $totjml_pembayar_didepan + $jml_pembayar_didepan;
					   $tothasil1 = $tothasil1 + $hasil1;
					   $totjml_siswa_byr_bj = $totjml_siswa_byr_bj + $jml_siswa_byr_bj;
					   $totjml_siswa_byr_piutang = $totjml_siswa_byr_piutang + $jml_siswa_byr_piutang;
					   $totjml_siswa_byr_depan = $totjml_siswa_byr_depan + $jml_siswa_byr_depan;
					   $totjml_pembayar_piutang_50 = $totjml_pembayar_piutang_50 + $jml_pembayar_piutang_50;
					   $totjml_diskon_50 = $totjml_diskon_50 + $jml_diskon_50;
					   $totjml_pembayar_depan_50 = $totjml_pembayar_depan_50 + $jml_pembayar_depan_50;
					   $tothasil2 = $tothasil2 + $hasil2;
					   $totpenunggak = $totpenunggak + intval($hasil1-$hasil2);
					   $totspp = $totspp + get_fee($dept) * intval($hasil1-$hasil2);
					   $totpractice = $totpractice + get_practice($dept) * intval($hasil1-$hasil2);
					   $totosis = $totosis + get_osis($dept) * intval($hasil1-$hasil2);
					   $totcomputer = $totcomputer + get_computer($dept) * intval($hasil1-$hasil2);
					   $totbrutto = $totbrutto + $totgross;
					   
					}
				
		   
		   ?>
		   

		   <tr> 
			   <td colspan="2" class="center strongs"> JUMLAH </td> 
               <td class="center strongs"> <?php echo $totsiswa; ?> </td>
               <td class="center strongs"> <?php echo $totpenunggak; ?> </td>
               <td class="right strongs"> <?php echo number_format($totspp); ?> </td>
               <td class="right strongs"> <?php echo number_format($totpractice); ?> </td>
               <td class="right strongs"> <?php echo number_format($totosis); ?> </td>
               <td class="right strongs"> <?php echo number_format($totcomputer); ?> </td>
               <td class="right strongs"> <?php echo number_format($totbrutto); ?> </td>
               <td class="center">  </td>
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
