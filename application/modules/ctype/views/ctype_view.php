
<script type="text/javascript">
	$(document).ready(function () {
		// Create Repeat Button.
		 $(".jqxButton").jqxButton({ width: '75', height: '25'});
		// Set its delay(the interval between two clicks) property.
	});
</script>

<?php 

$atts = array(
		  'class'      => 'fancy',
		  'title'      => '',
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
	  'height'     => '400',
	  'scrollbars' => 'no',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 400)/2)+\'',
);

$atts2 = array(
	  'class'      => 'fancy',
	  'title'      => '',
	  'width'      => '550',
	  'height'     => '350',
	  'scrollbars' => 'no',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 550)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 350)/2)+\'',
);

?>


<div id="webadmin">
	    
<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Contract Type </legend>
	<form name="ajax_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
        <table>
            <tr> 
            <td> <label for="tname">Name</label></td> <td>:</td> <td> <input type="text" class="required" name="tname" size="25" title="Name" 
            value="<?php echo set_value('tname', isset($default['name']) ? $default['name'] : ''); ?>" /> - 
            <input type="text" class="required" readonly name="titem" id="titem" size="10" title="Name" placeholder="Account"/> &nbsp; <?php echo anchor_popup(site_url("accountc/get_list/"), '[ ... ]', $atts1); ?> &nbsp;  </td> 
            <td> 
            <input type="submit" name="submit" class="jqxButton" title="SUBMIT" value="Save" />  
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
		
	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>
</div>

