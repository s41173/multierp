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
	table th{ background-color:#EFEFEF; padding:4px 0px 4px 0px; border-top:1px solid #000000; border-bottom:0px solid #000000;}
    p{ font-family:"Arial", Times, serif; font-size:12px; margin:0; padding:0;}
	legend{font-family:"Arial", Times, serif; font-size:13px; margin:0; padding:0; font-weight:600;}
	.tablesum{ font-size:13px;}
	.strongs{ font-weight:normal; font-size:12px; border-top:0px dotted #000000; }
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
						{ name: "Type", type: "string" },
                        { name: "C-Type", type: "string" },
						{ name: "Deal", type: "string" },
						{ name: "Date", type: "string" },
						{ name: "Due", type: "string" },
						{ name: "Order No", type: "string" },
						{ name: "Cur", type: "string" },
						{ name: "Customer", type: "string" },
						{ name: "Notes", type: "string" },
						{ name: "Tax", type: "number" },
						{ name: "Amount", type: "number" },
						{ name: "Balance", type: "number" },
						{ name: "Status", type: "string" },
						{ name: "Staff", type: "string" },
						{ name: "Approved", type: "string" },
						{ name: "Void-Date", type: "string" },
						{ name: "Void-Desc", type: "string" }
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
				  { text: 'Type', dataField: 'Type', width: 60 },
                  { text: 'C-Type', dataField: 'C-Type', width: 100 },
				  { text: 'Deal', dataField: 'Deal', width : 100 },
				  { text: 'Date', dataField: 'Date', width : 100 },
				  { text: 'Due', dataField: 'Due', width : 100 },
  				  { text: 'Order No', dataField: 'Order No', width : 100 },
				  { text: 'Cur', dataField: 'Cur', width : 60 },
				  { text: 'Customer', dataField: 'Customer', width : 200 },
				  { text: 'Notes', dataField: 'Notes', width : 200 },
				  { text: 'Tax', datafield: 'Tax', width: 150, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Amount', datafield: 'Amount', width: 150, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Balance', datafield: 'Balance', width: 150, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Status', datafield: 'Status', width: 70, cellsalign: 'center' },
				  { text: 'Staff', datafield: 'Staff', width: 90, cellsalign: 'center' },
				  { text: 'Approved', datafield: 'Approved', width: 90, cellsalign: 'center' },
				  { text: 'Void-Date', datafield: 'Void-Date', width: 90, cellsalign: 'center' },
				  { text: 'Void-Desc', datafield: 'Void-Desc', cellsalign: 'center' },
                ]
            });
			
			$('#jqxgrid').jqxGrid({ pagesizeoptions: ['100', '500', '1000', '3000', '5000', '10000']}); 
			
			$("#bexport").click(function() {
				
				var type = $("#crtype").val();	
				if (type == 0){ $("#jqxgrid").jqxGrid('exportdata', 'html', 'Contract-Summary'); }
				else if (type == 1){ $("#jqxgrid").jqxGrid('exportdata', 'xls', 'Contract-Summary'); }
				else if (type == 2){ $("#jqxgrid").jqxGrid('exportdata', 'pdf', 'Contract-Summary'); }
				else if (type == 3){ $("#jqxgrid").jqxGrid('exportdata', 'csv', 'Contract-Summary'); }
			});
			
			$('#jqxgrid').on('celldoubleclick', function (event) {
     	  		var col = args.datafield;
				var value = args.value;
				var res;
			
				if (col == 'Order No')
				{ 			
				   res = value.split("DJ-00");
				   openwindow(res[1]);
				}
 			});
			
			function openwindow(val)
			{
				var site = "<?php echo site_url('ap/invoice/');?>";
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
			<tr> <td> Period </td> <td> : </td> <td> <?php echo tglin($start); ?> to <?php echo tglin($end); ?> </td> </tr>
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo $rundate; ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Contract Order - Report </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:0px dotted #000000; ">
	
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
        
	</div>

</div>

<table id="table" border="0" width="100%">
<thead>
<tr>
<th> No </th> <th> Type </th> <th> C-Type </th> <th> Deal </th> <th> Date </th> <th> Due </th> <th> Order No </th> <th> Cur </th> <th> Customer </th> <th> Notes </th> <th> Tax </th> <th> Amount </th> <th> Balance </th> <th> Status </th> <th> Staff </th>
<th> Approved </th>  <th> Void-Date </th> <th> Void-Desc </th>
</tr>
</thead>

<tbody>

<?php 

  function status($val){ if ($val == 0){ return 'C'; }elseif($val == 2){ return 'D'; } else{ return 'S'; }}
  function void_date($val){ if ($val) { return tglin($val); }}
  function approved($val){ if ($val == 0){ return '-'; }else{ return 'Y'; }}
  function ctype($val){ $ct = new Contract_type_lib(); return $ct->get_name($val); }

  $i=1; 
  if ($contract)
  {
    foreach ($contract as $res)
    {	
       echo " 
       <tr> 
           <td class=\"strongs\">".$i."</td> 
           <td class=\"strongs\">".$res->type."</td>
           <td class=\"strongs\">".ctype($res->contract_type)."</td>
		   <td class=\"strongs\">".tglin($res->deal_dates)."</td>
		   <td class=\"strongs\">".tglin($res->dates)."</td>
		   <td class=\"strongs\">".tglin($res->due)."</td> 
           <td class=\"strongs\"> CO-00".$res->no."</td> 
           <td class=\"strongs\">".$res->currency."</td>
		   <td class=\"strongs\">".$res->prefix." ".$res->name."</td> 
           <td class=\"strongs\">".$res->notes."</td>
           <td class=\"strongs\">".$res->tax."</td>
		   <td class=\"strongs\">".$res->amount."</td>
		   <td class=\"strongs\">".$res->balance."</td>
           <td class=\"strongs\">".status($res->status)."</td>
		   <td class=\"strongs\">".$res->staff."</td>
		   <td class=\"strongs\">".approved($res->approved)."</td>
		   <td class=\"strongs\">".void_date($res->void_date)."</td>
		   <td class=\"strongs\">".$res->void_desc."</td>
       </tr>";
       $i++;
    }
  }  
?>
</tbody>

</table>

</body>
</html>
