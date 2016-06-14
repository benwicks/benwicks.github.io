<?php include '../m_redirection.php' ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>TL;DR About Ben</title>
<?php include($DOCUMENT_ROOT.'../favicon.html') ?>
<link rel="stylesheet" type="text/css" href="../css/style.css">
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
$page = 'about/tl%3bdr.php';
include '../nav.php';
?>
<div id="content" style="max-width:1000px;">
<?php
    $today = strtotime(date('Y-m-d'));
    $bday = '702298500';
    $age = floor(($today - $bday) / 31556926);
?>
<p>Benjamin Wicks is currently <?php echo $age;?> years old and he has lived in Omaha, NE since he was born.</p>
<p>Ben developed an interest in genetics and later in computer science during high school. One of his favorite high school teachers recommended that he should check out <a href="http://en.wikipedia.org/wiki/Bioinformatics" target="_blank">bioinformatics</a>. After graduating from <a href="http://mps.mwhs.schoolfusion.us/" target="_blank">Millard West High School</a> in May of 2010, Ben began his studies at <a href="http://www.unomaha.edu/" target="_blank">the University of Nebraska at Omaha</a>. He chose to <a href="http://bioinformatics.ist.unomaha.edu/" target="_blank">major in bioinformatics</a>, following the pre-medical track and taking honors courses. Ben is currently finishing his final year of his undergraduate studies in Omaha, and anxioiusly awaiting what the future will bring. Although his earliest dream to become an artist when he grows up has tapered off, he is still pursing his long-standing dream of becoming a doctor one day. Intrigued by the wisdom and skill demonstrated by his pediatrician, Ben knew that he was interested in helping people "feel better" one day too. That vision has continued to guide Ben and it has been adapted to the new interests that Ben has acquired over the years.</p>
<p>One of the biggest areas of Ben's interest is simply computer science. He took a liking to programming after he began to learn <a href="http://en.wikipedia.org/wiki/Java_(programming_language)" target="_blank">Java</a> in 11<sup>th</sup> grade.</p>
</div>
<?php
include '../copyright.php';
?>
</body>
</html>