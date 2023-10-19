<?php

include "common-white-label.php";

if (strpos($url, $search) !== false) {
    include('privacy-policy-sdc.php');
} else {
    include("white-lable/privacy-policy.php");
}

?>