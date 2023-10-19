<?php

include "../controller/ManageMobileCard.php";
$manage = new ManageMobileCard();
include_once '../sendMail/sendMail.php';

$error = false;
$errorMessage = "";
if (isset($_GET['custom_url'])) {
    $get_data = $manage->mdm_getDigitalCardDetailsOFUser($_GET['custom_url']);
    $parent_id = $get_data['parent_id'];
    if($parent_id !=""){
        $getParentData = $manage->getSpecificUserProfileById($parent_id);
        $custom_url = $getParentData['custom_url'];
    }else{
        $custom_url = $_GET['custom_url'];
    }

    $get_result = $manage->mdm_getDigitalCardDetails("service",$custom_url);
}else{
    header('location:../index.php');
}



?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "assets/common-includes/header_includes.php"  ?>
    <?php
    $domain_link = $get_data['domain_link'];
    if (isset($domain_link) && $domain_link != '') {
        $final_link = $domain_link;
    } else {
        $final_link = SHARED_URL. $_GET['custom_url'];
    }
    ?>
    <style>

        .nav-tabs > li {
            width: 25%;
            text-align: center;
            background: #ededed;
        }
        .nav-tabs > li.active {
            background: #ffffff;
        }
        .nav-tabs {
            border-bottom: 2px solid #DDD;
            background: white;
            margin-bottom: 25px;
        }

        .nav-tabs > li > a {
            padding: 10px 0;
        }


        .input-group{
            width: 100%;
        }
        .iti{
            width:100%
        }
        .iti__flag-container{
            z-index: 99;
        }

        .bank-up-div{
            overflow: unset;
        }
    </style>

</head>
<body style="background-image: linear-gradient(to top, #fff1eb 0%, #ace0f9 100%);height: 100vh">



<div class="container height-100">
    <div class="row sms-header">
        <div class="col-sm-12 text-center">
            <a style="float: left" href="<?php echo get_url_param_for_mobile('index.php'); ?>"><i
                    class="fas fa-chevron-left"></i></a>
            Scan QR Code
        </div>
    </div>


    <div class="row">
        <div class="card">
            <ul class="nav nav-tabs sms-tab" role="tablist">
                <li role="presentation"><a href="<?php echo get_url_param_for_mobile('add-remove-row.php'); ?>">SMS</a>
                </li>
                <li role="presentation" class="active"><a href="#qrcode"
                                                          aria-controls="home"
                                                          role="tab"
                                                          data-toggle="tab">QR</a>
                </li>
                <li role="presentation"><a href="<?php echo get_url_param_for_mobile('share-email.php'); ?>"
                                           aria-controls="profile">Email</a>
                </li>
                <li role="presentation"><a href="<?php echo get_url_param_for_mobile('unsaved-whatsapp.php'); ?>"
                                           aria-controls="profile">WhatsApp</a>
                </li>
            </ul>
            <div class="bank-up-div">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="qrcode">
                        <div class="col-md-4 col-md-offset-4">
                        <div class="sms-body">
                            <?php
/*
                            include "qr-code-export-vcf.php";
                            */?>

                            <!--<iframe id="qr-code" src="<?php /*echo get_url_param_for_mobile('qr-code-testing.php'); */?>"></iframe>-->
                            <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?php echo $final_link; ?>&choe=UTF-8"
                                 style="width: 100%" title="Paypal Details"/>

                        </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include "assets/common-includes/footer.php" ?>
<?php include "assets/common-includes/footer_includes.php" ?>

<script>
    if (screen.width >= 768){
        $('.mobile_footer').hide();
    }
    $('.footer-ul li a').each(function() {
        $(this).attr("href","m/" + $(this).attr("href"));
    });
</script>
<script>
    $('#qr-code').contents().find('#yourItemYouWantToChange').css({
        opacity: 0,
        color: 'purple'
    });
</script>

</body>
</html>

