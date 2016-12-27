<!DOCTYPE html>
<html lang="en">
<head>
    <title id='Description'> Tuition List Grid  </title>
    
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
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.selection.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.pager.js"></script>
   


<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";
var site = "<?php echo site_url();?>";

</script>

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
                        { name: "Fee", type: "string" },
                        { name: "Name", type: "string" },
                        { name: "Nis", type: "string" },
                    ]
                };
			
            var dataAdapter = new $.jqx.dataAdapter(source);
            $("#jqxgrid").jqxGrid(
            {
                width: '99.8%',
				source: dataAdapter,
				sortable: true,
				filterable: true,
				pageable: true,
				filtermode: 'excel',
				autoheight: true,
				columnsresize: true,
				autoshowfiltericon: false,
                columns: [
                  { text: 'Fee', dataField: 'Fee', width: 200 },
				  { text: 'Name', dataField: 'Name', width: 200 },
				  { text: 'Nis', dataField: 'Nis', width: 200 },
/*                  { text: 'Product', dataField: 'productname', width: 180 },
                  { text: 'Quantity', dataField: 'quantity', width: 80, cellsalign: 'right' },
                  { text: 'Unit Price', dataField: 'price', width: 90, cellsalign: 'right', cellsformat: 'c2' },
                  { text: 'Total', dataField: 'total', cellsalign: 'right', minwidth: 100, cellsformat: 'c2' }*/
                ]
            });
        });
    </script>
   
</head>
<body id='default'>

	<div id='jqxWidget'>
        <div style='margin-top: 10px;' id="jqxgrid"> </div>
    </div>
    
    <table id="table" style=" visibility:hidden;">
    <thead> <tr> <th> Fee </th> <th> Name </th> <th> Nis </th> </tr> </thead>
    
    <tbody>
    <?php
    
		if ($result)
		{ 
		    $fee = new Regcost_lib();
			$st = new Student_lib();
			foreach($result as $res)
			{
				echo "
				<tr>
				<td> ".$fee->get_name($fee->get_by_student($res->student_id))." </td>
				<td> ".$st->get_name($res->student_id)." </td>
				<td> ".$st->get_nisn($res->student_id)." </td>
				</tr>
				";
			}
		}
	
	?>
    </tbody>
    
    </table>
    
</body>
</html>