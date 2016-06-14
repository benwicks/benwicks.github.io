<?php
$postID = $_POST['postID'];
$score = $_POST['score'];
//$ip = $_POST['ip'];
//$name = $_POST['name'];
if (strlen($postID) == 0 || strlen($score) == 0) {
	echo "false";
	return;
}
$goodID = false;
$f = fopen('keys.txt','r');
$lineNum = 0;
while (($buffer = rtrim(fgets($f, 4096))) !== false) {
	if ($buffer == $postID) {
		$goodID = true;
		break;
	}
	$lineNum += 1;
}
fclose($f);
if ($goodID) {
	$con=mysqli_connect("database2014.db.9953790.hostedresource.com","database2014","Neri988!","database2014");
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		return;
	}
	$query = "SELECT score FROM snake_scores ORDER BY score DESC LIMIT 9,1";
	$result = mysqli_query($con,$query);
	$tenthScore = 0;
	while($row = mysqli_fetch_array($result)) {
		$tenthScore = $row['score'];
	}
	if ($score > $tenthScore) {
		echo "true";
	}
	mysqli_close($con);
	return;
} else {
	echo "DON'T CHEAT.";
	return;
}
?>
