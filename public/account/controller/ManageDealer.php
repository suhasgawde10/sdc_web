<?php
session_start();
include("controller.php");

class ManageDealer
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

    public $logoTable = "tb_logo";

    public $mailSettingTable = "tb_mail_setting";

    public $blogTable = "tb_blog";

    public $dealerLoginTable = "tb_dealer_login";

    public $dealerProfileTable = "tb_dealer_profile";

    public $userSubscriptionTable = "tb_user_subscription";

    public $subscriptionPlanTable = "tb_subscription_plan";

    public $customUrlLogTable = "tb_custom_log";

    public $dealerPlanTable = "tb_dealer_subscription_plan";

    public $walletHistoryTable = "tb_wallet_history";

    public $sectionNameTable = "tb_section_name";

    public $serviceRequestTable = "tb_service_request";



    function sendSMS($contact, $message)
    {
        
        $sendSmsUrl = SMS_URL . "?authkey=" . trim(AUTH_KEY) . "&mobiles=" . urlencode($contact) . "&message=" . urlencode($message) . "&sender=".trim(SMS_SENDER)."&route=4";
        $sendSmsUrl1 = str_replace(" ", "%20", $sendSmsUrl);
        $json = file_get_contents($sendSmsUrl1);
        if(is_string($json)){
            return true;
        } else {
            return false;
        }
    }


    function smsCreditChecker()
    {
        $url = "http://smspanel.kubictechnology.com/API/WebSMS/Http/v1.0a/index.php";
        /*$url = "http://123.108.46.12/API/WebSMS/Http/v1.0a/index.php";*/
        $username = "DGCARD";
        $password = "dgcard@123";
        $sender_id = "DGCARD";
        /*$message = "hii";*/
        $sendSmsUrl = $url . "?username=" . urlencode($username) . "&password=" . urlencode($password) . "&method=credit_check&format=json";
        $jsonContent = file_get_contents($sendSmsUrl);
        $json = json_decode($jsonContent);
        //$decoded = json_decode($json);
        $key = '2';
        $availablecredit = $json->$key->availablecredit;
        return $availablecredit;
    }

    function resetDealerPassword($old_password, $new_password)
    {
        $status = false;
        $controller = new Controller();
        $procedure = "CALL md_resetDealerPassword('" . $this->getUserSessionId() . "','$old_password', '$new_password',@p_out_param)";
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

    function updateUserCode($id,$referral_code)
    {
        $controller = new Controller();
        //$query = "update " . $this->profileTable . " set user_referer_code = '" . $_SESSION['user_code'] . "' where id = " . $id . "";
        $query = "call mu_updateUserCode(?,?)";
        $type ="is";
        $param = array($id,$referral_code);
        $id = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$param);
        return $id;
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

    function sendMailAdmin($toName, $toEmail, $subject, $message)
    {
        $sendMail = new sendMailSystem();
        $status = false;
        $sendMailStatus = $sendMail->sendMailAdmin($toName, $toEmail, MAIL_FROM_NAME, MAIL_FROM_EMAIL, $subject, $message);
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


    function validateContact($contact_no)
    {
        $result = false;
        $controller = new Controller();
        $sql = "call md_validateContact(?)";
        $type = "s";
        $param = array($contact_no);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }


    /* function validateTeamContact($contact_no)
     {
         $controller = new Controller();
         $query = "select * from " . $this->dealerLoginTable . " where contact_no='" . $contact_no . "'";
         $result = $controller->genericSelectCount($query);
         if ($result > 0) {
             return true;
         } else {
             return false;
         }
     }*/

    function validateRegisterEmail($email)
    {
        $result = false;
        $controller = new Controller();
        /* $sql = "select * from " . $this->loginTable . " where email='" . $email . "'";*/
        $sql = "call md_validateRegisterEmail(?)";
        $type = "s";
        $param = array($email);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }

    function validateContactByID($contact_no, $id)
    {
        $controller = new Controller();
        //    $query = "select * from " . $this->loginTable . " where contact_no='" . $contact_no . "' and user_id !=" . $id;
        $query = "call md_validateContactByID(?,?)";
        $type = "si";
        $params = array($contact_no, $id);
        $result = $controller->genericSelectCountUsingProcedure($query,$type,$params);
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
        $sql = "call md_validateRegisterEmailByID(?,?)";
        $type = "si";
        $param = array($email, $id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
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
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }

    function validateUserRegisterEmail($email)
    {
        $result = false;
        $controller = new Controller();
        $sql = "call mu_validateRegisterEmail(?)";
        $type = "s";
        $param = array($email);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }


    function addUser($name, $gender)
    {
        $controller = new Controller();
     //   $query = "insert into " . $this->dealerProfileTable . " (name,gender,status,created_date,message_status,block_status,approve_status) VALUES ('$name','$gender',0,curdate(),0,1,'Pending')";
        $query = "call md_addUser(?,?)";
        $type = "ss";
        $param = array($name, $gender);
        $procedure = "CALL md_addUser('$name', '$gender',@p_out_param)";

        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        $results2 = $mysqli->query("SELECT @p_out_param as last_id");
        $row = $results2->fetch_object();
        return $row->last_id;
    }

    function updateDealerCode($id)
    {
        $controller = new Controller();
        // $query = "update " . $this->dealerProfileTable . " set dealer_code = '" . $_SESSION['dealer_code'] . "' where id = " . $id . "";
        $query = "call md_updateDealerCode(?,?)";
        $type = "si";
        $param = array($_SESSION['dealer_code'],$id);
        $id = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$param);
        return $id;
    }

    function updateTeamLogin($email, $contact,$id)
    {
        $controller = new Controller();
      //  $query = "update " . $this->dealerLoginTable . " set email = '" . $email. "',contact_no= '" . $contact . "' where user_id = '" . $id . "'";
        $query = "call md_updateTeamLogin(?,?,?)";
        $type = "ssi";
        $param = array($email, $contact,$id);
        $id = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$param);
        return $id;
    }

    function addTeamLogin($user_id, $type, $email, $contact, $password)
    {
        $controller = new Controller();
        // $query = "insert into " . $this->dealerLoginTable . " (user_id,type,email,contact_no,password,dealer_code) VALUES ('$user_id','" . $type . "', '$email','$contact','$password','" . $_SESSION['dealer_code'] . "')";
        $query = "call md_addTeamLogin(?,?,?,?,?,?)";
        $data_type = "isssss";
        $param = array($user_id, $type, $email, $contact, $password,$_SESSION['dealer_code']);
        $id = $controller->genericInsertUpdateDeleteUsingProcedure($query,$data_type,$param);
        return $id;
    }

    function addUserLogin($user_id, $type, $email, $contact, $password)
    {
        $controller = new Controller();
        //  $query = "insert into " . $this->dealerLoginTable . " (user_id,type,email,contact_no,password) VALUES ('$user_id','" . $type . "', '$email','$contact','$password')";
        $query = "call md_addUserLogin(?,?,?,?,?)";
        $data_type = "issss";
        $param = array($user_id, $type, $email, $contact, $password);
        $id = $controller->genericInsertUpdateDeleteUsingProcedure($query,$data_type,$param);
        return $id;
    }

    function getSpecificDealerProfile()
    {
        $controller = new Controller();
//        $sql_query = "select * from " . $this->dealerProfileTable . " as dpt inner join " . $this->dealerLoginTable . " as dlt on dpt.id=dlt.user_id where dlt.user_id='" . $this->getUserSessionId() . "'";
        $query = "call mu_getSpecificDealerProfileByUserId(?)";
        $type = "i";
        $param = array($this->getUserSessionId());
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query,$type,$param);
        return $result;
    }

    function getSpecificDealerProfileByUserId($id)
    {
        $controller = new Controller();
       // $sql_query = "select dpt.*,dlt.email,dlt.contact_no,dlt.password,dlt.user_id from " . $this->dealerProfileTable . " as dpt inner join " . $this->dealerLoginTable . " as dlt on dpt.id=dlt.user_id where dlt.user_id='" . $id . "'";
        $query = "call mu_getSpecificDealerProfileByUserId(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query,$type,$param);
        return $result;
    }

    function updateDealerProfile($name, $gender, $date_of_birth, $altr_contact_no, $state, $city, $address, $id_proof, $light_bill, $c_name, $c_registered, $gstin_no, $pan_no, $landline_no, $office_address, $website, $b_email_id, $category, $approve_status,$drp_user_type,$checkname)
    {
        $controller = new Controller();
       /* if ($id_proof != "" && $light_bill == "") {
            $query = "update " . $this->dealerProfileTable . " set name='" . $name . "',gender='" . $gender . "',date_of_birth='" . $date_of_birth . "',altr_contact_no='" . $altr_contact_no . "',state='" . $state . "',city='" . $city . "',address='" . $address . "',id_proof='" . $id_proof . "',c_name='" . $c_name . "',c_registered='" . $c_registered . "',gstin_no='" . $gstin_no . "',pan_no='" . $pan_no . "',landline_no='" . $landline_no . "',office_address='" . $office_address . "',website='" . $website . "',b_email_id='" . $b_email_id . "',category='" . $category . "',updated_by='" . $_SESSION['dealer_name'] . "',updated_date=CURDATE(),message_status=1,approve_status='" . $approve_status . ",user_type='$drp_user_type' where id=" . $this->getUserSessionId() . "";
        } elseif ($id_proof == "" && $light_bill != "") {
            $query = "update " . $this->dealerProfileTable . " set name='" . $name . "',gender='" . $gender . "',date_of_birth='" . $date_of_birth . "',altr_contact_no='" . $altr_contact_no . "',state='" . $state . "',city='" . $city . "',address='" . $address . "',light_bill='" . $light_bill . "',c_name='" . $c_name . "',c_registered='" . $c_registered . "',gstin_no='" . $gstin_no . "',pan_no='" . $pan_no . "',landline_no='" . $landline_no . "',office_address='" . $office_address . "',website='" . $website . "',b_email_id='" . $b_email_id . "',category='" . $category . "',updated_by='" . $_SESSION['dealer_name'] . "',updated_date=CURDATE(),message_status=1,approve_status='" . $approve_status . "',user_type='$drp_user_type' where id=" . $this->getUserSessionId() . "";
        } elseif ($id_proof != "" && $light_bill != "") {
            $query = "update " . $this->dealerProfileTable . " set name='" . $name . "',gender='" . $gender . "',date_of_birth='" . $date_of_birth . "',altr_contact_no='" . $altr_contact_no . "',state='" . $state . "',city='" . $city . "',address='" . $address . "',id_proof='" . $id_proof . "',light_bill='" . $light_bill . "',c_name='" . $c_name . "',c_registered='" . $c_registered . "',gstin_no='" . $gstin_no . "',pan_no='" . $pan_no . "',landline_no='" . $landline_no . "',office_address='" . $office_address . "',website='" . $website . "',b_email_id='" . $b_email_id . "',category='" . $category . "',updated_by='" . $_SESSION['dealer_name'] . "',updated_date=CURDATE(),message_status=1,approve_status='" . $approve_status . "',user_type='$drp_user_type' where id=" . $this->getUserSessionId() . "";
        } elseif ($id_proof == "" && $light_bill == "") {
            $query = "update " . $this->dealerProfileTable . " set name='" . $name . "',gender='" . $gender . "',date_of_birth='" . $date_of_birth . "',altr_contact_no='" . $altr_contact_no . "',state='" . $state . "',city='" . $city . "',address='" . $address . "',c_name='" . $c_name . "',c_registered='" . $c_registered . "',gstin_no='" . $gstin_no . "',pan_no='" . $pan_no . "',landline_no='" . $landline_no . "',office_address='" . $office_address . "',website='" . $website . "',b_email_id='" . $b_email_id . "',category='" . $category . "',updated_by='" . $_SESSION['dealer_name'] . "',updated_date=CURDATE(),message_status=1,approve_status='" . $approve_status . "',user_type='$drp_user_type' where id=" . $this->getUserSessionId() . "";
        }*/
         $query = "call md_updateDealerProfile(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        //$query = "call md_updateDealerProfile('$name', '$gender', '$date_of_birth', '$altr_contact_no', '$state', '$city', '$address', '$id_proof', '$light_bill', '$c_name', '$c_registered', '$gstin_no', '$pan_no', '$landline_no', '$office_address', '$website', '$b_email_id', '$category','$approve_status','$drp_user_type','" .$this->getUserSessionId(). "',)";
        $type = "sssssssssssssssssssssii";
        $params = array($name, $gender, $date_of_birth, $altr_contact_no, $state, $city, $address, $id_proof, $light_bill, $c_name, $c_registered, $gstin_no, $pan_no, $landline_no, $office_address, $website, $b_email_id, $category,$approve_status,$drp_user_type,$checkname,$this->getUserSessionId(), $this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
        return $result;
    }

    function updateTeamProfile($name, $id_proof, $light_bill,$id)
    {
        $controller = new Controller();
       /* if ($id_proof != "" && $light_bill == "") {
            $query = "update " . $this->dealerProfileTable . " set name='" . $name . "',id_proof='" . $id_proof . "',updated_by='" . $this->getUserSessionId() . "',updated_date=CURDATE() where id=" . $id . "";
        } elseif ($id_proof == "" && $light_bill != "") {
            $query = "update " . $this->dealerProfileTable . " set name='" . $name . "',light_bill='" . $light_bill . "',updated_by='" . $this->getUserSessionId() . "',updated_date=CURDATE() where id=" . $id . "";
        } elseif ($id_proof != "" && $light_bill != "") {
            $query = "update " . $this->dealerProfileTable . " set name='" . $name . "',id_proof='" . $id_proof . "',light_bill='" . $light_bill . "',updated_by='" . $this->getUserSessionId() . "',updated_date=CURDATE() where id=" . $id . "";
        } elseif ($id_proof == "" && $light_bill == "") {
            $query = "update " . $this->dealerProfileTable . " set name='" . $name . "',updated_by='" . $this->getUserSessionId() . "',updated_date=CURDATE() where id=" . $id . "";
        }*/

        $query = "call md_updateTeamProfile(?,?,?,?,?)";
        $type = "sssii";
        $params = array($name, $id_proof, $light_bill,$this->getUserSessionId(),$id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
        return $result;
    }

    function insertTeamProfile($name,$id_proof, $light_bill,$approve_status)
    {
        $controller = new Controller();
       //  $query = "insert into " . $this->dealerProfileTable . " (name,id_proof,light_bill,status,created_by,created_date,message_status,block_status,approve_status) values ('" . $name . "','" . $id_proof . "','" . $light_bill . "',1,'" .$this->getUserSessionId() . "',CURDATE(),1,1,'$approve_status') ";
        $query = "call md_insertTeamProfile(?,?,?,?)";
        $type = "ssssi";
        $params = array($name, $id_proof, $light_bill,$approve_status,$this->getUserSessionId());
        $procedure = "CALL md_insertTeamProfile('$name', '$id_proof', '$light_bill','$approve_status','" . $this->getUserSessionId(). "',@p_out_param)";
        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        $results2 = $mysqli->query("SELECT @p_out_param as last_id");
        $row = $results2->fetch_object();
        return $row->last_id;
    }

    function publishUnpublish($id, $block_status, $tableName)
    {
        $controller = new Controller();
        //$updateQuery = "update " . $tableName . " set status=" . $block_status . " where id=" . $id . "";
        $sql = "call mu_publishUnpublish(?,?,?)";
        $type = "ssi";
        $params = array($tableName,$block_status,$id);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($sql,$type,$params);
        return $status;
    }


    /*function update_email_count()
    {
        $controller = new Controller();
        $sql_query = "update " . $this->profileTable . " set email_count = 1 where id=" . $this->getUserSessionId() . "";
        $result = $controller->genericInsertUpdateDelete($sql_query);
        return $result;
    }*/

    function update_user_email_count($id) // update_email_count
    {
        $controller = new Controller();
      //  $sql_query = "update " . $this->profileTable . " set email_count = 1 where id=" . $id;
        $query = "call mu_update_email_count(?)";
        $type = "i";
        $params = array($id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
        return $result;
    }

    function updateProfilePhoto($imgFile)
    {
        $controller = new Controller();
       // $query = "update " . $this->dealerProfileTable . " set img_name = '" . $imgFile . "' where id=" . $this->getUserSessionId() . "";
        $query = "call md_updateProfilePhoto(?,?)";
        $type = "si";
        $params = array($imgFile,$this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
        return $result;
    }

    function getUserSessionId()
    {
        if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
            $id = $_SESSION["create_user_id"];
        } else {
            $id = $_SESSION["dealer_id"];
        }
        return $id;
    }

    function  getUserSessionEmail()
    {
        if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
            $email = $_SESSION["create_user_email"];
        } else {
            $email = $_SESSION["dealer_email"];
        }
        return $email;
    }

    function  getUserSessionName()
    {
        if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
            $name = $_SESSION["create_user_name"];
        } else {
            $name = $_SESSION["dealer_name"];
        }
        return $name;
    }

   /* function update_email_id($email)
    {
        $controller = new Controller();
        $sql_query = "update " . $this->loginTable . " set email='" . $email . "' where user_id =" . $this->getUserSessionId() . "";
        $result = $controller->genericInsertUpdateDelete($sql_query);
        return $result;
    }

    function update_contact_no($contact)
    {
        $controller = new Controller();
        $sql_query = "update " . $this->loginTable . " set contact_no=" . $contact . " where user_id =" . $this->getUserSessionId() . "";
        $result = $controller->genericInsertUpdateDelete($sql_query);
        return $result;
    }*/

    function getUserExpiryDate()
    {
        $controller = new Controller();
       // $query = "select * from " . $this->profileTable . " where id = '" . $this->getUserSessionId() . "'";
        $sql = "call mu_getUserExpiryDate(?)";
        $type = "i";
        $param = array($this->getUserSessionId());
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }

    function selectTheme()
    {
        $controller = new Controller();
        // $query = "select * from " . $this->dealerProfileTable . " where id=" . $this->getUserSessionId() . "";
        $sql = "call md_selectTheme(?)";
        $type = "i";
        $param = array($this->getUserSessionId());
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }

    function adminLogin($contact, $pass)
    {
        $status = false;
        $controller = new Controller();
       // $sql_query = "select * from " . $this->dealerLoginTable . " where (contact_no='" . $contact . "' or email='" . $contact . "') and password='" . $pass . "'";
        $sql = "call md_adminLogin(?,?)";
        $type = "ss";
        $param = array($contact, $pass);
//        print_r($param);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }

    function getDealerProfile($user_id)
    {
        $controller = new Controller();
//        $sql_query = "SELECT dpt.* FROM " . $this->dealerProfileTable . " as dpt INNER JOIN " . $this->dealerLoginTable . " as dlt on dpt.id=dlt.user_id where dpt.id=" . $user_id . "";
        $sql = "call mu_getDealerProfileById(?)";
        $type = "i";
        $param = array($user_id);

        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }

    function getTeamProfile()
    {
        $controller = new Controller();
        // $sql_query = "SELECT * FROM " . $this->dealerProfileTable . " as dpt INNER JOIN " . $this->dealerLoginTable . " as dlt on dpt.id=dlt.user_id where dlt.dealer_code='" . $_SESSION['dealer_code'] . "'";
        $sql = "call md_getTeamProfile(?)";
        $type = "s";
        $param = array($_SESSION['dealer_code']);
        $result = $controller->genericSelectToIterateUsingProcedure($sql,$type,$param);
        return $result;
    }

    function getSessionDealerProfile()
    {
        $controller = new Controller();
        // $sql_query = "SELECT * FROM " . $this->dealerProfileTable . " as dpt INNER JOIN " . $this->dealerLoginTable . " as dlt on dpt.id=dlt.user_id where dpt.id='" . $this->getUserSessionId() . "'";
        $sql = "call mu_getDealerProfileById(?)";
        $type = "i";
        $param = array($this->getUserSessionId());
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }

    function displayDealerProfile()
    {
        $controller = new Controller();
       /* if(isset($_SESSION['dealer_type']) && $_SESSION['dealer_type']=="dealer") {
            $sql_query = "select * from " . $this->dealerProfileTable . " where id =" . $this->getUserSessionId() . "";
        }else{
            $sql_query = "select * from " . $this->dealerProfileTable . " where dealer_code ='" . $_SESSION['dealer_code'] . "'";
        }*/
        $sql = "call md_displayDealerProfile(?,?,?)";
        $type = "ssi";
        $param = array($_SESSION['dealer_type'],$_SESSION['dealer_code'],$this->getUserSessionId());
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }

    function subscriptionPlan()
    {
        $controller = new Controller();
    //    $sql_query = "select * from " . $this->dealerPlanTable . " where year!='Free Trail (5 days)' order by year";
        // $query = "call md_subscriptionPlan()";
        $query = "call mu_subscriptionPlan()";
        $result = $controller->genericSelectToIterateUsingProcedure($query);
        return $result;
    }

    function getUserSubscriptionPlan()
    {
        $controller = new Controller();
    //    $sql_query = "select * from " . $this->dealerPlanTable . " where year!='Free Trail (5 days)' order by year";
        $query = "call mu_subscriptionPlan()";
        $result = $controller->genericSelectToIterateUsingProcedure($query);
        return $result;
    }
    function subscriptionPlanForTrial()
    {
        $controller = new Controller();
       // $sql_query = "select * from " . $this->dealerPlanTable . " order by id";
        // $query = "call md_subscriptionPlanForTrial()";
        $query = "call mu_subscriptionPlanWithFree()";
        $result = $controller->genericSelectToIterateUsingProcedure($query);
        return $result;
    }

    function insertUserData($user_id,$data_type, $year,$plan_amount, $amount, $sDate, $endDate, $status, $active_plan, $invoice_no, $tax, $total_amount, $payment_brand, $payment_mode, $bankId, $timestamp,$dealer_by_pay, $payment_type,$for_bill,$for_email,$user_gstno,$for_pan,$from_bill,$from_gstno,$from_pan,$sac_code,$order_id,$payment_id,$error_code,$error_desc,$p_address)// referral_code
    {
        $controller = new Controller();

        $params = array($user_id,$data_type, $amount, $sDate, $endDate, $status, $active_plan, $invoice_no, $tax, $total_amount, $payment_brand, $payment_mode, $bankId, $timestamp, $dealer_gstn_no,$dealer_by_pay,$_SESSION['dealer_code']);
        $procedure = "CALL md_insertUserData('$user_id','$data_type','$year','$plan_amount', '$amount', '$sDate', '$endDate', '$status', '$active_plan', '$invoice_no', '$tax', '$total_amount', '$payment_brand', '$payment_mode', '$bankId', '$timestamp','$dealer_by_pay', '$payment_type','$for_bill','$for_email','$user_gstno','$for_pan','$from_bill','$from_gstno','$from_pan','$sac_code','$order_id','$payment_id','$error_code','$error_desc','$p_address','" . $_SESSION['dealer_code']. "',@p_out_param)";
        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        $results2 = $mysqli->query("SELECT @p_out_param as last_id");
        $row = $results2->fetch_object();
        return $row->last_id;
       /* $result = $controller->genericGetLastInsertedIdUsingProcedure($query,$type,$params);
        return $result;*/
    }

    function addUserDetails($name, $custom_url, $gender,$sell_ref,$dealer_id,$online_search,$country,$state,$city,$txt_company_name)
    {
        $controller = new Controller();
       // $query = "insert into " . $this->profileTable . " (theme_id,subscription_id,name,custom_url,gender,status,referer_code,created_by,created_date,update_user_count,email_count,verify_number,sell_ref,dealer_id,country) VALUES ('theme1','0','$name','$custom_url','$gender',1,'" . $_SESSION['dealer_code'] . "','" . $_SESSION['dealer_name'] . "',NOW(),0,0,0,'$sell_ref','$dealer_id','India')";
       /* $query = "call md_addUserDetails(?,?,?,?,?,?)";
        $type = "ssssss";
        $params = array($name, $custom_url, $gender,$sell_ref,$dealer_id,$_SESSION['dealer_name']);*/

        $procedure = "CALL md_addUserDetails('$name', '$custom_url', '$gender','$sell_ref','$dealer_id','" . $_SESSION['dealer_code']. "','" . $_SESSION['dealer_name']. "','$online_search','$country','$state','$city','$txt_company_name',@p_out_param)";
        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        $results2 = $mysqli->query("SELECT @p_out_param as last_id");
        $row = $results2->fetch_object();
        return $row->last_id;
        /*$result = $controller->genericGetLastInsertedIdUsingProcedure($query,$type,$params);
        return $result;*/
    }
    function getCountryCategory()
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->userSubscriptionTable . " where user_id='" . $id . "' order by id desc";
        $sql = "call mu_getCountryCategory()";
        $result = $controller->genericSelectToIterateUsingProcedure($sql);
        return $result;
    }
    function insertUserCreditData($year, $plan_amount,$taxable_amount, $sDate, $endDate, $status, $referral_code, $refrenced_by, $active_plan, $invoice_no, $discount, $tax, $total_amount, $payment_brand, $payment_mode, $bankId, $timestamp,$dealer_by_pay,$qty, $payment_type,$for_bill,$for_email,$user_gstno,$for_pan,$from_bill,$from_gstno,$from_pan,$sac_code,$id)
    {
        $controller = new Controller();
        // $query = "insert into " . $this->userSubscriptionTable . " (user_id,type,year,taxable_amount,start_date,end_date,status,referral_code,referenced_by,active_plan,invoice_no,discount,tax,total_amount,payment_brand,payment_mode,custBankId,timestamp,gstn_no) values (" . $this->getUserSessionId() . ",1,'" . $year . "','" . $taxable_amount . "','" . $sDate . "','" . $endDate . "','" . $status . "','" . $referral_code . "','" . $refrenced_by . "','" . $active_plan . "','" . $invoice_no . "','" . $discount . "','" . $tax . "','" . $total_amount . "','" . $payment_brand . "','" . $payment_mode . "','" . $bankId . "','" . $timestamp . "','" . $user_gstno . "')";
        $procedure = "CALL md_insertUserCreditData('$year','$plan_amount', '$taxable_amount', '$sDate', '$endDate', '$status', '$referral_code', '$refrenced_by', '$active_plan', '$invoice_no', '$discount', '$tax', '$total_amount', '$payment_brand', '$payment_mode', '$bankId', '$timestamp','$dealer_by_pay','$qty','$payment_type','$for_bill','$for_email','$user_gstno','$for_pan','$from_bill','$from_gstno','$from_pan','$sac_code', '" . $id. "',@p_out_param)";

        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        $results2 = $mysqli->query("SELECT @p_out_param as last_id");
        $row = $results2->fetch_object();
        return $row->last_id;

        // $result = $controller->genericGetLastInsertedIdUsingProcedure($query,$type,$params);
        // return $result;
    }
    function mu_insertUserCredit($year, $qty,$id)
    {
        $controller = new Controller();
        $query = "CALL mu_insertUserCredit(?,?,?)";
        $type = "sii";
        $params = array($year, $qty,$id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
        return $result;
    }

    function md_updateCompanyInfoDealer($company, $gstno)
    {
        $controller = new Controller();
        $query = "CALL md_updateCompanyInfoDealer(?,?,?)";
        $type = "ssi";
        $params = array($company, $gstno,$this->getUserSessionId());
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
        return $result;
    }
    function getProfilePercent($user_id)
    {
        $controller = new Controller();
        $percent = 10;
        $profile_sql_query = "SELECT img_name from " . $this->profileTable . " where id='" . $user_id . "'";
        $profile = $controller->genericSelectAlreadyIterated($profile_sql_query);
        if($profile !=null){
            $percent +=10;
        }
        $sql_query = "SELECT id from " . $this->serviceTable . " where user_id='$user_id'";
        $service = $controller->genericSelectAlreadyIterated($sql_query);
        if($service !=null){
            $percent +=10;
        }
        $gallery_query = "SELECT id from " . $this->imageTable . " where user_id='$user_id'";
        $gallery_result = $controller->genericSelectAlreadyIterated($gallery_query);
        if($gallery_result !=null){
            $percent +=10;
        }
        $video_query = "SELECT id from " . $this->videoTable . " where user_id='$user_id'";
        $video_query_result = $controller->genericSelectAlreadyIterated($video_query);
        if($video_query_result !=null){
            $percent +=10;
        }
        $client_query = "SELECT id from " . $this->clientTable . " where user_id='$user_id'";
        $client_query_result = $controller->genericSelectAlreadyIterated($client_query);
        if($client_query_result !=null){
            $percent +=10;
        }
        $review_query = "SELECT id from " . $this->clientReviewTable . " where user_id='$user_id'";
        $review_query_result = $controller->genericSelectAlreadyIterated($review_query);
        if($review_query_result !=null){
            $percent +=10;
        }
        $team_query = "SELECT id from " . $this->ourTeamTable . " where user_id='$user_id'";
        $team_query_result = $controller->genericSelectAlreadyIterated($team_query);
        if($team_query_result !=null){
            $percent +=10;
        }
        $upi_query = "SELECT id from " . $this->gatewayTable . " where user_id='$user_id'";
        $upi_query_result = $controller->genericSelectAlreadyIterated($upi_query);
        if($upi_query_result !=null){
            $percent +=10;
        }
        $bank_query = "SELECT id from " . $this->bankDetailsTable . " where user_id='$user_id'";
        $bank_query_result = $controller->genericSelectAlreadyIterated($bank_query);
        if($bank_query_result !=null){
            $percent +=10;
        }
        return $percent;

    }
    function addUserLoginDetails($user_id, $type, $email, $contact, $password,$api_key)
    {
        $controller = new Controller();
       // $query = "insert into " . $this->loginTable . " (user_id,type,email,contact_no,password,api_key) VALUES ('$user_id','" . $type . "', '$email','$contact','$password','$api_key')";
        $query = "call mu_addUserLogin(?,?,?,?,?,?)";
        $data_type = "isssss";
        $params = array($user_id, $type, $email, $contact, $password,$api_key);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$data_type,$params);
        return $result;
    }

    function addMenuBar($id)
    {
        $controller = new Controller();
        //$sql_query = "insert into " . $this->sectionNameTable . " (user_id,profile,services,our_service,gallery,images,videos,clients,client_name,client_review,team,our_team,bank,payment) values('$id','Profile','Services','Our Services','Gallery','Images','Videos','Clients','Clients','Clients Reviews','Team','Our Team','Bank','Payment')";
        $query = "call mu_addMenuBar(?)";
        $type = "i";
        $params = array($id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
        return $result;
    }

    function addCustomUrl($user_id, $custom_url)
    {
        $controller = new Controller();
        //$query = "insert into " . $this->customUrlLogTable . " (user_id,custom_url,date) VALUES ('$user_id','" . $custom_url . "',NOW())";
        $query = "call mu_addCustomUrl(?,?)";
        $type = "is";
        $params = array($user_id, $custom_url);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
        return $status;
    }

    function getSectionDetails()
    {
        $controller = new Controller();
      //  $sql_query = "select * from " . $this->sectionTable;
        $query = "call mu_getSectionDetails()";
        $result = $controller->genericSelectToIterateUsingProcedure($query);
        return $result;
    }

    function insertDefaultUserSectionEntry($user_id, $section_id,$p_dg_status)
    {
        $controller = new Controller();
       // $query = "insert into " . $this->sectionStatusTable . " (user_id,section_id,website,digital_card) VALUES (" . $user_id . "," . $section_id . ",1,1)";
        $query = "call mu_insertDefaultUserSectionEntry(?,?,?)";
        $type = "iii";
        $params = array($user_id, $section_id,$p_dg_status);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
        return $status;
    }
   /* function displayAllUser($dealer_code)
    {
        $controller = new Controller();
        $query = "select * from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on pt.id=lt.user_id where pt.referer_code = '" . $dealer_code . "'";
        $status = $controller->genericSelectToIterate($query);
        return $status;
    }

    function countAllUserByDealerCode($dealer_code)
    {
        $controller = new Controller();
        $query = "select COUNT(pt.id) as user_count from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on pt.id=lt.user_id where pt.referer_code = '" . $dealer_code . "'";

        $status = $controller->genericSelectAlreadyIterated($query);
        if($status['user_count'] > 0){
            return $status['user_count'];
        }else{
            return 0;
        }
    }*/

    function displayAllActiveUser($dealer_code)
    {
        $controller = new Controller();
       /* if(isset($_SESSION['dealer_type']) && $_SESSION['dealer_type']=="dealer"){
            $query = "select pt.*,lt.email,lt.contact_no,lt.user_id,lt.password,ust.year,ust.end_date from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on pt.id=lt.user_id inner join " . $this->userSubscriptionTable . " as ust on ust.user_id=pt.id where pt.referer_code = '" . $dealer_code . "' and ust.year not in('Free Trail (15 days)','Free Trail (5 days)') and ust.active_plan=1 group by pt.id order by pt.id desc";
        }else{
            $query = "select pt.*,lt.email,lt.contact_no,lt.user_id,lt.password,ust.year,ust.end_date from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on pt.id=lt.user_id inner join " . $this->userSubscriptionTable . " as ust on ust.user_id=pt.id where pt.dealer_id = '" . $this->getUserSessionId() . "' and ust.year not in('Free Trail (15 days)','Free Trail (5 days)') and ust.active_plan=1 group by pt.id order by pt.id desc";
        }*/
        $sql = "call md_displayAllActiveUser(?,?,?)";
        $type = "ssi";
        $param = array($dealer_code,$_SESSION['dealer_type'],$this->getUserSessionId());

        $result = $controller->genericSelectToIterateUsingProcedure($sql,$type,$param);

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

            $result = $controller->genericInsertUpdateDelete($query);
            return $result;
        } else {
            return false;
        }
    }

    function countAllActiveUser($dealer_code)
    {
        $controller = new Controller();
       /* if(isset($_SESSION['dealer_type']) && $_SESSION['dealer_type']=="dealer"){
            $query = "select count(pt.id) as user_count from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on pt.id=lt.user_id inner join " . $this->userSubscriptionTable . " as ust on ust.user_id=pt.id where pt.referer_code = '" . $dealer_code . "' and ust.year not in('Free Trail (15 days)','Free Trail (5 days)') and ust.active_plan=1 group by pt.id";
        }else{
            $query = "select count(pt.id) as user_count from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on pt.id=lt.user_id inner join " . $this->userSubscriptionTable . " as ust on ust.user_id=pt.id where pt.dealer_id = '" . $this->getUserSessionId() . "' and ust.year not in('Free Trail (15 days)','Free Trail (5 days)') and ust.active_plan=1 group by pt.id";
        }*/
        $sql = "call md_countAllActiveUser(?,?,?)";
        $type = "ssi";
        $param = array($dealer_code,$_SESSION['dealer_type'],$this->getUserSessionId());
        $status = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        if($status['user_count'] > 0){
            return $status['user_count'];
        }else{
            return 0;
        }
    }



    function displayAllInActiveUser($dealer_code)
    {
        $controller = new Controller();
      /*  if(isset($_SESSION['dealer_type']) && $_SESSION['dealer_type']=="dealer"){
            $query = "select pt.*,lt.email,lt.contact_no,lt.user_id,lt.password,ust.year,ust.end_date from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on pt.id=lt.user_id left join " . $this->userSubscriptionTable . " as ust on ust.user_id=pt.id where ((ust.year in('Free Trail (15 days)','Free Trail (5 days)') and ust.active_plan !=0 and pt.referer_code = '" . $dealer_code . "') or (pt.expiry_date is null and pt.referer_code = '" . $dealer_code . "')) group by pt.id order by pt.id desc";
        }else{
            $query = "select pt.*,lt.email,lt.contact_no,lt.user_id,lt.password,ust.year,ust.end_date from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on pt.id=lt.user_id left join " . $this->userSubscriptionTable . " as ust on ust.user_id=pt.id where ((ust.year in('Free Trail (15 days)','Free Trail (5 days)') and ust.active_plan !=0 and pt.dealer_id = '" . $this->getUserSessionId() . "') or (pt.expiry_date is null and pt.dealer_id = '" . $this->getUserSessionId() . "')) group by pt.id order by pt.id desc";
        }*/
        $sql = "call md_displayAllInActiveUser(?,?,?)";
        $type = "ssi";
        $param = array($dealer_code,$_SESSION['dealer_type'],$this->getUserSessionId());
        $result = $controller->genericSelectToIterateUsingProcedure($sql,$type,$param);
        return $result;
    }

    function countAllInActiveUser($dealer_code)
    {
        $controller = new Controller();
      /*  if(isset($_SESSION['dealer_type']) && $_SESSION['dealer_type']=="dealer"){
            $query = "select COUNT(pt.id) as user_count from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on pt.id=lt.user_id left join " . $this->userSubscriptionTable . " as ust on ust.user_id=pt.id where ((ust.year in('Free Trail (15 days)','Free Trail (5 days)') and ust.active_plan !=0 and pt.referer_code = '" . $dealer_code . "') or (pt.expiry_date is null and pt.referer_code = '" . $dealer_code . "')) group by pt.id";
        }else{
            $query = "select COUNT(pt.id) as user_count from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on pt.id=lt.user_id left join " . $this->userSubscriptionTable . " as ust on ust.user_id=pt.id where ((ust.year in('Free Trail (15 days)','Free Trail (5 days)') and ust.active_plan !=0 and pt.dealer_id = '" . $this->getUserSessionId() . "') or (pt.expiry_date is null and pt.dealer_id = '" . $this->getUserSessionId() . "')) group by pt.id";
        }*/
        $sql = "call md_countAllInActiveUser(?,?,?)";
        $type = "ssi";
        $param = array($dealer_code,$_SESSION['dealer_type'],$this->getUserSessionId());
        $status = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        if($status['user_count'] > 0){
            return $status['user_count'];
        }else{
            return 0;
        }
    }

    function countAllCustomerByCode($dealer_code)
    {
        $controller = new Controller();
     /*   if(isset($_SESSION['dealer_type']) && $_SESSION['dealer_type']=="dealer"){
            $query = "select COUNT(id) as customer_count from " . $this->profileTable . " as pt where pt.referer_code = '" . $dealer_code . "'";
        }else{
            $query = "select COUNT(id) as customer_count from " . $this->profileTable . " as pt where pt.dealer_id = '" . $this->getUserSessionId() . "'";
        }*/

        $sql = "call md_countAllCustomerByCode(?,?,?)";
        $type = "ssi";
        $param = array($dealer_code,$_SESSION['dealer_type'],$this->getUserSessionId());
        $status = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        if($status['customer_count'] !=""){
            return $status['customer_count'];
        }else{
            return 0;
        }
    }

   /* function displayDealerProfileByAlreadyIterate($dealer_code)
    {
        $controller = new Controller();
        $query = "select * from " . $this->dealerProfileTable . " where dealer_code = '" . $dealer_code . "'";
        $status = $controller->genericSelectAlreadyIterated($query);
        return $status;
    }*/

    function displayDealerWalletAmount($dealer_code,$type)
    {
        $controller = new Controller();
     //   $query = "select sum(amount) as total_amount from " . $this->walletHistoryTable . "  where dealer_code = '" . $dealer_code . "' and payment_status in ('$type')";
        $sql = "call md_displayDealerWalletAmount(?,?)";
        $data_type = "ss";
        $param = array($dealer_code,$type);
        $status = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$data_type,$param);
        if($status['total_amount'] !=null){
            return $status['total_amount'];
        }else{
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
        $status = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$data_type,$param);
        if($status['total_amount'] !=null){
            return $status['total_amount'];
        }else{
            return 0;
        }

    }

   /* function displayDailyUser()
    {
        $controller = new Controller();
        $query = "select * from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on pt.id=lt.user_id where pt.referer_code = '" . $_SESSION['dealer_code'] . "' and pt.created_date=CURDATE()";
        $status = $controller->genericSelectToIterate($query);
        return $status;
    }

    function displayMonthlyUser()
    {
        $controller = new Controller();
        $query = "select * from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on pt.id=lt.user_id where referer_code = '" . $_SESSION['dealer_code'] . "' and MONTH(pt.created_date) = MONTH(CURRENT_DATE())";
        $status = $controller->genericSelectToIterate($query);
        return $status;
    }*/

    function displayDealerRelatedUser($date, $last_date)
    {
        $controller = new Controller();
      /*  if(isset($_SESSION['dealer_type']) && $_SESSION['dealer_type']=="dealer") {
            $sql_query = "select pt.*,lt.email,lt.contact_no,lt.user_id,lt.password,ust.year,ust.end_date from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on pt.id=lt.user_id inner join " . $this->userSubscriptionTable . " as ust on ust.user_id=pt.id where pt.expiry_date < '" . $last_date . "' and pt.referer_code = '" . $_SESSION['dealer_code'] . "' group by pt.id";
        }else{
            $sql_query = "select pt.*,lt.email,lt.contact_no,lt.user_id,lt.password,ust.year,ust.end_date from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on pt.id=lt.user_id inner join " . $this->userSubscriptionTable . " as ust on ust.user_id=pt.id where pt.expiry_date < '" . $last_date . "' and pt.dealer_id = '" . $this->getUserSessionId() . "' group by pt.id";
        }*/
        $sql = "call md_displayDealerRelatedUser(?,?,?,?,?)";
        $type = "ssssi";
        $param = array($date, $last_date,$_SESSION['dealer_code'],$_SESSION['dealer_type'],$this->getUserSessionId());

        $result = $controller->genericSelectToIterateUsingProcedure($sql,$type,$param);
        return $result;
    }

    function getUserData($id)
    {
        $controller = new Controller();
        // $sql_query = "select * from " . $this->loginTable . " as lt inner join " . $this->profileTable . " as pt on pt.id=lt.user_id where lt.user_id=" . $id;
        $sql = "call mu_getUserData(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }

   /* function successOfUserPayment($user_id)
    {
        $controller = new Controller();
        $query = "select * from " . $this->userSubscriptionTable . " where user_id=" . $user_id . " and start_date=CURDATE()";

        $result = $controller->genericSelectCount($query);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    function displayUserPayment($user_id, $status)
    {
        $controller = new Controller();
        $query = "select * from " . $this->userSubscriptionTable . " where user_id=" . $user_id . " and status='" . $status . "' and start_date=CURDATE()";
        $result = $controller->genericSelectAlreadyIterated($query);
        return $result;
    }

    function displayCustomer($referer_code)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $this->loginTable . " as lt inner join " . $this->profileTable . " as pt on pt.id=lt.user_id  where pt.referer_code='" . $referer_code . "'";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }*/

    function displayUserData($id)
    {
        $controller = new Controller();
        // $sql_query = "select * from " . $this->profileTable . " where id=" . $id;
        $sql = "call mu_displayUserData(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql);
        return $result;
    }

    function displayWallerHistoryByDealer($dealer_code)
    {
        $controller = new Controller();
        // $sql_query = "select * from " . $this->walletHistoryTable . " where dealer_code	='" . $dealer_code . "'";
        $sql = "call md_displayWallerHistoryByDealer(?)";
        $type = "s";
        $param = array($dealer_code);
        $result = $controller->genericSelectToIterateUsingProcedure($sql,$type,$param);
        return $result;
    }

    function displayWallerHistoryByDealerByPayStatus($dealer_code,$pay_status)
    {
        $controller = new Controller();
     //   $sql_query = "select * from " . $this->walletHistoryTable . " where dealer_code	='" . $dealer_code . "' and payment_status='$pay_status'";
        $sql = "call md_displayWallerHistoryByDealer(?,?)";
        $type = "ss";
        $param = array($dealer_code,$pay_status);
        $result = $controller->genericSelectToIterateUsingProcedure($sql,$type,$param);
        return $result;
    }

    function updateUserExpiryDate($id, $expiry_date)
    {
        $controller = new Controller();
     //   $sql_query = "update " . $this->profileTable . " set referer_code='" . $_SESSION['dealer_code'] . "',expiry_date='" . $expiry_date . "',update_user_count=1,user_start_date=CURDATE() where id=" . $id;
        $query = "call md_updateUserExpiryDate(?,?,?)";
        $type = "iss";
        $params = array($id,$expiry_date,$_SESSION['dealer_code']);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
        return $result;
    }

    function updateUserExpiryDateAfterSuccess($id, $expiry_date)
    {
        $controller = new Controller();
     //   $sql_query = "update " . $this->profileTable . " set referer_code='" . $_SESSION['dealer_code'] . "',expiry_date='" . $expiry_date . "',update_user_count=1,user_start_date=CURDATE() where id=" . $id;
        $query = "call md_updateUserExpiryDateAfterSuccess(?,?)";
        $type = "is";
        $params = array($id,$expiry_date);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
        return $result;
    }

  /*  function updateUserReference($id)
    {
        $controller = new Controller();
        $sql_query = "update " . $this->profileTable . " set referer_code='" . $_SESSION['dealer_code'] . "',update_user_count=1 where id=" . $id;
        $result = $controller->genericInsertUpdateDelete($sql_query);
        return $result;
    }*/

    function resetPasswordContact($password, $contact_no)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->dealerLoginTable . " set password=" . $password . " where contact_no ='" . $contact_no . "'";
        $query = "call md_resetPasswordContact(?,?)";
        $type = "ss";
        $params = array($password, $contact_no);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
        return $result;
    }
    function resetPasswordEmail($password, $contact_no)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->dealerLoginTable . " set password=" . $password . " where contact_no ='" . $contact_no . "'";
        $query = "call md_resetPasswordEmail(?,?)";
        $type = "ss";
        $params = array($password, $contact_no);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function get_selected_value($year)
    {
        $controller = new Controller();
       // $sql_query = "select * from " . $this->dealerPlanTable . " where year='" . $year . "'";
       // $sql = "call md_get_selected_value(?)";
        $sql = "call mu_get_selected_value(?)";
        $type = "s";
        $param = array($year);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }

  /*  function get_selected_amount($year)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $this->subscriptionPlanTable . " where year='" . $year . "'";
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }*/

    function getSpecificUserProfile($id)
    {
        $controller = new Controller();
       // $sql_query = "select * from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id = pt.id where pt.id=" . $id;
        $sql = "call mu_getSpecificUserProfileById(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;

    }

    function getLastInvoiceNumber($currency_type='INR')
    {
        $controller = new Controller();
        $sql = "call mu_getLastInvoiceNumber(?)";
        $type = "s";
        $param = array($currency_type);
        $get = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $get;
    }

    function updateUserPlanStatus($id)
    {
        $controller = new Controller();
       // $sql_query = "update " . $this->userSubscriptionTable . " set active_plan='0' where user_id=" . $id;
        $query = "call mu_updateUserPlanStatusById(?)";
        $type = "i";
        $params = array($id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
        return $result;
    }

    function updateWalletAmount($payment_status,$date,$payment_remark,$id)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->walletHistoryTable . " set payment_status='$payment_status',payment_date='$date',payment_remark='$payment_remark' where id=" . $id;
        $query = "call md_updateWalletAmount(?,?,?,?)";
        $type = "sssi";
        $params = array($payment_status,$date,$payment_remark,$id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
        return $result;
    }

    function getSpecificUserProfileById($id)
    {
        $controller = new Controller();
      //  $sql_query = "select * from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id = pt.id where pt.id='" . $id . "'";
        $sql = "call mu_getSpecificUserProfileById(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }

    function displaySubscriptionDetails($id)
    {
        $controller = new Controller();
        // $sql_query = "SELECT * from " . $this->userSubscriptionTable . " where user_id='" . $id . "' order by id desc";
        $sql = "call mu_displaySubscriptionDetails(?)";
        $type = "i";
        $params = array($id);
        $result = $controller->genericSelectToIterateUsingProcedure($sql,$type,$params);
        return $result;
    }

    function getUserInvoiceData($id)
    {
        $controller = new Controller();
      //  $sql_query = "SELECT * from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id=pt.id inner join " . $this->userSubscriptionTable . " as ust on ust.user_id = pt.id where ust.id=" . $id;
        $sql = "call mu_getUserInvoiceData(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }

  /*  function getDealerInvoiceData($id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * from " . $this->dealerProfileTable . " as dpt inner join " . $this->dealerLoginTable . " as dlt on dlt.user_id=dpt.id inner join " . $this->userSubscriptionTable . " as ust on ust.user_id = dpt.id where ust.id=" . $id;
        $sql = "call getDealerInvoiceData(?,?)";
        $type = "ss";
        $param = array($dealer_code, $invoice_no);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }*/

    function validateCustomUrl($url)
    {
        $result = false;
        $controller = new Controller();
//        $sql = "select * from " . $this->customUrlLogTable . " as clt inner join " . $this->profileTable . " as pt where clt.custom_url='" . $url . "' or pt.custom_url='" . $url . "'";
        $sql = "call mu_validateCustomUrl(?)";
        $type = "s";
        $param = array($url);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }

   /* function kubicAmountByDealer($dealer_code)
    {
        $controller = new Controller();
        $query = "select sum(total_amount) as kubicAmount from " . $this->userSubscriptionTable . " where referral_code = '" . $dealer_code . "' and status='success'";
        $get = $controller->genericSelectAlreadyIterated($query);
        return $get;
    }
    function displayServiceDetails($id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * from " . $this->serviceTable . " where user_id='" . $id. "' ORDER BY id DESC ";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }*/


    /*start*/
    function displayAmountOfDay()
    {
        $controller = new Controller();
        // $sql_query = "SELECT sum(total_amount) total_amount FROM " . $this->userSubscriptionTable . " WHERE start_date > DATE_SUB(NOW(), INTERVAL 1 DAY) and status='success'";
        $sql = "call md_displayAmountOfDay(?)";
        $type = "s";
        $param = array($_SESSION['dealer_code']);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }

    function displayAmountOfWeek()
    {
        $controller = new Controller();
        //$sql_query = "SELECT sum(total_amount) total_amount FROM " . $this->userSubscriptionTable . " WHERE start_date > DATE_SUB(NOW(), INTERVAL 1 WEEK) and status='success'";
        $sql = "call md_displayAmountOfWeek(?)";
        $type = "s";
        $param = array($_SESSION['dealer_code']);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }

    function displayAmountOfMonth()
    {
        $controller = new Controller();
        //$sql_query = "SELECT sum(total_amount) total_amount FROM " . $this->userSubscriptionTable . " WHERE start_date > DATE_SUB(NOW(), INTERVAL 1 MONTH) and status='success'";
        $sql = "call md_displayAmountOfMonth(?)";
        $type = "s";
        $param = array($_SESSION['dealer_code']);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }

    function displayAmountOfYear()
    {
        $controller = new Controller();
        //$sql_query = "SELECT sum(total_amount) total_amount FROM " . $this->userSubscriptionTable . " WHERE start_date > DATE_SUB(NOW(), INTERVAL 1 YEAR) and status='success'";
        $sql = "call md_displayAmountOfYear(?)";
        $type = "s";
        $param = array($_SESSION['dealer_code']);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }

    function displayAmountOfLifetime()
    {
        $controller = new Controller();
        // $sql_query = "SELECT sum(total_amount) total_amount FROM " . $this->userSubscriptionTable . " where status='success'";
        $sql = "call md_displayAmountOfLifetime(?)";
        $type = "s";
        $param = array($_SESSION['dealer_code']);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }
    function updateUserApprovedStatus($approved_status, $user_id,$pay_status)
    {
        $controller = new Controller();
     //   $sql_query = "update " . $this->dealerProfileTable . " set status=1,approve_status='" . $approved_status . "',pay_status='$pay_status' where id ='" . $user_id . "'";
        $sql = "call mu_updateUserApprovedStatus(?,?,?)";
        $type = "ssi";
        $param = array($approved_status,$pay_status,$user_id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($sql,$type,$param);
        return $result;
    }

    function validateDealerPayStatus()
    {
        $controller = new Controller();
     //   $sql_query = "update " . $this->dealerProfileTable . " set status=1,approve_status='" . $approved_status . "',pay_status='$pay_status' where id ='" . $user_id . "'";
        $sql = "call mu_validateDealerPayStatus(?)";
        $type = "s";
        $param = array($_SESSION['dealer_code']);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
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


    /*end*/

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
            $result = $controller->genericGetLastInsertedId($query);
            return $result;
        } else {
            return false;
        }
    }
    function updateDealerPercent($pricing_id)
    {
        $controller = new Controller();
        $sql = "call md_updateDealerPercent(?,?)";
        $type = "si";
        $param = array($pricing_id,$this->getUserSessionId());
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($sql,$type,$param);
        return $status;
    }
    function getDealerPricingById($id)
    {
        $controller = new Controller();
        $sql = "call md_getDealerPricingById(?)";
        $type = "i";
        $param = array($id);
        $status = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $status;
    }
    function md_displayInvoiceDetailsForDealer()
    {
        $controller = new Controller();
        // $sql_query = "SELECT ust.id,ust.year,ust.total_amount,ust.start_date,ust.end_date,ust.status,ust.invoice_no,ust.active_plan,pt.name,lt.email,lt.contact_no from " . $this->userSubscriptionTable . " as ust inner join " . $this->profileTable . " as pt on pt.id=ust.user_id inner join " . $this->loginTable . " as lt on lt.user_id = pt.id where ust.status='success' and lt.type!='Admin' and ust.year!='Free Trail (5 days)' order by ust.id desc";
        $sql = "call md_displayInvoiceDetailsForDealer(?)";

        $type = "s";
        $param = array($_SESSION['dealer_code']);
        $status = $controller->genericSelectToIterateUsingProcedure($sql,$type,$param);
        return $status;
    }
    function md_displayInvoiceDetailsForDealerForFilter($fromdate,$todate)
    {
        $controller = new Controller();
        // $sql_query = "SELECT ust.id,ust.year,ust.total_amount,ust.start_date,ust.end_date,ust.status,ust.invoice_no,ust.active_plan,pt.name,lt.email,lt.contact_no from " . $this->userSubscriptionTable . " as ust inner join " . $this->profileTable . " as pt on pt.id=ust.user_id inner join " . $this->loginTable . " as lt on lt.user_id = pt.id where ust.status='success' and lt.type!='Admin' and ust.year!='Free Trail (5 days)' order by ust.id desc";
        $sql = "call md_displayInvoiceDetailsForDealerForFilter(?,?,?)";
        $type = "sss";
        $param = array($_SESSION['dealer_code'],$fromdate,$todate);

        $status = $controller->genericSelectToIterateUsingProcedure($sql,$type,$param);
        return $status;
    }
    function deactivateUserAccount($user_id,$reason,$further,$account_status,$status)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->userSubscriptionTable . " set active_plan='0' where user_id=" . $this->getUserSessionId() . "";
        $query = "call mu_deactivateUserAccount(?,?,?,?,?)";
        //$query = "call mu_deactivateUserAccount(86,'$reason','$further','$status')";
        $type = "isssi";
        $params = array($user_id,$reason,$further,$account_status,$status);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
        return $result;
    }
    function UpdatePasswordUserAccount($update_user_id,$update_password, $update_user_email)
    {
        $controller = new Controller();
        $query = "call mu_UpdatePasswordUserAccount(?,?,?)";
        $type = "iss";
        $params = array($update_user_id,$update_password, $update_user_email);
        /*print_r($params);
        exit;*/
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    /*New Function For Upgrade Plan*/

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
    function insertMannualSubscriptionData($user_id, $year, $plan_amount, $taxable_amount, $sDate, $endDate, $status, $referral_code, $refrenced_by, $active_plan, $invoice_no, $discount, $tax, $total_amount, $payment_brand, $payment_mode, $bankId, $timestamp, $payment_type, $for_bill, $for_email, $user_gstno, $for_pan, $from_bill, $from_gstno, $from_pan, $sac_code)
    {
        $controller = new Controller();
        $query = "call mu_insertUserData(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $type = "ssssssssssssssssi";
        $procedure = "CALL mu_insertUserData('$year','$plan_amount', '$taxable_amount', '$sDate', '$endDate', '$status', '$referral_code', '$refrenced_by', '$active_plan', '$invoice_no', '$discount', '$tax', '$total_amount', '$payment_brand', '$payment_mode', '$bankId', '$timestamp','$payment_type','$for_bill','$for_email','$user_gstno','$for_pan','$from_bill','$from_gstno','$from_pan','$sac_code', '" . $user_id . "',@p_out_param)";
        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        $results2 = $mysqli->query("SELECT @p_out_param as last_id");
        $row = $results2->fetch_object();
        return $row->last_id;
    }







}