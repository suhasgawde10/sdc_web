<?php

include "common-white-label.php";

if (strpos($url, $search) !== false) {
    include('demo-cards-sdc.php');
} else {
    include("white-lable/card.php");
}
?>