<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" />
<style type="text/css">@import url("<?php echo base_url() . 'css/login.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/modal.css'; ?>");</style>
<script type="text/javascript" src="<?php echo base_url(); ?>js/register.js"></script>
<title>Login</title>
</head>
<body onload="">

    <div class="headerplace"> 
		<div class="headerlogo"></div>	
	</div>
    
	<div class="container_12">
        	
			<div class="grid_12" id="container">
				
				<div class="grid_12" id="loginplace">
				
				    <!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <style> 
      
        .notif{ font-size: 16px; padding: 0; margin: 0;}
        .notif a{ font-weight: bold; font-size: 18px; padding: 0; margin: 0;}
        .duedate{ color: red;}
        #btnclose{ background-color: antiquewhite;  float: right; margin: 10px;}
        #btnclose:hover{ background-color: red; color: #fff;}
      
    </style>
    <p class="notif">
        
        Pelanggan Yth, 
Sebelumnya kami ucapkan terima kasih atas kepercayaan Anda untuk selalu menggunakan <a target="_blank" href="http://dswip.com"> Dswip ERP </a> sebagai layanan sistem aplikasi keuangan. <br> 

Bersama ini kami informasikan bahwa pembayaran atas tagihan Anda, periode ke 10 (sepuluh) belum kami terima. <br>
Layanan Bapak/Ibu juga akan segera kami nonaktifkan untuk sementara mulai <span class="duedate"> 28 Juni 2018.</span> Kami mohon maaf atas ketidaknyamanan yang ditimbulkan. <br>
       
       <button id="btnclose" class="btnclose" onclick="close_window();"> Close X </button>
       <div class="clear"></div>
        
    </p>
  </div>

</div>
					<div id="logos" style="margin: 15px 0 0 335px;"> <img align="middle" width="300" height="100" src="<?php echo $logo; ?>" alt="<?php echo $pname; ?>" /> </div> 
					<div class="clear"></div>
				
					<div id="loginbox">
					<?php echo validation_errors(); ?>
						<fieldset class="field">
						<p style="text-align:center; font-weight:bolder; "> Web based ERP System </p>
						<form action="<?php echo $form_action; ?>" name="login_form" id="login_form" method="post" onsubmit="return login();">  
							<table style="margin-left:35px; ">
								<tr> <td> <label for="username">Username</label>  </td> <td>:</td> <td> <input type="text" name="username" id="user" size="20" class="form_field" value="<?php echo set_value("username"); ?>"/> 
								</td> </tr>
								<tr> <td> <label for="password">Password</label> </td> <td>:</td> <td> <input type="password" name="password" id="pass" size="20" class="form_field" value="<?php echo set_value('username'); ?>"/> </td>  </tr>
								<tr> <td colspan="3"> <input type="submit" name="submit" class="button" value="Login"/> <input type="reset" name="reset" class="button" value="Reset"/> </td> </tr>
							</table>
					  </form>
					  <a href="<?php echo site_url("login/forgot"); ?>"> [ Forgot Password ] </a>
					  </fieldset>
					  <?php
						$message = $this->session->flashdata('message');
						echo $message == '' ? '' : '<p class="field_error">' . $message . '</p>';
					  ?>
				    </div>
					
					<div id="descbox">
						<p style="font-size:11px; color:#333333; text-align:left; font-family:Verdana, Arial, Helvetica, sans-serif; "> 
							This is a secure service that is only given to the appropriate authorities. <br />
							Username is the email address you have registered as a user ID to login
							and password provided separately via email.
						</p>
						
						<p style="font-size:11px; color:#333333; text-align:left; font-family:Verdana, Arial, Helvetica, sans-serif; ">
							If you experience any difficulties in accessing this service please contact your Administrator or Manager. 
						</p>
					</div>
					
					<div class="clear"></div>
				</div>							
        	</div>
	</div>
	
	<div class="clear"></div>
	<div id="footer">
		<p> Copyright &copy; <?php echo date('Y').' '.$pname; ?> <br />  
		Significant contributions to the web client have been made by 
		<a style="padding:0; margin:0; color:#0000FF;" target="_blank" href="http://dswip.com"> D'swip Kreasindo </a> </p>
	</div>
	
	<script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
//    modal.style.display = "block";
    load_modal();
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

function close_window(){
    modal.style.display = "none";
}

function load_modal(){
    modal.style.display = "block";
    setTimeout(function(){ modal.style.display = "none"; }, 30000);
}

</script>

</body>
</html>
