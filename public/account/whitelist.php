<?php
$main_site = false;
// print_r($_SERVER['HTTP_HOST']);
// exit;
if (strpos($_SERVER['HTTP_HOST'], "sharedigitalcard.com") !== false) {
    $main_site = true;
    $extension = ".php";
} else if(strpos($_SERVER['HTTP_HOST'], '127.0.0.1:8000') !== false){
    $main_site = true;
    $extension = ".php";
}

?>
