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
                        { name: "Generation", type: "string" },
                        { name: "Student", type: "string" },
                        { name: "Department", type: "string" },
						{ name: "Grade", type: "string" },
						{ name: "Date", type: "string" },
						{ name: "Credit", type: "string" },
						{ name: "Amount", type: "number" },
						{ name: "Certificate", type: "string" },
						{ name: "Taking", type: "string" },
						{ name: "Status", type: "string" },
						{ name: "User", type: "string" }
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
				  { text: 'Generation', dataField: 'Generation', width: 115 },
				  { text: 'Student', dataField: 'Student' },
				  { text: 'Department', dataField: 'Department', width: 100 },
				  { text: 'Grade', dataField: 'Grade', width: 100 },
				  { text: 'Date', dataField: 'Date', width: 100 },
				  { text: 'Credit', dataField: 'Credit', width: 100 },
				  { text: 'Amount', datafield: 'Amount', width: 120, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Certificate', dataField: 'Certificate', width: 150 },
				  { text: 'Taking', dataField: 'Taking', width: 100 },
				  { text: 'Status', dataField: 'Status', cellsalign: 'center', width: 80 },
				  { text: 'User', dataField: 'User', width: 80 },
                ]
            });
			
			$('#jqxgrid').jqxGrid({ pagesizeoptions: ['50', '100', '250', '500', '1000', '2000', '3000']}); 
			
			$("#bexport").click(function() {
				
				var type = $("#crtype").val();	
				if (type == 0){ $("#jqxgrid").jqxGrid('exportdata', 'html', 'Generation'); }
				else if (type == 1){ $("#jqxgrid").jqxGrid('exportdata', 'xls', 'Generation'); }
				else if (type == 2){ $("#jqxgrid").jqxGrid('exportdata', 'pdf', 'Generation'); }
				else if (type == 3){ $("#jqxgrid").jqxGrid('exportdata', 'csv', 'Generation'); }
			});
			
			$("#table").hide();
			
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
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Graduation - Summary Report </h4>
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
    
		<table id="table" border="0" width="100%">
        
           <thead>	
		   <tr>
 	       <th> No </th> <th> Generation </th> <th> Student </th> <th> Department </th> <th> Grade </th> 
           <th> Date </th> <th> Credit </th> <th> Amount </th> <th> Certificate </th> <th> Taking </th> <th> Status </th> <th> User </th> 
		   </tr>
           </thead>
		    
          <tbody>  
		  <?php 
		  
		  	  function status($val){ if ($val == 0){ $val = 'N'; } elseif ($val == 1){ $val = 'Y'; }return $val; }	
			  
			  function dept($student)
			  {
				  $st = new Student_lib();
				  $dept = new Dept_lib();
				  $student = $dept->get_name($st->get_dept($student)); 
				  return $student;
			  }
			  
			  function student($val){ $st = new Student_lib(); return $st->get_name($val); }
			  function user($val){ $user = new Admin_lib(); return $user->get_username($val); }
			  function grade($val)
			  { 
			    $st = new Student_lib(); 
				$gr = new Grade_lib();
				return $gr->get_name($st->get_grade($val));
			  }
			  
		  
		      $i=1; 
			  if ($result)
			  {  
				foreach ($result as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\" align=\"center\">".$res->year."</td> 
					   <td class=\"strongs\">".strtoupper(student($res->student_id))."</td> 
					   <td class=\"strongs\" align=\"center\">".dept($res->student_id)."</td>
					   <td class=\"strongs\" align=\"center\">".grade($res->student_id)."</td>
					   <td class=\"strongs\" align=\"center\">".tglin($res->dates)."</td>
					   <td class=\"strongs\" align=\"right\">".$res->credit."</td>
					   <td class=\"strongs\" align=\"right\">".$res->amount."</td> 
					   <td class=\"strongs\" align=\"right\">".$res->certificate_code."</td>
					    <td class=\"strongs\" align=\"right\">".tglin($res->taking_dates)."</td> 
					   <td class=\"strongs\" align=\"center\">".status($res->type)."</td>
					   <td class=\"strongs\" align=\"center\">".user($res->user)."</td> 
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
