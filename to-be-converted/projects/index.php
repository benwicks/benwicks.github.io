<?php include '../m_redirection.php' ?>
<!DOCTYPE html>
<!--[if IE 7]><html lang="en" class="ie7"><![endif]-->
<!--[if IE 8]><html lang="en" class="ie8"><![endif]-->
<!--[if IE 9]><html lang="en" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><html lang="en"><![endif]-->
<!--[if !IE]><html lang="en-US"><![endif]-->
<head>
<meta charset="UTF-8">
<title>Projects</title>
<?php include('../favicon.html') ?>
<link rel="stylesheet" type="text/css" href="../css/style.css">
<script src="../jquery.js" type="text/javascript"></script>
<script>
$(document).ready(function() {
    $("#logo").hover(function() {
        $(this).attr('src', '/images/ben_shadow.png');
    }, function() {
        $(this).attr('src', '/images/ben.png');
    });
});
</script>
</head>
<body>
<?php include_once("../analyticstracking.php") ?>
<div id="header">
<span>
<img id="logo" src="../images/ben.png" title="Benjamin Wicks" height="75" alt="ben" />
<br />
an aspiring <a href="http://en.wikipedia.org/wiki/Bioinformatics" target="_blank" id="bioi">bioinformatician</a>
</span>
</div>
<?php
include '../nav.php';
?>
<div id="content">
<?
$dirs = array_filter(glob('*'), 'is_dir');
foreach ($dirs as $d) {
	echo '<h2><a href="'.$d.'/">'.ucfirst($d).'</a></h2>';
}
?>
</div>
<?php
include '../copyright.php';
?>
</body>
</html>
