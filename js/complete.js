		$(document).ready(function(){
		  
		  
		  // ============================== jqwidget plugin ==========================================
		 
		  // ============================== jqwidget plugin ==========================================
		  
		  
			// $('#bnews').hide();
			
			// input-masked
			$("#d1,#d2,#d3,#d4,#d5,#d6,#d7,#d8,#d9,#d10,#d11,#d12,#d13,#d14,#d15,#d16").mask("9999-99-99");
			$("#tphone2,#tphone3").mask("099-9999999");
			$("#ttime").mask("99:99");
			$("#tqty").mask("99.9");
			$("#tact").mask("999-99");
			$("#tperiodfinancial").mask("2099-2099");
			$("#tmobile").mask("099-99999999");
			$("#tyear,#tyear1").mask("2099");
			
			
			// $('.flexme').flexigrid({height:'auto',width:845,striped:true});
			
			
			$('#loading').ajaxStart(function(){
				$(this).fadeIn();
			}).ajaxStop(function(){
				$(this).fadeOut();
			});
			
			// tabs
			$(".tab_content").hide(); //Hide all content
			$("ul.tabs li:first").addClass("active").show(); //Activate first tab
			$(".tab_content:first").show(); //Show first tab content
			
			//On Click Event
			$("ul.tabs li").click(function() {
				$("ul.tabs li").removeClass("active"); //Remove any "active" class
				$(this).addClass("active"); //Add "active" class to selected tab
				$(".tab_content").hide(); //Hide all tab content
				var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
				$(activeTab).fadeIn(); //Fade in the active content
				return false;
			});
			// akhir tabs
			
				
			$("a.fancy").fancybox({
				'width': '90%',
			    'height': '90%',
				'overlayColor' : '#000',
			    'autoScale': true,
				'transitionIn': 'fade',
				'transitionOut': 'elastic',
				'type': 'iframe',
				'href': this.href,
				'onClosed': function() { parent.location.reload(true)}
			});
			
			$("a.fancymini").fancybox({
				'overlayColor' : '#000',
			    'autoScale': true,
				'transitionIn': 'fade',
				'transitionOut': 'elastic',
				'type': 'iframe',
				'href': this.href,
				'onClosed': function() { parent.location.reload(true)}
			});
			
			$("a.fancyrefresh").fancybox({
				'titlePosition'		: 'outside',
				'overlayColor'		: '#000',
				'overlayOpacity'	: 0.9,
				'href': this.href,
				'onClosed': function() { parent.location.reload(true)}
			});
			
			$("a.fancydodol").fancybox(
            {
				'titlePosition'		: 'outside',
				'overlayColor'		: '#000',
				'overlayOpacity'	: 0.9,
				'href': this.href,
				'onClosed': function() { parent.location.reload(true)}
            })
			.hover(function () {$(this).click();});
			
			
			$(".details, .editstatus, .update, .cost").fancybox({
			    'width': '90%',
			    'height': '90%',
				'overlayColor' : '#000',
			    'autoScale': true,
				'transitionIn': 'fade',
				'transitionOut': 'elastic',
				'type': 'iframe',
				'href': this.href,
				'onClosed': function() { parent.location.reload(true)}
			});
			
			
			// Batas select action
			
			// hide / show
		
			  // Load Page di awal
			 // $("#obj2").hide();
			 // $("#bhide").hide();
			 
			 // Tampilkan DIV ID=Form setelah klik A ID=ShowForm
			 // $("#bshow").click(function(){
				 // $("#obj2").fadeIn();
				 // $("#bhide").fadeIn();
				 // $("#bshow").hide();
			 // });
			 
			 // sembunyi DIV ID=Form setelah klik A ID=ShowForm
			 // $("#bhide").click(function(){
				 // $("#obj2").hide();
				 // $("#bhide").hide();
				 // $("#bshow").fadeIn();
			 // });
			
			

			
			$('#cclassification').change(function() {		
//				var sales = $("#titem").val();
				$.ajax({
				type: 'POST',
				url: uri +'get_classification_no',
				data: $(this).serialize(),
				success: function(data)
				{
				   /*res = data.split("|");
				   amount = res[0]-res[1]*/
				   document.getElementById("tcno").value = data;
				}
				})
				return false;
			});
			
			$('#ccjournaltype').change(function() {		
				$.ajax({
				type: 'POST',
				url: uri +'get_counter_journal',
				data: $(this).serialize(),
				success: function(data)
				{
				   document.getElementById("tno").value = data;
				}
				})
				return false;
			});
			
			$('#cstokout').change(function() {
				
				var stockout = $("#tbpbg").val();
				var pro = $("#cstokout").val();
				
				$.ajax({
				type: 'POST',
				url: uri +'get_stock_out_qty',
				data: "stockout="+ stockout + "&product=" + pro,
				success: function(data)
				{
				   document.getElementById("tout").value = data;
				}
				})
				return false;
    	    });
			
			$('#cpitem').change(function() {
			
				var product = $("#cpitem").val();
				$.ajax({
				type: 'POST',
				url: uri +'get_product_qty',
				data: "product=" + product,
				success: function(data)
				{
				   document.getElementById("stqty").value = data;
				}
				})
				return false;
    	    });
			
			$('#bgetsalesno').click(function() {
				
				var sales = $("#titem").val();
				$.ajax({
				type: 'POST',
				url: uri +'get_salesno',
				data: "salesno=" + sales,
				success: function(data)
				{
				   res = data.split("|");
				   document.getElementById("ttotal").value = res[0];
				   document.getElementById("tdp").value = res[1];
				}
				})
				return false;
    	    });
			
			$('#bgetsalesp2').click(function() {
				
				var sales = $("#titem").val();
				$.ajax({
				type: 'POST',
				url: uri +'get_salesno',
				data: "salesno=" + sales,
				success: function(data)
				{
				   res = data.split("|");
				   amount = res[0]-res[1]
				   document.getElementById("ttotal").value = amount;
				}
				})
				return false;
    	    });
			
			$('#bgetnsalesno').click(function() {
				
				var sales = $("#titem").val();
				$.ajax({
				type: 'POST',
				url: uri +'get_nsalesno',
				data: "salesno=" + sales,
				success: function(data)
				{
				   res = data.split("|");
				   document.getElementById("ttotal").value = res[0];
				   document.getElementById("tdp").value = res[1];
				}
				})
				return false;
    	    });
			
			$('#bgetnsalesp2').click(function() {
				
				var sales = $("#titem").val();
				$.ajax({
				type: 'POST',
				url: uri +'get_nsalesno',
				data: "salesno=" + sales,
				success: function(data)
				{
				   res = data.split("|");
				   amount = res[0]-res[1]
				   document.getElementById("ttotal").value = amount;
				}
				})
				return false;
    	    });
			
		
			/* Insert ajax */
			
			// $('#form').submit(function() {
				// $.ajax({
					// type: 'POST',
					// url: $(this).attr('action'),
					// data: $(this).serialize(),
					// success: function(data) {
				    // $('#webadmin2').html(data);	
				  // }
				// })
				// return false;
			// });
			
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
			
			
			/* == ============ batas insert ajax ================*/
			
			
			/* Basic function */
			
			
			$('#tp1').keyup(function() {
				
				var cost = parseFloat($("#tcosts").val());
				var totaltax = parseFloat($('#ttotaltax').val());
				var total = parseFloat(cost + totaltax);
				var p1 = parseFloat($(this).val());
				var res = parseFloat(total - p1);
				$('#tbalance').val(res);
    	    });
			
			$('#tcosts').keyup(function() {
			
				var cost = parseFloat($(this).val());
				var totaltax = parseFloat($('#ttotaltax').val());
				var total = parseFloat(cost + totaltax);
				var p1 = parseFloat($('#tp1').val());
				var res = parseFloat(total - p1);
				$('#tbalance').val(res);
    	    });
			
			// autocomplete
			$('#tproductsearch').autocomplete({
				// serviceUrl berisi URL ke controller/fungsi yang menangani request kita
				serviceUrl: site+'/product/autocomplete',
				// fungsi ini akan dijalankan ketika user memilih salah satu hasil request
				onSelect: function (suggestion) {
					// alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
				}
			});	

			$('#tgproductsearch').autocomplete({
			// serviceUrl berisi URL ke controller/fungsi yang menangani request kita
			serviceUrl: site+'/gproduct/autocomplete',
			// fungsi ini akan dijalankan ketika user memilih salah satu hasil request
			onSelect: function (suggestion) {
				// alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
			}
			});	
			
			$('#tinventorysearch').autocomplete({
				// serviceUrl berisi URL ke controller/fungsi yang menangani request kita
				serviceUrl: site+'/inventoryc/autocomplete',
				// fungsi ini akan dijalankan ketika user memilih salah satu hasil request
				onSelect: function (suggestion) {
					// alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
				}
			});	
			
			$('#tstudentsearch').autocomplete({
				// serviceUrl berisi URL ke controller/fungsi yang menangani request kita
				serviceUrl: site+'/payment_status/autocomplete/',
				// fungsi ini akan dijalankan ketika user memilih salah satu hasil request
				onSelect: function (suggestion) {
					// alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
				}
			});	
			
			$('#tstudentinactive').autocomplete({
				// serviceUrl berisi URL ke controller/fungsi yang menangani request kita
				serviceUrl: site+'/inactive/autocomplete',
				// fungsi ini akan dijalankan ketika user memilih salah satu hasil request
				onSelect: function (suggestion) {
					// alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
				}
			});	
			
			$('#tvendorsearch').autocomplete({
			// serviceUrl berisi URL ke controller/fungsi yang menangani request kita
			serviceUrl: site+'/vendor/autocomplete',
			// fungsi ini akan dijalankan ketika user memilih salah satu hasil request
			onSelect: function (suggestion) {
				// alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
			}
			});	
			
			$('#tcustsearch').autocomplete({
			// serviceUrl berisi URL ke controller/fungsi yang menangani request kita
			serviceUrl: site+'/customer/autocomplete',
			// fungsi ini akan dijalankan ketika user memilih salah satu hasil request
			onSelect: function (suggestion) {
				// alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
			}
			});	
			
			$('#temployeesearch').autocomplete({
			// serviceUrl berisi URL ke controller/fungsi yang menangani request kita
			serviceUrl: site+'/employees/autocomplete',
			// fungsi ini akan dijalankan ketika user memilih salah satu hasil request
			onSelect: function (suggestion) {
				// alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
			}
			});	
		
			
			//end document ready
		  });
