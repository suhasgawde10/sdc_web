<?php

$errorPreview = false;
$errorPreviewMessage = "";

if (isset($_POST['submit'])) {
    $result = $manage->validateCustomUrl(trim($_POST['custom_url_preview']));
    if ($result) {
        $errorPreview = true;
        echo "<script>alert('custom url already exist')</script>";
        /* $errorPreviewMessage .="custom url already exist"; */
    }
    $removeCustomSpace = str_replace(' ','-',$_POST['custom_url_preview']);
    if (!$errorPreview) {
        $update_custom_url = $manage->updateCustomUrl($removeCustomSpace);
        $addLogFile = $manage->addCustomUrlLog($removeCustomSpace);
        if ($addLogFile) {
            if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
                $_SESSION['create_user_custom_url'] = $removeCustomSpace;
            }else{
                $_SESSION['custom_url'] = $removeCustomSpace;
            }
            $session_custom_url_is = $removeCustomSpace;
            /*header('location:basic-user-info.php');*/
            $toName = "";
            $toEmail = "" . $session_email . "";
            $subject = "Successfully changed the custom URL.";
            $sms_message = "Dear " . $session_name . ",\n";
            $sms_message .= "Your new digital card link is ready.\n";
            $sms_message .= "Please Click on below link to open your Digital Card.\n";
            $sms_message .= "www.sharedigitalcard.com/website/index.php?custom_url=" . $session_custom_url_is;
            $message = '<!DOCTYPE html><html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Updation Of Custom Url link</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <style>

        body
        {
            margin: 0;
            padding: 0;
        }

        .main
        {
            width: 50%;
            margin: 0 auto;
            box-shadow: 0 0px 5px 5px #ccc;
        }

        .cust-name
        {
            color:blue;
        }
        .content
        {
            width: 80%;
            margin: 0 auto;
        }

        .thanks-msg{
            background-image: url("https://image.freepik.com/free-vector/blue-geometric-shapes-transparent-background_1035-9784.jpg");
            width: 100%;
            height: auto;
            background-repeat: no-repeat;
            background-size: cover;
            position: relative;
        }

        .overlay
        {
            position: absolute;
            top:0;
            bottom: 0;
            right: 0;
            left: 0;
            background-color: rgba(86, 86, 86, 0.33);

        }
        .msg
        {
            text-align: center;
            color: white;
            /* font-size: 20px; */
            font-weight: 700;
            margin: 10px 0;
            padding-top: 4%;
        }


        .msg h1
        {
            font-size: 24px;
            margin: 0;
        }
        .msg p
        {
            font-size: 15px;
        }

        .icon
        {
            text-align: center;
            color: #c4a758;
            width: 80px;
            margin: 1px auto;
            background: white;
            border-radius: 50%;
            height: 80px;
            text-align: center;
            padding: 5px;
        }

        .user-name-logo h3
        {
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            text-decoration: underline;
            text-transform: uppercase;
            color: ghostwhite;

        }

        .email-id
        {
            font-size: 20px;
            font-weight: bold;
            color: #0c2edc;
        }
        .about-content p
        {
            font-size: 18px;
            line-height: 25px;
        }


        .details-step
        {
            vertical-align: middle;
            font-size: 12px;
            line-height: 34px;
            font-weight: bold;
            list-style-type: decimal;
            width: 100%;
            padding: 0;
            margin: 0;
        }

        .details-step li
        {
            vertical-align: middle;
            position: relative;
            width: 49%;
            display: inline-block;
            margin-bottom: 15px;
        }


        .icon img
        {
            width: 100%;
        }

        .step
        {
            background: #eae8e8;
            border-radius: 10px;
            font-size: 15px;
            padding: 5px;
        }

        .about-content span
        {
            color: #002bd0;
            font-weight: 600;
        }

        .about-content a
        {
            color: #002bd0;
            text-decoration: none;
        }

        .about-content a:hover
        {

            font-weight: bold;
            transform: scale(1.2);
        }


        .btn {
            padding: 1em 2.1em 1.1em;
            border-radius: 3px;
            margin: 8px 8px 20px 8px;
            color: #fbdedb;
            background-color: #fbdedb;
            display: inline-block;
            background: #e74c3c;
            -webkit-transition: 0.3s;
            -moz-transition: 0.3s;
            -o-transition: 0.3s;
            transition: 0.3s;
            font-family: sans-serif;
            font-weight: 800;
            font-size: .85em;
            text-transform: uppercase;
            text-align: center;
            text-decoration: none;
            -webkit-box-shadow: 0em -0.3rem 0em rgba(0, 0, 0, 0.1) inset;
            -moz-box-shadow: 0em -0.3rem 0em rgba(0, 0, 0, 0.1) inset;
            box-shadow: 0em -0.3rem 0em rgba(0, 0, 0, 0.1) inset;
            position: relative;
        }

        .btn:active {
            -webkit-transform: scale(0.80);
            -moz-transform: scale(0.80);
            -ms-transform: scale(0.80);
            -o-transform: scale(0.80);
            transform: scale(0.80);
        }
        .btn.block {
            display: block !important;
        }
        .btn.circular {
            border-radius: 50em !important;
        }
        .button-login
        {
            text-align: center;
        }

        .padding-sec
        {
            padding: 25px;
        }

        .se-date
        {
            color: #fb3737 !important;
            font-weight: 600;
        }
        .social-icon {
            width: 100%;
            padding: 0;
            margin: 0;
            vertical-align: top;
            position: relative;
            overflow: hidden;
            text-align: center;

        }

        .social-icon li
        {
            width: 8%;
            padding: 0;
            margin: 0;
            vertical-align: top;
            position: relative;
            display: inline-block;
        }
        .add
        {
            width: 100%;
            margin: 0 auto;
        }
        .add-details
        {
            /* width: 100%; */
            margin: 10px 0;
        }

        .logo
        {
            width: 15%;
            margin: 0 auto;
        }
        .logo img
        {
            width: 100%;
        }

        .addres
        {
            width: 100%;
            margin: 0 auto;
            font-size: 12px;

        }
        .footer-top
        {
            padding: 10px;
            background: #e6e6e6;
            height: 115px;
        }

        .text-center
        {
            text-align: center;
        }

        .footer-btm
        {
            padding: 10px;
            background: #e6e6e6;
        }

        .footer-btm a
        {
            text-decoration: none;
        }

        .content-foot
        {
            width: 85%;
            margin: 0 auto;
        }

        .about-content a
        {
            color: #002bd0;
            text-decoration: none;
        }

        .about-content a:hover
        {

            font-weight: bold;
            transform: scale(1.2);
        }
         .user-name-logo{
        padding-bottom: 1%;
        }
        @media (max-width: 991px) {
        .addres p{
         margin: 0;
         padding-bottom: 10px;
         }
         .button-login p{
         padding-bottom: 10px;
         }
         .main
        {
            width: 100%;
            }
            .padding-sec {
    padding: 10px;
}
    .otp-inner {
    width: 76%;
    padding: 10px;
    }

    .social-icon li{
    width: 14%;
    }
    .add-details {
     margin: 0;
}
.logo {
    width: 104px;
    margin: 0 auto;
}
.footer-top {
    padding: 10px 10px 0 10px;
    height: auto}

        }
        @media (max-width: 480px) {
         .addres p{
         margin: 0;
         padding-bottom: 10px;
         }
         .button-login p{
         padding-bottom: 10px;
         }
         .main
        {
            width: 100%;
            }
            .padding-sec {
            padding: 10px;
              }
              .otp-inner {
    width: 76%;
    padding: 10px;
    }

    .social-icon li{
    width: 8%;
    }
    .add-details {
     margin: 0;
}
.logo {
    width: 104px;
    margin: 0 auto;
}
.footer-top {
    padding: 10px 10px 0 10px;
    height: auto;}
        }
        @media (max-width: 360px) {
         .addres p{
         margin: 0;
         padding-bottom: 10px;
         }
         .button-login p{
         padding-bottom: 10px;
         }
         .main
        {
            width: 100%;
            }
            .padding-sec {
            padding: 10px;
              }
              .otp-inner {
    width: 76%;
    padding: 10px;
    }

    .social-icon li{
    width: 14%;
    }
    .add-details {
     margin: 0;
}
.logo {
    width: 104px;
    margin: 0 auto;
}
.footer-top {
    padding: 10px 10px 0 10px;
    height: auto;
    }

        }
         @media (max-width: 320px) {
         .addres p{
         margin: 0;
         padding-bottom: 10px;
         }
         .button-login p{
         padding-bottom: 10px;
         }
         .main
        {
            width: 100%;
            }
            .padding-sec {
            padding: 10px;
              }
              .otp-inner {
    width: 76%;
    padding: 10px;
    }

    .social-icon li{
    width: 14%;
    }
    .add-details {
     margin: 0;
}
.logo {
    width: 104px;
    margin: 0 auto;
}
.footer-top {
    padding: 10px 10px 0 10px;
    height: auto;
    }

        }
    </style>



</head>
<body>

<section class="padding-sec">
    <section class="main">

        <section class="thanks-msg">
            <div class="overlay">
                <div class="content">
                    <div class="msg">
                        <h1>Share Digital Card</h1>
                        <p>Updation of Custom Url</p>
                    </div>
                    <div class="user-name-logo">
                        <div class="icon">
                            <img src="https://sharedigitalcard.com/user/assets/images/user_update.png">
                        </div>
                     <!--   <h3>Sachin Pangam</h3>-->
                    </div>
                </div>
            </div>
        </section>
        <section class="about">
            <div class="content">
                <div class="about-content">
                    <p>Dear <span class="cust-name">' . ucwords($session_name) . '</span>,<p>
                    <p> This mail is regarding successful updations of custom url link.</p>
                    <p>
                        Your Digital Card custom url link <a href="#">www.sharedigitalcard.com/website/index.php?custom_url='.$session_custom_url_is.'</a> .Click here to view your Digital Card.
                    </p>
                </div>
                <div class="button-login">
                    <a href="http://sharedigitalcard.com/login.php" class="btn orange circular">Click To Login</a>
                </div>
                <p>
                        For To Done Any Changes In Digital card Click On to Above Button. And For any Query email Us on <a href="mailto:support@sharedigitalcard.com" class="payment">support@sharedigitalcard.com</a>
                    </p>

            </div>
        </section>
        <section class="footer-top">
            <div class="content-foot">
                <div class="add">
                    <div class="social">
                        <ul class="social-icon">
                         <li><img src="http://sharedigitalcard.com/user/assets/images/fb.png"> </li>
                            <li><img src="http://sharedigitalcard.com/user/assets/images/insta.png"></li>
                            <li><img src="http://sharedigitalcard.com/user/assets/images/yt.png"></li>
                            <li><img src="http://sharedigitalcard.com/user/assets/images/pin.png"></li>
                        </ul>
                    </div>
                </div>

            </div>

        </section>
        <section class="footer-btm">
            <div class="content text-center">
                <a href="https://kubictechnology.com/" target="_blank" title="Kubic Technology Website Development Company In Mumbai">
                    Kubic Technology Website Development Company In Mumbai
                </a>
            </div>
        </section>
    </section>
</section>

</body>
</html>';
            $url = $_SERVER['PHP_SELF'];
            header('location:'.$url);
            $sendMail = $manage->sendMail($toName, $toEmail, $subject, $message);
            $send_sms = $manage->sendSMS($session_contact_no, $sms_message);
        }
    }
}
if(isset($_POST['cancel_button'])){
    $url = $_SERVER['PHP_SELF'];
    header('location:'.$url);
}
?>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-10 padding_zero padding_zero_both">
            <div class="form-group form-float">
                <div class="form-line preview_holder">
                    <form action="" method="post">
                        <div class="info_circle help">
                            <div class="info-box-url" style="display: none;">
                                <a href="#" class="close-button">×</a>
                                <img src="assets/images/preview.png">
                            </div>
                            <a class="help-button" href="#" title="Click to know more"><i class="fas info_circle_color fa-info-circle"></i></a>
                        </div>
                        <input id="myInput" name="custom_url_preview" class="form-control preview_padding"
                               placeholder="Digital_Card/website/index.php?custom_url=<?php echo $session_custom_url_is; ?>"
                               value="<?php if (isset($_GET['custom_url_id'])){ echo $session_custom_url_is; }else{ echo "www.sharedigitalcard.com/website/index.php?custom_url=".$session_custom_url_is;} ?>">
                        <div class="edit_icon">
                            <?php if (isset($_GET['custom_url_id'])) { ?>
                                <button class="right_button" name="cancel_button"><i class="fas wrong_button fa-times"></i></button>
                                <button class="right_button" type="submit" name="submit"><i class="fas right_check fa-check"></i></button>
                            <?php
                            } else { ?>
                                <a class="fas edit_color fa-pencil-alt" href="<?php echo $_SERVER['PHP_SELF']; ?>?custom_url_id=<?php echo $id; ?>"></a>
                            <?php
                            } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2 preview_btn_margin">
        <!--<input type="button" <img src='assets/images/copy.png'>-->
        <!--<input type="image" onclick="myFunction()" onmouseout="outFunc()" src="assets/images/copy.png" alt="Tool Tip">-->
        <a title="copy URL" class="fas copy_button fa-copy" onclick="myFunction()" onmouseout="outFunc()" > copy URL</a>
        <a title="Preview" target="_blank" class="preview_button"
           href="../website/index.php?custom_url=<?php echo $session_custom_url_is; ?>">Live Preview</a>
    </div>
</div>
