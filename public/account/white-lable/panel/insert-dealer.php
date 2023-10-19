<?php
include "controller/ManageAdminApp.php";
$manage = new ManageAdminApp();
include "controller/validator.php";
$validate = new Validator();
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$controller = new Controller();
$con = $controller->connect();
include "controller/config data.php";
include "sendMail/sendMail.php";

$today_date = date('Y-m-d H:i:s');

if(isset($_POST['txt_cust_name']) && isset($_POST['txt_company']) && isset($_POST['txt_domain']) && isset($_POST['txt_email']) && isset($_POST['cont_number']) && isset($_POST['alt_cont_number']) && isset($_POST['expiry_date']) ){

    $cust_name = $_POST['txt_cust_name'];
    $company = $_POST['txt_company'];
    $domain = $_POST['txt_domain'];
    $email_id = $_POST['txt_email'];
    $cont_no = $_POST['cont_number'];
    $alt_cont_no = $_POST['alt_cont_number'];
    $expiry = $_POST['expiry_date'];
    $domain_link = $_POST['digital_domain_name'];
    $password = $security->encrypt(12345678). "8523";

//`customer_name`, `company_name`, `domain_name`, `email_id`, `contact_no`, `alter_contact_no`, `company_logo`, `expiry_date`, `password`, `about_img`, `about_desc`, `about_box_f`, `about_box_s`, `slider_image`, `slider_title`, `slider_desc`, `slider_color`, `created_at`, `created_by`

    $insertArray = array('customer_name'=>$cust_name,'company_name'=>$company,'domain_name'=>$domain,'email_id'=>$email_id,'contact_no'=>$cont_no,'alter_contact_no'=>$alt_cont_no,'expiry_date'=>$expiry,'domain_link_name'=>$domain_link,'password'=>$password,'about_desc'=>ABOUT_DESC,'about_box_f'=>ABOUT_BOX_1,'about_box_s'=>ABOUT_BOX_2,'slider_title'=>SLIDER_TITLE,'slider_desc'=>SLIDER_DESC,'slider_color'=>SLIDER_COLOR,'theme_font'=>THEME_FONT,'theme_color'=>THEME_COLOR,'created_at'=>$today_date,'created_by'=>$_SESSION['email']);
   /* echo '<pre>';
	var_dump($insertArray);
	echo '</pre>';*/
	$insertData = $manage->insert($manage->dealerTable,$insertArray);
    if($insertData){
        echo "Data Save Successfully";
        $message ='<!DOCTYPE html>
<html>
<head>
    <title>Share digital card</title>
    <style>
        body{
            background: #f1f1f1;
        }
        @media only screen and (max-width: 600px) {
            .main {
                width: 320px !important;
            }

            .top-image {
                width: 30% !important;
            }

            .inside-footer {
                width: 320px !important;
            }

            table[class="contenttable"] {
                width: 320px !important;
                text-align: left !important;
            }

            td[class="force-col"] {
                display: block !important;
            }

            td[class="rm-col"] {
                display: none !important;
            }

            .mt {
                margin-top: 15px !important;
            }

            *[class].width300 {
                width: 255px !important;
            }

            *[class].block {
                display: block !important;
            }

            *[class].blockcol {
                display: none !important;
            }

            .emailButton {
                width: 100% !important;
            }

            .emailButton a {
                display: block !important;
                font-size: 18px !important;
            }
        }
    </style>
</head>
<body link="#00a5b5" vlink="#00a5b5" alink="#00a5b5">
<table class=" main contenttable" align="center"
       style="font-weight: normal;border-collapse: collapse;border: 0;margin-left: auto;margin-right: auto;padding: 0;font-family: Arial, sans-serif;color: #000;background-color: white;font-size: 16px;line-height: 26px;width: 600px;">
    <tr>
        <td class="border"
            style="border-collapse: collapse;border: 1px solid #eeeff0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #000;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;">
            <table
                style="font-weight: normal;border-collapse: collapse;border: 0;margin: 0;padding: 0;font-family: Arial, sans-serif;">

                <tr>
                    <td valign="top" class="side title"
                        style="border-collapse: collapse;border: 0;margin: 0;padding: 20px;-webkit-text-size-adjust: none;color: #000;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;vertical-align: top;background-color: white;border-top: none;">
                        <table
                            style="font-weight: normal;border-collapse: collapse;border: 0;margin: 0;padding: 0;font-family: Arial, sans-serif;">
                            <tr>
                                <td class="head-title"
                                    style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #000;font-family: Arial, sans-serif;font-size: 24px;line-height: 34px;font-weight: bold; text-align: center;">
                                    <div class="mktEditable" id="main_title">
                                        Thank You!<br>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="top-padding"
                                    style="border-collapse: collapse;border: 0;margin: 0;padding: 15px 0;-webkit-text-size-adjust: none;color: #000;font-family: Arial, sans-serif;font-size: 16px;line-height: 21px;">
                                    <hr size="1" color="#1a73e9">
                                </td>
                            </tr>
                            <tr>
                                <td class="text"
                                    style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #000;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;">
                                    <div class="mktEditable" id="main_text" style="text-align: center">
                        Your account details credentials to access share digital website are as follows:
                                    </div>
                                    <br>
                                    <table
                                        style="font-weight: normal;border-collapse: collapse;border: 0;margin: 0;padding: 0;font-family: Arial, sans-serif;margin: 0 auto;margin-bottom: 20px;font-size: 18px;font-weight: 600;line-height: 30px;color:#000000">
                                        <tr>
                                            <td>Login ID:</td>
                                            <td>' . $email_id . '</td>
                                        </tr>
                                        <tr>
                                            <td>Password:</td>
                                            <td> 12345678 </td>
                                        </tr>
                                        <tr>
                                            <td>Expiry Date:</td>
                                            <td> '. $expiry .' </td>
                                        </tr>
                                        <tr>
                                            <td>Your Domain:</td>
                                            <td> '. $domain .' </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td class="text"
                                    style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #000;font-family: Arial, sans-serif;font-size: 16px;line-height: 24px;">
                                    <div class="mktEditable" id="download_button" style="text-align: center;">
                                        <a style="color:#ffffff; background-color: #1a73e9; border: 20px solid #1a73e9; border-left: 20px solid #1a73e9; border-right: 20px solid #1a73e9; border-top: 10px solid #1a73e9; border-bottom: 10px solid #1a73e9;border-radius: 3px; text-decoration:none;"
                                           href="index.php">Log in to Your Account
                    </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #000;font-family: Arial, sans-serif;font-size: 16px;line-height: 24px;">
                                    &nbsp;<br>
                                </td>
                            </tr>
                            <tr>
                                <td class="text"
                                    style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #000;font-family: Arial, sans-serif;font-size: 16px;line-height: 24px;">
                                    <div class="mktEditable" id="main_text">
                                        If you have any questions or need any help, dont hesitate to contact our support team on support@gmail.com
                                        <br>
                                    </div>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
                <br>
                <tr bgcolor="#fff" style="border-top: 4px solid #1a73e9;">
                    <td valign="top" class="footer"
                        style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #000;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;background: #fff;text-align: center;">
                        <table
                            style="font-weight: normal;border-collapse: collapse;border: 0;margin: 0;padding: 0;font-family: Arial, sans-serif;">
                            <tr>
                                <td class="inside-footer" align="center" valign="middle"
                                    style="border-collapse: collapse;border: 0;margin: 0;padding: 20px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 12px;line-height: 16px;vertical-align: middle;text-align: center;width: 580px;">
                                    <div id="address" class="mktEditable">
                                        <b style="font-size:18px">Share Digital Card</b><br>
                                        <p style="color: #1a73e9;"> &copy; Kubic technology</p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>';

        $insertPlanfiftyArray = array('user_id'=>$insertData,'plan_name'=>'50 plan','plan_price'=>0);
        $insertFranchiseData = $manage->insert($manage->franchiseTable,$insertPlanfiftyArray);

        $insertPlanEightyArray = array('user_id'=>$insertData,'plan_name'=>'80 plan','plan_price'=>0);
        $insertFranchiseData = $manage->insert($manage->franchiseTable,$insertPlanEightyArray);

        $insertPlanHunArray = array('user_id'=>$insertData,'plan_name'=>'100 plan','plan_price'=>0);
        $insertFranchiseData = $manage->insert($manage->franchiseTable,$insertPlanHunArray);


        $send_sms = $manage->sendMail("Manage Dealer", $email_id, "Login credentials", $message);
    }else{
        echo "not inserted";
    }

}


?>