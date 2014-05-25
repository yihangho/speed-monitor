<?php
require_once("commons/mysql.php");
require_once("commons/config.php");
require_once("paginator.php");


// Get total number of rows to prepare for pagination
$results = $mysql->query("SELECT COUNT(*) FROM speedtest");
$t = $results->fetch_all();
$num_records = $t[0][0];
$num_pages = ceil($num_records / RESULTS_PER_PAGE);

// Handle pagination
$current_page = intval($_GET["page"]);
if ($current_page > $num_pages) {
	$current_page = $num_pages;
} else if ($current_page < 1) {
	$current_page = 1;
}
$per_page     = RESULTS_PER_PAGE;
$first_index  = ($current_page-1) * RESULTS_PER_PAGE;

$results = $mysql->query("SELECT * FROM speedtest ORDER BY `ts` DESC LIMIT $first_index, $per_page");
date_default_timezone_set("UTC");
$results_arr = $results->fetch_all(MYSQLI_ASSOC);
foreach ($results_arr as $i => $row) {
	$date = new DateTime("@" . $row["ts"]);
	$date->setTimeZone(new DateTimeZone("Asia/Kuala_Lumpur"));
	$results_arr[$i]["ts"] = $date->format("d/m/y h:i A");
}

$paginator = new Paginator($current_page, $num_pages);
?>
<!DOCTYPE html>
<html>
	<!-- <?php echo $current_page; ?> -->
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
				<th>Server</th>
			</thead>
			<tbody>
				<?php foreach($results_arr as $row): ?>
					<tr<?php if ($row["dl"] < LOW_SPEED_CUTOFF):?> class="text-danger"<?php endif; ?>>
						<td><?php echo $row["ts"]; ?></td>
						<td><?php echo $row["ping"]; ?></td>
						<td><?php echo $row["dl"]; ?></td>
						<td><?php echo $row["ul"]; ?></td>
						<td><?php echo ($row["server_id"] ? $row["server_id"] : "N/A"); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<?php echo $paginator->get_output(); ?>
	</div>
</body>
