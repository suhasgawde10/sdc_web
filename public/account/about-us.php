<?php


include "common-white-label.php";

if (strpos($url, $search) !== false) {
    include ('about-us-sdc.php');
}else{
    include("white-lable/about-us.php");
}
?>