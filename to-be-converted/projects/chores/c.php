<?php
require('private/authenticate.php');
// http://www.colorpicker.com/C20808 - click Analogic
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title>Chore Details</title>
<?php
include('private/header.php');
if (USER_IS_MOBILE) {
?>
<style>
select {
	font-size: 20px;
}
</style>
<?php
} else {
?>
<style>
select {
	font-size: 16px;
}
</style>
<?php
}
?>
<style>
select {
	width:100%;
	margin:2px;
}
</style>
<script>
function submitChoreForm() {
	console.log("Button pressed.");
	if ($("#inputDateTime").val() === "") {
		alert("You must fill out the time that the chore was done.");
	} else {
		$("#inputDateTime").prop("disabled", false);
		$("#formSubmitChore").submit();
	}
}

$(function() {
	// Check whether datetime-local input is supported by the browser
	if (Modernizr.inputtypes['datetime-local']) {
		
	} else {
		console.log("Input type=\"datetime-local\" is not supported by this browser");
		$("#inputDateTime").val($("#inputDateTime").val().replace("T"," "));
	}
});
</script>
</head>
<body>
<?php
$con = mysqli_connect("database2014.db.9953790.hostedresource.com","database2014","Neri988!","database2014");
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: ".mysqli_connect_errno();
	// TODO: Break?
}
if (!isset($_GET['cid']) || empty($_GET['cid'])) {
	$_GET['cid'] = -1;
}
$choreID = $_GET['cid'];
$sql = "SELECT chores.name, chores.description, chore_assignments.due_time, residents.id, residents.first_name, MAX(chore_history.time_completed) AS last_done FROM chores LEFT JOIN chore_assignments ON (chore_assignments.chore_id = chores.id) LEFT JOIN residents ON (residents.id = chore_assignments.resident_id) LEFT JOIN chore_history ON (chores.id = chore_history.chore_id) WHERE chores.id = $choreID";
$rows = mysqli_query($con, $sql);
$row = $rows->fetch_assoc();
if ($row) {
	$assignedToUser = ($row['id'] == USER_ID);
	$dueTime = strtotime($row['due_time']);
	$due = date('l, F j \a\t g:i a', $dueTime);
?>
<div class="divHeader">
	<h2><?php echo $row['name']; ?></h2>
</div>
<p><b>Description: </b><?php echo $row['description']; ?></p>
<p><b>Last Done: </b><?php echo $row['last_done']; ?></p>
<p><b>Due: </b><?php echo $due; ?></p>
<hr />
<form id="formSubmitChore" action="api/submitChore.php" method="post">
	<label for="inputDateTime">When was the chore done?</label>
	<br />
	<input type="datetime-local" value="<? echo date("Y-m-d\TH:i:s", time()+7200); ?>" id="inputDateTime" name="inputDateTime">
	<br />
	<input type="text" value="<?php echo $choreID; ?>" id="inputChoreID" name="inputChoreID" hidden>
	<input type="text" value="<?php echo USER_ID; ?>" id="inputResidentID" name="inputResidentID" hidden>
<?php
	if ($assignedToUser) {
		
	} else {
		echo '<p>This chore was assigned to '.$row['first_name'].', <i>not</i> you.</p>';
		echo '<p><b>Did you do it instead of '.$row['first_name'].'?</b></p>';
	}	
?>
<hr />
<button name="cid" value="<?echo$choreID?>" type="button" onclick="submitChoreForm();" class="centerMe">I, <?echo USER_NAME?>, did this chore at the above time.</button>
</form>
<?php
} else {
	$choreOptions = '<option value="" disabled selected>Select a chore</option>';
	$sql = "SELECT id, name FROM chores WHERE active=1";
	$rows = mysqli_query($con,$sql);
	while ($row = mysqli_fetch_assoc($rows)) {
		$choreOptions .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
	}
?>
<div class="divHeader">
	<h2>Select a chore</h2>
</div>
<select id="cid" name="cid" onchange="window.location.replace('c.php?cid='+this.value);">
	<?php echo $choreOptions; ?>
</select>
<?php
}
?>


</body>
