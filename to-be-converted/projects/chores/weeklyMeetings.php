<?php
require('private/authenticate.php');
// http://www.colorpicker.com/C20808 - click Analogic
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title>Weekly Meetings</title>
<?php include('private/header.php'); ?>
<script type="text/javascript">
$(function() {
	if (Modernizr.inputtypes['datetime-local']) {
		
	} else {
		$("#inputBibleStudyTime").attr("placeholder", "yyyy-mm-ddThh:mm:ss");
		$("#inputMealTime").attr("placeholder", "yyyy-mm-ddThh:mm:ss");
	}
});
</script>
</head>
<body>
<?php
$con = mysqli_connect("database2014.db.9953790.hostedresource.com","database2014","Neri988!","database2014");

// Check if anything was posted
if (isset($_POST['inputMealTime']) && isset($_POST['selectTeam']) && isset($_POST['inputDescription'])) {
	$time = $_POST['inputMealTime'];
	$team = $_POST['selectTeam'];
	$description = $_POST['inputDescription'];
	if (!empty($time) && !empty($team) && !empty($description)) {
		// Add the meal to the database
		$time = strtotime($time);
		$time = date("Y-m-d H:i:s", $time);
		$description = mysqli_real_escape_string($con, lcfirst($description));
		$sql = "INSERT INTO weekly_meals (time, description, team_id) VALUES (\"$time\", \"$description\", $team)";
		?>
		<script>
			$(function() {
		<?php
		if (mysqli_query($con, $sql)) {
			echo '$("#spanNotificationRecordAdded").html("Added meal successfully!");';
		} else {
			echo '$("#spanNotificationRecordAdded").html("Failed to add meal.");';
		}
		echo '$("#notificationRecordAdded").fadeIn("slow").delay(3000).fadeOut();';
		?>
			});
		</script>
		<?php
	} else {
		echo '<script>alert("You must fill out each field.");</script>';
	}
} else if (isset($_POST['inputBibleStudyTime']) && isset($_POST['selectBibleStudyTeam']) && isset($_POST['inputBibleStudyDescription'])) {
	$time = $_POST['inputBibleStudyTime'];
	$team = $_POST['selectBibleStudyTeam'];
	$description = $_POST['inputBibleStudyDescription'];
	if (!empty($time) && !empty($team) && !empty($description)) {
		// Add the bible study to the database
		$time = strtotime($time);
		$time = date("Y-m-d H:i:s", $time);
		$description = mysqli_real_escape_string($con, lcfirst($description));
		$sql = "INSERT INTO weekly_bible_studies (time, description, team_id) VALUES (\"$time\", \"$description\", $team)";
		?>
		<script>
			$(function() {
		<?php
		if (mysqli_query($con, $sql)) {
			echo '$("#spanNotificationRecordAdded").html("Added bible study successfully!");';
		} else {
			echo '$("#spanNotificationRecordAdded").html("Failed to add bible study.");';
		}
		echo '$("#notificationRecordAdded").fadeIn("slow").delay(3000).fadeOut();';
		?>
			});
		</script>
		<?php
	} else {
		echo '<script>alert("You must fill out each field.");</script>';
	}
} else {
?>
<script>
$(function() {
	$("#notificationRecordAdded").hide();
});
</script>
<?php
}

if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: ".mysqli_connect_errno();
} else {
	// weekly meeting rotation table	
	// Get the ID of the last team to make the meal
	$sql = "SELECT team_id, resident_id FROM weekly_meals WHERE time < NOW() ORDER BY time DESC LIMIT 1";
	$lastTeam = mysqli_query($con, $sql);
	$row = mysqli_fetch_assoc($lastTeam);
	if (is_null($row['team_id'])) {
		$lastResID = $row['resident_id'];
		$lastResTeamID = mysqli_query($con, "SELECT `order` FROM weekly_meeting_teams WHERE (resident1_id = ".$lastResID." OR resident2_id = ".$lastResID.") AND active = 1");
		$row = mysqli_fetch_assoc($lastResTeamID);
		$lastTeamOrder = $row['order'];
	} else {
		$lastTeamID = $row['team_id'];
		$lastTeam = mysqli_query($con, "SELECT `order` FROM weekly_meeting_teams WHERE id = ".$lastTeamID);
		$row = mysqli_fetch_assoc($lastTeam);
		$lastTeamOrder = $row['order'];
	}
	// Get the maximum team ID
	$sql = "SELECT MAX(`order`) AS max, MIN(`order`) AS min FROM weekly_meeting_teams WHERE active=1";
	$rows = mysqli_query($con, $sql);
	$row = mysqli_fetch_assoc($rows);
	$maxTeamOrder = $row['max'];
	$minTeamOrder = $row['min'];
	if ($lastTeamOrder == $maxTeamOrder) {
		$nextMealTeamID = $minTeamOrder;
		$nextBibleStudyTeamID = $nextMealTeamID + 1;
	} else {
		$nextMealTeamID = $lastTeamOrder + 1;
		if ($nextMealTeamID == $maxTeamOrder) {
			$nextBibleStudyTeamID = $minTeamOrder;
		} else {
			$nextBibleStudyTeamID = $nextMealTeamID + 1;
		}
	}
	$sql = "SELECT weekly_meeting_teams.order AS id, r1.first_name AS r1_first_name, r2.first_name AS r2_first_name FROM weekly_meeting_teams LEFT JOIN residents AS r1 ON (weekly_meeting_teams.resident1_id = r1.id) LEFT JOIN residents AS r2 ON (weekly_meeting_teams.resident2_id = r2.id) WHERE weekly_meeting_teams.active = 1 ORDER BY weekly_meeting_teams.order ASC";
	$meals = mysqli_query($con, $sql);
	$rotation = '';
	while ($row = mysqli_fetch_assoc($meals)) {
		$id = $row['id'];
		if (is_null($row['r1_first_name'])) {
			$members = $row['r2_first_name'];
		} else if (is_null($row['r2_first_name'])) {
			$members = $row['r1_first_name'];
		} else {
			$members = $row['r1_first_name'] . ' and ' . $row['r2_first_name'];
		}
		if ($id == $nextMealTeamID) {
			$highlight = ' style="background-color:#65C208;"';
		} else if ($id == $nextBibleStudyTeamID) {
			$highlight = ' style="background-color:yellow;"';
		} else {
			$highlight = '';
		}
		$rotation .= '<tr'.$highlight.'><td>'.$id.'</td><td>'.$members.'</td></tr>';
	}
	echo '<div class="divHeader"><h2>Weekly Meeting Rotation Schedule</h2></div>';
	echo '<div class="centerForm"><p>The row highlighted in <span style="background-color:#65C208;">green</span> is responsible for <b>making the meal</b> next Sunday.<br />The row highlighted in <span style="background-color:yellow;">yellow</span> is responsible for <b>leading the Bible study</b> next Sunday.</p></div>';
	echo '<table id="tableRotation" style="margin-left:auto;margin-right:auto;">';
	echo '<tr><th>Team #</th><th>Members</th></tr>';
	echo $rotation;
	echo '</table>';
	echo '<br />';
	
	
	// weekly meeting meals table
	$sql = "SELECT weekly_meals.time, weekly_meals.description, (CASE WHEN (weekly_meals.resident_id IS NULL) THEN r1.first_name END) AS r1_first_name, (CASE WHEN (weekly_meals.resident_id IS NULL) THEN r1.last_name END) AS r1_last_name, (CASE WHEN (weekly_meals.resident_id IS NULL) THEN r2.first_name END) AS r2_first_name, (CASE WHEN (weekly_meals.resident_id IS NULL) THEN r2.last_name END) AS r2_last_name, (CASE WHEN (weekly_meals.team_id IS NULL) THEN residents.first_name END) AS first_name, (CASE WHEN (weekly_meals.team_id IS NULL) THEN residents.last_name END) AS last_name FROM weekly_meals LEFT JOIN residents ON (weekly_meals.resident_id = residents.id) LEFT JOIN weekly_meeting_teams ON (weekly_meals.team_id = weekly_meeting_teams.id) LEFT JOIN residents AS r1 ON (weekly_meeting_teams.resident1_id = r1.id) LEFT JOIN residents AS r2 ON (weekly_meeting_teams.resident2_id = r2.id) ORDER BY weekly_meals.time DESC";
	$meals = mysqli_query($con, $sql);
	$breakdown = '';
	while ($row = mysqli_fetch_assoc($meals)) {
		$name = '';
		if (is_null($row['r1_first_name'])) {
			$name = $row['first_name'];
		} else {
			$name = $row['r1_first_name'] . ' and ' . $row['r2_first_name'];
		}
		$time = strtotime($row['time']);
		if ($time < time()) {
			$verb = ' made ';
		} else {
			$verb = ' will make ';
		}
		$time = date('M j Y \a\t g:i A', $time);
		$breakdown .= '<tr><td>'.$time.'</td><td>'.$name.$verb.$row['description'].'</td></tr>';
	}
	echo '<div class="divHeader"><h2>Weekly Meeting Meal History</h2></div>';
	echo '<table id="tableBreakdown" style="margin-left:auto;margin-right:auto;">';
	echo '<tr><th>Date</th><th>Description</th></tr>';
	echo $breakdown;
	echo '</table>';
	
	echo '<br />';
	
	// A form for adding a meal (just for Ben!)
	if (USER_ID == '1') {
		$sql = "SELECT weekly_meeting_teams.id, CONCAT(r1.first_name,' and ',r2.first_name) AS name FROM weekly_meeting_teams LEFT JOIN residents AS r1 ON (weekly_meeting_teams.resident1_id = r1.id) LEFT JOIN residents AS r2 ON (weekly_meeting_teams.resident2_id = r2.id) WHERE weekly_meeting_teams.active = 1";
		$teams = mysqli_query($con, $sql);
		$options = '<option value="" disabled selected>Select a team</option>';
		while ($row = mysqli_fetch_assoc($teams)) {
			$options .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
		}
		echo '<div class="divHeader"><h2>Add a Meal</h2></div>';
		echo '<div class="centerForm">';
		echo '<form name="formAddMeal" action="weeklyMeetings.php" method="post">';
		echo '<label for="inputMealTime">When was the meal?</label>';
		echo '<br />';
		echo '<input id="inputMealTime" name="inputMealTime" title="Please specify when the meal occurred." type="datetime-local" required></input>';
		echo '<br />';
		echo '<label for="selectTeam">Who made the meal?</label>';
		echo '<br />';
		echo '<select id="selectTeam" name="selectTeam" title="Please specify who prepared the meal." required>';
		echo $options;
		echo '</select>';
		echo '<br />';
		echo '<label for="inputDescription">What was in the meal?</label>';
		echo '<br />';
		echo '<textarea id="inputDescription" name="inputDescription" maxlength="300" placeholder="Describe the meal" title="Please specify a description of the meal." required></textarea>';
		echo '<br />';
		echo '<input type="submit" value="Submit">';
		echo '</form>';
		echo '</div>';
	}
	
	echo '<br />';
	
	// weekly meeting bible study table
	$sql = "SELECT weekly_bible_studies.time, weekly_bible_studies.description, r1.first_name AS r1_first_name, r1.last_name AS r1_last_name, r2.first_name AS r2_first_name, r2.last_name AS r2_last_name FROM weekly_bible_studies LEFT JOIN weekly_meeting_teams ON (weekly_bible_studies.team_id = weekly_meeting_teams.id) LEFT JOIN residents AS r1 ON (weekly_meeting_teams.resident1_id = r1.id) LEFT JOIN residents AS r2 ON (weekly_meeting_teams.resident2_id = r2.id) ORDER BY weekly_bible_studies.time DESC";
	$bibleStudyRows = mysqli_query($con, $sql);
	$bibleStudies = '';
	if (mysqli_num_rows($bibleStudyRows) > 0) {
		while ($row = mysqli_fetch_assoc($bibleStudyRows)) {
			$name = $row['r1_first_name'] . ' and ' . $row['r2_first_name'];
			$time = strtotime($row['time']);
			if ($time < time()) {
				$verb = ' talked about ';
			} else {
				$verb = ' will talk about ';
			}
			$time = date('M j Y \a\t g:i A', $time);
			$bibleStudies .= '<tr><td>'.$time.'</td><td>'.$name.$verb.$row['description'].'</td></tr>';
		}
	}
	echo '<div class="divHeader"><h2>Weekly Bible Study History</h2></div>';
	echo '<table id="tableBreakdown" style="margin-left:auto;margin-right:auto;">';
	echo '<tr><th>Date</th><th>Description</th></tr>';
	echo $bibleStudies;
	echo '</table>';
	
	echo '<br />';
	
	// A form for adding a bible study (just for Ben!)
	if (USER_ID == '1') {
		$sql = "SELECT weekly_meeting_teams.id, CONCAT(r1.first_name,' and ',r2.first_name) AS name FROM weekly_meeting_teams LEFT JOIN residents AS r1 ON (weekly_meeting_teams.resident1_id = r1.id) LEFT JOIN residents AS r2 ON (weekly_meeting_teams.resident2_id = r2.id)";
		$teams = mysqli_query($con, $sql);
		$options = '<option value="" disabled selected>Select a team</option>';
		while ($row = mysqli_fetch_assoc($teams)) {
			$options .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
		}
		echo '<div class="divHeader"><h2>Add a Bible Study</h2></div>';
		echo '<div class="centerForm">';
		echo '<form name="formAddBibleStudy" action="weeklyMeetings.php" method="post">';
		echo '<label for="inputBibleStudyTime">When was the bible study?</label>';
		echo '<br />';
		echo '<input id="inputBibleStudyTime" name="inputBibleStudyTime" title="Please specify when the bible study occurred." type="datetime-local" required></input>';
		echo '<br />';
		echo '<label for="selectBibleStudyTeam">Who led the bible study?</label>';
		echo '<br />';
		echo '<select id="selectBibleStudyTeam" name="selectBibleStudyTeam" title="Please specify who led the bible study." required>';
		echo $options;
		echo '</select>';
		echo '<br />';
		echo '<label for="inputBibleStudyDescription">What was talked about in the bible study?</label>';
		echo '<br />';
		echo '<textarea id="inputBibleStudyDescription" name="inputBibleStudyDescription" maxlength="300" placeholder="Describe the topic of the bible study" title="Please specify a description of the bible study." required></textarea>';
		echo '<br />';
		echo '<input type="submit" value="Submit">';
		echo '</form>';
		echo '</div>';
	}	
}
mysqli_close($con);


?>
<div id="notificationRecordAdded" class="notification" hidden>
	<span id="spanNotificationRecordAdded" style="display: inline-block;"></span>
</div>
</body>
</html>
