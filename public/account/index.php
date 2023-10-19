<?php

include "common-white-label.php";


if (strpos($url, $search) !== false) {  
 
    include('index-sdc.php');
}else{
    include("white-lable/index.php");
}
?>