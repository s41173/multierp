<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title> School Fee Payment Status - <?php echo isset($name) ? $name : ''; ?></title>

<style type="text/css" media="all">

	body{ font-size:0.75em; font-family:Arial, Helvetica, sans-serif; margin:0; padding:0;}
	#container{ width:21cm; height:11.6cm; border:0pt solid #000;}
	.clear{ clear:both;}
	#tablebox{ height:6.45cm; width:20cm; border:0pt solid red; float:left; margin:0.1cm 0 0 0.4cm;}
		
	#logobox{ width:5.5cm; height:1cm; border:0pt solid blue; margin:0.8cm 0 0 0.5cm; float:left;}
	#venbox{ width:7.5cm; height:2.0cm; border:0pt solid green; margin:0.0cm 0cm 0.8cm 0.5cm; float:left;}
	#title{ text-align:center; font-size:17pt;}
	h4{ font-size:14pt; margin:0;}
</style>

<link rel="stylesheet" href="<?php echo base_url().'js/jxgrid/' ?>css/jqx.base.css" type="text/css" />
    
	<script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxcore.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxdata.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxbuttons.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxcheckbox.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxscrollbar.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxlistbox.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxdropdownlist.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxmenu.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.sort.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.filter.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.columnsresize.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.columnsreorder.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.selection.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.pager.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.aggregates.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxdata.export.js"></script>
	<script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.export.js"></script>
    
    <script type="text/javascript">
	
        $(document).ready(function () {
          
			var rows = $("#table tbody tr");
                // select columns.
                var columns = $("#table thead th");
                var data = [];
                for (var i = 0; i < rows.length; i++) {
                    var row = rows[i];
                    var datarow = {};
                    for (var j = 0; j < columns.length; j++) {
                        // get column's title.
                        var columnName = $.trim($(columns[j]).text());
                        // select cell.
                        var cell = $(row).find('td:eq(' + j + ')');
                        datarow[columnName] = $.trim(cell.text());
                    }
                    data[data.length] = datarow;
                }
                var source = {
                    localdata: data,
                    datatype: "array",
                    datafields:
                    [
                        { name: "Month", type: "string" },
						{ name: "Payment Date", type: "string" },
						{ name: "Fee Type", type: "string" },
						{ name: "Amount", type: "number" },
						{ name: "User", type: "string" }
                    ]
                };
			
            var dataAdapter = new $.jqx.dataAdapter(source);
            $("#jqxgrid").jqxGrid(
            {
                width: '100%',
				source: dataAdapter,
				sortable: true,
				filterable: true,
				pageable: true,
				altrows: true,
				enabletooltips: true,
				filtermode: 'excel',
				autoheight: true,
				columnsresize: true,
				columnsreorder: true,
				showstatusbar: true,
				statusbarheight: 30,
				showaggregates: true,
				autoshowfiltericon: false,
                columns: [
                  { text: 'Month', dataField: 'Month', width: 120 },
				  { text: 'Payment Date', dataField: 'Payment Date', width : 130, cellsalign: 'center' },
  				  { text: 'Fee Type', dataField: 'Fee Type', width : 200 },
 				  { text: 'Amount', datafield: 'Amount', width: 150, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'User', dataField: 'User', cellsalign: 'center' }
				  
                ]
            });
			
			$("#table").hide();
			
		// end jquery	
        });
    </script>


</head>

<body bgcolor="#FFFFFF"; onload="">

<div id="container">
	
    <center>
	   <div style="border:0px solid green; width:500px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> </h4>
           <p style="margin:5px; padding:0;"> <?php echo $address; ?> <br> Telp. <?php echo $phone1.' - '.$phone2; ?> <br>
               Website : <?php echo $website; ?> &nbsp; &nbsp; Email : <?php echo $email; ?> </p>
	   </div>
	</center> <hr>
	
    <p style="padding:0; font-weight:bold; font-size:1.3em; text-align:center;"> STATUS PEMBAYARAN SPP </p>
    
    <div id="venbox">
	<table width="100%" style="font-size:1em; margin:0; text-align:left; font-weight:bold;">
	  <tr> <td> Name </td> <td>:</td> <td> <?php echo isset($name) ? $name : ''; ?> </td> </tr>
	  <tr> <td> NIS </td> <td>:</td> <td> <?php echo isset($nis) ? $nis : ''; ?> </td> </tr>
	  <tr> <td> Department </td> <td>:</td> <td> <?php echo isset($dept) ? $dept : ''; ?> </td> </tr>
      <tr> <td> Grade / Faculty </td> <td>:</td> <td> <?php echo isset($grade) ? $grade : ''.' / '.isset($faculty) ? $faculty : ''; ?> </td> </tr>
      <tr> <td> School Year </td> <td>:</td> <td> <?php echo isset($year) ? $year : ''; ?> </td> </tr>
	</table>
	</div>
	
	<div id="tablebox">
    
    <?php
	
	function cek($date,$sid,$month,$financial,$type)
	{
		if ($date != '-')
		{ 
		   $cost = new Regcost_lib();
		   $tuition = new Tuition_lib();
		   $user = new Admin_lib();	
		   
		   $val = $tuition->get_by_student($sid,$date,$month,$financial);
		   if ($val)
		   {
			 if ($type == 'fee'){ return $cost->get_name($val->fee_type); }
		     elseif ($type == 'amount') { return intval($val->amount); }
			 elseif ($type == 'user') { return $user->get_username($val->user); }
		   }
		   else { return '-'; }
		}
		else { return '-'; }
	}
	
	function tgl($val)
	{if ($val != '-'){ return tglin($val); }else { return '-'; }}
	
	    
	?>
    
    <div id='jqxWidget'>
    <div style='margin-top: 10px;' id="jqxgrid"> </div> 
    </div>
	
		<table id="table">
        <thead>
	    <tr>  <th> Month </th> <th> Payment Date </th> <th> Fee Type </th> <th> Amount </th> <th> User </th> </tr>	
        </thead>

<tbody>
<tr>  <td> July </td> <td> <?php echo tgl($p1); ?> </td> <td> <?php echo cek($p1,$sid,'p1',$year,'fee'); ?> </td> <td class="right"> <?php echo cek($p1,$sid,'p1',$year,'amount'); ?> </td> <td> <?php echo cek($p1,$sid,'p1',$year,'user'); ?> </td> </tr>
<tr>  <td> August </td> <td> <?php echo tgl($p2); ?> </td> <td> <?php echo cek($p2,$sid,'p2',$year,'fee'); ?> </td> <td class="right"> <?php echo cek($p2,$sid,'p2',$year,'amount'); ?> </td> <td> <?php echo cek($p2,$sid,'p2',$year,'user'); ?> </td> </tr>
<tr>  <td> September </td> <td> <?php echo tgl($p3); ?> </td> <td> <?php echo cek($p3,$sid,'p3',$year,'fee'); ?> </td> <td class="right"> <?php echo cek($p3,$sid,'p3',$year,'amount'); ?> </td> <td> <?php echo cek($p3,$sid,'p3',$year,'user'); ?> </td> </tr>
<tr>  <td> October </td> <td> <?php echo tgl($p4); ?> </td> <td> <?php echo cek($p4,$sid,'p4',$year,'fee'); ?> </td> <td class="right"> <?php echo cek($p4,$sid,'p4',$year,'amount'); ?> </td> <td> <?php echo cek($p4,$sid,'p4',$year,'user'); ?> </td> </tr>
<tr>  <td> November </td> <td> <?php echo tgl($p5); ?> </td> <td> <?php echo cek($p5,$sid,'p5',$year,'fee'); ?> </td> <td class="right"> <?php echo cek($p5,$sid,'p5',$year,'amount'); ?> </td> <td> <?php echo cek($p5,$sid,'p5',$year,'user'); ?> </td> </tr>
<tr>  <td> December </td> <td> <?php echo tgl($p6); ?> </td> <td> <?php echo cek($p6,$sid,'p6',$year,'fee'); ?> </td> <td class="right"> <?php echo cek($p6,$sid,'p6',$year,'amount'); ?> </td> <td> <?php echo cek($p6,$sid,'p6',$year,'user'); ?> </td> </tr>
<tr>  <td> January </td> <td> <?php echo tgl($p7); ?> </td> <td> <?php echo cek($p7,$sid,'p7',$year,'fee'); ?> </td> <td class="right"> <?php echo cek($p7,$sid,'p7',$year,'amount'); ?> </td> <td> <?php echo cek($p7,$sid,'p7',$year,'user'); ?> </td> </tr>
<tr>  <td> February </td> <td> <?php echo tgl($p8); ?> </td> <td> <?php echo cek($p8,$sid,'p8',$year,'fee'); ?> </td> <td class="right"> <?php echo cek($p8,$sid,'p8',$year,'amount'); ?> </td> <td> <?php echo cek($p8,$sid,'p8',$year,'user'); ?> </td> </tr>
<tr>  <td> March </td> <td> <?php echo tgl($p9); ?> </td> <td> <?php echo cek($p9,$sid,'p9',$year,'fee'); ?> </td> <td class="right"> <?php echo cek($p9,$sid,'p9',$year,'amount'); ?> </td> <td> <?php echo cek($p9,$sid,'p9',$year,'user'); ?> </td> </tr>
<tr>  <td> April </td> <td> <?php echo tgl($p10); ?> </td> <td> <?php echo cek($p10,$sid,'p10',$year,'fee'); ?> </td> <td class="right"> <?php echo cek($p10,$sid,'p10',$year,'amount'); ?> </td> <td> <?php echo cek($p10,$sid,'p10',$year,'user'); ?> </td> </tr>
<tr>  <td> May </td> <td> <?php echo tgl($p11); ?> </td> <td> <?php echo cek($p11,$sid,'p11',$year,'fee'); ?> </td> <td class="right"> <?php echo cek($p11,$sid,'p11',$year,'amount'); ?> </td> <td> <?php echo cek($p11,$sid,'p11',$year,'user'); ?> </td> </tr>
<tr>  <td> June </td> <td> <?php echo tgl($p12); ?> </td> <td> <?php echo cek($p12,$sid,'p12',$year,'fee'); ?> </td> <td class="right"> <?php echo cek($p12,$sid,'p12',$year,'amount'); ?> </td> <td> <?php echo cek($p12,$sid,'p12',$year,'user'); ?> </td> </tr>
</tbody>

		</table>
	</div>  <div class="clear"></div>
	
	
</div>

</body>
</html>
