<?php
ob_start();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include '../sendMail/sendMail.php';
$error = false;
$errorMessage = "";
$error1 = false;
$errorMessage1 = "";
if (!isset($_SESSION['email'])) {
    header('location:../login.php');
} elseif (isset($_SESSION['email']) && isset($_SESSION['type']) && $_SESSION['type'] == 'User') {
    header('location:../login.php');
}

$random = rand(100, 10000);
$random_password = rand(1000, 10000);

if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
    $page_no = $_GET['page_no'];
} else {
    $page_no = 1;
}


if (isset($_GET['delete_data']) && $_GET['email']) {
    $email = $_GET['email'];
    $delete_data = $security->decrypt($_GET['delete_data']);
    $dirPath = "uploads/$email";
    function deleteDirectory($dirPath)
    {
        if (is_dir($dirPath)) {
            $objects = scandir($dirPath);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir") {
                        deleteDirectory($dirPath . DIRECTORY_SEPARATOR . $object);
                    } else {
                        unlink($dirPath . DIRECTORY_SEPARATOR . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dirPath);
        }
    }

    if ($email != '') {
        deleteDirectory($dirPath);
    }
    $status = $manage->deleteUser($delete_data);
    if ($status) {
        header('location:user-management.php');
    }
}

if (isset($_POST['btn_delete_all'])) {
    $deleted_id = explode(',', $_POST['deleted_id']);
    function deleteDirectory($dirPath)
    {
        if (is_dir($dirPath)) {
            $objects = scandir($dirPath);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir") {
                        deleteDirectory($dirPath . DIRECTORY_SEPARATOR . $object);
                    } else {
                        unlink($dirPath . DIRECTORY_SEPARATOR . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dirPath);
        }
    }

    if (!$error1) {
        if (count($deleted_id) >= 1) {
            foreach ($deleted_id as $key) {
                $get_user_details = $manage->getUserProfile($key);
                $dirPath = "uploads/" . $get_user_details['email'];
                if ($get_user_details['email'] != '') {
                    deleteDirectory($dirPath);
                }
                $status = $manage->deleteUser($key);
            }
            if ($status) {
                $error1 = true;
                $errorMessage1 = "Data has been successfully deleted";
            }

        } else {
            $error1 = true;
            $errorMessage1 = "Please select checkbox";
        }
    }
}

function GenerateAPIKey()
{
    $key = implode('-', str_split(substr(strtolower(md5(microtime() . rand(1000, 9999))), 0, 30), 6));
    return $key;
}

$api_key = GenerateAPIKey();

if (isset($_POST['btn_cancel'])) {
    unset($_SESSION['drp_package']);
    unset($_SESSION['txt_search']);
    header('location:user-management.php');
}

if (isset($_POST['btn_submit'])) {
    if (isset($_POST['txt_name']) && $_POST['txt_name'] != "") {
        $txt_name = $_POST['txt_name'];
    } else {
        $error = true;
        $errorMessage .= "Please enter name.<br>";
    }
    if (isset($_POST['txt_email']) && $_POST['txt_email'] != "") {
        if (!filter_var($_POST['txt_email'], FILTER_VALIDATE_EMAIL)) {
            $error = true;
            $errorMessage .= "Invalid email format.<br>";
        }
        $txt_email = $_POST['txt_email'];
    } else {
        $error = true;
        $errorMessage .= "Please enter your email.<br>";
    }
    if (isset($_POST['gender']) && $_POST['gender'] != "") {
        $gender = $_POST['gender'];
    } else {
        $error = true;
        $errorMessage .= "Please Select gender.<br>";
    }

    if (isset($_POST['country']) && $_POST['country'] != "") {
        $country = $_POST['country'];
    } else {
        $error = true;
        $errorMessage .= "Please Select country.<br>";
    }

    if (isset($_POST['txt_state']) && $_POST['txt_state'] != "") {
        $txt_state = $_POST['txt_state'];
    } else {
        $error = true;
        $errorMessage .= "Please Select state.<br>";
    }
    if (isset($_POST['txt_city']) && $_POST['txt_city'] != "") {
        $txt_city = $_POST['txt_city'];
    } else {
        $error = true;
        $errorMessage .= "Please Select city.<br>";
    }
    if (isset($_POST['txt_password']) && $_POST['txt_password'] != "") {
        $txt_password = $_POST['txt_password'];
    } else {
        $error = true;
        $errorMessage .= "Please enter your password.<br>";
    }

    if (isset($_POST['txt_contact']) && $_POST['txt_contact'] != "") {
        $txt_contact = $_POST['txt_contact'];
    } else {
        $error = true;
        $errorMessage .= "Please Enter Contact.<br>";
    }
    if (!isset($_POST['online_search'])) {
        $online_search = 0;
    } else {
        $online_search = 1;
    }
    if (!$error) {
        if (isset($_POST['txt_company_name']) && $_POST['txt_company_name'] != '') {
            $txt_company_name = $_POST['txt_company_name'];
            $txt_custom_url = str_replace(' ', '-', trim($txt_company_name));
            $result = $manage->validateCustomUrl(trim($txt_custom_url));
            if ($result) {
                $custom_url = $txt_custom_url . rand(1000, 100000);
            } else {
                $custom_url = $txt_custom_url;
            }
        } else {
            $txt_company_name = "";
            $txt_custom_url = str_replace(' ', '-', trim($txt_name));
            $result = $manage->validateCustomUrl(trim($txt_custom_url));
            if ($result) {
                $custom_url = $txt_custom_url . rand(1000, 100000);
            } else {
                $custom_url = $txt_custom_url;
            }
        }

        $result = $manage->validateRegisterEmail($_POST['txt_email']);
        if ($result) {
            $error = true;
            $errorMessage .= "Email ID Already Exist";
        } else {
            $validContact = $manage->validateContact($txt_contact);
            if ($validContact) {
                $error = true;
                $errorMessage .= "Contact No Already Exist";
            } else {
                $team_id = "";
                $verify_number = 1;
                $getUserId = $manage->addUser($txt_name, $custom_url, $gender, $txt_email, $team_id, $verify_number, $online_search, $country, $txt_state, $txt_city, $txt_company_name);
                if ($getUserId != 0) {
                    $type = "User";
                    $_SESSION['user_code'] = "ref100" . $getUserId;
                    $updateDealer = $manage->updateUserCode($getUserId);
                    $insertUser = $manage->addUserLogin($getUserId, $type, $txt_email, $txt_contact, $security->encrypt($txt_password) . "8523", $api_key);
                    if ($insertUser) {
                        $insertCustomUrl = $manage->addCustomUrl($getUserId, $custom_url);
                        $insertMenuBar = $manage->addMenuBar($getUserId);
                        $getSectionDetails = $manage->getSectionDetails();
                        if ($getSectionDetails != null) {
                            while ($result_data = mysqli_fetch_array($getSectionDetails)) {
                                $sectionId = $result_data["id"];
                                if ($sectionId == 7) {
                                    $p_dg_status = 0;
                                } else {
                                    $p_dg_status = 1;
                                }
                                $insertUserSectionEntry = $manage->insertDefaultUserSectionEntry($getUserId, $sectionId, $p_dg_status);
                            }

                        }

                        mkdir("uploads/" . $txt_email . "/profile/", 0777, true);
                        mkdir("uploads/" . $txt_email . "/image-slider/", 0777, true);
                        mkdir("uploads/" . $txt_email . "/about-us/", 0777, true);
                        mkdir("uploads/" . $txt_email . "/service/", 0777, true);
                        mkdir("uploads/" . $txt_email . "/images/", 0777, true);
                        mkdir("uploads/" . $txt_email . "/testimonials/clients", 0777, true);
                        mkdir("uploads/" . $txt_email . "/testimonials/client_review", 0777, true);
                        mkdir("uploads/" . $txt_email . "/our-team/", 0777, true);
                        mkdir("uploads/" . $txt_email . "/logo/", 0777, true);

                        $_SESSION['create_user_email'] = $txt_email;
                        $_SESSION['create_user_name'] = $txt_name;
                        $_SESSION['create_user_id'] = $security->encrypt($getUserId);
                        $_SESSION['create_user_type'] = $type;
                        $_SESSION['create_user_contact'] = $txt_contact;
                        $_SESSION['create_user_custom_url'] = $custom_url;
                        $_SESSION['create_user_status'] = true;

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
                        $gstn_no_status = 0;
                        $insertUserSubscription = $manage->insertUserData($year, $amount, $amount, $date1, $final_date, $status,
                            $referal_by, $refrenced_by, $active_plan, $invoice_no, $discount, $tax, $amount, $paymentBrand,
                            $paymentMode, $custBankId, $timestamp, $payment_type, "", "", "", "", FROM_BILL, FROM_GSTNO, FROM_PAN, SAC_CODE);
                        if ($insertUserSubscription) {
                            $updateUserExpiry = $manage->updateUserExpiryDateById($final_date, $getUserId);
                            if ($updateUserExpiry) {
                                $update_email_count = $manage->update_email_countById($getUserId);
                            }
                        }
                        $toName = $txt_name;
                        $toEmail = $txt_email;
                        $message = '<table style="width: 100%">
<tr>
<td colspan="2" style=' . $back_image . '>
<div style="' . $overlay . '">
<div style=" margin: 0 auto">
 <div class="user-name-logo" style="padding-top: 10px;">
                        <div style="text-align: center;color: #c4a758;width: 80px;margin: 1px auto;background: white;border-radius: 50%;height: 80px;text-align: center;padding: 5px;">
                            <img src="https://sharedigitalcard.com/assets/img/logo/logo.png" style="padding-top: 15px;width:100%">
                        </div>
                    </div>
                    <div style="text-align: center;color: white;font-weight: 700;padding-bottom: 10px;">
                        <h1 style="font-size: 24px;margin: 0;">Share Digital Card</h1>
                    </div>
</div>
</div>
</td>
</tr>
<tr>
<td colspan="2">
 <div style=" font-size: 18px;">
                     <p>Dear <span class="cust-name">' . ucwords($txt_name) . '</span>,</p>
                    <p> We are happy to welcome you to the digital world of visiting card. Thank you for registration.<br><br>
                        Your registered email id: <span class="email-id">' . $txt_email . '</span><br><br>
                        Please follow the further process and get your digital card. </p>
                        <p>
                        <b>Login URL: <a href="https://sharedigitalcard.com/login.php">https://sharedigitalcard.com/login.php</a></b><br>
                        <b>Username: </b>' . $txt_contact . '/' . $txt_email . '<br><b>Password: </b>' . $txt_password . '
                        </p>
                 <a href="' . SHARED_URL . $custom_url . '" style="' . $btn . ';background: #db5ea5 !important;width: 100%;color: #ffffff;border-radius: 4px;font-size: 16px;padding: 10px 0;">Open Your Digital Card</a>
                    <p>To do any changes in your "Share Digital Card " click on to below button to login to our web portal or you can change your details from mobile application.</p>
                </div>
                        <br>
                        <p>Please do not share username and password with anyone due to security reason.</p>
                </div>
</td>
</tr>
<tr><td colspan="2" style="text-align:center">
<a href="http://sharedigitalcard.com/login.php" style="' . $btn . ';color:white; border-radius: 4px;"><img src="http://sharedigitalcard.com/user/assets/images/laptop.png" style="width: 19%;display: inline-block;vertical-align: middle;padding-right: 5px;color: white;">Click To Login</a>
                   <a target="_blank" href="https://play.google.com/store/apps/details?id=sharedigitalcard.com.digitalcard" style="padding: 0px;display: inline-block;vertical-align: middle;"><img src="https://sharedigitalcard.com/assets/img/playstore.png"
                                                                                          style="width: 135px" alt="digital card app"></a>
</td></tr>
<tr>
<td colspan="2" style=" font-size: 18px;">
<p> For any query email us on <a href="mailto:support@sharedigitalcard.com" class="payment">support@sharedigitalcard.com</a></p>
</td>
</tr>
<tr><td colspan="2" style="padding: 10px;background: #e6e6e6;height: 115px;">
 <div style="width: 85%;margin: 0 auto;">
                <div style=" width: 100%;margin: 0 auto;">
                    <div style="text-align:center">
                        <a href="https://www.facebook.com/sharedigitalcard/"><img src="http://sharedigitalcard.com/user/assets/images/fb.png"></a>
<a href="https://www.instagram.com/sharedigitalcard/"><img src="http://sharedigitalcard.com/user/assets/images/insta.png"></a>
<a href="https://www.youtube.com/watch?v=6T9Ia_2rsig&list=PLg1QyEHQ9MYYBRDxWqLrWLCyvJlSheqTh"><img src="http://sharedigitalcard.com/user/assets/images/yt.png"></a>
                    </div>
                </div>

            </div>
</td></tr>
</table>';
                        $sms_message1 = "Dear " . ucwords($txt_name) . ", \nPlease login to fill all your details to complete your digital card.\nURL:sharedigitalcard.com/login.php \nUsername=" . $txt_contact . "\nPassword=" . $txt_password . "\n\nclick here to open your digital card\n" . SHARED_URL . $custom_url;
                        $subject = "ShareDigitalCard.com - Registration Successful.";
                        $sendMail = $manage->sendMail($toName, $toEmail, $subject, $message);
                        $send_sms = $manage->sendSMS($_SESSION['create_user_contact'], $sms_message1);
                        header("location:basic-user-info.php");
                    } else {
                        $error = true;
                        $errorMessage .= "Something went wrong.. please try again later";
                    }
                }
            }
        }
    }
}

if (isset($_POST['cancel_button'])) {
    if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
        header('location:user-management.php?page_no=' . $_GET['page_no']);
    } else {
        header('location:user-management.php');
    }

}

if (isset($_GET['user_expiry_id'])) {
    if (isset($_POST['update_expiry'])) {
        $user_expiry_id = $security->decrypt($_GET['user_expiry_id']);

        /*    die();*/
        $expiry_date = $_POST['expiry_date'];
        $update_expiry = $manage->updateUserExpiryDateAndSubscription($expiry_date, $user_expiry_id);
        $update_expiry = $manage->updateUserExpiryDateProfile($expiry_date, $user_expiry_id);
        if ($update_expiry) {
            if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
                header('location:user-management.php?page_no=' . $_GET['page_no']);
            } else {
                header('location:user-management.php');
            }
        }
    }

}
$displayPlan = $manage->displaySubscription();
$displayPlan2 = $manage->displaySubscription();
if ($displayPlan != null) {
    $countForPlan = mysqli_num_rows($displayPlan);
} else {
    $countForPlan = 0;
}
if (isset($_POST['btn_domain_link'])) {
    $id = $_POST['d_user_id'];
    $domain_link = $_POST['txt_link'];
    $condition = array('id' => $id);
    $data = array('domain_link' => $domain_link);
    $update = $manage->update($manage->profileTable, $data, $condition);
    if ($update) {
        $error1 = false;
        $errorMessage1 = "Domain Link updated successfully!";
    } else {
        $error1 = true;
        $errorMessage1 = "Issue while updating please try after some time!";
    }

}

if (isset($_POST['btn_mapped_dealer'])) {
    $user_id = $_POST['d_user_id'];
    $dealer_id = $_POST['drp_dealer_user'];
    $condition = array('id' => $user_id);
    $data = array('referer_code' => $dealer_id);
    $update = $manage->update($manage->profileTable, $data, $condition);
    if ($update) {
        $error1 = false;
        $errorMessage1 = "User Mapped successfully!";
    } else {
        $error1 = true;
        $errorMessage1 = "Issue while updating please try after some time!";
    }

}

if (isset($_POST['btn_mapped_editor'])) {
    $user_id = $_POST['d_user_id'];
    $editor_id = $_POST['drp_editor_user'];
    $condition = array('id' => $user_id);
    $data = array('editor_id' => $editor_id);
    $update = $manage->update($manage->profileTable, $data, $condition);
    if ($update) {
        $error1 = false;
        $errorMessage1 = "Editor Mapped successfully!";
    } else {
        $error1 = true;
        $errorMessage1 = "Issue while updating please try after some time!";
    }

}

if (isset($_POST['btn_converted_editor'])) {
    $user_id = $_POST['d_user_id'];
    $condition = array('id' => $user_id);
    $data = array('converted_by_editor' => 1);
    $update = $manage->update($manage->profileTable, $data, $condition);
    if ($update) {
        $error1 = false;
        $errorMessage1 = "Data updated successfully!";
    } else {
        $error1 = true;
        $errorMessage1 = "Issue while updating please try after some time!";
    }

}
$search_filter = false;
$total_records_per_page = 30;
$tab_status = 3;
if (isset($_POST['search'])) {
    if (isset($_POST['txt_search']) && $_POST['txt_search'] != "") {
        $txt_search = $_POST['txt_search'];
    } else {
        $txt_search = "";
    }
    $_SESSION['txt_search'] = $txt_search;
    if (isset($_POST['from_date']) && $_POST['from_date'] != '') {
        $from_date = $_POST['from_date'];
    } else {
        $from_date = "";
    }

    $_SESSION['from_date'] = $from_date;
    if (isset($_POST['to_date']) && $_POST['to_date'] != '') {
        $to_date = $_POST['to_date'];
    } else {
        $to_date = "";
    }

    $_SESSION['to_date'] = $to_date;
    if (isset($_POST['drp_package']) && $_POST['drp_package'] != '') {
        $drp_package = $_POST['drp_package'];
    } else {
        $drp_package = '';
    }
    $_SESSION['drp_package'] = $drp_package;
    $total_customer = $manage->displayAllUserByPackageCount($drp_package, $txt_search, $from_date, $to_date,$tab_status);
    $offset = ($page_no - 1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $adjacents = "2";
    $total_records = $total_customer;
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    $second_last = $total_no_of_pages - 1; // total page minus 1
    $displayUser = $manage->displayAllUserByPackage($drp_package, $txt_search, $offset, $total_records_per_page, $from_date, $to_date,$tab_status);
    if ($displayUser != null) {
        $countUser = mysqli_num_rows($displayUser);
    } else {
        $countUser = 0;
    }
    $search_filter = true;
} elseif (isset($_POST['drp_dealer'])) {
    $drp_dealer = $_POST['drp_dealer'];
    $total_customer = $manage->countAllActiveUser();

    $offset = ($page_no - 1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $adjacents = "2";
    $total_records = $total_customer;
    $displayUser = $manage->displayUserByDealerCode($offset, $total_records_per_page, $drp_dealer);
    if ($displayUser != null) {
        $countUser = mysqli_num_rows($displayUser);
    } else {
        $countUser = 0;
    }
    $search_filter = true;
} else {
    $total_customer = $manage->countAllActiveUser($tab_status);

    $offset = ($page_no - 1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $adjacents = "2";
    $total_records = $total_customer;
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    $second_last = $total_no_of_pages - 1; // total page minus 1

    $displayUser = $manage->displayAllActiveUser($offset, $total_records_per_page,$tab_status);
    if ($displayUser != null) {
        $countUser = mysqli_num_rows($displayUser);
    } else {
        $countUser = 0;
    }
    $search_filter = true;

}

if (isset($_SESSION['drp_package']) && !$search_filter) {
    $total_customer = $manage->displayAllUserByPackageCount($_SESSION['drp_package'], $_SESSION['txt_search'], $_SESSION['from_date'], $_SESSION['to_date']);
    $total_records_per_page = 25;
    $offset = ($page_no - 1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $adjacents = "2";
    $total_records = $total_customer;
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    $second_last = $total_no_of_pages - 1; // total page minus 1
    $displayUser = $manage->displayAllUserByPackage($_SESSION['drp_package'], $_SESSION['txt_search'], $offset, $total_records_per_page, $_SESSION['from_date'], $_SESSION['to_date']);
    if ($displayUser != null) {
        $countUser = mysqli_num_rows($displayUser);
    } else {
        $countUser = 0;
    }
}


if (isset($_GET['publishData']) && (isset($_GET['action']))) {
    $action = $_GET['action'];
    $id = $security->decrypt($_GET['publishData']);
    if ($action == "Unblock") {
        $result = $manage->publishUnpublish($id, 1, $manage->profileTable);
    } else {
        $result = $manage->publishUnpublish($id, 0, $manage->profileTable);
    }
    header('location:user-management.php');
}

function addUrlParam($array)
{

    $url = $_SERVER['REQUEST_URI'];
    $val = "";
    if ($array != "") {
        foreach ($array as $name => $value) {
            if ($val != "") {
                $val .= "&" . $name . '=' . urlencode($value);
            } else {
                $val .= $name . '=' . urlencode($value);
            }
        }
    }
    if (strpos($url, '?') !== false) {
        $url .= '&' . $val;
    } else {
        $url .= '?' . $val;
    }
    return $url;
}

$today_date = date('Y-m-d');

if (isset($_POST['btn_update'])) {
    $checkbox = explode(',', $_POST['txt_id']);
    $title = $_POST['txt_title'];
    $message = $_POST['txt_remark'];

    if (count($checkbox) > 0) {
        foreach ($checkbox as $key) {
            $get_user = $manage->getUserProfile($key);
            if ($get_user['user_notification'] != "") {
                $send = $manage->sendPushNotification(API_KEY, $get_user['user_notification'], $title, $message);
            }
        }

        $error1 = false;
        $errorMessage1 = "Notification has been sent successfully!";
    } else {
        $error1 = true;
        $errorMessage1 = "Please select atleast one checkbox";
    }

}


if (isset($_POST['send_renewal'])) {
    $checkbox = explode(',', $_POST['txt_id']);

    if (count($checkbox) > 0) {
        foreach ($checkbox as $key) {
            $get_user = $manage->getUserProfile($key);
            if ($get_user['user_notification'] != "") {
                $expiry_date = $get_user['expiry_date'];
                if ($expiry_date < $today_date) {
                    $title = "Digital card subscription expired";
                    $message = "Hello " . $get_user['name'] . ", Your Digital card has been expired please login into the panel to renew it.";
                } else {
                    $title = "Digital card subscription expiring soon";
                    $date = date("Y-m-d");
                    $earlier = new DateTime("$date");
                    $later = new DateTime("$expiry_date");

                    if ($expiry_date === $date) {
                        $diff = "1";
                    } else {
                        $diff = $later->diff($earlier)->format("%a");
                    }
                    $message = "Hello " . $get_user['name'] . ", Your Digital card is expiring in " . $diff . " days please login into the panel to renew it.";

                }
                $send = $manage->sendPushNotification(API_KEY, $get_user['user_notification'], $title, $message);

            }

        }
        $error1 = false;
        $errorMessage1 = "Notification has been sent successfully!";
    } else {
        $error1 = true;
        $errorMessage1 = "Please select atleast one checkbox";
    }


}

if (isset($_POST['btn_give_extra_days'])) {
    $checkbox = explode(',', $_POST['extra_day']);
    $errorMessage1 = "";
    $success = false;
    if (count($checkbox) > 1) {
        foreach ($checkbox as $key) {
            if ($key == 1) {
                break;
            }
            $get_extra_day_status = $manage->getUserExtraDayStatus($key);
            if ($get_extra_day_status["extra_day_status"] == 0) {
                //echo $get_extra_day_status["expiry_date"].'<br>';
                if (strtotime($get_extra_day_status["expiry_date"]) > strtotime(date('Y-m-d'))) {
                    $new_expiry_date = date('Y-m-d', strtotime($get_extra_day_status["expiry_date"] . ' + 4 days'));
                } else {
                    $new_expiry_date = date('Y-m-d', strtotime(date('Y-m-d') . ' + 4 days'));
                }
                /*echo $new_expiry_date;
                die();*/
                $update_status = $manage->updateExtraDayStatus($key, $new_expiry_date);
                if ($update_status) {
                    $success = true;
                }
            } else {
                $errorMessage1 .= $get_extra_day_status["name"] . " have already taken Extra days benefit.<br/>";
            }
        }
        if ($success) {
            $error1 = false;
            $errorMessage1 .= "<br/>Extra 2 Days benefit given to selected Users.";
        }
    } else {
        $error1 = true;
        $errorMessage1 = "Please select atleast one checkbox";
    }


}
if (isset($_POST['btn_update_credit'])) {
    // drp_year,credit_user_id,quantity,chk_invoice
    $credit_user_id = $_POST['credit_user_id'];
    $drp_year = $_POST['drp_year'];
    $date = date('Y-m-d');
    $quantity = $_POST['quantity'];
    $insertCredit = $manage->mu_insertUserCreditById($drp_year, $quantity, $credit_user_id);

    if ($insertCredit) {
        if (isset($_POST['chk_invoice']) && $_POST['chk_invoice'] == 1) {
            if ($drp_year == "1 year") {
                $month = 12;
            } else if ($drp_year == "3 year") {
                $month = 36;
            } else if ($drp_year == "5 year") {
                $month = 60;
            } else {
                $month = "";
            }
            $user_expiry_date = null;
            if ($user_expiry_date != null && $user_expiry_date >= $date && $month != "") {
                $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($user_expiry_date));
                $expiry_date = date("Y-m-d", $expiry_date_in_time);
            } elseif ($user_expiry_date != null && $user_expiry_date <= $date && $month != "") {
                $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($date));
                $expiry_date = date("Y-m-d", $expiry_date_in_time);
            } elseif ($user_expiry_date == null && $month != "") {
                $expiry_date_in_time = strtotime("+" . $month . " months", strtotime($date));
                $expiry_date = date("Y-m-d", $expiry_date_in_time);
            } else {
                $expiry_date = "";
            }
            $status_success = "success";
            $referal_by = "";
            $refrenced_by = "credit";
            $active_plan = 0;

            $discount_amount = 0;
            $LastInvoiceNo = $manage->getLastInvoiceNumber();
            if ($LastInvoiceNo['invoice_no'] == null) {
                $invoice_number = 1001;
            } else {
                $invoice_number = $LastInvoiceNo['invoice_no'] + 1;
            }

            $get_user_data = $manage->getUserProfile($user_id);
            if ($get_user_data != null) {
                $user_expiry_date = $get_user_data['expiry_date'];
                $name = $get_user_data['name'];
                $email = $get_user_data['email'];
                $user_contact = $get_user_data['contact_no'];
                $user_gstno = $get_user_data['gst_no'];

                $invoice_name = $get_user_data['company_name'];
                if ($invoice_name == '') {
                    $invoice_name = $get_user_data['name'];
                }

                $user_pan_no = $get_user_data['pan_no'];

            }

            if (isset($drp_year)) {
                $get_select_value = $manage->get_selected_value($drp_year);
                if ($get_select_value['amt'] != null) {
                    $old_amount = $get_select_value['amt'];
                } else {
                    $old_amount = 0;
                }
                $total_without_tax = $old_amount * $quantity;
                $taxable_amount = $total_without_tax * 9 / 100;
                $new_tax = $taxable_amount + $taxable_amount;
            }
            $paymentMode = "Cash";
            $paymentBrand = "";
            $custBankId = "";
            $timestamp = date('Y-m-d H:i:s');
            $payment_type = "Admin Panel";
            $insertUserSubscription = $manage->insertUserCreditDataById($credit_user_id, $drp_year, $old_amount, $old_amount,
                $date, $expiry_date, $status_success, $referal_by, $refrenced_by, $active_plan, $invoice_number, $discount_amount,
                $new_tax, $old_amount, $paymentBrand, $paymentMode, $custBankId, $timestamp, $quantity,
                $payment_type, $invoice_name, $email, $user_gstno, $user_pan_no, FROM_BILL, FROM_GSTNO, FROM_PAN, SAC_CODE);
            if ($insertUserSubscription) {
                $error1 = false;
                $errorMessage1 = "Credit has been added successfully!";
            } else {
                $error1 = true;
                $errorMessage1 = "Issue while adding credit please try after some time!";
            }
        } else {
            $error1 = false;
            $errorMessage1 = "Credit has been added successfully!";
        }
    } else {
        $error1 = true;
        $errorMessage1 = "Issue while adding credit please try after some time!";
    }

}
function fetch_all_data($result)
{
    $all = array();
    while ($thing = mysqli_fetch_array($result)) {
        $all[] = $thing;
    }
    return $all;
}

$approveDealer = $manage->displayApproveDealer();
$get_editor = $manage->getActiveTeamProfile();
$get_all_editor = fetch_all_data($get_editor);

?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>User Mangement</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        .header {
            padding: 10px;
        }

        .truncated_text {
            text-overflow: ellipsis;
            width: 150px;
            white-space: nowrap;
            overflow: hidden;
            padding: 0px;
            margin: 0px;
        }

        /* .truncated_text:hover {
             text-overflow: clip;
             width: auto;
             white-space: normal;
         }*/

    </style>

</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
    <?php
    if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
        include "assets/common-includes/session_button_includes.php";
        echo "<br>";
    }
    ?>
    <div class="clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <div class="col-md-1">
                        <div class="row">
                            <h4>
                                User <span class="badge"><?php
                                    if (isset($total_customer)) echo $total_customer;
                                    ?></span>
                            </h4>
                        </div>
                        <div id="snackbar">URL is on the clipboard, try to paste it!</div>
                    </div>

                    <?php
                    if (isset($_SESSION['type']) && $_SESSION['type'] == "Admin") {
                        ?>
                        <div class="col-md-2">
                            <button class="btn btn-success" type="button" data-toggle="modal"
                                    data-target="#myModal"><i class="fa fa-bell" aria-hidden="true"></i> Custom
                                Notification
                            </button>
                        </div>

                        <div class="col-md-2 text-right">
                            <form method="post" action="">
                                <input type="hidden" name="txt_id" class="txt_id">
                                <button class="btn btn-warning" type="submit" name="send_renewal"><i class="fa fa-bell"
                                                                                                     aria-hidden="true"></i>
                                    Send Renewal Notification
                                </button>
                            </form>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="col-md-2 text-right">
                        <form method="post" action="">
                            <input type="hidden" name="extra_day" class="extra_day">
                            <button class="btn btn-warning" type="submit" name="btn_give_extra_days">
                                Give 4 Days Extra
                            </button>
                        </form>
                    </div>
                    <div class="col-md-2 text-right">
                        <a href="export-user.php" class="btn btn-success" type="button">
                            Export To Excel
                        </a>
                    </div>
                    <?php
                    if (isset($_SESSION['type']) && $_SESSION['type'] != "Editor") {
                        ?>
                        <div class="col-md-2 text-right">
                            <button class="btn btn-primary open_digi" data-toggle="modal" data-target="#addUser">Create
                                Digital Card
                            </button>
                        </div>
                        <div class="col-md-2 text-right">
                            <form method="post" action=""
                                  onsubmit="return confirm('Are you sure you want to delete the user?');">
                                <input class="deleted_id" type="hidden" name="deleted_id">
                                <button class="btn btn-primary open_digi" type="submit" name="btn_delete_all"><i
                                        class="fas fa-trash-alt"></i>Delete
                                </button>
                            </form>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <div class="body">
                    <?php if ($error1) {
                        ?>
                        <div class="alert alert-danger">
                            <?php if (isset($errorMessage1)) echo $errorMessage1; ?>
                        </div>
                    <?php
                    } else if (!$error1 && $errorMessage1 != "") {
                        ?>
                        <div class="alert alert-success">
                            <?php if (isset($errorMessage1)) echo $errorMessage1; ?>
                        </div>
                    <?php
                    }
                    ?>

                    <fieldset class="filter_search">
                        <legend>Filter</legend>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="<?php
                                if (isset($_SESSION['type']) && $_SESSION['type'] == "Admin") {
                                    echo 'col-md-9';
                                } else {
                                    echo 'col-md-12';
                                }
                                ?>">
                                    <form method="post" action="">
                                        <div class="col-md-4">
                                            <div class="width-prf">
                                                <label class="form-label">Enter Keywords</label>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                        <input type="text" class="form-control" name="txt_search"
                                                               value="<?php if (isset($txt_search)) echo $txt_search; ?>"
                                                               placeholder="Enter name,email,contact number,keywords,designation,company_name">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">

                                            <div class="width-prf">
                                                <label class="form-label">From Date</label>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                        <input type="date" class="form-control" name="from_date"
                                                               value="<?php if (isset($from_date)) echo $from_date; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="width-prf">
                                                <label class="form-label">To Date</label>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                        <input type="date" class="form-control" name="to_date"
                                                               value="<?php if (isset($to_date)) echo $to_date; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        if (isset($_SESSION['type']) && $_SESSION['type'] == "Admin") {
                                            ?>
                                            <div class="col-md-4">
                                                <div class="width-prf">
                                                    <label class="form-label">Select Package</label>

                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <select class="form-control show-tick" name="drp_package">
                                                                <option value="">Select Package</option>
                                                                <?php
                                                                if ($displayPlan != null) {
                                                                    while ($row = mysqli_fetch_array($displayPlan)) {
                                                                        $year = $row['year'];
                                                                        ?>
                                                                        <option
                                                                            value="<?php echo $year; ?>" <?php if (isset($_SESSION['drp_package']) && $_SESSION['drp_package'] == $year) echo 'selected="selected"'; ?>><?php echo $year; ?></option>

                                                                    <?php }
                                                                } ?>
                                                                <option
                                                                    value="expired" <?php if (isset($_SESSION['drp_package']) && $_SESSION['drp_package'] == 'expired') echo 'selected="selected"'; ?>>
                                                                    Expired
                                                                </option>
                                                                <option
                                                                    value="purchased" <?php if (isset($_SESSION['drp_package']) && $_SESSION['drp_package'] == 'purchased') echo 'selected="selected"'; ?>>
                                                                    Purchased
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        <div class="col-md-4">

                                            <div class="width-prf">
                                                <?php
                                                if (isset($_SESSION['type']) && $_SESSION['type'] == "Admin") {
                                                    ?>
                                                    <label class="form-label">&nbsp;</label>
                                                <?php
                                                }
                                                ?>
                                                <div class="form-group form-float">
                                                    <div class="">
                                                        <button class="btn btn-primary" type="submit" name="search">
                                                            Search
                                                        </button>

                                                        <button type="submit" name="btn_cancel" class="btn btn-danger">
                                                            Clear filter
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <?php
                                if (isset($_SESSION['type']) && $_SESSION['type'] == "Admin") {
                                    ?>
                                    <div class="col-md-3">
                                        <form method="post" action="">

                                            <div class="width-prf">
                                                <label class="form-label">Select Dealer To see the user</label>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <select class="form-control show-tick" name="drp_dealer"
                                                                onchange="this.form.submit()" data-live-search="true">
                                                            <option value="">Select Dealer</option>
                                                            <?php

                                                            if ($approveDealer != null) {
                                                                $get_all_dealer = fetch_all_data($approveDealer);
                                                                foreach ($get_all_dealer as $form_data) {
                                                                    $dealer_code = $form_data['dealer_code'];
                                                                    ?>
                                                                    <option
                                                                        value="<?php echo $dealer_code; ?>" <?php if (isset($_POST['drp_dealer']) && $_POST['drp_dealer'] == $dealer_code) echo 'selected="selected"'; ?>><?php echo $form_data['name'] . "-" . $form_data['contact_no']; ?></option>

                                                                <?php }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </fieldset>

                    <ul class="nav nav-tabs tab-nav-right" role="tablist">
                        <li role="presentation" class="active"><a
                                href="#profile" class="custom_nav_tab"
                                data-toggle="tab">Free 5 Days</a>
                        </li>
                        <li role="presentation"><a
                                class="custom_nav_tab" href="converted_editor_panel.php">Converted </a>
                        </li>
                        <li role="presentation"><a
                                class="custom_nav_tab" href="expired_editor_panel.php">Expired </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active"
                             id="profile">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-sm"
                               cellspacing="0"
                               width="100%"> <!-- id="dtHorizontalVerticalExample" -->
                            <thead>
                            <tr class="back-color">
                                <th><input type="checkbox" id="checkAl"></th>
                                <th>User</th>
                                <!-- <th>Email ID</th>-->
                                <th>Login</th>
                                <th>Plan</th>
                                <th>Duration</th>
                                <th>ACTION</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($displayUser != null) {
                                $i = 10;
                                while ($result_data = mysqli_fetch_array($displayUser)) {
                                    $getCredit = $manage->getUserCreditById($result_data['id']);
                                    if ($getCredit != null) {
                                        $one_year_credit = $getCredit['one_year_credit'];
                                        if ($one_year_credit == null) {
                                            $one_year_credit = 0;
                                        }
                                        $three_year_credit = $getCredit['three_year_credit'];
                                        if ($three_year_credit == null) {
                                            $three_year_credit = 0;
                                        }
                                        $five_year_credit = $getCredit['five_year_credit'];
                                        if ($five_year_credit == null) {
                                            $five_year_credit = 0;
                                        }
                                        $life_time_credit = $getCredit['life_time_credit'];
                                        if ($life_time_credit == null) {
                                            $life_time_credit = 0;
                                        }
                                    } else {
                                        $one_year_credit = 0;
                                        $three_year_credit = 0;
                                        $five_year_credit = 0;
                                        $life_time_credit = 0;
                                    }
                                    $link = SHARED_URL . $result_data['custom_url'];
                                    $end_date = $result_data['expiry_date'];
                                    $password = rtrim($result_data['password'], "8523");
                                    $date = date("Y-m-d");
                                    $earlier = new DateTime("$date");
                                    $later = new DateTime("$end_date");

                                    $five_day = date('Y-m-d', strtotime(date_create("Y-m-d") . ' + 5 days'));
                                    if ($result_data['year'] != 'Life Time') {
                                        if ($end_date === $date) {
                                            $diff = "1";
                                        } elseif ($end_date < $date) {
                                            $diff = "Expired";
                                        } else {
                                            $diff = $later->diff($earlier)->format("%a");
                                        }
                                    } else {
                                        $diff = "Life Time";
                                    }

                                    $token_url = "http://sharedigitalcard.com/user/share-your-feedback.php?token=" . $security->encryptWebservice($result_data['id']);
                                    $payment_url = "http://sharedigitalcard.com/payment.php?token=" . $security->encryptWebservice($result_data['id']);
                                    $custom_url = $result_data['custom_url'];
                                    $gender = $result_data['gender'];
                                    $profilePath = "uploads/" . $result_data['email'] . "/profile/" . $result_data['img_name'];
                                    $total_availed = $manage->mu_displayParentUserDetailsCount($result_data['id']);
                                    $total_credit = $one_year_credit + $three_year_credit + $five_year_credit + $life_time_credit + $total_availed;
                                    ?>
                                    <tr style="<?php
                                    if ($result_data['year'] != 'Life Time') {
                                        if ($result_data['status'] == '0') {
                                            echo 'background-color: #f96b6b';
                                        } elseif ($diff == 0) {
                                            echo 'background-color: #fdc8ce';
                                        } elseif ($end_date <= $five_day) {
                                            echo 'background-color: #f9eabf;';
                                        }
                                    } ?>">
                                        <td><input type="checkbox" id="checkItem" name="check[]" class="checkbox1"
                                                   value="<?php echo $result_data["id"]; ?>"></td>
                                        <td>
                                            <a href="<?php echo SHARED_URL . $result_data['custom_url'] ?>"
                                               target="_blank">
                                                <div style="display: inline-block;vertical-align: top">
                                                    <img
                                                        src="<?php if (!file_exists($profilePath) && $gender == "Male" or $result_data['img_name'] == "") {
                                                            echo "uploads/male_user.png";
                                                        } elseif (!file_exists($profilePath) && $gender == "Female" or $result_data['img_name'] == "") {
                                                            echo "uploads/female_user.png";
                                                        } else {
                                                            echo $profilePath;
                                                        } ?>" class="user_profile_image">
                                                </div>
                                                <div style="display: inline-block;">
                                                    <?php
                                                    echo $result_data['name'];
                                                    if ($result_data['designation'] != "") {
                                                        echo "<p class='truncated_text' title='" . $result_data['designation'] . "'>" . $result_data['designation'] . "</p>";
                                                    }
                                                    if ($result_data['company_name'] != "") {
                                                        echo "<p class='truncated_text'title='" . $result_data['company_name'] . "'>" . $result_data['company_name'] . "</p>";
                                                    }
                                                    if ($result_data['designation'] == "" && $result_data['company_name'] == "") {
                                                        echo "<br>";
                                                    }

                                                    echo "<label class='label label-success'>" . $manage->getProfilePercent($result_data['id']) . "%</label>";


                                                    $created_status = false;
                                                    if (like_match('%dealer%', $result_data['referer_code']) == 1) {
                                                        $getDealer = $manage->getDealerProfile($result_data['referer_code']);
                                                        $dealer_id = $getDealer['user_id'];
                                                        echo ' <a href="view-dealer-profile.php?user_id=' . $security->encrypt($dealer_id) . '"><label class="label label-danger">By ' . $getDealer['name'] . '</label> </a>';
                                                        $created_status = true;
                                                    } else if (isset($result_data['created_by']) && $result_data['created_by'] != "") {
                                                        echo ' <label class="label label-danger">' . $result_data['created_by'] . '</label>';
                                                    } else {
                                                        $get_editor_by_id = $manage->getEditorProfile($result_data['editor_id']);
                                                        echo ' <label class="label label-info">Self Register - ' . $get_editor_by_id['name'] . '</label>';
                                                    }
                                                    if ($total_availed > 0 && $total_credit > 0) {
                                                        echo "<br><a href='manage-parent-child.php?id=" . $security->encrypt($result_data['id']) . "&name=" . $result_data['name'] . "&contact=" . $result_data['contact_no'] . "&email=" . $result_data['email'] . "&custom_url=" . $result_data['custom_url'] . "'>Total : " . $total_credit . " , Availed : " . $total_availed . "</a>";
                                                    }
                                                    ?>
                                                </div>
                                            </a>
                                        </td>
                                        <!--<td><?php /*echo $result_data['email']; */ ?></td>-->
                                        <td><?php echo $result_data['email'] . "<br>" . $result_data['contact_no']; ?>

                                            <br><?php
                                            if (isset($_SESSION['type']) && $_SESSION['type'] == "Admin") {
                                                echo $security->decrypt($password);
                                            }
                                            if ($result_data['user_notification'] != "") {
                                                $user_notification = $result_data['user_notification'];
                                                ?>
                                                <br><a onclick="setClipboard('<?php echo $user_notification; ?>')"><i
                                                        class="fa fa-bell"></i></a>
                                            <?php
                                            }

                                            ?></td>
                                        <td><?php echo $result_data['year'] . '<br>days : <label class="label label-success">' . $diff . '</label><br>Date : <label class="label label-info">' . $result_data['user_start_date'] . '</label>'; ?></td>

                                        <td style="position: relative">Start Date
                                            :</br><?php echo $result_data['start_date'] . " </br> "; ?>

                                            <form method="post" action="">
                                                Expiry Date :</br>
                                                <input type="date" name="expiry_date"
                                                       value="<?php echo $result_data['end_date']; ?>" <?php if (!isset($_GET['user_expiry_id'])) echo 'disabled' ?>>
                                                <?php
                                                if (isset($_SESSION['type']) && $_SESSION['type'] == "Admin") {
                                                    ?>
                                                    <div id="edit_icon_user" class="edit_icon">
                                                        <?php if (isset($_GET['user_expiry_id']) && $result_data['id'] == $security->decrypt($_GET['user_expiry_id'])) {
                                                            ?>
                                                            <button class="right_button1" name="cancel_button"><i
                                                                    class="fas wrong_button1 fa-times"></i></button>
                                                            <button class="right_button1" type="submit"
                                                                    name="update_expiry"><i
                                                                    class="fas right_check fa-check"></i></button>
                                                        <?php
                                                        } else { ?>
                                                            <a class="fas edit_color fa-pencil-alt"
                                                               href="<?php echo addUrlParam(array('user_expiry_id' => $security->encrypt($result_data['id']))); ?>"></a>
                                                        <?php
                                                        } ?>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </form>


                                        </td>

                                        <td>
                                            <ul class="header-dropdown">
                                                <li class="dropdown dropdown-inner-table">
                                                    <a href="javascript:void(0);" class="dropdown-toggle"
                                                       data-toggle="dropdown"
                                                       role="button" aria-haspopup="true" aria-expanded="false">
                                                        <i class="material-icons">more_vert</i>
                                                    </a>
                                                    <ul class="dropdown-menu pull-right">
                                                        <li>
                                                            <a href="user-management-view.php?user_id=<?php echo $security->encrypt($result_data['id']) ?>"><i
                                                                    class="fas fa-eye"></i>&nbsp;&nbsp;View more</a>
                                                        </li>
                                                        <li>
                                                            <a onclick="setClipboard('<?php echo $link; ?>')"><i
                                                                    class="fas fa-copy"></i>&nbsp;&nbsp;Copy URL</a>
                                                        </li>
                                                        <li>
                                                            <a onclick="setClipboard('<?php echo $token_url; ?>')"><i
                                                                    class="fas fa-copy"></i>&nbsp;&nbsp;Feedback URL</a>
                                                        <li>
                                                        <li>
                                                            <a onclick="setClipboard('<?php echo $payment_url; ?>')"><i
                                                                    class="fas fa-copy"></i>&nbsp;&nbsp;Payment URL</a>
                                                        </li>
                                                        <li>
                                                            <a href="user-session-edit.php?id=<?php echo $security->encrypt($result_data['id']) ?>&name=<?php echo $result_data['name'] ?>&contact=<?php echo $result_data['contact_no'] ?>&email=<?php echo $result_data['email'] ?>&custom_url=<?php echo $result_data['custom_url'] ?>">
                                                                <i class="fas fa-edit"></i>&nbsp;&nbsp;Edit</a>
                                                        </li>
                                                        <?php
                                                        if (isset($_SESSION['type']) && $_SESSION['type'] == "Admin") {
                                                            ?>
                                                            <li>
                                                                <a href="upgrade-plan.php?user_id=<?php echo $security->encrypt($result_data['id']) ?>">
                                                                    <i class="fa fa-money" aria-hidden="true"></i>&nbsp;&nbsp;Upgrade
                                                                    Plan</a>
                                                            </li>
                                                            <li>
                                                                <a onclick="openCredit('<?php echo $result_data['id'] ?>')">
                                                                    <i class="fa fa-money" aria-hidden="true"></i>&nbsp;&nbsp;Add
                                                                    Credit</a>
                                                            </li>
                                                            <?php

                                                            if (like_match('%dealer%', $result_data['referer_code']) != 1) {
                                                                ?>
                                                                <li>
                                                                    <a href="#"
                                                                       onclick="updateMappedDealer('<?php echo $result_data['id']; ?>','dealer')"
                                                                       data-toggle="modal" data-target="#mappeDealer">
                                                                        <i class="fas fa-file"></i>&nbsp;&nbsp;Mapped
                                                                        Dealer</a>
                                                                </li>
                                                                <li>
                                                                    <a href="#"
                                                                       onclick="updateMappedDealer('<?php echo $result_data['id']; ?>','mapped')"
                                                                       data-toggle="modal" data-target="#mappedEditor">
                                                                        <i class="fas fa-file"></i>&nbsp;&nbsp;Mapped
                                                                        Editor</a>
                                                                </li>
                                                                <li>
                                                                    <a href="#"
                                                                       onclick="updateMappedDealer('<?php echo $result_data['id']; ?>','converted')"
                                                                       data-toggle="modal" data-target="#mappedEditor">
                                                                        <i class="fas fa-file"></i>&nbsp;&nbsp;Converted By</a>
                                                                </li>
                                                            <?php
                                                            }
                                                        }
                                                        ?>
                                                        <li>
                                                            <a href="#"
                                                               onclick="addDomainLink('<?php echo $result_data['id']; ?>','<?php echo $result_data['domain_link'] ?>')">
                                                                <i class="fas fa-envelope"></i>&nbsp;&nbsp;Add Domain
                                                                link</a>
                                                        </li>
                                                        <li>
                                                            <a href="send-email-sms.php?user_id=<?php echo $security->encrypt($result_data['id']) ?>">
                                                                <i class="fas fa-envelope"></i>&nbsp;&nbsp;Email &
                                                                SMS</a>
                                                        </li>
                                                        <?php
                                                        if (isset($_SESSION['type']) && $_SESSION['type'] == "Admin") {
                                                            ?>
                                                            <li>
                                                                <a onclick="return confirm('Are You sure you want to <?php echo $result_data['status'] == 1 ? 'Block' : 'Unblock'; ?>?');"
                                                                   href="user-management.php?publishData=<?php echo $security->encrypt($result_data['id']) ?>&action=<?php echo $result_data['status'] == 1 ? "Block" : "Unblock"; ?>"
                                                                   class="<?php echo $result_data['status'] == 0 ? "fa fa-unlock" : "fa fa-ban"; ?>">
                                                                    &nbsp;&nbsp;<?php echo $result_data['status'] == 1 ? "Block" : "Unblock"; ?></a>
                                                            </li>

                                                            <li>
                                                                <a href="user-management.php?delete_data=<?php echo $security->encrypt($result_data['id']) ?>&email=<?php echo $result_data['email'] ?>"
                                                                   onclick="return confirm('Are You sure you want to delete?');">
                                                                    <i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
                                                            </li>
                                                        <?php
                                                        }
                                                        ?>
                                                        <!--<li>
                                                            <a onclick="return confirm('Are You sure you want to <?php /*echo $result_data['status'] == 0 ? 'publish' : 'unpublish'; */ ?>?');"
                                                               href="user-management.php?publishData=<?php /*echo $security->encrypt($result_data['id']) */ ?>&action=<?php /*echo $result_data['status'] == 0 ? "publish" : "unpublish"; */ ?> "><i
                                                                    class="fas <?php /*echo $result_data['status'] == 0 ? "fa-upload" : "fa-download"; */ ?>"></i>&nbsp;&nbsp;<?php /*echo $result_data['status'] == 1 ? "Unpublish" : "Publish"; */ ?>
                                                            </a>
                                                        </li>-->
                                                    </ul>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                            <?php
                            } else {
                                ?>
                                <tr>
                                    <td colspan="10" class="text-center">No data found!</td>
                                </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                        </table>
                        <div>

                            <ul class="pagination m-0">
                                <?php // if($page_no > 1){ echo "<li><a href='?page_no=1'>First Page</a></li>"; } ?>

                                <li <?php if ($page_no <= 1) {
                                    echo "class='disabled'";
                                } ?>>
                                    <a <?php if ($page_no > 1) {
                                        echo "href='?page_no=$previous_page'";
                                    } ?>>Previous</a>
                                </li>

                                <?php
                                if ($total_no_of_pages <= 10) {
                                    for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {
                                        if ($counter == $page_no) {
                                            echo "<li class='active'><a>$counter</a></li>";
                                        } else {
                                            echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                                        }
                                    }
                                } elseif ($total_no_of_pages > 10) {

                                    if ($page_no <= 4) {
                                        for ($counter = 1; $counter < 8; $counter++) {
                                            if ($counter == $page_no) {
                                                echo "<li class='active'><a>$counter</a></li>";
                                            } else {
                                                echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                                            }
                                        }
                                        echo "<li><a>...</a></li>";
                                        echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
                                        echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                                    } elseif ($page_no > 4 && $page_no < $total_no_of_pages - 4) {
                                        echo "<li><a href='?page_no=1'>1</a></li>";
                                        echo "<li><a href='?page_no=2'>2</a></li>";
                                        echo "<li><a>...</a></li>";
                                        for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {
                                            if ($counter == $page_no) {
                                                echo "<li class='active'><a>$counter</a></li>";
                                            } else {
                                                echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                                            }
                                        }
                                        echo "<li><a>...</a></li>";
                                        echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
                                        echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                                    } else {
                                        echo "<li><a href='?page_no=1'>1</a></li>";
                                        echo "<li><a href='?page_no=2'>2</a></li>";
                                        echo "<li><a>...</a></li>";

                                        for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
                                            if ($counter == $page_no) {
                                                echo "<li class='active'><a>$counter</a></li>";
                                            } else {
                                                echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                                            }
                                        }
                                    }
                                }
                                ?>

                                <li <?php if ($page_no >= $total_no_of_pages) {
                                    echo "class='disabled'";
                                } ?>>
                                    <a <?php if ($page_no < $total_no_of_pages) {
                                        echo "href='?page_no=$next_page'";
                                    } ?>>Next</a>
                                </li>
                                <?php if ($page_no < $total_no_of_pages) {
                                    echo "<li><a href='?page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
                                } ?>
                            </ul>
                            <div style="padding-left: 10px;">
                                <strong>Page <?php echo $page_no . " of " . $total_no_of_pages; ?></strong>
                            </div>
                        </div>
                    </div>
                            </div></div>


                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="addUser" role="dialog">
    <div class="modal-dialog cust-model-width">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header cust-upi-madal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create Digital Card</h4>
            </div>
            <div class="modal-body">
                <div class="body">
                    <form id="forgot_password" method="POST">
                        <?php if ($error) {
                            ?>
                            <div class="alert alert-danger">
                                <?php if (isset($errorMessage)) echo $errorMessage; ?>
                            </div>
                        <?php
                        } else if (!$error && $errorMessage != "") {
                            ?>
                            <div class="alert alert-success">
                                <?php if (isset($errorMessage)) echo $errorMessage; ?>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>

                            <div class="form-line">
                                <input name="txt_name" type="text" class="form-control" placeholder="Full name"
                                       autofocus>
                            </div>
                        </div>
                        <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>

                            <div class="form-line">
                                <input name="txt_email" type="email" class="form-control" placeholder="Email"
                                       autofocus>
                            </div>
                        </div>
                        <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">account_box</i>
                        </span>

                            <div class="form-line">
                                <input type="number" name="txt_contact" class="form-control"
                                       placeholder="Contact Number" autofocus onkeypress="return isNumberKey(event)"
                                       required="required"
                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                       maxlength="10">
                            </div>
                        </div>
                        <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">home</i>
                        </span>

                            <div class="form-line">
                                <input name="txt_company_name" type="text" class="form-control"
                                       placeholder="Company Name(Optional)"
                                       autofocus
                                       value="<?php if (isset($_POST['txt_company_name'])) echo $_POST['txt_company_name']; ?>">
                            </div>
                        </div>

                        <div class="input-group">
                         <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <select id="gender" name="gender" class="form-control">
                                        <option name="male" value="Male">Male
                                        </option>
                                        <option name="female" value="Female">Female
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="input-group">
                         <span class="input-group-addon">
                          <i class="fa fa-globe" style="font-size: 18px;"></i>
                        </span>

                            <div class="form-group form-float">
                                <div class="form-line" style="z-index: 999">
                                    <select id="country" name="country" class="form-control" data-live-search="true"
                                            onchange="getStateDataByCountry(this.value)">
                                        <?php
                                        $countries_array = $manage->getCountryCategory();
                                        while ($value = mysqli_fetch_array($countries_array)) {
                                            ?>
                                            <option
                                                value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="input-group">
                         <span class="input-group-addon">
                          <i class="fa fa-globe" style="font-size: 18px;"></i>
                        </span>

                            <div class="form-group form-float">
                                <div class="form-line" style="z-index: 99">
                                    <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                    <!--<input type="text" name="txt_state"
                                                                           class="form-control"
                                                                           placeholder="Enter State"
                                                                           value="<?php /*if (isset($state) && $state !=""){ echo $state; }else{ echo $current_region; } */ ?>">-->
                                    <div id="state_select">
                                        <select name="txt_state"
                                                class="gender_li form-control"
                                                onchange="getCityByStateId(this.value)">
                                            <option value="">Select an option</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="input-group">
                         <span class="input-group-addon">
                          <i class="fa fa-globe" style="font-size: 18px;"></i>
                        </span>

                            <div class="form-group form-float">
                                <div class="form-line" style="z-index: 9">
                                    <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                    <!--<input type="text" name="txt_city"
                                                                           class="form-control"
                                                                           placeholder="Enter City"
                                                                           value="<?php /*if (isset($city) && $city !=""){ echo $city; }else{ echo $current_city; } */ ?>">-->
                                    <div id="city_select">
                                        <select name="txt_city"
                                                class="gender_li form-control">
                                            <option value="">Select an option</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>

                            <div class="form-line">
                                <input name="txt_password" type="text" class="form-control"
                                       placeholder=" Password" value="<?php echo $random_password; ?>"
                                       autofocus>
                            </div>
                        </div>
                        <div class="input-group">
                            <input type="checkbox" name="online_search" checked value="1"> Do You Want to promote your
                            business online.
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary" name="btn_submit">Create Digital Card
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "assets/common-includes/footer_includes.php" ?>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog cust-model-width">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header cust-upi-madal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Custom Notification</h4>
            </div>
            <div class="modal-body">
                <div class="body">
                    <form id="upi_form_validation" method="POST" action="">
                        <input type="hidden" name="txt_id" class="txt_id">

                        <div>
                            <label class="form-label">Title</label>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input name="txt_title" class="form-control"
                                           placeholder="Enter Title">
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Remark</label>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <textarea class="form-control" name="txt_remark"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group form_inline form-float">
                            <button class="btn btn-primary waves-effect form-control"
                                    name="btn_update" type="submit">
                                Send Notification
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myDomainModal" role="dialog">
    <div class="modal-dialog cust-model-width">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header cust-upi-madal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Update Domain ink</h4>
            </div>
            <div class="modal-body">
                <div class="body">
                    <form id="upi_form_validation" method="POST" action="">
                        <input type="hidden" name="d_user_id" class="d_user_id">

                        <div>
                            <label class="form-label">Enter Link</label>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input name="txt_link" class="form-control txt_link"
                                           placeholder="Enter Domain Link">
                                </div>
                            </div>
                        </div>
                        <div class="form-group form_inline form-float">
                            <button class="btn btn-primary waves-effect form-control"
                                    name="btn_domain_link" type="submit">
                                Update Domain ink
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mappedEditor" role="dialog">
    <div class="modal-dialog cust-model-width">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header cust-upi-madal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select Editor To Mapped</h4>
            </div>
            <div class="modal-body">
                <div class="body">
                    <form id="upi_form_validation" method="POST" action="">
                        <input type="hidden" name="d_user_id" class="d_user_id">

                        <div>
                            <label class="form-label">Select Editor</label>

                            <div class="form-group form-float">
                                <select class="form-control show-tick" name="drp_editor_user">
                                    <option value="">Select Editor</option>
                                    <?php
                                    if ($get_editor != null) {
                                        foreach ($get_all_editor as $editor_row) {
                                            ?>
                                            <option
                                                value="<?php echo $editor_row['id']; ?>"><?php echo $editor_row['name'] . "-" . $editor_row['contact_no']; ?></option>

                                        <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group form_inline form-float">
                            <button class="btn btn-primary waves-effect form-control"
                                    name="btn_mapped_editor" type="submit">
                                Update
                            </button>
                            <button class="btn btn-primary waves-effect form-control"
                                    name="btn_converted_editor" type="submit">
                                Update
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mappeDealer" role="dialog">
    <div class="modal-dialog cust-model-width">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header cust-upi-madal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select Dealer To Mapped</h4>
            </div>
            <div class="modal-body">
                <div class="body">
                    <form id="upi_form_validation" method="POST" action="">
                        <input type="hidden" name="d_user_id" class="d_user_id">

                        <div>
                            <label class="form-label">Select dealer</label>

                            <div class="form-group form-float">
                                <select class="form-control show-tick" name="drp_dealer_user" data-live-search="true">
                                    <option value="">Select Dealer</option>
                                    <?php
                                    if ($approveDealer != null) {
                                        foreach ($get_all_dealer as $form_data) {
                                            $dealer_code = $form_data['dealer_code'];
                                            ?>
                                            <option
                                                value="<?php echo $dealer_code; ?>" <?php if (isset($_POST['drp_dealer']) && $_POST['drp_dealer'] == $dealer_code) echo 'selected="selected"'; ?>><?php echo $form_data['name'] . "-" . $form_data['contact_no']; ?></option>

                                        <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group form_inline form-float">
                            <button class="btn btn-primary waves-effect form-control"
                                    name="btn_mapped_dealer" type="submit">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myCreditModal" role="dialog">
    <div class="modal-dialog cust-model-width">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header cust-upi-madal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Upgrade Credit To The User </h4>
            </div>
            <div class="modal-body">
                <div class="body">
                    <form id="upi_form_validation" method="POST" action="">
                        <input type="hidden" name="d_user_id" class="d_user_id">

                        <div>
                            <label class="form-label">Select Year</label>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <select class="form-control show-tick" name="drp_year">
                                        <option value="">Select Year</option>
                                        <?php
                                        if ($displayPlan2 != null) {
                                            while ($row = mysqli_fetch_array($displayPlan2)) {
                                                $year = $row['year'];
                                                if ($year != 'Free Trail (5 days)') {
                                                    ?>
                                                    <option
                                                        value="<?php echo $year; ?>"><?php echo $year; ?></option>

                                                <?php }
                                            }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="credit_user_id" id="credit_user_id">

                        <div>
                            <label class="form-label">Enter Quantity</label>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input class="form-control" type="number" name="quantity"
                                           placeholder="Enter Quantity">
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="form-group form-float">
                                <input type="checkbox" name="chk_invoice" value="1"> do you want to make invoice.
                            </div>
                        </div>
                        <div class="form-group form_inline form-float">
                            <button class="btn btn-primary waves-effect form-control"
                                    name="btn_update_credit" type="submit">
                                Update User Credit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function addDomainLink(id, domain_link) {
        $('.d_user_id').val(id);
        $('.txt_link').val(domain_link);
        $('#myDomainModal').modal('show');
    }
    function updateMappedDealer(id,val) {
        if(val == 'mapped'){
            $('button[name=btn_converted_editor]').hide();
            $('button[name=btn_mapped_editor]').show();
        }else{
            $('button[name=btn_converted_editor]').show();
            $('button[name=btn_mapped_editor]').hide();
        }
        $('.d_user_id').val(id);
    }

</script>
<?php
if ($error && $errorMessage != "") {
    ?>
    <script>
        $('.open_digi').click();
    </script>
<?php
}
?>
<script>
    function openCredit(user_id) {
        $('#credit_user_id').val(user_id);
        $('#myCreditModal').modal('show');
    }
</script>
<script>
    $("#checkAl").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
    $(".user_amount").click(function () {
        var $row = $(this).closest("tr");    // Find the row
        var amt = $row.find(".nr").text(); // Find the text

    });
    $(document).ready(function () {

        $(".checkbox1").change(function () {
            //Create an Array.
            var selected = new Array();
            $('input[type="checkbox"]:checked').each(function () {
                selected.push(this.value);
            });
            if (selected.length > 0) {
                $('.txt_id').val(selected.join(","));
                $('.extra_day').val(selected.join(","));
                $('.deleted_id').val(selected.join(","));

            }

        });

    });
</script>
<script>
    function getStateDataByCountry(value) {
        var dataString = 'country_id=' + value;
        if (value != '') {
            $.ajax({
                url: "get_city_ajax.php",
                type: "POST",
                data: dataString,
                success: function (html) {
                    $('#state_select').html(html);
                }
            });
        } else {
            $('#state_select').html(' <select name="txt_city" class="form-control"><option value="">select an option</option></select>');
        }
    }
    function getCityByStateId(value) {

        var dataString = 'state_id=' + value;
        if (value != '') {
            $.ajax({
                url: "get_city_ajax.php",
                type: "POST",
                data: dataString,
                success: function (html) {
                    $('#city_select').html(html);
                }
            });
        } else {
            $('#city_select').html(' <select name="txt_city" class="form-control"><option value="">select an option</option></select>');
        }
    }
</script>
</body>
</html>