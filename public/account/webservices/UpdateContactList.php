<?php
header('Content-Type: application/json');
include "../controller/ManageService.php";
$manage = new ManageService();

$response = array();
$response["success"] = false;

if (isset($_POST['email_address']) && $_POST['email_address'] != ''
    && isset($_POST['contact_no']) && $_POST['contact_no'] != ''
    && isset($_POST['id_list']) && $_POST['id_list'] != ''
) {

    $email = $_POST['email_address'];
    $contact = $_POST['contact_no'];

    $result = $manage->validateContactDetails($email, $contact);
    if ($result != null) {
        $id_list = $_POST['id_list'];
        $update = $manage->updateContactId($email, $contact, $id_list);

        if ($update) {
            $response["success"] = true;
            $response["message"] = "Update Contact List successfully";
        } else {
            $response["message"] = "Issue Occurred while updating details";
        }
    } else {
        $response["message"] = "Unauthorised Access.";
    }
} else {
    $response["message"] = "Parameter are missing.";
}
echo json_encode($response);
?>