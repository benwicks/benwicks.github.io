<?php
$postID = $_POST['postID'];
$score = $_POST['score'];
$ip = $_POST['ip'];
$name = $_POST['name'];
if (strlen($postID) == 0 || strlen($score) == 0 || strlen($ip) == 0 || strlen($name) == 0) {
	echo "false";
	return;
}
$goodID = false;
$f = fopen('keys.txt','r');
while (($buffer = rtrim(fgets($f, 4096))) !== false) {
	if ($buffer == $postID) {
		$goodID = true;
		break;
	}
}
fclose($f);
if ($goodID) {
	$con=mysqli_connect("database2014.db.9953790.hostedresource.com","database2014","Neri988!","database2014");
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		return;
	}
	$postID = mysqli_real_escape_string($con,$postID);
	$score = mysqli_real_escape_string($con,$score);
	$ip = mysqli_real_escape_string($con,$ip);
	$name = mysqli_real_escape_string($con,$name);
	$query = "SELECT score FROM snake_scores ORDER BY score DESC LIMIT 9,1";
	$result = mysqli_query($con,$query);
	$tenthScore = 0;
	while($row = mysqli_fetch_array($result)) {
		$tenthScore = $row['score'];
	}
	if ($score > $tenthScore) {
		$query = "INSERT INTO snake_scores VALUES (".$score.",'".$name."','".$ip."',NOW())";
		mysqli_query($con,$query);
		echo 'true';
	}
	mysqli_close($con);
	return;
} else {
	echo "DON'T CHEAT.";
	return;
}
?>
