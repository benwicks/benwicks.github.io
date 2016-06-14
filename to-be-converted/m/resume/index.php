<!DOCTYPE html>
<html>
<head>
<title>Resume</title>
<?php
    $path = $_SERVER['DOCUMENT_ROOT'];
    $path .= "/m/header.php";
    include_once($path);
?>
</head>
<body>
<div data-role="page">
    <div data-role="header">
        <h1>Ben's Resume</h1>
    </div>
    <div data-role="content">
        <?php
            $resPath = "/resume/Benjamin Wicks Resume.pdf";
            $resName = $_SERVER['DOCUMENT_ROOT'].$resPath;
            $resLink = 'http://benjaminwicks.com'.$resPath;
            $cvPath = "/resume/Benjamin Wicks CV.pdf";
            $cvName = $_SERVER['DOCUMENT_ROOT'].$cvPath;
            $cvLink = 'http://benjaminwicks.com'.$cvPath;
        ?>
        <h2><a href="<?php echo $resLink; ?>" target="_blank">My Resume</a></h2>
        <?php
            if (file_exists($resName)) {
                echo "<p><i>Last modified:</i> " . date ("F d Y", filemtime($resName)). "</p>";
                echo "<p><i>File size:</i> ".round((filesize($resName)/1000), 2, PHP_ROUND_HALF_DOWN)." kB</p>";
            }
        ?>
        <h2><a href="<?php echo $cvLink; ?>" target="_blank">My CV</a></h2>
        <?php
            if (file_exists($cvName)) {
                echo "<p><i>Last modified:</i> " . date ("F d Y", filemtime($cvName)). "</p>";
                echo "<p><i>File size:</i> ".round((filesize($cvName)/1000), 2, PHP_ROUND_HALF_DOWN)." kB</p>";
            }
        ?>
    </div>
    <div data-role="footer">
        <?php
            echo '<h4>&copy; 2012-'.date("Y").'  - Benjamin Wicks</h4>';
        ?>
    </div>
</div>
</body>
</html>
