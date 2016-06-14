<?php
$con=mysqli_connect("database2014.db.9953790.hostedresource.com","database2014","Neri988!","database2014");
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	return;
}
// First, remove all but top 10 scores
$query = "DELETE FROM snake_scores WHERE score NOT IN (SELECT score FROM snake_scores ORDER BY score DESC LIMIT 10)";
mysqli_query($con,$query);
$query = "SELECT * FROM snake_scores ORDER BY score DESC LIMIT 10";
$result = mysqli_query($con,$query);
$json = "{\"highscores\":[";
while($row = mysqli_fetch_array($result)) {
	$json .= "{\"score\":\"".$row['score']."\",\"user\":\"".$row['user']."\",\"time\":\"".date('M j Y',strtotime($row['time']))."\"},";
}
$json = rtrim($json,",");
$json .= "]}";
echo $json;
mysqli_close($con);
return;
?>
