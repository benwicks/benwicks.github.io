<?php include '../m_redirection.php' ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Ben's Portfolio</title>
<?php include($DOCUMENT_ROOT.'../favicon.html') ?>
<link rel="stylesheet" type="text/css" href="../css/style.css">
<style>
.photo_caption_div
{
    vertical-align: middle;
    overflow-x: scroll;
    display:inline-block;
    height: auto;
    width: auto;
    margin: 10px;
    padding: 2px 10px;
    background-color: rgb(255,143,31);
    background-color: rgba(255,143,31,.4);
    white-space: nowrap;
}
.photo
{
    height: 400px;
    margin: 5px 0;
    padding:1px;
    border:2px solid #000000;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
.tall_photo
{
    height:650px;
    margin: 5px 0;
    padding:1px;
    border:2px solid #000000;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
.small_photo
{
    width:150px;
    margin: 5px 0;
    padding:1px;
    border:2px solid #000000;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
.photo_caption
{
    margin: 5px 0;
    width: 350px;
    font-size:14px;
    text-align: center;
    margin-left: auto;
    margin-right: auto;
    padding: 2px;
}
.photo_title
{
    text-align: center;
}
#reu, #door_photos, #android_apps
{
    white-space: nowrap;
    overflow-x: scroll;
    overflow-y: hidden;
    margin: 0 20px;
    padding: 15px;
    background-color: rgb(176,176,176);
    background-color: rgba(176,176,176,.2);
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
$page = 'portfolio';
include '../nav.php';
?>
<div id="content">
<p>A lot of my work is already on <a href="http://www.linkedin.com/pub/ben-wicks/37/202/a49" target="_blank">LinkedIn</a>, so you might check there first.</p>
<p>I also have a few personal projects hosted on my <a href="https://github.com/benwicks" target="_blank">GitHub page</a>.</p>
<hr />
<div id="all">
<h2>Python plotting (via <a href="http://matplotlib.org/" target="_blank">matplotlib</a>)</h2>
<h3>Plots for <a href="http://genome.ist.unomaha.edu/" target="_blank">UNO REU Bioinformatics</a></h3>
<div id="reu">
<div class="photo_caption_div">
<a href="http://genome.ist.unomaha.edu/cgi-bin/genbank.cgi" target="_blank">
<img class="photo" src="images/01.png" title="Growth of GenBank" alt="A graph should have appeared here :(" />
</a>
<p class="photo_caption"><b>Growth of GenBank, 2013</b><br />&copy; 2014 Mark A. Pauley, University of Nebraska at Omaha<br />Used with permission.</p>
</div>
<div class="photo_caption_div"> 
<a href="http://genome.ist.unomaha.edu/cgi-bin/protein_sizes.py" target="_blank">
<img class="photo" src="images/02.png" title="Protein Sizes" alt="A graph should have appeared here :(" />
</a>
<p class="photo_caption"><b>Protein Sizes, 2013</b><br />&copy; 2014 Mark A. Pauley, University of Nebraska at Omaha<br />Used with permission.</p>
</div>
<div class="photo_caption_div">
<a href="http://genome.ist.unomaha.edu/cgi-bin/genbank_vs_intel.cgi" target="_blank">
<img class="photo" src="images/03.png" title="GenBank versus Intel" alt="A graph should have appeared here :(" />
</a>
<p class="photo_caption"><b>GenBank vs. Intel, 2013</b><br />&copy; 2014 Mark A. Pauley, University of Nebraska at Omaha<br />Used with permission.</p>
</div>
<div class="photo_caption_div">
<a href="http://genome.ist.unomaha.edu/cgi-bin/protein_sizes2.py" target="_blank">
<img class="photo" src="images/04.png" title="Protein Composition" alt="A screenshot of a table should have appeared here :(" />
</a>
<p class="photo_caption"><b>Protein Composition, 2013</b><br />&copy; 2014 Mark A. Pauley, University of Nebraska at Omaha<br />Used with permission.</p>
</div>
<div class="photo_caption_div">
<a href="http://genome.ist.unomaha.edu/cgi-bin/gene_sizes.py" target="_blank">
<img class="photo" src="images/05.png" title="Gene Sizes" alt="A graph should have appeared here :(" />
</a>
<p class="photo_caption"><b>Gene Sizes, 2013</b><br />&copy; 2014 Mark A. Pauley, University of Nebraska at Omaha<br />Used with permission.</p>
</div>
</div>
</div>
<hr />
<h2>Android Applications</h2>
<div id="android_apps">
	<div class="photo_caption_div">
	<h3 class="photo_title">Public Art Omaha (for <a href="http://attic.ist.unomaha.edu" target="_blank">the IS&amp;T Attic</a>)</h3>
	<a href="https://play.google.com/store/apps/details?id=edu.unomaha.ist.pao" target="_blank">
	<img class="small_photo" src="images/pao.png" title="Public Art Omaha Android App" alt="Public Art Omaha Android App" />
	</a>
	<p class="photo_caption">I worked on Public Art Omaha from fall 2010 to fall 2011.</p>
	</div>
	<div class="photo_caption_div">
	<h3 class="photo_title">PKI Open House (for <a href="http://attic.ist.unomaha.edu" target="_blank">the IS&amp;T Attic</a>)</h3>
	<a href="https://play.google.com/store/apps/details?id=edu.unomaha.ist.pkiopenhouse" target="_blank">
	<img class="small_photo" src="images/oh.png" title="PKI Open House App" alt="PKI Open House Android App" />
	</a>
	<p class="photo_caption">I worked on the Open House app from fall 2011 to fall 2012.</p>
	</div>
	<div class="photo_caption_div">
	<h3 class="photo_title">Simple Clock</h3>
	<a href="https://play.google.com/store/apps/details?id=com.wicks.simpleclock" target="_blank">
	<img class="small_photo" src="images/sc.png" title="Simple Clock Android App" alt="Simple Clock Android App" />
	</a>
	<p class="photo_caption">I worked on this personal project in fall 2011 and spring 2012.</p>
	</div>
</div>
<hr />
<h2>Arduino Projects</h2>
<h3>Dorm Door Opener</h3>
<p>I assembled an Arduino project that uses a servo motor to turn my door handle enough to un-lock the door. The Arduino is also connected to my local network wirelessly and it acts as a web server. The functionality of the Arduino is controlled by a RESTful web service which can be accessed via an Android application that I wrote. The Arduino code is available <a href="https://github.com/benwicks/DoorWebServerAndServo" target="_blank">here</a> and the Android app code is available <a href="https://github.com/benwicks/DoorWebClient" target="_blank">here</a>.</p>
<div id="door_photos">
	<div class="photo_caption_div">
		<img class="tall_photo" src="images/door/door1.JPG" title="" alt="" />
		<p class="photo_caption">Pre-LED screen install</p>
	</div>
	<div class="photo_caption_div">
		<img class="tall_photo" src="images/door/door2.JPG" title="" alt="" />
		<p class="photo_caption">Close-up, pre-LED screen install</p>
	</div>
	<div class="photo_caption_div">
		<img class="tall_photo" src="images/door/door3.JPG" title="" alt="" />
		<p class="photo_caption">Mid-LED screen install</p>
	</div>
	<div class="photo_caption_div">
		<iframe class="photo" width="640" height="360" src="//www.youtube.com/embed/GAXrUll9Xz8" seamless allowfullscreen></iframe>
		<p class="photo_caption">A video of (a prototype of) the project in action</p>
	</div>
	<div class="photo_caption_div">
		<iframe src="images/door/door_project_documentation.pdf" width="600" height="700" alt="PDF Documentation" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html"></iframe>
		<p class="photo_caption">The <a href="images/door/door_project_documentation.pdf">documentation</a> for this project</p>
	</div>
</div>
</div>
<?php
include '../copyright.php';
?>
</body>
</html>
