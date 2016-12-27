<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title> <?php echo isset($title) ? $title : ''; ?>  </title>
<style media="all">
	table{ font-family:"Arial", Times, serif; font-size:11px;}
	table th{ font-family:arial; font-size:10pt;}
	h4{ font-family:"Arial", Times, serif; font-size:14pt; font-weight:600; margin:0;}
	.clear{clear:both;}
	table th{ background-color:#000; color:#fff; padding:4px 0px 4px 0px; border-top:1px solid #000000; border-bottom:1px solid #000000;}
    p{ font-family:"Arial", Times, serif; font-size:12px; margin:0; padding:0;}
	legend{font-family:"Arial", Times, serif; font-size:13px; margin:0; padding:0; font-weight:600;}
	.tablesum{ font-size:13px;}
	.strongs{ font-weight:normal; font-size:12px; border-top:1px dotted #000000; border-right:1px dotted #000; text-transform: capitalize; }
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
						{ name: "Type", type: "string" },
						{ name: "Employee", type: "string" },
						{ name: "Notes", type: "string" },
						{ name: "Borrow", type: "number" },
						{ name: "Paid", type: "number" },
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
				{ text: 'Date', dataField: 'Date', width : 150 },
				{ text: 'Type', dataField: 'Type', width : 150 },
  				{ text: 'Employee', dataField: 'Employee', width : 200 },
				{ text: 'Notes', dataField: 'Notes', width : 200 },
				{ text: 'Borrow', dataField: 'Borrow', cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				{ text: 'Paid', dataField: 'Paid', cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				{ text: 'Log', dataField: 'Log', width : 200, cellsalign: 'center' }
                ]
            });
			
		$('#jqxgrid').jqxGrid({ pagesizeoptions: ['20', '30', '40', '50', '100', '200', '300']}); 
		
		$("#bexport").click(function() {
				
			var type = $("#crtype").val();	
			if (type == 0){ $("#jqxgrid").jqxGrid('exportdata', 'html', 'Loan-Trans'); }
			else if (type == 1){ $("#jqxgrid").jqxGrid('exportdata', 'xls', 'Loan-Trans'); }
			else if (type == 2){ $("#jqxgrid").jqxGrid('exportdata', 'pdf', 'Loan-Trans'); }
			else if (type == 3){ $("#jqxgrid").jqxGrid('exportdata', 'csv', 'Loan-Trans'); }
		})	
		
		$("#table").hide();
		
		// end jquery	
        });
		
		
    </script>

</head>

<body onLoad="">

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0">
			<tr> <td> Employee Type </td> <td> : </td> <td> <?php $type; ?> </td> </tr>
            <tr> <td> Run Date </td> <td> : </td> <td> <?php echo date('d-m-Y'); ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:500px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> </h4>
           <p style="margin:5px; padding:0;"> <?php echo $address; ?> <br> Telp. <?php echo $phone1.' - '.$phone2; ?> <br>
               Website : <?php echo $website; ?> &nbsp; &nbsp; Email : <?php echo $email; ?> </p>
	   </div>
	</center> <hr>
    
    <p style="text-align:center; font-size:14pt; font-weight:bold;"> Employee's Loans Payment Report </p>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; ">
		
        <div id='jqxWidget'>
        <div style='margin-top:0px;' id="jqxgrid"> </div>
        
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
  	       <th> No </th> <th> Date </th> <th> Type </th> <th> Employee </th> <th> Notes </th> <th> Borrow </th> <th> Paid </th> <th> Log </th> 
		   </tr>
           </thead>
		  
          <tbody>  
		  <?php 
		  
		  	  function employee($val)
			  {
				  $emp = new Employee_lib();
				  if ($val == 0) { $res = 'Non'; } else { $res = $emp->get_name($val); } return $res;
			  }		
			  
			  function borrow($type='borrow',$amount=0){ if ($type == 'borrow'){ return $amount; }else{ return 0; } }
			  function paid($type='paid',$amount=0){ if ($type == 'paid'){ return $amount; }else{ return 0; } }
		  		  
		      $i=1; 
			  if ($results)
			  {
				$total_borrow=0; 
				$total_paid=0; 
				foreach ($results as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".tglin($res->date)."</td>
					   <td class=\"strongs\">".$res->type."</td>
					   <td class=\"strongs\">".employee($res->employee_id)."</td>
					   <td class=\"strongs\">".$res->notes."</td>
					   <td class=\"strongs\" align=\"right\">".borrow($res->trans_type,$res->amount)."</td>
					   <td class=\"strongs\" align=\"right\">".paid($res->trans_type,$res->amount)."</td>
					   <td class=\"strongs\">".$res->log."</td>
				   </tr>";
				   $i++;
				   $total_borrow = $total_borrow + borrow($res->trans_type,$res->amount);
				   $total_paid = $total_paid + paid($res->trans_type,$res->amount);
				}
			  }  
			  
		  ?>
          </tbody>
          
		  <!-- <tr> <td align="right" class="strongs" colspan="5"> <b> Total : </b> </td> 
                <td class="strongs" align="right"> 
                    <b> <?php //echo number_format(isset($total_borrow)) ? number_format($total_borrow) : 0; ?> </b> 
                </td> 
                <td class="strongs" align="right">
                <b> <?php //echo number_format(isset($total_paid)) ? number_format($total_paid) : 0; ?> </b> 
                </td> 
           </tr>-->
		</table>
	</div>

</div>

</body>
</html>
