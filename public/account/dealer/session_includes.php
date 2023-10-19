<?php

if (isset($_SESSION['dealer_id'])) {
    if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
        $session_email = $_SESSION['create_user_email'];
        $id = $_SESSION["create_user_id"];
        $session_name = $_SESSION['create_user_name'];
        $session_contact_no = $_SESSION['create_user_contact'];
    } else {
        $session_email = $_SESSION['dealer_email'];
        $session_name = $_SESSION['dealer_name'];
        $id = $_SESSION["dealer_id"];
        $session_contact_no = $_SESSION['dealer_contact'];
    }
}

?>