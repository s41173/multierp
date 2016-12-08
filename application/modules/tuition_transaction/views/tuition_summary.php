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
                        { name: "Period", type: "string" },
                        { name: "Tuitions", type: "string" },
                        { name: "Date", type: "string" },
						{ name: "Fee", type: "string" },
						{ name: "Department", type: "string" },
						{ name: "Grade", type: "string" },
						{ name: "Student", type: "string" },
						{ name: "School", type: "number" },
						{ name: "Practical", type: "number" },
						{ name: "OSIS", type: "number" },
						{ name: "Computer", type: "number" },
						{ name: "Cost", type: "number" },
						{ name: "BOS", type: "number" },
						{ name: "Foundation", type: "number" },
						{ name: "Balance", type: "number" },
						{ name: "Status", type: "string" },
						{ name: "Month", type: "string" },
						{ name: "User", type: "string" },
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
				  { text: 'Period', dataField: 'Period', width: 85 },
				  { text: 'Tuitions', dataField: 'Tuitions', width: 75 },
				  { text: 'Date', dataField: 'Date', width: 100 },
				  { text: 'Fee', dataField: 'Fee', width: 130 },
				  { text: 'Department', dataField: 'Department', width: 100 },
				  { text: 'Grade', dataField: 'Grade', width: 100 },
				  { text: 'Student', dataField: 'Student', width: 150 },
				  { text: 'School', datafield: 'School', width: 120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Practical', datafield: 'Practical', width: 120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'OSIS', datafield: 'OSIS', width: 120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Computer', datafield: 'Computer', width: 120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Cost', datafield: 'Cost', width: 120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Foundation', datafield: 'Foundation', width: 120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Balance', datafield: 'Balance', width: 120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Status', dataField: 'Status', cellsalign: 'center', width: 80 },
				  { text: 'Month', dataField: 'Month', width: 80 },
				  { text: 'User', dataField: 'User', width: 80 },
                ]
            });
			
			$('#jqxgrid').jqxGrid({ pagesizeoptions: ['50', '100', '250', '500', '1000', '2000', '3000']}); 
			
			$("#bexport").click(function() {
				
				var type = $("#crtype").val();	
				if (type == 0){ $("#jqxgrid").jqxGrid('exportdata', 'html', 'Tuition-Transaction'); }
				else if (type == 1){ $("#jqxgrid").jqxGrid('exportdata', 'xls', 'Tuition-Transaction'); }
				else if (type == 2){ $("#jqxgrid").jqxGrid('exportdata', 'pdf', 'Tuition-Transaction'); }
				else if (type == 3){ $("#jqxgrid").jqxGrid('exportdata', 'csv', 'Tuition-Transaction'); }
			});
			
			$("#table").hide();
			
        });
    </script>

</head>
<body>

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0">
			<tr> <td> Period </td> <td> : </td> <td> <?php echo tglin($start); ?> &nbsp; to &nbsp; <?php echo tglin($end); ?> </td> </tr>
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo $rundate; ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Tuition Transaction Fee - Summary Report </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:0px dotted #000000; ">
	
    	<div id='jqxWidget'>
        <div style='margin-top:10px;' id="jqxgrid"> </div>
        
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
    
		<table id="table" border="0" width="100%" style="visibility:hidden;">
        
           <thead>	
		   <tr>
 	       <th> No </th> <th> Period </th> <th> Tuitions </th> <th> Date </th> <th> Fee </th> <th> Department </th> <th> Grade </th> 
           <th> Student </th> <th> School </th> <th> Practical </th> <th> OSIS </th> <th> Computer </th> <th> Cost </th> 
           <th> BOS </th> <th> Foundation </th> <th> Balance </th> <th> Status </th> <th> Month </th> <th> User </th>
		   </tr>
           </thead>
		    
          <tbody>  
		  <?php 
		  
		  	  function status($val){ if ($val == 0){ $val = 'B'; } elseif ($val == 1){ $val = 'N'; } elseif ($val == 2){ $val = 'F'; } return $val; }	
			  
			  function dept($student)
			  {
				  $st = new Student_lib();
				  $dept = new Dept_lib();
				  $student = $dept->get_name($st->get_dept($student)); 
				  return $student;
			  }
			  
			  function student($val){ $st = new Student_lib(); return $st->get_name($val); }
			  function user($val){ $user = new Admin_lib(); return $user->get_username($val); }
			  function fee($val){ $fee = new Regcost_lib(); return $fee->get_name($val); }
			  function grade($val)
			  { 
			    $st = new Student_lib(); 
				$gr = new Grade_lib();
				return $gr->get_name($st->get_grade($val));
			  }
			  
			function month_stts($val)
			{
				$res=0;
				switch ($val)
				{
				  case 'p1':$res = 1; break;
				  case 'p2':$res = 2; break;
				  case 'p3':$res = 3; break;
				  case 'p4':$res = 4; break;
				  case 'p5':$res = 5; break;
				  case 'p6':$res = 6; break;
				  case 'p7':$res = 7; break;
				  case 'p8':$res = 8; break;
				  case 'p9':$res = 9; break;
				  case 'p10':$res = 10; break;
				  case 'p11':$res = 11; break;
				  case 'p12':$res = 12; break;
				}
				return $res;
			}
			  
			  function month($val)
			  {
				  $ps = new Payment_status_lib();
				  return $ps->months_name(month_stts($val));
			  }
			  
			 $tot_school = 0;
			 $tot_practical = 0;
			 $tot_osis = 0;
			 $tot_computer = 0;
			 $tot_cost = 0;
			 $tot_aid_goverment = 0;
			 $tot_aid_foundation = 0;
			 $tot_amount = 0;
		  
		      $i=1; 
			  if ($tuitions)
			  {  
				foreach ($tuitions as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\" align=\"center\">".$res->financial_year."</td> 
					   <td class=\"strongs\" align=\"center\"> TJ-00".$res->tuition."</td> 
					   <td class=\"strongs\" align=\"center\">".tglin($res->dates)."</td>
					   <td class=\"strongs\" align=\"center\">".fee($res->fee_type)."</td> 
   					   <td class=\"strongs\" align=\"center\">".dept($res->student)."</td>
					   <td class=\"strongs\" align=\"center\">".grade($res->student)."</td>
					   <td class=\"strongs\">".strtoupper(student($res->student))."</td> 
					   <td class=\"strongs\" align=\"right\">".$res->school_fee."</td>
					   <td class=\"strongs\" align=\"right\">".$res->practical."</td> 
					   <td class=\"strongs\" align=\"right\">".$res->osis."</td>
					   <td class=\"strongs\" align=\"right\">".$res->computer."</td> 
					   <td class=\"strongs\" align=\"right\">".$res->cost."</td>
					   <td class=\"strongs\" align=\"right\">".$res->aid_goverment."</td> 
					   <td class=\"strongs\" align=\"right\">".$res->aid_foundation."</td>
					   <td class=\"strongs\" align=\"right\">".$res->amount."</td> 
					   <td class=\"strongs\" align=\"center\">".status($res->type)."</td>
					   <td class=\"strongs\" align=\"center\">".month($res->month)."</td>
					   <td class=\"strongs\" align=\"center\">".user($res->user)."</td> 
				   </tr>";
				   $i++;
				   
				   $tot_school = $tot_school + $res->school_fee;
				   $tot_practical = $tot_practical + $res->practical;
				   $tot_osis = $tot_osis + $res->osis;
				   $tot_computer = $tot_computer + $res->computer;
				   $tot_cost = $tot_cost + $res->cost;
				   $tot_aid_goverment = $tot_aid_goverment + $res->aid_goverment;
   				   $tot_aid_foundation = $tot_aid_foundation + $res->aid_foundation;
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
