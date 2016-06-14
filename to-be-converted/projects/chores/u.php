<?php
require('private/authenticate.php');

if (isset($_GET['n'])) {
	$name = $_GET['n'];
	if (strlen($name) == 0) {
		header('Location: http://chores.benjaminwicks.com/');
		die();
	}
} else if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$name = 'idpassed';
	if (strlen($id) == 0) {
		header('Location: http://chores.benjaminwicks.com/');
		die();
	}
} else {
	header('Location: http://chores.benjaminwicks.com/');
	die();
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title>Resident Information</title>
<?php include('private/header.php'); ?>
</head>
<body>
<?php
switch(strtolower($name)) {
	case 'idpassed':
		break;
	case 'justin':
		$id = 0;
		break;
	case 'benjamin':
	case 'ben':
		$id = 1;
		break;
	case 'blake':
		$id = 2;
		break;
	case 'grant':
		$id = 3;
		break;
	case 'ethan':
		$id = 4;
		break;
	case 'thomas':
		$id = 5;
		break;
	case 'nick':
		$id = 6;
		break;
	case 'stephen':
		$id = 7;
		break;
	case 'zach':
		$id = 8;
		break;
	case 'andrew':
		$id = 9;
		break;
	default:
		$name = '';
		$id = -1;	
}

if ($id >= 0) {
	$con = mysqli_connect("database2014.db.9953790.hostedresource.com","database2014","Neri988!","database2014");
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: ".mysqli_connect_error();
	} else {
		$result = mysqli_query($con,"SELECT first_name FROM residents WHERE id=".$id);
		$row = mysqli_fetch_array($result);
		$name = $row['first_name'];
		$result = mysqli_query($con,"SELECT chores.id, chores.name, chores.description, chores.frequency, chore_assignments.due_time, chore_assignments.resident_id FROM chores LEFT JOIN chore_assignments ON (chore_assignments.chore_id = chores.id) WHERE chores.active = 1 AND chore_assignments.resident_id = $id ORDER BY chore_assignments.due_time ASC");
		while ($row = mysqli_fetch_assoc($result)) {
			$nowTime = time();
			$dueTime = strtotime($row['due_time']);
			$dueDateString = date('l, F j \a\t g:i a', $dueTime);
			if ($nowTime > $dueTime) {
				$numTime = ($nowTime - $dueTime)/24/60/60;
				$timeUnits = 'days';
				$due = intval($numTime).' '.$timeUnits.' ago on '.$dueDateString;
			} else {	
				$numTime = ($dueTime - $nowTime)/24/60/60;
				$timeUnits = 'days';
				$due = 'In '.intval($numTime).' '.$timeUnits.' on '.$dueDateString;
			}
			// $due = date('l, F j \a\t g:i a', $dueTime);
			$chores .= '<li><b>'.$row['name'].'</b></li><ul><li>'.$row['description'].'</li><li><b>Due:</b> '.$due.'</li>'.((USER_ID==$id)?'<li><a href="c.php?cid='.$row['id'].'">Done?</a></li>':'').'</ul></li>';
		}
		// TODO: Show chores for which this resident is a manager
		
		
		// TODO: Show upcoming weekly meeting responsibilities
		
		mysqli_close($con);
	}
}
// TODO: A database call to check on others' upcoming birthdays
if (strlen($name) > 0) {
	echo '<div class="divHeader"><h1>'.$name.'</h1></div>';
	if (strlen($chores) > 0) {
		$headerText = (USER_ID==$id)?'Your chores:':$name.'\'s chores:';
		echo '<h2>'.$headerText.'</h2>';
		echo '<ul>'.$chores.'</ul>';
	}
}
?>
</body>
</html>
