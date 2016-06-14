<?php
$con = mysqli_connect("database2014.db.9953790.hostedresource.com","database2014","Neri988!","database2014");
if (mysqli_connect_errno()) {
	header('Content-Type: application/json');
	echo '{"error":"Failed to connect to MySQL: '.mysqli_connect_errno().'"}';
	exit;
} else {
	$resultUsers = mysqli_query($con,"SELECT first_name, id FROM residents") or die('{"error":"'.mysqli_error($con).'"}');
	$validUsers = array();
	while ($row = mysqli_fetch_assoc($resultUsers)) {
		$validUsers[strtolower($row['first_name'])] = $row['id'];
	}
	$validUsernames = array_keys($validUsers);
	if (preg_match('/Basic\s+(.*)$/i', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
		list($name, $password) = explode(':', base64_decode($matches[1]));
		$_SERVER['PHP_AUTH_USER'] = strip_tags($name);
		$_SERVER['PHP_AUTH_PW'] = strip_tags($password);
	}
	$user = strtolower($_SERVER['PHP_AUTH_USER']);
	$pass = $_SERVER['PHP_AUTH_PW'];
	if (in_array($user, $validUsernames) && strlen($pass) > 0) {
		$sql = "SELECT COUNT(*) AS count FROM residents WHERE id=$validUsers[$user] AND password = PASSWORD(\"$pass\")";
		$resultMatch = mysqli_query($con,$sql) or die('{"error":"'.mysqli_error($con).'"}');
		$row = $resultMatch->fetch_assoc();
		$validated = ($row['count'] > 0);		
	} else {
		$validated = false;
	}
	mysqli_close($con);
	session_start();
	if (!$validated || isset($_SESSION['logOut'])) {
		unset($_SESSION['logOut']);
		header('WWW-Authenticate: Basic realm="Ocho Chores Login"');
		header('HTTP/1.0 401 Unauthorized');
		header('Content-Type: application/json');
		echo '{"error":"Not authorized."}';
		exit;
	} else {
		define('USER_ID', $validUsers[$user]);
		define('USER_NAME', ucfirst($user));
	}
}
?>