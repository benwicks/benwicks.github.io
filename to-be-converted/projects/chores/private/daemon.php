#!/usr/bin/php
<?php
// http://www.inmotionhosting.com/support/community-support/general-server-setup/receiving-fatal-error-when-trying-to-send-email-from-php-script
// require_once 'Mail-1.2.0/Mail.php';
// This script should be run every hour.
// It should check each chore_assignment and e-mail reminders
// It should check each un-confirmed chore_history and e-mail confirmation e-mail
// It should NOT send an e-mail reminder every hour (so keep a record of when they were sent.)
// Perhaps it should only e-mail once a day if the status doesn't change

// Also, it should send out a special e-mail a couple hours after the weekly meeting
function rand_str($random_string_length) {
	$characters = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$random = '';
	for ($i = 0; $i < $random_string_length; $i++) {
		$random .= $characters[rand(0, strlen($characters)-1)];
	}
	return $random;
}

$message = "...";

$con = mysqli_connect("database2014.db.9953790.hostedresource.com","database2014","Neri988!","database2014");
if (mysqli_connect_errno()) {
	$message = "Failed to connect to MySQL: ".mysqli_connect_errno();
} else {
	// SELECT all residents
	$justBen = " AND id=1";
	$sql = "SELECT first_name, email FROM residents WHERE DATE(NOW()) >= move_in_date AND (DATE(NOW()) < move_out_date OR move_out_date IS NULL)".$justBen;
	$residents = mysqli_query($con,$sql);
	while ($row = mysqli_fetch_assoc($residents)) {
		$message .= 'Will send e-mail to '.$row['first_name'].': "'.$row['email'].'"'.PHP_EOL;
		
		// $from = "chore_manager@benjaminwicks.com";
		// $to = $row['email'];
		$to = 'chore_manager@benjaminwicks.com';
		$subject = 'Test Chore Reminder';
		$body = 'Hi, how are you?';
		$headers = 'MIME-Version: 1.0'."\r\n".'Content-type: text/html; charset=iso-8859-1'."\r\n";
		// $headers = 'From: chore_manager@benjaminwicks.com'."\r\n".'Reply-To: chore_manager@benjaminwicks.com'."\r\n".'X-Mailer: PHP/'.phpversion();
		// $headers = array('From' => $from, 'To' => $to, 'Subject' => $subject);
		// $smtp = Mail::factory('smtp', array('host' => 'smtpout.secureserver.net', 'port' => '25', 'auth' => true, 'username' => 'chore_manager@benjaminwicks.com', 'password' => '%d&PV4XB'));
		
		// $mail = $smtp->send($to, $headers, $body);
		// if (PEAR::isError($mail)) {
		// 	echo('<p>'.$mail->getMessage().'</p>');
		// } else {
		// 	echo('<p>Message successfully sent!</p>');
		// }
		
		// $content = "This is a <a href=\"http://www.example.com\">test</a>.";
		$content = "This is a <a href=\"google.com\">test</a>.";
		/* COMMENTED! mail($to, $row['email'], $content, $headers); */
		// echo 'Mail sent to '.$emailAddress.PHP_EOL;
	}
}
mysqli_close($con);





$key = rand_str(15);
$logMessage = time().PHP_EOL."\tMessage:\t$message".PHP_EOL."\tKey:\t\t$key";

/* COMMENTED! file_put_contents('daemon.log', $logMessage, FILE_APPEND | LOCK_EX); */
?>
