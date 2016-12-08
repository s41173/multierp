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
						{ name: "Order No", type: "string" },
						{ name: "Customer", type: "string" },
						{ name: "Notes", type: "string" },
						{ name: "Sub Total", type: "number" },
						{ name: "Discount", type: "number" },
						{ name: "Tax", type: "number" },
						{ name: "Costs", type: "number" },
						{ name: "Sales Total", type: "number" },
 					    { name: "Payment", type: "number" },
						{ name: "Balance", type: "number" },
						{ name: "Status", type: "string" }
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
  				  { text: 'Order No', dataField: 'Order No', width : 100 },
				  { text: 'Customer', dataField: 'Customer', width: 250, },
				  { text: 'Notes', dataField: 'Notes', width: 180, },
				  { text: 'Sub Total', datafield: 'Sub Total', width: 120, cellsalign: 'right', cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
  				  { text: 'Discount', datafield: 'Discount', width: 90, cellsalign: 'right', cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Tax', datafield: 'Tax', width: 90, cellsalign: 'right', cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Costs', datafield: 'Costs', width: 90, cellsalign: 'right', cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Sales Total', datafield: 'Sales Total', width: 120, cellsalign: 'right', cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Payment', datafield: 'Payment', width: 100, cellsalign: 'right', cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Balance', datafield: 'Balance', width: 120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
			      { text: 'Status', dataField: 'Status', width : 90 }
				  
                ]
            });
			
			$('#jqxgrid').jqxGrid({ pagesizeoptions: ['10', '20', '30', '40', '50', '100', '200', '300', '500', '1000', '2000']}); 
			
			$("#bexport").click(function() {
				
				var type = $("#crtype").val();	
				if (type == 0){ $("#jqxgrid").jqxGrid('exportdata', 'html', 'NonTaxSales-Summary'); }
				else if (type == 1){ $("#jqxgrid").jqxGrid('exportdata', 'xls', 'NonTaxSales-Summary'); }
				else if (type == 2){ $("#jqxgrid").jqxGrid('exportdata', 'pdf', 'NonTaxSales-Summary'); }
				else if (type == 3){ $("#jqxgrid").jqxGrid('exportdata', 'csv', 'NonTaxSales-Summary'); }
			});
			
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
			<tr> <td> Period </td> <td> : </td> <td> <?php echo tgleng($start); ?> to <?php echo tgleng($end); ?> </td> </tr>
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo $rundate; ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Non Tax Sales Order Report </h4>
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
 	       <th> No </th> <th> Date </th> <th> Order No </th> <th> Customer </th> <th> Notes </th> <th> Sub Total </th> <th> Discount </th> <th> Tax </th> <th> Costs </th> <th> Sales Total </th> 
		   <th> Payment </th> <th> Balance </th> <th> Status </th> 
		   </tr>
           </thead>

		  <tbody> 
		  <?php 
		  
		  	  function status($val)
			  { if ($val == 0){ $val = 'credit'; } else { $val = 'settled'; } return $val; }	
		  
		  
		      $i=1; 
			  if ($saless)
			  {
				foreach ($saless as $sales)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".tgleng($sales->dates)."</td> 
					   <td class=\"strongs\"> NSO-00".$sales->no."</td> 
					   <td class=\"strongs\">".$sales->prefix.' '.$sales->name."</td> 
  					   <td class=\"strongs\">".$sales->notes."</td> 
					   <td class=\"strongs\" align=\"right\">".intval($sales->total - $sales->tax + $sales->discount)."</td>
					   <td class=\"strongs\" align=\"right\">".intval($sales->discount)."</td> 
					   <td class=\"strongs\" align=\"right\">".intval($sales->tax)."</td>
					   <td class=\"strongs\" align=\"right\">".intval($sales->costs)."</td> 
					   <td class=\"strongs\" align=\"right\">".intval($sales->total + $sales->costs)."</td> 
					   <td class=\"strongs\" align=\"right\">".intval($sales->p1)."</td>
					   <td class=\"strongs\" align=\"right\">".intval($sales->p2)."</td> 
					   <td class=\"strongs\" align=\"center\">".status($sales->status)."</td> 
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
