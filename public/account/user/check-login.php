<?php
session_start();

 //$time = date("m/d/Y h:i:s a", strtotime("+30 seconds"));
$inactive = date("m/d/Y h:i:s a", time() + 30);


if(isset($_SESSION['timeout']) ) {
    $session_life = time() - $_SESSION['timeout'];
    if($session_life > $inactive) echo "0";
    else echo "1";
}

$_SESSION['timeout'] = time();