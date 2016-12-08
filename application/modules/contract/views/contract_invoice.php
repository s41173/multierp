<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title> Order Contract - CO-00<?php echo isset($pono) ? $pono : ''; ?></title>

<style type="text/css" media="all">

	body{ font-size:0.75em; font-family:Arial, Helvetica, sans-serif; margin:0; padding:0;}
	#container{ width:21cm; height:11.6cm; border:0pt solid #000;}
	.clear{ clear:both;}
	#tablebox{ width:20cm; border:0pt solid red; float:left; margin:0cm 0 0 0.4cm;}
		
	#logobox{ width:5.5cm; height:1cm; border:0pt solid blue; margin:0.8cm 0 0 0.5cm; float:left;}
	#venbox{ border:0pt solid green; margin:0.0cm 0cm 0.8cm 0.5cm; float:left; width:9.5cm;}
	#venbox2,#venbox3{ border:0pt solid green; margin:0.0cm 0.5cm 0.6cm 0.5cm; float:right; width:8.5cm;}
	#title{ text-align:center; font-size:17pt;}
	h4{ font-size:14pt; margin:0;}
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
						{ name: "Sales", type: "string" },
						{ name: "Date", type: "string" },
						{ name: "Notes", type: "string" },
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
                  { text: 'No', dataField: 'No', width: 70 },
				  { text: 'Sales', dataField: 'Sales', width : 90, cellsalign: 'center' },
				  { text: 'Date', dataField: 'Date', width : 110, cellsalign: 'center' },
				  { text: 'Notes', dataField: 'Notes', cellsalign: 'left' },
 				  { text: 'Amount', datafield: 'Amount', width: 200, cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] }
                ]
            });
			
			$("#table").hide();
			
			// table phase
			
			var rows = $("#table1 tbody tr");
                // select columns.
                var columns = $("#table1 thead th");
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
						{ name: "Phase", type: "string" },
						{ name: "Date", type: "string" },
						{ name: "Amount", type: "number" },
						{ name: "Status", type: "string" }
                    ]
                };
			
            var dataAdapter = new $.jqx.dataAdapter(source);
            $("#jqxgrid1").jqxGrid(
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
                  { text: 'No', dataField: 'No', width: 70 },
				  { text: 'Phase', dataField: 'Phase', width : 90, cellsalign: 'center' },
				  { text: 'Date', dataField: 'Date', width : 110, cellsalign: 'center' },
   				  { text: 'Amount', datafield: 'Amount', cellsalign: 'right', cellsformat: 'number', aggregates: ['sum'] },
 				  { text: 'Status', datafield: 'Status', width: 150, cellsalign: 'center' }
                ]
            });
			
			$("#table1").hide();
			
		// end jquery	
        });
    </script>


</head>

<body bgcolor="#FFFFFF"; onload="">

<div id="container">
	
    <center>
	   <div style="border:0px solid green; width:500px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> </h4>
           <p style="margin:5px; padding:0;"> <?php echo $address; ?> <br> Telp. <?php echo $phone1.' - '.$phone2; ?> <br>
               Website : <?php echo $website; ?> &nbsp; &nbsp; Email : <?php echo $email; ?> </p>
	   </div>
	</center> <hr>
	
    <p style="padding:0; font-weight:bold; font-size:1.3em; text-align:center;"> SALES ORDER CONTRACT </p>
    
    <div id="venbox">
	<table width="100%" style="font-size:1em; margin:0; text-align:left; font-weight:bold;">
	  <tr> <td> No </td> <td>:</td> <td> CO-00<?php echo isset($pono) ? $pono : ''; ?> </td> </tr>
      <tr> <td> Doc-No </td> <td>:</td> <td> <?php echo isset($docno) ? $docno : ''; ?> </td> </tr>
      <tr> <td> Deal Date </td> <td>:</td> <td> <?php echo $dealdate; ?> </td> </tr>
      <tr> <td> Order Date </td> <td>:</td> <td> <?php echo $podate; ?> / <?php echo $due; ?> </td> </tr>
	  <tr> <td> Currency </td> <td>:</td> <td> <?php echo isset($currency) ? $currency : ''; ?> </td> </tr>
      <tr> <td> Type </td> <td>:</td> <td> <?php echo isset($type) ? $type : ''; ?> </td> </tr>
	  <tr> <td> Customer </td> <td>:</td> <td> <?php echo isset($customer) ? $customer : ''; ?> </td> </tr>
      <tr> <td> Notes </td> <td>:</td> <td> <?php echo isset($notes) ? $notes : ''; ?> </td> </tr>
      <tr> <td> User </td> <td>:</td> <td> <?php echo isset($user) ? $user : ''; ?> </td> </tr>
	</table>
	</div>
    
    <div id="venbox2">
	<table width="100%" style="font-size:1em; margin:0; text-align:left; font-weight:bold;">
	  <tr> <td> Amount </td> <td>: &nbsp;</td> <td align="right"> <?php echo number_format($amount); ?>,- </td> </tr>
      <tr> <td> Tax </td> <td>: &nbsp;</td> <td align="right"> <?php echo number_format($tax); ?>,- </td> </tr>
      <tr> <td> Balance </td> <td>: &nbsp;</td> <td align="right"> <?php echo number_format(intval($amount+$tax)); ?>,- </td> </tr>
	  <tr> <td> Rest Balance </td> <td>: &nbsp;</td> <td align="right"> <?php echo number_format($balance); ?>,- </td> </tr>
      <tr> <td> Approved </td> <td>:</td> <td> <?php echo isset($stts) ? $stts : ''; ?> </td> </tr>
	</table> <br /> 
    
    <fieldset class="field"> <legend> Contract - Void </legend>
    <table width="100%" style="font-size:1em; margin:0; text-align:left; font-weight:bold;">
	  <tr> <td> Void - Date </td> <td>: &nbsp;</td> <td align="right"> <?php echo tglin($void_date); ?> </td> </tr>
      <tr> <td> Void - Desc </td> <td>: &nbsp;</td> <td align="right"> <?php echo $void_desc; ?> </td> </tr>
	</table>
    </fieldset>
	</div>
    
	<div id="tablebox">
    
    <?php
	
	function cek($date,$sid,$month,$financial,$type)
	{
		if ($date != '-')
		{ 
		   $cost = new Regcost_lib();
		   $tuition = new Tuition_lib();
		   $user = new Admin_lib();	
		   
		   $val = $tuition->get_by_student($sid,$date,$month,$financial);
		   if ($val)
		   {
			 if ($type == 'fee'){ return $cost->get_name($val->fee_type); }
		     elseif ($type == 'amount') { return intval($val->amount); }
			 elseif ($type == 'user') { return $user->get_username($val->user); }
		   }
		   else { return '-'; }
		}
		else { return '-'; }
	}
	
	function tgl($val)
	{if ($val != '-'){ return tglin($val); }else { return '-'; }}
	
	    
	?>
    
    <div id='jqxWidget'>
    <fieldset class="field"> <legend> Phase List </legend>
    <div style='margin-top: 10px;' id="jqxgrid1"> </div> 
    
		
		<table id="table1">
        <thead>
	    <tr> <th> No </th> <th> Phase </th> <th> Date </th> <th> Amount </th> <th> Status </th> </tr>	
        </thead>

        <tbody>
        
        <?php
        
		  function status($val){ if ($val == 0){ return 'PENDING'; }else { return 'COLLECTABLE'; } }
			
          if ($items1)
		  {
			$i=1;  
			foreach ($items1 as $res)
	        {
			     echo "
				 <tr> 
					<td> ".$i." </td>
					<td class=\"left\">".$res->no." </td> 
					<td class=\"left\"> ".tglin($res->dates)." </td> 
					<td class=\"left\"> ".$res->amount." </td> 
					<td class=\"right\"> ".status($res->status)." </td>   
				 </tr>
				
				"; $i++;
		    }
		  }
        
        ?>
        
        </tbody>

		</table>
	</div>  
    </fieldset> <br />
    
    <div class="clear"></div>
    
    
    <fieldset class="field"> <legend> Collectable List </legend>
    <div style='margin-top: 10px;' id="jqxgrid"> </div> 
    
		
		<table id="table">
        <thead>
	    <tr> <th> No </th> <th> Sales </th> <th> Date </th> <th> Notes </th> <th> Amount </th> </tr>	
        </thead>

        <tbody>
        
        <?php
        
          if ($items)
		  {
			$i=1;  
			foreach ($items as $res)
	        {
			     echo "
				 <tr> 
					<td> ".$i." </td>
					<td class=\"left\"> SO-00".$res->no." </td> 
					<td class=\"left\"> ".tglin($res->dates)." </td> 
					<td class=\"left\"> ".$res->notes." </td> 
					<td class=\"right\"> ".$res->total." </td>   
				 </tr>
				
				"; $i++;
		    }
		  }
        
        ?>
        
        </tbody>

		</table>
	</div>  
    </fieldset>
    
    <div class="clear"></div>
	
	
</div>

</body>
</html>
