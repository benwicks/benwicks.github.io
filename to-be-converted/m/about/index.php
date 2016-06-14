<!DOCTYPE html>
<html>
<head>
<title>About</title>
<?php
    $path = $_SERVER['DOCUMENT_ROOT'];
    $path .= "/m/header.php";
    include($path);
?>
<style>
.photo_caption_div
{
    margin-left: auto;
    margin-right: auto;
    margin-top: 20px;
    width: 220px;
    padding: 2px;
    background-color: rgb(255,143,31);
    background-color: rgba(255,143,31,.4);
    overflow-x: scroll;
    white-space: nowrap;
}
.photo
{
    margin: 5px 0;
    padding:1px;
    border:2px solid #000000;
    display: block;
    margin-left: auto;
    margin-right: auto
}
.photo_caption
{
    margin: 5px 0;
    font-size:14px;
    text-align: center
}
</style>
</head>
<body>
<div data-role="page">
    <div data-role="header">
        <h1>About Ben</h1>
    </div>
    <div id="content" style="padding:0 15px;">
        <?php
            $today = strtotime(date('Y-m-d'));
            $bday = '702298500';
            $age = floor(($today - $bday) / 31556926);
        ?>
        <p>Benjamin Wicks is currently <?php echo $age;?> years old and he has lived in Omaha, NE since he was born.</p>
        <div class="photo_caption_div">
            <img class="photo" src="/images/Benjamin_Wicks_Photo.jpg" title="Benjamin Wicks smiling" width="150" alt="A photo of Ben should have appeared here :(" />
            <p class="photo_caption">Benjamin Wicks, 2013</p>
        </div>
        <p>Ben just finished the four year <a href="http://bioinformatics.ist.unomaha.edu/undergraduate.php?p=undergraduate" target="_blank">bioinformatics</a> undergraduate program at the University of Nebraska at Omaha. There are special places in his heart for <a href="http://en.wikipedia.org/wiki/Ultimate_(sport)" target="_blank">Ultimate Frisbee</a>, <a href="http://developer.android.com/" target="_blank">Android</a>, and <a href="http://www.arduino.cc/" target="_blank">Arduino</a>.</p>
        <p>He has been interested in human health and genetics for several years. Two of Ben's newer interests are DIY electronics and neuroscience. One field of particular interest to him is the <a href="http://en.wikipedia.org/wiki/Brain%E2%80%93computer_interface" target="_blank">interfacing</a> of mind and machine. What if prosthetics (of all sorts) could be developed that could interface closely with the brain and allow for very fine control?</p>
        <!--<p><a href="tl%3bdr.php">Life Story Version</a></p>-->
    </div>
    <div data-role="footer">
        <?php
            echo '<h4>&copy; 2012-'.date("Y").'  - Benjamin Wicks</h4>';
        ?>
    </div>
</div>
</body>
</html>
