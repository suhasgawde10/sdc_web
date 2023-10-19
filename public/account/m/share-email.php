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
<?php

if (isset($_POST['sendEmail'])) {
    $txt_email = $_POST['txt_email'];
    $toName = $name;
    $subject = $name." has sent you a digital card";
    $message = "Hello ,<br> Please click on below link to check Digital Card!<br>";
    $message .= "<a href='$final_link'> $final_link </a>";
    if (is_array($txt_email)) {
        for ($i = 0; $i < count($txt_email); $i++) {
            $sendMail = $manage->sendMail($toName, $txt_email[$i], $subject, $message);
            if ($sendMail) {
                $error = false;
                $errorMessage = "Digital card has been sent successfully.";
            } else {
                $error = true;
                $errorMessage = "something when wrong while sending email to ".$txt_email[$i];
            }
        }
    }
}
?>


<div class="container height-100">
    <div class="row sms-header">
        <div class="col-sm-12 text-center">
            <a style="float: left" href="<?php echo get_url_param_for_mobile('index.php'); ?>"><i
                    class="fas fa-chevron-left"></i></a>
            SEND EMAIL
        </div>
    </div>
    <div class="row">
        <div class="card">
            <ul class="nav nav-tabs sms-tab" role="tablist">
                <li role="presentation"><a href="<?php echo get_url_param_for_mobile('add-remove-row.php'); ?>">SMS</a>
                </li>
                <li role="presentation"><a href="<?php echo get_url_param_for_mobile('qr_code.php'); ?>"
                                           aria-controls="profile">QR</a>
                </li>
                <li role="presentation" class="active"><a href="#Email"
                                                          aria-controls="profile"
                                                          role="tab"
                                                          data-toggle="tab">Email</a>
                </li>
                <li role="presentation"><a href="<?php echo get_url_param_for_mobile('unsaved-whatsapp.php'); ?>"
                                           aria-controls="profile">WhatsApp</a>
                </li>
            </ul>
            <div class="bank-up-div">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="Email">
                        <div class="col-md-4 col-md-offset-4">
                        <div class="sms-body">
                            <form method="post" action="">
                                <div class="text-center">
                                    <div class="send_sms_table">
                                        <?php if ($error) {
                                            ?>
                                            <div class="alert alert-danger">
                                                <?php if (isset($errorMessage)) echo $errorMessage; ?>
                                            </div>
                                        <?php
                                        } else if (!$error && $errorMessage != "") {
                                            ?>
                                            <div class="alert alert-success" style="padding: 8px; font-size: 13px;">
                                                <?php if (isset($errorMessage)) echo $errorMessage; ?>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        <table style="width: 100%; text-align: -webkit-center "  rules="all">

                                            <tr id="rowId">
                                                <td>

                                                    <div class="input-group" style="display: flex; margin-bottom: 10px">
                                                        <span class="input-group-addon"  style="width: 48.95px;"><i
                                                                class="glyphicon glyphicon-envelope"></i></span>
                                                        <input name="txt_email[]" type="email" class="form-control"
                                                               autofocus placeholder="Enter Email" required="required"/>&nbsp;&nbsp;<span class="plus_icon" onclick="addMoreRows(this.form);">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    </span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>


                                <div id="addedRows"></div>
                                <br>
                                <button class="btn btn-success" name="sendEmail" type="submit">Send Email&nbsp;&nbsp;&nbsp;<i
                                        class="fas fa-paper-plane"></i></button>
                            </form>
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

<script type="text/javascript">
    var rowCount = 1;

    function addMoreRows(frm) {
        rowCount++;
        /*var recRow = '<p id="rowCount'+rowCount+'" ><tr><td><div class="input-group" style="display: flex"><span class="input-group-addon" style="width: 49px;">+91</span> <input name="contact_no" type="number" class="form-control" placeholder="Enter Number" required="required"/> &nbsp;&nbsp;</div></td></tr><a href="javascript:void(0);" onclick="removeRow('+rowCount+');"><i class="fa fa-times plus_icon" aria-hidden="true"></i></a></p>';*/
        var recRow = '<p style="display:flex" id="rowCount' + rowCount + '"><tr><td style="display: table; position: relative;border-collapse: separate"><span class="input-group-addon" style="width: 49px;"><i class="glyphicon glyphicon-envelope"></i></span> <input name="txt_email[]" type="email" class="form-control" autofocus placeholder="Enter Email" required="required"/></td></tr>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="removeRow(' + rowCount + ');"><i class="fa fa-minus plus_icon" aria-hidden="true"></i></a></p>';
        jQuery('#addedRows').append(recRow);
    }
    function removeRow(removeNum) {
        jQuery('#rowCount' + removeNum).remove();
    }
</script>

</body>
</html>

