<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title> <?php echo isset($title) ? $title : ''; ?>  </title>
<style media="all">
	table{ font-family:"Arial"; font-size:11px;}
	h4{ font-family:"Arial"; font-size:14px; font-weight:600;}
	.clear{clear:both;}
	table th{ background-color:#EFEFEF; padding:4px 0px 4px 0px; border-top:1px solid #000000; border-bottom:1px solid #000000;}
    p{ font-family:"Arial"; font-size:12px; margin:0; padding:0;}
	legend{font-family:"Times New Roman", Times, serif; font-size:13px; margin:0; padding:0; font-weight:600;}
	.tablesum{ font-size:13px;}
	.strongs{ font-weight:normal; font-size:12px; border-top:1px dotted #000000; }
	.right{ font-weight:normal; font-size:12px; border-top:1px dotted #000000; text-align:right; }
	.center{ font-weight:normal; font-size:12px; border-top:1px dotted #000000; text-align:center; }
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
						{ name: "Code", type: "string" },
						{ name: "Currency", type: "string" },
						{ name: "Category", type: "string" },
						{ name: "Brand", type: "string" },
						{ name: "Warehouse", type: "string" },
						{ name: "Type", type: "string" },
						{ name: "Name / Model", type: "string" },
						{ name: "Qty", type: "number" },
						{ name: "Unit", type: "string" },
						{ name: "Unit Cost", type: "number" },
						{ name: "Amount", type: "number" }
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
				  { text: 'Code', dataField: 'Code', width : 90 },
  				  { text: 'Currency', dataField: 'Currency', width : 90 },
				  { text: 'Category', dataField: 'Category',  width : 120 },
				  { text: 'Brand', dataField: 'Brand', width:110 },
				  { text: 'Warehouse', dataField: 'Warehouse', width:140 },
				  { text: 'Type', dataField: 'Type', width:100 },
				  { text: 'Name / Model', dataField: 'Name / Model' },
				  { text: 'Qty', datafield: 'Qty', width: 70, cellsalign: 'center', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Unit', datafield: 'Unit', width: 70, cellsalign: 'center' },
				  { text: 'Unit Cost', datafield: 'Unit Cost', width: 110, cellsalign: 'right', cellsformat: 'number'},
				  { text: 'Amount', datafield: 'Amount', width: 150, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] }
				  
                ]
            });
			
			$("#bexport").click(function() {
				
				var type = $("#crtype").val();	
				if (type == 0){ $("#jqxgrid").jqxGrid('exportdata', 'html', 'Product'); }
				else if (type == 1){ $("#jqxgrid").jqxGrid('exportdata', 'xls', 'Product'); }
				else if (type == 2){ $("#jqxgrid").jqxGrid('exportdata', 'pdf', 'Product'); }
				else if (type == 3){ $("#jqxgrid").jqxGrid('exportdata', 'csv', 'Product'); }
			});
			
			$('#jqxgrid').jqxGrid({ pagesizeoptions: ['20', '30', '40', '50', '100', '200', '300', '500', '1000', '2000']}); 
			
			$("#table").hide();
			
        });
    </script>

</head>

<body>

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0">
    		<tr> <td> Category </td> <td> : </td> <td> <?php echo $category; ?> </td> </tr>
			<tr> <td> Brand </td> <td> : </td> <td> <?php echo $brand; ?> </td> </tr>
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo $rundate; ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Inventory Report </h4>
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
 	       <th> No </th> <th> Code </th> <th> Currency </th> <th> Category </th> <th> Brand </th> <th> Warehouse </th> <th> Type </th> <th> Name / Model </th> 
           <th> Qty </th> <th> Unit </th> <th> Unit Cost </th> <th> Amount </th>
		   </tr>
           </thead>
		  
          <tbody> 
		  <?php 		  
		  
		      $i=1; 
			  if ($reports)
			  {
				  
				$wr = new Warehouse_lib();
			    function get_unit_cost($pid)
				{ 
				    $pro = new Products_lib();
					$qty = $pro->get_qty($pid);
					$sum = $pro->get_sum_stock($pid);
					if ($qty == 0 || $sum == 0){ return 0; } else { return intval($sum/$qty); } 
				}
				  
				foreach ($reports as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\"> PRO-00".$res->id."</td>
					   <td class=\"strongs\">".$res->currency."</td>
					   <td class=\"strongs\">".$res->category."</td>
					   <td class=\"strongs\">".$res->brand."</td>
   					   <td class=\"strongs\">".$wr->get_name($res->warehouse_id)."</td>
					   <td class=\"strongs\">".ucfirst($res->type)."</td>
					   <td class=\"strongs\">".$res->name."</td>
					   <td class=\"center\">".$res->qty."</td>
   				       <td class=\"center\">".$res->unit."</td> 
					   <td class=\"right\">".get_unit_cost($res->id)."</td> 
					   <td class=\"right\">".$res->qty*get_unit_cost($res->id)."</td> 
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
