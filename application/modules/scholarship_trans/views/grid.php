<!DOCTYPE html>
<html lang="en">
<head>
    <title id='Description'>This example shows how to create a Grid from Array data.
    </title>
    <link rel="stylesheet" href="<?php echo base_url().'js/jxgrid/' ?>css/jqx.base.css" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxcore.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxdata.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxbuttons.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxcheckbox.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxscrollbar.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxmenu.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.selection.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.sort.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.filter.js"></script> <!-- filter -->
    <script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxgrid.columnsresize.js"></script>
   
   <!-- <script type="text/javascript" src="js/jqxgrid.edit.js"></script>-->
    <!--<script type="text/javascript" src="js/jqxlistbox.js"></script>-->
    <!--<script type="text/javascript" src="js/jqxdropdownlist.js"></script>-->
<!--    <script type="text/javascript" src="js/sdemos.js"></script>-->

<script type="text/javascript">
var uri = "<?php echo site_url('ajax')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";
var site = "<?php echo site_url();?>";

</script>

   <script type="text/javascript">
        $(document).ready(function () {
            var url = site+'/scholarship_trans/generate_json';
            // prepare the data
            var source =
            {
                datatype: "json",
                datafields: [
                    { name: 'id' },
                    { name: 'name' }
                ],
                id: 'id',
                url: url,
                root: 'datax'
            };
            var dataAdapter = new $.jqx.dataAdapter(source);
            $("#jqxgrid").jqxGrid(
            {
                width: 850,
                source: dataAdapter,
                sortable: true,
              //  filterable: true,
                //filtermode: 'excel',
                columnsresize: true,
               // autoshowfiltericon: false,
                columns: [
                  { text: 'ID', dataField: 'id', width: 200 },
                  { text: 'Name', dataField: 'name', width: 200 }
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
        <div style='margin-top: 10px;' id="jqxgrid">
        </div>
    </div>
</body>
</html>