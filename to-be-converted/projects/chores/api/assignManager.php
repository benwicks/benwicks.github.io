<?php
require('../private/authenticate.php');
header('Content-Type: application/json');

$assignmentID = isset($_GET['assignmentID']) ? strip_tags($_GET['assignmentID']) : '';
$managerID = isset($_GET['managerID']) ? strip_tags($_GET['managerID']) : '';

$con = mysqli_connect("database2014.db.9953790.hostedresource.com","database2014","Neri988!","database2014");
if (mysqli_connect_errno()) {
	echo '{"error":"Failed to connect to MySQL: '.mysqli_connect_errno().'"}';
	mysqli_close($con);
	return;
} else {
	$resultChores = mysqli_query($con,"UPDATE chore_assignments SET manager_id = $managerID WHERE id = $assignmentID") or die('{"error":"'.mysqli_error($con).'"}');
	mysqli_close($con);
	echo '{"success":"It worked. Assigned chore assignment ('.$assignmentID.') to manager ('.$managerID.')"}';
	return;
}
?>