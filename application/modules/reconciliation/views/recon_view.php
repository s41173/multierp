<?php 

$atts = array(
		  'class'      => 'refresh',
		  'title'      => 'add po',
		  'width'      => '800',
		  'height'     => '600',
		  'scrollbars' => 'yes',
		  'status'     => 'yes',
		  'resizable'  => 'yes',
		  'screenx'    =>  '\'+((parseInt(screen.width) - 800)/2)+\'',
		  'screeny'    =>  '\'+((parseInt(screen.height) - 600)/2)+\'',
		);
		
$atts1 = array(
	  'class'      => 'refresh',
	  'title'      => 'Purchase Invoice',
	  'width'      => '600',
	  'height'     => '280',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 280)/2)+\'',
);

$atts2 = array(
	  'class'      => 'refresh',
	  'title'      => 'Purchase Report',
	  'width'      => '800',
	  'height'     => '600',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 800)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 600)/2)+\'',
);

?>

<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Bank Reconciliation </legend>	
    	
        <form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
					
		        <td> <label for="tdate"> Current Date : &nbsp; &nbsp; </label> <br />  
                <input type="Text" name="tstart" id="d1" title="Start" size="10" class="required" /> 
			    <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/>                 -  
                <input type="Text" name="tend" id="d2" title="End" size="10" class="required" /> 
			    <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d2','yyyymmdd')" style="cursor:pointer"/>
                &nbsp; &nbsp; 
                </td> 
					
                <td> <label for="taccount"> Account : </label> <br />  
                     <select name="cacc" class="required">
	<option value="bank" <?php echo set_select('cacc', 'bank', isset($default['acc']) && $default['acc'] == 'bank' ? TRUE : FALSE); ?> /> 
    Bank </option>
	<option value="cash" <?php echo set_select('cacc', 'cash', isset($default['acc']) && $default['acc'] == 'cash' ? TRUE : FALSE); ?> /> 
    Cash </option>
	<option value="pettycash" <?php echo set_select('cacc', 'pettycash', isset($default['acc']) && $default['acc'] == 'pettycash' ? TRUE : FALSE); ?> /> Petty Cash </option>
			</select> &nbsp; &nbsp;
                </td> 
                
                <td> <label for="tbalance"> Current Balance : </label> <br />  
                <input type="text" id="tbalance" name="tbalance" size="13" title="Balance" onKeyUp="checkdigit(this.value, 'tbalance')" />                &nbsp; </td> 
										
                <td colspan="3" align="right"> <br />  
                   <input type="submit" name="submit" class="button" title="" value="Search" />
                   <input type="reset" name="reset" class="button" title="" value="Cancel" /> 
                </td>
					</tr> 
				</table>	
			</form>		
          
	</fieldset>
</div>


<div id="webadmin2">
	
	<form name="search_form" class="myform" method="post" action="<?php echo ! empty($form_action_del) ? $form_action_del : ''; ?>">
     <?php echo ! empty($table) ? $table : ''; ?>
	 <div class="paging"> <?php echo ! empty($pagination) ? $pagination : ''; ?> </div>
	</form>	
    
    <table style="float:left; margin:5px;">            
        <tr>
            <td colspan="2"> <label> Current Balance : &nbsp; </label> 
            <input type="text" size="15" readonly="readonly" value="<?php echo number_format($current); ?>" /> </td>
        </tr>       
	</table>
    
    <table style="float:right; margin:5px;">
			<tr> 
            <td> <label> Debit : </label> <br /> <input type="text" size="15" readonly="readonly" value="<?php echo number_format($debit); ?>" />             &nbsp; </td> 
			<td> <label> Credit : </label> <br /> <input type="text" size="15" readonly="readonly" value="<?php echo number_format($credit); ?>" />            </td> 
            </tr>
            
            <tr>
				<td colspan="2"> <label> Difference : &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </label> 
				<input type="text" size="15" readonly="readonly" value="<?php echo number_format($diff); ?>" /> </td>
			</tr>
            
	</table>
    
    <div class="clear"></div>
	
	<table align="right" style="margin:10px 0px 0 0; padding:3px; " width="100%" bgcolor="#D9EBF5">
	<tbody>
		<tr> 
		   <td align="right"> 
           <?php echo anchor_popup(site_url("reconciliation/report"), 'REPORT', $atts1); ?>
		   </td> 
		</tr>
	</tbody>
	</table>

	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>

	
</div>

