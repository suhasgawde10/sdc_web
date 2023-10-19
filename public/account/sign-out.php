<?php
session_start();

unset($_SESSION['email']);
unset($_SESSION['id']);
unset($_SESSION['name']);
unset($_SESSION['contact']);
unset($_SESSION['custom_url']);
unset($_SESSION['type']);
unset($_SESSION['admin_contact']);
unset($_SESSION['admin_email']);
unset($_SESSION['tmp_email']);
unset($_SESSION['email_login']);
unset($_SESSION['verified_status']);

header('location:login.php');
?>