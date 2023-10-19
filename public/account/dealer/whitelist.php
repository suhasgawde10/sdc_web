<?php
$main_site = false;

if (strpos($_SERVER['HTTP_HOST'], 'sharedigitalcard.com') !== false) {
    $main_site = true;
    $extension = "";
} else if(strpos($_SERVER['HTTP_HOST'], 'localhost') !== false){
    $main_site = true;
    $extension = ".php";
}

?>