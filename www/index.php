<?php
$mysql = new mysqli("localhost", "root", "root", "speedtest");

$results = $mysql->query("SELECT * FROM speedtest ORDER BY `ts` DESC");
date_default_timezone_set("UTC");
$results_arr = $results->fetch_all(MYSQLI_ASSOC);
foreach ($results_arr as $i => $row) {
	$date = new DateTime("@" . $row["ts"]);
	$date->setTimeZone(new DateTimeZone("Asia/Kuala_Lumpur"));
	$results_arr[$i]["ts"] = $date->format("d/m/y h:i A");
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta chatset="utf-8">
	<title>Internet Speed</title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

	<script src="https://www.google.com/jsapi"></script>
	<script>
		google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(function() {
			var data = google.visualization.arrayToDataTable([
				['Time', 'Ping Time', 'Download Speed', 'Upload Speed'],
				<?php foreach (array_reverse($results_arr) as $row): ?>
				["<?php echo $row["ts"]; ?>", <?php echo $row["ping"]; ?>, <?php echo $row["dl"]; ?>, <?php echo $row["ul"]; ?>],
				<?php endforeach; ?>
			]);

			var options = {
				title: "Internet speeds and ping time",
				curveType: "function",
				vAxes: [{
					title: "Speed (Mbit/s)"
				}, {
					title: "Ping time (msec)"
				}],
				series: {
					0: {targetAxisIndex: 1}
				}
			};

			var chart = new google.visualization.LineChart(document.getElementById('chart'));
			chart.draw(data, options);
		});

	</script>
</head>
<body>
	<div class="container">
		<div id="chart"></div>
		<table class="table">
			<thead>
				<th>Time</th>
				<th>Ping</th>
				<th>DL</th>
				<th>UL</th>
			</thead>
			<tbody>
				<?php foreach($results_arr as $row): ?>
					<tr<?php if ($row["dl"] < 4):?> class="text-danger"<?php endif; ?>>
						<td><?php echo $row["ts"]; ?></td>
						<td><?php echo $row["ping"]; ?></td>
						<td><?php echo $row["dl"]; ?></td>
						<td><?php echo $row["ul"]; ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</body>