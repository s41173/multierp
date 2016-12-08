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
	table th{ background-color:#EFEFEF; padding:4px 0px 4px 0px; border-top:1px solid #000000; border-bottom:1px solid #000000;}
    p{ font-family:"Arial", Times, serif; font-size:12px; margin:0; padding:0;}
	legend{font-family:"Arial", Times, serif; font-size:13px; margin:0; padding:0; font-weight:600;}
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
                        { name: "Date", type: "string" },
                        { name: "Order No", type: "string" },
                        { name: "Periode", type: "string" },
						{ name: "Department", type: "string" },
						{ name: "Faculty", type: "string" },
						{ name: "Student", type: "string" },
						{ name: "City", type: "string" },
						{ name: "District", type: "string" },
						{ name: "Village", type: "string" },
						{ name: "Zip", type: "string" },
						{ name: "P2-Date", type: "string" },
						{ name: "P-Status", type: "string" },
						{ name: "Math", type: "string" },
						{ name: "Indonesia", type: "string" },
						{ name: "Physics", type: "string" },
						{ name: "English", type: "string" },
						{ name: "Chemical", type: "string" },
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
				  { text: 'Date', dataField: 'Date', width: 85 },
				  { text: 'Order No', dataField: 'Order No', width: 90 },
				  { text: 'Periode', dataField: 'Periode', width: 100 },
				  { text: 'Department', dataField: 'Department', width: 100 },
				  { text: 'Faculty', dataField: 'Faculty', width: 75 },
				  { text: 'Student', dataField: 'Student', width: 150 },
				  { text: 'City', dataField: 'City', width: 150 },
				  { text: 'District', dataField: 'District', width: 150 },
				  { text: 'Village', dataField: 'Village', width: 150 },
				  { text: 'Zip', dataField: 'Zip', width: 150 },
				  { text: 'P2-Date', dataField: 'P2-Date', width: 100 },
				  { text: 'P-Status', dataField: 'P-Status', width: 90 },
				  { text: 'Math', dataField: 'Math', width: 80 },
				  { text: 'Indonesia', dataField: 'Indonesia', width: 100 },
				  { text: 'Physics', dataField: 'Physics', width: 80 },
				  { text: 'English', dataField: 'English', width: 80 },
				  { text: 'Chemical', dataField: 'Chemical', width: 100 },
				  { text: 'Log', dataField: 'Log', width: 70 },
                ]
            });
			
			$('#jqxgrid').jqxGrid({ pagesizeoptions: ['50', '100', '250', '500', '1000', '2000', '3000']}); 
			
			$("#bexport").click(function() {
				
				var type = $("#crtype").val();	
				if (type == 0){ $("#jqxgrid").jqxGrid('exportdata', 'html', 'Registration'); }
				else if (type == 1){ $("#jqxgrid").jqxGrid('exportdata', 'xls', 'Registration'); }
				else if (type == 2){ $("#jqxgrid").jqxGrid('exportdata', 'pdf', 'Registration'); }
				else if (type == 3){ $("#jqxgrid").jqxGrid('exportdata', 'csv', 'Registration'); }
			});
			
			$("#table").hide();
			
        });
    </script>

</head>

<body>

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0">
            <tr> <td> Department </td> <td> : </td> <td> <?php echo $dept; ?> </td> </tr>
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo $rundate; ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Registration - Report </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:1px dotted #000000; ">
	
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
    
		<table id="table" width="100%">
		   <thead>
           <tr>
 	       <th> No </th> <th> Date </th> <th> Order No </th> <th> Periode </th> <th> Department </th> <th> Faculty </th>  <th> Student </th> 
           <th> City </th> <th> District </th> <th> Village </th> <th> Zip </th>
           <th> P2-Date </th> <th> P-Status </th> <th> Math </th> <th> Indonesia </th> <th> Physics </th> <th> English </th> <th> Chemical </th> <th> Log </th>
		   </tr>
           </thead>
		    
          <tbody>  
		  <?php 	
		  
		  	  function dept($val){ $res = new Dept_lib(); return $res->get_name($val);  }
			  
			  function student($val){ $st = new Student_lib(); return $st->get_name($val); }
			  
			  function faculty($student)
			  {
				  $st = new Student_lib();
				  $faculty = new Faculty_lib();
				  $student = $faculty->get_name($st->get_faculty($student)); 
				  return $student;
			  }
			  
			  function zip($sid,$type)
			  {
				  $st = new Student();
				  $st->where('students_id',$sid)->get();
				  $ct = new City_lib();
				  if ($type == 'zip'){ return $st->zipcode; }else { return $ct->get_from_zip($st->zipcode,$type); }
			  }
		  
		      $i=1; 
			  if ($result)
			  {
				foreach ($result as $res)
				{	
				   echo " 
				   <tr> 
				       <td>".$i."</td> 
					   <td>".tglin($res->dates)."</td> 
					   <td> REG-0".$res->no."</td> 
					   <td>".$res->financial_year."</td> 
   					   <td>".dept($res->dept_id)."</td> 
   					   <td>".faculty($res->student_id)."</td>
					   <td>".student($res->student_id)."</td>
   					   <td>".zip($res->student_id,'name')."</td>
					   <td>".zip($res->student_id,'district')."</td>
					   <td>".zip($res->student_id,'village')."</td>
					   <td>".zip($res->student_id,'zip')."</td>
					   <td>".$res->p2date."</td>
					   <td>".$res->payment_status."</td>
					   <td>".$res->math."</td>
   					   <td>".$res->indonesia."</td>
					   <td>".$res->physics."</td>
					   <td>".$res->english."</td>
					   <td>".$res->chemical."</td>
   					   <td>".$res->log."</td>
				   </tr>";
				   $i++;
				}
			  }  
		  ?>
		  </tbody> 
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

</div>

</body>
</html>
