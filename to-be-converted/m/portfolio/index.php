<!DOCTYPE html>
<html>
<head>
<title>Portfolio</title>
<?php
    $path = $_SERVER['DOCUMENT_ROOT'];
    $path .= "/m/header.php";
    include_once($path);
?>
<script type="text/javascript" src="../jquery/jquery.mobile-1.4.2.min.js">
</script>
</head>
<body>
<div data-role="page">
    <div data-role="header">
        <h1>Ben's Portfolio</h1>
    </div>
    <div data-role="content">
        <p>Most of my work is already on <a href="http://www.linkedin.com/pub/ben-wicks/37/202/a49" target="_blank">LinkedIn</a>, so you can check there first.</p>
	<p>I also have a few projects hosted on my <a href="https://github.com/benwicks" target="_blank">GitHub page</a>. And there's also that <a href="http://stackoverflow.com/users/3667225/therealbenjamin" target="_blank">stackoverflow account</a> that I recently set up.</p>
    </div>
    <div data-role="footer">
        <?php
            echo '<h4>&copy; 2012-'.date("Y").'  - Benjamin Wicks</h4>';
        ?>
    </div>
</div>
</body>
</html>
