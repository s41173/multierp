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
						{ name: "Vendor", type: "string" },
                        { name: "Type", type: "string" },
						{ name: "Contact", type: "string" },
						{ name: "NPWP", type: "string" },
						{ name: "Address", type: "string" },
                        { name: "Phone", type: "string" },
						{ name: "Fax", type: "string" },
						{ name: "Mobile", type: "string" },
						{ name: "Email", type: "string" },
                        { name: "City", type: "string" },
                        { name: "Acc Name", type: "string" },
                        { name: "Acc No", type: "string" },
                        { name: "Bank", type: "string" }
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
				  { text: 'Vendor', dataField: 'Vendor', width : 175 },
  				  { text: 'Type', dataField: 'Type', width : 100 },
				  { text: 'Contact', dataField: 'Contact', width : 150 },
				  { text: 'NPWP', dataField: 'NPWP', width : 150 },
				  { text: 'Address', datafield: 'Address', width: 200, cellsalign: 'center' },
				  { text: 'Phone', datafield: 'Phone', width: 200, cellsalign: 'center'},
                  { text: 'Fax', datafield: 'Fax', width: 130, cellsalign: 'center'},
                  { text: 'Mobile', datafield: 'Mobile', width: 130, cellsalign: 'center'},
                  { text: 'Email', datafield: 'Email', width: 150, cellsalign: 'center'},
                  { text: 'City', datafield: 'City', width: 90, cellsalign: 'center'},
                  { text: 'Acc Name', datafield: 'Acc Name', width: 130, cellsalign: 'center'},
				  { text: 'Acc No', datafield: 'Acc No', width: 120, cellsalign: 'center'},
                  { text: 'Bank', datafield: 'Bank', width: 150, cellsalign: 'center'}
                ]
            });
			
			$('#jqxgrid').jqxGrid({ pagesizeoptions: ['100', '300', '500', '1000', '2000', '3000']}); 
			
			$("#bexport").click(function() {
				
				var type = $("#crtype").val();	
				if (type == 0){ $("#jqxgrid").jqxGrid('exportdata', 'html', 'Vendor-Summary'); }
				else if (type == 1){ $("#jqxgrid").jqxGrid('exportdata', 'xls', 'Vendor-Summary'); }
				else if (type == 2){ $("#jqxgrid").jqxGrid('exportdata', 'pdf', 'Vendor-Summary'); }
				else if (type == 3){ $("#jqxgrid").jqxGrid('exportdata', 'csv', 'Vendor-Summary'); }
			});
			
			$('#jqxgrid').on('celldoubleclick', function (event) {
     	  		var col = args.datafield;
				var value = args.value;
				var res;
			
				if (col == 'Order No')
				{ 			
				   res = value.split("DJC-00");
				   openwindow(res[1]);
				}
 			});
			
			function openwindow(val)
			{
				var site = "<?php echo site_url('apc/invoice/');?>";
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
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo $rundate; ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Vendor - Report </h4>
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
<th> No </th> <th> Vendor </th> <th> Type </th> <th> Contact </th> <th> NPWP </th> <th> Address </th> <th> Phone </th>
<th> Fax </th> <th> Mobile </th> <th> Email </th> <th> City </th> <th> Acc Name </th> <th> Acc No </th> 
<th> Bank </th>
</tr>
</thead>

<tbody>

<?php 

  $i=1; 
  if ($results)
  {
    foreach ($results as $res)
    {	
       echo " 
       <tr> 
           <td class=\"strongs\">".$i."</td> 
           <td class=\"strongs\">".$res->prefix.' '.$res->name."</td> 
           <td class=\"strongs\">".$res->type."</td> 
           <td class=\"strongs\">".$res->cp1."</td> 
           <td class=\"strongs\">".$res->npwp."</td> 
           <td class=\"strongs\">".$res->address."</td> 
           <td class=\"strongs\">".$res->phone1.' / '.$res->phone2."</td> 
           <td class=\"strongs\">".$res->fax."</td> 
           <td class=\"strongs\">".$res->hp."</td> 
           <td class=\"strongs\">".$res->email."</td>
           <td class=\"strongs\">".$res->city."</td> 
           <td class=\"strongs\">".$res->acc_name."</td> 
           <td class=\"strongs\">".$res->acc_no."</td> 
           <td class=\"strongs\">".$res->bank."</td> 
       </tr>";
       $i++;
    }
  }  
?>
</tbody>

</table>

</body>
</html>
