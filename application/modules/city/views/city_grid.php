 <script type="text/javascript">
        $(document).ready(function () {
			var url = "<?php echo $source;?>";
            // prepare the data
            var source =
            {
                datatype: "json",
                datafields: [
                    { name: 'id' },
                    { name: 'name' }
                ],
                id: 'id',
                url: url,
                root: 'datax'
            };
            var dataAdapter = new $.jqx.dataAdapter(source);
            $("#jqxgrid").jqxGrid(
            {
                width: '99.8%',
				source: dataAdapter,
				sortable: true,
				filterable: true,
				pageable: true,
				filtermode: 'excel',
				autoheight: true,
				columnsresize: true,
				autoshowfiltericon: false,
                columns: [
               //   { text: 'ID', dataField: 'id', width: '500' },
                  { text: 'Name', dataField: 'name', width: '99.99%' }
/*                  { text: 'Product', dataField: 'productname', width: 180 },
                  { text: 'Quantity', dataField: 'quantity', width: 80, cellsalign: 'right' },
                  { text: 'Unit Price', dataField: 'price', width: 90, cellsalign: 'right', cellsformat: 'c2' },
                  { text: 'Total', dataField: 'total', cellsalign: 'right', minwidth: 100, cellsformat: 'c2' }*/
                ]
            });
        });
    </script>


<div id="webadmin">
	
	<div class="title"> <?php $flashmessage = $this->session->flashdata('message'); ?> </div>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<div id="errorbox" class="errorbox"> <?php echo validation_errors(); ?> </div>
	
	<fieldset class="field"> <legend> City </legend>
	<form name="modul_form" class="myform" id="ajaxform" method="post" action="<?php echo $form_action; ?>">
				<table>
					<tr> 
					<td> <label for="tname">Name</label></td> <td>:</td> <td><input type="text" class="required" name="tname" size="25" title="Name" 
					value="<?php echo set_value('tname', isset($default['name']) ? $default['name'] : ''); ?>" /> <br />  </td> 
					
					<td colspan="3"> <input type="submit" name="submit" class="button" title="Klik tombol untuk proses data" value=" Save " /> <input type="reset" name="reset" class="button" title="Klik tombol untuk proses data" value=" Cancel " /> </td>
					
					</tr> 
				</table>	
			</form>			  
	</fieldset>
</div>


<div id="webadmin2">
	
	<form name="search_form" class="myform" method="post" action="<?php echo ! empty($form_action_del) ? $form_action_del : ''; ?>">
     
        <div id="jqxgrid">
        </div>
     
	</form>	
    	
	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>
</div>

