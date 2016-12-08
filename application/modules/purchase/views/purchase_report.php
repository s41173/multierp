<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title> <?php echo isset($title) ? $title : ''; ?>  </title>
<style media="all">
	table{ font-family:Tahoma, Geneva, sans-serif; font-size:11px;}
	h4{ font-family:"Tahoma", Times, serif; font-size:14px; font-weight:600;}
	.clear{clear:both;}
	table th{ background-color:#EFEFEF; padding:4px 0px 4px 0px; border-top:1px solid #000000; border-bottom:1px solid #000000;}
    p{ font-family:Tahoma, Geneva, sans-serif; font-size:12px; margin:0; padding:0;}
	legend{ font-family:Tahoma, Geneva, sans-serif; font-size:13px; margin:0; padding:0; font-weight:600;}
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
						{ name: "Order No", type: "string" },
						{ name: "Vendor", type: "string" },
						{ name: "Acc", type: "string" },
  					    { name: "Sub Total", type: "number" },
						{ name: "Tax", type: "number" },
						{ name: "Costs", type: "number" },
						{ name: "Purchase Total", type: "number" },
						{ name: "Payment", type: "number" },
						{ name: "Refund", type: "string" },
						{ name: "Refund Amount", type: "number" },
						{ name: "Balance", type: "number" },
						{ name: "Status", type: "string" },
						{ name: "Stock-IN", type: "string" }
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
				  { text: 'Date', dataField: 'Date', width : 120 },
  				  { text: 'Order No', dataField: 'Order No', width : 100 },
				  { text: 'Vendor', dataField: 'Vendor', width : 200 },
  				  { text: 'Acc', datafield: 'Acc', width: 70, cellsalign: 'center' },
				  { text: 'Sub Total', datafield: 'Sub Total', width: 130, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Tax', datafield: 'Tax', width: 130, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Costs', datafield: 'Costs', width: 130, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Purchase Total', datafield: 'Purchase Total', width: 130, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
   				  { text: 'Refund', datafield: 'Refund', width: 95, cellsalign: 'center' },
				  { text: 'Refund Amount', datafield: 'Refund Amount', width: 130, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Payment', datafield: 'Payment', width: 130, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Balance', datafield: 'Balance', width: 150, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
  				  { text: 'Status', datafield: 'Status', width: 90, cellsalign: 'center'},
				  { text: 'Stock-IN', datafield: 'Stock-IN', width: 160, cellsalign: 'center'}
				  
                ]
            });
			
			$('#jqxgrid').jqxGrid({ pagesizeoptions: ['10', '20', '30', '40', '50', '100', '200', '300']}); 
			
			$("#bexport").click(function() {
				
				var type = $("#crtype").val();	
				if (type == 0){ $("#jqxgrid").jqxGrid('exportdata', 'html', 'Purchase-Summary'); }
				else if (type == 1){ $("#jqxgrid").jqxGrid('exportdata', 'xls', 'Purchase-Summary'); }
				else if (type == 2){ $("#jqxgrid").jqxGrid('exportdata', 'pdf', 'Purchase-Summary'); }
				else if (type == 3){ $("#jqxgrid").jqxGrid('exportdata', 'csv', 'Purchase-Summary'); }
			});
			
			$('#jqxgrid').on('celldoubleclick', function (event) {
     	  		var col = args.datafield;
				var value = args.value;
				var res;
			
				if (col == 'Order No')
				{ 			
				   res = value.split("PO-00");
				   openwindow(res[1]);
				}
 			});
			
			function openwindow(val)
			{
				var site = "<?php echo site_url('purchase/print_invoice/');?>";
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
    		<tr> <td> Currency </td> <td> : </td> <td> <?php echo $currency; ?> </td> </tr>
			<tr> <td> Period </td> <td> : </td> <td> <?php echo tglin($start); ?> :: <?php echo tglin($end); ?> </td> </tr>
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo $rundate; ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Purchase Order Report </h4>
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
 	       <th> No </th> <th> Date </th> <th> Order No </th> <th> Vendor </th> <th> Acc </th> <th> Sub Total </th> <th> Tax </th> 
           <th> Costs </th> <th> Purchase Total </th> <th> Payment </th> <th> Refund </th> <th> Refund Amount </th>  <th> Balance </th> <th> Status </th> 
           <th> Stock-IN </th>
		   </tr>
           </thead>
		   
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
		  
          <tbody> 
		  <?php 
		  
		  	  function status($val)
			  { if ($val == 0){ $val = 'debt'; } else { $val = 'settled'; } return $val; }	
			  
			  function ap($val){ if ($val > 0){ return 'CD-00'.$val; }else { return "-"; } }
			  
			  function stockin($no,$stts)
			  {
				  $sin = new Stock_in_lib();
				  if ($stts == 0){ return '-'; }
				  else { $res = $sin->get_stockin_based_purchase($no); return 'BTB-00'.$res->no.' : '.tglin($res->dates); }
			  }
		  
		  
		      $i=1; 
			  if ($purchases)
			  {
				foreach ($purchases as $purchase)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".tglin($purchase->dates)."</td> 
					   <td class=\"strongs\"> PO-00".$purchase->no."</td> 
					   <td class=\"strongs\">".$purchase->prefix.' '.$purchase->name."</td> 
					   <td class=\"strongs\">".ucfirst($purchase->acc)."</td> 
					   <td class=\"strongs\" align=\"right\">".intval($purchase->total - $purchase->tax)."</td> 
					   <td class=\"strongs\" align=\"right\">".$purchase->tax."</td>
					   <td class=\"strongs\" align=\"right\">".$purchase->costs."</td> 
					   <td class=\"strongs\" align=\"right\">".intval($purchase->total + $purchase->costs)."</td> 
					   <td class=\"strongs\" align=\"right\">".$purchase->p1."</td>
					   <td class=\"strongs\" align=\"right\">".ap($purchase->ap_over)."</td>
					   <td class=\"strongs\" align=\"right\">".$purchase->over_amount."</td>
					   <td class=\"strongs\" align=\"right\">".$purchase->p2."</td> 
					   <td class=\"strongs\" align=\"center\">".status($purchase->status)."</td> 
					   <td class=\"strongs\" align=\"center\">".stockin($purchase->no,$purchase->stock_in_stts)."</td> 
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
