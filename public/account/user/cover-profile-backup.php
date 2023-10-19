<?php
ob_start();
error_reporting(0);
ini_set('memory_limit', '-1');
date_default_timezone_set("Asia/Kolkata");
include_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../controller/ManageUser.php";
$controller = new Controller();
$con = $controller->connect();
header('Content-Type: text/html; charset=utf-8');

$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';
include("android-login.php");
$maxsize = 4194304;
include_once('lib/ImgCompressor.class.php');
$error = false;
$errorMessage = "";
$errorFile = false;
$errorMessageFile = "";
include("session_includes.php");



$userSpecificResult = $manage->selectTheme();
if ($userSpecificResult != null) {
    $expiry_date = $userSpecificResult['expiry_date'];
    $get_email_count = $userSpecificResult['email_count'];
    $country= $userSpecificResult['country'];
}

if ($expiry_date != "") {
    include "validate-page.php";
    $notification_token = $userSpecificResult['user_notification'];
}else{
    $notification_token  = "";
}


$userSpecificResult = $manage->displayUserSubscriptionDetails();

if($userSpecificResult!=null){
    $expiry_date = $userSpecificResult['expiry_date'];
    $plan_name = $userSpecificResult['year'];
    $referral_by = $userSpecificResult['referer_code'];
    $sell_ref = $userSpecificResult['sell_ref'];
    if($sell_ref == ""){
        $sell_ref = "dealer_link";
    }
}else{
    $plan_name = "trial";
}


$active_tab = false;


$imgUploadStatus = false;
$fileUploadStatus = false;
/*This method used for update the Branch data*/

if ($id != 0) {
    $form_data = $manage->getSpecificUserProfile();
    if ($form_data != null) {
        $name = $form_data['name'];
        $designation = $form_data['designation'];
        $gender = $form_data['gender'];

        $profilePath = "uploads/" . $session_email . "/profile/" . $form_data['img_name'];
    }
}


if(isset($_POST['btn_update_cover'])){
    if (isset($_FILES['upload']) && $_FILES['upload']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $imgUploadStatus = true;
        $directory_name = "uploads/" . $session_email . "/profile/";
        $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG','.gif','.GIF');
        $total = count($_FILES['upload']['name']);
        for ($i = 0; $i < $total; $i++) {
            $filename = $_FILES['upload']['name'][$i];
            list($width, $height, $type, $attr) = getimagesize($_FILES['upload']['tmp_name'][$i]);
            $extensionStatus = $validate->validateFileExtension($filename, $extension);
            if (!$extensionStatus) {
                $error = true;
                $errorMessage .= "Please select valid file extension";
            }
            if (($_FILES['upload']['size'][$i] >= $maxsize)) {
                $error = true;
                $errorMessage .= 'File too large. File must be less than 4 megabytes.';
            }

            if($height > $width){
                $error = true;
                $errorMessage .= 'Please select landscape image only.';
            }
        }
    }

    if (!$error) {
        $digits = 4;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newfilename = "";
        if ($imgUploadStatus) {
            for ($i = 0; $i < $total; $i++) {
                /*    $tmpFilePath1 = $_FILES['upload']['tmp_name'][$i];
                    $setting = array(
                        'directory' => $directory_name, // directory file compressed output
                        'file_type' => array( // file format allowed
                            'image/jpeg',
                            'image/png',
                            'image/gif'
                        )
                    );
                    $ImgCompressor = new ImgCompressor($setting);
                    $result = $ImgCompressor->run($tmpFilePath1, 'jpg', 5);
                    $key = json_encode($result);
                    $decode = json_decode($key);
                    $value = 'status';
                    $fileStatus = $decode->$value;
                    if ($fileStatus == "success") {
                        $data = "data";
                        $compressed = "compressed";
                        $img_name = "name";
                        if($cover_name !=''){
                            $cover_name .= ",".$decode->$data->$compressed->$img_name;
                        }else{
                            $cover_name .= $decode->$data->$compressed->$img_name;
                        }

                    } else {
                        $error .= true;
                        $errorMessage .= "Issue while uploading\nNote: File too large. File must be less than 4 megabytes";
                    }
                }*/
                $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                $file_original_name = substr($_FILES['upload']['name'][$i], 0, strrpos($_FILES['upload']['name'][$i], '.'));
                $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                $newimgname = $file_original_name . "$" . $randomNum . '.' . $file_extension;
                $newimgname = str_replace([' ','_'],'-',$newimgname);
                // Compress Image
                $newPath = $directory_name . $newimgname;
                if($file_extension == "gif" or $file_extension == "GIF"){
                    $upload = move_uploaded_file($tmpFilePath,$newPath);
                }else{
                    $upload = compressImage($tmpFilePath, $newPath, 60);
                }
                if (!$upload) {
                    $error = true;
                    $errorMessage = "Issue while uploading\nNote: File too large. File must be less than 4 megabytes";
                }else{
                    if(!$error){
                        $status = $manage->insertCoverPhoto($newimgname);
                        $page_name =$_SESSION['menu']['s_profile'];
                        $action = "Add";
                        if(isset($_SESSION['dealer_login_type'])){
                            $by = "by dealer.";
                        }else{
                            $by = "by user.";
                        }
                        $remark = "Cover image has been added ".$by;
                        $insertLog = $manage->insertUserLogData($page_name,$action,$remark);
                        if (!$status) {

                            $error = true;
                            $errorMessage = "Issue while insert cover profile, Please try again.";
                        }
                    }
                }
            }
        }

        if ($id != 0) {
            $form_data = $manage->getSpecificUserProfile();
            if ($form_data != null) {
                $name = $form_data['name'];
                $designation = $form_data['designation'];
                $gender = $form_data['gender'];

                $profilePath = "uploads/" . $session_email . "/profile/" . $form_data['img_name'];

            }
        }

    }
}

$five_day = date('Y-m-d', strtotime(date_create("Y-m-d") . ' + 5 days'));

if (isset($_GET['img_name']) && (isset($_GET['delete_id']))) {
    $cover_id = $security->decryptWebservice($_GET['delete_id']);
    $ke_img_name = $security->decryptWebservice($_GET['img_name']);
    $imagePath = "uploads/' . $session_email . '/profile/" . $ke_img_name;
    if (file_exists($imagePath)) {
        unlink('uploads/' . $session_email . '/profile/' . $ke_img_name);
    }
    $update = $manage->deleteCoverPhoto($cover_id);
    $page_name = $_SESSION['menu']['s_profile'];
    $action = "delete";
    if(isset($_SESSION['dealer_login_type'])){
        $by = "by dealer.";
    }else{
        $by = "by user.";
    }
    $remark = "Cover image has been deleted ".$by;
    $insertLog = $manage->insertUserLogData($page_name,$action,$remark);
    header('location:cover-profile.php');
}


?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include "assets/common-includes/total_count.php" ?>
    <title>Cover Profile</title>
    <!-- Google Fonts -->
   <?php include "assets/common-includes/header_includes.php"; ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <style>
        .content{
            padding: 5px;
            margin: 0 auto;
        }
        .content span{
            width: 250px;
        }

        .dz-message{
            text-align: center;
            font-size: 28px;
        }
        .profile-left-ul li {
            overflow: unset;
        }
        table tr{
            width: 33%;
            display: inline-block;
            cursor: move;
        }
    </style>
    <link rel="stylesheet" href="croppie.css" />
    <script src="croppie.js"></script>
</head>
<body>
<?php
if (!isset($_GET['android_user_id']) && (!isset($_GET['type']) && $_GET['type'] != "android") && (!isset($_GET['api_key']))) {
?>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>

<section class="content">
    <?php
    if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
        include "assets/common-includes/session_button_includes.php";
        echo "<br>";
    }
    ?>
    <?php

    if ($get_email_count == "1")
        include "assets/common-includes/preview.php" ?>
    <?php
    }elseif (isset($_GET['android_user_id']) && (isset($_GET['type']) && $_GET['type'] == "android") && (isset($_GET['api_key']))) {
    ?>
    <section class="androidSection">
        <?php
        }
        ?>
        <div class="clearfix padding_bottom_46">
            <div class="">
                <a href="basic-user-info.php" style="margin-bottom: 10px;" type="button" class="btn btn-primary"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
            </div>
            <div id="load_div">
                <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12 padding_zero_both">
                    <div class="row margin_div1">
                        <div class="card">
                            <div class="body card_padding">

                                <div id="uploaded_cover_image" class="cover_image contact-icon-btm1">
                                    <?php

                                    $get_cover_data = $manage->getCoverImageOfUser();
                                    if($get_cover_data !=null) {
                                        $coverCount = mysqli_num_rows($get_cover_data);
                                    }else{
                                        $coverCount = 0;
                                    }
                                    function fetch_all_data($result)
                                    {
                                        $all = array();
                                        while($thing = mysqli_fetch_array($result)) {
                                            $all[] = $thing;
                                        }
                                        return $all;
                                    }

                                    if($get_cover_data !=null) {
                                        $cover_first_img = fetch_all_data($get_cover_data);
                                        foreach ($cover_first_img as $k){
                                            $cover_first_img_path ="uploads/" . $session_email . "/profile/".$k['cover_pic'];
                                            break;
                                        }

                                    }
                                    if ($get_cover_data == null) {
                                        echo "<img src='uploads/admin_background.jpg' style='height: 100%'>";
                                    }elseif (file_exists($cover_first_img_path) && $coverCount == 1){
                                        echo '<img src="' . $cover_first_img_path . '">';
                                    }elseif ($coverCount > 1){
                                        ?>
                                        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                                            <!-- Indicators -->
                                            <!-- <ol class="carousel-indicators">
                                            <?php
                                            /*                                            $i = 1;
                                                                                        foreach ($key_data as $key) {
                                                                                            */?>
                                                <li data-target="#carousel-example-generic" data-slide-to="0" <?php /*if($i == 1) echo 'class="active"'; */?>></li>
                                                <?php
                                            /*                                            $i++;
                                                                                        }
                                                                                        */?>
                                        </ol>-->

                                            <!-- Wrapper for slides -->
                                            <div class="carousel-inner" role="listbox">
                                                <?php

                                                foreach ($cover_first_img as $key) {
                                                    $path = "uploads/" . $session_email . "/profile/" . $key['cover_pic'];
                                                    if (file_exists($path) && $key !="") {
                                                        ?>
                                                        <div class="item <?php if ($i == 1) echo 'active'; ?>">
                                                            <?php
                                                            echo '<img src="' . $path . '" />';
                                                            ?>
                                                        </div>
                                                        <?php
                                                        $i++;
                                                    }
                                                }
                                                ?>
                                            </div>

                                            <!-- Controls -->
                                            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </div>
                                        <?php
                                    } else {
                                        echo "<img src='uploads/admin_background.jpg' style='height: 100%'>";
                                    } ?>

                                </div>
                                <ul class="profile-left-ul">

                                    <li class="profile-pm-0">
                                        <div class="form-float text-align-profile" style="position: relative">
                                            <div id="uploaded_image">
                                                <img
                                                        src="<?php if (!file_exists($profilePath) && $gender == "Male" or $form_data['img_name'] == "") {
                                                            echo "uploads/male_user.png";
                                                        } elseif (!file_exists($profilePath) && $gender == "Female" or $form_data['img_name'] == "") {
                                                            echo "uploads/female_user.png";
                                                        } else {
                                                            echo $profilePath;
                                                        } ?>" class="profile_image">

                                            </div>


                                            <!--<div class="contact-icon-btm">
                                                <input type="file" name="upload_image" id="upload_image"
                                                       accept="image/*"/>
                                                <a id="OpenImgUpload">
                                                    <div class="p-align"><i class="fas fa-camera"></i></div>
                                                </a>
                                            </div>-->
                                        </div>
                                    </li>
                                    <li class="text-center">
                                        <div class="width-prf">
                                            <label class="form-label"></label>

                                            <div class="form-group form-group-left form-float">
                                                <div class=""><!-- <i class="fas fa-user"></i>  -->
                                                    <h4><b><?php if (isset($name)) echo $name; ?></b></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>

                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12 padding_zero_both">

                <div class="row margin_div_web">

                    <div class="col-md-12">

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


                        <div class="col-md-3">
                            <div class="card">
                                <div class="body text-center">
                                    <form method="post" enctype="multipart/form-data" action="">
                                        <input type="file" name="upload[]" id="upload_image"
                                               accept="image/*" multiple/>
                                        <a class="cover-upload" ><i class="fas fa-cloud-upload-alt"></i><br>Upload</a>
                                        <button type="submit" name="btn_update_cover" style="display: none"></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php

                            if($get_cover_data !=null) {
                                ?>
                                <table class="table">
                                    <tbody class="row_position">
                                    <?php
                                    foreach ($cover_first_img as $key_data) {
                                        $path = "uploads/" . $session_email . "/profile/" . $key_data['cover_pic'];

                                        if ($key_data['cover_pic'] != "") {
                                            ?>

                                            <tr id="<?php echo $security->encryptWebservice($key_data['id']); ?>">
                                                <td>

                                                    <div class="card "
                                                         style="height: 100px;overflow: hidden;">
                                                        <div class="col-md-12 text-right contact-icon-btm1">
                                                            <a class="cover-anchor"
                                                               onclick="return confirm('Are You sure you want to remove cover photo?');"
                                                               href="cover-profile.php?delete_id=<?php echo $security->encryptWebservice($key_data['id']); ?>&img_name=<?php echo $security->encryptWebservice($key_data['cover_pic']); ?>"><i
                                                                    class='fas fa-trash-alt color_red'></i></a><br>
                                                            <?php
                                                            echo '<a href="' . $path . '" target="_blank" style="cursor:move"><img src="' . $path . '" style="width:100%"></a>';
                                                            ?>
                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }

                                    ?>

                                    </tbody>

                                </table>
                                <?php
                            }
                        ?>
                    </div>
                </div>
            </div>


            <!-- Footer -->

            <!-- #Footer -->
        </div>


    </section>

    <div class="modal fade" id="edit_cover_pic">
        <div class="modal-dialog cover_dialog_width">
            <div class="modal-content">
                <div class="modal-header header_bottom">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><b>Upload cover image</span></b></h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 text-center">
                        <div id="image_demo" style="width:350px; margin-top:30px"></div>
                    </div>
                </div>
                <div class="modal-footer footer_bottom">
                    <button class="btn btn-success crop_cover_image"><i
                            class="fas fa-check"></i>
                        &nbsp;Upload Image
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--<script src="https://cdn.rawgit.com/t4t5/sweetalert/v0.2.0/lib/sweet-alert.min.js"></script>-->
    <?php include "assets/common-includes/footer_includes.php" ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
    var el = document.getElementById('vanilla-demo');
    var vanilla = new Croppie(el, {
        viewport: { width: 100, height: 100 },
        boundary: { width: 300, height: 300 },
        showZoomer: false,
        enableOrientation: true
    });
    vanilla.bind({
        url: 'demo/demo-2.jpg',
        orientation: 4
    });
    //on button click
    vanilla.result('blob').then(function(blob) {
        // do something with cropped blob
    });
</script>
    <script type="text/javascript">
        function loadData(){
            $("#load_div").load("cover-profile.php #load_div");
        }
        $( ".row_position" ).sortable({
            delay: 150,
            stop: function() {
                var selectedData = new Array();
                $('.row_position>tr').each(function() {
                    selectedData.push($(this).attr("id"));
                });
                updateOrder(selectedData);
            }
        });


        function updateOrder(data) {
            $.ajax({
                url:"changePosition.php",
                type:'post',
                data:{cover_profile:data},
                success:function(data){

                }
            })
        }
    </script>

    <script>
        $(document).ready(function(){
            $('button[name=btn_update_cover]').hide();
            $(document).on('change', '#cover_image', function(){
                $('button[name=btn_update_cover]')[0].click();
            });
        });
    </script>


    <!-- verify close -->
    <script>
        function close_verify_modal(){
            $('#overlay').hide();
            $('.verify_number_div').hide();
        }

    </script>

    <!-- end  -->
    <script>
        $('.cover-upload').click(function () {
            $('#upload_image').trigger('click');
        });
    </script>

    <!--<script type="text/javascript">
        document.getElementById("b3").onclick = function () {
            swal("Good job!", "You clicked the button!", "success");
        };
    </script>-->


</body>
</html>