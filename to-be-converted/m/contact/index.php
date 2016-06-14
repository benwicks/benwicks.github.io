<!DOCTYPE html>
<html>
<head>
<title>Contact</title>
<?php
    $path = $_SERVER['DOCUMENT_ROOT'];
    $path .= "/m/header.php";
    include_once($path);
?>
</head>
<body>
<div data-role="page">
    <div data-role="header">
        <h1>Contact Ben</h1>
    </div>
    <div data-role="content">
        <p style="text-align:center;">I'm on Linkedin:</p>
        <center>
        <a href="http://www.linkedin.com/pub/ben-wicks/37/202/a49" target="_blank">
            <img src="http://www.linkedin.com/img/webpromo/btn_viewmy_160x25.png" width="160" height="25" border="0" alt="View my profile on LinkedIn">
        </a>
        </center>
        <p style="text-align:center;">Or, you can <a href="mailto:&#098;&#101;&#110;&#064;&#098;&#101;&#110;&#106;&#097;&#109;&#105;&#110;&#119;&#105;&#099;&#107;&#115;&#046;&#099;&#111;&#109;">e-mail me</a>.</p>
    </div>
    <div data-role="footer">
        <?php
            $yearNow = date("Y");
            echo '<h4>&copy; 2012-'.$yearNow.'  - Benjamin Wicks</h4>';
            // echo '<p class="copyright">&copy; 2012-'.$yearNow.'  - Benjamin Wicks</p>';
        ?>
    </div>
</div>
</body>
</html>
