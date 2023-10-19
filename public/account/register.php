<?php

include "common-white-label.php";
// echo$url;
// echo$search;
// exit;
if (strpos($url, $search) !== false) {
    include('register-sdc.php');
} else {
    include("white-lable/registration.php");
}
?>