<?php
@session_start();
include("controller.php");

class ManageWebsite
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

    public $logoTable = "tb_logo";



    function displayUserProfile($custom_url)
    {
        $controller = new Controller();
        $query = "SELECT * from " . $this->profileTable . " as pt inner join " . $this->loginTable. " as lt on lt.user_id=pt.id where custom_url='" . $custom_url . "'";
        /*  echo $query;
          die();*/
        $result = $controller->genericSelectAlreadyIterated($query);
        return $result;
    }

    function displayService($custom_url)
    {
        $controller = new Controller();
        $query = "SELECT tbs.service_name,tbs.description,tbs.img_name,lt.email FROM tb_services as tbs INNER JOIN tb_user_profile as tbf ON tbs.user_id=tbf.id INNER JOIN tb_login as lt on lt.user_id=tbf.id WHERE tbf.custom_url='" . $custom_url . "' and tbs.status=1";
        /*echo $query;
        die();*/
        $status = $controller->genericSelectToIterate($query);
        return $status;
    }

    function displayImage($custom_url)
    {
        $controller = new Controller();
        $query = "SELECT tbi.image_name,tbi.img_name,lt.email FROM tb_image as tbi INNER JOIN tb_user_profile AS tbf on tbf.id=tbi.user_id INNER JOIN tb_login as lt on lt.user_id=tbf.id WHERE tbf.custom_url='" . $custom_url . "' and tbi.status=1";
        $result = $controller->genericSelectToIterate($query);
        return $result;
    }

    function displayVideo($custom_url)
    {
        $controller = new Controller();
        $query = "select * from " . $this->videoTable . " as tbv inner join " . $this->profileTable . " as tbp on tbp.id=tbv.user_id INNER JOIN tb_login as lt on lt.user_id=tbp.id where tbp.custom_url='" . $custom_url . "' and tbv.status=1";
        /*echo $query;
        die();*/
        $result = $controller->genericSelectToIterate($query);
        return $result;
    }

    function displayClient($custom_url)
    {
        $controller = new Controller();
        $query = "SELECT tb_clients.name,tb_clients.img_name,lt.email FROM tb_clients INNER JOIN tb_user_profile ON tb_user_profile.id=tb_clients.user_id INNER JOIN tb_login as lt on lt.user_id=tb_user_profile.id where tb_user_profile.custom_url='" . $custom_url . "' and tb_clients.status=1";
        /*echo $query;
        die();*/
        $result = $controller->genericSelectToIterate($query);
        return $result;
    }

    function displayClientReview($custom_url)
    {
        $controller = new Controller();
        $query = "SELECT tb_client_review.name,tb_client_review.description,tb_client_review.img_name,lt.email FROM tb_client_review INNER JOIN tb_user_profile on tb_user_profile.id=tb_client_review.user_id INNER JOIN tb_login as lt on lt.user_id=tb_user_profile.id where tb_user_profile.custom_url='" . $custom_url . "' and tb_client_review.status=1";
        /*echo $query;
        die();*/
        $result = $controller->genericSelectToIterate($query);
        return $result;
    }

    function displayOurTeam($custom_url)
    {
        $controller = new Controller();
        $query = "SELECT tb_our_team.name,tb_our_team.designation,tb_our_team.img_name,lt.email FROM tb_our_team INNER JOIN tb_user_profile ON tb_our_team.user_id=tb_user_profile.id INNER JOIN tb_login as lt on lt.user_id=tb_user_profile.id where tb_user_profile.custom_url='" . $custom_url . "' and tb_our_team.status=1";
        /*echo $query;
        die();*/
        $result = $controller->genericSelectToIterate($query);
        return $result;
    }

    function displayBank($custom_url)
    {
        $controller = new Controller();
        $query = "SELECT tb_bank_details.name,tb_bank_details.bank_name,tb_bank_details.account_number,tb_bank_details.ifsc_code,tb_bank_details.branch FROM tb_bank_details INNER JOIN tb_user_profile ON tb_user_profile.id=tb_bank_details.user_id where tb_user_profile.custom_url='" . $custom_url . "' and tb_bank_details.status=1";
        /*echo $query;
        die();*/
        $result = $controller->genericSelectToIterate($query);
        return $result;
    }

    function displaySlider($custom_url)
    {
        $controller = new Controller();
        $query = "SELECT tb_image_slider.description,tb_image_slider.img_name,lt.email FROM tb_image_slider INNER JOIN tb_user_profile ON tb_user_profile.id=tb_image_slider.user_id INNER JOIN tb_login as lt on lt.user_id=tb_user_profile.id where tb_user_profile.custom_url='" . $custom_url . "' and tb_image_slider.status=1";
        /* echo $query;
         die();*/
        $result = $controller->genericSelectToIterate($query);
        return $result;
    }

    function displayAboutUs($custom_url)
    {
        $controller = new Controller();
        $query = "SELECT tb_about_us.description,tb_about_us.img_name,lt.email FROM tb_about_us INNER JOIN tb_user_profile ON tb_user_profile.id=tb_about_us.user_id INNER JOIN tb_login as lt on lt.user_id=tb_user_profile.id where tb_user_profile.custom_url='" . $custom_url . "' and tb_about_us.status=1";
        /* echo $query;
         die();*/
        $result = $controller->genericSelectAlreadyIterated($query);
        return $result;
    }
    function displayOnOffStatus($custom_url,$section_id){
        $controller = new Controller();
        $query = "select * from tb_section_status INNER JOIN tb_user_profile on tb_user_profile.id=tb_section_status.user_id where tb_user_profile.custom_url='" . $custom_url . "' and section_id=" . $section_id . "";
         /*echo $query;
         die();*/
        $result = $controller->genericSelectAlreadyIterated($query);
        return $result;
    }
    function selectTheme($custom_url)
    {
        $controller = new Controller();
        $query = "select * from tb_user_profile where tb_user_profile.custom_url='" . $custom_url . "'";
        /*echo $query;
        die();*/
        $result = $controller->genericSelectAlreadyIterated($query);
        return $result;
    }
    function displayLogo($custom_url)
    {
        $controller = new Controller();
        $query = "SELECT tb_logo.company_name,tb_logo.tag_line,tb_logo.img_name,lt.email FROM tb_logo INNER JOIN tb_user_profile on tb_user_profile.id=tb_logo.user_id INNER JOIN tb_login as lt on lt.user_id=tb_user_profile.id where tb_user_profile.custom_url='" . $custom_url . "'";
        /*echo $query;
        die();*/
        $result = $controller->genericSelectAlreadyIterated($query);
        return $result;
    }







}