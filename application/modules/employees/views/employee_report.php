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
						{ name: "NIP", type: "string" },
						{ name: "Att Code", type: "string" },
						{ name: "Name", type: "string" },
						{ name: "Type", type: "string" },
						{ name: "Time Work", type: "string" },
						{ name: "Division", type: "string" },
						{ name: "Role", type: "string" },
						{ name: "Department", type: "string" },
						{ name: "Gender", type: "string" },
						{ name: "Religion", type: "string" },
						{ name: "Date Of Birth", type: "string" },
						{ name: "Marital Status", type: "string" },
						{ name: "Phone", type: "string" },
						{ name: "Mobile", type: "string" },
						{ name: "Email", type: "string" },
						{ name: "Acc-No", type: "string" },
						{ name: "Status", type: "string" }
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
				{ text: 'NIP', dataField: 'NIP', width : 70 },
  				{ text: 'Att Code', dataField: 'Att Code', width : 100 },
				{ text: 'Name', dataField: 'Name', width: 200 },
				{ text: 'Type', dataField: 'Type', width:80 },
				{ text: 'Time Work', dataField: 'Time Work', width:100, cellsalign: 'center' },
				{ text: 'Division', dataField: 'Division', width:150 },
				{ text: 'Role', datafield: 'Role', width:100 },
				{ text: 'Department', datafield: 'Department', width:120 },
				{ text: 'Gender', datafield: 'Gender', width:100 },
				{ text: 'Religion', datafield: 'Religion', width:100 },
				{ text: 'Date Of Birth', datafield: 'Date Of Birth', width:150 },
				{ text: 'Marital Status', datafield: 'Marital Status', width:120 },
				{ text: 'Phone', datafield: 'Phone', width:120 },
			    { text: 'Mobile', datafield: 'Mobile', width:120 },
				{ text: 'Email', datafield: 'Email', width:120 },
				{ text: 'Acc-No', datafield: 'Acc-No', width:150 },
				{ text: 'Status', datafield: 'Status', width:100, cellsalign: 'center' }

                ]
            });
			
		$('#jqxgrid').jqxGrid({ pagesizeoptions: ['10', '20', '30', '40', '50', '100', '200', '300']}); 
		
		$("#bexport").click(function() {
				
			var type = $("#crtype").val();	
			if (type == 0){ $("#jqxgrid").jqxGrid('exportdata', 'html', 'Employee'); }
			else if (type == 1){ $("#jqxgrid").jqxGrid('exportdata', 'xls', 'Employee'); }
			else if (type == 2){ $("#jqxgrid").jqxGrid('exportdata', 'pdf', 'Employee'); }
			else if (type == 3){ $("#jqxgrid").jqxGrid('exportdata', 'csv', 'Employee'); }
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
    
    <p style="text-align:center; font-size:14pt; font-weight:bold;"> Employee Report </p>
	
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
	
		<table id="table" border="0">
		   
           <thead>
           <tr>
  	       <th> No </th> <th> NIP </th> <th> Att Code </th> <th> Name </th> <th> Type </th> <th> Time Work </th> <th> Division </th> <th> Role </th> 
           <th> Department </th> <th> Gender </th> 
           <th> Religion </th> <th> Date Of Birth </th> <th> Marital Status </th> <th> Phone </th> <th> Mobile </th> 
           <th> Email </th> <th> Acc-No </th> <th> Status </th>
		   </tr>
           </thead>
		   
          <tbody>  
		  <?php 
		  
		  	  function dept($val)
			  {
				  $dept = new Dept_lib();
				  if ($val == 0) { $res = 'General'; } else { $res = $dept->get_name($val); } return $res;
			  }	
			  
			  function division($val)
			  {
				  $division = new Division_lib();
				  if ($val == 0) { $res = 'Non Division'; } else { $res = $division->get_name($val); } return $res;
			  }	
			  
			  function status($val){ if ($val == 0){ return 'Non Active'; } else { return 'Active'; } }
			  
			  function genre($val){ if($val == 'm'){return 'Male';}else{ return 'Female'; }}
			  
			  function marital($val)
			  { if($val == 'yes'){ $res = 'Married'; }elseif($val == 'no'){ $res = 'Not Married';}else{ $res = 'No Status';} return $res; }
		  		  
		      $i=1; 
			  if ($results)
			  {
				foreach ($results as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".$res->nip."</td>
					   <td class=\"strongs\">".$res->attcode."</td>
					   <td class=\"strongs\">".$res->first_title.' '.$res->name.' '.$res->end_title."</td>
					   <td class=\"strongs\" align=\"center\">".$res->type."</td>
					   <td class=\"strongs\" align=\"center\">".$res->work_time."</td>
					   <td class=\"strongs\">".division($res->division_id)."</td>
					   <td class=\"strongs\">".ucfirst($res->role)."</td>
					   <td class=\"strongs\">".dept($res->dept_id)."</td>
					   <td class=\"strongs\">".genre($res->genre)."</td>
					   <td class=\"strongs\">".$res->religion."</td>
					   <td class=\"strongs\">".$res->born_place.' , '.tglincomplete($res->born_date)."</td>
					   <td class=\"strongs\">".marital($res->status)."</td>
					   <td class=\"strongs\">".$res->phone."</td>
					   <td class=\"strongs\">".$res->mobile."</td>
					   <td class=\"strongs\">".$res->email."</td>
					   <td class=\"strongs\">".$res->acc_no."</td>
					   <td class=\"strongs\">".status($res->active)."</td>
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
