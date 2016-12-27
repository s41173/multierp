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
                        { name: "No", type: "string" },
						{ name: "Date", type: "string" },
						{ name: "Code", type: "string" },
						{ name: "Type", type: "string" },
						{ name: "Tax Type", type: "string" },
						{ name: "Voucher", type: "string" },
						{ name: "Vendor", type: "string" },
						{ name: "Cur", type: "string" },
						{ name: "Acc", type: "string" },
						{ name: "Amount", type: "number" },
						{ name: "Discount", type: "number" },
						{ name: "Late-Charge", type: "number" },
						{ name: "Balance", type: "number" },
						{ name: "Over", type: "number" },
						{ name: "Balance-Over", type: "number" },
						{ name: "Credit-Status", type: "string" },
						{ name: "Log", type: "string" }
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
                  { text: 'No', dataField: 'No', width: 50 },
				  { text: 'Date', dataField: 'Date', width : 100 },
  				  { text: 'Code', dataField: 'Code', width : 120 },
				  { text: 'Type', dataField: 'Type', width : 90 },
				  { text: 'Tax Type', dataField: 'Tax Type', width : 80 },
				  { text: 'Voucher', dataField: 'Voucher', width : 100 },
				  { text: 'Vendor', dataField: 'Vendor', width : 200 },
  				  { text: 'Cur', dataField: 'Cur', width : 70 },
  				  { text: 'Acc', datafield: 'Acc', width: 100, cellsalign: 'center' },
				  { text: 'Amount', datafield: 'Amount', width: 150, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Discount', datafield: 'Discount', width: 150, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Late-Charge', datafield: 'Late-Charge', width: 150, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Balance', datafield: 'Balance', width: 150, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Over', datafield: 'Over', width: 150, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Balance-Over', datafield: 'Balance-Over', width: 150, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Credit-Status', datafield: 'Credit-Status', width: 120, cellsalign: 'center' },
  				  { text: 'Log', datafield: 'Log', width: 90, cellsalign: 'center'}
				  
                ]
            });
			
			$('#jqxgrid').jqxGrid({ pagesizeoptions: ['100', '200', '300', '400', '500', '1000', '2000', '5000']}); 
			
			$("#bexport").click(function() {
				
				var type = $("#crtype").val();	
				if (type == 0){ $("#jqxgrid").jqxGrid('exportdata', 'html', 'AP-Payment-Summary'); }
				else if (type == 1){ $("#jqxgrid").jqxGrid('exportdata', 'xls', 'AP-Payment-Summary'); }
				else if (type == 2){ $("#jqxgrid").jqxGrid('exportdata', 'pdf', 'AP-Payment-Summary'); }
				else if (type == 3){ $("#jqxgrid").jqxGrid('exportdata', 'csv', 'AP-Payment-Summary'); }
			});
			
			$('#jqxgrid').on('celldoubleclick', function (event) {
     	  		var col = args.datafield;
				var value = args.value;
				var res;
			
				if (col == 'Code')
				{ 			
				   res = value.split("CD-00");
				   openwindow(res[1]);
				}
 			});
			
			function openwindow(val)
			{
				var site = "<?php echo site_url('ap_payment/invoice/');?>";
				window.open(site+"/"+val, "", "width=800, height=600"); 
				//alert(site+"/"+val);
			}
			
			$("#table").hide();
			
		// end jquery	
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
	
	<div style="border:0px solid red; float:right;">
		<table border="0">
      		<tr> <td> Currency </td> <td> : </td> <td> <?php echo $currency; ?> </td> </tr>
			<tr> <td> Account </td> <td> : </td> <td> <?php echo $acc; ?> </td> </tr>
		</table>
	</div>


	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> AP - Payment Report </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:1px dotted #000000; ">
	
    <div id='jqxWidget'>
        <div style='margin-top: 10px;' id="jqxgrid"> </div>
        
        <table style="float:right; margin:5px;">
        <tr>
        <td> <input type="button" id="bexport" value="Export"> - </td>
        <td> 
        <select id="crtype"> <option value="0"> HTML </option> <option value="1"> XLS </option>  <option value="2"> PDF </option> 
        <option value="3"> CSV </option> 
        </select>
        </td>
        </tr>
        </table>
        
    </div>
    
		<table id="table" border="0" width="100%">
		   <thead>
           <tr>
 	       <th> No </th> <th> Date </th> <th> Code </th> <th> Type </th> <th> Tax Type </th> <th> Voucher </th> <th> Vendor </th> <th> Cur </th> <th> Acc </th> <th> Amount </th> <th> Discount </th> <th> Late-Charge </th> <th> Balance </th> <th> Over </th> <th> Balance-Over </th> <th> Credit-Status </th> <th> Log </th> 
		   </tr>
           </thead>
		  
          <tbody> 
		  <?php 
		  
		      function acc($val=null)
			  {
				switch ($val)
				{
					case 'pettycash': $val = 'Petty cash'; break;
					case 'cash': $val = 'Cash'; break;
					case 'bank': $val = 'Bank'; break;
				}
				return $val;
			  }
			  
			  function status($val) { if ($val==1){ $val = 'settled'; } else{ $val = 'debt'; } return $val; }
			  function tax($val) { if ($val==1){ $val = 'tax'; } else{ $val = 'non tax'; } return $val; }
  			  function type($val) { if ($val=='PO'){ $val = 'PURCHASE'; } else{ $val = 'PRINTING'; } return $val; }
			  function over($val) { if ($val==1){ $val = 'Y'; } else{ $val = '-'; } return $val; }
			  
			  		  
		      $i=1; 
			  if ($reports)
			  {
				foreach ($reports as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".tglin($res->dates)."</td> 
					   <td class=\"strongs\"> CD-00".$res->no."</td>
					   <td class=\"strongs\">".type($res->type)."</td> 
					   <td class=\"strongs\">".tax($res->tax)."</td> 
					   <td class=\"strongs\">".$res->voucher_no."</td> 
   					   <td class=\"strongs\">".$res->prefix.' '.$res->name."</td> 
					   <td class=\"strongs\">".$res->currency."</td> 
					   <td class=\"strongs\">".acc($res->acc)."</td> 
					   <td class=\"strongs\" align=\"right\">".intval($res->amount + $res->discount - $res->late)."</td> 
					   <td class=\"strongs\" align=\"right\">".$res->discount."</td> 
					   <td class=\"strongs\" align=\"right\">".$res->late."</td> 
					   <td class=\"strongs\" align=\"right\">".$res->amount."</td> 
					   <td class=\"strongs\" align=\"right\">".$res->over."</td>
					   <td class=\"strongs\" align=\"right\">".intval($res->amount+$res->over)."</td> 
					   <td class=\"strongs\" align=\"right\">".over($res->credit_over)."</td>
					   <td class=\"strongs\" align=\"center\">".$res->log."</td> 
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
