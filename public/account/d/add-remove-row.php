<?php

include "../controller/ManageMobileCard.php";
$manage = new ManageMobileCard();
include_once '../sendMail/sendMail.php';

$error = false;
$errorMessage = "";

include 'assets/common-includes/all-query.php';

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

$get_country = $manage->mdm_getCountryCode($country);
if($get_country !=null){
    $country_code = $get_country['phonecode'];
}else{
    $country_code = "91";
}
if (isset($_POST['send'])) {
    $error = false;
    $contact_no = $_POST['contact_no'];
    if (is_array($contact_no)) {
        for ($i = 0; $i < count($contact_no); $i++) {
            $message = "Hello ,\nPlease click on below link to check Digital Card! :)\n" . $final_link;
            $sendSMS = $manage->sendSMS($contact_no[$i], $message);
            if ($sendSMS) {
                $error = false;
                $errorMessage = "Digital card has been sent successfully.";
            } else {
                $error = true;
                $errorMessage .= "something when wrong while sending sms to ".$contact_no[$i];
            }
        }
    }

}

?>


<div>
    <div class="container">
        <div class="row sms-header">
            <div class="col-sm-12 text-center">
                <a style="float: left" href="<?php echo get_url_param_for_mobile('index.php'); ?>"><i
                        class="fas fa-chevron-left"></i></a>
                <span class="text-center">SEND SMS</span>
            </div>
        </div>


        <div class="row">
            <div class="card">
                <ul class="nav nav-tabs sms-tab" role="tablist">
                    <li role="presentation" class="active"><a href="#SMS"
                                                              aria-controls="home"
                                                              role="tab"
                                                              data-toggle="tab">SMS</a>
                    </li>
                    <li role="presentation"><a href="<?php echo get_url_param_full_url('qr_code.php'); ?>"
                                               aria-controls="profile">QR</a>
                    </li>
                    <li role="presentation"><a href="<?php echo get_url_param_full_url('share-email.php'); ?>"
                                               aria-controls="profile">Email</a>
                    </li>
                    <li role="presentation"><a href="<?php echo get_url_param_full_url('unsaved-whatsapp.php'); ?>"
                                               aria-controls="profile">WhatsApp</a>
                    </li>
                </ul>
                <div class="bank-up-div">
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="SMS">
                            <div class="col-md-4 col-md-offset-4">
                            <div class="sms-body">
                                <form method="post" action="">
                                    <div class="text-center">
                                        <div  class="send_sms_table">
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
                                            <table rules="all" style="width: 100%; text-align: center">
                                                <tr id="rowId">
                                                    <td>
                                                        <div class="input-group" style="display: flex; margin-bottom: 10px">
                                                            <span class="input-group-addon" style="width: 48.95px;">+91</span>
                                                            <input name="contact_no[]" type="number"
                                                                   class="form-control" placeholder="Enter Number"
                                                                   required="required" autofocus oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "10"/>
                                                            &nbsp;&nbsp;<span class="plus_icon" onclick="addMoreRows(this.form);"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                                        </div>
                                                    </td>

                                                </tr>

                                            </table>
                                        </div>
                                    </div>

                                    <div id="addedRows"></div>
                                    <br>
                                    <button class="btn btn-success" name="send" type="submit">Send SMS&nbsp;&nbsp;&nbsp;<i
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
</div>


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

