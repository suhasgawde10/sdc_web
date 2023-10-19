<?php
ob_start();
ini_set('memory_limit', '-1');
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();

$controller = new Controller();
$con = $controller->connect();
if(isset($_GET['token']) && $_GET['token']!=""){
    $token = explode(',',$security->decryptWebservice($_GET['token']));
    // $get_data = $manage->displayUserReview($token[0],$token[1]);
    $form_data = $manage->getUserProfile($token[0]);
    $custom_url = $form_data['custom_url'];
    header('location:https:../m/testimonial.php?custom_url=' . $custom_url."&feedback=true");

}
$section_id = 5;
$alreadySaved = false;

$alreadySavedVideo = false;
$section_video_id = 4;

$maxsize = 4194304;
include_once('lib/ImgCompressor.class.php');


$error = false;
$errorMessage = "";


$imgUploadStatus = false;
/*This method used for update the Branch data*/


if (isset($_POST['btn_save_client'])) {
    if (isset($_POST['txt_name']) && $_POST['txt_name'] != "") {
        $name = mysqli_real_escape_string($con, $_POST['txt_name']);
    } else {
        $error = true;
        $errorMessage .= "Please enter name.<br>";
    }
    if (isset($_POST['txt_des']) && $_POST['txt_des'] != "") {
        $description = mysqli_real_escape_string($con, $_POST['txt_des']);
    } else {
        $error = true;
        $errorMessage .= "Please enter review.<br>";
    }
    /*Start of pdf upload*/
    /*echo $_FILES['upload']['error'][0];
        die();*/
    if (isset($_FILES['upload']) && $_FILES['upload']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $imgUploadStatus = true;
        $directory_name = "uploads/" . $user_email . "/testimonials/client_review/";
        $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
        $total = count($_FILES['upload']['name']);
        for ($i = 0; $i < $total; $i++) {
            $filename = $_FILES['upload']['name'][$i];
            $extensionStatus = $validate->validateFileExtension($filename, $extension);
            if (!$extensionStatus) {
                $error = true;
                $errorMessage = "Please select valid file extension";
            }
        }
    }
    if (!$error) {
        $cover_name = "";
        $digits = 4;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newfilename = "";
        if ($imgUploadStatus) {
            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath1 = $_FILES['upload']['tmp_name'][$i];
                $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                $file_original_name = substr($_FILES['upload']['name'][$i], 0, strrpos($_FILES['upload']['name'][$i], '.'));
                $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                $newimgname = $file_original_name . "$" . $randomNum . '.' . $file_extension;
                $cover_name = str_replace([' ', '_'], '-', $newimgname);
                $newPath = $directory_name . $cover_name;
                // Compress Image
                $upload = compressImage($tmpFilePath, $newPath, 60);
                if (!$upload) {
                    $error = true;
                    $errorMessage = "Issue while uploading\nNote: File too large. File must be less than 4 megabytes";
                }
            }
        }


        if(!$error){
            $status = $manage->addClientsReviewWithoutSession($token[0], $name, $description, $cover_name);
            if ($status) {
                $_SESSION['cust_name'] = $name;
                $_SESSION['reviewd'] = "success";
                $description = $name = "";
            } else {
                $error = true;
                $errorMessage = "Issue while submitting feedback, Please try again.";
            }
        }
    }

}

?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title><?php echo $title; ?> Requesting for Your Feedback - Share Feedback</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <link rel="stylesheet" type="text/css" href="assets/css/component.css"/>
    <!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- remove this if you use Modernizr -->
    <script>(function (e, t, n) {
            var r = e.querySelectorAll("html")[0];
            r.className = r.className.replace(/(^|\s)no-js(\s|$)/, "$1js$2")
        })(document, window, 0);</script>

    <style>
        section.content {
            margin: 29px 15px 0 259px;
        }
        @media only screen and (max-width: 767px) {
            h2{
                margin-top: 0;
            }
        }
        .morecontent span {
            display: none;
        }
        .morelink {
            display: block;
        }
        .form-control{
            display: block;
            width: 100% !important;
            height: 34px;
            padding: 6px 12px !important;
            line-height: 1.42857143;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc !important;
            border-radius: 4px !important;
            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075) !important;
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075) !important;
            -webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s !important;
            -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s !important;
            -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s !important;
            transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s !important;
            transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s !important;
            transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s,-webkit-box-shadow ease-in-out .15s !important;
        }
    </style>

</head>
<body style="background-image: url(assets/images/feedback-back.jpg);">
<?php
if (!isset($_GET['android_user_id']) && (!isset($_GET['type'])) && (!isset($_GET['api_key']))) {
?>

<section class="content">
    <?php
    }elseif (isset($_GET['android_user_id']) && (isset($_GET['type']) && $_GET['type'] == "android") && (isset($_GET['api_key']))) {
    ?>
    <section class="androidSection">
        <?php
        }
        ?>
        <div class="col-lg-10 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 padding_zero padding_zero_both">
            <div class="body">
                <!-- Nav tabs -->

                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding_zero margin_div padding_zero_both ">
                    <div class="card">
                        <?php
                        if(isset($_SESSION['reviewd']) && $_SESSION['reviewd'] =="success"){
                        ?>
                            <div class="body" style="margin-top: 25%;background: #f7f7f7;">
                                <div class="form-group text-center">
                                    <div>
                                        <img src="assets/images/check.png" style="width: 18%">
                                    </div>
                                    <div>
                                        <h4>Hi <?php echo $_SESSION['cust_name']; ?>,</h4>

                                        <p>Thank You! For Your Valuable Feedback.</p>

                                        <p>We appreciate you taking the time to send us this helpful response.</p>

                                        <p>Don't hesitate to reach out if you have any more questions, comments, or concerns.</p>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }else {
                            ?>

                            <div class="col-md-12 cover_img_div">
                                <div class="row">
                                    <img src="<?php if (!file_exists($coverPic) or $form_data['cover_pic'] == "") {
                                        echo "assets/images/default.jpg";
                                    } else {
                                        echo $coverPic;
                                    } ?>" style="width: 100%">
                                </div>
                            </div>
                            <div class="body">
                                <div class="col-md-12 text-center m-t-5 m-b-0">
                                    <div class="row">
                                        <h2>Feedback Form</h2>
<!--                                      //  <span class="more">--><?php //echo urldecode($get_data['message']) ?><!--</span>-->
                                        <p><b>We would like your feedback to improve our services .</p>
<!--                                     //   <p>what is your opinion of the page?</b></p>-->
                                        <hr>
                                        <div>
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
                                        </div>
                                    </div>
                                </div>
                                <form id="form_validation" method="POST" action=""
                                      enctype="multipart/form-data">

                                    <div>
                                        <label class="form-label">Enter Your Name</label> <span
                                            class="required_field">*</span>

                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input name="txt_name" class="form-control"
                                                       placeholder="Enter Your Name"
                                                       value="">
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="form-label">Describe Your Experience</label> <span
                                            class="required_field">*</span>

                                        <div class="form-group form-float">
                                            <div class="form-line">
                                        <textarea name="txt_des" rows="4" cols="50" class="form-control"
                                                  placeholder="Describe Your Experience"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="form-group form-float">
                                            <div class="row">
                                                <div class="col-md-6 col-xs-7 m-b-0">
                                                    <label class="form-label">Profile Pic (Optional)</label>
                                                    <input type="file" name="upload[]" id="file-7"
                                                           class="inputfile inputfile-6"
                                                           data-multiple-caption="{count} files selected"
                                                           multiple
                                                           onchange="readURL(this);"
                                                           accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG"/>
                                                    <label for="file-7"><span></span> <img id="blah"
                                                                                           class="input_choose_file blah"
                                                                                           src=""
                                                                                           alt=""/><strong
                                                            class="input_choose_file">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                 width="20"
                                                                 height="17" viewBox="0 0 20 17">
                                                                <path
                                                                    d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/>
                                                            </svg>
                                                            Choose a file&hellip;</strong></label>
                                                </div>
                                                <div class="col-md-6 col-xs-5">
                                                    <label>Default Image</label><br>
                                                    <?php echo '<img src="uploads/user.png" style="width: 30%;"/>'; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="form-group text-center">
                                            <button type="submit" class="btn btn-primary waves-effect"
                                                    name="btn_save_client"><i class="fa fa-paper-plane" style="font-size: 16px;"></i> Share Feedback</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>


            </div>
            </div>
    </section>

    <?php include "assets/common-includes/footer_includes.php" ?>

</body>
<script>
    $(document).ready(function() {
        if(window.outerWidth < 480) {
            $('body').css('background','#eaeaea');
            // Configure/customize these variables.
            var showChar = 100;  // How many characters are shown by default
            var ellipsestext = "...";
            var moretext = "Show more >";
            var lesstext = "Show less";


            $('.more').each(function () {
                var content = $(this).html();

                if (content.length > showChar) {

                    var c = content.substr(0, showChar);
                    var h = content.substr(showChar, content.length - showChar);

                    var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

                    $(this).html(html);
                }

            });

            $(".morelink").click(function () {
                if ($(this).hasClass("less")) {
                    $(this).removeClass("less");
                    $(this).html(moretext);
                } else {
                    $(this).addClass("less");
                    $(this).html(lesstext);
                }
                $(this).parent().prev().toggle();
                $(this).prev().toggle();
                return false;
            });
        }
    });
</script>
</html>