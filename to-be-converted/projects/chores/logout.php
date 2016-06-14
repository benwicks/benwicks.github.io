<?php
// header('WWW-Authenticate: Basic realm="Ocho Chores Re-Login"');
// header('HTTP/1.0 401 Unauthorized');
// $_SERVER['PHP_AUTH_USER'] = 'notauser';
// $_SERVER['PHP_AUTH_PW'] = 'notapassword';
session_start();
$_SESSION['logOut'] = true;
header('Location: index.php');
?>