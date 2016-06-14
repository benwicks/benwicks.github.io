<?php
require('../private/authenticate.php');
header('Content-Type: application/json');

$choreID = isset($_GET['choreID']) ? strip_tags($_GET['choreID']) : '';
$residentID = isset($_GET['residentID']) ? strip_tags($_GET['residentID']) : '';
$oneWeek = date("Y-m-d H:i:s",strtotime("+1 week"));
$m = date("m",strtotime($oneWeek));
$d = date("j",strtotime($oneWeek));
$y = date("Y",strtotime($oneWeek));
// $dueTime = date("Y-m-d H:i:s", rtrim(mktime(20,0,0,$m,$d,$y,-1)));
$dueTime = rtrim(mktime(20,0,0,$m,$d,$y,-1));

$con = mysqli_connect("database2014.db.9953790.hostedresource.com","database2014","Neri988!","database2014");
if (mysqli_connect_errno()) {
	echo '{"error":"Failed to connect to MySQL: '.mysqli_connect_errno().'"}';
	mysqli_close($con);
	return;
} else {
	$resultChores = mysqli_query($con,"INSERT INTO chore_assignments (resident_id, chore_id, due_time, status) VALUES ($residentID, $choreID, FROM_UNIXTIME($dueTime), 'I') ON DUPLICATE KEY UPDATE resident_id = $residentID, due_time = FROM_UNIXTIME($dueTime), status = 'I'") or die('{"error":"'.mysqli_error($con).'"}');
	mysqli_close($con);
	echo '{"success":"It worked. Assigned chore ('.$choreID.') to resident ('.$residentID.')"}';
	return;
}
?>