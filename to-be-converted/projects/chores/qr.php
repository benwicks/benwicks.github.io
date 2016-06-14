<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title>The Ocho Chores Home</title>
<?php include('private/header.php'); ?>
<script type="text/javascript">
</script>
<style type="text/css">
td {
text-align:center;
vertical-align:middle;
}
</style>
</head>
<body>
<?
$con = mysqli_connect("database2014.db.9953790.hostedresource.com","database2014","Neri988!","database2014");
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: ".mysqli_connect_errno();
} else {
	// Chore-breakdown table
	$sql = "SELECT id, name, description FROM chores WHERE active=1";
	$chores = mysqli_query($con,$sql);
	$table = '<table>';
	$i = 0;
	while ($row = mysqli_fetch_assoc($chores)) {
		$imgURL = 'http://chart.apis.google.com/chart?chs=80x80&cht=qr&chld=|1&chl=http%3A%2F%2Fchores.benjaminwicks.com%2Fc.php%3Fcid%3D'.$row['id'];
		$tmp = '<td><img src="'.$imgURL.'"></img><br /><h2>'.$row['name'].'</h2><p>'.$row['description'].'</p></td>';
		if (($i % 3) == 0) {
			$table .= '<tr>'.$tmp;
		} else if ((($i-1)%3) == 0) {
			$table .= $tmp;
		} else {
			$table .= $tmp.'</tr>';
		}
		$i += 1;
	}
	$table .= '</table>';
	echo $table;
}

?>
</body>
</html>
