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

    <link rel="stylesheet" type="text/css" href="<?php echo base_url().'js/pivot/' ?>pivot.css">
	<script type="text/javascript" src="<?php echo base_url().'js/pivot/' ?>jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/pivot/' ?>jquery-ui-1.9.2.custom.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/pivot/' ?>jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/pivot/' ?>pivot.js"></script> 
    
    <script type="text/javascript">
        $(document).ready(function () {
          	
			var input = $("#input")
			$("#output").pivotUI(input);
			$("#input").hide();
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
	   <div style="border:0px solid green; width:260px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Scholarship Transaction - Pivot Table </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:0px dotted #000000; ">
		
        <div id='jqxWidget'>
        <div style='margin-top: 30px;' id="output"> </div>
        </div>
       
    
		<table id="input" border="0" width="100%" style="visibility: hidden;">
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
