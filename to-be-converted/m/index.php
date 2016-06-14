<!DOCTYPE html>
<html>
<head>
<title>Ben Wicks</title>
<?php
include('header.php');
?>
</head>
<body>
<div data-role="page">
    <div data-role="header">
        <h1>Benjamin Wicks</h1>
    </div>
    <div data-role="content">
        <ul data-role="listview" data-inset="true">
            <li><a href="/about">About</a></li>
            <li><a href="/contact">Contact</a></li>
            <li><a href="/resume">Resume</a></li>
            <li><a href="/portfolio">Portfolio</a></li>            
        </ul>
        <p style="text-align:center;">Welcome to my mobile site!</p>
        <p style="text-align:center;">It is quite the work in progress.</p>
        <p style="text-align:center;">You can check the site on your desktop computer to see a little more.</p>
    </div>
    <div data-role="footer">
        <?php
            echo '<h4>&copy; 2012-'.date("Y").'  - Benjamin Wicks</h4>';
        ?>
    </div>
</div>
</body>
</html>
