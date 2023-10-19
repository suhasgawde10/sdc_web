<?php

include "common-white-label.php";

if (strpos($url, $search) !== false) {
    include('pricing-sdc.php');
} else {
    include("white-lable/pricing.php");
}
?>