<!doctype html>
<html>

<head>
    <title>Wykres obciążenia magazynu</title>
    <meta http-equiv="refresh" content="60">
    <script src="js/Chart.bundle.js"></script>
    <script src="js/utils.js"></script>
    <style>
        canvas {
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
        }
    </style>
</head>

<body>


<?php

    $conn = oci_connect(login, password, dbhost);
    $stid = oci_parse($conn, 'SELECT * FROM view_warehouse_traffic');
    oci_execute($stid);

    $opisy ="";
    $kolumna1 ="";
    $kolumna2 ="";
    $kolumna3 ="";
    $kolumna4 ="";



while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {


    $opisy = $opisy . "'" . $row["symbol"] ."',";
    $kolumna1 = $kolumna1 . $row["wzDoRealizacji"] . "," ;
    $kolumna2 = $kolumna2 . $row["wzKompletacja"] . "," ;
    $kolumna3 = $kolumna3 . $row["wzDoKontroli"] . "," ;
    $kolumna4 = $kolumna4 . $row["wzPakowanie"] . "," ;




}



?>


<div style="width: 100%; ">
    <canvas id="canvas"></canvas>
</div>

<script>
    var barChartData = {


        labels: [ <?php echo $opisy; ?> ],

        datasets: [{
            label: 'Do realizacji',
            backgroundColor: window.chartColors.red,
            yAxisID: "y-axis-1",
            data: [ <?php echo $kolumna1; ?> ]
        }, {
            label: 'Kompletacja',
            backgroundColor: window.chartColors.orange,
            yAxisID: "y-axis-1",
            data: [ <?php echo $kolumna2; ?>  ]
        },{
            label: 'Do kontroli',
            backgroundColor: window.chartColors.blue,
            yAxisID: "y-axis-1",
            data: [ <?php echo $kolumna3; ?>  ]
        },{
            label: 'Do pakowania',
            backgroundColor: window.chartColors.green,
            yAxisID: "y-axis-1",
            data: [ <?php echo $kolumna4; ?>  ]
        }]

    };
    window.onload = function() {
        var ctx = document.getElementById("canvas").getContext("2d");
        window.myBar = new Chart(ctx, {
            type: 'bar',
            data: barChartData,
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:"Wskaźnik obciążenia magazynu",
		    fontSize: 32,
	 	    fontStyle: "bold"		
                },
                tooltips: {
                    mode: 'index',
                    intersect: true
                },
		legend: {
			display: true,
			labels: {
				fontStyle: "bold",
				fontSize: 13
				}
			},
		
                scales: {

		    xAxes: [{
			ticks: {
				fontSize: 24,
				fontStyle: "bold"
				}
	
			}],			

                    yAxes: [{
                        type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                        display: true,
                        position: "left",
                        id: "y-axis-1",
			ticks: {
				fontSize: 24,
				fontStyle: "bold",
	                	sugesstedMax: 100,
		                min: 0,
        		        stepSize: 1 
            		}	

                    } ],
                }
            }
        });
    };

    document.getElementById('randomizeData').addEventListener('click', function() {
        barChartData.datasets.forEach(function(dataset, i) {
            dataset.data = dataset.data.map(function() {
                return randomScalingFactor();
            });
        });
        window.myBar.update();
    });
</script>
</body>

</html>

