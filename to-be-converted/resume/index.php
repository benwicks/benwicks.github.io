<?php include '../m_redirection.php' ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>My Resume</title>
<?php include($DOCUMENT_ROOT.'../favicon.html') ?>
<link rel="stylesheet" type="text/css" href="../css/style.css">
<style>
h2
{
margin-bottom:0em;
}
.document
{
text-decoration:none;
}
.details
{
margin-left:20px;
}
</style>
<script src="../jquery.js" type="text/javascript"></script>
<script>
$(document).ready(function() {
    $("#logo").hover(function() {
        $(this).attr('src', '../images/ben_shadow.png');
    }, function() {
        $(this).attr('src', '../images/ben.png');
    });
});
</script>
</head>
<body>
<?php include_once("../analyticstracking.php") ?>
<div id="header">
<span>
<a href="/">
<img id="logo" src="../images/ben.png" title="Benjamin Wicks" height="75" alt="ben" />
</a>
<br />
an aspiring <a href="http://en.wikipedia.org/wiki/Bioinformatics" target="_blank" id="bioi">bioinformatician</a>
</span>
</div>
<?php
$page = 'resume';
include '../nav.php';
?>
<div id="content">
<h2><a class="document" href="/resume/Benjamin%20Wicks%20CV.pdf" target="_blank">My CV</a></h2>
<?php
$filename = 'Benjamin Wicks CV.pdf';
if (file_exists($filename)) {
    echo "<div class=\"details\">";
    echo "<p><i>Last modified:</i> ".date("F d Y", filemtime($filename))."</p>";
    echo "<p><i>File size:</i> ".round((filesize($filename)/1000), 2, PHP_ROUND_HALF_DOWN)." kB</p>";
    echo "</div>";
}
?>
<h2><a class="document" href="/resume/Benjamin%20Wicks%20Resume.pdf" target="_blank">My Resume</a></h2>
<?php
$filename = 'Benjamin Wicks Resume.pdf';
if (file_exists($filename)) {
    echo "<div class=\"details\">";
    echo "<p><i>Last modified:</i> ".date("F d Y", filemtime($filename))."</p>";
    echo "<p><i>File size:</i> ".round((filesize($filename)/1000), 2, PHP_ROUND_HALF_DOWN)." kB</p>";
    echo "</div>";
}
?>
</div>

<?php
include '../copyright.php';
?>
</body>
</html>
