<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title> <?php echo isset($title) ? $title : ''; ?>  </title>
<style media="all">
	table{ font-family:Arial, Helvetica, sans-serif; font-size:11px;}
	table th{ font-family:arial; font-size:10pt;}
	h4{ font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:600;}
	.clear{clear:both;}
	table th{ background-color:#EFEFEF; padding:4px 0px 4px 0px; border-top:1px solid #000000; border-bottom:1px solid #000000;}
    p{ font-family:"Arial", Times, serif; font-size:12px; margin:0; padding:0;}
	legend{font-family:"Arial", Times, serif; font-size:13px; margin:0; padding:0; font-weight:600;}
	.tablesum{ font-size:13px;}
	.strongs{ font-weight:normal; font-size:12px; border-top:1px dotted #000000; text-transform:uppercase; }
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
    
    <!-- pivot -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url().'js/pivot/' ?>pivot.css">
    <script type="text/javascript" src="<?php echo base_url().'js/pivot/' ?>jquery-ui-1.9.2.custom.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/pivot/' ?>pivot.js"></script>  
    <!-- pivot -->
    
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
						{ name: "Student", type: "string" },
						{ name: "Department", type: "string" },
						{ name: "Faculty", type: "string" },
						{ name: "Grade", type: "string" },
						{ name: "Jul", type: "string" },
						{ name: "Aug", type: "string" },
						{ name: "Sep", type: "string" },
						{ name: "Oct", type: "string" },
						{ name: "Nov", type: "string" },
						{ name: "Dec", type: "string" },
						{ name: "Jan", type: "string" },
						{ name: "Feb", type: "string" },
						{ name: "Mar", type: "string" },
						{ name: "Apr", type: "string" },
						{ name: "May", type: "string" },
						{ name: "Jun", type: "string" },
						{ name: "Credit", type: "number" }
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
				  { text: 'Student', dataField: 'Student', width : 200 },
  				  { text: 'Department', dataField: 'Department', width : 110 },
				  { text: 'Faculty', dataField: 'Faculty', width : 90 },
				  { text: 'Grade', dataField: 'Grade', width : 90 },
				  { text: 'Jul', dataField: 'Jul', width : 60, cellsalign: 'center', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Aug', dataField: 'Aug', width : 60, cellsalign: 'center', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Sep', dataField: 'Sep', width : 60, cellsalign: 'center', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Oct', dataField: 'Oct', width : 60, cellsalign: 'center', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Nov', dataField: 'Nov', width : 60, cellsalign: 'center', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Dec', dataField: 'Dec', width : 60, cellsalign: 'center', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Jan', dataField: 'Jan', width : 60, cellsalign: 'center', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Feb', dataField: 'Feb', width : 60, cellsalign: 'center', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Mar', dataField: 'Mar', width : 60, cellsalign: 'center', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Apr', dataField: 'Apr', width : 60, cellsalign: 'center', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'May', dataField: 'May', width : 60, cellsalign: 'center', cellsformat: 'number', aggregates: ['sum'] },
				  { text: 'Jun', dataField: 'Jun', width : 60, cellsalign: 'center', cellsformat: 'number', aggregates: ['sum'] },
 				  { text: 'Credit', datafield: 'Credit', width: 80, cellsalign: 'center', cellsformat: 'number', aggregates: ['sum'] }
				  
                ]
            });
			
			$("#bexport").click(function() {
				
				var type = $("#crtype").val();	
				if (type == 0){ $("#jqxgrid").jqxGrid('exportdata', 'html', 'Payment_Report-Summary'); }
				else if (type == 1){ $("#jqxgrid").jqxGrid('exportdata', 'xls', 'Payment_Report-Summary'); }
				else if (type == 2){ $("#jqxgrid").jqxGrid('exportdata', 'pdf', 'Payment_Report-Summary'); }
				else if (type == 3){ $("#jqxgrid").jqxGrid('exportdata', 'csv', 'Payment_Report-Summary'); }
			});
			
			$('#jqxgrid').jqxGrid({ pagesizeoptions: ['50', '100', '250', '500', '1000', '2000', '3000']}); 
			
			// pivot
			
			var input = $("#tablesum")
			$("#output").pivotUI(input);
			
			// pivot
			
			$("#bsummary").click(function() {
			  $("#jqxgrid").show(); $("#output").hide(); 
			});
			
			$("#bpivot").click(function() {
			  $("#jqxgrid").hide(); $("#output").show(); 
			});
			
			$("#table, #tablesum").hide();
			$("#output").hide();
			
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
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Fee Payment Recapitulation - Summary </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:0px dotted #000000; ">
    
        <div id='jqxWidget'>
        <div style='margin-top: 10px;' id="jqxgrid"> </div>
        <div style='margin-top: 10px;' id="output"> </div>
        
        <table style="float:right; margin:5px;">
        <tr>
        <td> <input type="button" id="bsummary" value="Summary"> : <input type="button" id="bpivot" value="Pivotable"> &nbsp; </td>
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
 	       <th> No </th> <th> Student </th> <th> Department </th> <th> Faculty </th> <th> Grade </th> <th> Jul </th> <th> Aug </th> <th> Sep </th> 
           <th> Oct </th> <th> Nov </th> <th> Dec </th> <th> Jan </th> <th> Feb </th> <th> Mar </th> <th> Apr </th> <th> May </th> <th> Jun </th>
           <th> Credit </th>
		   </tr>
           </thead>
		    
          <tbody>  
		  <?php 
		  
		  
		  	  function status($val)
			  { if ($val == 0){ $val = 'debt'; } else { $val = 'settled'; } return $val; }	
		  
			  function cekdate($date=null,$period=null)
			  { 
				$res = null;
				if ($date)
				{
					$m = date('n', strtotime($date)); 
					if ($m <= $period){ $res = $date; }else{ $res = '-'; }
				}
				else { $res = '-'; }
				return $res;
			  }
			  
			  function get_miss($res,$period,$monthperiod)
			  {
				  $ps = new Period();
				  $py = new Payment_status_lib();
				  $ps = $py->months_periode($ps->get()->month);
				  
				  if ($monthperiod <= $ps)
				  {
					  $result = 0;
					  for($i=1; $i<=$monthperiod; $i++)
					  {
						 $pi = 'p'.$i;
						 if (cekdate($res->$pi,$period) == '-'){ $result = $result + 0; }
						 else { $result = $result + 1; }
					  }
					  return intval($monthperiod-$result);
				  }
				  else { return 0; }
			  }
			  
			  function get_miss_end($sid,$year)
			  {
				 $py = new Payment_status_lib(); 
				 return ($py->get_miss_payment($sid,$year));
			  }
		  
		      $i=1; 
			  if ($payments)
			  { 
  		  	    $tot1 = 0; $tot2 = 0; $tot3 = 0; $tot4 = 0; $tot5 = 0; $tot6 = 0; $tot7 = 0; $tot8 = 0; $tot9 = 0;
			    $tot10 = 0; $tot11 = 0; $tot12 = 0; $tot13 = 0;
				foreach ($payments as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
					   <td class=\"strongs\">".$res->name.' - '.$res->nisn."</td>
					   <td class=\"strongs\">".$res->dept."</td>
					   <td class=\"strongs\">".$res->faculty."</td>
					   <td class=\"strongs\">".$res->grade."</td> 
					   <td class=\"strongs\" align=\"center\">".get_miss($res,7,1)."</td> 
					   <td class=\"strongs\" align=\"center\">".get_miss($res,8,2)."</td>
					   <td class=\"strongs\" align=\"center\">".get_miss($res,9,3)."</td> 
					   <td class=\"strongs\" align=\"center\">".get_miss($res,10,4)."</td> 
					   <td class=\"strongs\" align=\"center\">".get_miss($res,11,5)."</td> 
					   <td class=\"strongs\" align=\"center\">".get_miss($res,12,6)."</td> 
					   <td class=\"strongs\" align=\"center\">".get_miss($res,1,7)."</td> 
					   <td class=\"strongs\" align=\"center\">".get_miss($res,2,8)."</td> 
					   <td class=\"strongs\" align=\"center\">".get_miss($res,3,9)."</td> 
					   <td class=\"strongs\" align=\"center\">".get_miss($res,4,10)."</td> 
					   <td class=\"strongs\" align=\"center\">".get_miss($res,5,11)."</td> 
					   <td class=\"strongs\" align=\"center\">".get_miss($res,6,12)."</td> 
					   <td class=\"strongs\" align=\"center\">".get_miss_end($res->student_id,$year)."</td> 
				   </tr>";
				   
				   $tot1 = $tot1 + get_miss($res,7,1);
				   $tot2 = $tot2 + get_miss($res,8,2);
				   $tot3 = $tot3 + get_miss($res,9,3);
				   $tot4 = $tot4 + get_miss($res,10,4);
				   $tot5 = $tot5 + get_miss($res,11,5);
				   $tot6 = $tot6 + get_miss($res,12,6);
				   $tot7 = $tot7 + get_miss($res,1,7);
				   $tot8 = $tot8 + get_miss($res,2,8);
				   $tot9 = $tot9 + get_miss($res,3,9);
				   $tot10 = $tot10 + get_miss($res,4,10);
				   $tot11 = $tot11 + get_miss($res,5,11);
				   $tot12 = $tot12 + get_miss($res,6,12);
				   $tot13 = $tot13 + get_miss_end($res->student_id,$year);
				   
				   $i++;
				}
			  }  
			  
		  ?>
          
         <!-- <tr>
          <td colspan="5" align="right"> <b> Summary : </b> </td>
          <td class="strongs" align="center"> <?php //echo $tot1; ?> </td>
          <td class="strongs" align="center"> <?php //echo $tot2; ?> </td>
          <td class="strongs" align="center"> <?php //echo $tot3; ?> </td>
          <td class="strongs" align="center"> <?php //echo $tot4; ?> </td>
          <td class="strongs" align="center"> <?php //echo $tot5; ?> </td>
          <td class="strongs" align="center"> <?php //echo $tot6; ?> </td>
          <td class="strongs" align="center"> <?php //echo $tot7; ?> </td>
          <td class="strongs" align="center"> <?php //echo $tot8; ?> </td>
          <td class="strongs" align="center"> <?php //echo $tot9; ?> </td>
          <td class="strongs" align="center"> <?php //echo $tot10; ?> </td>
          <td class="strongs" align="center"> <?php //echo $tot11; ?> </td>
          <td class="strongs" align="center"> <?php //echo $tot12; ?> </td>
          <td class="strongs" align="center"> <?php //echo $tot13; ?> </td>
          </tr>-->
		  
        </tbody>   
		</table> 
        
        <table id="tablesum">
        	<thead>
            <tr> <th> Department </th> <th> Jul </th> <th> Aug </th> <th> Sep </th> 
                 <th> Oct </th> <th> Nov </th> <th> Dec </th> <th> Jan </th> <th> Feb </th> <th> Mar </th> <th> Apr </th> <th> May </th> 
                 <th> Jun </th> 
            </tr>
            </thead>
            
            <tbody>

			<?php
			
			function tunggakan($dept,$grade,$monthperiod,$year,$mpnow)
			{ 
				$payment = new Payment_status_lib();
				if ($monthperiod <= $mpnow){ return $payment->get_miss_recapitulation($dept,$grade,$monthperiod,$year); }
				else { return 0; }
			}
            
			foreach ($dept_class->get() as $res)
			{	
			   echo " 
			   <tr> 
				   <td class=\"strongs\">".$res->name."</td> 
				   <td class=\"strongs\">".tunggakan($res->dept_id,null,1,$year,$monthperiod)."</td> 
				   <td class=\"strongs\">".tunggakan($res->dept_id,null,2,$year,$monthperiod)."</td> 
				   <td class=\"strongs\">".tunggakan($res->dept_id,null,3,$year,$monthperiod)."</td> 
				   <td class=\"strongs\">".tunggakan($res->dept_id,null,4,$year,$monthperiod)."</td> 
				   <td class=\"strongs\">".tunggakan($res->dept_id,null,5,$year,$monthperiod)."</td> 
				   <td class=\"strongs\">".tunggakan($res->dept_id,null,6,$year,$monthperiod)."</td> 
				   <td class=\"strongs\">".tunggakan($res->dept_id,null,7,$year,$monthperiod)."</td> 
				   <td class=\"strongs\">".tunggakan($res->dept_id,null,8,$year,$monthperiod)."</td> 
				   <td class=\"strongs\">".tunggakan($res->dept_id,null,9,$year,$monthperiod)."</td> 
				   <td class=\"strongs\">".tunggakan($res->dept_id,null,10,$year,$monthperiod)."</td> 
				   <td class=\"strongs\">".tunggakan($res->dept_id,null,11,$year,$monthperiod)."</td> 
				   <td class=\"strongs\">".tunggakan($res->dept_id,null,12,$year,$monthperiod)."</td> 
			   </tr>";
			   
			   $i++;
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
