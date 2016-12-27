function login_status()
{
	$(document).ready(function(){
	$.ajax({
		type: 'POST',
		url: uri +'get_hello',
		data: $(this).serialize(),
		success: function(data){ alert(data); }
	})
	return false;	
   }); 
   
  // setTimeout("login_status()",1000);
}