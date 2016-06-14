<?php
require('private/authenticate.php');
// http://www.colorpicker.com/C20808 - click Analogic
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title>Birthdays</title>
<?php include('private/header.php'); ?>
</head>
<body>
<?php
$con = mysqli_connect("database2014.db.9953790.hostedresource.com","database2014","Neri988!","database2014");
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: ".mysqli_connect_errno();
} else {
	// SELECT the birthdays
	$sql = "SELECT id, first_name, DATE_FORMAT(birth_date, '%m-%d') AS short_bday, birth_date FROM residents WHERE move_out_date IS NULL ORDER BY MONTH(birth_date), YEAR(birth_date)";
	$result = mysqli_query($con, $sql);
	$birthdays = array();
	while ($row = mysqli_fetch_assoc($result)) {
		array_push($birthdays, $row);
	}
	// Put the nearest birthday at the start of the array
	$today = date('m-d');
	$idx = 0;
	foreach ($birthdays as $b) {
		if ($b['short_bday'] >= $today) {
			break;
		}
		$idx++;
	}
	$birthdays = array_merge(array_slice($birthdays, $idx), array_slice($birthdays, 0, $idx));
}
mysqli_close($con);

foreach ($birthdays as $b) {
	// TODO: Format the next birthday nicely - http://php.net/manual/en/function.date.php
	// TODO: Calculate how many days there are until the birthday - http://stackoverflow.com/questions/654363/how-many-days-until-x-y-z-date
	$parts = explode('-', $b['birth_date']);
	$thisYear = date('Y');
	$birthYear = $parts[0];
	$month = $parts[1];
	$day = $parts[2];
	$nextBirthday = $month.'-'.$day;
	if ($nextBirthday == date('m-d')) {
		// The birthday is today
		$nextBirthday = $thisYear.'-'.$nextBirthday;
		$nextAge = $thisYear - $birthYear;
		$when = 'is '.$nextAge.' years old today!';
	} else if ($b['short_bday'] > $today) {
		// The birthday is yet this year
		$nextAge = $thisYear - $birthYear;
		$when = 'will be '.$nextAge.' on '.$b['short_bday'].'.';
	} else {
		// The birthday is in the next calendar year
		$nextAge = ($thisYear+1) - $birthYear;
		$when = 'will be '.$nextAge.' on '.$b['short_bday'].'.';
	}
	// $nextBirthday = 
	echo '<a href="/u.php?id='.$b['id'].'">'.$b['first_name'].'</a> '.$when.'<br /><br />';
}

?>
</body>
</html>
