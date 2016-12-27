<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title> <?php echo isset($title) ? $title : ''; ?>  </title>
<style media="all">
	table{ font-family:"Arial", Times, serif; font-size:9pt;}
	table th{ font-family:arial; font-size:9pt;}
	h4{ font-family:"Arial", Times, serif; font-size:14pt; font-weight:600; margin:0;}
	.clear{clear:both;}
	table th{ background-color:#000; color:#fff; padding:4px 0px 4px 0px; border-top:1px solid #000000; border-bottom:1px solid #000000;}
    p{ font-family:"Arial", Times, serif; font-size:12px; margin:0; padding:0;}
	legend{font-family:"Arial", Times, serif; font-size:13px; margin:0; padding:0; font-weight:600;}
	.tablesum{ font-size:13px;}
	.strongs{ font-weight:normal; font-size:12px; border-top:1px dotted #000000; border-right:1px dotted #000; text-transform:capitalize; }
	.poder{ border-bottom:0px solid #000000; color:#0000FF;}
	.red{ color:#00C; font-weight:bold; font-size:9pt;}
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
						{ name: "Division", type: "string" },
						{ name: "Employee", type: "string" },
						{ name: "Payment", type: "string" },
						{ name: "Dept", type: "string" },
						{ name: "Basic", type: "number" },
						{ name: "Experience", type: "number" },
						{ name: "Consumption", type: "number" },
						{ name: "Transportation", type: "number" },
						{ name: "Overtime", type: "number" },
						{ name: "Bonus", type: "number" },
						{ name: "Principal", type: "number" },
						{ name: "PKS", type: "number" },
						{ name: "Kajur", type: "number" },
						{ name: "Guardians", type: "number" },
						{ name: "Picket", type: "number" },
						{ name: "Late", type: "number" },
						{ name: "Loan", type: "number" },
						{ name: "Tax", type: "number" },
						{ name: "Insurance", type: "number" },
						{ name: "Other", type: "number" },
						{ name: "Amount", type: "number" },
						{ name: "User", type: "string" },
						{ name: "Log", type: "string" },
                    ]
                };
				
			var linkrenderer = function (row, column, value) 
			{
                /*if (value.indexOf('#') != -1) {
                    value = value.substring(0, value.indexOf('#'));
                }
                var format = { onClick: 'opens("dodol")' };
                var html = $.jqx.dataFormat.formatlink(value, format);
                return html;*/
				alert(value);
            }	
			
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
				{ text: 'Division', dataField: 'Division', width : 120 },
  				{ text: 'Employee', dataField: 'Employee', width : 150 },
				{ text: 'Payment', dataField: 'Payment', width: 100 },
				{ text: 'Dept', dataField: 'Dept', width:80 },
				{ text: 'Basic', dataField: 'Basic', width:120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				{ text: 'Experience', dataField: 'Experience', width:120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				{ text: 'Consumption', datafield: 'Consumption', width:120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				{ text: 'Transportation', datafield: 'Transportation', width:120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				{ text: 'Overtime', datafield: 'Overtime', width:120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				{ text: 'Bonus', datafield: 'Bonus', width:120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				{ text: 'Principal', datafield: 'Principal', width:120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				{ text: 'PKS', datafield: 'PKS', width:120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				{ text: 'Kajur', datafield: 'Kajur', width:120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
			    { text: 'Guardians', datafield: 'Guardians', width:120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				{ text: 'Picket', datafield: 'Picket', width:120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				{ text: 'Late', datafield: 'Late', width:120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				{ text: 'Loan', datafield: 'Loan', width:120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				{ text: 'Tax', datafield: 'Tax', width:120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				{ text: 'Insurance', datafield: 'Insurance', width:120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				{ text: 'Other', datafield: 'Other', width:120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				{ text: 'Amount', datafield: 'Amount', width:120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				{ text: 'User', dataField: 'User', width:75 },
				{ text: 'Log', dataField: 'Log', width:50 }

                ]
            });
			
		$('#jqxgrid').jqxGrid({ pagesizeoptions: ['10', '20', '30', '40', '50', '100', '200', '300']}); 
		
		$("#bexport").click(function() {
				
			var type = $("#crtype").val();	
			if (type == 0){ $("#jqxgrid").jqxGrid('exportdata', 'html', 'Payroll-Transaction'); }
			else if (type == 1){ $("#jqxgrid").jqxGrid('exportdata', 'xls', 'Payroll-Transaction'); }
			else if (type == 2){ $("#jqxgrid").jqxGrid('exportdata', 'pdf', 'Payroll-Transaction'); }
			else if (type == 3){ $("#jqxgrid").jqxGrid('exportdata', 'csv', 'Payroll-Transaction'); }
		})	
		
		$("#table").hide();
		
		// end jquery	
        });
		
		
    </script>

</head>

<body>

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0">
            <tr> <td> Period </td> <td> : </td> <td> <?php echo get_month($month).' - '.$year; ?> </td> </tr>
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
    
    <p style="text-align:center; font-size:14pt; font-weight:bold;"> Payroll Transaction Summary </p>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; ">
    
    	<div id='jqxWidget'>
        <div style='margin-top: 30px;' id="jqxgrid"> </div>
        
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
    
		<table id="table" border="0" width="100%" style="visibility:hidden;">
		   
           <thead>
           <tr>
  	       <th> No </th> <th> Division </th> <th> Employee </th> <th> Payment </th> <th> Dept </th> <th> Basic </th> <th> Experience </th> 
           <th> Consumption </th> <th> Transportation </th> <th> Overtime </th> <th> Bonus </th> <th> Principal </th> <th> PKS </th> 
           <th> Kajur </th> <th> Guardians </th> <th> Picket </th> <th> Late </th> <th> Loan </th> <th> Tax </th> <th> Insurance </th> 
           <th> Other </th> <th> Amount </th> <th> User </th> <th> Log </th>
		   </tr>
           </thead>
		    
          <tbody>  
		  <?php 
		  
		  	  function employee($val)
			  {
				  $emp = new Employee_lib();
				  if ($val == 0) { $res = 'Non'; } else { $res = $emp->get_name($val); } return $res;
			  }	
			  
			  function dept($val)
			  { 
			    $dept = new Dept_lib(); 
				if ($val == 0) { $res = 'Non'; }else { $res = $dept->get_name($val); } return $res;
			  }
			  
			  function division($val)
			  {
				  $division = new Division_lib();
				  $employee = new Employee_lib();
				  $division_id = $employee->get_division_by_id($val);
				  return $division->get_name($division_id);
		      }
			  		  		  
		      $i=1; 
			  if ($results)
			  {
				 $tot_salary = 0; 
				 $tot_experience = 0; 
				 $tot_consumption = 0; 
				 $tot_transport = 0; 
				 $tot_overtime = 0; 
				 $tot_bonus = 0; 
				 $tot_principal = 0; 
				 $tot_principal_helper = 0; 
				 $tot_head = 0; 
				 $tot_home = 0; 
				 $tot_picket = 0; 
				 $tot_loan = 0; 
				 $tot_late = 0; 
				 $tot_tax = 0; 
				 $tot_insurance = 0; 
				 $tot_other = 0; 
				 $tot_amount = 0; 
				   
				foreach ($results as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\" align=\"center\">".$i."</td> 
					   <td class=\"strongs\">".division($res->employee_id)."</td>
					   <td class=\"strongs\">".employee($res->employee_id)."</td>
					   <td class=\"strongs\" align=\"righ\">".$res->payment_type."</td>
   					   <td class=\"strongs\" align=\"righ\">".dept($res->dept)."</td>
					   <td class=\"strongs\" align=\"right\">".$res->basic_salary."</td>
					   <td class=\"strongs\" align=\"right\">".$res->experience."</td>
					   <td class=\"strongs\" align=\"right\">".$res->consumption."</td>
					   <td class=\"strongs\" align=\"right\">".$res->transportation."</td>
					   <td class=\"strongs\" align=\"right\">".$res->overtime."</td>
					   <td class=\"strongs\" align=\"right\">".$res->bonus."</td>
					   <td class=\"strongs\" align=\"right\">".$res->principal."</td>
					   <td class=\"strongs\" align=\"right\">".$res->principal_helper."</td>
					   <td class=\"strongs\" align=\"right\">".$res->head_department."</td>
					   <td class=\"strongs\" align=\"right\">".$res->home_room."</td>
					   <td class=\"strongs\" align=\"right\">".$res->picket."</td>
					   <td class=\"strongs\" align=\"right\">".$res->late."</td>
					   <td class=\"strongs\" align=\"right\">".$res->loan."</td>
					   <td class=\"strongs\" align=\"right\">".$res->tax."</td>
					   <td class=\"strongs\" align=\"right\">".$res->insurance."</td>
					   <td class=\"strongs\" align=\"right\">".$res->other_discount."</td>
					   <td class=\"strongs\" align=\"right\">".$res->amount."</td>
					   <td class=\"strongs\" align=\"center\">".$res->user."</td>
					   <td class=\"strongs\" align=\"center\">".$res->log."</td>
				   </tr>";
				   $i++;
				   
				   // total
				   $tot_salary = $tot_salary + $res->basic_salary;
				   $tot_experience = $tot_experience + $res->experience;
				   $tot_consumption = $tot_consumption + $res->consumption;
				   $tot_transport = $tot_transport + $res->transportation;
				   $tot_overtime = $tot_overtime + $res->overtime;
				   $tot_bonus = $tot_bonus + $res->bonus;
				   $tot_principal = $tot_principal + $res->principal;
				   $tot_principal_helper = $tot_principal_helper + $res->principal_helper;
				   $tot_head   = $tot_head + $res->head_department;
				   $tot_home   = $tot_home + $res->home_room;
				   $tot_picket = $tot_picket + $res->picket;
				   $tot_loan   = $tot_loan + $res->loan;
				   $tot_late   = $tot_late + $res->late;
				   $tot_tax    = $tot_tax + $res->tax;
				   $tot_insurance = $tot_insurance + $res->insurance;
				   $tot_other = $tot_other + $res->other_discount;
				   $tot_amount = $tot_amount + $res->amount;
				}
			  }  
		  ?>
         
        </tbody>  
		</table>
	</div>

</div>

</body>
</html>
