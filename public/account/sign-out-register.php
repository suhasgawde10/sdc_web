<?php
session_start();
session_destroy();
if (isset($_GET['type']) && $_GET['type'] == 'android'){
    header('location:register.php?type='. $_GET['type']);
}elseif(isset($_GET['email_register']) && $_GET['email_register'] == 'true'){
    header('location:register.php?email_register='. $_GET['email_register']);
}else{
    header('location:register.php');
}
?>