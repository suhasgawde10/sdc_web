<?php


if (isset($_SESSION['id'])) {
    if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
        $session_email = $_SESSION['create_user_email'];
        $id = $security->decrypt($_SESSION["create_user_id"]);
        $session_name = $_SESSION['create_user_name'];
        $session_custom_url_is = $_SESSION['create_user_custom_url'];
        $session_contact_no = $_SESSION['create_user_contact'];
    } else {
        $session_email = $_SESSION['email'];
        $session_name = $_SESSION['name'];
        $id = $security->decrypt($_SESSION["id"]);
        $session_custom_url_is = $_SESSION['custom_url'];
        $session_contact_no = $_SESSION['contact'];
    }
}

?>