var xmlHttp = buatObjekXmlHttp();
// var uri = 'http://admin/ekspedisi/';

function buatObjekXmlHttp()
{
   var obj = null;
   if (window.ActiveXObject)
   obj = new ActiveXObject("Microsoft.XMLHTTP");
   else
   if (window.XMLHttpRequest)
   obj = new XMLHttpRequest();

   // Cek isi xmlHttp
   if (obj == null)
   document.write(
   "Browser tidak mendukung XMLHttpRequest");
   return obj;
}

function cekall(val)
{
	for (i=0;i<val;i++)
	{
		document.getElementById("cek"+i).checked=true;
	}
}

function uncekall(val)
{for (i=0;i<val;i++){document.getElementById("cek"+i).checked=false;}}

function geturl(val)
{
	document.getElementById("turl").value = val.toLowerCase()+"/";
}

function setnilai(val)
{
	document.getElementById("turl").value = "article/get_category/"+val.toLowerCase()+"/";
}

function setnews(permalink)
{
	opener.document.getElementById("turl").value = "article/get_article/"+permalink+"/";
	self.close();
}

function setpermalink(permalink)
{
	res = permalink.replace(/\s/g, "");
	document.getElementById("tpermalink").value = res;
}

// - ----------------------------------------------------------------------------ajax ----------------------------------------------------------------------


function checkdigit(sText, nid)
{
   var ValidChars = "0123456789.";
   var IsNumber = true;
   var Char;


   for (i = 0; i < sText.length && IsNumber == true;
   i ++ )
   {
      Char = sText.charAt(i);
      if (ValidChars.indexOf(Char) == - 1)
      {
         IsNumber = false;
         document.getElementById(nid).value = "0";
         alert("Format text must be numeric");
      }
      else
      {
         document.getElementById(nid).value = sText;
      }
   }
}

function checknumeric(sText, nid)
{
   var ValidChars = "0123456789.";
   var IsNumber = true;
   var Char;


   for (i = 0; i < sText.length && IsNumber == true;
   i ++ )
   {
      Char = sText.charAt(i);
      if (ValidChars.indexOf(Char) == - 1)
      {
         IsNumber = true;
         document.getElementById(nid).value = sText;
      }
      else
      {
         IsNumber = false;
         alert("Format text salah");
         document.getElementById(nid).value = "";
      }
   }
}


function calculatepayment()
{
	var bill = parseFloat(document.getElementById("tbill").value);
    var p1 = parseFloat(document.getElementById("tp1").value);
    var p2 = parseFloat(document.getElementById("tp2").value);
	
    if (isNaN(bill) != true & isNaN(p1) != true)
    {
       var g = parseFloat( bill - p1);
       document.getElementById("tp2").value = g;
	   
	   var h = parseFloat( p1 + p2);
	   document.getElementById("ttotal").value = g;
    }
	else
	{
	   alert("Value can't null");
	}
}

function calculate()
{
    if (check() == true)
    {
       var a = parseFloat(document.getElementById("ubangun").value);
       var b = parseFloat(document.getElementById("udaftar").value);
       var c = parseFloat(document.getElementById("uskul").value);
       var d = parseFloat(document.getElementById("uosis").value);
       var e = parseFloat(document.getElementById("upraktek").value);
       var f = parseFloat(document.getElementById("udll").value);
    
       var g = parseFloat(a + b + c + d + e + f);
       document.getElementById("jumlah").value = g;
       document.getElementById("tmblp").disabled = false;
    }
}

/*==================== opener set value ==========================*/

function setvalue(values,id)
{
	opener.document.getElementById(id).value = values;
	self.close();
	// document.getElementById(id).value = values;
}


//------------------------------------------------------------export_view-------------------------------------------------------------------

//------------------------------------------------------------login_view-------------------------------------------------------------------

function login()
{	
    var user = document.getElementById("user");
    var pass = document.getElementById("pass");
    
      if (user.value == "")
      {
         alert("Username must be filled");
         user.focus();
         return false;
      }
      
      else if (pass.value == "")
      {
         alert("Password must be filled");
         pass.focus();
         return false;
      }
      
      else
      {
        return true;
      }
}

