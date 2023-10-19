<?php
ini_set('memory_limit', '-1');
$error = false;
$errorMessage = "";
include "controller/ManageAdminApp.php";
$manage = new ManageAdminApp();
include "controller/validator.php";
$validate = new Validator();
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$controller = new Controller();
$con = $controller->connect();

if (isset($_POST['status']) && isset($_POST['user_id'])) {
    $status = $_POST['status'];
    $user_id = $_POST['user_id'];
    if ($status) {
        $update_value = 1;
    } else {
        $update_value = 0;
    }

    $update_status = $manage->updateFranchiseStatus($user_id, $status);
    if ($update_status) {
        echo "Status change Successfully";
    } else {
        echo "Issue while updating details, Please try again.";
    }

} elseif (isset($_POST['privacystatus']) && isset($_POST['user_id'])) {
    $privacy = $_POST['privacystatus'];
    $user_ids = $_POST['user_id'];
    if ($privacy) {
        $update_value1 = 1;
    } else {
        $update_value1 = 0;
    }
    $update_status1 = $manage->updatePrivacyPolicyStatus($user_ids, $privacy);
    if ($update_status1) {
        echo "Status change Successfully";
    } else {
        echo "Issue while updating details, Please try again.";
    }

} elseif (isset($_POST['servicestatus']) && isset($_POST['user_id'])) {
    $service = $_POST['servicestatus'];
    $user_ids = $_POST['user_id'];
    /*if ($service) {
        $update_value1 = 1;
    } else {
        $update_value1 = 0;
    }*/
    $update_status1 = $manage->updateServiceStatus($user_ids, $service);
    if ($update_status1) {
        echo "Status change Successfully";
    } else {
        echo "Issue while updating details, Please try again.";
    }

}
elseif (isset($_POST['planstatus']) && isset($_POST['user_id'])) {
    $service = $_POST['planstatus'];
    $user_ids = $_POST['user_id'];
    /*if ($service) {
        $update_value1 = 1;
    } else {
        $update_value1 = 0;
    }*/
    $update_status1 = $manage->updatePlanStatus($user_ids, $service);
    if ($update_status1) {
        echo "Status change Successfully";
    } else {
        echo "Issue while updating details, Please try again.";
    }

}
elseif (isset($_POST['themestatus']) && isset($_POST['user_id'])) {
    $service = $_POST['themestatus'];
    $user_ids = $_POST['user_id'];
    /*if ($service) {
        $update_value1 = 1;
    } else {
        $update_value1 = 0;
    }*/
    $update_status1 = $manage->updateThemeStatus($user_ids, $service);
    if ($update_status1) {
        echo "Status change Successfully";
    } else {
        echo "Issue while updating details, Please try again.";
    }

}
elseif (isset($_POST['testimonialstatus']) && isset($_POST['user_id'])) {
    $service = $_POST['testimonialstatus'];
    $user_ids = $_POST['user_id'];
    /*if ($service) {
        $update_value1 = 1;
    } else {
        $update_value1 = 0;
    }*/
    $update_status1 = $manage->updateTestimonialStatus($user_ids, $service);
    if ($update_status1) {
        echo "Status change Successfully";
    } else {
        echo "Issue while updating details, Please try again.";
    }

}
elseif (isset($_POST['teamstatus']) && isset($_POST['user_id'])) {
    $service = $_POST['teamstatus'];
    $user_ids = $_POST['user_id'];
    /*if ($service) {
        $update_value1 = 1;
    } else {
        $update_value1 = 0;
    }*/
    $update_status1 = $manage->updateTeamStatus($user_ids, $service);
    if ($update_status1) {
        echo "Status change Successfully";
    } else {
        echo "Issue while updating details, Please try again.";
    }

}
?>