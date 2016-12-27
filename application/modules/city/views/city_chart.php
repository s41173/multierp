<!-- CSS Grid -->
<link rel="stylesheet" href="<?php echo base_url().'js/jxgrid/' ?>css/jqx.base.css" type="text/css" />
<!-- CSS Grid -->

<!-- JS Grid -->

<script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxcore.js"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxdata.js"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxdraw.js"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jxgrid/' ?>js/jqxchart.core.js"></script>
<!-- JS Grid -->

<script type="text/javascript" src="<?php echo base_url();?>public/javascripts/FusionCharts.js"></script>

<script type="text/javascript">

$(document).ready(function () {
		
			var url = "<?php echo $source;?>";
            // prepare chart data
           
		var source =
		{
			 datatype: "json",
			 datafields: [
				 { name: 'OrderDate', type: 'date'},
				 { name: 'Quantity'},
				 { name: 'ProductName'}
			],
			url: url
		};

	   var dataAdapter = new $.jqx.dataAdapter(source,
		{
			autoBind: true,
			async: false,
			downloadComplete: function () { },
			loadComplete: function () { },
			loadError: function () { }
		});

	 // prepare jqxChart settings
		var settings = {
                title: "Fitness & exercise weekly scorecard",
                description: "Time spent in vigorous exercise",
                padding: { left: 5, top: 5, right: 5, bottom: 5 },
                titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
                source: dataAdapter,
                categoryAxis:
                    {
                        dataField: 'Quantity',
                        showGridLines: false
                    },
                colorScheme: 'scheme02',
                seriesGroups:
                    [
                        {
                            type: 'column',
                            columnsGapPercent: 30,
                            seriesGapPercent: 0,
                            valueAxis:
                            {
                                minValue: 0,
                                maxValue: 100,
                                unitInterval: 10,
                                description: 'Time in minutes'
                            },
                            series: [
                                    { dataField: 'Quantity', displayText: 'ProductName'}
                                ]
                        }
                    ]
            };
            
            // select the chartContainer DIV element and render the chart.
            $('#chartContainer').jqxChart(settings);
			
			$("#button").click(function(){
				$("#chartContainer").toggle();
			});
});

</script>


<div id="webadmin">
	
	<?php  echo ! empty($graph) ? $graph : '';  ?>
</div>


<div id="webadmin2">
		
        <input type="button" id="button" value="Show / Hide" />
        <div id="chartContainer" style="width:100%; height:500px;"> </div>
</div>

