<?php
include "controller/ManageAdminApp.php";
$manage = new ManageAdminApp();
include "controller/validator.php";
$validate = new Validator();
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$controller = new Controller();
$con = $controller->connect();

//include "panel/sendMail/sendMail.php";

/*echo "hello";
exit;*/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

error_reporting(0);


$host = parse_url('https://' . $_SERVER['HTTP_HOST'] . '/', PHP_URL_HOST);
$domains = explode('.', $host);
$url = $domains[count($domains) - 2];

$fetchDataFromDomain = $manage->getDealerFromDomain($url);
$company_name = $fetchDataFromDomain['company_name'];
$logo = $fetchDataFromDomain['company_logo'];
$company_email = $fetchDataFromDomain['email_id'];
$domain_link_name = $fetchDataFromDomain['domain_link_name'];

$hosts = $fetchDataFromDomain['smtp_host'];
$username = $fetchDataFromDomain['smtp_username'];
$pawd = $fetchDataFromDomain['smtp_password'];
$port = $fetchDataFromDomain['smtp_port'];

if($hosts == "" && $username == "" && $pawd == "" && $port == ""){
    $hosts = "smtp.gmail.com";
    $username = "kubic.testing2@gmail.com";
    $pawd = "Kubic@2021";
    $port = "587";
}

if (!empty($_POST)) {
    $fullname = $_POST['txt_name'];
    $email = $_POST['txt_email'];
    $contact = $_POST['txt_contact'];
    $company = $_POST['txt_company'];
    $gender = $_POST['drp_gender'];
    $country_id = $_POST['drp_country'];
    $state_id = $_POST['state'];
    $city_id = $_POST['city'];
    $password = $_POST['txt_password'];
    $con_password = $_POST['txt_con_password'];
    $promote_business = $_POST['promote_business'];
    $terms_accepted = $_POST['chk_terms'];
    $api_key = "6fb9fa56-a66e-490b-a8dd-ad6a37e65f62";

    /*$getCountry = $manage->getCountryById($country_id);
    $country = $getCountry['name'];
    $getState = $manage->getStateById($state_id);
    $state = $getState['name'];*/
    $getCity = $manage->getCityById($city_id);
    $city = $getCity['name'];

    if ($promote_business == "") {
        $promote_business = 0;
    }



    $result = "$fullname,$email,$contact,$country_id,$state_id,$city,$gender,$password,$company,$url,$terms_accepted,$promote_business,$api_key";

    $token = $security->encryptWebservice($result);
//    echo $token;

    $postRequest = array(
        'token' => $token
    );
    $cURLConnection = curl_init('https://sharedigitalcard.com/SDCWebsiteRegistration');
    curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
    curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

    $json = curl_exec($cURLConnection);
    $encode = json_decode($json, true);
    print_r($json);

    if (!$encode['error']) {
        $subject = "hello $fullname, your digital card has been created successfully!";
        $message = '<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0;">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500" rel="stylesheet">
	<style>
    /* Reset styles */
    body {
      font-family: Arial, sans-serif;
      height: 100% !important;
      margin: 0;
      min-width: 100%;
      padding: 0;
      width: 100% !important;
    }
    body, table, td, div, p, a {
      line-height: 100%;
      text-size-adjust: 100%;
      -webkit-font-smoothing: antialiased;
      -ms-text-size-adjust: 100%;
      -webkit-text-size-adjust: 100%;
    }
    table, td {
      border-collapse: collapse !important;
      border-spacing: 0;
      mso-table-lspace: 0pt;
      mso-table-rspace: 0pt;
    }
    p {
        margin-block-start: .5em;
        margin-block-end: .5em;
    }
    img {
      border: 0;
      line-height: 100%;
      outline: none;
      text-decoration: none;
      -ms-interpolation-mode: bicubic;
    }
    .action-item {
      border: 1px solid #005f7f;
      color: #005f7f;
      padding: 8px 20px;
    }
    .action-item:hover {
      background-color: #2a923d;
      border: 1px solid #2a923d;
      color: #fff;
    }
    #outlook a {padding: 0;}
    .ReadMsgBody {width: 100%;}
    .ExternalClass {width: 100%;}
    .ExternalClass,
    .ExternalClass p,
    .ExternalClass span,
    .ExternalClass font,
    .ExternalClass td,
    .ExternalClass div {line-height: 100%;}

    /* Rounded corners for advanced mail clients only */
    @media all and (min-width: 560px) {
      .container {
        border-radius: 8px;
        -webkit-border-radius: 8px;
        -moz-border-radius: 8px;
        -khtml-border-radius: 8px;
      }
    }
    /* Set color for auto links (addresses, dates, etc.) */
    a, a:hover {color: #005f7f;}
    .footer a,
    .footer a:hover {
      color: #999999;
    }
 	</style>
	<!-- MESSAGE SUBJECT -->
	<title>Green Team</title>
</head>
<body topmargin="0" rightmargin="0" bottommargin="0" leftmargin="0" marginwidth="0" marginheight="0" width="100%" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%; height: 100%; -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; background-color: #ececec; color: #333333;" bgcolor="#ececec" text="#333333">
<!-- WRAPPER TABLE -->
<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%;">
  <tr>
    <br>
    <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;" bgcolor="#ececec">
      <!-- WRAPPER -->
      <table border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#ffffff" width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0; width: inherit; max-width: 560px; margin: 30px 0 0 0;">
        <!-- PRIMARY IMAGE -->
        <tr>
          <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-top: 5px;">';

        if($logo !=""){
         $message .= '<img border="0" vspace="0" hspace="0" src="panel/uploads/logo/' . $logo . '" alt="' . $company_name . '" width="560" style="border: none; color: #333333; display: block; font-size: 13px; margin: 0; max-width: 560px; padding: 0; outline: none; text-decoration: none; width: 100%; -ms-interpolation-mode: bicubic;"/>';
        }

         $message .= ' </td>
        </tr>
        <!-- CONTENT -->
        <tr>
          <td valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;">

            <h4 style="color: #333;  font-size: 20px; font-weight: 800; line-height: 100%; margin: 20px 0 10px 0; padding: 0;">hi ' . $fullname . ', Your account details mentioned below</h4><br>
            <ul style="list-style: none;margin: 0 auto">
              <li style="font-size: 15px; font-weight: 400; line-height: 160%; color: #333333; font-family: Arial, sans-serif;"><strong>Username</strong>: ' . $contact . ' / ' . $email . '</li>
              <li style="font-size: 15px; font-weight: 400; line-height: 160%; color: #333333; font-family: Arial, sans-serif;"><strong>Password</strong>: ' . $password . '</li>
            </ul>
            <br>

          </td>
        </tr>
        <tr>
          <td valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td style="padding-bottom: 0px;" align="center">
                    <table border="0" cellspacing="0" cellpadding="0" align="center">
                      <tbody>
                        <!-- Action Item -->
                        <tr>
                          <td align="center" style="background-color: #327c30; border-radius: 3px; font-size: 17px; letter-spacing: 1px; padding: 5px 20px;">
                            <a href="' . $domain_link_name . '/login" target="_blank" style="background-color: #327c30; border-radius: 3px; color: #fff; display: inline-block; font-weight: 500; line-height: 30px; text-align: center; text-decoration: none;">
                              Login Now
                            </a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        <tr>
        <tr>
          <td valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;">
            <p style="color: #333333; font-size: 15px; font-weight: 400; font-family: Arial, sans-serif; line-height: 160%;">&nbsp;</p>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>';
        try {
//            $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = $hosts;
            $mail->SMTPAuth = true;
            $mail->Username = $username;
            $mail->Password = $pawd;
            $mail->SMTPSecure = 'tls';
            $mail->Port = $port;

            $mail->setFrom('atulkumar@kubictechnology.in', $company_name);
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->AltBody = 'Body in plain text for non-HTML mail clients';
            $mail->send();

        } catch (Exception $e) {
//            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    curl_close($cURLConnection);

}

?>