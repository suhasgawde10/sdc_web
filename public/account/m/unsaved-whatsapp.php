<?php
ob_start();
include "../controller/ManageDesktopCard.php";
$manage = new ManageDesktopCard();
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

    <link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
</head>
<body style="background-image: linear-gradient(to top, #fff1eb 0%, #ace0f9 100%);height: 100vh">
<?php

$get_country = $manage->mdm_getCountryCode($country);
if ($get_country != null) {
    $country_code = $get_country['phonecode'];
} else {
    $country_code = "91";
}
if (isset($_POST['send'])) {
    
    if (isset($_REQUEST['phone_number']['full']) && $_REQUEST['phone_number']['full'] != "") {
        $whatsapp_number = $_REQUEST['phone_number']['full'];
    } else {
        $error = true;
        $errorMessage = "Please Enter Contact Number";
    }


    if (!$error) {
        $text = "";
        if (isset($company_name) && $company_name != "") $text = "*" . trim($company_name) . "*%0A%0A";
        $text .= $final_link;
        $link_is = 'https://api.whatsapp.com/send?phone=' . $whatsapp_number . '&text=' . $text;
       // header('location:'.$link_is);
        echo "<script type=\"text/javascript\">
        window.open('https://api.whatsapp.com/send?phone=".$whatsapp_number."&text=".$text."', '_blank')
    </script>";
        /*$country_code*/
    }
}




?>


<section>
    <div class="container">
        <div class="row sms-header">
            <div class="col-sm-12 text-center">
                <a style="float: left" href="<?php echo get_url_param_for_mobile('index.php') ?>"><i
                            class="fas fa-chevron-left"></i></a>
                <span class="text-center">Unsaved WhatsApp</span>
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
                    <li role="presentation"><a href="<?php echo get_url_param_for_mobile('share-email.php'); ?>"
                                               aria-controls="profile">Email</a>
                    </li>
                    <li role="presentation" class="active"><a href="#SMS"
                                                              aria-controls="home"
                                                              role="tab"
                                                              data-toggle="tab">WhatsApp</a>
                    </li>
                </ul>
                <div class="bank-up-div">
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="SMS">
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
                                            <h4>Share Digital Card On WhatsApp</h4>
                                            <table style="width: 100%; text-align: -webkit-center " rules="all">
                                                <tr id="rowId">
                                                    <td>
                                                        <!-- <div class="input-group"
                                                              style="display: flex; margin-bottom: 10px">

                                                             <input name="whatsapp_number" type="number"
                                                                    class="form-control" placeholder="Enter Number With Country Code"
                                                                    required="required" autofocus
                                                                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                    maxlength="15"/>
                                                         </div>-->
                                                        <div class="input-group">

                                                            <input type="tel" name="phone_number[main]" required="required"
                                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                   maxlength="15" class="form-control" id="phone_number" autofocus  />
                                                            <!--      <span class="input-group-addon">Tel</span>-->
                                                        </div>
                                                    </td>

                                                </tr>

                                            </table>
                                        </div>
                                    </div>

                                    <div id="addedRows"></div>
                                    <br>
                                    <button class="btn btn-success" name="send" type="submit">Send&nbsp;&nbsp;&nbsp;<i
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
</section>


<p id="result"></p>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.7/js/intlTelInput.js"></script>-->
<script>
   /* $("input").intlTelInput({
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.6/js/utils.js"
    });*/
    var phone_number = window.intlTelInput(document.querySelector("#phone_number"), {
        separateDialCode: true,
        preferredCountries:["in"],
        hiddenInput: "full",
        utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
    });

    /*$("button[name=send]").click(function() {
        var full_number = phone_number.getNumber(intlTelInputUtils.numberFormat.E164);
        $("input[name='phone_number[full]'").val(full_number);
        alert(full_number)

    });*/
</script>
<?php include "assets/common-includes/footer_includes.php" ?>
<script>
    if (screen.width >= 768) {

        $('.mobile_footer').hide();
    }
    $('.footer-ul li a').each(function () {
        $(this).attr("href", "m/" + $(this).attr("href"));
    });
</script>
<script type="text/javascript">
    var rowCount = 1;

    function addMoreRows(frm) {
        rowCount++;
        /*var recRow = '<p id="rowCount'+rowCount+'" ><tr><td><div class="input-group" style="display: flex"><span class="input-group-addon" style="width: 49px;">+91</span> <input name="contact_no" type="number" class="form-control" placeholder="Enter Number" required="required"/> &nbsp;&nbsp;</div></td></tr><a href="javascript:void(0);" onclick="removeRow('+rowCount+');"><i class="fa fa-times plus_icon" aria-hidden="true"></i></a></p>';*/
        var recRow = '<p style="display:flex" id="rowCount' + rowCount + '"><tr><td style="display: table; position: relative;border-collapse: separate"><span class="input-group-addon" style="width: 48.95px;">+91</span> <input name="contact_no[]" type="number" class="form-control" placeholder="Enter Number" autofocus required="required" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "10"/></td></tr>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="removeRow(' + rowCount + ');"><i class="fa fa-minus plus_icon" aria-hidden="true"></i></a></p>';
        jQuery('#addedRows').append(recRow);
    }

    function removeRow(removeNum) {
        jQuery('#rowCount' + removeNum).remove();
    }
</script>
<!--<script>
    $(document).ready(function () {
        $('#butsave').on('click', function () {
            var contact = document.getElementById('rowCount1' + rowCount).value;
            alert(contact);
            var dataString = "contact_no=" + contact;
            $.ajax({
                type: "POST",
                url: "theme-change.php",
                data: dataString,
                success: function (html) {
                    $("#price").html(html);
                }
            });
</script>-->
</body>
</html>

