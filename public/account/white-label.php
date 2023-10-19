<?php
/**
 * Created by PhpStorm.
 * User: Kubic Technology
 * Date: 10/03/2022
 * Time: 1:38 PM
 */
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $link = "https";
else $link = "http";
$link .= "://";
$link .= $_SERVER['HTTP_HOST'];
//$link .= $_SERVER['REQUEST_URI'];
header('location:' . $link . '/white-lable/panel/index.php');
?>