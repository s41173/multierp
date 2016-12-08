<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title> <?php echo isset($title) ? $title : ''; ?>  </title>
<style media="all">
	table{ font-family:"Arial", serif; font-size:8pt;}
	table th{ font-family:arial; font-size:9pt;}
	h4{ font-family:"Times New Roman", Times, serif; font-size:14px; font-weight:600;}
	.clear{clear:both;}
	table th{ background-color:#EFEFEF; padding:4px 0px 4px 0px; border-top:1px solid #000000; border-bottom:1px solid #000000;}
    p{ font-family:"Times New Roman", Times, serif; font-size:12px; margin:0; padding:0;}
	legend{font-family:"Times New Roman", Times, serif; font-size:13px; margin:0; padding:0; font-weight:600;}
	.tablesum{ font-size:9pt;}
	.strongs{ font-weight:normal; font-size:8pt; border-top:1px dotted #000000; text-transform:uppercase; }
	.poder{ border-bottom:0px solid #000000; color:#0000FF;}
	.no{ width:1cm;}
	.name{ width:6cm;}
	.dept{ width:2cm;}
	.general{ width:3cm;}
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
						{ name: "Department", type: "string" },
						{ name: "Level", type: "string" },
						{ name: "Tuition", type: "string" },
						{ name: "Qty", type: "number" },
						{ name: "School", type: "number" },
						{ name: "OSIS", type: "number" },
						{ name: "Computer", type: "number" },
						{ name: "Practical", type: "number" },
						{ name: "Cost", type: "number" },
						{ name: "Aid", type: "number" },
						{ name: "Amount", type: "number" },
						{ name: "Balance", type: "number" }
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
				  { text: 'Department', dataField: 'Department', width : 110 },
  				  { text: 'Level', dataField: 'Level', width : 70 },
				  { text: 'Tuition', dataField: 'Tuition', width : 180 },
				  { text: 'Qty', datafield: 'Qty', width: 70, cellsalign: 'center', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'School', datafield: 'School', width: 100, cellsalign: 'right', cellsformat: 'number' },
				  { text: 'OSIS', datafield: 'OSIS', width: 90, cellsalign: 'right', cellsformat: 'number' },
				  { text: 'Computer', datafield: 'Computer', width: 100, cellsalign: 'right', cellsformat: 'number' },
				  { text: 'Practical', datafield: 'Practical', width: 100, cellsalign: 'right', cellsformat: 'number' },				  
				  { text: 'Cost', datafield: 'Cost', width: 100, cellsalign: 'right', cellsformat: 'number' },				  
				  { text: 'Aid', datafield: 'Aid', width: 100, cellsalign: 'right', cellsformat: 'number' },
				  { text: 'Amount', datafield: 'Amount', width: 90, cellsalign: 'right', cellsformat: 'number' },
				  { text: 'Balance', datafield: 'Balance', cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] }				  
				  
                ]
            });
			
			$("#bexport").click(function() {
				
				var type = $("#crtype").val();	
				if (type == 0){ $("#jqxgrid").jqxGrid('exportdata', 'html', 'AR-Report-Finance'); }
				else if (type == 1){ $("#jqxgrid").jqxGrid('exportdata', 'xls', 'AR-Report-Finance'); }
				else if (type == 2){ $("#jqxgrid").jqxGrid('exportdata', 'pdf', 'AR-Report-Finance'); }
				else if (type == 3){ $("#jqxgrid").jqxGrid('exportdata', 'csv', 'AR-Report-Finance'); }
			});
			
			
			$('#jqxgrid').jqxGrid({ pagesizeoptions: ['50', '100', '250', '500', '1000', '2000', '3000']}); 
			
			$("#table").hide();
			
        });
    </script>

</head>

<body>

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0">
    		<tr> <td> Department </td> <td> : </td> <td> <?php echo $department; ?> </td> </tr>
			<tr> <td> Faculty </td> <td> : </td> <td> <?php echo tgleng($faculty); ?> </td> </tr>
            <tr> <td> Grade </td> <td> : </td> <td> <?php echo tgleng($grade); ?> </td> </tr>
            <tr> <td> Academic Year </td> <td> : </td> <td> <?php echo $year; ?> </td> </tr>
            <tr> <td> Period </td> <td> : </td> <td> <?php echo get_month($period); ?> </td> </tr>
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo date('d-m-Y'); ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:280px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Bruto Income </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="border:0px solid brown; margin-top:20px; border-bottom:0px dotted #000000;">
	
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
        
        <div style="border:0px solid red; float:left; margin:15px 0px 0px 0px;">
		<p> Prepared By : <br/> <br/> <br/>  <br/> <br/>
		    (_______________________) 
		</p>
	    </div>
	
        <div style="border:0px solid red; float:left; margin:15px 0px 0px 40px;">
            <p> Approval By : <br/> <br/> <br/>  <br/> <br/>
                (_______________________) 
            </p>
        </div>
    
		<table id="table" border="0" width="100%">
		   <thead>
           <tr>
 	       <th> No </th> <th> Department </th> <th> Level </th> <th> Tuition </th> <th> Qty </th> <th> School </th> <th> OSIS </th> <th> Computer </th> 
           <th> Practical </th> <th> Cost </th> <th> Aid </th> <th> Amount </th> <th> Balance </th>
		   </tr>
           </thead>
           
           <tbody>
		    
		  <?php 
		   
			  function get_fee($dept,$grade,$type)
			  {
				 $cost = new Regcost_lib();
				 $gr = new Grade_lib();
		         $cost = $cost->get_by_id($gr->get_fee($grade));
				 $res = 0;
				 
				 if ($type == 0){ $res = $cost->school; }
				 elseif ($type == 1){ $res = $cost->practice; }
				 elseif ($type == 2){ $res = $cost->osis; }
				 elseif ($type == 3){ $res = $cost->computer; }
				 return intval($res);
			  }
			  
			  function amount($payments,$fee)
			  {
				  $regcost = new Regcost_lib();
				  $hasil = 0;
				  
				  foreach ($payments as $res)
				  {
					 if ($regcost->get_by_student($res->student_id) == $fee){ $hasil = $hasil + 1; }
				  }
				  return $hasil;
			  }
			  
			 
		      $i=1; 
			  $dept = new Dept_lib();
			  
			  if ($fee)
			  {
				foreach ($fee as $res)
				{	
				   echo " 
				   <tr> 
			<td class=\"strongs\" align=\"center\">".$i."</td> 
			<td class=\"strongs\">".$dept->get_name($res->dept_id)."</td>
			<td class=\"strongs\">".$res->grade."</td> 
			<td class=\"strongs\">".$res->name."</td>
			<td class=\"strongs\" align=\"center\">".amount($payments,$res->id)."</td>
			<td class=\"strongs\" align=\"right\">".$res->school."</td>
			<td class=\"strongs\" align=\"right\">".$res->osis."</td>
			<td class=\"strongs\" align=\"right\">".$res->computer."</td>
			<td class=\"strongs\" align=\"right\">".$res->practice."</td>
			<td class=\"strongs\" align=\"right\">".$res->others."</td>
			<td class=\"strongs\" align=\"right\">".$res->aid."</td>
			<td class=\"strongs\" align=\"right\">".$res->p1."</td>
			<td class=\"strongs\" align=\"right\">".intval(amount($payments,$res->id)*$res->p1)."</td>
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
