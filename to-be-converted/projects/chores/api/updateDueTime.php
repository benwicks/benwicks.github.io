<?php
require('../private/authenticate.php');
header('Content-Type: application/json');

$assignmentID = isset($_GET['assignmentID']) ? strip_tags($_GET['assignmentID']) : '';
$dueTime = isset($_GET['dueTime']) ? strip_tags($_GET['dueTime']) : '';

$con = mysqli_connect("database2014.db.9953790.hostedresource.com","database2014","Neri988!","database2014");
if (mysqli_connect_errno()) {
	echo '{"error":"Failed to connect to MySQL: '.mysqli_connect_errno().'"}';
	mysqli_close($con);
	return;
} else {
	$resultChores = mysqli_query($con,"UPDATE chore_assignments SET due_time = \"$dueTime\" WHERE id = $assignmentID") or die('{"error":"'.mysqli_error($con).'"}');
	mysqli_close($con);
	echo '{"success":"It worked. Updated chore assignment ('.$assignmentID.') to due time ('.$dueTime.')"}';
	return;
}
?>