<?php
include "controller/ManageWebService.php";
$manage_service = new ManageWebService();
include "controller/validator.php";
$validate = new Validator();
include 'sendMail/sendMail.php';
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();

$response = array();
$response["error"] = false;
$response["message"] = "";
define("WEB_API_KEY", "6fb9fa56-a66e-490b-a8dd-ad6a37e65f62");
function GenerateAPIKeyNew()
{
    $key = implode('-', str_split(substr(strtolower(md5(microtime() . rand(1000, 9999))), 0, 30), 6));
    return $key;
}

$user_api_key = GenerateAPIKeyNew();

/*$string = "suhas gawde,suhasgawde1114@gmail.com,8888888891,India,Maharashtra,Mumbai,Male,12345678,Kubic technology,https://dgindia.online,1,0,6fb9fa56-a66e-490b-a8dd-ad6a37e65f62";
echo $security->encryptWebservice($string);
die();*/

//$_POST["token"] = "QXR1bGt1bWFyLGFsQGdtYWlsLmNvbSw5ODcwMzg5Nzc1LEluZGlhLE1haGFyYXNodHJhLE11bWJhaSxNYWxlLEF0dWxrdW1hckAxLGt1YmljIHRlY2hub2xvZ3ksaHR0cHM6Ly9rb2x5YXNrYW1hcmtldGluZ3NvbHV0aW9ucy5jb20vLDEsMCw2ZmI5ZmE1Ni1hNjZlLTQ5MGItYThkZC1hZDZhMzdlNjVmNjI=WejLT";

if (isset($_POST['token'])) {
    $token = $security->decryptWebservice(trim($_POST['token']));

    $explodeToken = explode(",", $token);

    /*echo "<pre>";
    var_dump($explodeToken);
    echo "</pre>";
    die();*/

    $txt_name = $explodeToken[0];
    $txt_email = $explodeToken[1];
    $txt_contact = $explodeToken[2];
    $txt_country = $explodeToken[3];
    $txt_state = $explodeToken[4];
    $txt_city = $explodeToken[5];
    $txt_gender = $explodeToken[6];
    $txt_password = $explodeToken[7];
    $txt_company_name = $explodeToken[8];
    $site_name = $explodeToken[9];
    $txt_terms = $explodeToken[10];
    $txt_online_search = $explodeToken[11];
    $api_key = $explodeToken[12];

    if (isset($api_key) && $api_key == WEB_API_KEY) {

        if (isset($txt_name) && $txt_name != "") {
        } else {
            $response["error"] = true;
            $response["message"] .= "Please enter name.<br>";
        }
        if (isset($txt_contact) && $txt_contact != "" && is_numeric($txt_contact)) {

        } else {
            $response["error"] = true;
            $response["message"] .= "Please enter contact number.<br>";
        }
        if (isset($txt_email) && $txt_email != "") {
            if (!filter_var($txt_email, FILTER_VALIDATE_EMAIL)) {
                $response["error"] = true;
                $response["message"] .= "Invalid email format.<br>";
            }
        } else {
            $response["error"] = true;
            $response["message"] .= "Please enter your email.<br>";
        }
        if (isset($txt_country) && $txt_country != "") {

        } else {
            $response["error"] = true;
            $response["message"] .= "Please Select country.<br>";
        }

        if (isset($txt_state) && $txt_state != "") {
        } else {
            $response["error"] = true;
            $response["message"] .= "Please Select state.<br>";
        }
        if (isset($txt_city) && $txt_city != "") {

        } else {
            $response["error"] = true;
            $response["message"] .= "Please Select city.<br>";
        }
        if (isset($txt_gender) && $txt_gender != "") {

        } else {
            $response["error"] = true;
            $response["message"] .= "Please Select gender.<br>";
        }

        if (isset($txt_password) && $txt_password != "") {

        } else {
            $response["error"] = true;
            $response["message"] .= "Please enter your password.<br>";
        }
        $result = $manage_service->validateUserContact($txt_contact);
        if ($result) {
            $response["error"] = true;
            $response["message"] .= "Contact Number Already Exists!!<br>";
        }
        if (!$response["error"]) {
            if (isset($txt_company_name) && $txt_company_name != '') {
                $txt_custom_url = str_replace(' ', '-', trim($txt_company_name));
                $result = $manage_service->validateCustomUrl(trim($txt_custom_url));
                if ($result) {
                    $new_custom_url = $txt_custom_url . rand(1000, 100000);
                } else {
                    $new_custom_url = $txt_custom_url;
                }
            } else {
                $txt_company_name = "";
                $txt_custom_url = str_replace(' ', '-', trim($txt_name));
                $result = $manage_service->validateCustomUrl(trim($txt_custom_url));
                if ($result) {
                    $new_custom_url = $txt_custom_url . rand(1000, 100000);
                } else {
                    $new_custom_url = $txt_custom_url;
                }
            }
            $new_custom_url = str_replace([",", "/", "'"], "", $new_custom_url);
            $new_custom_url = str_replace("&", "and", $new_custom_url);
            $result = $manage_service->validateUserRegisterEmail($txt_email);
            if ($result) {
                $response["error"] = true;
                $response["message"] .= "Email ID Already Exists!!";
            } else {
                $sell_ref = "dealer_panel";
                $getDealerCode = $manage_service->getDealerInfo($site_name);

                $dealer_id = $getDealerCode["dealer_code"];
                $dg_site = $getDealerCode["dg_card_site_link"];
                $dealer_name = $getDealerCode["name"];

                if (!isset($_POST['online_search'])) {
                    $online_search = 0;
                } else {
                    $online_search = 1;
                }
                $getUserId = $manage_service->addUserDetails($txt_name, $new_custom_url, $txt_gender, $sell_ref, $dealer_id, $online_search, $txt_country, $txt_state, $txt_city, $txt_company_name, $dealer_name);
                if ($getUserId != 0) {
                    $type = "User";
                    $user_referral_code = "ref100" . $getUserId;
                    $updateDealer = $manage_service->updateUserCode($getUserId, $user_referral_code);
                    $insertUser = $manage_service->addUserLoginDetails($getUserId, $type, $txt_email, $txt_contact, $security->encrypt($txt_password) . "8523", $user_api_key);

                    if ($insertUser) {
                        //  $insertCustomUrl = $manage->addCustomUrl($getUserId, $new_custom_url);
                        $insertMenuBar = $manage_service->addMenuBar($getUserId);
                        $getSectionDetails = $manage_service->getSectionDetails();
                        if ($getSectionDetails != null) {
                            while ($result_data = mysqli_fetch_array($getSectionDetails)) {
                                $sectionId = $result_data["id"];
                                if ($sectionId == 7) {
                                    $p_dg_status = 0;
                                } else {
                                    $p_dg_status = 1;
                                }
                                $insertUserSectionEntry = $manage_service->insertDefaultUserSectionEntry($getUserId, $sectionId, $p_dg_status);
                            }
                        }
                        if (!file_exists('user/uploads/')) {
                            mkdir("user/uploads", 0777, true);
                        }

                        mkdir("user/uploads/" . trim($txt_email) . "/profile/", 0777, true);
                        mkdir("user/uploads/" . trim($txt_email) . "/image-slider/", 0777, true);
                        mkdir("user/uploads/" . trim($txt_email) . "/about-us/", 0777, true);
                        mkdir("user/uploads/" . trim($txt_email) . "/service/", 0777, true);
                        mkdir("user/uploads/" . trim($txt_email) . "/images/", 0777, true);
                        mkdir("user/uploads/" . trim($txt_email) . "/testimonials/clients", 0777, true);
                        mkdir("user/uploads/" . trim($txt_email) . "/testimonials/client_review", 0777, true);
                        mkdir("user/uploads/" . trim($txt_email) . "/our-team/", 0777, true);
                        mkdir("user/uploads/" . trim($txt_email) . "/logo/", 0777, true);

                        $toName = $txt_name;
                        $toEmail = trim($txt_email);

                        //$sms_message1 = "Dear " . ucwords($txt_name) . ", \nPlease login to fill all your details to complete your digital card.\nURL:" . $dg_site . " \nUsername=" . $txt_contact . "\nPassword=" . $txt_password . "\n\nclick here to open your digital card\n" . SHARED_URL . $new_custom_url;

                        $get_user_data = $manage_service->getUserData($getUserId);
                        if ($get_user_data != null) {
                            $user_expiry_date = $get_user_data['expiry_date'];
                            $u_name = $get_user_data['name'];
                            $u_email = $get_user_data['email'];
                            $u_contact = $get_user_data['contact_no'];
                            $update_user_count = $get_user_data['update_user_count'];
                            $get_email_count = $get_user_data['email_count'];
                            $custom_url = $get_user_data['custom_url'];
                        }
                        $date1 = date("Y-m-d");
                        $date = date_create("$date1");
                        date_add($date, date_interval_create_from_date_string("5 days"));
                        $final_date = date_format($date, "Y-m-d");
                        $year = "Free Trail (5 days)";
                        $amount = "0";
                        $status = "success";
                        $referal_by = "";
                        $refrenced_by = "";
                        $active_plan = 1;
                        $invoice_no = "";
                        $discount = 0;
                        $paymentMode = "";
                        $paymentBrand = "";
                        $custBankId = "";
                        $timestamp = date('Y-m-d H:i:s');
                        $tax = 0;
                        $dealer_gstn_no = "";
                        $dealer_by_pay = 1;
                        $payment_type = "RazorPay";
                        $insertUserSubscription = $manage_service->insertUserData($getUserId, $year, $amount, $amount, $date1, $final_date, $status, $referal_by, $referal_by, $active_plan, $invoice_no, $discount, $tax, $amount, $paymentBrand, $paymentMode, $custBankId, $timestamp, $payment_type, "", "", $dealer_gstn_no, "", FROM_BILL, FROM_GSTNO, FROM_PAN, SAC_CODE);
                        if ($insertUserSubscription) {
                            $txt_contact = $txt_email = $txt_name = "";
                            $updateUserExpiry = $manage_service->updateUserExpiryDate($getUserId, $final_date);
                            if ($updateUserExpiry) {
                                $update_email_count = $manage_service->update_user_email_count($getUserId);
                                $error = false;
                                $response["message"] = 'Register Successfully, Please use credentials shared on your Registered Mobile Number in order to Get started with your Digital Card.';
                            }
                        }
                    } else {
                        $response["error"] = true;
                        $response["message"] .= "Something went wrong!! Please try again later.";
                    }
                }
            }
        }
    } else {
        $response["error"] = true;
        $response["message"] = "Invalid Request Please try again after Some time.";
    }
} else {
    $response["error"] = true;
    $response["message"] = "Invalid Request Please try again after Some time.";
}

echo json_encode($response);

?>