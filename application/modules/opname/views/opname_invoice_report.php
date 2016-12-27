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
						{ name: "Category", type: "string" },
						{ name: "Product", type: "string" },
						{ name: "Unit", type: "string" },
						{ name: "End Qty", type: "number" },
						{ name: "Physical", type: "number" },
						{ name: "Difference", type: "number" }
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
				  { text: 'Category', dataField: 'Category', width : 200 },
  				  { text: 'Product', dataField: 'Product' },
				  { text: 'Unit', dataField: 'Unit', width:90 },
				  { text: 'End Qty', dataField: 'End Qty', width : 150, cellsalign: 'center' },
				  { text: 'Physical', dataField: 'Physical', width : 150, cellsalign: 'center' },
				  { text: 'Difference', dataField: 'Difference', width : 150, cellsalign: 'center', cellsformat: 'number', aggregates: ['sum']}
				  
                ]
            });
			
			$("#bexport").click(function() {
				
				var type = $("#crtype").val();	
				if (type == 0){ $("#jqxgrid").jqxGrid('exportdata', 'html', 'Opname-Receipt'); }
				else if (type == 1){ $("#jqxgrid").jqxGrid('exportdata', 'xls', 'Opname-Receipt'); }
				else if (type == 2){ $("#jqxgrid").jqxGrid('exportdata', 'pdf', 'Opname-Receipt'); }
				else if (type == 3){ $("#jqxgrid").jqxGrid('exportdata', 'csv', 'Opname-Receipt'); }
			});
			
			$('#jqxgrid').jqxGrid({ pagesizeoptions: ['10', '20', '30', '40', '50', '100', '200', '300']}); 
			
			$("#table").hide();
			
        });
    </script>

</head>

<body>

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0">
    		<tr> <td> SOP No </td> <td> : </td> <td> <?php echo 'SO-00'.$no.' - '.$podate; ?> </td> </tr>
			<tr> <td> Supervisor </td> <td> : </td> <td> <?php echo $supervisor; ?> </td> </tr>
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo $rundate; ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Taking Inventory - Report Details </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:1px dotted #000000; ">
    
    	 <div id='jqxWidget'>
            <div style='margin-top: 20px;' id="jqxgrid"> </div>
            
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
 	       <th> No </th> <th> Category </th> <th> Product </th> <th> Unit </th> <th> End Qty </th> <th> Physical </th> <th> Difference </th>
		   </tr>
           </thead>
		   
          <tbody> 
		  <?php  
		  
		     $i=1; 
			  if ($reports)
			  {
				
				function category($pid)
				{
					$pro = new Products_lib();
					$cat = new Category_lib();
					return $cat->get_name($pro->get_category($pid));
				}
				  
				foreach ($reports as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".category($res->pid)."</td>
					   <td class=\"strongs\">".$res->product."</td>
					   <td class=\"strongs\">".$res->unit."</td> 
					   <td class=\"strongs\" align=\"right\">".$res->end."</td> 
					   <td class=\"strongs\" align=\"right\">".$res->physical."</td>
					   <td class=\"strongs\" align=\"right\">".$res->difference."</td> 
				   </tr>";
				  // poder($res->no); echo "<br/>";
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
