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
	  'height'     => '300',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 300)/2)+\'',
);

$atts2 = array(
	  'class'      => 'refresh',
	  'title'      => 'Purchase Report',
	  'width'      => '600',
	  'height'     => '400',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 400)/2)+\'',
);

?>

<script type="text/javascript">
	
$(document).ready(function(){
			
	$('#cdept').change(function() {

	   $.ajax({
		type: 'POST',
		url: uri +'get_faculty_id',
		data: $(this).serialize(),
		success: function(data)
		{
		   document.getElementById("facultybox").innerHTML = data;
		}
		})
		return false;
	   
	});
	
	$('#cdept2').change(function() {

	   $.ajax({
		type: 'POST',
		url: uri +'get_faculty_id/null/cfacultys2',
		data: $(this).serialize(),
		success: function(data)
		{
		   document.getElementById("facultybox2").innerHTML = data;
		}
		})
		return false;
	   
	});
			
	$('#bget').click(function() {

	   var dept = $("#cdept").val();
	   var faculty = $("#cfacultys").val();
	   
	   $.ajax({
		type: 'POST',
		url: uri +'get_grade/val',
		data: "dept="+ dept + "&faculty=" + faculty,
		success: function(data)
		{
		   document.getElementById("gradebox").innerHTML = data;
		}
		})
		return false;
	   
	});
	
	$('#bget2').click(function() {

	   var dept = $("#cdept2").val();
	   var faculty = $("#cfacultys2").val();
	   
	   $.ajax({
		type: 'POST',
		url: uri +'get_grade/val',
		data: "dept="+ dept + "&faculty=" + faculty,
		success: function(data)
		{
		   document.getElementById("gradebox2").innerHTML = data;
		}
		})
		return false;
	   
	});
	
/* end document */		
});
	
</script>

<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> Student List </legend>	
    	
        <form name="modul_form" class="myform" id="form" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
                    
            <td> <label for="cdept"> Department : </label> <br />
		        <?php $js = 'id="cdept"'; echo form_dropdown('cdept', $dept, isset($default['dept']) ? $default['dept'] : '', $js); ?> <br /> </td>
                
    <td> <label for="cfaculty"> Faculty : </label> <br />
	<div id="facultybox" style="float:left; margin-right:5px;"></div> &nbsp; </td>
        		     						
            <td colspan="3" align="right"> <br />  
               <input type="submit" name="submit" class="button" title="" value="Search" /> 
            </td>
			</tr> 
		</table>	
			</form>		
	</fieldset>
</div>


<div id="webadmin2">
	    
    <form name="search_form" class="myform" method="post" action="<?php echo ! empty($form_action_select) ? $form_action_select : ''; ?>">
     <?php echo ! empty($table) ? $table : ''; ?>
	 <div class="paging"> <?php echo ! empty($pagination) ? $pagination : ''; ?> </div>
	 <p class="cek"> <?php echo ! empty($radio1) ? $radio1 : ''; echo ! empty($radio2) ? $radio2 : ''; ?>  </p> 

    <!-- Mutation Form -->
	<fieldset class="field"> <legend> Transfer Process </legend>	
    	
	<table>
	<tr> 
                    
    <td> <label for="cdept"> Department : </label> <br />
         <?php $js = 'id="cdept2"'; echo form_dropdown('cdept', $dept, isset($default['dept']) ? $default['dept'] : '', $js); ?> <br /> 
    </td>
                
    <td> <label for="cfaculty"> Faculty / Grade : </label> <br />
	<div id="facultybox2" style="float:left; margin-right:5px;"></div> <input type="button" id="bget2" value="GET" /> &nbsp; </td>
    
    <td> <label for="cgrade">  </label> <br />
    <div id="gradebox2"  style="float:left; margin-right:5px;"></div>  &nbsp; </td>
        		             						
    <td colspan="3" align="right"> <br />  
    <input type="submit" name="submit" class="button" title="" value="TRANSFER" /> 
    </td>
	</tr> 
	</table>	
    </form>		  
	</fieldset>
    <!-- Mutation Form -->

	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>

	
</div>

