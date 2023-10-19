<?php
@session_start();
include("controller.php");

class ManageMobileCard
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

    public $logTable = "tb_log_file";

    public $planTable = "tb_subscription_plan";

    public $userSubscriptionTable = "tb_user_subscription";

    public $customUrlLogTable = "tb_custom_log";

    public $mobileThemeTable = "tb_mobile_theme";

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

    function sendSMSWithTemplateId($contact, $message, $template_id)
    {
        $sendSmsUrl = "https://www.alots.in/sms-panel/api/http/index.php?username=Kubic&apikey=FEAB9-F45CF&apirequest=Text&sender=DGCARD&mobile=$contact&TemplateID=$template_id&route=TRANS&format=JSON&message=$message";
        $json = file_get_contents($sendSmsUrl);
        if (is_string($json)) {
            return true;
        } else {
            return false;
        }
    }
    
    function mdm_getDigitalCardDetails($data_type,$custom_url,$ser_type=0)
    {
        $controller = new Controller();
        $query = "call mdm_getDigitalCardDetails(?,?,?)";
        $type = "ssi";
        $param = array($data_type,$custom_url,$ser_type);
        /*$query = "SELECT * from " . $this->profileTable . " as pt inner join " . $this->loginTable. " as lt on lt.user_id=pt.id where custom_url='" . $custom_url . "'";*/
        $result = $controller->genericSelectToIterateUsingProcedure($query,$type,$param);
        return $result;
    }
    function mdm_getDigitalCardDetailsOFUser($custom_url)
    {
        $controller = new Controller();
        $query = "call mdm_getDigitalCardDetailsOFUser(?)";
        $type = "s";
        $param = array($custom_url);
        /*$query = "SELECT * from " . $this->profileTable . " as pt inner join " . $this->loginTable. " as lt on lt.user_id=pt.id where custom_url='" . $custom_url . "'";*/
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query,$type,$param);
        return $result;
    }

    function mdm_getCountryCode($id)
    {
        $controller = new Controller();
        $query = "call mdm_getCountryCode(?)";
        $type = "i";
        $param = array($id);
        /*$query = "SELECT * from " . $this->profileTable . " as pt inner join " . $this->loginTable. " as lt on lt.user_id=pt.id where custom_url='" . $custom_url . "'";*/
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query,$type,$param);
        return $result;
    }

    function displayService($custom_url)
    {
        $controller = new Controller();
        $query = "SELECT tbs.service_name,tbs.description,tbs.img_name,tbs.request_status,lt.email FROM tb_services as tbs INNER JOIN tb_user_profile as tbf ON tbs.user_id=tbf.id INNER JOIN tb_login as lt on lt.user_id=tbf.id WHERE tbf.custom_url='" . $custom_url . "' and tbs.status=1 order by tbs.position_order";
        /*echo $query;
        die();*/
        $status = $controller->genericSelectToIterate($query);
        return $status;
    }

    function displayImage($custom_url)
    {
        $controller = new Controller();
        $query = "SELECT tbi.image_name,tbi.img_name,lt.email FROM tb_image as tbi INNER JOIN tb_user_profile AS tbf on tbf.id=tbi.user_id INNER JOIN tb_login as lt on lt.user_id=tbf.id WHERE tbf.custom_url='" . $custom_url . "' and tbi.status=1 order by tbi.id desc ";
        $result = $controller->genericSelectToIterate($query);
        return $result;
    }

    function displayVideo($custom_url)
    {
        $controller = new Controller();
        $query = "select * from " . $this->videoTable . " as tbv inner join " . $this->profileTable . " as tbp on tbp.id=tbv.user_id INNER JOIN tb_login as lt on lt.user_id=tbp.id where tbp.custom_url='" . $custom_url . "' and tbv.status=1 order by tbv.id desc";
        /*echo $query;
        die();*/
        $result = $controller->genericSelectToIterate($query);
        return $result;
    }

    function displayClient($custom_url)
    {
        $controller = new Controller();
        $query = "SELECT tb_clients.name,tb_clients.img_name,lt.email FROM tb_clients INNER JOIN tb_user_profile ON tb_user_profile.id=tb_clients.user_id INNER JOIN tb_login as lt on lt.user_id=tb_user_profile.id where tb_user_profile.custom_url='" . $custom_url . "' and tb_clients.status=1 order by tb_clients.position_order";
        /*echo $query;
        die();*/
        $result = $controller->genericSelectToIterate($query);
        return $result;
    }

    function displayClientReview($custom_url)
    {
        $controller = new Controller();
        $query = "SELECT tb_client_review.name,tb_client_review.description,tb_client_review.img_name,lt.email FROM tb_client_review INNER JOIN tb_user_profile on tb_user_profile.id=tb_client_review.user_id INNER JOIN tb_login as lt on lt.user_id=tb_user_profile.id where tb_user_profile.custom_url='" . $custom_url . "' and tb_client_review.status=1 order by tb_client_review.id desc";
        /*echo $query;
        die();*/
        $result = $controller->genericSelectToIterate($query);
        return $result;
    }

    function displayOurTeam($custom_url)
    {
        $controller = new Controller();
        $query = "SELECT tb_our_team.name,tb_our_team.designation,tb_our_team.img_name,lt.email FROM tb_our_team INNER JOIN tb_user_profile ON tb_our_team.user_id=tb_user_profile.id INNER JOIN tb_login as lt on lt.user_id=tb_user_profile.id where tb_user_profile.custom_url='" . $custom_url . "' and tb_our_team.status=1 order by tb_our_team.position_order";
        /*echo $query;
        die();*/
        $result = $controller->genericSelectToIterate($query);
        return $result;
    }

    function displayBank($custom_url)
    {
        $controller = new Controller();
        $query = "SELECT tb_bank_details.name,tb_bank_details.bank_name,tb_bank_details.account_number,tb_bank_details.ifsc_code FROM tb_bank_details INNER JOIN tb_user_profile ON tb_user_profile.id=tb_bank_details.user_id where tb_user_profile.custom_url='" . $custom_url . "' and tb_bank_details.status=1 order by tb_bank_details.id desc";/* and tb_bank_details.default_bank=1*/
        /*echo $query;
        die();*/
        $result = $controller->genericSelectToIterate($query);
        return $result;
    }

    function displaySlider($custom_url)
    {
        $controller = new Controller();
        $query = "SELECT tb_image_slider.description,tb_image_slider.img_name,tb_user_profile.email FROM tb_image_slider INNER JOIN tb_user_profile ON tb_user_profile.id=tb_image_slider.user_id where tb_user_profile.custom_url='" . $custom_url . "' and tb_image_slider.status=1";
        /* echo $query;
         die();*/
        $result = $controller->genericSelectToIterate($query);
        return $result;
    }

    function displayAboutUs($custom_url)
    {
        $controller = new Controller();
        $query = "SELECT tb_about_us.description,tb_about_us.img_name,tb_user_profile.email FROM tb_about_us INNER JOIN tb_user_profile ON tb_user_profile.id=tb_about_us.user_id where tb_user_profile.custom_url='" . $custom_url . "' and tb_about_us.status=1";
        /*echo $query;
        die();*/
        $result = $controller->genericSelectAlreadyIterated($query);
        return $result;
    }


    function displayOnOffStatus($custom_url,$section_id){
        $controller = new Controller();
        // $query = "select * from tb_section_status INNER JOIN tb_user_profile on tb_user_profile.id=tb_section_status.user_id where tb_user_profile.custom_url='" . $custom_url . "' and section_id=" . $section_id . "";
        $query = "call mdm_displayOnOffStatus(?,?)";
        $type = "ss";
        $params = array($custom_url,$section_id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query,$type,$params);
        return $result;
    }

    function countUserdId($user_id, $page_type,$ip_address)
    {
        $controller = new Controller();
        // $query = "select * from " . $this->logTable . " where user_id =" . $user_id . " and page_type = '" . $page_type . "' and date=curdate()";
        $query = "call mdm_countUserId(?,?,?)";
        $type = "iss";
        $params = array($user_id, $page_type,$ip_address);
        $result = $controller->genericSelectCountUsingProcedure($query,$type,$params);
        return $result;
    }

    function insertUserCount($user_id, $page_type, $count,$ip_aadr,$city,$region,$countryName)
    {
        $controller = new Controller();
        //   $query = "Insert into " . $this->logTable . " (user_id,page_type,count,date,ip_addr,country,state,city) values ('$user_id','" . $page_type . "','$count',NOW(),'$ip_aadr','$countryName','$region','$city')";
        $query = "call mdm_insertUserCount(?,?,?,?,?,?,?)";
        $type = "isissss";
        $params = array($user_id, $page_type, $count,$ip_aadr,$city,$region,$countryName);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
        return $result;
    }

    function updateUserCount($count, $user_id, $page_type,$ip_aadr,$city,$region,$countryName)
    {
        $controller = new Controller();
        //  $query = "update " . $this->logTable . " set count=" . $count . ",ip_addr='$ip_aadr',country='$countryName',state='$region',city='$city' where user_id=" . $user_id . " and page_type = '" . $page_type . "' and date=curdate()";
       $query = "call mdm_insertUserCount(?,?,?,?,?,?,?)";
        $type = "isssssi";
        $params = array($count, $page_type,$ip_aadr,$city,$region,$countryName,$user_id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
        return $result;
    }

    function getUserId($user_id,$page_type)
    {
        $controller = new Controller();
        //  $query = "select * from " . $this->logTable . " where user_id = " . $user_id . " and page_type = '" . $page_type . "' and date=curdate()";
        $query = "call mdm_getUserId(?,?)";
        $type = "is";
        $params = array($user_id, $page_type);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query,$type,$params);
        return $result;
    }



    function getYearRelatedToId($card_id)
    {
        $controller = new Controller();
       // $query = "select * from " . $this->planTable . " where type=$card_id";
        $query = "call mdm_getYearRelatedToId(?)";
        $type = "s";
        $params = array($card_id);
        $getResult = $controller->genericSelectToIterateUsingProcedure($query,$type,$params);
        return $getResult;
    }

    /*function showPlanByYear()
    {
        $controller = new Controller();
        $query = "select * from " . $this->planTable;
        $getResult = $controller->genericSelectToIterate($query);
        return $getResult;
    }*/

   /* function insertUserData($user_id, $name, $contact, $type, $year, $amount, $sDate, $endDate)
    {
        $controller = new Controller();
        $query = "Insert into " . $this->userSubscriptionTable . " (user_id,name,contact,type,year,amount,start_date,end_date,status) values ('$user_id','" . $name . "'," . $contact . "," . $type . "," . $year . "," . $amount . ",'" . $sDate . "','" . $endDate . "',0)";
        $query = "call mdm_getYearRelatedToId(?)";
        $type = "i";
        $params = array($card_id);
        $result = $controller->genericInsertUpdateDelete($query);
        return $result;
    }*/

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

    function getAmountRelatedToId($card_id, $year)
    {
        $controller = new Controller();
       // $query = "select * from " . $this->planTable . " where type=$card_id and year='" . $year . "'";
        $query = "call mdm_getAmountRelatedToId(?,?)";
        $type = "ss";
        $params = array($card_id,$year);
        $getResult = $controller->genericSelectAlreadyIteratedUsingProcedure($query,$type,$params);
        return $getResult;
    }

    /*  function showPlan*/

    function getGatewayPaymentDetails($id)
    {
        $controller = new Controller();
        // $sql_query = "select * from " . $this->gatewayTable . " where user_id=" . $id . "";
        $query = "call mdm_getGatewayPaymentDetails(?)";
        $type = "i";
        $params = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query,$type,$params);
        return $result;
    }


    function validContactForCustomUrl($contact_no)
    {
        $result = false;
        $controller = new Controller();
      //  $sql = "select * from " . $this->loginTable . " as lt inner join " . $this->profileTable . " as pt on pt.id = lt.user_id where contact_no='" . $contact_no . "'";
        $query = "call mdm_validContactForCustomUrl(?)";
        $type = "s";
        $params = array($contact_no);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query,$type,$params);
        return $result;
    }


    function getUserSubscriptionDetails($id)
    {
        $controller = new Controller();
        //   $query = "select * from " . $this->userSubscriptionTable . " where user_id = '" . $id . "' and active_plan=1 order by id desc limit 1";
        $query = "call mdm_getUserSubscriptionDetails(?)";
        $type = "i";
        $params = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query,$type,$params);
        return $result;
    }

    function validateUserStatus($id)
    {
        $controller = new Controller();
        //$query = "select * from " . $this->profileTable . " where status=1 and id='$id'";
        $query = "call mdm_validateUserStatus(?)";
        $type = "i";
        $params = array($id);
        $result = $controller->genericSelectCountUsingProcedure($query,$type,$params);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    function validCustomUrl($custom_url)
    {
        $controller = new Controller();
        // $query = "select * from " . $this->profileTable . " where custom_url='" . $custom_url . "'";
        $query = "call mdm_validCustomUrl(?)";
        $type = "s";
        $params = array($custom_url);
        $result = $controller->genericSelectCountUsingProcedure($query,$type,$params);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }
    function validCustomUrlFromLog($custom_url)
    {
        $controller = new Controller();
        //  $query = "select * from " . $this->customUrlLogTable . " where custom_url='" . $custom_url . "'";
        $query = "call mdm_validCustomUrlFromLog(?)";
        $type = "s";
        $params = array($custom_url);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query,$type,$params);
        return $result;
    }

    function gettingCustomUrl($id)
    {
        $controller = new Controller();
        // $query = "select * from " . $this->profileTable . " where id='" . $id . "'";
        $query = "call mdm_gettingCustomUrl(?)";
        $type = "i";
        $params = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query,$type,$params);
        return $result;
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
            $result = $controller->genericGetLastInsertedId($query);
            return $result;
        } else {
            return false;
        }
    }


    function displayVerifiedUser($user_id)
    {
        $controller = new Controller();
        // $sql_query = "SELECT * from " . $this->userSubscriptionTable . " as ust where ust.user_id=" . $user_id . " and ust.active_plan=1 and ust.year!='Free Trail (5 days)'";
        $query = "call mdm_displayVerifiedUser(?)";
        $type = "i";
        $params = array($user_id);
        $result = $controller->genericSelectCountUsingProcedure($query,$type,$params);
        return $result;
    }


    function getSectionName($user_id)
    {
        $result = false;
        $controller = new Controller();
        // $sql = "select * from " . $this->sectionNameTable . " where user_id='" . $user_id . "'";
        $query = "call mdm_getSectionName(?)";
        $type = "i";
        $params = array($user_id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query,$type,$params);
        return $result;
    }
    function validThemeUserId($custom_url,$user_id)
    {
        $controller = new Controller();
        // $query = "select * from " . $this->profileTable . " where id='" . $user_id . "' and custom_url='" . $custom_url. "'";
        $query = "call mdm_validThemeUserId(?,?)";
        $type = "si";
        $params = array($custom_url,$user_id);

        $result = $controller->genericSelectCountUsingProcedure($query,$type,$params);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }
    function displayAllThemeImage($user_id)
    {
        $controller = new Controller();
        // $query = "SELECT * from " . $this->mobileThemeTable. "";
        $query = "call mdm_displayAllThemeImage(?)";
        $type = "i";
        $params = array($user_id);
        $result = $controller->genericSelectToIterateUsingProcedure($query,$type,$params);
        return $result;
    }
    function updateUserTheme($theme,$custom_url)
    {
        $controller = new Controller();
        // $query = "update " . $this->profileTable. " set user_theme='" . $theme. "' where custom_url = '" . $custom_url . "'";
        $query = "call mdm_updateUserTheme(?,?)";
        $type = "ss";
        $params = array($theme,$custom_url);

        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
        return $result;
    }
    function addTheme($user_id,$title, $img_name, $thumb_img)
    {
        $controller = new Controller();
        //$query = "insert into " . $this->mobileThemeTable . " (title,img_name,thumb_img,status) VALUES ('$title','$img_name','$thumb_img',1)";
        $query = "call mu_addTheme(?,?,?,?)";
        $type = "isss";
        $params = array($user_id,$title, $img_name, $thumb_img);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query,$type,$params);
        return $status;
    }
    function updateUserLeadCount($type,$id)
    {
        $controller = new Controller();
        // $query = "update " . $this->profileTable. " set user_theme='" . $theme. "' where custom_url = '" . $custom_url . "'";
        $query = "call mu_updateLeadCount(?,?)";
        $data_type = "si";
        $params = array($type,$id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query,$data_type,$params);
        return $result;
    }
    function getSpecificUserProfileById($id)
    {
        $controller = new Controller();
        // $sql_query = "select * from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id = pt.id where pt.id='" . $id . "'";
        $sql = "call mu_getSpecificUserProfile(?)";
        $type = "s";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }
    function getCoverImageOfUser($id)
    {
        $controller = new Controller();
        //$sql_query = "select * from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id = pt.id where pt.id=" . $this->getUserSessionId();
        $sql = "call mu_getCoverImageOfUser(?)";
        $type = "s";
        $param = array($id);
        $result = $controller->genericSelectToIterateUsingProcedure($sql,$type,$param);
        return $result;
    }
    function getTokenDetails($id,$token)
    {
        $controller = new Controller();
        //$sql_query = "select * from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id = pt.id where pt.id=" . $this->getUserSessionId();
        $sql = "call mu_getTokenDetails(?,?)";
        $type = "is";
        $param = array($id,$token);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        if($result !=null){
            return true;
        }else{
            return false;
        }
    }
    function mdm_getReviewDetailsByFilter($custom_url,$data_type)
    {
        $controller = new Controller();
        $query = "call mdm_getReviewDetailsByFilter(?,?)";
        $type = "ss";
        $param = array($custom_url,$data_type);
        /*$query = "SELECT * from " . $this->profileTable . " as pt inner join " . $this->loginTable. " as lt on lt.user_id=pt.id where custom_url='" . $custom_url . "'";*/
        $result = $controller->genericSelectToIterateUsingProcedure($query,$type,$param);
        return $result;
    }
    function getTotalReviews($user_id)
    {
        $controller = new Controller();
        $query = "call mdm_getTotalReviews(?)";
        $type = "i";
        $param = array($user_id);
        /*$query = "SELECT * from " . $this->profileTable . " as pt inner join " . $this->loginTable. " as lt on lt.user_id=pt.id where custom_url='" . $custom_url . "'";*/
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query,$type,$param);
        return $result;
    }

    function mu_insertPrivateLinkToken($id,$token_number,$expiry_date)
    {
        $controller = new Controller();
        $sql = "call mu_insertPrivateLinkToken(?,?,?)";
        $type = "iss";
        $param = array($id,$token_number,$expiry_date);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($sql,$type,$param);
        return $status;
    }
        function countPayPal($user_id)
    {
        $controller = new Controller();
        //  $query = "select * from " . $this->gatewayTable . " where user_id=" . $user_id;
        $query = "call mu_countPayPal(?)";
        $type = "i";
        $params = array($user_id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query,$type,$params);
        if($result !=null){
            return $result;
        }else{
            return false;
        }
    }

    function mdm_displaySectionTheme($user_id,$section_id){
        $controller = new Controller();
        // $query = "select * from tb_section_status INNER JOIN tb_user_profile on tb_user_profile.id=tb_section_status.user_id where tb_user_profile.custom_url='" . $custom_url . "' and section_id=" . $section_id . "";
        $query = "call mdm_displaySectionTheme(?,?)";
        $type = "ii";
        $params = array($user_id,$section_id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query,$type,$params);
        return $result;
    }
    function mdm_displayVideoCount($user_id)
    {
        $status = 1;
        $controller = new Controller();
        //  $query = "select * from " . $this->gatewayTable . " where user_id=" . $user_id;
        $query = "call mdm_displayVideoCount(?,?)";
        $type = "ii";
        $params = array($user_id,$status);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query,$type,$params);
        if($result['rowNum'] !=null){
            return $result['rowNum'];
        }else{
            return 0;
        }

    }

    function mu_displayVideoDetailsByLimit($user_id,$start,$end)
    {
        $status = 1;
        $controller = new Controller();
        //  $query = "select * from " . $this->gatewayTable . " where user_id=" . $user_id;

        $query = "call mu_displayVideoDetailsByLimit(?,?,?,?)";
        $type = "iiii";
        $params = array($user_id,$start,$end,$status);
        $result = $controller->genericSelectToIterateUsingProcedure($query,$type,$params);
        return $result;

    }

    function mdm_displayGalleryCount($user_id)
    {
        $controller = new Controller();
        //  $query = "select * from " . $this->gatewayTable . " where user_id=" . $user_id;
        $query = "call mdm_displayGalleryCount(?)";
        $type = "i";
        $params = array($user_id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query,$type,$params);
        if($result['rowNum'] !=null){
            return $result['rowNum'];
        }else{
            return 0;
        }

    }

    function mu_displayGalleryDetailsByLimit($user_id,$start,$end)
    {
        $controller = new Controller();
        //  $query = "select * from " . $this->gatewayTable . " where user_id=" . $user_id;
        $query = "call mu_displayGalleryDetailsByLimit(?,?,?)";
        $type = "iii";
        $params = array($user_id,$start,$end);
        $result = $controller->genericSelectToIterateUsingProcedure($query,$type,$params);
        return $result;

    }
    function getDealerProfile($dealer_code)
    {
        $controller = new Controller();
        //$sql_query = "select * from " . $this->dealerProfileTable . " as dpt inner join " . $this->dealerLoginTable . " as dlt on dpt.id=dlt.user_id where dpt.dealer_code='" . $dealer_code . "'";
        $sql = "call mu_getDealerProfile(?)";
        $type = "s";
        $param = array($dealer_code);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $result;
    }
    function mdm_totalPageCount($user_id){
        $controller = new Controller();
        // $query = "select * from tb_section_status INNER JOIN tb_user_profile on tb_user_profile.id=tb_section_status.user_id where tb_user_profile.custom_url='" . $custom_url . "' and section_id=" . $section_id . "";
        $query = "call mdm_totalPageCount(?)";
        $type = "i";
        $params = array($user_id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query,$type,$params);
        return $result;
    }
    function getAllBusinessLinksById($id)
    {
        $controller = new Controller();
        //$query = "insert into " . $this->customUrlLogTable . " (user_id,custom_url,date) VALUES ('$user_id','" . $custom_url . "',NOW())";
        $query = "call mdm_getAllBusinessLinksByIdIterate(?)";
        $type = "i";
        $params = array($id);
        $status = $controller->genericSelectToIterateUsingProcedure($query,$type,$params);
        return $status;
    }

function getSectionStatus($user_id,$section_id)
    {
        $controller = new Controller();
        $results = false;
        $sql_query = "SELECT * FROM `tb_section_status` WHERE user_id = $user_id and section_id = $section_id and website IN(1) AND digital_card IN(1)";
        $result = $controller->genericSelectCount($sql_query);
        if($result > 0){
            $results = true;
        }else{
            $results = false;
        }
        return $results;
    }

    function getSectionImageDataByCustom($user_id,$section_id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM `tb_section_icon` WHERE `user_id` = $user_id AND `section_id` = $section_id";
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }
    function getSectionStatusCountUnhide($user_id)
    {
        $controller = new Controller();
        $sql_query = "SELECT COUNT(user_id) as totalShow FROM `tb_section_status` WHERE user_id = $user_id AND section_id IN(10,1,11,2,4,6,7) AND website IN(1) and digital_card IN(1)";
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

}