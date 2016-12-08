<html>
<head>
<title> Payroll Invoice Receipt : <?php echo isset($pono) ? $pono : ''; ?> </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
	
	body,td,th {font-family: Courier New, Courier, monospace;}
    body{ margin:0px auto 0px;	padding:3px; font-size:12px;	color:#333;	width:95%; background-position:top; background-color:#fff;}
    .table-list {clear: both; text-align: left; border-collapse: collapse;	margin: 0px 0px 10px 0px; background:#fff;} 
    .table-list td {color: #333; font-size:12px; border-color: #fff; border-collapse: collapse;	vertical-align: center;	padding: 3px 5px;	      border-bottom:1px #CCCCCC solid;}
	
</style>

</head>
<body onLoad="window.print()">
<table class="table-list" width="430" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td colspan="4" align="center"><h2> <?php echo isset($company) ? $company : ''; ?> <br> PAYROLL INVOICE </h2></td>
  </tr>
  <tr>
    <td  colspan="4" align="center">
      <table width="100%" border="0" cellpadding="2" cellspacing="1">
		<tr>
          <td width="24%"><strong> Order No </strong></td>
          <td width="4%"><strong>:</strong></td>
          <td width="72%"> <?php echo isset($pono) ? $pono : ''; ?> </td>
        </tr>

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
          <td><strong> Account </strong></td>
          <td><strong>:</strong></td>
          <td> <?php echo isset($acc) ? $acc : ''; ?> </td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td width="294" align="right"><strong> Salary   (Rp)+ : </strong></td>
    <td width="125" align="right"> <?php echo isset($salary) ? $salary : '0'; ?>,- </td>
  </tr>
  <tr>
    <td width="294" align="right"><strong> Honor (Rp)+ : </strong></td>
    <td width="125" align="right"> <?php echo isset($honor) ? $honor : '0'; ?>,- </td>
  </tr>
  <tr>
    <td align="right"><strong> Bonus  (Rp)+ : </strong></td>
    <td align="right"> <?php echo isset($bonus) ? $bonus : '0'; ?>,- </td>
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
    <td align="right"> <?php echo isset($other) ? $other : '0'; ?>,- </td>
  </tr>
  
    
  <tr>
    <td align="right" bgcolor="#CCCCCC"><strong> Total   (Rp) : </strong></td>
    <td align="right" bgcolor="#CCCCCC"><b> <?php echo isset($balance) ? $balance : '0'; ?>,- </b></td>
  </tr>
  <tr>
    <td colspan="4">Admin : <?php echo isset($user) ? $user : ''; ?> / <?php echo isset($log) ? $log : ''; ?> </td>
  </tr>
</table>
<table width="430" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center">  </td>
  </tr>
</table>
</body>
</html>