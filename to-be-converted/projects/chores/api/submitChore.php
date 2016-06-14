<?php
require('../private/authenticate.php');
if (isset($_POST['inputDateTime']) && isset($_POST['inputChoreID']) && isset($_POST['inputResidentID'])) {
	$timeCompleted = $_POST['inputDateTime'];
	$timeCompleted = str_replace('T', ' ', $timeCompleted);
	$choreID = $_POST['inputChoreID'];
	$residentID = $_POST['inputResidentID'];
} else {
	echo '<h1 style="color:red;">Error: no information sent.</h1>';
	die();
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title>Submitting Chore</title>
<?php include('../private/header.php'); ?>
</head>
<body>

<?php
$con = mysqli_connect("database2014.db.9953790.hostedresource.com","database2014","Neri988!","database2014");
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: ".mysqli_connect_errno();
} else {
	$soFarSoGood = false;
	// INSERT statement for chore_history
	$sql = "INSERT INTO chore_history (resident_id, chore_id, time_completed) VALUES ($residentID, $choreID, \"$timeCompleted\")";
	$result = mysqli_query($con,$sql);
	if ($result) {
		$soFarSoGood = true;
	} else {
		$soFarSoGood = false;
	}
	// UPDATE statement for chore_assignments (due date based on frequency in chores table)	
	// First, get the frequency that this chore should be done at
	$sql = "SELECT frequency FROM chores WHERE id = $choreID";
	$result = mysqli_query($con,$sql);
	$row = mysqli_fetch_assoc($result);
	$days = $row['frequency'];
	// Then, check on the last due date
	$sql = "SELECT due_time, id FROM chore_assignments WHERE chore_id=$choreID";
	$result = mysqli_query($con,$sql);
	$row = mysqli_fetch_assoc($result);
	$dueTime = $row['due_time'];
	$assignmentID = $row['id'];
	// Then, generate a next due date
	$dueDateTime = strtotime($dueTime);
	$nextDueTime = date("Y-m-d G:i:s",strtotime(date("Y-m-d G:i:s", $dueDateTime).' +'.$days.' day'.(($days==1)?'':'s')));
	if (time() >= $nextDueTime) {
		$nextDueTime = date("Y-m-d",strtotime(date("Y-m-d G:i:s", time()).' +'.$days.' day'.(($days==1)?'':'s')))." ".end(explode(" ",$dueTime));
	}
	$sql = "UPDATE chore_assignments SET due_time = \"".$nextDueTime."\" WHERE id=$assignmentID";
	$result = mysqli_query($con,$sql);
	if ($result) {
		$soFarSoGood = $soFarSoGood;
	} else {
		$soFarSoGood = false;
	}
}
mysqli_close($con);

if ($soFarSoGood) {
	echo 'It worked';
} else {
	echo 'It did <i>not</i> work';
}

?>
</body>
</html>
