<?php
$timezone = new DateTimeZone("UTC");
$date = new DateTime("now", $timezone);
$dateTime = $date->format("Y-m-d\TH:i:s");
?>