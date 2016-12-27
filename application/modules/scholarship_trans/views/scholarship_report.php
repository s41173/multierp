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
	.strongs{ font-weight:normal; font-size:12px; border-top:1px dotted #000000; text-align:center; }
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
						{ name: "Financial Year", type: "string" },
						{ name: "Date", type: "string" },
						{ name: "Transcode", type: "string" },
						{ name: "Scholarship", type: "string" },
						{ name: "Department", type: "string" },
						{ name: "Student", type: "string" },
						{ name: "Request (M)", type: "number" },
						{ name: "Start", type: "string" },
						{ name: "Until", type: "string" },
						{ name: "Period (M)", type: "number" },
						{ name: "Status", type: "string" },
						{ name: "Amount", type: "number" }
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
				  { text: 'Financial Year', dataField: 'Financial Year', width: 120 },
				  { text: 'Date', dataField: 'Date', width : 100 },
  				  { text: 'Transcode', dataField: 'Transcode', width : 100 },
				  { text: 'Scholarship', dataField: 'Scholarship', width: 200 },
				  { text: 'Department', dataField: 'Department', width:110 },
				  { text: 'Student', dataField: 'Student', width:150 },
				  { text: 'Request (M)', dataField: 'Request (M)', width:110 },
				  { text: 'Start', datafield: 'Start', width: 75, cellsalign: 'center' },
				  { text: 'Until', datafield: 'Until', width: 75, cellsalign: 'center' },
				  { text: 'Period (M)', datafield: 'Period (M)', width: 90, cellsalign: 'center'},
				  { text: 'Status', datafield: 'Status', width: 90, cellsalign: 'center'},
				  { text: 'Amount', datafield: 'Amount', width: 150, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] }
				  
                ]
            });
			
			$('#jqxgrid').jqxGrid({ pagesizeoptions: ['10', '20', '30', '40', '50', '100', '200', '300']}); 
			
			$("#bexport").click(function() {
				
				var type = $("#crtype").val();	
				if (type == 0){ $("#jqxgrid").jqxGrid('exportdata', 'html', 'Scholarship-Transaction'); }
				else if (type == 1){ $("#jqxgrid").jqxGrid('exportdata', 'xls', 'Scholarship-Transaction'); }
				else if (type == 2){ $("#jqxgrid").jqxGrid('exportdata', 'pdf', 'Scholarship-Transaction'); }
				else if (type == 3){ $("#jqxgrid").jqxGrid('exportdata', 'csv', 'Scholarship-Transaction'); }
			})	
			
        });
    </script>

</head>

<body>

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0">
			<tr> <td> Period </td> <td> : </td> <td> <?php echo tglin($start); ?> to <?php echo tglin($end); ?> </td> </tr>
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo $rundate; ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Scholarship Transaction - Report </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:0px dotted #000000; ">
		
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
    
		<table id="table" border="0" width="100%" style="visibility: hidden;">
        <thead>
		<tr>
 	    <th> No </th> <th> Financial Year </th> <th> Date </th> <th> Transcode </th> <th> Scholarship </th> <th> Department </th> <th> Student </th>        <th> Request (M) </th> <th> Start </th> <th> Until </th> <th> Period (M) </th> <th> Notes </th> <th> Status </th> <th> Amount </th>
		</tr>
        </thead>
        
		<tbody>   
		  <?php 
		  
		  	  function scholarship($val){ $res = new Scholarship_trans_lib();  $res = $res->get_name($val); if ($res){ return $res; }}
			  function dept($val){ $res = new Dept_lib();  $res = $res->get_name($val); if ($res){ return $res; }}
			  function student($val){ $res = new Student_lib();  $res = $res->get_name($val); if ($res){ return $res; }}
			  
			  function start($val,$financial)
			  { $res = new Payment_status_lib();  
			    $month = $res->months_name($val); 
				$year = $res->year_name($val,$financial);
				return $month.'-'.$year;
			  }
			  
			  function amount($period,$sid)
			  {
				  $scholarship = new Scholarship_trans_lib();
				  $res = intval($period*$scholarship->get_fee_type($sid));
				  return $res;
			  }
			  
			  function stts($val){ if ($val==1){ return 'Active'; }else{ return 'Non Active'; }}
		  
		      $i=1; 
			  $val = 0;
			  if ($result)
			  {
				foreach ($result as $ap)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".$ap->financial_year."</td>
					   <td class=\"strongs\">".tglin($ap->dates)."</td> 
					   <td class=\"strongs\"> SCT-".$ap->id."</td>
					   <td class=\"strongs\">".scholarship($ap->scholarship_id)."</td>
					   <td class=\"strongs\">".dept($ap->dept_id)."</td> 
					   <td class=\"strongs\">".student($ap->student)."</td> 
					   <td class=\"strongs\">".$ap->request."</td>
					   <td class=\"strongs\">".start($ap->start,$ap->financial_year)."</td>
					   <td class=\"strongs\">".start($ap->until,$ap->financial_year)."</td> 
					   <td class=\"strongs\">".$ap->period."</td>
   					   <td class=\"strongs\">".$ap->desc."</td>
					   <td class=\"strongs\">".stts($ap->status)."</td>
					   <td class=\"strongs\" align=\"right\">".amount($ap->period,$ap->scholarship_id)."</td> 
				   </tr>";
				   $val = $val + amount($ap->period,$ap->scholarship_id);
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
