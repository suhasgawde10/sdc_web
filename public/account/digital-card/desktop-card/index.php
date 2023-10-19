<?php
include '../../controller/Constants.php';

if(isset($_GET['custom_url'])){
    $custom_url = $_GET['custom_url'];
    if(isset($_GET['token']) && $_GET['token'] !=''){
        $token  = "&token=".$_GET['token'];
    }else{
        $token = '';
    }
    header('location:'.FULL_WEBSITE_URL. $custom_url.$token);
}
