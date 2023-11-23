<?php
session_start();
include("controller.php");
include "../vendor/autoload.php";
// include "../vendor/imagine/imagine/src/Gd/Imagine.php";
// include "../vendor/imagine/imagine/src/Image/Box.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
class ManageUser
{
    public $loginTable = "tb_login";

    public $profileTable = "tb_user_profile";

    public $bankDetailsTable = "tb_bank_details";

    public $clientReviewTable = "tb_client_review";

    public $serviceTable = "tb_services";

    public $imageTable = "tb_image";

    public $videoTable = "tb_video";

    public $clientTable = "tb_clients";

    public $ourTeamTable = "tb_our_team";

    public $gatewayTable = "tb_gateway";

    public $sliderTable = "tb_image_slider";

    public $aboutUsTable = "tb_about_us";

    public $sectionTable = "tb_section";

    public $sectionStatusTable = "tb_section_status";

    public $sectionIconTable = "tb_section_icon";

    public $logoTable = "tb_logo";

    public $mailSettingTable = "tb_mail_setting";

    public $logTable = "tb_log_file";

    public $planTable = "tb_subscription_plan";

    public $userSubscriptionTable = "tb_user_subscription";

    public $customUrlLogTable = "tb_custom_log";

    public $blogTable = "tb_blog";

    public $dealerLoginTable = "tb_dealer_login";

    public $dealerProfileTable = "tb_dealer_profile";

    public $mobileThemeTable = "tb_mobile_theme";

    public $bookmarkTable = "tb_bookmark";

    public $temporaryTable = "tb_temp_table";

    public $dealerPlanTable = "tb_dealer_subscription_plan";

    public $sectionNameTable = "tb_section_name";

    public $feedbackInvitationTable = "tb_feedback_invitation";

    public $walletHistoryTable = "tb_wallet_history";

    public $couponTable = "tb_coupon";

    public $serviceRequestTable = "tb_service_request";

    public $notificationTable = "tb_notification";
    public $coverProfileTable = "tb_cover_profile";
    public $payPalTable = "tb_paypal";


    /** This method perform the login operation for login table
     * @param $email this variable used as a username
     * @param $pass this variable used as a password
     * @return bool|mysqli_result|null if genericSelectToIterate success then it return true else false
     */

    function adminLogin($email, $pass)
    {
        $status = false;
        $controller = new Controller();
        $sql_query = "select * from " . $this->loginTable . " where (email='" . $controller->clean($email) . "' or contact_no='" . $email . "') and password='" . $pass . "'";
        $result = $controller->genericSelectToIterateUsingProcedure($sql_query);
        return $result;
    }

    function adminLoginV2($email, $pass)
    {
        $controller = new Controller();
        $stmt = $controller->prepare("call loginchecker(?,?)");
        $stmt->bind_param("ss", $email, $pass);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }

    function getUserProfile($user_id)
    {
        $controller = new Controller();
        $sql_query = "call mu_getSpecificUserProfileById(?)";
        $type = "i";
        $values = array($user_id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql_query, $type, $values);
        return $result;
    }

    function getUserProfileForLogin($email, $pass)
    {
        $controller = new Controller();
        $sql_query = "call mu_getUserProfileForLogin(?,?)";
//        $sql_query = "call mu_getUserProfileForLogin('$email','$pass')";
//         echo $sql_query;
//         die();
        $type = "ss";
        $values = array($email, $pass);
        $new_value = $controller->getCleanValue($values);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql_query, $type, $new_value);
        return $result;
    }

    function getUserProfileForOTPLogin($contact_no)
    {
        $controller = new Controller();
        $sql_query = "SELECT usp.name,usp.custom_url,tb_login.contact_no,tb_login.type,usp.status,tb_login.email,usp.expiry_date,tb_login.user_id FROM tb_user_profile as usp INNER JOIN tb_login on usp.id=tb_login.user_id where tb_login.contact_no=".$contact_no." limit 1";

        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }
    /* function getUserProfileForLogin($val_username,$val_pass)
     {
         $controller = new Controller();
         $sql_query = "SELECT usp.name,usp.custom_url,tb_login.contact_no,tb_login.type,usp.status,tb_login.email,usp.expiry_date,tb_login.user_id FROM tb_user_profile as usp INNER JOIN tb_login on usp.id=tb_login.user_id where (tb_login.email='$val_username' or tb_login.contact_no='$val_username') and tb_login.password='$val_pass' limit 1";
         $result = $controller->genericSelectAlreadyIterated($sql_query);
         return $result;
     }*/

    function validateRegisterEmail($email)
    {
        $result = false;
        $controller = new Controller();
        /* $sql = "select * from " . $this->loginTable . " where email='" . $email . "'";*/
        $sql = "call mu_validateRegisterEmail(?)";
        $type = "s";
        $param = array($email);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function validateChildEmail($email)
    {
        $result = false;
        $controller = new Controller();
        /* $sql = "select * from " . $this->loginTable . " where email='" . $email . "'";*/
        $sql = "call mu_validateChildEmail(?)";
        $type = "s";
        $param = array($email);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function validateCustomUrl($url)
    {
        $result = false;
        $controller = new Controller();
        //$sql = "select * from " . $this->customUrlLogTable . " as clt inner join " . $this->profileTable . " as pt where clt.custom_url='" . $url . "' or pt.custom_url='" . $url . "'";
        $sql = "call mu_validateCustomUrl(?)";
        $type = "s";
        $param = array($url);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }


    function addUser($name, $custom_url, $gender, $source, $team_id, $verify_number, $online_search, $country, $state, $city, $company_name, $verified_email_status)
    {
        $controller = new Controller();
        //  $query = "insert into " . $this->profileTable . " (theme_id,subscription_id,name,custom_url,gender,status,referer_code,update_user_count,email_count,verify_number,dealer_id,online_search,country) VALUES ('theme1','0','$name','$custom_url','$gender',1,'$source',0,0,'$verify_number','$team_id','$online_search','$country')";
        /*$query = "call mu_addUser(?,?,?,?,?,?,?,?,?)";
        $type = "sssssiiss";
        $param = array($name, $custom_url, $gender, $source, $team_id, $verify_number, $online_search, $country,$name,$state,$city);*/
        $procedure = "CALL mu_addSharedigitalUserRegistration('$name', '$custom_url', '$gender', '$source', '$team_id', '$verify_number', '$online_search', '$country','$state','$city','$name','$company_name','$verified_email_status',@p_out_param)";
        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        $results2 = $mysqli->query("SELECT @p_out_param as last_id");
        $row = $results2->fetch_object();
        return $row->last_id;
        //  $id = $controller->genericGetLastInsertedIdUsingProcedure($query,$type,$param);
    }


    function addUserLogin($user_id, $type, $email, $contact, $password, $api_key)
    {
        $controller = new Controller();
//        $query = "insert into " . $this->loginTable . " (user_id,type,email,contact_no,password,api_key) VALUES ('$user_id','" . $type . "', '$email','$contact','$password','" . $api_key . "')";
        $query = "call mu_addUserLogin(?,?,?,?,?,?)";
        $data_type = "isssss";
        $param = array($user_id, $type, $email, $contact, $password, $api_key);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $data_type, $param);
        return $status;
    }

    function addCustomUrl($user_id, $custom_url)
    {
        $controller = new Controller();
        //$query = "insert into " . $this->customUrlLogTable . " (user_id,custom_url,date) VALUES ('$user_id','" . $custom_url . "',NOW())";
        $query = "call mu_addCustomUrl(?,?)";
        $type = "is";
        $params = array($user_id, $custom_url);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }


    function resetUserPassword($old_password, $new_password)
    {
        $status = false;
        $controller = new Controller();
        /*$query = "select password from " . $this->loginTable . " where email='" . $this->getUserSessionEmail() . "'";
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query);
        $user_password = $result["password"];
        $validOldPassword = false;
        if ($user_password == $old_password) {
            $validOldPassword = true;
        }
        if ($validOldPassword) {
            $updateQuery = "update " . $this->loginTable . " set password='" . $new_password . "' where email='" . $this->getUserSessionEmail() . "'";
            $status = $controller->genericInsertUpdateDelete($updateQuery);
        }*/
        // $query = "call mu_resetUserPassword(?,?,?)";
        $type = "sss";
        $params = array($this->getUserSessionEmail(), $old_password, $new_password);
        $procedure = "CALL mu_resetUserPassword('" . $this->getUserSessionId() . "','$old_password', '$new_password',@p_out_param)";
        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        $results2 = $mysqli->query("SELECT @p_out_param as status");
        $row = $results2->fetch_object();
        if ($row->status == "true") {
            return true;
        } else {
            return false;
        }
    }

    function validUserPassword($password)
    {
        $status = false;
        $controller = new Controller();
        $type = "sss";
        $params = array($this->getUserSessionEmail(), $password);
        $procedure = "CALL mu_validUserPassword('" . $this->getUserSessionEmail() . "','$password',@p_out_param)";
        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        $results2 = $mysqli->query("SELECT @p_out_param as status");
        $row = $results2->fetch_object();
        if ($row->status == "true") {
            return true;
        } else {
            return false;
        }
    }

    function updateUserCode($id)
    {
        $controller = new Controller();
        //$query = "update " . $this->profileTable . " set user_referer_code = '" . $_SESSION['user_code'] . "' where id = " . $id . "";
        $query = "call mu_updateUserCode(?,?)";
        $type = "is";
        $param = array($id, $_SESSION['user_code']);
        $id = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $param);
        return $id;
    }


    function updateUserCredit($credit, $id)
    {
        $controller = new Controller();
        //$query = "update " . $this->profileTable . " set user_referer_code = '" . $_SESSION['user_code'] . "' where id = " . $id . "";
        $query = "call mu_updateUserCredit(?,?)";
        $type = "ii";
        $param = array($credit, $id);
        $id = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $param);
        return $id;
    }

    function validateAdminEmail($email)
    {
        $result = false;
        $controller = new Controller();
        // $sql = "select * from " . $this->loginTable . " where email='" . $email . "'";
        $sql = "call mu_validateAdminEmail(?)";
        $type = "s";
        $param = array($email);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function validateUserContact($contact_no)
    {
        $result = false;
        $controller = new Controller();
        // $sql = "select * from " . $this->loginTable . " where contact_no='" . $contact_no . "'";
        $sql = "call mu_validateUserContact(?)";
        $type = "s";
        $param = array($contact_no);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function resetPassword($password, $email)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->loginTable . " set password='" . $password . "' where email ='" . $email . "'";
        $query = "call mu_resetPassword(?,?)";
        $type = "ss";
        $params = array($password, $email);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function resetPasswordContact($password, $contact_no)
    {
        $controller = new Controller();
        //  $sql_query = "update " . $this->loginTable . " set password='" . $password . "' where contact_no ='" . $contact_no . "'";
        $query = "call mu_resetPasswordContact(?,?)";
        $type = "ss";
        $params = array($password, $contact_no);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function getSpecificUserProfile()
    {
        $controller = new Controller();
        //$sql_query = "select * from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id = pt.id where pt.id=" . $this->getUserSessionId();
        $sql = "call mu_getSpecificUserProfile(?)";
        $type = "s";
        $param = array($this->getUserSessionId());
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function getCoverImageOfUser()
    {
        $controller = new Controller();
        //$sql_query = "select * from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id = pt.id where pt.id=" . $this->getUserSessionId();
        $sql = "call mu_getCoverImageOfUser(?)";
        $type = "s";
        $param = array($this->getUserSessionId());
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $param);
        return $result;
    }

    function getSpecificUserChildProfile($id)
    {
        $controller = new Controller();
        //$sql_query = "select * from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id = pt.id where pt.id=" . $this->getUserSessionId();
        $sql = "call mu_getSpecificUserProfile(?)";
        $type = "s";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function getSpecificUserProfileById($id)
    {
        $controller = new Controller();
        // $sql_query = "select * from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id = pt.id where pt.id='" . $id . "'";
        $sql = "call mu_getSpecificUserProfile(?)";
        $type = "s";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function updateUserProfile($name, $designation, $gender, $date_of_birth, $altr_contact_no, $website, $linked_in, $youtube, $facebook, $twitter, $instagram, $map_link, $address, $keyword, $playstore, $whatsapp_no, $saved_email, $state, $city, $locality, $business_category, $optional_staus, $landline_number,$social_status)
    {
        $controller = new Controller();
        $query = "call mu_updateUserProfile(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $type = "sssssssssssssssssssssiisi";
        if ($date_of_birth == '0000-00-00') {
            $new_date_of_birth = "null";
        } else {
            $new_date_of_birth = "'" . $date_of_birth . "'";
        }
        $params = array($name, $designation, $gender, $date_of_birth, $altr_contact_no, $website, $linked_in, $youtube, $facebook, $twitter, $instagram, $map_link, $address, $keyword, $playstore, $whatsapp_no, $saved_email, $state, $city, $locality, $business_category, $optional_staus, $this->getUserSessionId(), $landline_number,$social_status);
        $procedure = "call mu_updateUserProfile('$name', '$designation', '$gender', $new_date_of_birth, '$altr_contact_no', '$website', '$linked_in', '$youtube', '$facebook', '$twitter', '$instagram', '$map_link', '$address', '$keyword', '$playstore', '$whatsapp_no', '$saved_email', '$state', '$city', '$locality','$business_category','$optional_staus','" . $this->getUserSessionId() . "','$landline_number',$social_status)";
        // echo $procedure;
        // die();
        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        return $results1;
    }

    function updateUserChildProfile($name, $designation, $gender, $date_of_birth, $altr_contact_no, $website, $linked_in, $youtube, $facebook, $twitter, $instagram, $map_link, $address, $keyword, $playstore, $whatsapp_no, $saved_email, $state, $city, $locality, $business_category, $optional_staus, $removeImgSpace, $display_data)
    {
        $controller = new Controller();
        /* if ($update_user_count != 0) {*/
        mysqli_set_charset($controller->connect(), 'utf8');
        //$query = "update " . $this->profileTable . " set theme_id=0,subscription_id=0,name=N'" . $name . "',designation=N'" . $designation . "',gender='" . $gender . "',date_of_birth='" . $date_of_birth . "',altr_contact_no='" . $altr_contact_no . "',website_url='" . $website . "',linked_in='" . $linked_in . "',youtube='" . $youtube . "',facebook='" . $facebook . "',twitter='" . $twitter . "',instagram='" . $instagram . "',map_link=N'" . $map_link . "',address=N'" . $address . "',status=1,updated_by='" . $this->getUserSessionName() . "',updated_date=NOW(),user_keyword=N'" . $keyword . "',playstore_url='" . $playstore . "',whatsapp_no='" . $whatsapp_no . "',saved_email='" . $saved_email . "',state='" . $state . "',city='" . $city . "',locality='" . $locality . "' where id=" . $this->getUserSessionId() . "";
        $query = "call mu_updateUserChildProfile(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        // $query = "call mu_updateUserProfile('$name', '$designation', '$gender', '$date_of_birth', '$altr_contact_no', '$website', '$linked_in', '$youtube', '$facebook', '$twitter', '$instagram', '$map_link', '$address', '$keyword', '$playstore', '$whatsapp_no', '$saved_email', '$state', '$city', '$locality','" . $this->getUserSessionId(). "')";
        $type = "ssssssssssssssssssssssii";
        $procedure = "call mu_updateUserChildProfile('$name', '$designation', '$gender', '$date_of_birth', '$altr_contact_no', '$website', '$linked_in', '$youtube', '$facebook', '$twitter', '$instagram', '$map_link', '$address', '$keyword', '$playstore', '$whatsapp_no', '$saved_email', '$state', '$city', '$locality','$business_category','$optional_staus','$removeImgSpace','" . $display_data . "')";

        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        return $results1;
    }

    function insertParentUserProfile($name, $custom_url, $designation, $gender, $date_of_birth, $altr_contact_no, $website, $linked_in, $youtube, $facebook, $twitter, $instagram, $map_link, $address, $removeImgSpace, $keyword, $playstore, $whatsapp_no, $saved_email, $country, $state, $city, $locality, $business_category, $optional_staus, $expiry_date)
    {
        $controller = new Controller();
        /* if ($update_user_count != 0) {*/
        mysqli_set_charset($controller->connect(), 'utf8');
        //$query = "update " . $this->profileTable . " set theme_id=0,subscription_id=0,name=N'" . $name . "',designation=N'" . $designation . "',gender='" . $gender . "',date_of_birth='" . $date_of_birth . "',altr_contact_no='" . $altr_contact_no . "',website_url='" . $website . "',linked_in='" . $linked_in . "',youtube='" . $youtube . "',facebook='" . $facebook . "',twitter='" . $twitter . "',instagram='" . $instagram . "',map_link=N'" . $map_link . "',address=N'" . $address . "',status=1,updated_by='" . $this->getUserSessionName() . "',updated_date=NOW(),user_keyword=N'" . $keyword . "',playstore_url='" . $playstore . "',whatsapp_no='" . $whatsapp_no . "',saved_email='" . $saved_email . "',state='" . $state . "',city='" . $city . "',locality='" . $locality . "' where id=" . $this->getUserSessionId() . "";
        $query = "call mu_insertUserParentProfile(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        // $query = "call mu_updateUserProfile('$name', '$designation', '$gender', '$date_of_birth', '$altr_contact_no', '$website', '$linked_in', '$youtube', '$facebook', '$twitter', '$instagram', '$map_link', '$address', '$keyword', '$playstore', '$whatsapp_no', '$saved_email', '$state', '$city', '$locality','" . $this->getUserSessionId(). "')";
        $type = "ssssssssssssssssssssssii";
        // $params = array($name,$custom_url, $designation, $gender, $date_of_birth, $altr_contact_no, $website, $linked_in, $youtube, $facebook, $twitter, $instagram, $map_link, $address, $keyword, $playstore, $whatsapp_no, $saved_email, $state, $city, $locality,$business_category,$optional_staus,$this->getUserSessionId());

        $procedure = "CALL mu_insertUserParentProfile('$name','$custom_url', '$designation', '$gender', '$date_of_birth', '$altr_contact_no', '$website', '$linked_in', '$youtube', '$facebook', '$twitter', '$instagram', '$map_link', '$address','$removeImgSpace', '$keyword', '$playstore', '$whatsapp_no', '$saved_email','$country', '$state', '$city', '$locality','$business_category','$optional_staus','$expiry_date', '" . $this->getUserSessionId() . "',@p_out_param)";

        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        $results2 = $mysqli->query("SELECT @p_out_param as last_id");
        $row = $results2->fetch_object();
        return $row->last_id;
        /*

                $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
                return $result;*/
    }

    function updateChildLoginDetails($email, $contact, $id)
    {
        $controller = new Controller();
        //  $sql_query = "update " . $this->profileTable . " set email_count = 1 where id=" . $this->getUserSessionId() . "";
        $query = "call mu_updateChildLogin(?,?,?)";
        $type = "ssi";
        $params = array($email, $contact, $id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function update_email_count()
    {
        $controller = new Controller();
        //  $sql_query = "update " . $this->profileTable . " set email_count = 1 where id=" . $this->getUserSessionId() . "";
        $query = "call mu_update_email_count(?)";
        $type = "i";
        $params = array($this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function update_email_countById($id)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->profileTable . " set email_count = 1 where id=" . $id . "";
        $query = "call mu_update_email_count(?)";
        $type = "i";
        $params = array($id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateEnquiryEmail($email)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->profileTable . " set email_count = 1 where id=" . $id . "";
        $query = "call mu_updateEnquiryEmail(?,?)";
        $type = "si";
        $params = array($email, $this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateUserExpiryDate($expiry_date)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->profileTable . " set expiry_date='" . $expiry_date . "',update_user_count=1,user_start_date=CURDATE() where id=" . $this->getUserSessionId() . "";
        $query = "call mu_updateUserExpiryDate(?,?)";
        $type = "is";
        $params = array($this->getUserSessionId(), $expiry_date);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }


    function updateUserExpiryDateForPayment($expiry_date)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->profileTable . " set expiry_date='" . $expiry_date . "',update_user_count=1 where id=" . $this->getUserSessionId() . "";
        $query = "call mu_updateUserExpiryDateForPayment(?,?)";
        $type = "is";
        $params = array($this->getUserSessionId(), $expiry_date);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateUserExpiryOfChild($id, $expiry_date)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->profileTable . " set expiry_date='" . $expiry_date . "',update_user_count=1 where id=" . $this->getUserSessionId() . "";
        $query = "call mu_updateUserExpiryDateForPayment(?,?)";
        $type = "is";
        $params = array($id, $expiry_date);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateUserExpiryDateWithRefrence($dealer_code, $expiry_date, $sell_ref)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->profileTable . " set referer_code='" . $dealer_code . "',expiry_date='" . $expiry_date . "',sell_ref='$sell_ref' where id=" . $this->getUserSessionId() . "";
        $query = "call mu_updateUserExpiryDateWithRefrence(?,?,?,?)";
        $type = "isss";
        $params = array($this->getUserSessionId(), $dealer_code, $expiry_date, $sell_ref);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateUserExpiryDateById($expiry_date, $user_id)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->profileTable . " set expiry_date='" . $expiry_date . "',update_user_count=1,user_start_date=CURDATE() where id=" . $user_id . "";
        $query = "call mu_updateUserExpiryDateById(?,?)";
        $type = "is";
        $params = array($user_id, $expiry_date);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateUserExpiryDateProfile($expiry_date, $id)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->profileTable . " set expiry_date='" . $expiry_date . "' where id=" . $id . "";
        $query = "call mu_updateUserExpiryDateProfile(?,?)";
        $type = "is";
        $params = array($id, $expiry_date);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function mu_updateUserSectionTheme($theme, $section, $id)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->profileTable . " set expiry_date='" . $expiry_date . "' where id=" . $id . "";
        $query = "call mu_updateUserSectionTheme(?,?,?)";
        $type = "iii";
        $params = array($theme, $section, $id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateUserExpiryDateAndSubscription($expiry_date, $id)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->userSubscriptionTable . " set end_date='" . $expiry_date . "' where user_id=" . $id . "";
        $query = "call mu_updateUserExpiryDateAndSubscription(?,?)";
        $type = "is";
        $params = array($id, $expiry_date);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateUserPlanStatus()
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->userSubscriptionTable . " set active_plan='0' where user_id=" . $this->getUserSessionId() . "";
        $query = "call mu_updateUserPlanStatusById(?)";
        $type = "i";
        $params = array($this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function deactivateUserAccount($reason, $further, $account_status, $status)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->userSubscriptionTable . " set active_plan='0' where user_id=" . $this->getUserSessionId() . "";
        $query = "call mu_deactivateUserAccount(?,?,?,?,?)";
        //$query = "call mu_deactivateUserAccount(86,'$reason','$further','$status')";
        $type = "isssi";
        $params = array($this->getUserSessionId(), $reason, $further, $account_status, $status);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function activateUserAccount($status)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->userSubscriptionTable . " set active_plan='0' where user_id=" . $this->getUserSessionId() . "";
        $query = "call mu_activateUserAccount(?,?)";
        $type = "ii";
        $params = array($this->getUserSessionId(), $status);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    public function update($table, $data, $conditions)
    {
        $controller = new Controller();
        if (!empty($data) && is_array($data)) {
            $colvalSet = '';
            $whereSql = '';
            $i = 0;
            /*    if(!array_key_exists('modified',$data)){
                    $data['modified'] = date("Y-m-d H:i:s");
                }*/
            foreach ($data as $key => $val) {
                $pre = ($i > 0) ? ', ' : '';
                $colvalSet .= $pre . $key . "='" . $val . "'";
                $i++;
            }
            if (!empty($conditions) && is_array($conditions)) {
                $whereSql .= ' WHERE ';
                $i = 0;
                foreach ($conditions as $key => $value) {
                    $pre = ($i > 0) ? ' AND ' : '';
                    $whereSql .= $pre . $key . " = '" . $value . "'";
                    $i++;
                }
            }
            $query = "UPDATE " . $table . " SET " . $colvalSet . $whereSql;
//            UPDATE tb_section_icon SET section_img='check-mark-button$0.png' WHERE user_id = '86' AND section_id = '11'
            /*print_r($query);
            exit;*/
            $result = $controller->genericInsertUpdateDelete($query);
            return $result;
        } else {
            return false;
        }
    }


    function updateUserKeyword($keyword)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->profileTable . " set user_keyword='$keyword' where id=" . $this->getUserSessionId() . "";
        $query = "call mu_updateUserKeyword(?,?)";
        $type = "is";
        $params = array($this->getUserSessionId(), $keyword);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateUserPlanStatusById($user_id)
    {
        $controller = new Controller();
        //    $sql_query = "update " . $this->userSubscriptionTable . " set active_plan='0' where user_id=" . $user_id . "";
        $query = "call mu_updateUserPlanStatusById(?)";
        $type = "i";
        $params = array($user_id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function mu_updateDealerAccess($status)
    {
        $controller = new Controller();
        $query = "call mu_updateDealerAccess(?,?)";
        $type = "ii";
        $params = array($this->getUserSessionId(), $status);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function mu_updateUserReciever($status)
    {
        $controller = new Controller();
        $query = "call mu_updateUserReciever(?,?)";
        $type = "is";
        $params = array($this->getUserSessionId(), $status);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateUserOnlineSearch($online_search)
    {
        $controller = new Controller();
        //    $sql_query = "update " . $this->userSubscriptionTable . " set active_plan='0' where user_id=" . $user_id . "";
        $query = "call mu_updateUserOnlineSearch(?,?)";
        $type = "ii";
        $params = array($this->getUserSessionId(), $online_search);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function subscriptionPlan()
    {
        $controller = new Controller();
        // $sql_query = "select * from " . $this->planTable . " where year!='Free Trail (5 days)' order by id asc";
        $query = "call mu_subscriptionPlan()";
        $result = $controller->genericSelectToIterateUsingProcedure($query);
        return $result;
    }

    function subscriptionPlanWithFree()
    {
        $controller = new Controller();
        // $sql_query = "select * from " . $this->planTable . " order by id asc";
        $query = "call mu_subscriptionPlanWithFree()";
        $result = $controller->genericSelectToIterateUsingProcedure($query);
        return $result;
    }

    function updateCoverPhoto($imgFile)
    {
        $controller = new Controller();
        //$query = "update " . $this->profileTable . " set cover_pic='" . $imgFile . "' where id=" . $this->getUserSessionId() . "";
        $query = "call mu_updateCoverPhoto(?,?)";
        $type = "is";
        $params = array($this->getUserSessionId(), $imgFile);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function insertCoverPhoto($imgFile)
    {
        $controller = new Controller();
        //$query = "update " . $this->profileTable . " set cover_pic='" . $imgFile . "' where id=" . $this->getUserSessionId() . "";
        $query = "call mu_insertCoverPhoto(?,?)";
        $type = "is";
        $params = array($this->getUserSessionId(), $imgFile);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateProfilePhoto($imgFile)
    {
        $controller = new Controller();
        // $query = "update " . $this->profileTable . " set img_name='" . $imgFile . "' where id=" . $this->getUserSessionId() . "";
        $query = "call mu_updateProfilePhoto(?,?)";
        $type = "is";
        $params = array($this->getUserSessionId(), $imgFile);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function getUserSessionId()
    {
        $security = new EncryptDecrypt();
        if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
            $id = $security->decrypt($_SESSION["create_user_id"]);
        } else {
            $id = $security->decrypt($_SESSION["id"]);
        }
        return $id;
    }

    function getUserSessionEmail()
    {
        if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
            $email = $_SESSION["create_user_email"];
        } else {
            $email = $_SESSION["email"];
        }
        return $email;
    }

    function getUserSessionName()
    {
        if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
            $name = $_SESSION["create_user_name"];
        } else {
            $name = $_SESSION["name"];
        }
        return $name;
    }

    function addService($user_id, $name, $description, $imgFile, $request_status, $read_more, $whatsapp_status, $call_status, $serv_type, $payment_status, $pay_option, $txt_amount, $pay_link)
    {
        $controller = new Controller();
        $insert_query = "call mu_addServiceUpdated(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $insert_type = "isssiisiiiisss";
        $insert_values = array($user_id, $name, $description, $imgFile, $request_status, $user_id, $read_more, $whatsapp_status, $call_status, $serv_type, $payment_status, $pay_option, $txt_amount, $pay_link);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($insert_query, $insert_type, $insert_values);
        return $status;
    }


    function displayServiceDetails($ser_type = 0)
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->serviceTable . " where user_id=" . $this->getUserSessionId() . " ORDER BY position_order ";
        $sql = "call mu_displayServiceDetails(?,?)";
        $type = "ii";
        $param = array($ser_type, $this->getUserSessionId());
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $param);
        return $result;
    }

    //for android
    function displayServiceDetailsForAndroid($user_id)
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->serviceTable . " where user_id=" . $this->getUserSessionId() . " ORDER BY position_order ";
        $sql = "call mu_displayServiceDetails(?)";
        $type = "i";
        $param = array($user_id);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $param);
        return $result;
    }

    function getServiceDetails($id)
    {

        $controller = new Controller();
        // $sql_query = "select * from " . $this->serviceTable . " where id=" . $id . "";
        $sql = "call mu_getServiceDetails(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;

    }

    function updateService($name, $description, $imgFile, $request_status, $read_more, $id, $whatsapp_status, $call_status, $serv_type, $payment_status, $read_more_txt, $txt_amount, $pay_link)
    {
        $controller = new Controller();
//        if ($imgFile != "") {
//            $query = "update " . $this->serviceTable . " set service_name='" . $name . "',description='" . $description . "',img_name='" . $imgFile . "',request_status='$request_status',updated_by='" . $this->getUserSessionName() . "',updated_date=NOW() where id=" . $id . "";
//        } else {
//            $query = "update " . $this->serviceTable . " set service_name='" . $name . "',description='" . $description . "',request_status='$request_status',updated_by='" . $this->getUserSessionName() . "',updated_date=NOW() where id=" . $id . "";
//        }

        $query = "call mu_updateService(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $type = "isssiisiiiisss";
        $params = array($this->getUserSessionId(), $name, $description, $imgFile, $request_status, $id, $read_more, $whatsapp_status, $call_status, $serv_type, $payment_status, $read_more_txt, $txt_amount, $pay_link);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function deleteService($id)
    {
        $controller = new Controller();

        //$query = "delete from " . $this->serviceTable . " where id=" . $id . "";
        $query = "call mu_deleteService(?)";
        $type = "i";
        $params = array($id);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function masterDelete($id, $tableName)
    {
        $controller = new Controller();
        //$updateQuery = "update " . $tableName . " set status=" . $block_status . " where id=" . $id . "";
        $sql = "call ma_masterDelete(?,?)";
        $type = "si";
        $params = array($tableName, $id);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($sql, $type, $params);
        return $status;
    }

    function deleteCoupon($id)
    {
        $controller = new Controller();

        //$query = "delete from " . $this->couponTable . " where id=" . $id . "";
        $query = "call mu_deleteCoupon(?)";
        $type = "i";
        $params = array($id);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function deleteCoverPhoto($id)
    {
        $controller = new Controller();

        //$query = "delete from " . $this->couponTable . " where id=" . $id . "";
        $query = "call mu_deleteCoverPhoto(?)";
        $type = "i";
        $params = array($id);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function deleteWalletHistory($id)
    {
        $controller = new Controller();

        //$query = "delete from " . $this->couponTable . " where id=" . $id . "";
        $query = "call mu_deleteWalletHistory(?)";
        $type = "i";
        $params = array($id);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function publishUnpublishService($id, $block_status)
    {
        $controller = new Controller();
        $updateQuery = "update " . $this->serviceTable . " set status=" . $block_status . " where id=" . $id . "";
        $status = $controller->genericInsertUpdateDelete($updateQuery);
        return $status;
    }

    function addImage($name, $imgFile)
    {
        $controller = new Controller();
        $sql_query = "call mu_addImage(?,?,?,?)";
        $type = "issi";
        $value = array($this->getUserSessionId(), $name, $imgFile, $this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($sql_query, $type, $value);
        return $result;
    }

    function InsertUser($username, $name, $password, $contact, $email)
    {
        $controller = new Controller();
        $sql_query = "call mu_insertuser(?,?,?,?,?)";
        $type = "sssss";
        $value = array($username, $name, $password, $contact, $email);
        $clean_value = $this->getCleanValue($value);
        $result = $controller->genericGetLastInsertedIdUsingProcedure($sql_query, $type, $clean_value);
        return $result;
    }


    function displayImageDetails()
    {
        $controller = new Controller();
        // $sql_query = "SELECT * from " . $this->imageTable . " where user_id=" . $this->getUserSessionId() . " order by id desc";
        $sql = "call mu_displayImageDetails(?)";
        $type = "i";
        $params = array($this->getUserSessionId());
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function getImageDetails($id)
    {

        $controller = new Controller();
        //  $sql_query = "select * from " . $this->imageTable . " where id=" . $id . "";
        $sql = "call mu_getImageDetails(?)";
        $type = "i";
        $params = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $params);
        return $result;

    }

    function updateImage($name, $imgFile, $id)
    {
        $controller = new Controller();
        /*  if ($imgFile != "") {
              $query = "update " . $this->imageTable . " set image_name='" . $name . "',img_name='" . $imgFile . "',updated_by='" . $this->getUserSessionName() . "',updated_date=NOW() where id=" . $id . "";
          } else {
              $query = "update " . $this->imageTable . " set image_name='" . $name . "',updated_by='" . $this->getUserSessionName() . "',updated_date=NOW() where id=" . $id . "";
          }*/

        $query = "call mu_updateImage(?,?,?,?)";
        $type = "issi";
        $params = array($this->getUserSessionId(), $name, $imgFile, $id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function deleteImage($id)
    {
        $controller = new Controller();

        //  $query = "delete from " . $this->imageTable . " where id=" . $id . "";
        $query = "call mu_deleteImage(?)";
        $type = "i";
        $params = array($id);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function publishUnpublishImage($id, $block_status)
    {
        $controller = new Controller();
        $updateQuery = "update " . $this->imageTable . " set status=" . $block_status . " where id=" . $id . "";
        $status = $controller->genericInsertUpdateDelete($updateQuery);
        return $status;
    }

    function addVideo($video_name, $channel_id = false)
    {
        $controller = new Controller();
        $query = "call mu_addVideo(?,?,?,?)";
        $type = "issi";
        $values = array($this->getUserSessionId(), $video_name, $channel_id, $this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $values);
        return $result;
    }

    function displayVideoDetails()
    {
        $controller = new Controller();
        // $sql_query = "SELECT * from " . $this->videoTable . " where user_id=" . $this->getUserSessionId() . "";
        $sql = "call mu_displayVideoDetails(?)";
        $type = "i";
        $params = array($this->getUserSessionId());
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function deleteVideo($id)
    {
        $controller = new Controller();

        // $query = "delete from " . $this->videoTable . " where id=" . $id . "";
        $query = "call mu_deleteVideo(?)";
        $type = "i";
        $params = array($id);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function publishUnpublishVideo($id, $block_status)
    {
        $controller = new Controller();
        $updateQuery = "update " . $this->videoTable . " set status=" . $block_status . " where id=" . $id . "";
        $status = $controller->genericInsertUpdateDelete($updateQuery);
        return $status;
    }

    /*Testimonial client*/


    function addClientsReview($user_id, $name, $description, $imgFile, $rd_rating, $time)
    {
        $controller = new Controller();
//        $query = "insert into " . $this->clientReviewTable . " (user_id,name,description,img_name,status,created_by,created_date) VALUES ('$user_id', '$name', '$description','$imgFile',1,'" . $this->getUserSessionName() . "',NOW())";
        $query = "call mu_addClientsReview(?,?,?,?,?,?,?)";
        $type = "isssiis";
        $params = array($user_id, $name, $description, $imgFile, $this->getUserSessionId(), $rd_rating, $time);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function addClientsReviewWithoutSession($user_id, $name, $description, $imgFile)
    {
        $controller = new Controller();
        // $query = "insert into " . $this->clientReviewTable . " (user_id,name,description,img_name,status,created_by,created_date) VALUES ('$user_id', '$name', '$description','$imgFile',1,'" . $name . "',NOW())";
        $query = "call mu_addClientsReviewWithoutSession(?,?,?,?,?)";
        $type = "isssi";
        $params = array($user_id, $name, $description, $imgFile, $this->getUserSessionId());
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function displayClientReviewDetails()
    {
        $controller = new Controller();
        //$sql_query = "SELECT * from " . $this->clientReviewTable . " where user_id=" . $this->getUserSessionId() . " order by id desc";
        $sql = "call mu_displayClientReviewDetails(?)";
        $type = "i";
        $params = array($this->getUserSessionId());
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function getClintReviewDetails($id)
    {

        $controller = new Controller();
        // $sql_query = "select * from " . $this->clientReviewTable . " where id=" . $id . "";
        $sql = "call mu_getClintReviewDetails(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;

    }

    function updateClientReview($name, $description, $imgFile, $id, $rd_rating)
    {
        $controller = new Controller();
        /* if ($imgFile != "") {
             $query = "update " . $this->clientReviewTable . " set name='" . $name . "',description='" . $description . "',img_name='" . $imgFile . "',updated_by='" . $this->getUserSessionName() . "',updated_date=NOW() where id=" . $id . "";
         } else {
             $query = "update " . $this->clientReviewTable . " set name='" . $name . "',description='" . $description . "',updated_by='" . $this->getUserSessionName() . "',updated_date=NOW() where id=" . $id . "";
         }*/
        $query = "call mu_updateClientReview(?,?,?,?,?,?)";
        $type = "isssii";
        $params = array($this->getUserSessionId(), $name, $description, $imgFile, $id, $rd_rating);


        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function deleteClientsReview($id)
    {
        $controller = new Controller();

        // $query = "delete from " . $this->clientReviewTable . " where id=" . $id . "";
        $query = "call mu_deleteClientsReview(?)";
        $type = "i";
        $params = array($id);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function publishUnpublishClientReview($id, $block_status)
    {
        $controller = new Controller();
        $updateQuery = "update " . $this->clientReviewTable . " set status=" . $block_status . " where id=" . $id . "";
        $status = $controller->genericInsertUpdateDelete($updateQuery);
        return $status;
    }

    /*Testimonial clients*/

    function addClient($name, $imgFile)
    {
        $controller = new Controller();
        /*$sql_query = "call mu_getMaxPosition(?,?)";
        $type = "si";
        $values = array($this->clientTable,$this->getUserSessionId());
        $status_data = $controller->genericGetLastInsertedIdUsingProcedure($sql_query,$type,$values);
        if ($status_data != null) {
            $pos_id = $status_data['pos_id'] + 1;
        } else {
            $pos_id = 1;
        }*/
        $insert_query = "call mu_addClient(?,?,?,?)";
        $insert_type = "issi";
        $insert_values = array($this->getUserSessionId(), $name, $imgFile, $this->getUserSessionId());
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($insert_query, $insert_type, $insert_values);
        return $status;
    }

    function displayClientDetails()
    {
        $controller = new Controller();
        // $sql_query = "SELECT * from " . $this->clientTable . " where user_id=" . $this->getUserSessionId() . " order by position_order";
        $sql = "call mu_displayClientDetails(?)";
        $type = "i";
        $params = array($this->getUserSessionId());
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function getClientDetails($id)
    {

        $controller = new Controller();
        //$sql_query = "select * from " . $this->clientTable . " where id=" . $id . "";
        $sql = "call mu_getClientDetails(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;

    }

    function updateClient($name, $imgFile, $id)
    {
        $controller = new Controller();
        /*if ($imgFile != "") {
            $query = "update " . $this->clientTable . " set name='" . $name . "',img_name='" . $imgFile . "',updated_by='" . $this->getUserSessionName() . "',updated_date=NOW() where id=" . $id . "";
        } else {
            $query = "update " . $this->clientTable . " set name='" . $name . "',updated_by='" . $this->getUserSessionName() . "',updated_date=NOW() where id=" . $id . "";
        }*/
        $sql = "call mu_updateClient(?,?,?,?)";
        $type = "issi";
        $params = array($this->getUserSessionId(), $name, $imgFile, $id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($sql, $type, $params);
        return $result;
    }

    function deleteClient($id)
    {
        $controller = new Controller();

        // $query = "delete from " . $this->clientTable . " where id=" . $id . "";
        $query = "call mu_deleteClient(?)";
        $type = "i";
        $params = array($id);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function publishUnpublish($id, $block_status, $tableName)
    {
        $controller = new Controller();
        //$updateQuery = "update " . $tableName . " set status=" . $block_status . " where id=" . $id . "";
        $sql = "call mu_publishUnpublish(?,?,?)";
        $type = "sii";
        $params = array($tableName, $block_status, $id);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($sql, $type, $params);
        return $status;
    }

    function updatePoweredByStatus($id, $block_status)
    {
        $controller = new Controller();
        //$updateQuery = "update " . $tableName . " set status=" . $block_status . " where id=" . $id . "";
        $sql = "call mu_updatePoweredByStatus(?,?)";
        $type = "ii";
        $params = array($block_status, $id);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($sql, $type, $params);
        return $status;
    }

    /*Our Team*/

    function addTeam($user_id, $name, $designation, $imgFile, $dg_link, $c_number, $w_number)
    {
        $controller = new Controller();
        /* $sql_query = "select MAX(position_order) as pos_id from " . $this->ourTeamTable . " where user_id=" . $this->getUserSessionId();
         $status_data = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
         if ($status_data != null) {
             $pos_id = $status_data['pos_id'] + 1;
         } else {
             $pos_id = 1;
         }
         $query = "insert into " . $this->ourTeamTable . " (user_id,position_order,name,designation,img_name,status,created_by,created_date) VALUES ('$user_id','$pos_id','$name', '$designation','$imgFile',1,'" . $this->getUserSessionName() . "',NOW())";*/
        $query = "call mu_addTeam(?,?,?,?,?,?,?,?)";
        $type = "issssssi";
        $params = array($user_id, $name, $designation, $imgFile, $dg_link, $c_number, $w_number, $this->getUserSessionId());


        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function displayTeamDetails()
    {
        $controller = new Controller();
        // $sql_query = "SELECT * from " . $this->ourTeamTable . " where user_id=" . $this->getUserSessionId() . " order by position_order";
        $sql = "call mu_displayTeamDetails(?)";
        $type = "i";
        $params = array($this->getUserSessionId());
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function getTeamDetails($id)
    {

        $controller = new Controller();
        // $sql_query = "select * from " . $this->ourTeamTable . " where id=" . $id . "";
        $sql = "call mu_getTeamDetails(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;

    }

    function updateTeam($name, $designation, $imgFile, $dg_link, $c_number, $w_number, $id)
    {
        $controller = new Controller();
        /* if ($imgFile != "") {
             $query = "update " . $this->ourTeamTable . " set name='" . $name . "',designation='" . $designation . "',img_name='" . $imgFile . "',updated_by='" . $this->getUserSessionName() . "',updated_date=NOW() where id=" . $id . "";

         } else {
             $query = "update " . $this->ourTeamTable . " set name='" . $name . "',designation='" . $designation . "',updated_by='" . $this->getUserSessionName() . "',updated_date=NOW() where id=" . $id . "";
         }*/

        $sql = "call mu_updateTeam(?,?,?,?,?,?,?,?)";
        $type = "issssssi";
        $param = array($this->getUserSessionId(), $name, $designation, $imgFile, $dg_link, $c_number, $w_number, $id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($sql, $type, $param);
        return $result;
    }

    function deleteTeam($id)
    {
        $controller = new Controller();

        //$query = "delete from " . $this->ourTeamTable . " where id=" . $id . "";
        $query = "call mu_deleteTeam(?)";
        $type = "i";
        $params = array($id);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function publishUnpublishTeam($id, $block_status)
    {
        $controller = new Controller();
        $updateQuery = "update " . $this->ourTeamTable . " set status=" . $block_status . " where id=" . $id . "";
        $status = $controller->genericInsertUpdateDelete($updateQuery);
        return $status;
    }

    /*Update Bank Details*/

    function addBankDetails($name, $bank_name, $account_number, $ifsc_code, $default_bank)
    {
        $controller = new Controller();
        //$query = "insert into " . $this->bankDetailsTable . " (user_id,name,bank_name,account_number,ifsc_code,status,default_bank,created_by,created_date) VALUES ('" . $this->getUserSessionId() . "', '$name', '$bank_name','$account_number','$ifsc_code',1,'$default_bank','" . $this->getUserSessionName() . "',NOW())";
        $query = "call mu_addBankDetails(?,?,?,?,?,?)";
        $type = "issssi";
        $params = array($this->getUserSessionId(), $name, $bank_name, $account_number, $ifsc_code, $default_bank);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function updateBankDetails($name, $bank_name, $account_number, $ifsc_code, $id)
    {
        $controller = new Controller();
        //$query = "update " . $this->bankDetailsTable . " set name='" . $name . "',bank_name='" . $bank_name . "',account_number='" . $account_number . "',ifsc_code='" . $ifsc_code . "',status=1,updated_by='" . $this->getUserSessionName() . "',updated_date=NOW() where id=" . $id . "";
        $query = "call mu_updateBankDetails(?,?,?,?,?,?)";
        $type = "issssi";
        $params = array($this->getUserSessionId(), $name, $bank_name, $account_number, $ifsc_code, $id);

        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function displayBankDetails()
    {
        $controller = new Controller();
        // $sql_query = "SELECT * from " . $this->bankDetailsTable . " where user_id=" . $this->getUserSessionId() . " order by id desc";
        $sql = "call mu_displayBankDetails(?)";
        $type = "i";
        $params = array($this->getUserSessionId());
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function deleteBank($id)
    {
        $controller = new Controller();

        // $query = "delete from " . $this->bankDetailsTable . " where id=" . $id . "";
        $query = "call mu_deleteBank(?)";
        $type = "i";
        $params = array($id);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function publishUnpublishBank($id, $block_status)
    {
        $controller = new Controller();
        $updateQuery = "update " . $this->bankDetailsTable . " set status=" . $block_status . " where id=" . $id . "";
        $status = $controller->genericInsertUpdateDelete($updateQuery);
        return $status;
    }

    function getBankDetails($id)
    {

        $controller = new Controller();
        // $sql_query = "select * from " . $this->bankDetailsTable . " where id=" . $id . "";
        $query = "call mu_getBankDetails(?)";
        $type = "i";
        $params = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query, $type, $params);
        return $result;

    }

    function addGatewayDetails($upi_id, $upi_number)
    {
        $controller = new Controller();
        //$query = "insert into " . $this->gatewayTable . " (user_id,upi_id,upi_mobile_no,status,created_by,created_date) VALUES ('" . $this->getUserSessionId() . "', '$upi_id', '$upi_number',1,'" . $this->getUserSessionName() . "',NOW())";
        $query = "call mu_addGatewayDetails(?,?,?)";
        $type = "iss";
        $params = array($this->getUserSessionId(), $upi_id, $upi_number);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function addPayPalDetails($email, $link)
    {
        $controller = new Controller();
        //$query = "insert into " . $this->gatewayTable . " (user_id,upi_id,upi_mobile_no,status,created_by,created_date) VALUES ('" . $this->getUserSessionId() . "', '$upi_id', '$upi_number',1,'" . $this->getUserSessionName() . "',NOW())";
        $query = "call mu_addPayPalDetails(?,?,?)";
        $type = "iss";
        $params = array($this->getUserSessionId(), $email, $link);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    /*Image slider*/

    /*   function addImageSlider($description, $imgFile)
      {
          $controller = new Controller();
          $query = "insert into " . $this->sliderTable . " (user_id,description,img_name,status,created_by,created_date) VALUES ('" . $this->getUserSessionId() . "','$description','$imgFile',1,'" . $this->getUserSessionName() . "',NOW())";
          $query = "call mu_addImageSlider(?,?,?)";
          $type = "iss";
          $params = array($this->getUserSessionId(),$description, $imgFile);
          $status = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
          return $status;
      }

     function displayImageSliderDetails()
      {
          $controller = new Controller();
          $sql_query = "SELECT * from " . $this->sliderTable . " where user_id=" . $this->getUserSessionId() . " order by id desc";

          $result = $controller->genericSelectToIterateUsingProcedure($sql,$type,$params);
          return $result;
      }*/

    function displayLeadResult($id, $status)
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->serviceRequestTable . " where user_id=" . $this->getUserSessionId() . " order by id desc";
        $sql = "call mu_displayLeadResult(?,?)";
        $type = "is";
        $params = array($id, $status);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function displayLeadResultWithLimit($id, $status, $start, $end)
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->serviceRequestTable . " where user_id=" . $this->getUserSessionId() . " order by id desc";
        $sql = "call mu_displayLeadResultWithLimit(?,?,?,?)";
        $type = "isii";
        $params = array($id, $status, $start, $end);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function displayLeadResultWithLimitForFilter($id, $status, $start, $end, $name, $from, $to)
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->serviceRequestTable . " where user_id=" . $this->getUserSessionId() . " order by id desc";
        $sql = "call mu_displayLeadResultWithLimitForFilter(?,?,?,?,?,?,?)";
        $type = "isiisss";
        $params = array($id, $status, $start, $end, $name, $from, $to);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function displayLeadResultWithLimitForFilterCount($id, $status, $start, $end, $name, $from, $to)
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->serviceRequestTable . " where user_id=" . $this->getUserSessionId() . " order by id desc";
        $sql = "call mu_displayLeadResultWithLimitForFilterCount(?,?,?,?,?,?,?)";
        $type = "isiisss";
        $params = array($id, $status, $start, $end, $name, $from, $to);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $params);
        if ($result['total_count'] != null) {
            return $result['total_count'];
        } else {
            return 0;
        }
    }

    //Android Start
    function mu_displayLeadResultWithLimitForAndroid($id, $start, $end, $status = null, $from = null, $to = null, $drp_service = null)
    {

        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->serviceRequestTable . " where user_id=" . $this->getUserSessionId() . " order by id desc";
        if ($from == null) {
            $from = "null";
        } else {
            $from = "'$from'";
        }
        if ($to == null) {
            $to = "null";
        } else {
            $to = "'$to'";
        }

        $procedure = "call mu_displayLeadResultWithLimitForAndroid($id,'$status',$from,$to,$start,$end,'$drp_service')";
        $mysqli = $controller->connect();
        $results = $mysqli->query($procedure);
        return $results;
    }

    function mu_displayLeadResultWithLimitForFilterCountForAndroid($id, $status = null, $from = null, $to = null, $drp_service = null)
    {

        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->serviceRequestTable . " where user_id=" . $this->getUserSessionId() . " order by id desc";
        if ($from == null) {
            $from = "null";
        } else {
            $from = "'$from'";
        }
        if ($to == null) {
            $to = "null";
        } else {
            $to = "'$to'";
        }
        $sql = "call mu_displayLeadResultWithLimitForFilterCountForAndroid($id ,'$status',$from,$to,'$drp_service')";

        $mysqli = $controller->connect();
        $result = $mysqli->query($sql);
        $results = $result->fetch_array(MYSQLI_ASSOC);
        if ($results['total_count'] != null) {
            return $results['total_count'];
        } else {
            return 0;
        }

    }

    function validateUserIdAndAPIKey($id, $api_key)
    {
        $result = false;
        $controller = new Controller();
        //  $sql = "select l.email,up.custom_url,up.name,up.img_name,l.contact_no,l.user_id,up.expiry_date,up.user_referer_code,up.status,up.designation,l.api_key FROM " . $this->loginTable . " l inner join " . $this->profileTable . " up on l.user_id=up.id where l.user_id='" . $id . "' and l.api_key='$api_key'";
        $sql = "call ms_validateUserIdAndAPIKey(?,?)";
        $type = "is";
        $param = array($id, $api_key);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }
// Android End
    /*  function getImageSliderDetails($id)
      {

          $controller = new Controller();
          $sql_query = "select * from " . $this->sliderTable . " where id=" . $id . "";

          $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
          return $result;

      }

      function updateImageSlider($description, $imgFile, $id)
      {
          $controller = new Controller();
          if ($imgFile != "") {
              $query = "update " . $this->sliderTable . " set description='" . $description . "',img_name='" . $imgFile . "',updated_by='" . $this->getUserSessionName() . "',updated_date=NOW() where id=" . $id . "";

          } else {
              $query = "update " . $this->sliderTable . " set description='" . $description . "',updated_by='" . $this->getUserSessionName() . "',updated_date=NOW() where id=" . $id . "";
          }

          $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
          return $result;
      }*/

    /* function deleteImageSlider($id)
     {
         $controller = new Controller();

         $query = "delete from " . $this->sliderTable . " where id=" . $id . "";
         $status = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
         return $status;
     }*/

    /* function publishUnpublishImageSlider($id, $block_status)
     {
         $controller = new Controller();
         $updateQuery = "update " . $this->sliderTable . " set status=" . $block_status . " where id=" . $id . "";
         $status = $controller->genericInsertUpdateDelete($updateQuery);
         return $status;
     }*/

    /*About us*/


    /*function addContactUs($description, $imgFile)
    {
        $controller = new Controller();
        $query = "insert into " . $this->aboutUsTable . " (user_id,description,img_name,status,created_by,created_date) VALUES ('" . $this->getUserSessionId() . "','$description','$imgFile',1,'" . $this->getUserSessionName() . "',NOW())";
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
        return $status;
    }

    function getContactUsDetails($id)
    {

        $controller = new Controller();
        $sql_query = "select * from " . $this->aboutUsTable . " where user_id=" . $id . "";

        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }

    function updateContactUs($description, $imgFile, $id)
    {
        $controller = new Controller();
        if ($imgFile != "") {
            $query = "update " . $this->aboutUsTable . " set description='" . $description . "',img_name='" . $imgFile . "',updated_by='" . $this->getUserSessionName() . "',updated_date=NOW() where user_id=" . $id . "";
        } else {
            $query = "update " . $this->aboutUsTable . " set description='" . $description . "',updated_by='" . $this->getUserSessionName() . "',updated_date=NOW() where user_id=" . $id . "";
        }
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
        return $result;
    }

    function countAboutUs($user_id)
    {
        $controller = new Controller();
        $query = "select * from " . $this->aboutUsTable . " where user_id=" . $user_id;
        $result = $controller->genericSelectCount($query);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }
*/
    function countPayPal($user_id)
    {
        $controller = new Controller();
        //  $query = "select * from " . $this->gatewayTable . " where user_id=" . $user_id;
        $query = "call mu_countPayPal(?)";
        $type = "i";
        $params = array($user_id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query, $type, $params);
        if ($result != null) {
            return $result;
        } else {
            return false;
        }

    }

    function countGateway($user_id)
    {
        $controller = new Controller();
        //  $query = "select * from " . $this->gatewayTable . " where user_id=" . $user_id;
        $query = "call mu_countGateway(?)";
        $type = "i";
        $params = array($user_id);
        $result = $controller->genericSelectCountUsingProcedure($query, $type, $params);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    function getGatewayPaymentDetails($id)
    {

        $controller = new Controller();
        //$sql_query = "select * from " . $this->gatewayTable . " where user_id=" . $id . "";
        $sql = "call mu_getGatewayPaymentDetails(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }


    function mu_updatePaymentPayPal($email, $link, $id)
    {
        $controller = new Controller();
        //    $query = "update " . $this->gatewayTable . " set upi_id='" . $upi_id . "',upi_mobile_no=" . $upi_number . ",updated_by='" . $this->getUserSessionName() . "',updated_date=NOW() where user_id=" . $id . "";
        $query = "call mu_updatePaymentPayPal(?,?,?)";
        $type = "iss";
        $params = array($id, $email, $link);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updatePaymentGateway($upi_id, $upi_number, $id)
    {
        $controller = new Controller();
        //    $query = "update " . $this->gatewayTable . " set upi_id='" . $upi_id . "',upi_mobile_no=" . $upi_number . ",updated_by='" . $this->getUserSessionName() . "',updated_date=NOW() where user_id=" . $id . "";
        $query = "call mu_updatePaymentGateway(?,?,?)";
        $type = "iss";
        $params = array($id, $upi_id, $upi_number);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateCustomUrl($custom_url)
    {
        $controller = new Controller();

        //  $query = "update " . $this->profileTable . " set custom_url=N'" . $custom_url . "' where id=" . $this->getUserSessionId() . "";
        $query = "call mu_updateCustomUrl(?,?)";
        $type = "is";
        $params = array($this->getUserSessionId(), $custom_url);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateCustomUrlOfChild($id, $custom_url)
    {
        $controller = new Controller();

        //  $query = "update " . $this->profileTable . " set custom_url=N'" . $custom_url . "' where id=" . $this->getUserSessionId() . "";
        $query = "call mu_updateCustomUrl(?,?)";
        $type = "is";
        $params = array($id, $custom_url);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function addCustomUrlLog($custom_url)
    {
        $controller = new Controller();
        // $query = "insert into " . $this->customUrlLogTable . " (user_id, custom_url,date) VALUES ('" . $this->getUserSessionId() . "',N'" . $custom_url . "',NOW())";
        $query = "call mu_addCustomUrlLog(?,?)";
        $type = "is";
        $params = array($this->getUserSessionId(), $custom_url);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function countService($user_id, $section_id)
    {
        $controller = new Controller();
        //$query = "select * from " . $this->sectionStatusTable . " where user_id=" . $user_id . " and section_id=" . $section_id . "";
        $query = "call mu_countService(?,?)";
        $type = "ii";
        $params = array($user_id, $section_id);
        $result = $controller->genericSelectCountUsingProcedure($query, $type, $params);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    /* function addServiceStatus($type)
     {
         $controller = new Controller();
         $query = "insert into " . $this->sectionStatusTable . " (user_id,section_id,type) VALUES ('" . $this->getUserSessionId() . "',1,'" . $type . "')";
         $status = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
         return $status;
     }*/

    function getServiceStatus($user_id, $section_id)
    {

        $controller = new Controller();
        // $sql_query = "select * from " . $this->sectionStatusTable . " where user_id=" . $user_id . " and section_id=" . $section_id . " ";
        $sql = "call mu_getServiceStatus(?,?)";
        $type = "ii";
        $params = array($user_id, $section_id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $params);
        return $result;
    }

    function getSectionDetails()
    {
        $controller = new Controller();
        //$sql_query = "select * from " . $this->sectionTable;
        $query = "call mu_getSectionDetails()";
        $result = $controller->genericSelectToIterateUsingProcedure($query);
        return $result;
    }

    function insertDefaultUserSectionEntry($user_id, $section_id, $p_dg_status)
    {
        $controller = new Controller();
        //$query = "insert into " . $this->sectionStatusTable . " (user_id,section_id,website,digital_card) VALUES (" . $user_id . "," . $section_id . ",1,1)";
        $query = "call mu_insertDefaultUserSectionEntry(?,?,?)";
        $type = "iii";
        $params = array($user_id, $section_id, $p_dg_status);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function insertReview($user_id, $invitation_id, $message)
    {
        $controller = new Controller();
        //  $query = "insert into " . $this->feedbackInvitationTable . " (user_id,invitation_id,message) VALUES ('" . $user_id . "','" . $invitation_id . "','$message')";
        $query = "call mu_insertReview(?,?,?)";
        $type = "iis";
        $params = array($user_id, $invitation_id, $message);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function updateSectionStatus($section_id, $website, $digital_card)
    {
        $controller = new Controller();
        $query = "call mu_updateSectionStatus(?,?,?,?)";
        $type = "iiii";
        $params = array($this->getUserSessionId(), $website, $digital_card, $section_id);
        /*print_r($params);
        exit;*/
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        /*print_r($status);
        exit;*/
        return $status;
    }

    function countLogoData($user_id)
    {
        $controller = new Controller();
        // $query = "select * from " . $this->logoTable . " where user_id=" . $user_id;
        $query = "call mu_countLogoData(?)";
        $type = "i";
        $params = array($user_id);
        $result = $controller->genericSelectCountUsingProcedure($query, $type, $params);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    function getLogoDetails($user_id)
    {
        $controller = new Controller();
        // $sql_query = "select * from " . $this->logoTable . " where user_id=" . $user_id . "";
        $query = "call mu_getLogoDetails(?,?,?,?)";
        $type = "i";
        $param = array($user_id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query, $type, $param);
        return $result;
    }

    /*  function addLogo($company_name, $tag_line, $imgFile)
      {
          $controller = new Controller();
          $query = "insert into " . $this->logoTable . " (user_id,company_name,tag_line,img_name) VALUES (" . $this->getUserSessionId() . ",'" . $company_name . "','" . $tag_line . "','" . $imgFile . "')";
          $status = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
          return $status;
      }

      function updateLogo($company_name, $tag_line, $imgFile, $id)
      {
          $controller = new Controller();
          if ($imgFile != "") {
              $query = "update " . $this->logoTable . " set company_name='" . $company_name . "',tag_line='" . $tag_line . "',img_name='" . $imgFile . "' where user_id=" . $id . "";
          } else {
              $query = "update " . $this->logoTable . " set company_name='" . $company_name . "',tag_line='" . $tag_line . "' where user_id=" . $id . "";
          }
          $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
          return $result;
      }*/


    /*This is for email Authentication*/
    /*   function countEmailData($user_id)
      {
          $controller = new Controller();
          $query = "select * from " . $this->mailSettingTable . " where user_id=" . $user_id;
          $result = $controller->genericSelectCount($query);
          if ($result > 0) {
              return true;
          } else {
              return false;
          }
      }

       function getEmailDetails($user_id)
       {
           $controller = new Controller();
           $sql_query = "select * from " . $this->mailSettingTable . " where user_id=" . $user_id . "";
           $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
           return $result;
       }

        function addMailSetting($email, $password)
         {
             $controller = new Controller();
             $query = "insert into " . $this->mailSettingTable . " (user_id,email,password) VALUES (" . $this->getUserSessionId() . ",'" . $email . "','" . $password . "')";

           $status = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
           return $status;
       }

       function updateMailSetting($email, $password, $id)
       {
           $controller = new Controller();
           $query = "update " . $this->mailSettingTable . " set email='" . $email . "',password='" . $password . "' where user_id=" . $id . "";
           $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
           return $result;
       }

       function updateTheme($theme_id)
       {
           $controller = new Controller();
           $query = "update " . $this->profileTable . " set theme_id='" . $theme_id . "' where id=" . $this->getUserSessionId() . "";
           $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
           return $result;
       }*/

    function selectTheme()
    {
        $controller = new Controller();
        // $query = "select * from " . $this->profileTable . " where id=" . $this->getUserSessionId() . "";
        $sql = "call mu_selectTheme(?)";
        $type = "i";
        $param = array($this->getUserSessionId());
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function serviceCount()
    {
        $controller = new Controller();
        //  $query = "select * from " . $this->serviceTable . " where user_id=" . $this->getUserSessionId() . "";
        $sql = "call mu_serviceCount(?)";
        $type = "i";
        $params = array($this->getUserSessionId());
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function imageCount()
    {
        $controller = new Controller();
        //   $query = "select * from " . $this->imageTable . " where user_id=" . $this->getUserSessionId() . "";
        $sql = "call mu_imageCount(?)";
        $type = "i";
        $params = array($this->getUserSessionId());
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function clientCount()
    {
        $controller = new Controller();
        // $query = "select * from " . $this->clientTable . " where user_id=" . $this->getUserSessionId() . "";
        $sql = "call mu_clientCount(?)";
        $type = "i";
        $params = array($this->getUserSessionId());
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function clientReviewCount()
    {
        $controller = new Controller();
        //  $query = "select * from " . $this->clientReviewTable . " where user_id=" . $this->getUserSessionId() . "";
        $sql = "call mu_clientReviewCount(?)";
        $type = "i";
        $params = array($this->getUserSessionId());
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function ourTeamCount()
    {
        $controller = new Controller();
        //  $query = "select * from " . $this->ourTeamTable . " where user_id=" . $this->getUserSessionId() . "";
        $sql = "call mu_ourTeamCount(?)";
        $type = "i";
        $params = array($this->getUserSessionId());
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function sendMail($toName, $toEmail, $subject, $message)
    {
        $sendMail = new sendMailSystem();
        $status = false;
        $sendMailStatus = $sendMail->sendMail($toName, $toEmail, MAIL_FROM_NAME, MAIL_FROM_EMAIL, $subject, $message);
        if ($sendMailStatus) {
            $status = true;
        } else {
            $status = false;
        }
        return $status;
    }

    function sendMailWithAttachment($toName, $toEmail, $subject, $message, $attachment)
    {
        $sendMail = new sendMailSystem();
        $status = false;
        $sendMailStatus = $sendMail->sendMailWithAttachment($toName, $toEmail, MAIL_FROM_NAME, MAIL_FROM_EMAIL, $subject, $message, $attachment);
        if ($sendMailStatus) {
            $status = true;
        } else {
            $status = false;
        }
        return $status;
    }

    /* function countExpiryDate()
     {
         $controller = new Controller();
         $query = "select * from " . $this->profileTable . " where user_id='" . $this->getUserSessionId() . "'";
         $result = $controller->genericSelectCount($query);
         if ($result > 0) {
             return true;
         } else {
             return false;
         }
     }*/

    function getUserExpiryDate()
    {
        $controller = new Controller();
        //$query = "select * from " . $this->profileTable . " where id = '" . $this->getUserSessionId() . "'";
        $sql = "call mu_getUserExpiryDate(?)";
        $type = "i";
        $param = array($this->getUserSessionId());
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }


    /*Admin Panel*/
    function displayAllUser()
    {
        $controller = new Controller();
        // $sql_query = "SELECT pt.id,pt.name,pt.company_name,lt.email,lt.contact_no,pt.status,pt.custom_url,pt.img_name,pt.gender,pt.designation,pt.user_start_date,ust.start_date,ust.end_date,ust.year,lt.password from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id=pt.id inner join " . $this->userSubscriptionTable . " as ust on ust.user_id = pt.id where lt.type!='Admin' and ust.active_plan=1 order by ust.id desc";
        $sql = "call mu_displayAllUser()";
        $result = $controller->genericSelectToIterateUsingProcedure($sql);
        return $result;
    }

    function getBusniessCategory()
    {
        $controller = new Controller();
        $sql = "call mu_getBusniessCategory()";
        $result = $controller->genericSelectToIterateUsingProcedure($sql);
        return $result;
    }

    function getBusniessCategoryByName($name)
    {
        $controller = new Controller();
        $sql = "call mu_getBusniessCategoryByName(?)";
        $type = "s";
        $param = array($name);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $param);
        return $result;
    }

    function mu_validateBusniessCategoryByName($name)
    {
        $controller = new Controller();
        $sql = "call mu_validateBusniessCategoryByName(?)";
        $type = "s";
        $param = array($name);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $param);
        return $result;
    }

    function insertBusniessCategory($name)
    {
        $controller = new Controller();
        $sql = "call mu_insertBusniessCategory(?)";
        $type = "s";
        $param = array($name);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($sql, $type, $param);
        return $result;
    }

    function countAllActiveUser()
    {
        $security = new EncryptDecrypt();
        $controller = new Controller();
        /* if ($_SESSION['type'] == "Editor") {
             $sql_query = "SELECT COUNT(pt.id) as user_count from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id=pt.id inner join " . $this->userSubscriptionTable . " as ust on ust.user_id = pt.id where lt.type!='Admin' and ust.referenced_by !='dealer' and pt.referer_code not like '%dealer%' and ust.active_plan=1 order by ust.id desc";
         } else {
             $sql_query = "SELECT COUNT(pt.id) as user_count from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id=pt.id inner join " . $this->userSubscriptionTable . " as ust on ust.user_id = pt.id where lt.type!='Admin' and ust.active_plan=1 order by ust.id desc";
         }*/
        $sql = "call mu_countAllActiveUser(?,?)";
        $type = "si";
        $param = array($_SESSION['type'], $security->decrypt($_SESSION["id"]));
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        if ($result['user_count']) {
            return $result['user_count'];
        } else {
            return 0;
        }
    }

    function countAllActiveUserWithoutTrial()
    {
        $controller = new Controller();
        //$sql_query = "SELECT COUNT(pt.id) as user_count from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id=pt.id inner join " . $this->userSubscriptionTable . " as ust on ust.user_id = pt.id where lt.type!='Admin'  and ust.year not in('Free Trail (15 days)','Free Trail (5 days)') and ust.active_plan=1 order by ust.id desc";
        $sql = "call mu_countAllActiveUserWithoutTrial()";
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql);
        if ($result['user_count']) {
            return $result['user_count'];
        } else {
            return 0;
        }
    }

    function displayAllActiveUser($start, $end)
    {
        $controller = new Controller();
        $security = new EncryptDecrypt();
        $sql = "call mu_displayAllActiveUser(?,?,?,?)";
        $type = "iisi";
        $param = array($start, $end, $_SESSION['type'], $security->decrypt($_SESSION["id"]));
        /* print_r($param);
         exit;*/
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $param);
        return $result;
    }

    function displayUserByDealerCode($start, $end, $dealer_code)
    {
        $controller = new Controller();
        $sql = "call mu_displayUserByDealerCode(?,?,?)";
        $type = "iis";
        $param = array($start, $end, $dealer_code);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $param);
        return $result;
    }

    function getUserCredit()
    {
        $controller = new Controller();
        $sql = "call mu_getUserCredit(?)";
        $type = "i";
        $param = array($this->getUserSessionId());
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function getUserCreditById($id)
    {
        $controller = new Controller();
        $sql = "call mu_getUserCredit(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function getUserCreditByYear($year)
    {
        $controller = new Controller();
        $sql = "call mu_getUserCreditByYear(?,?)";
        $type = "is";
        $param = array($this->getUserSessionId(), $year);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        if ($result != null) {
            return $result;
        } else {
            return null;
        }

    }

    function displayAllUserByPackage($package, $txt_search, $start, $end, $from, $to)
    {
        $controller = new Controller();
        $security = new EncryptDecrypt();
        /*    $sql_query = "SELECT pt.id,pt.name,pt.referer_code,pt.company_name,lt.email,lt.user_notification,lt.contact_no,pt.status,pt.custom_url,pt.img_name,pt.gender,pt.designation,pt.user_start_date,pt.expiry_date,ust.start_date,ust.end_date,ust.year,lt.password from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id=pt.id inner join " . $this->userSubscriptionTable . " as ust on ust.user_id = pt.id ";
          if ($package != "" && $txt_search != "") {
              $sql_query .= "where lt.type!='Admin' and ust.year='" . $package . "' and ust.active_plan=1 and (name like '" . $txt_search . "%' or designation like '" . $txt_search . "%' or user_keyword like '%" . $txt_search . "%' or address like '%" . $txt_search . "%') group by pt.id order by pt.id desc";
          } elseif ($package != "" && $txt_search == "") {
              $sql_query .= "where lt.type!='Admin' and ust.year='" . $package . "' and ust.active_plan=1 order by pt.id desc";
          } elseif ($package == "" && $txt_search != "") {
              $sql_query .= "where lt.type!='Admin' and ust.active_plan=1 and (name like '" . $txt_search . "%' or designation like '" . $txt_search . "%' or user_keyword like '%" . $txt_search . "%' or address like '%" . $txt_search . "%') order by pt.id desc";
          }*/
        if ($from == '') {
            $from = "null";
        } else {
            $from = "'$from'";
        }
        if ($to == '') {
            $to = "null";
        } else {
            $to = "'$to'";
        }
        $sql = "call mu_displayAllUserByPackage(?,?,?,?,?,?,?,?)";
        $type = "ssiisssi";
        $params = array($package, $txt_search, $start, $end, $from, $to, $_SESSION['type'], $security->decrypt($_SESSION["id"]));
        $procedure = "call mu_displayAllUserByPackage('$package', '$txt_search',$start,$end,$from,$to,'" . $_SESSION['type'] . "'," . $security->decrypt($_SESSION["id"]) . ")";
		/*echo $procedure;
		die();*/

        $mysqli = $controller->connect();
        $results = $mysqli->query($procedure);
        if ($results->num_rows > 0) {
            $return = $results;
        } else {
            $return = null;
        }
        return $return;

    }

    function displayAllUserByPackageCount($package, $txt_search, $from, $to)
    {
        $security = new EncryptDecrypt();
        $controller = new Controller();
        $sql = "call mu_displayAllUserByPackageCount(?,?,?,?,?,?)";
        $type = "sssssi";
        if ($from == '') {
            $from = "null";
        } else {
            $from = "'$from'";
        }
        if ($to == '') {
            $to = "null";
        } else {
            $to = "'$to'";
        }
        $params = array($package, $txt_search, $from, $to, $_SESSION['type'], $security->decrypt($_SESSION["id"]));
        $procedure = "call mu_displayAllUserByPackageCount('$package', '$txt_search',$from,$to,'" . $_SESSION['type'] . "'," . $security->decrypt($_SESSION["id"]) . ")";
        $mysqli = $controller->connect();
        $results = $mysqli->query($procedure);
        if ($results->num_rows > 0) {
            $row = $results->fetch_array(MYSQLI_ASSOC);
            if ($row['total_customer'] != null) {
                return $row['total_customer'];
            } else {
                return 0;
            }
        } else {
            return 0;
        }


    }

    function displayAllExpiredUserCount()
    {
        $controller = new Controller();
        $sql = "call mu_displayAllExpiredUserCount()";
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql);
        if ($result['total_customer']) {
            return $result['total_customer'];
        } else {
            return 0;
        }

    }

    function displayAllExpiredUser($start, $end)
    {
        $controller = new Controller();
        // $sql_query = "SELECT pt.id,pt.name,lt.email,lt.contact_no,pt.status,pt.custom_url,pt.img_name,pt.gender,pt.designation,pt.user_start_date,ust.start_date,ust.end_date,ust.year,lt.password from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id=pt.id inner join " . $this->userSubscriptionTable . " as ust on ust.user_id = pt.id where lt.type!='Admin' and pt.expiry_date < CURDATE() and ust.active_plan=1 order by pt.id desc";
        $sql = "call mu_displayAllExpiredUser(?,?)";
        $type = "ii";
        $params = array($start, $end);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function displayDefaultUser()
    {
        $controller = new Controller();
        //$sql_query = "SELECT pt.id,pt.name,lt.email,lt.contact_no,pt.custom_url,lt.password from tb_user_profile as pt inner join tb_login as lt on lt.user_id =pt.id where lt.type!='Admin' and lt.type!='Editor' and pt.id not in(select user_id from tb_user_subscription where active_plan=1) order by pt.id desc";
        $sql = "call mu_displayDefaultUser()";
        $result = $controller->genericSelectToIterateUsingProcedure($sql);
        return $result;
    }

    function deleteUser($id)
    {
        $controller = new Controller();
        /*$query = "DELETE *  FROM " . $this->profileTable . "  INNER JOIN " . $this->loginTable . "
WHERE " . $this->profileTable . ".id = " . $this->loginTable . ".user_id and " . $this->profileTable. ".id=" . $id . "";*/
        /*  $query = "DELETE FROM " . $this->profileTable . " where id=" . $id . ";";
          $query .= " DELETE FROM " . $this->loginTable . " where user_id=" . $id . "";*/
//        $query = "DELETE " . $this->profileTable . "," . $this->loginTable . " FROM " . $this->profileTable . "
//        INNER JOIN
//    " . $this->loginTable . " ON " . $this->profileTable . ".id = " . $this->loginTable . ".user_id
//WHERE
//    " . $this->profileTable . ".id = " . $id . ";";
        $query = "call mu_deleteUser(?)";
        $type = "i";
        $params = array($id);
        /*echo $query;
        die();*/
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }


    function displaySubscriptionDetails()
    {
        $controller = new Controller();
        // $sql_query = "SELECT * from " . $this->userSubscriptionTable . " where user_id=" . $this->getUserSessionId() . " order by id desc";
        $sql = "call mu_displaySubscriptionDetails(?)";
        $type = "i";
        $params = array($this->getUserSessionId());
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function displayLogDetailsOfUserDetails()
    {
        $controller = new Controller();
        // $sql_query = "SELECT * from " . $this->userSubscriptionTable . " where user_id=" . $this->getUserSessionId() . " order by id desc";
        $sql = "call mu_displayLogDetailsOfUserDetails(?)";
        $type = "i";
        $params = array($this->getUserSessionId());
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function displayParentUserDetails($id)
    {
        $controller = new Controller();
        // $sql_query = "SELECT * from " . $this->userSubscriptionTable . " where user_id=" . $this->getUserSessionId() . " order by id desc";
        $sql = "call mu_displayParentUserDetails(?)";
        $type = "i";
        $params = array($id);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function mu_displayParentUserDetailsCount($id)
    {
        $controller = new Controller();
        // $sql_query = "SELECT * from " . $this->userSubscriptionTable . " where user_id=" . $this->getUserSessionId() . " order by id desc";
        $sql = "call mu_displayParentUserDetailsCount(?)";
        $type = "i";
        $params = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $params);
        if ($result['total'] != null) {
            return $result['total'];
        } else {
            return 0;
        }

    }

    function displayInvoiceDetails()
    {
        $controller = new Controller();
        // $sql_query = "SELECT ust.id,ust.year,ust.total_amount,ust.start_date,ust.end_date,ust.status,ust.invoice_no,ust.active_plan,pt.name,lt.email,lt.contact_no from " . $this->userSubscriptionTable . " as ust inner join " . $this->profileTable . " as pt on pt.id=ust.user_id inner join " . $this->loginTable . " as lt on lt.user_id = pt.id where ust.status='success' and lt.type!='Admin' and ust.year!='Free Trail (5 days)' order by ust.id desc";
        $sql = "call mu_displayInvoiceDetails()";
        $result = $controller->genericSelectToIterateUsingProcedure($sql);
        return $result;
    }

    function displayInvoiceDetailsByDate($from_date, $to_date, $status)
    {
        $controller = new Controller();
        /*if ($from_date != "" && $to_date != "" && $status != "") {
            $sql_query = "SELECT ust.id,ust.year,ust.total_amount,ust.start_date,ust.end_date,ust.status,ust.invoice_no,ust.active_plan,pt.name,lt.email,lt.contact_no from " . $this->userSubscriptionTable . " as ust inner join " . $this->profileTable . " as pt on pt.id=ust.user_id inner join " . $this->loginTable . " as lt on lt.user_id = pt.id where ust.start_date BETWEEN '" . $from_date . "' AND '" . $to_date . "' and ust.status='$status' and ust.year!='Free Trail (5 days)' and lt.type!='Admin' order by ust.id desc";
        } else {
            $sql_query = "SELECT ust.id,ust.year,ust.total_amount,ust.start_date,ust.end_date,ust.status,ust.invoice_no,ust.active_plan,pt.name,lt.email,lt.contact_no from " . $this->userSubscriptionTable . " as ust inner join " . $this->profileTable . " as pt on pt.id=ust.user_id inner join " . $this->loginTable . " as lt on lt.user_id = pt.id where ust.status='$status' and ust.year!='Free Trail (5 days)' and lt.type!='Admin' order by ust.id desc";

        }*/
        if ($from_date == '') {
            $from_date = "null";
        } else {
            $from_date = "$from_date";
        }
        if ($to_date == '') {
            $to_date = "null";
        } else {
            $to_date = "$to_date";
        }
        $sql = "call mu_displayInvoiceDetailsByDate(?,?,?)";
        $type = "sss";
        $params = array($from_date, $to_date, $status);
        $procedure = "call mu_displayInvoiceDetailsByDate('$from_date', '$to_date', '$status')";
        // $result = $controller->genericSelectToIterateUsingProcedure($sql,$type,$params);
        $mysqli = $controller->connect();
        $results = $mysqli->query($procedure);
        if ($results->num_rows > 0) {
            $return = $results;
        } else {
            $return = null;
        }
        return $return;
    }

    function displayAllInvoiceByAll($from_date, $to_date, $status)
    {
        $controller = new Controller();
        /*  if ($from_date != "" && $to_date != "" && $status != "") {
              $sql_query = "SELECT ust.id,ust.year,ust.total_amount,ust.start_date,ust.end_date,ust.status,ust.invoice_no,ust.active_plan,pt.name,lt.email,lt.contact_no from " . $this->userSubscriptionTable . " as ust inner join " . $this->profileTable . " as pt on pt.id=ust.user_id inner join " . $this->loginTable . " as lt on lt.user_id = pt.id where ust.start_date BETWEEN '" . $from_date . "' AND '" . $to_date . "' and ust.year!='Free Trail (5 days)' and lt.type!='Admin' order by ust.id desc";
          } else {
              $sql_query = "SELECT ust.id,ust.year,ust.total_amount,ust.start_date,ust.end_date,ust.status,ust.invoice_no,ust.active_plan,pt.name,lt.email,lt.contact_no from " . $this->userSubscriptionTable . " as ust inner join " . $this->profileTable . " as pt on pt.id=ust.user_id inner join " . $this->loginTable . " as lt on lt.user_id = pt.id where ust.year!='Free Trail (5 days)' and lt.type!='Admin' order by ust.id desc";
          }*/
        $sql = "call mu_displayAllInvoiceByAll(?,?,?)";
        $type = "sss";
        $params = array($from_date, $to_date, $status);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function displaySubscriptionDetailsById($id)
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->userSubscriptionTable . " where user_id='" . $id . "' order by id desc";
        $sql = "call mu_displaySubscriptionDetailsById(?)";
        $type = "i";
        $params = array($id);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function getStateCategory($state)
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->userSubscriptionTable . " where user_id='" . $id . "' order by id desc";
        $sql = "call mu_getStateCategory(?)";
        $type = "i";
        $params = array($state);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function getStateCategoryById($id)
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->userSubscriptionTable . " where user_id='" . $id . "' order by id desc";
        $sql = "call mu_getStateCategoryById(?)";
        $type = "i";
        $params = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $params);
        return $result;
    }

    function getCountryCategory()
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->userSubscriptionTable . " where user_id='" . $id . "' order by id desc";
        $sql = "call mu_getCountryCategory()";
        $result = $controller->genericSelectToIterateUsingProcedure($sql);
        return $result;
    }

    function getCityDataByStateID($state_id)
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->userSubscriptionTable . " where user_id='" . $id . "' order by id desc";
        $sql = "call mu_getCityDataByStateID(?)";
        $type = "i";
        $params = array($state_id);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function displaySubscriptionDetailsByIdAlreadyIteate($id)
    {
        $controller = new Controller();
        //$sql_query = "SELECT year from " . $this->userSubscriptionTable . " where user_id='" . $id . "' and active_plan=1 order by id desc limit 1";
        $sql = "call mu_displaySubscriptionDetailsByIdAlreadyIteate(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function displayLogDetails($id)
    {
        $controller = new Controller();
        //   $sql_query = "SELECT page_type,sum(COUNT) as count from " . $this->logTable . " where user_id=" . $id . " group by page_type";
        $sql = "call mu_displayLogDetails(?)";
        $type = "i";
        $params = array($id);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function displayAllUserOFDealer($dealer_code)
    {
        $controller = new Controller();
        //   $sql_query = "SELECT page_type,sum(COUNT) as count from " . $this->logTable . " where user_id=" . $id . " group by page_type";
        $sql = "call md_displayAllUserOFDealer(?)";
        $type = "s";
        $params = array($dealer_code);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function displayLogDetailsOfUser($from_date, $to_date)
    {
        $controller = new Controller();
        /*  $sql_query = "SELECT page_type,sum(COUNT) as count from " . $this->logTable . " where user_id=" . $this->getUserSessionId() . " ";
          if ($from_date != "" && $to_date != "") {
              $sql_query .= "and date between '$from_date' and '$to_date' group by page_type";
          } else {
              $sql_query .= "group by page_type";
          }*/
        if ($from_date == "") {
            $from_date = "null";
        } else {
            $from_date = "'$from_date'";
        }
        if ($to_date == "") {
            $to_date = "null";
        } else {
            $to_date = "'$to_date'";
        }
        $proce = "call mu_displayLogDetailsOfUser(" . $this->getUserSessionId() . ",$from_date, $to_date)";
        $mysqli = $controller->connect();
        $result = $mysqli->query($proce);
        if ($result->num_rows > 0) {
            $return = $result;
        } else {
            $return = null;
        }
        return $return;
    }

    function displayLogDetailsOfUserByCity($from_date, $to_date)
    {
        $controller = new Controller();
        /*$sql_query = "SELECT page_type,sum(COUNT) as count,city from " . $this->logTable . " where user_id=" . $this->getUserSessionId() . " and city !='' ";
        if ($from_date != "" && $to_date != "") {
            $sql_query .= "and date between '$from_date' and '$to_date' group by city";
        } else {
            $sql_query .= "group by city";
        }*/
        if ($from_date == "") {
            $from_date = "null";
        } else {
            $from_date = "'$from_date'";
        }
        if ($to_date == "") {
            $to_date = "null";
        } else {
            $to_date = "'$to_date'";
        }
        $sql = "call mu_displayLogDetailsOfUserByCity(?,?,?)";
        $type = "iss";
        $proce = "call mu_displayLogDetailsOfUserByCity(" . $this->getUserSessionId() . ",$from_date, $to_date)";
        $mysqli = $controller->connect();
        $result = $mysqli->query($proce);
        if ($result->num_rows > 0) {
            $return = $result;
        } else {
            $return = null;
        }
        return $return;
    }

    function displayLogDetailsOfUserByState($from_date, $to_date)
    {
        $controller = new Controller();
        /*$sql_query = "SELECT page_type,sum(COUNT) as count,state from " . $this->logTable . " where user_id=" . $this->getUserSessionId() . " and state !='' ";
        if ($from_date != "" && $to_date != "") {
            $sql_query .= "and date between '$from_date' and '$to_date' group by state";
        } else {
            $sql_query .= "group by state";
        }*/
        /*   $sql = "call mu_displayLogDetailsOfUserByState(?,?,?)";
           $type = "iss";
           $params = array($this->getUserSessionId(),$from_date, $to_date);
           $result = $controller->genericSelectToIterateUsingProcedure($sql,$type,$params);
           return $result;*/
        if ($from_date == "") {
            $from_date = "null";
        } else {
            $from_date = "'$from_date'";
        }
        if ($to_date == "") {
            $to_date = "null";
        } else {
            $to_date = "'$to_date'";
        }

        $proce = "call mu_displayLogDetailsOfUserByState(" . $this->getUserSessionId() . ",$from_date, $to_date)";
        $mysqli = $controller->connect();
        $result = $mysqli->query($proce);
        if ($result->num_rows > 0) {
            $return = $result;
        } else {
            $return = null;
        }
        return $return;
    }

    function displayLogDetailsOfUserById($from_date, $to_date, $user_id)
    {
        $controller = new Controller();
        $sql_query = "SELECT page_type,sum(COUNT) as count from " . $this->logTable . " where user_id='" . $user_id . "' ";
        if ($from_date != "" && $to_date != "") {
            $sql_query .= "and date between '$from_date' and '$to_date' group by page_type";
        } else {
            $sql_query .= "group by page_type";
        }

        $result = $controller->genericSelectToIterateUsingProcedure($sql_query);
        return $result;
    }

    function displayLogDetailsOfUserByCityById($from_date, $to_date, $user_id)
    {
        $controller = new Controller();
        $sql_query = "SELECT page_type,sum(COUNT) as count,city from " . $this->logTable . " where user_id='" . $user_id . "' and city !='' ";
        if ($from_date != "" && $to_date != "") {
            $sql_query .= "and date between '$from_date' and '$to_date' group by city";
        } else {
            $sql_query .= "group by city";
        }
        $result = $controller->genericSelectToIterateUsingProcedure($sql_query);
        return $result;
    }

    function displayLogDetailsOfUserByStateById($from_date, $to_date, $user_id)
    {
        $controller = new Controller();
        $sql_query = "SELECT page_type,sum(COUNT) as count,state from " . $this->logTable . " where user_id='" . $user_id . "' and state !='' ";
        if ($from_date != "" && $to_date != "") {
            $sql_query .= "and date between '$from_date' and '$to_date' group by state";
        } else {
            $sql_query .= "group by state";
        }
        $result = $controller->genericSelectToIterateUsingProcedure($sql_query);
        return $result;
    }


    public function getCountBetweenDate($from_date, $to_date, $user_id)
    {
        $controller = new Controller();
        //$query = "SELECT page_type,sum(COUNT) as count from " . $this->logTable . " where date BETWEEN '" . $from_date . "' AND '" . $to_date . "' AND user_id=" . $user_id . "  GROUP BY page_type";
        $sql = "call mu_getCountBetweenDate(?,?,?)";
        $type = "ssi";
        $params = array($from_date, $to_date, $user_id);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;

    }

    /*function sendSMS($contact, $message)
    {

        $username = "DGCARD";
        $password = "dgcard@123";
        $sendSmsUrl = SMS_URL . "?username=" . urlencode(SMS_USERNAME) . "&apikey=" . urlencode(SMS_APIKEY) . "&apirequest=Text&sender=" . urlencode(SMS_SENDER) . "&mobile=" . urlencode($contact) . "&message=" . urlencode($message) . "&route=TRANS&format=JSON";
        $sendSmsUrl1 = str_replace(" ", "%20", $sendSmsUrl);
        $json = file_get_contents($sendSmsUrl1);
        $json = json_decode($json);
        if ($json->status == "success") {
            return true;
        } else {
            return false;
        }
    }*/

    function sendSMS($contact, $message)
    {

        $username = "DGCARD";
        $password = "dgcard@123";
        /*        http://sms.bulksmsserviceproviders.com/api/send_http.php?authkey=8b7d2649d2239e549d4d0bbb66ef6ff5&mobiles=9768904980,9773884631&message=hello%0D%0Ahow+are+you&sender=DGCARD&route=4*/
        $sendSmsUrl = SMS_URL . "?authkey=" . trim(AUTH_KEY) . "&mobiles=" . urlencode($contact) . "&message=" . urlencode($message) . "&sender=" . trim(SMS_SENDER) . "&route=4";
        $sendSmsUrl1 = str_replace(" ", "%20", $sendSmsUrl);
        $json = file_get_contents($sendSmsUrl1);
        if (is_string($json)) {
            return true;
        } else {
            return false;
        }
    }

    // function sendSMSWithTemplateId($contact, $message, $template_id){
    //     $sender_id = 'DGCARD';
    //     $template_id = $template_id;
    //     $phone = $contact;
    //     $msg = $message;
    //     $username = 'Kubic';
    //     $apikey = 'FEAB9-F45CF';
    //     $uri = 'https://www.alots.in/sms-panel/api/http/index.php';
    //     $data = array(
    //     'username'=> $username,
    //     'apikey'=> $apikey,
    //     'apirequest'=>'Text/Unicode',
    //     'sender'=> $sender_id,
    //     'route'=>'TRANS',
    //     'format'=>'JSON',
    //     'message'=> $msg,
    //     'mobile'=> $phone,
    //     'TemplateID' => $template_id,
    //     );

    //     $ch = curl_init($uri);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_POST, 1);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_FAILONERROR, true);
    //     curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    //     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    //     curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
    //     $resp = curl_exec($ch);
    //     $error = curl_error($ch);
    //     echo $resp;
    //     die();
    //     curl_close ($ch);
    //     return json_encode(compact('resp', 'error'));
    // }

    function sendSMSWithTemplateId($contact, $message, $template_id)
    {
        $sendSmsUrl = "https://www.alots.in/sms-panel/api/http/index.php?username=Kubic&apikey=FEAB9-F45CF&apirequest=Text&sender=DGCARD&mobile=".$contact."&TemplateID=".$template_id."&route=TRANS&format=JSON&message=".$message;
        $json = file_get_contents($sendSmsUrl);
        if (is_string($json)) {
            return true;
        } else {
            return false;
        }
    }

    function sendPushNotification($api_key, $notification_token, $title, $message)
    {
        $url = 'https://kubictechnology.com/sendNotification.php';
        $myvars = 'api_key=' . $api_key . '&notification_token=' . $notification_token . '&title=' . $title . '&message=' . $message;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
    }

    function smsCreditChecker()
    {
        $url = "http://www.alotsolutions.in/API/WebSMS/Http/v1.0a/index.php";
        $username = "DGCARD";
        $password = "dgcard@123";
        $sender_id = "DGCARD";
        $sendSmsUrl = $url . "?username=" . urlencode($username) . "&password=" . urlencode($password) . "&method=credit_check&format=json";
        $jsonContent = file_get_contents($sendSmsUrl);
        $json = json_decode($jsonContent);
        $key = '2';
        $availablecredit = $json->$key->availablecredit;
        return $availablecredit;
    }

    function displayNewSubscriptionDetails()
    {
        $controller = new Controller();
        // $sql_query = "SELECT * from " . $this->userSubscriptionTable . " as ust inner join " . $this->profileTable . " as pt on pt.id=ust.user_id INNER JOIN " . $this->loginTable . " as lt on pt.id=lt.user_id order by ust.id desc";
        $sql = "call mu_displayNewSubscriptionDetails()";
        $result = $controller->genericSelectToIterateUsingProcedure($sql);
        return $result;
    }

    function updateUserStatus($user_id)
    {
        $controller = new Controller();
        //  $sql_query = "update " . $this->userSubscriptionTable . " set status=1 where id =" . $user_id . "";
        $sql = "call mu_updateUserStatus(?)";
        $type = "i";
        $params = array($user_id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($sql, $type, $params);
        return $result;
    }

    function displayRelatedUser($date, $last_date)
    {
        $controller = new Controller();
        // $sql_query = "select * from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id = pt.id where expiry_date BETWEEN '" . $date . "' and '" . $last_date . "'";
        $sql = "call mu_displayRelatedUser(?,?)";
        $type = "ss";
        $params = array($date, $last_date);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function validateContact($contact_no)
    {
        $result = false;
        $controller = new Controller();
        $sql = "call mu_validateUserContact(?)";
        $type = "s";
        $param = array($contact_no);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function mu_validateEditorContact($contact_no)
    {
        $result = false;
        $controller = new Controller();
        $sql = "call mu_validateEditorContact(?)";
        $type = "s";
        $param = array($contact_no);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function validateChildContact($contact_no)
    {
        $result = false;
        $controller = new Controller();
        $sql = "call mu_validateChildContact(?)";
        $type = "s";
        $param = array($contact_no);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function getAccountDetailsCount()
    {
        $controller = new Controller();
        //  $query = "select * from " . $this->bankDetailsTable . " where user_id=" . $this->getUserSessionId();
        $sql = "call mu_getAccountDetailsCount(?)";
        $type = "i";
        $params = array($this->getUserSessionId());
        $result = $controller->genericSelectCountUsingProcedure($sql, $type, $params);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    function updateDefaultBank()
    {
        $controller = new Controller();
        //$sql_query = "update " . $this->bankDetailsTable . " set default_bank=0 where user_id =" . $this->getUserSessionId();
        $query = "call mu_updateDefaultBank(?)";
        $type = "i";
        $params = array($this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateDefaultBankStatus($default_id)
    {
        $controller = new Controller();
        //$sql_query = "update " . $this->bankDetailsTable . " set default_bank=1 where id =" . $default_id;
        $query = "call mu_updateDefaultBank(?)";
        $type = "i";
        $params = array($this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updatePassword($password)
    {
        $controller = new Controller();
        //   $sql_query = "update " . $this->loginTable . " set password='" . $password . "'";
        $query = "call mu_updatePassword(?)";
        $type = "i";
        $params = array($password);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function update_email_id($email)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->loginTable . " set email='" . $email . "' where user_id =" . $this->getUserSessionId() . "";
        $query = "call mu_update_email_id(?,?)";
        $type = "si";
        $params = array($email, $this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function update_contact_no($contact)
    {
        $controller = new Controller();
        //$sql_query = "update " . $this->loginTable . " set contact_no='" . $contact . "' where user_id =" . $this->getUserSessionId() . "";
        $query = "call mu_update_contact_no(?,?)";
        $type = "si";
        $params = array($contact, $this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function verifyContactNumber()
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->profileTable . " set verify_number=1 where id =" . $this->getUserSessionId() . "";
        $query = "call mu_verifyContactNumber(?)";
        $type = "i";
        $params = array($this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }
    function ContactNumberChecker($contact)
    {
        $controller = new Controller();
        $sql = "select * from " . $this->loginTable . " where contact_no='" . $contact . "'";

        $result = $controller->genericSelectAlreadyIterated($sql);
        return $result;
    }

    function countTotalNumber($from_date, $to_date, $user_id)
    {
        $controller = new Controller();
        /* if ($from_date == "" && $to_date == "") {
             $sql_query = "select sum(count) as count from " . $this->logTable . " where user_id=" . $user_id . "";
         } else {
             $sql_query = "select sum(count) as count from " . $this->logTable . " where date BETWEEN '" . $from_date . "' AND '" . $to_date . "' AND user_id=" . $user_id . "";
         }*/
        $sql = "call mu_verifyContactNumber(?,?,?)";
        $type = "ssi";
        $param = array($from_date, $to_date, $user_id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        if ($result["count"] == null) {
            $result = 0;
        } else {
            $result = $result["count"];
        }
        return $result;
    }

    function getSpecificCount($from_date, $to_date, $user_id)
    {
        $controller = new Controller();

        /* if ($from_date == "" && $to_date == "") {
             $sql_query = "SELECT page_type,sum(COUNT) as count from " . $this->logTable . " where user_id=" . $user_id . "  GROUP BY page_type";
         } else {
             $sql_query = "SELECT page_type,sum(COUNT) as count from " . $this->logTable . " where date BETWEEN '" . $from_date . "' AND '" . $to_date . "' AND user_id=" . $user_id . "  GROUP BY page_type";
         }*/
        $sql = "call mu_getSpecificCount(?,?,?)";
        $type = "ssi";
        $params = array($from_date, $to_date, $user_id);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function addBlog($title, $description, $facebook, $instagram, $keyword, $video_file, $imgFile)
    {
        $controller = new Controller();
//         $query = "insert into " . $this->blogTable . " (title,description,facebook,instagram,keyword,video_file,img_file,status,created_date) VALUES ('" . $this->removeQuote($title) . "', '" . $this->removeQuote($description) . "', '" . $this->removeQuote($facebook) . "','" . $this->removeQuote($instagram) . "','" . $this->removeQuote($keyword) . "','$video_file','$imgFile',0,NOW())";
        $query = "call mu_addBlog(?,?,?,?,?,?,?)";
        $type = "sssssss";
        $params = array($title, $description, $facebook, $instagram, $keyword, $video_file, $imgFile);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function removeQuote($value)
    {
        $controller = new Controller();
        $result = addslashes($value);
        return $result;
    }

    function displayBlogDetails()
    {
        $controller = new Controller();
        //$sql_query = "SELECT * from " . $this->blogTable;
        $sql = "call mu_displayBlogDetails()";
        $result = $controller->genericSelectToIterateUsingProcedure($sql);
        return $result;
    }

    function getBlogDetails($id)
    {
        $controller = new Controller();
        // $sql_query = "select * from " . $this->blogTable . " where id=" . $id . "";
        $sql = "call mu_getBlogDetails(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function updateBlog($title, $description, $facebook, $instagram, $keyword, $video_file, $imgFile, $id)
    {
        $controller = new Controller();
        /* if ($imgFile != "") {
             $query = "update " . $this->blogTable . " set title='" . $this->removeQuote($title) . "',description='" . $this->removeQuote($description) . "',facebook='" . $this->removeQuote($facebook) . "',instagram = '" . $this->removeQuote($instagram) . "',keyword='" . $this->removeQuote($keyword) . "',video_file='" . $video_file . "',img_file='" . $imgFile . "',updated_date=NOW() where id=" . $id . "";

         } else {
             $query = "update " . $this->blogTable . " set title='" . $this->removeQuote($title) . "',description='" . $this->removeQuote($description) . "',facebook='" . $this->removeQuote($facebook) . "',instagram = '" . $this->removeQuote($instagram) . "',keyword='" . $this->removeQuote($keyword) . "',video_file='" . $video_file . "',updated_date=NOW() where id=" . $id . "";
         }*/
        $query = "call mu_updateBlog(?,?,?,?,?,?,?,?)";
        $type = "sssssssi";
        $params = array($title, $description, $facebook, $instagram, $keyword, $video_file, $imgFile, $id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateCompany($company_name, $gst_no, $pan_no, $about, $our_mission, $company_profile, $logo_name)
    {
        $controller = new Controller();
        /*if ($company_profile != "") {
            $query = "update " . $this->profileTable . " set company_name='" . $this->removeQuote($company_name) . "',gst_no='" . $this->removeQuote($gst_no) . "',pan_no='" . $this->removeQuote($pan_no) . "',about_company= '" . $this->removeQuote($about) . "',our_mission='" . $this->removeQuote($our_mission) . "',company_profile='" . $company_profile . "' where id=" . $this->getUserSessionId() . "";
        } else {
            $query = "update " . $this->profileTable . " set company_name='" . $this->removeQuote($company_name) . "',gst_no='" . $this->removeQuote($gst_no) . "',pan_no='" . $this->removeQuote($pan_no) . "',about_company= '" . $this->removeQuote($about) . "',our_mission='" . $this->removeQuote($our_mission) . "' where id=" . $this->getUserSessionId() . "";
        }*/
        $query = "call mu_updateCompany(?,?,?,?,?,?,?,?)";
        $type = "sssssssi";
        $params = array($company_name, $gst_no, $pan_no, $about, $our_mission, $company_profile, $logo_name, $this->getUserSessionId());

        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateDeleteCompany($company_profile)
    {
        $controller = new Controller();
        //$query = "update " . $this->profileTable . " set company_profile='" . $company_profile . "' where id=" . $this->getUserSessionId() . "";
        $query = "call mu_updateDeleteCompany(?,?)";
        $type = "si";
        $params = array($company_profile, $this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function mu_updateDeleteCompanyLogo($company_logo)
    {
        $controller = new Controller();
        //$query = "update " . $this->profileTable . " set company_profile='" . $company_profile . "' where id=" . $this->getUserSessionId() . "";
        $query = "call mu_updateDeleteCompanyLogo(?,?)";
        $type = "si";
        $params = array($company_logo, $this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }


    function deleteBlog($id)
    {
        $controller = new Controller();
        // $query = "delete from " . $this->blogTable . " where id=" . $id . "";
        $query = "call mu_deleteBlog(?)";
        $type = "i";
        $params = array($id);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    /*
        function publishUnpublishBlog($id, $block_status)
        {
            $controller = new Controller();
            $updateQuery = "update " . $this->blogTable . " set status=" . $block_status . " where id=" . $id . "";
            $status = $controller->genericInsertUpdateDelete($updateQuery);
            return $status;
        }

        function setImageResolution($imagePath)
        {
            $imagick = new \Imagick(realpath($imagePath));
            $imagick->setImageResolution(50, 50);
            header("Content-Type: image/jpg");
            echo $imagick->getImageBlob();
        }*/


    function addSubscription($year, $amount)
    {
        $controller = new Controller();
        // $query = "insert into " . $this->planTable . " (type,year,amt,status) VALUES (1,'" . $year . "','" . $amount . "',0)";
        $query = "call mu_addSubscription(?,?)";
        $type = "si";
        $params = array($year, $amount);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function updateSubscription($year, $amount, $id)
    {
        $controller = new Controller();
        // $query = "update " . $this->planTable . " set type='1' ,year='" . $year . "',amt='" . $amount . "' where id=" . $id . "";
        $query = "call mu_addSubscription(?,?,?)";
        $type = "sii";
        $params = array($year, $amount, $id);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function displaySubscription()
    {
        $controller = new Controller();
        // $sql_query = "SELECT * from " . $this->planTable . " order by id desc";
        $sql = "call mu_displaySubscription()";
        $result = $controller->genericSelectToIterateUsingProcedure($sql);
        return $result;
    }

    function displayNotification($user_date)
    {
        $controller = new Controller();
        //$sql_query = "SELECT * from " . $this->notificationTable . " order by id desc";
        $sql = "call mu_displayNotification(?)";
        $type = "s";
        $params = array($user_date);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function displayNotificationWithoutDate()
    {
        $controller = new Controller();
        //$sql_query = "SELECT * from " . $this->notificationTable . " order by id desc";
        $sql = "call mu_displayNotificationWithoutDate()";
        $result = $controller->genericSelectToIterateUsingProcedure($sql);
        return $result;
    }

    function displayCouponModule()
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->couponTable . " order by id desc";
        $sql = "call mu_displayCouponModule()";
        $result = $controller->genericSelectToIterateUsingProcedure($sql);
        return $result;
    }

    function getProfilePercent($user_id)
    {
        $controller = new Controller();

        $procedure = "CALL mu_getProfilePercent('" . $user_id . "',@percent)";
        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        $results2 = $mysqli->query("SELECT @percent as percent");
        $row = $results2->fetch_object();
        return $row->percent;

    }

    function getUserProfilePercent()
    {
        $controller = new Controller();//

        $procedure = "CALL mu_getProfilePercent('" . $this->getUserSessionId() . "',@percent)";
        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        $results2 = $mysqli->query("SELECT @percent as percent");
        $row = $results2->fetch_object();
        return $row->percent;
    }

    function deleteSubscription($id)
    {
        $controller = new Controller();
        //$query = "delete from " . $this->planTable . " where id=" . $id . "";
        $query = "call mu_deleteSubscription(?)";
        $type = "i";
        $params = array($id);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function getSubscriptionDetails($id)
    {
        $controller = new Controller();
        //$sql_query = "select * from " . $this->planTable . " where id=" . $id . "";
        $sql = "call mu_getSubscriptionDetails(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function deleteNotification($id)
    {
        $controller = new Controller();
        //$query = "delete from " . $this->notificationTable . " where id=" . $id . "";
        $query = "call mu_deleteNotification(?)";
        $type = "i";
        $params = array($id);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function getNotificationDetailsByid($id)
    {
        $controller = new Controller();
        // $sql_query = "select * from " . $this->notificationTable . " where id=" . $id . "";
        $sql = "call mu_getNotificationDetailsByid(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function deleteCouponCode($id)
    {
        $controller = new Controller();
        //$query = "delete from " . $this->couponTable . " where id=" . $id . "";
        $query = "call mu_deleteCouponCode(?)";
        $type = "i";
        $params = array($id);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function getCouponCodeDetails($id)
    {
        $controller = new Controller();
        //$sql_query = "select * from " . $this->couponTable . " where id=" . $id . "";
        $sql = "call mu_getCouponCodeDetails(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function get_selected_value($year)
    {
        $controller = new Controller();
        // $sql_query = "select * from " . $this->planTable . " where year='" . $year . "'";
        $sql = "call mu_get_selected_value(?)";
        $type = "s";
        $param = array($year);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    /*   function get_selected_dealer_value($year)
       {
           $controller = new Controller();
           $sql_query = "select * from " . $this->dealerPlanTable . " where year='" . $year . "' limit 1";
           $result = $controller->genericSelectAlreadyIteratedUsingProcedure();
           return $result;
       }*/

    function validateDiscountCode($coupan_name)
    {
        $controller = new Controller();
        //  $query = "select * from " . $this->couponTable . " where coupan_name='" . $coupan_name . "' and curdate() between from_date and to_date";
        $sql = "call mu_validateDiscountCode(?)";
        $type = "s";
        $param = array($coupan_name);

        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        if ($result != null) {
            return $result;
        } else {
            return false;
        }
    }

    function validateReferalCode($user_referer_code)
    {
        $controller = new Controller();
        // $query = "select id from " . $this->profileTable . " as pt where pt.user_referer_code='" . $user_referer_code . "' and pt.id !='" . $this->getUserSessionId() . "' limit 1";
        $sql = "call mu_validateReferalCode(?,?)";
        $type = "si";
        $param = array($user_referer_code, $this->getUserSessionId());

        $result = $controller->genericSelectCountUsingProcedure($sql, $type, $param);

        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    function validateUserReferralCode($user_referer_code)
    {
        $controller = new Controller();
        //  $query = "select id from " . $this->profileTable . " as pt where pt.user_referer_code='" . $user_referer_code . "' and pt.status=1";
        $sql = "call mu_validateUserReferralCode(?)";
        $type = "s";
        $param = array($user_referer_code);
        $result = $controller->genericSelectCountUsingProcedure($sql, $type, $param);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    function validateDealerReferralCode($user_referer_code)
    {
        $controller = new Controller();
        //  $query = "select id from " . $this->dealerProfileTable . " as pt where pt.dealer_code='" . $user_referer_code . "' and pt.status=1";
        $sql = "call mu_validateDealerReferralCode(?)";
        $type = "s";
        $param = array($user_referer_code);
        $result = $controller->genericSelectCountUsingProcedure($sql, $type, $param);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    function validateReferalCodeById($user_referer_code, $id)
    {
        $controller = new Controller();
        //  $query = "select * from " . $this->profileTable . " as pt inner join " . $this->userSubscriptionTable . " as ust on ust.user_id=pt.id where pt.user_referer_code='" . $user_referer_code . "' and pt.id !='" . $id . "' and ust.year not in ('" . TRIAl_YEAR . "') limit 1";
        $sql = "call mu_validateReferalCodeById(?,?,?)";
        $type = "ssi";
        $param = array($user_referer_code, TRIAl_YEAR, $id);
        $result = $controller->genericSelectCountUsingProcedure($sql, $type, $param);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    function successOfUserPayment()
    {
        $controller = new Controller();
        $query = "select * from " . $this->userSubscriptionTable . " where user_id=" . $this->getUserSessionId() . " and start_date=CURDATE()";
        $result = $controller->genericSelectCount($query);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    function insertUserData($year, $plan_amount, $taxable_amount, $sDate, $endDate, $status, $referral_code, $refrenced_by, $active_plan, $invoice_no, $discount, $tax, $total_amount, $payment_brand, $payment_mode, $bankId, $timestamp, $payment_type, $for_bill, $for_email, $user_gstno, $for_pan, $from_bill, $from_gstno, $from_pan, $sac_code)
    {
        $controller = new Controller();
        // $query = "insert into " . $this->userSubscriptionTable . " (user_id,type,year,taxable_amount,start_date,end_date,status,referral_code,referenced_by,active_plan,invoice_no,discount,tax,total_amount,payment_brand,payment_mode,custBankId,timestamp,gstn_no) values (" . $this->getUserSessionId() . ",1,'" . $year . "','" . $taxable_amount . "','" . $sDate . "','" . $endDate . "','" . $status . "','" . $referral_code . "','" . $refrenced_by . "','" . $active_plan . "','" . $invoice_no . "','" . $discount . "','" . $tax . "','" . $total_amount . "','" . $payment_brand . "','" . $payment_mode . "','" . $bankId . "','" . $timestamp . "','" . $user_gstno . "')";
        $query = "call mu_insertUserData(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $type = "sssssssssssssssssi";
        // $params = array($year, $taxable_amount, $sDate, $endDate, $status, $referral_code, $refrenced_by, $active_plan, $invoice_no, $discount, $tax, $total_amount, $payment_brand, $payment_mode, $bankId, $timestamp, $payment_type,$for_bill,$for_email,$user_gstno,$for_pan,$from_bill,$from_gstno,$from_pan,$sac_code, $this->getUserSessionId());
        $procedure = "CALL mu_insertUserData('$year','$plan_amount', '$taxable_amount', '$sDate', '$endDate', '$status', '$referral_code', '$refrenced_by', '$active_plan', '$invoice_no', '$discount', '$tax', '$total_amount', '$payment_brand', '$payment_mode', '$bankId', '$timestamp','$payment_type','$for_bill','$for_email','$user_gstno','$for_pan','$from_bill','$from_gstno','$from_pan','$sac_code', '" . $this->getUserSessionId() . "',@p_out_param)";
        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        $results2 = $mysqli->query("SELECT @p_out_param as last_id");
        $row = $results2->fetch_object();
        return $row->last_id;

        // $result = $controller->genericGetLastInsertedIdUsingProcedure($query,$type,$params);
        // return $result;
    }


    function insertUserCreditData($year, $plan_amount, $taxable_amount, $sDate, $endDate, $status, $referral_code, $refrenced_by, $active_plan, $invoice_no, $discount, $tax, $total_amount, $payment_brand, $payment_mode, $bankId, $timestamp, $qty, $payment_type, $for_bill, $for_email, $user_gstno, $for_pan, $from_bill, $from_gstno, $from_pan, $sac_code, $order_id, $payment_id, $error_code, $error_desc, $p_address)
    {
        $controller = new Controller();
        $procedure = "CALL mu_insertUserCreditData('$year','$plan_amount', '$taxable_amount', '$sDate', '$endDate', '$status', '$referral_code', '$refrenced_by', '$active_plan', '$invoice_no', '$discount', '$tax', '$total_amount', '$payment_brand', '$payment_mode', '$bankId', '$timestamp','$qty','$payment_type','$for_bill','$for_email','$user_gstno','$for_pan','$from_bill','$from_gstno','$from_pan','$sac_code','$order_id','$payment_id','$error_code','$error_desc','$p_address', '" . $this->getUserSessionId() . "',@p_out_param)";
        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        $results2 = $mysqli->query("SELECT @p_out_param as last_id");
        $row = $results2->fetch_object();
        return $row->last_id;
    }

    function insertUserCreditDataById($id, $year, $plan_amount, $taxable_amount, $sDate, $endDate, $status, $referral_code, $refrenced_by, $active_plan, $invoice_no, $discount, $tax, $total_amount, $payment_brand, $payment_mode, $bankId, $timestamp, $qty, $payment_type, $for_bill, $for_email, $user_gstno, $for_pan, $from_bill, $from_gstno, $from_pan, $sac_code)
    {
        $controller = new Controller();
        $procedure = "CALL mu_insertUserCreditData('$year', '$plan_amount','$taxable_amount', '$sDate', '$endDate', '$status', '$referral_code', '$refrenced_by', '$active_plan', '$invoice_no', '$discount', '$tax', '$total_amount', '$payment_brand', '$payment_mode', '$bankId', '$timestamp','$qty','$payment_type','$for_bill','$for_email','$user_gstno','$for_pan','$from_bill','$from_gstno','$from_pan','$sac_code','','','','','', '" . $id . "',@p_out_param)";
        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        $results2 = $mysqli->query("SELECT @p_out_param as last_id");
        $row = $results2->fetch_object();
        return $row->last_id;

        // $result = $controller->genericGetLastInsertedIdUsingProcedure($query,$type,$params);
        // return $result;
    }

    function mu_insertUserCredit($year, $qty)
    {
        $controller = new Controller();
        $query = "CALL mu_insertUserCredit(?,?,?)";
        $type = "sii";
        $params = array($year, $qty, $this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function mu_insertUserCreditById($year, $qty, $id)
    {
        $controller = new Controller();
        $query = "CALL mu_insertUserCredit('$year', $qty,$id)";
        // $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
        $mysqli = $controller->connect();
        $result = $mysqli->query($query);
        if ($result != null) {
            return true;
        } else {
            return false;
        }

    }

    function mu_updateUserCreditByAdmin($year, $qty, $id)
    {
        $controller = new Controller();
        $query = "CALL mu_updateUserCreditByAdmin(?,?,?)";
        $type = "sii";
        $params = array($year, $qty, $id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function insertUserDataForRazor($year, $plan_amount, $taxable_amount, $sDate, $endDate, $status, $referral_code, $refrenced_by, $active_plan, $invoice_no, $discount, $tax, $total_amount, $payment_brand, $payment_mode, $bankId, $timestamp, $payment_type, $for_bill, $for_email, $user_gstno, $for_pan, $from_bill, $from_gstno, $from_pan, $sac_code, $order_id, $payment_id, $error_code, $error_desc, $p_address, $currency_type = 'INR')
    {
        $controller = new Controller();
        $procedure = "CALL mu_insertUserDataForPayzor('$year','$plan_amount', '$taxable_amount', '$sDate', '$endDate', '$status', '$referral_code', '$refrenced_by', '$active_plan', '$invoice_no', '$discount', '$tax', '$total_amount', '$payment_brand', '$payment_mode', '$bankId', '$timestamp','$payment_type','$for_bill','$for_email','$user_gstno','$for_pan','$from_bill','$from_gstno','$from_pan','$sac_code','$order_id','$payment_id','$error_code','$error_desc','$p_address','$currency_type', '" . $this->getUserSessionId() . "',@p_out_param)";
        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        $results2 = $mysqli->query("SELECT @p_out_param as last_id");
        $row = $results2->fetch_object();
        return $row->last_id;
    }

    function master_trial_data($year, $taxable_amount, $sDate, $endDate, $status, $referral_code, $refrenced_by, $active_plan, $invoice_no, $discount, $tax, $total_amount, $payment_brand, $payment_mode, $bankId, $timestamp, $user_gstno)
    {
        $controller = new Controller();
        // $query = "insert into " . $this->userSubscriptionTable . " (user_id,type,year,taxable_amount,start_date,end_date,status,referral_code,referenced_by,active_plan,invoice_no,discount,tax,total_amount,payment_brand,payment_mode,custBankId,timestamp,gstn_no) values (" . $this->getUserSessionId() . ",1,'" . $year . "','" . $taxable_amount . "','" . $sDate . "','" . $endDate . "','" . $status . "','" . $referral_code . "','" . $refrenced_by . "','" . $active_plan . "','" . $invoice_no . "','" . $discount . "','" . $tax . "','" . $total_amount . "','" . $payment_brand . "','" . $payment_mode . "','" . $bankId . "','" . $timestamp . "','" . $user_gstno . "')";
        $query = "call mu_master_trial(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $type = "sssssssssssssssssi";
        $params = array($year, $taxable_amount, $sDate, $endDate, $status, $referral_code, $refrenced_by, $active_plan, $invoice_no, $discount, $tax, $total_amount, $payment_brand, $payment_mode, $bankId, $timestamp, $user_gstno, $this->getUserSessionId());
        $procedure = "CALL mu_master_trial('$year', '$taxable_amount', '$sDate', '$endDate', '$status', '$referral_code', '$refrenced_by', '$active_plan', '$invoice_no', '$discount', '$tax', '$total_amount', '$payment_brand', '$payment_mode', '$bankId', '$timestamp', '$user_gstno', '" . $this->getUserSessionId() . "',@p_out_param)";
        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        $results2 = $mysqli->query("SELECT @p_out_param as last_id");
        $row = $results2->fetch_object();
        return $row->last_id;

        // $result = $controller->genericGetLastInsertedIdUsingProcedure($query,$type,$params);
        // return $result;
    }

    /*  function insertUserProgressData($taxable_amount, $tax, $total_amount)
      {
          $controller = new Controller();
          $query = "insert into " . $this->userSubscriptionTable . " (user_id,type,year,taxable_amount,status,active_plan,tax,total_amount,timestamp) values (" . $this->getUserSessionId() . ",1,'" . $_SESSION['new_year'] . "','" . $taxable_amount . "','Progress','0','" . $tax . "','" . $total_amount . "',NOW())";

          $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
          return $result;
      }*/

    function insertMannualSubscriptionData($user_id, $year, $plan_amount, $taxable_amount, $sDate, $endDate, $status, $referral_code, $refrenced_by, $active_plan, $invoice_no, $discount, $tax, $total_amount, $payment_brand, $payment_mode, $bankId, $timestamp, $payment_type, $for_bill, $for_email, $user_gstno, $for_pan, $from_bill, $from_gstno, $from_pan, $sac_code)
    {
        $controller = new Controller();
        // $query = "insert into " . $this->userSubscriptionTable . " (user_id,type,year,taxable_amount,start_date,end_date,status,referral_code,referenced_by,active_plan,invoice_no,discount,tax,total_amount,payment_brand,payment_mode,custBankId,timestamp) values (" . $user_id . ",1,'" . $year . "','" . $taxable_amount . "','" . $sDate . "','" . $endDate . "','" . $status . "','" . $referral_code . "','" . $refrenced_by . "','" . $active_plan . "','" . $invoice_no . "','" . $discount . "','" . $tax . "','" . $total_amount . "','" . $payment_brand . "','" . $payment_mode . "','" . $bankId . "','" . $timestamp . "')";
        $query = "call mu_insertUserData(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $type = "ssssssssssssssssi";
        // $params = array($year, $taxable_amount, $sDate, $endDate, $status, $referral_code, $refrenced_by, $active_plan, $invoice_no, $discount, $tax, $total_amount, $payment_brand, $payment_mode, $bankId, $timestamp, $this->getUserSessionId());
        $procedure = "CALL mu_insertUserData('$year','$plan_amount', '$taxable_amount', '$sDate', '$endDate', '$status', '$referral_code', '$refrenced_by', '$active_plan', '$invoice_no', '$discount', '$tax', '$total_amount', '$payment_brand', '$payment_mode', '$bankId', '$timestamp','$payment_type','$for_bill','$for_email','$user_gstno','$for_pan','$from_bill','$from_gstno','$from_pan','$sac_code', '" . $user_id . "',@p_out_param)";
        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        $results2 = $mysqli->query("SELECT @p_out_param as last_id");
        $row = $results2->fetch_object();
        return $row->last_id;
        /*
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
        return $result;*/
    }

    function displayRelatedReferralUser($user_referer_code)
    {
        $controller = new Controller();
        //$sql_query = "select * from " . $this->profileTable . " where user_referer_code='" . $user_referer_code . "'";
        $sql = "call mu_displayRelatedReferralUser(?)";
        $type = "s";
        $param = array($user_referer_code);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function update_referral_user($expiry_date, $referral_code)
    {
        $controller = new Controller();
        //$sql_query = "update " . $this->profileTable . " set expiry_date='" . $expiry_date . "' where user_referer_code ='" . $referral_code . "'";
        $query = "call mu_update_referral_user(?,?)";
        $type = "ss";
        $params = array($expiry_date, $referral_code);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function displayUserData()
    {
        $controller = new Controller();
        // $sql_query = "select * from " . $this->profileTable . " where id='" . $this->getUserSessionId() . "'";
        $sql = "call mu_displayUserData(?)";
        $type = "i";
        $param = array($this->getUserSessionId());
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql);
        return $result;
    }

    function getDealerProfile($dealer_code)
    {
        $controller = new Controller();
        //$sql_query = "select * from " . $this->dealerProfileTable . " as dpt inner join " . $this->dealerLoginTable . " as dlt on dpt.id=dlt.user_id where dpt.dealer_code='" . $dealer_code . "'";
        $sql = "call mu_getDealerProfile(?)";
        $type = "s";
        $param = array($dealer_code);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }


    function getDealerProfileById($id)
    {
        $controller = new Controller();
        //  $sql_query = "select * from " . $this->dealerProfileTable . " as dpt inner join " . $this->dealerLoginTable . " as dlt on dpt.id=dlt.user_id where dpt.user_id='" . $id . "'";
        $sql = "call mu_getDealerProfileById(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function updateDealerPayment($amount, $dealer_code)
    {
        $controller = new Controller();
        //  $sql_query = "update " . $this->dealerProfileTable . " set wallet_amount='" . $amount . "' where dealer_code='" . $dealer_code . "'";
        $query = "call mu_updateDealerPayment(?,?)";
        $type = "is";
        $params = array($amount, $dealer_code);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function getUserData()
    {
        $controller = new Controller();
        // $sql_query = "select * from " . $this->loginTable . " as lt inner join " . $this->profileTable . " as pt on pt.id=lt.user_id where pt.id=" . $this->getUserSessionId();
        $sql = "call mu_getUserData(?)";
        $type = "i";
        $param = array($this->getUserSessionId());
//        echo
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function displayUserSubscriptionDetails()
    {
        $controller = new Controller();
        //  $sql_query = "select ust.year,pt.expiry_date,pt.referer_code,pt.sell_ref,lt.user_notification from " . $this->loginTable . " as lt inner join " . $this->profileTable . " as pt on pt.id=lt.user_id inner join " . $this->userSubscriptionTable . " as ust on pt.id=ust.user_id where pt.id='" . $this->getUserSessionId() . "' and ust.active_plan=1 limit 1";
        $sql = "call mu_displayUserSubscriptionDetails(?)";
        /*echo $sql;
        exit;*/
        $type = "i";
        $param = array($this->getUserSessionId());
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function validateDealerCode($dealer_code)
    {
        $controller = new Controller();
        //  $query = "select * from " . $this->dealerProfileTable . " where dealer_code='" . $dealer_code . "' and status=1";
        $sql = "call mu_validateDealerCode(?)";
        $type = "s";
        $param = array($dealer_code);
        $result = $controller->genericSelectCountUsingProcedure($sql, $type, $param);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    function getDealerDetailsByDealerCode($dealer_code)
    {
        $controller = new Controller();
        //  $query = "select * from " . $this->dealerProfileTable . " where dealer_code='" . $dealer_code . "' and status=1";
        $sql = "call mu_validateDealerCode(?)";
        $type = "s";
        $param = array($dealer_code);
        $result = $controller->genericSelectCountUsingProcedure($sql, $type, $param);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    /* function updateUserReference($dealer_code)
     {
         $controller = new Controller();
         $sql_query = "update " . $this->profileTable . " set referer_code='" . $dealer_code . "',update_user_count=1 where id=" . $this->getUserSessionId() . "";

         $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
         return $result;
     }*/

    function updateUserReferenceById($dealer_code, $user_id)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->profileTable . " set referer_code='" . $dealer_code . "',update_user_count=1 where id=" . $user_id . "";
        $query = "call mu_updateUserReferenceById(?,?)";
        $type = "si";
        $params = array($dealer_code, $user_id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function addTheme($title, $img_name, $thumb_img)
    {
        $controller = new Controller();
        //$query = "insert into " . $this->mobileThemeTable . " (title,img_name,thumb_img,status) VALUES ('$title','$img_name','$thumb_img',1)";
        $query = "call mu_addTheme(?,?,?,?)";
        $type = "isss";
        $params = array($this->getUserSessionId(), $title, $img_name, $thumb_img);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function displayThemeDetails()
    {
        $controller = new Controller();
        //$sql_query = "SELECT * from " . $this->mobileThemeTable . " ORDER BY id DESC ";
        $sql = "call mu_displayThemeDetails()";
        $result = $controller->genericSelectToIterateUsingProcedure($sql);
        return $result;
    }

    function deleteThemeImage($id)
    {
        $controller = new Controller();
        //$query = "delete from " . $this->mobileThemeTable . " where id=" . $id . "";
        $query = "call mu_deleteThemeImage(?)";
        $type = "i";
        $params = array($id);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function validateUserId($id)
    {
        $result = false;
        $controller = new Controller();
        //  $sql = "select * from " . $this->loginTable . " where user_id='" . $id . "'";
        $sql = "call mu_validateUserId(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function displayAllUserByID($id)
    {
        $controller = new Controller();
        // $sql_query = "SELECT * from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id=pt.id where pt.id =" . $id;
        $sql = "call mu_displayAllUserByID(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }


    /* function displayExpiredUserByID($id)
     {
         $controller = new Controller();
         $sql_query = "SELECT * from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id=pt.id where pt.id =" . $id;
         $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
         return $result;
     }

     function addBookmark($user_id, $bookmark_id)
     {
         $controller = new Controller();
         $sql_query = "insert into " . $this->bookmarkTable . " (user_id,bookmark_user_id,added_date) values ('$user_id','$bookmark_id',curdate())";
         $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
         return $result;
     }

     function removeBookmark($bookmark_id)
     {
         $controller = new Controller();
         $sql_query = "delete from " . $this->bookmarkTable . " where bookmark_user_id=" . $bookmark_id;
         $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
         return $result;
     }

     function displayAllBookmark($id)
     {
         $result = false;
         $controller = new Controller();
         $sql = "select * from " . $this->profileTable . " as pt inner join " . $this->bookmarkTable . " as bt on bt.bookmark_user_id = pt.id INNER JOIN " . $this->loginTable . " as lt ON lt.user_id= pt.id where bt.user_id='" . $id . "'";

         $result = $controller->genericSelectToIterateUsingProcedure($sql,$type,$params);
         return $result;
     }*/


    public function displaySearchUser($search)
    {
        $controller = new Controller();
        // $sql_query = "select * from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id = pt.id where lt.type='User' and (name like '" . $search . "%' or designation like '" . $search . "%' or address like '%" . $search . "%' or user_keyword like '%" . $search . "%') and pt.expiry_date >=CURDATE()";
        $sql = "call mu_displaySearchUser(?)";
        $type = "i";
        $params = array($search);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    public function displayAllSearchUser($statement, $startpoint, $limit)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $statement . " limit $startpoint,$limit";

        $result = $controller->genericSelectToIterateUsingProcedure($sql_query);
        return $result;
    }

    function getSumOfRow($query)
    {
        $controller = new Controller();
        $sql_query = "SELECT COUNT(*) as `num` FROM {$query}";
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql_query);
        return $result;
    }

    /* function displayContactUser($search)
     {
         $controller = new Controller();
         $sql_query = "select lt.user_id from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id = pt.id where lt.type='User' and (name like '" . $search . "%' or designation like '" . $search . "%')";
         $result = $controller->genericSelectToIterateUsingProcedure($sql,$type,$params);
         return $result;
     }

     function checkContactUserId($user_id, $id)
     {
         $controller = new Controller();
         $sql_query = "select * from " . $this->loginTable . " where user_contact like '%" . $user_id . "%' and user_id=" . $id;
         $result = $controller->genericSelectToIterateUsingProcedure($sql,$type,$params);
         return $result;
     }*/

    function displayApproveDealer()
    {
        $controller = new Controller();
        //  $sql_query = "SELECT dpt.*,dlt.email,dlt.contact_no,dlt.password,dlt.user_id from " . $this->dealerProfileTable . " as dpt inner join " . $this->dealerLoginTable . " as dlt on dpt.id=dlt.user_id where dpt.status=1 and dpt.approve_status='Approved' and dpt.dealer_code !='' order by dpt.id desc";
        $sql = "call mu_displayApproveDealer()";
        $result = $controller->genericSelectToIterateUsingProcedure($sql);
        return $result;
    }


    function displayAmountOfDay()
    {
        $controller = new Controller();
        // $sql_query = "SELECT sum(total_amount) total_amount FROM " . $this->userSubscriptionTable . " WHERE start_date > DATE_SUB(NOW(), INTERVAL 1 DAY) and status='success'";
        $sql = "call mu_displayAmountOfDay()";
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql);
        return $result;
    }

    function displayAmountOfWeek()
    {
        $controller = new Controller();
        //$sql_query = "SELECT sum(total_amount) total_amount FROM " . $this->userSubscriptionTable . " WHERE start_date > DATE_SUB(NOW(), INTERVAL 1 WEEK) and status='success'";
        $sql = "call mu_displayAmountOfWeek()";
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql);
        return $result;
    }

    function displayAmountOfMonth()
    {
        $controller = new Controller();
        //$sql_query = "SELECT sum(total_amount) total_amount FROM " . $this->userSubscriptionTable . " WHERE start_date > DATE_SUB(NOW(), INTERVAL 1 MONTH) and status='success'";
        $sql = "call mu_displayAmountOfMonth()";
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql);
        return $result;
    }

    function displayAmountOfYear()
    {
        $controller = new Controller();
        //$sql_query = "SELECT sum(total_amount) total_amount FROM " . $this->userSubscriptionTable . " WHERE start_date > DATE_SUB(NOW(), INTERVAL 1 YEAR) and status='success'";
        $sql = "call mu_displayAmountOfYear()";
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql);
        return $result;
    }

    function displayAmountOfLifetime()
    {
        $controller = new Controller();
        // $sql_query = "SELECT sum(total_amount) total_amount FROM " . $this->userSubscriptionTable . " where status='success'";
        $sql = "call mu_displayAmountOfLifetime()";
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql);
        return $result;
    }

    function displayPendingDealer()
    {
        $controller = new Controller();
        //$sql_query = "SELECT * from " . $this->dealerProfileTable . " as dpt inner join " . $this->dealerLoginTable . " as dlt on dpt.id=dlt.user_id where dpt.status=0 and dpt.approve_status='Pending' order by dpt.id desc";
        $sql = "call mu_displayPendingDealer()";
        $result = $controller->genericSelectToIterateUsingProcedure($sql);
        return $result;
    }

    /*  function addTempData($new_year, $new_total_amount, $referral_code)
      {
          $controller = new Controller();
          $query = "insert into " . $this->temporaryTable . " (user_id,year,amount,refrence_by) VALUES ('" . $this->getUserSessionId() . "','$new_year','$new_total_amount','$referral_code')";
          $status = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
          return $status;
      }*/

    function getUserInvoiceData($id)
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id=pt.id inner join " . $this->userSubscriptionTable . " as ust on ust.user_id = pt.id where ust.id=" . $id;
        $sql = "call mu_getUserInvoiceData(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function getDealerInvoiceData($dealer_code, $invoice_no)
    {
        $controller = new Controller();
        //$sql_query = "SELECT * from " . $this->dealerProfileTable . " as dpt inner join " . $this->dealerLoginTable . " as dlt on dlt.user_id=dpt.id inner join " . $this->userSubscriptionTable . " as ust on ust.referral_code = dpt.dealer_code where ust.referral_code='" . $dealer_code . "' and ust.invoice_no='$invoice_no'";
        $sql = "call mu_getDealerInvoiceData(?,?)";
        $type = "ss";
        $param = array($dealer_code, $invoice_no);

        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function checkUserPayStatus($id)
    {
        $controller = new Controller();
        // $sql_query = "SELECT * from " . $this->userSubscriptionTable . " where id=" . $id;
        $sql = "call mu_checkUserPayStatus(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function getUserInvoiceDataByInvoiceNumber($invoice_no)
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id=pt.id inner join " . $this->userSubscriptionTable . " as ust on ust.user_id = pt.id where ust.invoice_no='" . $invoice_no . "'";
        $sql = "call mu_getUserInvoiceDataByInvoiceNumber(?)";
        $type = "s";
        $param = array($invoice_no);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function displayVerifiedUser($user_id)
    {
        $controller = new Controller();
        // $sql_query = "SELECT * from " . $this->userSubscriptionTable . " as ust where ust.user_id=" . $user_id . " and ust.active_plan=1 and ust.year!='Free Trail (5 days)'";
        $sql = "call mu_displayPendingDealer(?)";
        $type = "i";
        $param = array($user_id);
        $result = $controller->genericSelectCountUsingProcedure($sql, $type, $param);
        return $result;
    }

    function getLastInvoiceNumber($currency_type = 'INR')
    {
        $controller = new Controller();
        $sql = "call mu_getLastInvoiceNumber(?)";
        $type = "s";
        $param = array($currency_type);
        $get = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $get;
    }


    function validUserId($user_id)
    {
        $controller = new Controller();
        // $query = "select * from " . $this->loginTable . " where user_id='" . $user_id . "'";
        $sql = "call mu_validUserId(?)";
        $type = "i";
        $param = array($user_id);
        $result = $controller->genericSelectCountUsingProcedure($sql, $type, $param);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    function validAPIKEYId($user_id, $api_key)
    {
        $controller = new Controller();
        $query = "select email,user_id,api_key from " . $this->loginTable . " where user_id='" . $user_id . "' and api_key='" . $api_key . "'";
   
        // $sql = "call mu_validAPIKEYId(?,?)";
        // $type = "is";
        // $param = array($user_id, $api_key);
        $result = $controller->genericSelectCount($query);
        // $result = $controller->genericSelectCountUsingProcedure($sql, $type, $param);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    function getSectionName()
    {
        $result = false;
        $controller = new Controller();
        //   $sql = "select * from " . $this->sectionNameTable . " where user_id='" . $this->getUserSessionId() . "'";
        $sql = "call mdm_getSectionName(?)";
        $type = "i";
        $param = array($this->getUserSessionId());
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function updateSectionProfile($profile_name)
    {
        $controller = new Controller();
        //  $sql_query = "update " . $this->sectionNameTable . " set profile='" . $profile_name . "' where user_id='" . $this->getUserSessionId() . "'";
        $query = "call mu_updateSectionProfile(?,?)";
        $type = "si";
        $params = array($profile_name, $this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateSectionService($service_name, $service_header)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->sectionNameTable . " set services ='" . $service_name . "',our_service='" . $service_header . "' where user_id='" . $this->getUserSessionId() . "'";
        $query = "call mu_updateSectionService(?,?,?)";
        $type = "ssi";
        $params = array($service_name, $service_header, $this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateSectionProduct($product_name, $product_header)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->sectionNameTable . " set services ='" . $service_name . "',our_service='" . $service_header . "' where user_id='" . $this->getUserSessionId() . "'";
        $query = "call mu_updateSectionProduct(?,?,?)";
        $type = "ssi";
        $params = array($product_name, $product_header, $this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateSectionGallery($gallery, $images, $videos)
    {
        $controller = new Controller();
        //$sql_query = "update " . $this->sectionNameTable . " set gallery ='" . $gallery . "',images='" . $images . "',videos='" . $videos . "' where user_id='" . $this->getUserSessionId() . "'";
        $query = "call mu_updateSectionGallery(?,?,?,?)";
        $type = "sssi";
        $params = array($gallery, $images, $videos, $this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateSectionClients($clients, $clients_name, $client_review)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->sectionNameTable . " set clients='" . addslashes($clients) . "',client_name ='" . addslashes($clients_name) . "',client_review='" . addslashes($client_review) . "' where user_id='" . $this->getUserSessionId() . "'";
        $query = "call mu_updateSectionClients(?,?,?,?)";
        $type = "sssi";
        $params = array($clients, $clients_name, $client_review, $this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateSectionTeam($team, $our_team)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->sectionNameTable . " set team ='" . $team . "',our_team='" . $our_team . "' where user_id='" . $this->getUserSessionId() . "'";
        $query = "call mu_updateSectionTeam(?,?,?)";
        $type = "ssi";
        $params = array($team, $our_team, $this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateSectionBank($bank, $payment)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->sectionNameTable . " set bank ='" . $bank . "',payment='" . $payment . "' where user_id='" . $this->getUserSessionId() . "'";
        $query = "call mu_updateSectionBank(?,?,?)";
        $type = "ssi";
        $params = array($bank, $payment, $this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateSectionNavbar($basic, $company)
    {
        $controller = new Controller();
        //    $sql_query = "update " . $this->sectionNameTable . " set basic_info ='" . $basic . "',company_info='" . $company . "' where user_id='" . $this->getUserSessionId() . "'";
        $query = "call mu_updateSectionNavbar(?,?,?)";
        $type = "ssi";
        $params = array($basic, $company, $this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function addMenuBar($id)
    {
        $controller = new Controller();
        //$sql_query = "insert into " . $this->sectionNameTable . " (user_id,profile,services,our_service,gallery,images,videos,clients,client_name,client_review,team,our_team,bank,payment) values('$id','Profile','Services','Our Services','Gallery','Images','Videos','Clients','Clients','Clients Reviews','Team','Our Team','Bank','Payment')";
        $query = "call mu_addMenuBar(?)";
        $type = "i";
        $params = array($id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function displayUserReview($id, $invitation_id)
    {
        $result = false;
        $controller = new Controller();
        $sql = "select * from " . $this->feedbackInvitationTable . " where user_id='" . $id . "' and invitation_id	='" . $invitation_id . "' limit 1";
        $result = $controller->genericSelectAlreadyIterated($sql);
        return $result;
    }

    function blockUnblockDealer($id, $block_status, $tableName)
    {
        $controller = new Controller();
        // $updateQuery = "update " . $tableName . " set block_status=" . $block_status . " where id=" . $id . "";
        $query = "call mu_blockUnblockDealer(?,?,?)";
        $type = "iis";
        $params = array($id, $block_status, $tableName);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }


    function deleteDealer($id)
    {
        $controller = new Controller();
        /* $query = "DELETE " . $this->dealerProfileTable . "," . $this->dealerLoginTable . " FROM " . $this->dealerProfileTable . "
         INNER JOIN
     " . $this->dealerLoginTable . " ON " . $this->dealerProfileTable . ".id = " . $this->dealerLoginTable . ".user_id
 WHERE
     " . $this->dealerProfileTable . ".id = " . $id . ";";*/
        $query = "call mu_deleteDealer(?)";
        $type = "i";
        $params = array($id);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function kubicAmountByDealer($dealer_code)
    {
        $controller = new Controller();
        // $query = "select sum(total_amount) as kubicAmount from " . $this->userSubscriptionTable . " where referral_code = '" . $dealer_code . "' and status='success'";
        $sql = "call mu_kubicAmountByDealer(?)";
        $type = "s";
        $param = array($dealer_code);

        $status = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $status;
    }


    function countAllUserByDealerCode($dealer_code)
    {
        $controller = new Controller();
        //$query = "select COUNT(pt.id) as user_count from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on pt.id=lt.user_id where pt.referer_code = '" . $dealer_code . "'";
        $sql = "call mu_countAllUserByDealerCode(?)";
        $type = "s";
        $param = array($dealer_code);
        $status = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        if ($status['user_count'] > 0) {
            return $status['user_count'];
        } else {
            return 0;
        }
    }


    public function insert($table, $data)
    {
        $controller = new Controller();
        if (!empty($data) && is_array($data)) {
            $colname = '';
            $colval = '';
            $i = 0;
            /*    if(!array_key_exists('modified',$data)){
                    $data['modified'] = date("Y-m-d H:i:s");
                }*/
            foreach ($data as $key => $val) {
                $pre = ($i > 0) ? ', ' : '';
                $colname .= $pre . $key;
                $colval .= $pre . "'$val'";
                $i++;
            }

            $query = "insert into " . $table . " (" . $colname . ") values (" . $colval . ") ";
            /*echo $query;
            exit;*/

            $result = $controller->genericGetLastInsertedId($query);
            return $result;
        } else {
            return false;
        }
    }

    function countAllActiveUserOfdealer($dealer_code)
    {
        $controller = new Controller();
        // $query = "select count(pt.id) as user_count from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on pt.id=lt.user_id inner join " . $this->userSubscriptionTable . " as ust on ust.user_id=pt.id where pt.referer_code = '" . $dealer_code . "' and ust.year not in('Free Trail (15 days)','Free Trail (5 days)') and ust.active_plan=1 group by pt.id";
        $sql = "call mu_countAllActiveUserOfdealer(?)";
        $type = "s";
        $param = array($dealer_code);
        $status = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        if ($status['user_count'] > 0) {
            return $status['user_count'];
        } else {
            return 0;
        }
    }

    function displayDealerWalletAmount($dealer_code, $type)
    {
        $controller = new Controller();
        //   $query = "select sum(amount) as total_amount from " . $this->walletHistoryTable . "  where dealer_code = '" . $dealer_code . "' and payment_status in ('$type')";
        $sql = "call md_displayDealerWalletAmount(?,?)";
        $data_type = "ss";
        $param = array($dealer_code, $type);
        $status = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $data_type, $param);
        if ($status['total_amount'] != null) {
            return $status['total_amount'];
        } else {
            return 0;
        }

    }

    function displayDealerTotalWalletAmount($dealer_code)
    {
        $controller = new Controller();
        //   $query = "select sum(amount) as total_amount from " . $this->walletHistoryTable . "  where dealer_code = '" . $dealer_code . "' and payment_status in ('$type')";
        $sql = "call md_displayDealerTotalWalletAmount(?)";
        $data_type = "s";
        $param = array($dealer_code);
        $status = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $data_type, $param);
        if ($status['total_amount'] != null) {
            return $status['total_amount'];
        } else {
            return 0;
        }

    }

    function displayUserWalletAmount($user_id, $type)
    {
        $controller = new Controller();
        //   $query = "select sum(amount) as total_amount from " . $this->walletHistoryTable . "  where dealer_code = '" . $dealer_code . "' and payment_status in ('$type')";
        $sql = "call mu_displayUserWalletAmount(?,?)";
        $data_type = "ss";
        $param = array($user_id, $type);
        $status = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $data_type, $param);
        if ($status['total_amount'] != null) {
            return $status['total_amount'];
        } else {
            return 0;
        }

    }

    function displayUserTotalWalletAmount($user_id)
    {
        $controller = new Controller();
        //   $query = "select sum(amount) as total_amount from " . $this->walletHistoryTable . "  where dealer_code = '" . $dealer_code . "' and payment_status in ('$type')";
        $sql = "call mu_displayUserTotalWalletAmount(?)";
        $data_type = "s";
        $param = array($user_id);
        $status = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $data_type, $param);
        if ($status['total_amount'] != null) {
            return $status['total_amount'];
        } else {
            return 0;
        }

    }

    function getPendingFormCount()
    {
        $controller = new Controller();
        // $query = "SELECT usp.id,usp.company_name,st.service_name,it.image_name,tv.video_link,ct.name as client_name,crt.name as client_review,ort.name as our_team,btd.bank_name,gt.upi_id FROM `tb_user_profile` as usp LEFT JOIN tb_services as st on st.user_id=usp.id LEFT JOIN tb_image as it on it.user_id=usp.id LEFT JOIN tb_video as tv on tv.user_id=usp.id LEFT JOIN tb_clients as ct on ct.user_id=usp.id LEFT JOIN tb_client_review as crt on crt.user_id=usp.id LEFT JOIN tb_our_team as ort on ort.user_id=usp.id LEFT JOIN tb_bank_details as btd on btd.user_id=usp.id LEFT JOIN tb_gateway as gt on gt.user_id=usp.id WHERE usp.id='" . $this->getUserSessionId() . "' GROUP by usp.id";
        $sql = "call mu_getPendingFormCount(?)";
        $type = "i";
        $param = array($this->getUserSessionId());
        $status = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $status;
    }

    function getSpecificTeamProfileByUserId($id)
    {
        $controller = new Controller();
        // $sql_query = "select dpt.*,dlt.email,dlt.contact_no,dlt.password,dlt.user_id from " . $this->profileTable . " as dpt inner join " . $this->loginTable . " as dlt on dpt.id=dlt.user_id where dlt.user_id='" . $id . "'";
        $sql = "call mu_getSpecificTeamProfileByUserId(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function getEditorProfile($id)
    {
        $controller = new Controller();
        // $sql_query = "select dpt.*,dlt.email,dlt.contact_no,dlt.password,dlt.user_id from " . $this->profileTable . " as dpt inner join " . $this->loginTable . " as dlt on dpt.id=dlt.user_id where dlt.user_id='" . $id . "'";
        $sql = "call mu_getEditorProfile(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function getTeamProfile()
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * FROM " . $this->profileTable . " as dpt INNER JOIN " . $this->loginTable . " as dlt on dpt.id=dlt.user_id where dlt.type='Editor' order by dpt.id desc";
        $sql = "call mu_getTeamProfile()";
        $result = $controller->genericSelectToIterateUsingProcedure($sql);
        return $result;
    }

    function getActiveTeamProfile()
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * FROM " . $this->profileTable . " as dpt INNER JOIN " . $this->loginTable . " as dlt on dpt.id=dlt.user_id where dlt.type='Editor' order by dpt.id desc";
        $sql = "call mu_getActiveTeamProfile()";
        $result = $controller->genericSelectToIterateUsingProcedure($sql);
        return $result;
    }

    function insertTeamProfile($name, $id_proof, $light_bill)
    {
        $controller = new Controller();
        //$query = "insert into " . $this->profileTable . " (name,status,created_by,created_date,id_proof,light_bill) values ('" . $name . "',1,'" . $this->getUserSessionId() . "',CURDATE(),'" . $id_proof . "','" . $light_bill . "') ";
        $query = "call mu_insertTeamProfile(?,?,?,?)";
        $type = "sssi";
        $params = array($name, $id_proof, $light_bill, $this->getUserSessionId());
        $procedure = "CALL mu_insertTeamProfile('$name', '$id_proof', '$light_bill','" . $this->getUserSessionId() . "',@p_out_param)";
        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        $results2 = $mysqli->query("SELECT @p_out_param as last_id");
        $row = $results2->fetch_object();
        return $row->last_id;
        /*$result = $controller->genericGetLastInsertedIdUsingProcedure($query,$type,$params);
        return $result;*/
    }

    function updateTeamLogin($email, $contact, $id)
    {
        $controller = new Controller();
        //  $query = "update " . $this->loginTable . " set email = '" . $email . "',contact_no= '" . $contact . "' where user_id = '" . $id . "'";
        $query = "call mu_updateTeamLogin(?,?,?)";
        $type = "ssi";
        $params = array($email, $contact, $id);
        $id = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $id;
    }

    function updateNotificationCount($notify, $user_id)
    {
        $controller = new Controller();
        // $query = "update " . $this->profileTable . " set notification_count = '$notify' where id=" . $user_id;
        $query = "call mu_updateNotificationCount(?,?)";
        $type = "si";
        $params = array($notify, $user_id);
        $id = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $id;
    }

    function addTeamLogin($user_id, $type, $email, $contact, $password)
    {
        $controller = new Controller();
        // $query = "insert into " . $this->loginTable . " (user_id,type,email,contact_no,password) VALUES ('$user_id','" . $type . "', '$email','$contact','$password')";
        $query = "call mu_addTeamLogin(?,?,?,?,?)";
        $data_type = "issss";
        $params = array($user_id, $type, $email, $contact, $password);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $data_type, $params);
        return $status;
    }

    function validateContactByID($contact_no, $id)
    {
        $controller = new Controller();
        //    $query = "select * from " . $this->loginTable . " where contact_no='" . $contact_no . "' and user_id !=" . $id;
        $query = "call mu_validateContactByID(?,?)";
        $type = "si";
        $params = array($contact_no, $id);
        $result = $controller->genericSelectCountUsingProcedure($query, $type, $params);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    function mu_validateEditorContactById($contact_no, $id)
    {
        $controller = new Controller();
        //    $query = "select * from " . $this->loginTable . " where contact_no='" . $contact_no . "' and user_id !=" . $id;
        $query = "call mu_validateEditorContactById(?,?)";
        $type = "si";
        $params = array($contact_no, $id);
        $result = $controller->genericSelectCountUsingProcedure($query, $type, $params);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    function validateChildContactByID($contact_no, $id)
    {
        $controller = new Controller();
        //    $query = "select * from " . $this->loginTable . " where contact_no='" . $contact_no . "' and user_id !=" . $id;
        $query = "call mu_validateChildContactById(?,?)";
        $type = "si";
        $params = array($contact_no, $id);
        $result = $controller->genericSelectCountUsingProcedure($query, $type, $params);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    function validateRegisterEmailByID($email, $id)
    {
        $result = false;
        $controller = new Controller();
        //     $sql = "select * from " . $this->loginTable . " where email='" . $email . "' and user_id !=" . $id;
        $sql = "call mu_validateRegisterEmailByID(?,?)";
        $type = "si";
        $param = array($email, $id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function validateChildEmailByID($email, $id)
    {
        $result = false;
        $controller = new Controller();
        //     $sql = "select * from " . $this->loginTable . " where email='" . $email . "' and user_id !=" . $id;
        $sql = "call mu_validateChildEmailByID(?,?)";
        $type = "si";
        $param = array($email, $id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function updateTeamProfile($name, $id_proof, $light_bill, $id)
    {
        $controller = new Controller();
        /*if ($id_proof != "" && $light_bill == "") {
            $query = "update " . $this->profileTable . " set name='" . $name . "',id_proof='" . $id_proof . "',updated_by='" . $this->getUserSessionId() . "',updated_date=CURDATE() where id=" . $id . "";
        } elseif ($id_proof == "" && $light_bill != "") {
            $query = "update " . $this->profileTable . " set name='" . $name . "',light_bill='" . $light_bill . "',updated_by='" . $this->getUserSessionId() . "',updated_date=CURDATE() where id=" . $id . "";
        } elseif ($id_proof != "" && $light_bill != "") {
            $query = "update " . $this->profileTable . " set name='" . $name . "',id_proof='" . $id_proof . "',light_bill='" . $light_bill . "',updated_by='" . $this->getUserSessionId() . "',updated_date=CURDATE() where id=" . $id . "";
        } elseif ($id_proof == "" && $light_bill == "") {
            $query = "update " . $this->profileTable . " set name='" . $name . "',updated_by='" . $this->getUserSessionId() . "',updated_date=CURDATE() where id=" . $id . "";
        }*/
        $query = "call mu_updateTeamProfile(?,?,?,?,?)";
        $type = "sssii";
        $params = array($name, $id_proof, $light_bill, $this->getUserSessionId(), $id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateDealerProfilePhotoById($imgFile, $id)
    {

        $controller = new Controller();
        // $query = "update " . $this->dealerProfileTable . " set img_name = '" . $imgFile . "' where id=" . $id . "";
        $query = "call mu_updateDealerProfilePhotoById(?,?)";
        $type = "si";
        $params = array($imgFile, $id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function getSpecificDealerProfileByUserId($id)
    {
        $controller = new Controller();
        //   $sql_query = "select dpt.*,dlt.email,dlt.contact_no,dlt.password,dlt.user_id from " . $this->dealerProfileTable . " as dpt inner join " . $this->dealerLoginTable . " as dlt on dpt.id=dlt.user_id where dlt.user_id='" . $id . "'";
        $query = "call mu_getSpecificDealerProfileByUserId(?)";
        $type = "i";
        $params = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query, $type, $params);
        return $result;
    }

    /*dealer management */
    function displayPendingDealerForUser()
    {
        $controller = new Controller();
        //$sql_query = "SELECT dpt.*,dlt.email,dlt.contact_no,dlt.user_id from " . $this->dealerProfileTable . " as dpt inner join " . $this->dealerLoginTable . " as dlt on dpt.id=dlt.user_id where dpt.status=0 and dpt.approve_status='Pending' order by dpt.id desc";
        $sql = "call mu_displayPendingDealerForUser()";

        $result = $controller->genericSelectToIterateUsingProcedure($sql);
        return $result;
    }

    function updateUserApprovedStatus($approved_status, $user_id, $pay_status)
    {
        $controller = new Controller();
//        $sql_query = "update " . $this->dealerProfileTable . " set status=1,approve_status='" . $approved_status . "',pay_status='$pay_status' where id ='" . $user_id . "'";
        $query = "call mu_updateUserApprovedStatus(?,?,?)";
        $type = "ssi";
        $params = array($approved_status, $pay_status, $user_id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function updateUserRejectedStatus($approved_status, $rejected_message, $user_id)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->dealerProfileTable . " set approve_status='" . $approved_status . "',rejected_message='" . $rejected_message . "' where id =" . $user_id . "";
        $query = "call mu_updateUserRejectedStatus(?,?,?)";
        $type = "ssi";
        $params = array($approved_status, $rejected_message, $user_id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function getSpecificDealerProfileByUserIdForUser($id)
    {
        $controller = new Controller();
        //$sql_query = "select dpt.*,dlt.email,dlt.contact_no,dlt.password,dlt.user_id from " . $this->dealerProfileTable . " as dpt inner join " . $this->dealerLoginTable . " as dlt on dpt.id=dlt.user_id where dlt.user_id='" . $id . "'";
        $sql = "call mu_getSpecificDealerProfileByUserIdForUser(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function updateDealerProfileById($name, $gender, $date_of_birth, $altr_contact_no, $state, $city, $address, $id_proof, $light_bill, $c_name, $c_registered, $gstin_no, $pan_no, $landline_no, $office_address, $website, $b_email_id, $category, $drp_user_type, $checkname, $id)
    {
        $controller = new Controller();
        /* if ($id_proof != "" && $light_bill == "") {
             $query = "update " . $this->dealerProfileTable . " set name='" . $name . "',gender='" . $gender . "',date_of_birth='" . $date_of_birth . "',altr_contact_no='" . $altr_contact_no . "',state='" . $state . "',city='" . $city . "',address='" . $address . "',id_proof='" . $id_proof . "',c_name='" . $c_name . "',c_registered='" . $c_registered . "',gstin_no='" . $gstin_no . "',pan_no='" . $pan_no . "',landline_no='" . $landline_no . "',office_address='" . $office_address . "',website='" . $website . "',b_email_id='" . $b_email_id . "',category='" . $category . "',updated_by='" . $_SESSION['id'] . "',updated_date=CURDATE(),user_type='$drp_user_type' where id=" . $id . "";
         } elseif ($id_proof == "" && $light_bill != "") {
             $query = "update " . $this->dealerProfileTable . " set name='" . $name . "',gender='" . $gender . "',date_of_birth='" . $date_of_birth . "',altr_contact_no='" . $altr_contact_no . "',state='" . $state . "',city='" . $city . "',address='" . $address . "',light_bill='" . $light_bill . "',c_name='" . $c_name . "',c_registered='" . $c_registered . "',gstin_no='" . $gstin_no . "',pan_no='" . $pan_no . "',landline_no='" . $landline_no . "',office_address='" . $office_address . "',website='" . $website . "',b_email_id='" . $b_email_id . "',category='" . $category . "',updated_by='" . $_SESSION['id'] . "',updated_date=CURDATE(),user_type='$drp_user_type' where id=" . $id . "";
         } elseif ($id_proof != "" && $light_bill != "") {
             $query = "update " . $this->dealerProfileTable . " set name='" . $name . "',gender='" . $gender . "',date_of_birth='" . $date_of_birth . "',altr_contact_no='" . $altr_contact_no . "',state='" . $state . "',city='" . $city . "',address='" . $address . "',id_proof='" . $id_proof . "',light_bill='" . $light_bill . "',c_name='" . $c_name . "',c_registered='" . $c_registered . "',gstin_no='" . $gstin_no . "',pan_no='" . $pan_no . "',landline_no='" . $landline_no . "',office_address='" . $office_address . "',website='" . $website . "',b_email_id='" . $b_email_id . "',category='" . $category . "',updated_by='" . $_SESSION['id'] . "',updated_date=CURDATE(),user_type='$drp_user_type' where id=" . $id . "";
         } elseif ($id_proof == "" && $light_bill == "") {
             $query = "update " . $this->dealerProfileTable . " set name='" . $name . "',gender='" . $gender . "',date_of_birth='" . $date_of_birth . "',altr_contact_no='" . $altr_contact_no . "',state='" . $state . "',city='" . $city . "',address='" . $address . "',c_name='" . $c_name . "',c_registered='" . $c_registered . "',gstin_no='" . $gstin_no . "',pan_no='" . $pan_no . "',landline_no='" . $landline_no . "',office_address='" . $office_address . "',website='" . $website . "',b_email_id='" . $b_email_id . "',category='" . $category . "',updated_by='" . $_SESSION['id'] . "',updated_date=CURDATE(),user_type='$drp_user_type' where id=" . $id . "";
         }*/
        $query = "call mu_updateDealerProfileById(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $type = "ssssssssssssssssssssi";
        $params = array($name, $gender, $date_of_birth, $altr_contact_no, $state, $city, $address, $id_proof, $light_bill, $c_name, $c_registered,
            $gstin_no, $pan_no, $landline_no, $office_address, $website, $b_email_id, $category, $drp_user_type, $checkname, $id);
        /*$procedure = "call mu_updateDealerProfileById('$name', '$gender', '$date_of_birth', '$altr_contact_no', '$state', '$city', '$address', '$id_proof', '$light_bill', '$c_name', '$c_registered', '$gstin_no', '$pan_no', '$landline_no', '$office_address', '$website', '$b_email_id', '$category','$drp_user_type', $id)";
        echo  $procedure;
        die();*/

        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function displayCustomer($referer_code)
    {
        $controller = new Controller();
        //$sql_query = "select * from " . $this->loginTable . " as lt inner join " . $this->profileTable . " as pt on pt.id=lt.user_id  where pt.referer_code='" . $referer_code . "'";
        $sql = "call mu_displayCustomer(?)";
        $type = "s";
        $params = array($referer_code);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $params);
        return $result;
    }

    function displayRejectedDealer()
    {
        $controller = new Controller();
        //$sql_query = "SELECT * from " . $this->dealerProfileTable . " as dpt inner join " . $this->dealerLoginTable . " as dlt on dpt.id=dlt.user_id where dpt.status=0 and dpt.approve_status='Rejected' order by dpt.id desc";
        $sql = "call mu_displayRejectedDealer()";
        $result = $controller->genericSelectToIterateUsingProcedure($sql);
        return $result;
    }

    function getUserExtraDayStatus($user_id)
    {
        $controller = new Controller();
        // $sql_query = "SELECT extra_day_status,name,expiry_date from " . $this->profileTable . " where id=" . $user_id;
        $sql = "call mu_getUserExtraDayStatus(?)";
        $type = "i";
        $params = array($user_id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $params);
        return $result;
    }

    function updateExtraDayStatus($user_id, $new_expiry_date)
    {
        $controller = new Controller();
        /*$sql_query = "update " . $this->profileTable . " set extra_day_status=1,expiry_date='" . $new_expiry_date . "' where id =" . $user_id . "";
        $result = $controller->genericInsertUpdateDelete($sql_query);
        $sql_query2 = "update " . $this->userSubscriptionTable . " set end_date='" . $new_expiry_date . "' where user_id=" . $user_id . "";*/
        $sql = "call mu_updateExtraDayStatus(?,?)";
        $type = "is";
        $params = array($user_id, $new_expiry_date);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($sql, $type, $params);
        return $result;
    }

    function getDealerPricingById($id)
    {
        $controller = new Controller();
        $sql = "call md_getDealerPricingById(?)";
        $type = "i";
        $param = array($id);
        $status = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $status;
    }

    function updateWalletAmount($payment_status, $date, $payment_remark, $id)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->walletHistoryTable . " set payment_status='$payment_status',payment_date='$date',payment_remark='$payment_remark' where id=" . $id;
        $query = "call md_updateWalletAmount(?,?,?,?)";
        $type = "sssi";
        $params = array($payment_status, $date, $payment_remark, $id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function mu_addWalletamount($user_id, $amount, $payment_remark)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->walletHistoryTable . " set payment_status='$payment_status',payment_date='$date',payment_remark='$payment_remark' where id=" . $id;
        $query = "call mu_addWalletamount(?,?,?)";
        $type = "iis";
        $params = array($user_id, $amount, $payment_remark);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function displayWallerHistoryByDealer($dealer_code)
    {
        $controller = new Controller();
        // $sql_query = "select * from " . $this->walletHistoryTable . " where dealer_code	='" . $dealer_code . "'";
        $sql = "call md_displayWallerHistoryByDealer(?)";
        $type = "s";
        $param = array($dealer_code);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $param);
        return $result;
    }

    function displayWallerHistoryByDealerByPayStatus($dealer_code, $pay_status)
    {
        $controller = new Controller();
        //   $sql_query = "select * from " . $this->walletHistoryTable . " where dealer_code	='" . $dealer_code . "' and payment_status='$pay_status'";
        $sql = "call md_displayWallerHistoryByDealer(?,?)";
        $type = "ss";
        $param = array($dealer_code, $pay_status);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $param);
        return $result;
    }

    function displayWallerHistoryOfUserByPayStatus($user_id, $pay_status)
    {
        $controller = new Controller();
        //   $sql_query = "select * from " . $this->walletHistoryTable . " where dealer_code	='" . $dealer_code . "' and payment_status='$pay_status'";
        $sql = "call mu_displayWallerHistoryOfUserByPayStatus(?,?)";
        $type = "ss";
        $param = array($user_id, $pay_status);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $param);
        return $result;
    }

    function displayWallerHistoryTeamMember($user_id)
    {
        $controller = new Controller();
        // $sql_query = "select * from " . $this->walletHistoryTable . " where dealer_code	='" . $dealer_code . "'";
        $sql = "call mu_displayWallerHistoryTeamMeber(?)";
        $type = "s";
        $param = array($user_id);

        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $param);
        return $result;
    }

    function getDealerPriceDetails()
    {
        $controller = new Controller();
        //   $sql_query = "update " . $this->dealerProfileTable . " set status=1,approve_status='" . $approved_status . "',pay_status='$pay_status' where id ='" . $user_id . "'";
        $sql = "call md_getDealerPriceDetails()";
        $result = $controller->genericSelectToIterateUsingProcedure($sql);
        return $result;
    }

    function updateDealerPercent($percent, $id)
    {
        $controller = new Controller();
        //   $sql_query = "update " . $this->dealerProfileTable . " set status=1,approve_status='" . $approved_status . "',pay_status='$pay_status' where id ='" . $user_id . "'";
        $sql = "call mu_updateDealerPercent(?,?)";
        $type = "ii";
        $param = array($percent, $id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($sql, $type, $param);
        return $result;
    }

    function updateUserLeadCount($type)
    {
        $controller = new Controller();
        // $query = "update " . $this->profileTable. " set user_theme='" . $theme. "' where custom_url = '" . $custom_url . "'";
        $query = "call mu_updateLeadCount(?,?)";
        // $query = "call mu_updateLeadCount('$type','" . $this->getUserSessionId(). "')";
        $data_type = "si";
        $params = array($type, $this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $data_type, $params);
        return $result;
    }

    function total_Notification_count()
    {
        $controller = new Controller();
        $sql = "call mu_total_Notification_count(?)";
        $type = "i";
        $param = array($this->getUserSessionId());
        $status = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $status;
    }

    function total_sub_leads_count($id)
    {
        $controller = new Controller();
        $sql = "call mu_total_sub_leads_count(?)";
        $type = "i";
        $param = array($id);
        $status = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $status;
    }

    function mu_displayPurchasedUser()
    {
        $controller = new Controller();
        $sql = "call mu_displayPurchasedUser()";
        $status = $controller->genericSelectToIterateUsingProcedure($sql);
        return $status;
    }

    function insertUserLogData($page_name, $action, $remark, $img_name = null)
    {
        $controller = new Controller();
        $sql = "call mu_insertUserLog(?,?,?,?,?)";
        $type = "issss";
        $param = array($this->getUserSessionId(), $page_name, $action, $remark, $img_name);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($sql, $type, $param);
        return $status;
    }

    function mu_insertPrivateLinkToken($token_number, $expiry_date)
    {
        $controller = new Controller();
        $sql = "call mu_insertPrivateLinkToken(?,?,?)";
        $type = "iss";
        $param = array($this->getUserSessionId(), $token_number, $expiry_date);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($sql, $type, $param);
        return $status;
    }

//section theme
    function mdm_displaySectionTheme($user_id, $section_id)
    {
        $controller = new Controller();
        // $query = "select * from tb_section_status INNER JOIN tb_user_profile on tb_user_profile.id=tb_section_status.user_id where tb_user_profile.custom_url='" . $custom_url . "' and section_id=" . $section_id . "";
        $query = "call mdm_displaySectionTheme(?,?)";
        $type = "ii";
        $params = array($user_id, $section_id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query, $type, $params);
        return $result;
    }

    function mu_checkduplicateVideo($url)
    {
        $controller = new Controller();
        // $query = "select * from tb_section_status INNER JOIN tb_user_profile on tb_user_profile.id=tb_section_status.user_id where tb_user_profile.custom_url='" . $custom_url . "' and section_id=" . $section_id . "";
        $query = "call mu_checkduplicateVideo(?,?)";
        $type = "si";
        $params = array($url, $this->getUserSessionId());

        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query, $type, $params);
        if ($result != null) {
            return $result;
        } else {
            return false;
        }
    }

    function mu_listAllVideoUsingChannel($channel_id)
    {
        $controller = new Controller();
        // $query = "select * from tb_section_status INNER JOIN tb_user_profile on tb_user_profile.id=tb_section_status.user_id where tb_user_profile.custom_url='" . $custom_url . "' and section_id=" . $section_id . "";
        $query = "call mu_listAllVideoUsingChannel(?,?)";
        $type = "si";
        $params = array($channel_id, $this->getUserSessionId());
        $result = $controller->genericSelectToIterateUsingProcedure($query, $type, $params);
        return $result;
    }

    function mu_deleteExistingVideo($url)
    {
        $controller = new Controller();
        // $query = "select * from tb_section_status INNER JOIN tb_user_profile on tb_user_profile.id=tb_section_status.user_id where tb_user_profile.custom_url='" . $custom_url . "' and section_id=" . $section_id . "";
        $query = "call mu_deleteExistingVideo(?,?)";
        $type = "si";
        $params = array($url, $this->getUserSessionId());

        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function mdm_displayVideoCount()
    {
        $controller = new Controller();
        //  $query = "select * from " . $this->gatewayTable . " where user_id=" . $user_id;
        $query = "call mdm_displayVideoCount(?,?)";
        $type = "ii";
        $params = array($this->getUserSessionId(), '');
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query, $type, $params);
        if ($result['rowNum'] != null) {
            return $result['rowNum'];
        } else {
            return 0;
        }

    }

    function mu_displayVideoDetailsByLimit($start, $end)
    {
        $controller = new Controller();
        //  $query = "select * from " . $this->gatewayTable . " where user_id=" . $user_id;
        $query = "call mu_displayVideoDetailsByLimit(?,?,?,?)";
        $type = "iiii";
        $params = array($this->getUserSessionId(), $start, $end, '');
        $result = $controller->genericSelectToIterateUsingProcedure($query, $type, $params);
        return $result;

    }

    function insertContactNumberLog($contact_no)
    {
        $controller = new Controller();
        //$query = "insert into " . $this->customUrlLogTable . " (user_id,custom_url,date) VALUES ('$user_id','" . $custom_url . "',NOW())";
        $query = "call mu_insertContactNumberLog(?)";
        $type = "s";
        $params = array($contact_no);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function mu_displayInsertLog()
    {
        $controller = new Controller();
        $query = "call mu_displayInsertLog()";
        $status = $controller->genericSelectToIterateUsingProcedure($query);
        return $status;
    }

    function getAllBusinessLinks()
    {
        $controller = new Controller();
        //$query = "insert into " . $this->customUrlLogTable . " (user_id,custom_url,date) VALUES ('$user_id','" . $custom_url . "',NOW())";
        $query = "call mdm_getAllBusinessLinksByIdIterate(?)";
        $type = "i";
        $params = array($this->getUserSessionId());
        $status = $controller->genericSelectToIterateUsingProcedure($query, $type, $params);
        return $status;
    }

    function insertBusinessLink($link)
    {
        $controller = new Controller();
        //$query = "insert into " . $this->customUrlLogTable . " (user_id,custom_url,date) VALUES ('$user_id','" . $custom_url . "',NOW())";
        $query = "call mu_insertBusinessLink(?,?)";
        $type = "si";
        $params = array($link, $this->getUserSessionId());
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function updateBusinessLink($link, $id)
    {
        $controller = new Controller();
        //$query = "insert into " . $this->customUrlLogTable . " (user_id,custom_url,date) VALUES ('$user_id','" . $custom_url . "',NOW())";
        $query = "call mu_updateBusinessLink(?,?,?)";
        $type = "sii";
        $params = array($link, $this->getUserSessionId(), $id);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function getAllBusinessLinksById($id)
    {
        $controller = new Controller();
        //$query = "insert into " . $this->customUrlLogTable . " (user_id,custom_url,date) VALUES ('$user_id','" . $custom_url . "',NOW())";
        $query = "call mu_getAllBusinessLinksById(?)";
        $type = "i";
        $params = array($id);

        $status = $controller->genericSelectAlreadyIteratedUsingProcedure($query, $type, $params);
        return $status;
    }

    function rep_escape($string)
    {
        return str_replace(['\r\n', '\r', '\n', '\\'], '', $string);
    }

    /*function addServiceIcon($section_id, $file_name)
    {
        $controller = new Controller();
        $query = "call mu_insertIcon(?,?,?)";
        $type = "iis";
        $params = array($this->getUserSessionId(), $section_id, $file_name);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }*/
    function addServiceIcon($section_id, $file_name)
    {
        $controller = new Controller();
        $sql_query = "INSERT INTO `tb_section_icon`(`user_id`, `section_id`, `section_img`) VALUES ('" . $this->getUserSessionId() . "',$section_id,'$file_name')";
        /*echo $sql_query;
        exit;*/
        $result = $controller->genericInsertUpdateDelete($sql_query);
        return $result;
    }

    function CheckUserId($id, $section_id)
    {
        $result = false;
        $controller = new Controller();
        //  $sql = "select * from " . $this->loginTable . " where user_id='" . $id . "'";
        $sql = "call mu_checkUserId(?,?)";
        $type = "ii";
        $param = array($id, $section_id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function serviceIconUserId($section_id)
    {
        $result = false;
        $controller = new Controller();
        //  $sql = "select * from " . $this->loginTable . " where user_id='" . $id . "'";
        $sql = "call mu_serviceIconUserId (?,?)";
        $type = "ii";
        $param = array($this->getUserSessionId(), $section_id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function getLastUserId()
    {
        $controller = new Controller();
        $query = "call mu_getLastUserId()";
        $status = $controller->genericSelectAlreadyIteratedUsingProcedure($query);
        return $status;
    }

    function getBasicDataByUserId($table, $user_id)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $table . " where user_id=" . $user_id . " ";
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    function getAllBasicDataByUserId($table, $user_id)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $table . " where user_id=" . $user_id . " ";
        $result = $controller->genericSelectToIterateUsingProcedure($sql_query);
        return $result;
    }

    function getSectionStatus($user_id, $section_id)
    {
        $controller = new Controller();
        $results = false;
        $sql_query = "SELECT * FROM `tb_section_status` WHERE user_id = $user_id and section_id = $section_id and website IN(1) AND digital_card IN(1)";
        $result = $controller->genericSelectCount($sql_query);
        if ($result > 0) {
            $results = true;
        } else {
            $results = false;
        }
        return $results;
    }

    function getSectionStatusUserId()
    {
        $controller = new Controller();
        $sql_query = "SELECT user_id FROM `tb_section_status` GROUP BY user_id; ";
        $result = $controller->genericSelectToIterateUsingProcedure($sql_query);
        return $result;
    }

    function getDealerPercentDataByDealerId($dealer_id)
    {
        $controller = new Controller();
        $sql_query = "SELECT dpr.percentage,dpro.* FROM `tb_dealer_pricing` as dpr INNER JOIN tb_dealer_profile AS dpro ON dpr.id = dpro.dealer_percent WHERE dpro.id = $dealer_id limit 1";
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    function imageCompressor($newPath)
    {
        try {     // Create an instance of the Imagine library
                $imagine = new Imagine();

                // Open the original image
                $image = $imagine->open($newPath);

                // Determine the new width while keeping the aspect ratio
                $newWidth = 1000; // Set the desired width
                $originalSize = $image->getSize();
                $originalWidth = $originalSize->getWidth();
                $originalHeight = $originalSize->getHeight();
                $aspectRatio = $originalWidth / $originalHeight;

                if ($aspectRatio >= 1) {
                    // Landscape image or square image
                    $newHeight = $newWidth / $aspectRatio;
                } else {
                    // Portrait image
                    $newHeight = $newWidth;
                    $newWidth = $newHeight * $aspectRatio;
                }
                $box = new Box($newWidth, $newHeight);

                $image->resize($box);

                $image->save($newPath); 
            } catch (RuntimeException $e) {
                // Handle the exception here, for example, log the error or show a user-friendly message
                echo "An error occurred while compressing the image: " . $e->getMessage();
            }
    // dd($image);  
    // exit;
    }

    function getImageFileSize($imagePath) {
        $sizeInBytes = filesize($imagePath);
        $sizeInKB = round($sizeInBytes / 1024, 2); // Convert to kilobytes (KB)
        return $sizeInKB;
    }


}