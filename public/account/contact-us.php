<?php
include "common-white-label.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (strpos($url, $search) !== false) {
    include('contact-sdc.php');
    
} else {
    include("white-lable/contact-us.php");
}
?>
