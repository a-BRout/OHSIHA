<?php

// Initialize the session
session_start();

// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("location: login.php");
  exit;
}
require_once __DIR__.'/functions.php';
session_start();

$result = Get_table($user = $_SESSION['username']);

$litrat = array();
$hinta = array();
foreach ($result as $value) {
    //$litrat[] = $value[1];
    $kulutus = $value[1]/($value[3]/100);
    $litrat[] = array("label" => $value[0], "y" => $kulutus);
    $hinta[] = array("label" => $value[0], "y" => $value[2]/$value[1]);
}


?>

<!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	title:{
		text: "Fuel Consumption per 100km and Cost per litre"
	},
	axisY: {
		title: "Litres and Euros",
		valueFormatString: "##",
		suffix: "",
		prefix: ""
	},
	data: [{
		type: "spline",
		markerSize: 5,
		xValueFormatString: "DD/MM/YYYY",
		yValueFormatString: "##.00",
		xValueType: "dateTime",
		dataPoints: <?php echo json_encode($litrat, JSON_NUMERIC_CHECK); ?>
	},{
    type: "spline",
    markerSize: 5,
    xValueFormatString: "DD/MM/YYYY",
    yValueFormatString: "##.00",
    xValueType: "dateTime",
    dataPoints: <?php echo json_encode($hinta, JSON_NUMERIC_CHECK); ?>
    }]
});

chart.render();

}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>
