<?php
require('private/authenticate.php');
// http://www.colorpicker.com/C20808 - click Analogic
// TODO: A "News Feed" feature with latest updates and upcoming announcements
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title>The Ocho Chores Home</title>
<?php include('private/header.php'); ?>
<script type="text/javascript">
var isLocalStorageAvailable = Modernizr.localstorage;

function assignChore(selectElement, choreID) {
	var residentID = selectElement.options[selectElement.selectedIndex].value;
	var httpRequest;
	if (window.XMLHttpRequest) {
		httpRequest = new XMLHttpRequest();
	} else {
		httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
	}
	httpRequest.onreadystatechange = function() {
		if (httpRequest.readyState==4 && httpRequest.status==200) {
			var jsonObject = JSON.parse(httpRequest.responseText);
			if (typeof jsonObject.error !== "undefined") {
				console.log("Error:\t"+jsonObject.error);
			} else if (typeof jsonObject.success !== "undefined") {			
				// If things went okay, un-color the row
				console.log("Success:\t"+jsonObject.success);
				selectElement.parentNode.style.backgroundColor = "transparent";
				// TODO: Fill in the breakdown table again
				
				// tableBreakdown
			}
		}
	};
	httpRequest.open("GET","api/assignChore.php?choreID="+choreID+"&residentID="+residentID,true);
	httpRequest.send();
}

function assignManager(selectElement, assignmentID) {
	var managerID = selectElement.options[selectElement.selectedIndex].value;
	var httpRequest;
	if (window.XMLHttpRequest) {
		httpRequest = new XMLHttpRequest();
	} else {
		httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
	}
	httpRequest.onreadystatechange = function() {
		if (httpRequest.readyState==4 && httpRequest.status==200) {
			var jsonObject = JSON.parse(httpRequest.responseText);
			if (typeof jsonObject.error !== "undefined") {
				console.log("Error:\t"+jsonObject.error);
			} else if (typeof jsonObject.success !== "undefined") {			
				// If things went okay, un-color the row
				console.log("Success:\t"+jsonObject.success);
				selectElement.parentNode.style.backgroundColor = "transparent";
				// TODO: Fill in the breakdown table again? Only if I add a managing column
				
				// tableBreakdown
			}
		}
	};
	httpRequest.open("GET","api/assignManager.php?assignmentID="+assignmentID+"&managerID="+managerID,true);
	httpRequest.send();
}

function updateDueTime(inputElement, assignmentID) {
	var dueTime = inputElement.value.trim().replace('T',' ');
	if (dueTime.length === 0) {
		dueTime = "NULL";
	}
	if (window.XMLHttpRequest) {
		httpRequest = new XMLHttpRequest();
	} else {
		httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
	}
	httpRequest.onreadystatechange = function() {
		if (httpRequest.readyState==4 && httpRequest.status==200) {
			console.log(httpRequest.responseText);
			var jsonObject = JSON.parse(httpRequest.responseText);
			if (typeof jsonObject.error !== "undefined") {
				console.log("Error:\t"+jsonObject.error);
			} else if (typeof jsonObject.success !== "undefined") {			
				// If things went okay, un-color the row
				console.log("Success:\t"+jsonObject.success);
				inputElement.parentNode.style.backgroundColor = "transparent";
				if (dueTime === "NULL") {
					inputElement.parentNode.style.backgroundColor = "#C2C208";
				}
			}
		}
	};
	httpRequest.open("GET","api/updateDueTime.php?assignmentID="+assignmentID+"&dueTime="+dueTime,true);
	httpRequest.send();
}
$(function() {
	// Check whether datetime-local input is supported by the browser
        if (Modernizr.inputtypes['datetime-local']) {
                
        } else {
                console.log("Input type=\"datetime-local\" is not supported by this browser");
                $("#inputDueTime").attr("placeholder", "yyyy-mm-ddThh:mm:ss");
        }
});
</script>
</head>
<body>
<div class="divHeader">
	<h1>The Ocho Chore Chart</h1>
</div>
<?php
$con = mysqli_connect("database2014.db.9953790.hostedresource.com","database2014","Neri988!","database2014");
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: ".mysqli_connect_errno();
} else {
	// Chore-breakdown table
	$sql = "SELECT chore_assignments.resident_id, residents.first_name, residents.last_name, COUNT(chore_assignments.resident_id) AS count FROM chore_assignments LEFT JOIN residents ON residents.id = chore_assignments.resident_id GROUP BY chore_assignments.resident_id";
	$resultBreakdown = mysqli_query($con,$sql);
	$breakdown = '';
	while ($row = mysqli_fetch_assoc($resultBreakdown)) {
		$breakdown .= '<tr><td><a href="/u.php?n='.$row['first_name'].'">'.$row['first_name'].' '.$row['last_name'].'</a></td><td>'.$row['count'].'</td></tr>';
	}
	echo '<div class="divHeader"><h2>Chore assignment breakdown</h2></div>';
	echo '<table id="tableBreakdown" style="margin-left:auto;margin-right:auto;">';
	echo '<tr><th>Name</th><th>Chore count</th></tr>';
	echo $breakdown;
	echo '</table>';
	
	// Weekly meeting schedule table
	// $resultDinners = mysqli_query($con, "SELECT * FROM ");
	echo '<div class="divHeader"><h2><a href="/weeklyMeetings.php">Weekly Meetings page</a></h2></div>';
	// Birthdays page
	echo '<div class="divHeader"><h2><a href="/birthdays.php">Birthdays page</a></h2></div>';
	// Residents stay duration page
	echo '<div class="divHeader"><h2><a href="/residents.php">Residents Stay Durations page</a></h2></div>';
	
	$disabled = (USER_ID!=='1')?' disabled':'';
	
	// Chore-assignments table
	$resultChores = mysqli_query($con,"SELECT chores.name, chores.description, chores.id AS chore_id, chore_assignments.resident_id, chore_assignments.manager_id, chore_assignments.id, chore_assignments.due_time FROM chores LEFT JOIN chore_assignments ON (chore_assignments.chore_id = chores.id) WHERE chores.active = 1 ORDER BY chores.rotation_id, chores.name");
	$assignToPrefix = '<select onchange="assignChore(this, ';
	$assignToSuffix = ');"'.$disabled.'><option value="" disabled selected>Select resident</option>';
	$managedByPrefix = '<select onchange="assignManager(this, ';
	$managedBySuffix = ');"'.$disabled.'><option value="" disabled selected>Select manager</option>';
	$dueTimePrefix = '<input type="datetime-local" id="inputDueTime" onchange="updateDueTime(this, ';
	$dueTimeSuffix = ');" '.$disabled;
	$resultResidents = mysqli_query($con,"SELECT first_name, last_name, id FROM residents WHERE move_out_date IS NULL ORDER BY last_name, first_name");
	$residents = array();
	while ($row = mysqli_fetch_assoc($resultResidents)) {
		array_push($residents, $row);
	}
	while ($row = mysqli_fetch_assoc($resultChores)) {
		$residentsOptions = '';
		$assignedResidentName = '';
		foreach ($residents as $r) {
			$residentsOptions .= '<option value="'.$r['id'].'" '.(($r['id'] == $row['resident_id']) ? 'selected' : '').'>'.$r['first_name'].' '.(USER_IS_MOBILE?substr($r['last_name'],0,1).'.':$r['last_name']).'</option>';
			if ($r['id'] == $row['resident_id']) {
				$assignedResidentName = $r['first_name'].' '.(USER_IS_MOBILE?substr($r['last_name'],0,1).'.':$r['last_name']);
			}
		}
		$assignTo = $assignToPrefix . $row['chore_id'] . $assignToSuffix . $residentsOptions . '</select>';
		$managersOptions = '';
		foreach ($residents as $r) {
			$managersOptions .= '<option value="'.$r['id'].'" '.(($r['id'] == $row['manager_id']) ? 'selected' : '').'>'.$r['first_name'].' '.$r['last_name'].'</option>';
		}
		$manager = $managedByPrefix.$row['id'].$managedBySuffix.$managersOptions.'</select>';
		$timeValue = ($row['due_time']===NULL)?'':' value="'.str_replace(' ','T',$row['due_time']).'" ';
		$assignedTimeText = ($row['due_time']===NULL)?'':date("l, F jS",strtotime($row['due_time']));
		$dueTime = $dueTimePrefix.$row['id'].$dueTimeSuffix.$timeValue.' />';
		$unassigned = ($row['resident_id'] === NULL)?' style="background-color:#C20865;" ':'';
		$unmanaged = ($row['manager_id'] === NULL)?' style="background-color:#C2C208;" ':'';
		$noDueTime = (strlen($timeValue) > 0)?'':' style="background-color:#C2C208;" ';
		if (USER_IS_MOBILE) {
			$activeChores .= '<h3><a href="c.php?cid='.$row['chore_id'].'">'.$row['name'].'</a></h3><p><b>Assigned to:</b> '.($unassigned?'<i>Nobody</i></p>':$assignedResidentName.'</p><p><b>Due on:</b> '.($noDueTime?'<i>not assigned</i>':$assignedTimeText).'</p>').'<hr />';
		} else {
			$activeChores .= '<tr><td><a href="c.php?cid='.$row['chore_id'].'">'.$row['name'].'</a></td><td>'.$row['description'].'</td><td'.$unassigned.'>'.$assignTo.'</td><td'.$unmanaged.'>'.$manager.'</td><td'.$noDueTime.'>'.$dueTime.'</td></tr>';
		}
	}
	echo '<div class="divHeader"><h2>Chore assignments</h2></div>';
	if (USER_IS_MOBILE) {
		echo $activeChores;
	} else {
		echo '<table id="tableAssignChores" style="margin-left:auto;margin-right:auto;">';
		echo '<tr><th>Chore</th><th>Description</th><th>Assigned to</th><th>Managed by</th><th>Due Date</th></tr>';
		echo $activeChores;
		echo '</table>';
	}
}
mysqli_close($con);
?>
<form id="formLogOut" action="logout.php" method="post">
	<input type="text" value="1" id="logMeOut" name="logMeOut" hidden />
	<br />
	<input type="submit" value="Log Out" class="centerMe largerFont">
</form>
</body>
</html>
