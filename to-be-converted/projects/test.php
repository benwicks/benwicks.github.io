<?
$code = "<div class='Hello'>He said \"Hello\"</div>";
echo $code;
$new_code = preg_replace(array("/(Hello)(?=[^>]*(<|$))/"), array("Hi"), $code);
echo $new_code;
?>
