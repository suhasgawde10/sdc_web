<?php

include "../controller/ManageMobileCard.php";

$manage = new ManageMobileCard();
include_once '../sendMail/sendMail.php';
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../controller/validator.php";
$error = false;
$errorMessage = "";


if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $link = "https";
else
    $link = "http";
$link .= "://";

$link .= $_SERVER['HTTP_HOST'];

$link .= $_SERVER['REQUEST_URI'];

if (isset($_GET['custom_url'])) {
    $get_data = $manage->mdm_getDigitalCardDetailsOFUser($_GET['custom_url']);
    $parent_id = $get_data['parent_id'];
    $company_name = $get_data['company_name'];
    if($parent_id !=""){
        $getParentData = $manage->getSpecificUserProfileById($parent_id);
        $custom_url = $getParentData['custom_url'];
    }else{
        $custom_url = $_GET['custom_url'];
    }

}

function fetch_all_data($result)
{
    $all = array();
    while($thing = mysqli_fetch_array($result)) {
        $all[] = $thing;
    }
    return $all;
}

$validToken = false;
if(isset($_GET['token']) && $_GET['token'] !=''){
    $token = trim($_GET['token']);
    $validToken = $manage->getTokenDetails($get_data['user_id'],$token);
}
function rep_escape($string){
    return str_replace(['\r\n','\r','\n','\\'],'',$string);
}

$user_id = $get_data['user_id'];
$contact_no = $get_data['contact_no'];
$email = $get_data['email'];
$name = $get_data['name'];


?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        .sms-body{
            margin-top: 10px;
        }
    </style>
</head>
<body style="background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
<?php


?>
<div id="snackbar"></div>

<div>
    <div class="container height-100">
        <div class="row sms-header">
            <div class="col-sm-12 text-center">
                <a style="float: left" href="index.php?custom_url=<?php echo $_GET['custom_url'] ?>"><i
                        class="fas fa-chevron-left"></i></a>
                <h4 class="text-center"> Share Link</h4>
            </div>
        </div>


        <div class="row">
            <div class="card">
                <div class="bank-up-div">

                            <div class="col-sm-offset-2 col-sm-10 sms-body">
                                <form method="post" action="">
                                    <div class="alert alert-danger" id="msg_alert_danger">
                                    </div>
                                    <div class="alert alert-success" id="msg_alert_success">
                                    </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12 " style="margin: 10px 0 10px 0;">
                                                <div class="row sharelink" >
                                                    <div class="col-md-10">
                                                        <h5>Public Link (Without Bank Info)</h5>
                                                        <p>Bank Details will not be visible in public.</p>
                                                    </div>
                                                    <div class="col-md-2 text-right">
                                                        <button class="btn btn-default" type="button" onclick="setClipboard('<?php echo $final_link; ?>','URL is on the Clipboard try to paste it!')">Copy Link</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12" >
                                                <div class="row sharelink" >
                                                    <div class="col-md-10">
                                                        <h5>Private Link (Secure - With Bank Info)</h5>
                                                        <p>Bank Details will be visible anyone on the internet with this link can view</p>
                                                    </div>
                                                    <div class="col-md-2 text-right">
                                                        <button class="btn btn-default get_link" type="button" name="get_link">Get Link</button>
                                                    </div>
                                                </div>
                                            </div>

                                </form>
                            </div>

                </div>
            </div>
        </div>

    </div>
    <?php include "assets/common-includes/footer.php" ?>
</div>

<?php // include "assets/common-includes/footer_includes.php" ?>


<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>-->


<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>

<script>
    $(document).ready(function(){
        $('#msg_alert_danger').hide();
        $('#msg_alert_success').hide();
    });
    $('.get_link').on('click',function(){
            var dataString = "get_link=true"+"&custom_url="+'<?php echo urlencode($custom_url); ?>';
            console.log(dataString);
            $.ajax({
                type: 'POST',
                url: '<?php echo FULL_WEBSITE_URL; ?>quick-demo-ajax.php',
                dataType: "json",
                data: dataString,
                beforeSend: function () {
                    $('.get_link').text('Getting...').attr("disabled", 'disabled');
                },
                success: function (response) {
                    if (response.status == 'ok') {
                        $('.get_link').text('Get link').removeAttr("disabled");
                        $('#msg_alert_danger').hide();
                        $('#msg_alert_success').show().text(response.msg);
                    } else {
                        $('.get_link').text('Get link').removeAttr('disabled');
                        $('#msg_alert_success').hide();
                        $('#msg_alert_danger').show().text('Issue while getting private link please try after some time .');
                    }
                },
                error: function (err) {
                    $('.get_link').text('Get link').removeAttr('disabled');
                    console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
                }
            });
    });

</script>
<script>
    function setClipboard(value,text) {

        var tempInput = document.createElement("input");
        tempInput.style = "position: absolute; left: -1000px; top: -1000px";
        tempInput.value = value;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        if ("copy") {
            var x = document.getElementById("snackbar");
            x.innerHTML = text;
            x.className = "show";
            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
        }
    }
</script>
<script>
    $(window).load(function () {
        setTimeout(function(){
            $('.spinner').fadeOut();
            $('.back-color').fadeOut();
            $('.path').fadeOut();
        }, 20);
    });
</script>
</body>
</html>

