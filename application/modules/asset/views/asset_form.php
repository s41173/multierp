<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'development-bundle/themes/base/ui.all.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/jquery.fancybox-1.3.4.css'; ?>");</style>

<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.autocomplete.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/register.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/datetimepicker_css.js"></script>

<script type="text/javascript">
var uri = "<?php echo site_url('asset')."/"; ?>";
var baseuri = "<?php echo base_url(); ?>";
var site = null;
</script>

<style>
        .refresh{ border:1px solid #AAAAAA; color:#000; padding:2px 5px 2px 5px; margin:0px 2px 0px 2px; background-color:#FFF;}
		.refresh:hover{ background-color:#CCCCCC; color: #FF0000;}
		.refresh:visited{ background-color:#FFF; color: #000000;}	
</style>

<script type="text/javascript">


$(document).ready(function(){
		
	$('#cgroup').change(function() {
      $.get(uri+"get_period_group/"+$(this).val(), function(data, status){ $("#tperiod").val(data); $("#ttotalmonth").val(data*12); }  );
        
	});
    
    $('#bcalculate').click(function() {
      
        var purchase_amt = parseInt($("#tamount").val());
        var residual = parseInt($("#tresidual").val());
        var total_month = parseInt($("#ttotalmonth").val());
        if (residual < purchase_amt){
            
            var amt = Math.round((purchase_amt-residual)/total_month);
            $("#tcost").val(amt);
            
        }else{ $("#tresidual").val(''); }
	});
    
    $('#ajaxform,#ajaxform2,#ajaxform3,#ajaxform4').submit(function() {
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(data) {
                // $('#result').html(data);
                if (data == "true")
                {
                    location.reload(true);
                }
                else
                {
                    // alert(data);
                    document.getElementById("errorbox").innerHTML = data;
                }

            }
        })
        return false;
    });
	
/* end document */		
});

</script>

<?php 
		
$atts1 = array(
	  'class'      => 'refresh',
      'id'         => 'bdemand',
	  'title'      => 'add cust',
	  'width'      => '600',
	  'height'     => '400',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 400)/2)+\'',
);

?>

<div id="webadmin">
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Fixed Asset </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>" >
				<table>
                
					<tr>	
         <td> <label for="tno"> Code </label> </td> <td>:</td>
	     <td> <input type="text" class="required" name="tcode" id="tcode" size="10" title="Code" value="" /> </td>
					</tr>
                   
                   <tr>	
         <td> <label for="tno"> Name </label> </td> <td>:</td>
	     <td> <input type="text" class="required" name="tname" id="tname" size="35" title="Name" /> </td>
					</tr>
                   
            <tr>	
			<td> <label for="tname"> Group </label> </td> <td>:</td>
			<td> <?php $js = 'class="required", id="cgroup"'; echo form_dropdown('cgroup', $group, isset($default['group']) ? $default['group'] : '', $js); ?> &nbsp; <br /> </td>
			</tr>
                    
            <tr>	
                 <td> <label for="tdate"> Purchase Date </label> </td> <td>:</td>
                 <td>  
                   <input type="Text" name="tdate" id="d1" title="Purchase date" size="10" class="required" /> 
                   <img src="<?php echo base_url();?>/jdtp-images/cal.gif" onclick="javascript:NewCssCal('d1','yyyymmdd')" style="cursor:pointer"/> &nbsp; 
                   <label for="tdate"> Period (Year) </label> &nbsp; 
                   <input type="number" id="tperiod" name="tperiod" style="width:50px;" readonly> 
                </td>
            </tr>
                            
            <tr>	
         <td> <label for="tno"> Purchase Amount </label> </td> <td>:</td>
	     <td> <input type="text" class="required" name="tamount" id="tamount" size="10" title="Purchase Amount" onKeyUp="checkdigit(this.value, 'tamount')" value="0" /> </td>
            </tr>
                            
            <tr>	
         <td> <label for="tno"> Residual Amount </label> </td> <td>:</td>
	     <td> <input type="text" class="required" name="tresidual" id="tresidual" size="10" title="Residual Amount" onKeyUp="checkdigit(this.value, 'tresidual')" value="0" />
            <button type="button" id="bcalculate"> Calculate </button>
            </td>
            </tr>
                            
         <tr>	
         <td> <label for="tno"> Monthly Cost </label> </td> <td>:</td>
	     <td> <input type="text" class="required" name="tcost" id="tcost" size="10" title="Monthly Cost" readonly /> 
        &nbsp; <span> * &nbsp; </span> <input type="number" id="ttotalmonth" name="ttotalmonth" style="width:50px;" readonly> <span> months </span> </td>
         </tr>
					            
        <tr>
            <td> <label for="titem"> Account </label> </td> <td>:</td>
            <td>
<input type="text" class="required" readonly name="titem" id="titem" size="5" title="Accumulation" value="<?php echo set_value('titem', isset($default['account']) ? $default['account'] : ''); ?>" /> &nbsp;
             <?php echo anchor_popup(site_url("accountc/get_list/"), '[ ... ]', $atts1); ?> &nbsp; </td>  
        </tr>
        
        <tr> <td> <label for="tdesc"> Description </label> </td> <td>:</td> 
        <td> <textarea name="tdesc" class="required" title="Description" cols="41" rows="3"></textarea> &nbsp; <br /> </td></tr>	
			   
				</table>
				<p style="margin:15px 0 0 0; float:right;">
					<input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> 
					<input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " />
				</p>	
			</form>			  
	</fieldset>
</div>

