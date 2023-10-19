<?php
session_start();
include("controller.php");

class ManageService
{
    public $loginTable = "tb_login";

    public $profileTable = "tb_user_profile";

    public $planTable = "tb_subscription_plan";

    public $userSubscriptionTable = "tb_user_subscription";


    function clearData($value)
    {
        $controller = new Controller();
        $clear_value = mysqli_real_escape_string($controller, $value);
        return $clear_value;
    }

    /** This method perform the login operation for login table
     * @param $email this variable used as a username
     * @param $pass this variable used as a password
     * @return bool|mysqli_result|null if genericSelectToIterate success then it return true else false
     */

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
    function resetUserPassword($user_id,$old_password, $new_password)
    {
        $status = false;
        $controller = new Controller();
        $procedure = "CALL mu_resetUserPassword('" . $user_id . "','$old_password', '$new_password',@p_out_param)";
        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        $results2 = $mysqli->query("SELECT @p_out_param as status");
        $row = $results2->fetch_object();
        if($row->status == "true"){
            return true;
        }else{
            return false;
        }
    }

    function sendMailAsBCC($toName, $toEmail, $subject, $message,$email_list)
    {
        $sendMail = new sendMailSystem1();
        $status = false;
        $sendMailStatus = $sendMail->sendMail($toName, $toEmail, MAIL_FROM_NAME, MAIL_FROM_EMAIL, $subject, $message,$email_list);
        if ($sendMailStatus) {
            $status = true;
        } else {
            $status = false;
        }
        return $status;
    }

    function sendSMS($contact, $message)
    {
        $url = "http://smspanel.kubictechnology.com/API/WebSMS/Http/v1.0a/index.php";
        /*$url = "http://123.108.46.12/API/WebSMS/Http/v1.0a/index.php";*/
        $username = "DGCARD";
        $password = "dgcard@123";
        $sender_id = "DGCARD";
        /*$message = "hii";*/
        $sendSmsUrl = $url . "?username=" . urlencode($username) . "&password=" . urlencode($password) . "&sender=" . urlencode($sender_id) . "&to=" . urlencode($contact) . "&message=" . urlencode($message) . "&reqid=1&format=json";
        $sendSmsUrl = str_replace(" ", "%20", $sendSmsUrl);
        $json = file_get_contents($sendSmsUrl);
        $json = json_decode($json);
        if ($json->billcredit == "1.00") {
            return true;
        } else {
            return false;
        }
    }

    function userLogin($email, $pass)
    {
        $controller = new Controller();
      //  $sql_query = "SELECT l.email,up.custom_url,up.name,up.img_name,l.contact_no,l.user_id,up.expiry_date,up.user_referer_code,up.status,up.designation,l.api_key FROM " . $this->loginTable . " l inner join " . $this->profileTable . " up on l.user_id=up.id where (l.email = '" . $email . "' or l.contact_no='" . $email . "') and l.password = '" . $pass . "'";
        $sql_query = "call ms_userLogin(?,?)";
        $type = "ss";
        $params = array($email, $pass);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql_query,$type,$params);
        return $result;
    }
    function updateUserNotification($token,$user_id)
    {
        $controller = new Controller();
        //$sql_query = "update " . $this->loginTable . " set user_notification ='" . $token . "' where user_id='" . $user_id. "'";
        $sql_query = "call ms_updateUserNotification(?,?)";
        $type = "si";
        $param = array($token,$user_id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($sql_query);
        return $result;
    }
    function validateUserIdAndAPIKey($id,$api_key)
    {
        $result = false;
        $controller = new Controller();
      //  $sql = "select l.email,up.custom_url,up.name,up.img_name,l.contact_no,l.user_id,up.expiry_date,up.user_referer_code,up.status,up.designation,l.api_key FROM " . $this->loginTable . " l inner join " . $this->profileTable . " up on l.user_id=up.id where l.user_id='" . $id . "' and l.api_key='$api_key'";
        $sql = "call ms_validateUserIdAndAPIKey(?,?)";
        $type = "is";
        $param = array($id,$api_key);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }

    function getRegisterContacts()
    {
        $controller = new Controller();
        //$sql_query = "SELECT contact_no FROM " . $this->loginTable;
        $sql_query = "call ms_getRegisterContacts()";
        $result = $controller->genericSelectToIterateUsingProcedure($sql_query);
        return $result;
    }

    function getSubscriptionDetailsById($user_id)
    {
        $controller = new Controller();
       // $sql_query = "SELECT year FROM " . $this->userSubscriptionTable ." where user_id='$user_id' and active_plan=1 limit 1";
        $sql_query = "call ms_getSubscriptionDetailsById(?)";
        $type = "i";
        $param = array($user_id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql_query,$type,$param);
        return $result;
    }

    public function validateContactDetails($email, $contact)
    {
        $controller = new Controller();
        //$sql_query = "SELECT id FROM " . $this->loginTable . " where email = '" . $email . "' and contact_no='" . $contact . "'";
        $query = "call ms_validateContactDetails(?,?)";
        $type = "ss";
        $param  =array($email, $contact);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query,$type,$param);
        return $result;
    }

    public function updateContactId($email, $contact, $id_list)
    {
        $result = false;
        $controller = new Controller();
        $contactCheck = "";
        if (strpos($id_list, "#") !== false) {
            $contactCheck = explode("#", $id_list);
        } else {
            $contactCheck = $id_list;
        }

        if (is_array($contactCheck)) {
            $sql_query = "SELECT user_id FROM " . $this->loginTable . " where contact_no In (";
            foreach ($contactCheck as $value) {
                $sql_query .= "'" . $value . "',";
            }
            $sql_query = substr($sql_query, 0, strlen($sql_query) - 1);
            $sql_query .= ")";
            $newResult = $controller->genericSelectToIterate($sql_query);
        } else {
            $sql_query = "SELECT user_id FROM " . $this->loginTable . " where contact_no In ('" . $contactCheck . "')";
            $newResult = $controller->genericSelectToIterate($sql_query);
        }

        if ($newResult != null) {
            $idListComma = "";
            while ($row = mysqli_fetch_assoc($newResult)) {
                $idListComma .= $row["user_id"] . ",";
            }
            if ($idListComma != "") {
                $idListComma = substr($idListComma, 0, strlen($idListComma) - 1);
                $sql_query = "update " . $this->loginTable . " set user_contact='" . $idListComma . "' where email='" . $email . "' and contact_no='" . $contact . "'";
                $result = $controller->genericInsertUpdateDelete($sql_query);
            }
        }
        return $result;
    }

    /*function getAllUser()
    {
        $controller = new Controller();
        $sql_query = "select * from " . $this->loginTable . " as lt inner join " . $this->profileTable . " as pt on pt.id=lt.user_id";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }*/

    function getdaysRemaining($days)
    {
        $controller = new Controller();
        $query = "call ms_getUserDetails(?)";
        $type = "i";
        $param = array('0');
        $result = $controller->genericSelectToIterateUsingProcedure($query,$type,$param);
        return $result;
    }
    function mu_insertPrivateLinkToken($user_id,$token_number,$expiry_date)
    {
        $controller = new Controller();
        $sql = "call mu_insertPrivateLinkToken(?,?,?)";
        $type = "iss";
        $param = array($user_id,$token_number,$expiry_date);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($sql,$type,$param);
        return $status;
    }
    function displayUserSubscriptionDetails($user_id)
    {
        $controller = new Controller();
        //  $sql_query = "select ust.year,pt.expiry_date,pt.referer_code,pt.sell_ref,lt.user_notification from " . $this->loginTable . " as lt inner join " . $this->profileTable . " as pt on pt.id=lt.user_id inner join " . $this->userSubscriptionTable . " as ust on pt.id=ust.user_id where pt.id='" . $this->getUserSessionId() . "' and ust.active_plan=1 limit 1";
        $sql = "call mu_displayUserSubscriptionDetails(?)";
        $type = "i";
        $param = array($user_id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }













}