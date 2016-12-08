<html>
<head>
<title> Employee Salary Receipt : <?php echo isset($employee) ? $employee : ''; ?> </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css" media="all">
	
	
	body,td,th {font-family: Courier New, Courier, monospace;}
    body{ margin:0px auto 0px;	padding:3px; font-size:12px; color:#333; width:95%; background-color:#fff;}
    .table-list {clear: both; text-align: left; border-collapse: collapse;	margin: 0px 0px 10px 0px; background:#fff;} 
    .table-list td {color: #333; font-size:13px; border-color: #fff; border-collapse: collapse;	vertical-align: center;	padding: 3px 5px;	      border-bottom:1px #CCCCCC solid;}
	
</style>

</head>
<body onLoad="window.print()">
<div id="container">
<table class="table-list" width="420" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td colspan="4" align="center"><h2> <?php echo isset($company) ? $company : ''; ?> <br> SALARY RECEIPT </h2></td>
  </tr>
  <tr>
    <td  colspan="4" align="center">
      <table width="100%" border="0" cellpadding="2" cellspacing="1">
        <tr>
          <td width="24%"><strong> Date </strong></td>
          <td width="4%"><strong>:</strong></td>
          <td width="72%"> <?php echo isset($date) ? $date : ''; ?> </td>
        </tr>
        <tr>
          <td width="24%"><strong> Currency </strong></td>
          <td width="4%"><strong>:</strong></td>
          <td width="72%"> <?php echo isset($cur) ? $cur : ''; ?> </td>
        </tr>
        <tr>
          <td><strong> Period </strong></td>
          <td><strong>:</strong></td>
          <td> <?php echo isset($period) ? $period : ''; ?> </td>
        </tr>
        <tr>
          <td><strong> Name </strong></td>
          <td><strong>:</strong></td>
          <td> <?php echo isset($nip) ? $nip : ''; ?> / <?php echo isset($employee) ? $employee : ''; ?> </td>
        </tr>
        <tr>
          <td><strong> Division </strong></td>
          <td><strong>:</strong></td>
          <td> <?php echo isset($division) ? $division : ''; ?> </td>
        </tr>
        <tr>
          <td><strong> Department </strong></td>
          <td><strong>:</strong></td>
          <td> <?php echo isset($dept) ? $dept : ''; ?> </td>
        </tr>
      </table>
  </tr>
  <tr>
    <td width="294" align="right"><strong> Basic Salary   (Rp)+ : </strong></td>
    <td width="125" align="right"> <?php echo isset($basic) ? $basic : '0'; ?>,- </td>
  </tr>
  <tr>
    <td width="294" align="right"><strong> Experience Benefit   (Rp)+ : </strong></td>
    <td width="125" align="right"> <?php echo isset($experience) ? $experience : '0'; ?>,- </td>
  </tr>
  <tr>
    <td align="right"><strong> Consumption (Rp)+ : </strong></td>
    <td align="right"> <?php echo isset($consumption) ? $consumption : '0'; ?>,- </td>
  </tr>
  <tr>
    <td align="right"><strong> Transportation (Rp)+ : </strong></td>
    <td align="right"> <?php echo isset($transportation) ? $transportation : '0'; ?>,- </td>
  </tr>
  <tr>
    <td align="right"><strong> Overtime (Rp)+ : </strong></td>
    <td align="right"> <?php echo isset($overtime) ? $overtime : '0'; ?>,- </td>
  </tr>
  <tr>
    <td align="right"><strong> Bonus  (Rp)+ : </strong></td>
    <td align="right"> <?php echo isset($bonus) ? $bonus : '0'; ?>,- </td>
  </tr>
  
   <tr>
    <td align="left"><strong> Deduction </strong></td>
    <td align="right"></td>
  </tr>
  
  <tr>
    <td align="right"><strong> Late Charges (Rp)- : </strong></td>
    <td align="right"> <?php echo isset($late) ? $late : '0'; ?>,- </td>
  </tr>
  
  <tr>
    <td align="right"><strong> Loan Payment (Rp)- : </strong></td>
    <td align="right"> <?php echo isset($loan) ? $loan : '0'; ?>,- </td>
  </tr>
  
  <tr>
    <td align="right"><strong> Tax (Rp)- : </strong></td>
    <td align="right"> <?php echo isset($tax) ? $tax : '0'; ?>,- </td>
  </tr>
  
  <tr>
    <td align="right"><strong> Insurance (Rp)- : </strong></td>
    <td align="right"> <?php echo isset($insurance) ? $insurance : '0'; ?>,- </td>
  </tr>
  
  <tr>
    <td align="right"><strong> Other (Rp)- : </strong></td>
    <td align="right"> <?php echo isset($other_discount) ? $other_discount : '0'; ?>,- </td>
  </tr>
  
    
  <tr>
    <td align="right" bgcolor="#CCCCCC"><strong> Total   (Rp) : </strong></td>
    <td align="right" bgcolor="#CCCCCC"><b> <?php echo isset($amount) ? $amount : '0'; ?>,- </b></td>
  </tr>
  <tr>
    <td colspan="4">Admin : <?php echo isset($user) ? $user : ''; ?> / <?php echo isset($log) ? $log : ''; ?> </td>
  </tr>
</table>
<table width="430" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center">** THANK YOU ** </td>
  </tr>
</table>
</div>
</body>
</html>