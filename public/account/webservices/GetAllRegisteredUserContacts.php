<?php
header('Content-Type: application/json');
include "../controller/ManageService.php";
$manage = new ManageService();

$response = array();
$response["success"] = false;

if (isset($_POST['email_address']) && $_POST['email_address'] != '') {
    $result = $manage->getRegisterContacts();
    if ($result != NULL) {
        $contacts = "";
        while ($row = mysqli_fetch_assoc($result)) {
            $contacts .= $row["contact_no"] . ",";
			$custom_url .= $row["custom_url"] . ",";
			$email .= $row["email"] . ",";
			$profile_img .= $row["img_name"] . ",";
        }
        $contacts = substr($contacts, 0, strlen($contacts) - 1);
        $response["success"] = true;
        $response["contact_no"] = $contacts;
		$response["email"] = $email;
		$response["custom_url"] = $custom_url;
		$response["profile_img"] = $profile_img;
    } else {
        $response["message"] = "Null Data Found.";
    }
} else {
    $response["message"] = "Parameter are missing.";
}
echo json_encode($response);
?>