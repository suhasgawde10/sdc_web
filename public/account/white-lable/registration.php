<?php
$error = false;
$errorMessage = "";
include "controller/ManageAdminApp.php";
$manage = new ManageAdminApp();
include "controller/validator.php";
$validate = new Validator();
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$controller = new Controller();
$con = $controller->connect();
include "controller/config data.php";
//include "sendMail/sendMail.php";
include "common-file.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

$countries_array = $manage->getCountryCategory();

?>


<!DOCTYPE html>
<html>
<head>
    <title>Create digital card - <?php echo strtoupper($company_name); ?></title>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="DGINDIA" name="keywords">
    <!-- Favicons -->
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <style>
        .error {
            color: red;
        }

        .valid {
            border: 2px solid #0ff;
        }

        .form-control:disabled, .form-control[readonly] {
            background: <?php THEME_COLOR ?> !important;
            padding: 7px 24px;
            color: #fff;
            transition: 0.4s;
            border-radius: 50px;
            margin-top: 5px;
            outline: unset;
            cursor: not-allowed;
            border: none;
        }

        #pswd_info {
            position: absolute;
            bottom: -25px;
            left: 38px;
            width: 300px;
            padding: 10px;
            background: #fefefe;
            font-size: .875em;
            border-radius: 5px;
            box-shadow: 0 1px 3px #ccc;
            border: 1px solid #ddd;
            z-index: 9999;
        }

        #pswd_info h4 {
            font-size: 18px;
            margin: 0 0 10px 0;
            padding: 0;
            font-weight: normal;

        }

        #pswd_info::before {
            content: "\25B2";
            position: absolute;
            top: -12px;
            left: 45%;
            font-size: 14px;
            line-height: 14px;
            color: #ddd;
            text-shadow: none;
            display: block;

        }

        .invalid {
            background: url(assets/img/close.png) no-repeat 0 50%;
            background-size: 12px;
            padding-left: 22px;
            line-height: 24px;
            color: #ec3f41;
        }

        .validp {
            background: url(assets/img/right.png) no-repeat 0 50%;
            background-size: 20px;
            padding-left: 22px;
            line-height: 24px;
            color: #3a7d34;
        }

        #pswd_info {
            display: none;
        }
    </style>

    <!-- Vendor CSS Files -->
    <?php include "white-lable/assets/common-includes/header-includes.php"; ?>

</head>
<body>
<?php include "white-lable/assets/common-includes/header.php"; ?>
<section class="inner-page registration_section" style="margin-top: 30px;">
    <div class="container">
        <div class="col-lg-10" style="margin: 0 auto">
            <div class="registration_form">
                <form action="" method="post" id="form_register" autocomplete="off">

                    <div class="text-center">
                        <div class="head_reg">
                            <h3 style="color: #393c4f;">Create Your Digital Card</h3>
                        </div>
                    </div>
                    <div id="feedback_success" class="alert alert-success" role="alert"></div>
                    <div id="feedback_error" class="alert alert-danger" role="alert"></div>


                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Full Name : </label>
                            <input placeholder="Your Name" type="text" name="txt_name" class="form-control" required="">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Email : </label>
                            <input placeholder="Your Email" type="email" name="txt_email" class="form-control"
                                   required="">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Contact No. : </label>
                            <input placeholder="Contact Number" type="text" name="txt_contact" class="form-control">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Company Name (Optional): </label>
                            <input placeholder="Company Name" type="text" name="txt_company" class="form-control">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Gender: </label>
                            <select name="drp_gender" class="form-control">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>

                        </div>
                        <div class="form-group col-md-6">
                            <label>Country: </label>
                            <select id="drp_country" name="drp_country" class="form-control"
                                    onchange="getStateDataByCountry(this.value)" required="">
                                <option value="">Select Country</option>
                                <?php
                                while ($value = mysqli_fetch_array($countries_array)) {
                                    ?>
                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>State: </label>

                            <div id="state_select">
                                <select name="state" id="state" required
                                        class="form-control"
                                        onchange="getCityByStateId(this.value)">
                                    <option value="">Select state</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>City: </label>

                            <div id="city_select">
                                <select name="city" id="city" required
                                        class="form-control">
                                    <option value="">Select city</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Password: </label>
                            <input placeholder="Password" type="password" id="password" name="txt_password"
                                   class="form-control"
                                   autocomplete="off">
                            <lable id="password_verify" class="error">Please Complete Password requirement</lable>

                        </div>
                        <div id="pswd_info">
                            <h4>Password must meet the following requirements:</h4>
                            <ul style="list-style: none">
                                <li id="letter" class="invalid">At least <strong>one letter</strong>
                                </li>
                                <li id="capital" class="invalid">At least <strong>one capital
                                        letter</strong></li>
                                <li id="number" class="invalid">At least <strong>one number</strong>
                                </li>
                                <li id="splcha" class="invalid">At least <strong>one special character</strong>
                                </li>
                                <li id="length" class="invalid">Be at least <strong>8 characters</strong></li>
                            </ul>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Confirm Password: </label>
                            <input placeholder="Confirm Password" type="password" id="confirmPassword"
                                   name="txt_con_password" class="form-control">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="checkbox" name="chk_terms" required="required" value="1"> I
                            accept all <a style="color: #2793e6;cursor: pointer" href="#" target="_blank">Terms
                                &amp; Condition</a>
                        </div>
                        <div class="form-group col-md-6">
                            <input type="checkbox" name="promote_business" value="1">
                            Do you want to promote your business online?
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-4">
                                <p id="question"></p>
                            </div>
                            <div class="col-lg-8">
                                <input id="ans" type="text">
                            </div>
                        </div>

                        <!--<div id="message">Please verify.</div>
                        <div id="success">Verification completed :)</div>-->


                    </div>

                    <!--<div class="form-group mt-3">
                        <label>Select Plan : </label>
                        <select name="drp_plans" class="form-control">
                            <option Value="0">Select</option>
                            <option value="1 Year">1 Year</option>
                            <option value="2 Year + 1 Year (Free)">2 Year + 1 Year (Free)</option>
                            <option value="3 Year + 2 Year (Free)">3 Year + 2 Year (Free)</option>
                            <option value="Life Time">Life Time</option>
                        </select>
                    </div>-->

                    <!--<%-- <div class="my-3">
                        <div class="loading">Loading</div>
                        <div class="error-message"></div>
                        <div class="sent-message">Your message has been sent. Thank you!</div>
                    </div>--%>-->
                    <div class="text-center btn_msg">
                        <button id="btn_register" name="btn_register" type="submit"
                                class="send_msg form-control">Create Card
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</section>
<?php include "white-lable/assets/common-includes/footer.php"; ?>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

<div id="preloader">
    <div class="loder-img">
    </div>
</div>

<?php
include "white-lable/assets/common-includes/footer-includes.php";
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>-->
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script>
    $(document).ready(function () {

        $("#feedback_error").hide();
        $("#feedback_success").hide();

        $('#form_register').validate({
            rules: {
                txt_name: {
                    required: true
                },
                txt_email: {
                    required: true,
                    email: true
                },
                txt_contact: {
                    required: true,
                    rangelength: [10, 12],
                    number: true
                },
                drp_country: {
                    required: true
                },
                state: {
                    required: true
                },
                city: {
                    required: true
                },
                txt_password: {
                    required: true
                },
                txt_con_password: {
                    required: true,
                    equalTo: "#password"
                },
                chk_terms: {
                    required: true
                }

            },
            messages: {
                name: 'Please enter Name.',
                email: {
                    required: 'Please enter Email Address.',
                    email: 'Please enter a valid Email Address.'
                },
                contact: {
                    required: 'Please enter Contact.',
                    rangelength: 'Contact should be 10 digit number.'
                },
                drp_country: {
                    required: 'please Select Country.'
                },
                state: {
                    required: 'please select State.'
                },
                city: {
                    required: 'please select State.'
                },
                password: {
                    required: 'Please enter Password.'
                },
                confirmPassword: {
                    required: 'Please enter Confirm Password.',
                    equalTo: 'Confirm Password do not match with Password.'
                },
                chk_terms: {
                    required: 'Please read and accept all term and Condition'
                }
            },
            submitHandler: function (form) {
//                form.submit();
                $.ajax({
                    type: 'POST',
                    url: 'white-lable/getData.php',
                    data: $('#form_register').serialize(),
                    beforeSend: function() {
                        $("#btn_register").text("Processing...");
                        $("#btn_register"). attr("disabled", true);
                        $("#btn_register").css("cursor", "not-allowed");
                    },
                    success: function (response) {

                        console.log(response);
                        const obj = JSON.parse(response);
                        var error = obj.error;
                        var message = obj.message;

                        if (error == false) {
                            document.getElementById("form_register").reset();
                            $('#form_register').trigger("reset");
                            $("#btn_register").text("Create Card");
                            $("#btn_register"). attr("disabled", false);
                            $("#btn_register").css("cursor", "pointer");
                            swal({
                                title: "Success!",
                                text: "Success: Registration Completed",
                                type: "success",
                                icon: "success"
                            }).then(function () {
                                window.location = "<?php echo $domain_link_name."/login" ?>";
                            });
                        } else {
                            $("#btn_register").text("Create Card");
                            $("#btn_register"). attr("disabled", false);
                            $("#btn_register").css("cursor", "pointer");
                            swal({
                                title: "Error!",
                                text: message,
                                type: "danger",
                                icon: "error",
                                showConfirmButton: true
                            })
                        }
                    }
                });
            }
        })
        ;
    })
    ;
</script>
<script src="https://cdn.jsdelivr.net/npm/jquery-simple-captcha@1.0.0/src/jquery.simpleCaptcha.min.js"></script>
<script>
    $(document).ready(function () {
        $('#btn_register').attr('disabled', 'disabled');
        $('#success').hide();
        var randomNum1;
        var randomNum2;

        //set the largeest number to display

        var maxNum = 10;
        var total;

        randomNum1 = Math.ceil(Math.random() * maxNum);
        randomNum2 = Math.ceil(Math.random() * maxNum);
        total = randomNum1 + randomNum2;

        $("#question").prepend(randomNum1 + " + " + randomNum2 + "=");

        // When users input the value

        $("#ans").keyup(function () {

            var input = $(this).val();
            var slideSpeed = 200;


            if (input == total) {
                $('#ans').css('border-color', 'green');
                $('#message').hide();
                $('#success').show();

                $('button[type=submit]').removeAttr('disabled');
                $('#success').slideDown(slideSpeed);
                $('#fail').slideUp(slideSpeed);
            }
            else {
                $('#ans').css('border-color', 'red');
                $('button[type=submit]').attr('disabled', 'disabled');
                $('#fail').slideDown(slideSpeed);
                $('#success').slideUp(slideSpeed);

            }

        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#password_verify').hide();
        $('#password').keyup(function () {
            var pswd = $(this).val();
            var strength = 1;

            if (pswd.length < 8) {
                $('#length').removeClass('validp').addClass('invalid');
                strength++;
            } else {
                $('#length').removeClass('invalid').addClass('validp');
            }
            if (pswd.match(/[A-z]/)) {
                $('#letter').removeClass('invalid').addClass('validp');
                strength++;
            } else {
                $('#letter').removeClass('validp').addClass('invalid');
            }

            //validate capital letter
            if (pswd.match(/[A-Z]/)) {
                $('#capital').removeClass('invalid').addClass('validp');
                strength++;
            } else {
                $('#capital').removeClass('validp').addClass('invalid');
            }

            //Special Character
            if (pswd.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) {
                $('#splcha').removeClass('invalid').addClass('validp');
                strength++;
            } else {
                $('#splcha').removeClass('validp').addClass('invalid');
            }

            //validate number
            if (pswd.match(/\d/)) {
                $('#number').removeClass('invalid').addClass('validp');
                strength++;

            } else {
                $('#number').removeClass('validp').addClass('invalid');
                $('#btn-submit').attr('disabled', true);
            }
            if (strength != 5) {
//                    $('#btn-submit').removeAttr('disabled');
                $('#ans').attr('disabled', 'disabled');
                $('#password_verify').show();
                $('#password').css('border-color', 'red');
            } else {
                $('#ans').removeAttr('disabled');
                $('#password_verify').hide();
                $('#password').css('border-color', '');
            }

        }).focus(function () {
            /*$('#btn-submit').attr('disabled','disabled');*/
            $('#pswd_info').show();
            $('#password_verify').hide();
        }).blur(function () {
            /*$('#btn-submit').removeAttr('disabled');*/
            $('#pswd_info').hide();
            $('#password_verify').hide();

        });
    });
</script>


<script async src="https://www.googletagmanager.com/gtag/js?id=G-ZG5Y9ZEJ2V"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-ZG5Y9ZEJ2V');
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#drp_country").val(101);
//        $("select[name=invoice_state]").val(22);
        getStateDataByCountry(101);
//        getCityByStateId(22)
    });
</script>
<script type="text/javascript">
    function getStateDataByCountry(value) {
        var dataString = 'country_id=' + value;
        if (value != '') {
            $.ajax({
                url: "white-lable/get_city_ajax.php",
                type: "POST",
                data: dataString,
                success: function (html) {
                    $('#state_select').html(html);
                    getCityByStateId($('select[name=city]').val());
                }
            });
        } else {
            $('#state_select').html(' <select name="city" id="state" class="form-control"><option value="">select an option</option></select>');
        }
    }
    function getCityByStateId(value) {

        var dataString = 'state_id=' + value;
        if (value != '') {
            $.ajax({
                url: "white-lable/get_city_ajax.php",
                type: "POST",
                data: dataString,
                success: function (html) {
                    $('#city_select').html(html);
                }
            });
        } else {
            $('#city_select').html(' <select name="city"  class="form-control"><option value="">select an option</option></select>');
        }
    }
</script>
</body>
</html>
