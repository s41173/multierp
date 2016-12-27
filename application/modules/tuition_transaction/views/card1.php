<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title> School Fee Card - <?php echo isset($name) ? $name : ''; ?></title>


<style media="all">

	body{ font-family: Arial, Helvetica, sans-serif; font-size:0.75em;}
	p {padding:0; margin:0; float:left; font-size:0.875em; font-family: Arial, Helvetica, sans-serif;}
	.clear{ clear:both;}
	
	.container{ width:26cm; height:16.5cm; border:0px solid #000;}
	.tablecontin{ width:24.5cm; height:9.5cm; margin:4.9cm 0 0 0cm; border-bottom:0px solid red; float:right; }
	
	.spp { text-align:right; width:4.1cm; font-size:14pt; font-weight:normal; }
	.amount { text-align:right; width:3.5cm; font-size:14pt; font-weight:normal; }
	.general { text-align:right; width:3.5cm; font-size:14pt; font-weight:normal; }
	.paraf { text-align:left; width:6cm; font-size:14pt; font-weight:normal; }
	.log { text-align:right; width:1.8cm; font-size:14pt; font-weight:normal; }
	.box1{ height:0.7cm; width:23.5cm; margin:0 0 0.4cm 0;}
	
</style>

</head>

<body onLoad="window.print()">

<div class="container">
	
	<p style="margin-left:17cm; margin-top:1.2cm; font-size:20pt; float:left; font-weight:bold"> <?php echo isset($year) ? $year : ''; ?> </p> 
    <div class="clear"></div>
	
	<div class="tablecontin">
	
    <div class="box1">  
        <p class="spp"> <?php echo isset($p1['amount1']) ? $p1['amount1'] : '' ?> </p> 
        <p class="spp"> <?php echo isset($p1['amount2']) ? $p1['amount2'] : '' ?> </p> 
        <p class="amount"> <?php echo isset($p1['total']) ? $p1['total'] : '' ?> </p>
        <p class="general"> <?php echo isset($p1['dates']) ? $p1['dates'] : '' ?> </p>
        <p class="paraf"> &nbsp; </p>
        <p class="log"> <?php echo isset($p1['log']) ? $p1['log'] : '' ?> </p>
    </div>
    <div class="clear"></div>
    
    <div class="box1">  
        <p class="spp"> <?php echo isset($p2['amount1']) ? $p2['amount1'] : '' ?> </p> 
        <p class="spp"> <?php echo isset($p2['amount2']) ? $p2['amount2'] : '' ?> </p> 
        <p class="amount"> <?php echo isset($p2['total']) ? $p2['total'] : '' ?> </p>
        <p class="general"> <?php echo isset($p2['dates']) ? $p2['dates'] : '' ?> </p>
        <p class="paraf"> &nbsp; </p>
        <p class="log"> <?php echo isset($p2['log']) ? $p2['log'] : '' ?> </p>
    </div>
    <div class="clear"></div>
    
    <div class="box1">  
        <p class="spp"> <?php echo isset($p3['amount1']) ? $p3['amount1'] : '' ?> </p> 
        <p class="spp"> <?php echo isset($p3['amount2']) ? $p3['amount2'] : '' ?> </p> 
        <p class="amount"> <?php echo isset($p3['total']) ? $p3['total'] : '' ?> </p>
        <p class="general"> <?php echo isset($p3['dates']) ? $p3['dates'] : '' ?> </p>
        <p class="paraf"> &nbsp; </p>
        <p class="log"> <?php echo isset($p3['log']) ? $p3['log'] : '' ?> </p>
    </div>
    <div class="clear"></div>
    
    <div class="box1">  
        <p class="spp"> <?php echo isset($p4['amount1']) ? $p4['amount1'] : '' ?> </p> 
        <p class="spp"> <?php echo isset($p4['amount2']) ? $p4['amount2'] : '' ?> </p> 
        <p class="amount"> <?php echo isset($p4['total']) ? $p4['total'] : '' ?> </p>
        <p class="general"> <?php echo isset($p4['dates']) ? $p4['dates'] : '' ?> </p>
        <p class="paraf"> &nbsp; </p>
        <p class="log"> <?php echo isset($p4['log']) ? $p4['log'] : '' ?> </p>
    </div>
    <div class="clear"></div>
    
    
    <div class="box1">  
        <p class="spp"> <?php echo isset($p5['amount1']) ? $p5['amount1'] : '' ?> </p> 
        <p class="spp"> <?php echo isset($p5['amount2']) ? $p5['amount2'] : '' ?> </p> 
        <p class="amount"> <?php echo isset($p5['total']) ? $p5['total'] : '' ?> </p>
        <p class="general"> <?php echo isset($p5['dates']) ? $p5['dates'] : '' ?> </p>
        <p class="paraf"> &nbsp; </p>
        <p class="log"> <?php echo isset($p5['log']) ? $p5['log'] : '' ?> </p>
    </div>
    <div class="clear"></div>
    
    <div class="box1">  
        <p class="spp"> <?php echo isset($p6['amount1']) ? $p6['amount1'] : '' ?> </p> 
        <p class="spp"> <?php echo isset($p6['amount2']) ? $p6['amount2'] : '' ?> </p> 
        <p class="amount"> <?php echo isset($p6['total']) ? $p6['total'] : '' ?> </p>
        <p class="general"> <?php echo isset($p6['dates']) ? $p6['dates'] : '' ?> </p>
        <p class="paraf"> &nbsp; </p>
        <p class="log"> <?php echo isset($p6['log']) ? $p6['log'] : '' ?> </p>
    </div>
    <div class="clear"></div>
    
    <div class="box1">  
        <p class="spp"> <?php echo isset($p7['amount1']) ? $p7['amount1'] : '' ?> </p> 
        <p class="spp"> <?php echo isset($p7['amount2']) ? $p7['amount2'] : '' ?> </p> 
        <p class="amount"> <?php echo isset($p7['total']) ? $p7['total'] : '' ?> </p>
        <p class="general"> <?php echo isset($p7['dates']) ? $p7['dates'] : '' ?> </p>
        <p class="paraf"> &nbsp; </p>
        <p class="log"> <?php echo isset($p7['log']) ? $p7['log'] : '' ?> </p>
    </div>
    <div class="clear"></div>
    
    <div class="box1">  
        <p class="spp"> <?php echo isset($p8['amount1']) ? $p8['amount1'] : '' ?> </p> 
        <p class="spp"> <?php echo isset($p8['amount2']) ? $p8['amount2'] : '' ?> </p> 
        <p class="amount"> <?php echo isset($p8['total']) ? $p8['total'] : '' ?> </p>
        <p class="general"> <?php echo isset($p8['dates']) ? $p8['dates'] : '' ?> </p>
        <p class="paraf"> &nbsp; </p>
        <p class="log"> <?php echo isset($p8['log']) ? $p8['log'] : '' ?> </p>
    </div>
    <div class="clear"></div>
    
    <div class="box1">  
        <p class="spp"> <?php echo isset($p9['amount1']) ? $p9['amount1'] : '' ?> </p> 
        <p class="spp"> <?php echo isset($p9['amount2']) ? $p9['amount2'] : '' ?> </p> 
        <p class="amount"> <?php echo isset($p9['total']) ? $p9['total'] : '' ?> </p>
        <p class="general"> <?php echo isset($p9['dates']) ? $p9['dates'] : '' ?> </p>
        <p class="paraf"> &nbsp; </p>
        <p class="log"> <?php echo isset($p9['log']) ? $p9['log'] : '' ?> </p>
    </div>
    <div class="clear"></div>
    
    <div class="box1">  
        <p class="spp"> <?php echo isset($p10['amount1']) ? $p10['amount1'] : '' ?> </p> 
        <p class="spp"> <?php echo isset($p10['amount2']) ? $p10['amount2'] : '' ?> </p> 
        <p class="amount"> <?php echo isset($p10['total']) ? $p10['total'] : '' ?> </p>
        <p class="general"> <?php echo isset($p10['dates']) ? $p10['dates'] : '' ?> </p>
        <p class="paraf"> &nbsp; </p>
        <p class="log"> <?php echo isset($p10['log']) ? $p10['log'] : '' ?> </p>
    </div>
    <div class="clear"></div>
    
    <div class="box1">  
        <p class="spp"> <?php echo isset($p11['amount1']) ? $p11['amount1'] : '' ?> </p> 
        <p class="spp"> <?php echo isset($p11['amount2']) ? $p11['amount2'] : '' ?> </p> 
        <p class="amount"> <?php echo isset($p11['total']) ? $p11['total'] : '' ?> </p>
        <p class="general"> <?php echo isset($p11['dates']) ? $p11['dates'] : '' ?> </p>
        <p class="paraf"> &nbsp; </p>
        <p class="log"> <?php echo isset($p11['log']) ? $p11['log'] : '' ?> </p>
    </div>
    <div class="clear"></div>
    
    <div class="box1">  
        <p class="spp"> <?php echo isset($p12['amount1']) ? $p12['amount1'] : '' ?> </p> 
        <p class="spp"> <?php echo isset($p12['amount2']) ? $p12['amount2'] : '' ?> </p> 
        <p class="amount"> <?php echo isset($p12['total']) ? $p12['total'] : '' ?> </p>
        <p class="general"> <?php echo isset($p12['dates']) ? $p12['dates'] : '' ?> </p>
        <p class="paraf"> &nbsp; </p>
        <p class="log"> <?php echo isset($p12['log']) ? $p12['log'] : '' ?> </p>
    </div>
    <div class="clear"></div>
    
   
   <!-- batas box -->
	</div> 
    <div class="clear"></div>

</div>

</body>
</html>
