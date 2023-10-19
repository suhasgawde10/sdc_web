<?php
include "controller/ManageWebService.php";
$manage_service = new ManageWebService();
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$response = array();
$response["error"] = false;
$response["message"] = "";
define("WEB_API_KEY", "6fb9fa56-a66e-490b-a8dd-ad6a37e65f62");

if (isset($_POST['token'])) {

    $token = $security->decryptWebservice($_POST['token']);
    $explodeToken = explode(",", $token);
    $site_name = $explodeToken[0];
    $api_key = $explodeToken[1];

    if (isset($api_key) && $api_key == WEB_API_KEY) {
        $getDealerCode = $manage_service->getDealerInfo($site_name);

        if ($getDealerCode != null) {

            $dg_site_name = $getDealerCode["dg_card_site_link"];

            $getDealerCardDetails = $manage_service->getDealerCardDetails($getDealerCode["dealer_code"]);

            if ($getDealerCardDetails != null) {
                $card = array();

                while ($row = mysqli_fetch_assoc($getDealerCardDetails)) {
                    $item = array();
                    $item["name"] = $security->encrypt($row["name"]);
                    $item["email"] = $security->encrypt($row["email"]);
                    $item["custom_url"] = $security->encrypt($row["custom_url"]);
                    $item["designation"] = $security->encrypt($row["designation"]);
                    $item["altr_contact_no"] = $security->encrypt($row["altr_contact_no"]);
                    $item["website_url"] = $security->encrypt($row["website_url"]);
                    $item["linked_in"] = $security->encrypt($row["linked_in"]);
                    $item["youtube"] = $security->encrypt($row["youtube"]);
                    $item["facebook"] = $security->encrypt($row["facebook"]);
                    $item["twitter"] = $security->encrypt($row["twitter"]);
                    $item["instagram"] = $security->encrypt($row["instagram"]);
                    $item["map_link"] = $security->encrypt($row["map_link"]);
                    $item["address"] = $security->encrypt($row["address"]);
                    $item["img_name"] = $security->encrypt($row["img_name"]);
                    $item["user_keyword"] = $security->encrypt($row["user_keyword"]);
                    $item["company_name"] = $security->encrypt($row["company_name"]);
                    $item["playstore_url"] = $security->encrypt($row["playstore_url"]);
                    $item["whatsapp_no"] = $security->encrypt($row["whatsapp_no"]);
                    $item["business_category"] = $security->encrypt($row["business_category"]);
                    $item["company_logo"] = $security->encrypt($row["company_logo"]);
                    $item["landline_number"] = $security->encrypt($row["landline_number"]);

                    array_push($card, $item);
                }

                $response["error"] = true;
                $response["dg_domain_link"] = $security->encrypt($dg_site_name);
                $response["card"] = $card;
            } else {
                $response["error"] = true;
                $response["message"] = "No Record Found";
            }
        } else {
            $response["error"] = false;
            $response["message"] = "Invalid Request Please try again after Some time. 3rd last";
        }
    } else {
        $response["error"] = false;
        $response["message"] = "Invalid Request Please try again after Some time. 2nd last";
    }
} else {
    $response["error"] = false;
    $response["message"] = "Invalid Request Please try again after Some time. last";
}

echo json_encode($response);

?>