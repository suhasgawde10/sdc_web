<?php
session_start();
include("controller.php");

class ManageAdmin
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


    function displayAllUser()
    {
        $controller = new Controller();
        $sql_query = "SELECT * from " . $this->profileTable;
        /*echo $sql_query;
        die();*/
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }


}