<?php
require('private/authenticate.php');
// http://www.colorpicker.com/C20808 - click Analogic
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title>Residents</title>
<?php include('private/header.php'); ?>
<style>
.chart {
	display: inline-block;
	font: 10px sans-serif;
	text-align: right;
	padding: 2px;
	color: white;
}
.current {
	background-color: steelblue;
}
.old {
	background-color: red;
}
#tableDurations td {
	margin: 0;
	white-space: nowrap;
}
#tableDurations td:first-child {
	position: absolute;
	width: 5em;
	left: 0;
	top: auto;
	border-top-width: 3px;
	margin-top: -3px;	
}
#divScroll {
	width: 90%;
	overflow-x: scroll;
	margin-left: 5em;
	overflow-y: visible;
	padding-bottom: 1px;
}
</style>
</head>
<body>
<?php
$con = mysqli_connect("database2014.db.9953790.hostedresource.com","database2014","Neri988!","database2014");
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: ".mysqli_connect_errno();
} else {
	// SELECT the birthdays
	$sql = "SELECT id, first_name, move_in_date, move_out_date FROM residents ORDER BY move_in_date ASC";
	$result = mysqli_query($con, $sql);
	$residents = array();
	while ($row = mysqli_fetch_assoc($result)) {
		array_push($residents, $row);
	}
}
mysqli_close($con);
?>
<h1>Residents</h1>
<br /><br />
<div id="divScroll"> 
<table id="tableDurations">
<?
$maxStayDays = 0;
foreach ($residents as $r) {
	if (isset($r['move_out_date'])) {
		$stayDays = floor((strtotime($r['move_out_date'])-strtotime($r['move_in_date']))/(60*60*24));
	} else {
		$stayDays = floor((time()-strtotime($r['move_in_date']))/(60*60*24));
	}
	if ($stayDays > $maxStayDays) {
		$maxStayDays = $stayDays;
	}
}
$ratio = 1000/$maxStayDays;
$earliestMoveInDate = strtotime($residents[0]['move_in_date']);
$now = time();
$totalDateDiff = $now - $earliestMoveInDate;
$totalDaysDiff = floor($maxDateDiff/(60*60*24));
foreach ($residents as $r) {
	$parts = explode('-', $r['move_in_date']);
	$year = $parts[0];
	$month = $parts[1];
	$day = $parts[2];
	$moveInDate = strtotime($r['move_in_date']);
	$marginLeft = round(floor(($moveInDate - $earliestMoveInDate)/(60*60*24))*$ratio).'px';
	if (isset($r['move_out_date'])) {
		$moveOutDate = strtotime($r['move_out_date']);
		$duration = floor(($moveOutDate - $moveInDate)/(60*60*24));
		$currentOrOld = 'chart old';
	} else {
		$duration = floor(($now - $moveInDate)/(60*60*24));
		$currentOrOld = 'chart current';
	}
	$width = round($duration*$ratio).'px';
	echo '<tr><td><b>'.$r['first_name'].' '.$r['last_name'].'</b></td>';
	echo '<td><span title="Move In: '.$r['move_in_date'].'&#10;Move Out: '.$r['move_out_date'].'" style="margin-left:'.$marginLeft.';width:'.$width.'" class="'.$currentOrOld.'">'.$duration.' days</span></td></tr>';
}
?>
</table>
</div>
</body>
</html>
