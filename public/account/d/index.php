<?php

error_reporting(1);
ini_set('memory_limit', '-1');
require_once "../controller/ManageDesktopCard.php";
$manage = new ManageDesktopCard();
//include_once '../sendMail/sendMail.php';
require_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
// include "../controller/validator.php";
// $validate = new Validator();
// include_once('../user/lib/ImgCompressor.class.php');
//include "../data-uri-image.php";
$error = false;
$errorMessage = "";
$error1 = false;
$errorMessage1 = "";
$Themerror = false;
$ThemerrorMessage = "";
include "assets/common-includes/all-query.php";

/*echo FULL_DESKTOP_URL . "services" . get_full_param();
exit;*/

if($ProfileSectionStatus != 1){
    $redirect = FULL_DESKTOP_URL . "services" . get_full_param();
    header('Location: '.$whatsapp_no);
    die();
}

//exit;


?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>

    <title><?php echo $name; ?> - <?php echo $designation; ?> - <?php echo $_SERVER['HTTP_HOST']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "assets/common-includes/header_includes.php"; ?>

    <link rel="stylesheet" type="text/css" href="<?php echo FULL_DESKTOP_URL; ?>assets/css/component.min.css"/>
    <?php
    if ($user_status != 1) {
        ?>
        <script>
            document.getElementsByTagName("body")[0].removeAttribute("class");
            document.getElementsByTagName("body")[0].setAttribute("class", "invaliduser");
        </script>
        <?php
    }

    ?>
   <!--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.1/tiny-slider.css">
   -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <link href="<?php echo FULL_DESKTOP_URL; ?>assets/css/colorpicker.min.css" rel="stylesheet" type="text/css">

</head>

<body class="background_body_image">
<?php
/*
echo $name;
die();*/

?>
<div class="end_sub_overlay">
    <div style="margin-top: 10%;text-align: center;"><!--class="bg-text"-->
        <img src="<?php echo FULL_DESKTOP_URL; ?>assets/images/sub.png" style="width: 40%">
    </div>
</div>
<?php
if ($user_status == 1) {
    ?>
    <section>
        <div class="digi-heading"></div>
        <div class="container">
            <div class="digi-web-main" <?php
            if ($user_expired_status) echo 'style="box-shadow: none;"'; ?>>
                <div>
                    <?php include "assets/common-includes/left_menu.php" ?><!--Left Menu-->
                    <?php
                    if(!$user_expired_status) {
                        ?>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12 bhoechie-tab-container">
                                    <div class=" col-md-2  bhoechie-tab-menu-custom">
                                        <?php include "assets/common-includes/nav_tab.php"; ?>
                                    </div>
                                    <div class=" col-md-10 bhoechie-tab margin-padding-remover">
                                        <?php
                                        /*                                if ($get_service_status != null) {
                                                                            if (isset($_GET['custom_url']) && $get_service_status['digital_card'] == 1) {
                                                                                $alreadyActiveSet = true;
                                                                                $alreadyActiveContent = true;*/
                                            include "assets/common-includes/company-info.php";
                                        /*   }
                                       }
                                       */ ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>


                <div class="sub_expired">
                    <label> Subscription has been expired. </label>
                </div>
            </div>


        </div>

    </section>

    <?php include "assets/common-includes/footer.php" ?>
    <?php

} else {
    ?>
    <section>

        <div class="container">
            <div class="inavlid-div">
                <?php
                if ($user_status == 2) {
                    ?>
                    <div>
                        <img src="<?php echo FULL_WEBSITE_URL; ?>theme/blocked.png">
                        <h4>
                            The user of this Digital card has Deactivated his/her account.
                        </h4>
                        <p>If you have any concern regarding this Digital Card user then please email us at <a
                                    href="mailto:support@sharedigitalcard.com">support@sharedigitalcard.com</a></p>
                    </div>
                    <?php
                } elseif ($user_status == 3) {
                    ?>
                    <div>
                        <img src="<?php echo FULL_WEBSITE_URL; ?>theme/trash.png">
                        <h4>
                            The user of this Digital card has Deleted his/her account.
                        </h4>
                        <p>If you have any concern regarding this Digital Card user then please email us at <a
                                    href="mailto:support@sharedigitalcard.com">support@sharedigitalcard.com</a></p>
                    </div>
                    <?php
                } elseif ($user_status == 0) {
                    ?>
                    <div>
                        <img src="<?php echo FULL_WEBSITE_URL; ?>theme/blocked.png">
                        <h4>
                            The user of this Digital card has Blocked his/her account.
                        </h4>
                        <p>If you have any concern regarding this Digital Card user then please email us at <a
                                    href="mailto:support@sharedigitalcard.com">support@sharedigitalcard.com</a></p>
                    </div>
                    <?php
                }
                ?>

            </div>
        </div>
    </section>
    <?php
}
?>
<?php

if (isset($_GET['theme']) && $_GET['theme'] == "active") {
    ?>
    <div class="theme_div">
        <!-- END OF TESTIMONIALS -->
        <form method="post" action="">
            <diV class="col-md-12">
                <div class="row">
                    <div class="col-md-8 thene_back_grey">
                        <ul class="my-theme-slider">
                            <?php
                            if ($theme_data != null) {
                                $i = 1;
                                while ($display_data = mysqli_fetch_array($theme_data)) {
                                    ?>
                                    <li>
                                        <div class="card-theme image_grid">
                                            <label>
                                                <input type="radio" name="selimg">
                                        <span class="caption">
                                            <span></span></span>
                                                <img class="img-responsive"
                                                     src="<?php echo FULL_WEBSITE_URL."theme/" . $display_data['thumb_img']; ?>"
                                                     onclick="change_background_image(<?php echo $i; ?>)"
                                                     alt="<?php echo $display_data['img_name']; ?>"
                                                     id="slider_image<?php echo $i; ?>">
                                            </label>

                                            <?php
                                            if ($user_theme == $display_data['img_name']) {
                                                ?>
                                                <div>
                                                    <div class="check_theme">
                                                        <i class="fa check fa-check" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>

                                            <!--  <p><?php /*echo $display_data['title']; */ ?></p>-->

                                        </div>
                                    </li>
                                    <?php
                                    $i++;
                                }
                            }
                            ?>

                        </ul>
                        <div class="col-md-12 text-center">
                            <div id="price"></div>
                            <button type="submit" name="btn_apply" class="btn mt-10 btn-success"><i class="fa fa-check"
                                                                                                    aria-hidden="true"></i>
                                Apply
                                Theme
                            </button>
                            <button type="button" class="btn btn-primary mt-10" data-target="#enquiryModal"
                                    data-toggle="modal">
                                <i class="fa fa-upload"></i> Upload Image
                            </button>
                            <div class="mt-10">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 theme_apply_sec">
                        <div class="input-group mb-3">
                            <label>Pick Background Color</label>
                            <input type="text" id="picker-back-colour" aria-describedby="basic-addon3" aria-label=""
                                   class="form-control textpicker picker" placeholder="Pick Background Color"
                                   autocomplete="off">
                            <button type="button" onclick="applyBackColor(this)"
                                    class="mt-10 btn form-control btn-success"
                                    id="basic-addon3"><i class="fa fa-check" aria-hidden="true"></i> Apply
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2 pick_color_div">
                        <label>Change Font Family</label>

                        <div class="dropdown">
                            <select class="form-control" onchange="body_font_family_color(this.value)">
                                <option>Select Font</option>
                                <option id="raleway-font" style="font-family: Raleway;">Raleway</option>
                                <option id="montserrat-font" style="font-family: Montserrat">Montserrat</option>
                                <option id="titillium-font" style="font-family: Titillium Web">Titillium Web</option>
                                <option id="pacifico-font" style="font-family: Pacifico">Pacifico</option>
                                <option id="josefin-slab-font" style="font-family: Josefin Slab">Josefin Slab</option>
                                <option id="orbitron-font" style="font-family: Orbitron">Orbitron</option>
                                <option id="comfortaa-font" style="font-family: Comfortaa;">Comfortaa</option>
                                <option id="courgette-font" style="font-family: Courgette;">Courgette</option>
                                <option id="ubuntu-font" style="font-family: Ubuntu;">Ubuntu</option>
                                <option id="chewy-font" style="font-family: Chewy;">Chewy</option>
                                <option id="lobster-two-font" style="font-family: Lobster Two;">Lobster Two</option>
                                <option id="kaushan-script-font" style="font-family: Kaushan Script;">Kaushan Script
                                </option>
                                <option id="economica-font" style="font-family: Economica;">Economica</option>
                                <option id="satisfy-font" style="font-family: Satisfy;">Satisfy</option>
                                <option id="FreigDisProBoo" style="font-family: FreigDisProBoo;">FreigDisProBoo</option>
                                <option id="GothamNarrow-Bold" style="font-family: GothamNarrow-Bold;"
                                        value="GothamNarrow-Bold">Gotham Narrow Bold
                                </option>
                                <option id="GothamNarrow-Bold" style="font-family: GothamNarrow-Bold;"
                                        value="GothamNarrow-Book">Gotham Narrow Book
                                </option>
                                <option id="GothamNarrow-Bold" style="font-family: Graphik-Starwood-Regular;"
                                        value="Graphik-Starwood-Regular">Graphik Starwood Regular
                                </option>
                                <option id="GothamNarrow-Bold" style="font-family: Graphik-Starwood-Semibold;"
                                        value="Graphik-Starwood-Semibold">Graphik Starwood Semibold
                                </option>
                                <option id="GothamNarrow-Bold" style="font-family: GriffithGothic-Bold;"
                                        value="GriffithGothic-Bold">Griffith Gothic Bold
                                </option>
                                <option id="GothamNarrow-Bold" style="font-family: GriffithGothic-Light;"
                                        value="GriffithGothic-Light">Griffith Gothic Light
                                </option>
                                <option id="GothamNarrow-Bold" style="font-family: GriffithGothic-Thin;"
                                        value="GriffithGothic-Thin">Griffith Gothic Thin
                                </option>
                            </select>
                            <input type="hidden" class="font_family_hidden">
                            <button type="button" onclick="applyFontFamily(this)"
                                    class=" btn text_color_btn btn-success"><i class="fa fa-check"></i> Apply
                            </button>

                        </div>

                        <!-- <p><b>Pick Text Color</b></p>-->
                        <div class="input-group mb-3 mt-10">
                            <label>Pick Text Color</label>
                            <input type="text" id="picker-text-colour" aria-describedby="basic-addon2" aria-label=""
                                   class="form-control textpicker picker" placeholder="Pick Text Color"
                                   autocomplete="off">
                            <button type="button" onclick="applyTextColor(this)" class=" btn text_color_btn btn-success"
                                    id="basic-addon2"><i class="fa fa-check" aria-hidden="true"></i> Apply
                            </button>
                        </div>
                        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
                        <script src="<?php echo FULL_DESKTOP_URL; ?>assets/css/colorpicker.js"
                                type="text/javascript"></script>
                    </div>
                </div>
            </diV>
        </form>
    </div>

    <div class="modal fade " id="enquiryModal"
         role="dialog" style="z-index: 9999">
        <div class="modal-dialog modal-md">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header service_modal_title">
                    <button type="button" class="close cust-close custom_modal_close"
                            data-dismiss="modal" style="margin-top: -4px;font-size: 30px;">&times;
                    </button>
                    <h4 class="modal-title cust-model-heading" style="font-size: 14px; color: white">Upload Theme</h4>
                </div>
                <div class="modal-body">
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
                    } ?>
                    <form method="post" action="" enctype="multipart/form-data">
                        <div>
                            <label class="form-label">Upload Image</label>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="file" name="upload[]" id="file-7"
                                           class="inputfile inputfile-6"
                                           data-multiple-caption="{count} files selected"
                                           multiple
                                           onchange="readURL(this);"
                                           accept="image/*" style="display: none"/>
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
                            </div>
                        </div>
                        <!--<div class="form-group text_box">
                            <label class="f_p text_c f_400">Theme Name</label>
                            <input type="text" placeholder="Enter theme name" class="form-control" name="txt_title">
                        </div>-->

                        <!-- start -->
                        <!-- end -->
                        <div class="action_btn text-center mt_15">
                            <button class="btn_hover btn btn-info app_btn" type="submit" name="upload_theme">Upload</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        var master = $('p,h1,h2,h3,h4,h5,h6,a,span,.text-color-p');
        var input_family_hidden = $('.font_family_hidden');
        function body_font_family_color(val){
            master.css("font-family",val);
            input_family_hidden.val(val);
        }
        function applyFontFamily(val){
            var dataString = "change_font_family="+encodeURIComponent(input_family_hidden.val());
            $.ajax({
                type: "POST",
                url: "<?php echo FULL_WEBSITE_URL; ?>review-flyout-ajax.php", // Name of the php files
                data: dataString+custom_url,
                beforeSend: function () {
                    $(val).text('Applying...').attr("disabled", 'disabled');
                },
                success: function (html) {
                    $(val).text('Applied').removeAttr("disabled");
                }
            });
        }
    </script>
    <script>
        function change_background_image(id) {
            var img = document.getElementById('slider_image' + id);
            var d = img.getAttribute("alt");
            $('.content-main').css("background-image", "url(<?php echo FULL_WEBSITE_URL ?>theme/" + d + ")");
            var dataString = "background_image=" + d;
            $.ajax({
                type: "POST",
                url: "<?php echo FULL_DESKTOP_URL; ?>theme-change.php", // Name of the php files
                data: dataString,
                success: function (html) {
                    $("#price").html(html);
                    $("#price").css({"display": "none"});
                }
            });
        }
    </script>
    <script>
        function applyTextColor(val) {
            var color = $('#picker-text-colour').val();
            var dataString = "text_color=" + color + "&user_id=" +<?php echo $user_id; ?>;
            $.ajax({
                type: "POST",
                url: "<?php echo FULL_WEBSITE_URL; ?>review-flyout-ajax.php", // Name of the php files
                data: dataString,
                beforeSend: function () {
                    $(val).text('Applying...').attr("disabled", 'disabled');
                },
                success: function (html) {
                    $(val).text('Applied').removeAttr("disabled");
                }
            });
        }
        var custom_url = '&custom_url=<?php echo $_GET['custom_url'] ?>';
        function applyBackColor(val) {
            var color = $('#picker-back-colour').val();
            var dataString = "back_color=" + color;
            $.ajax({
                type: "POST",
                url: "<?php echo FULL_WEBSITE_URL; ?>review-flyout-ajax.php", // Name of the php files
                data: dataString+custom_url,
                beforeSend: function () {
                    $(val).text('Applying...').attr("disabled", 'disabled');
                },
                success: function (html) {
                    $(val).text('Applied').removeAttr("disabled");
                }
            });
        }
    </script>
<?php
}
?>
<script>
    function successMessage(text) {
        Swal.fire({
            showConfirmButton: false,
            title: '<strong>Success!</strong>',
            icon: 'success',
            html:
                '<p>' + text + '</p>',
            showCloseButton: true,
            focusConfirm: false
        })
    }

    function dangerMessage(text) {
        Swal.fire({
            showConfirmButton: false,
            title: '<strong>Warning!</strong>',
            icon: 'warning',
            html:
                '<p>' + text + '</p>',
            showCloseButton: true,
            focusConfirm: false
        })
    }

</script>
<?php
if ($error && $errorMessage != "") {
    ?>
    <script>
        $(document).ready(function () {
            $('#enquiryModal').modal('show');
            //dangerMessage('<?php //echo $errorMessage; ?>//');
        });

    </script>
<?php
}elseif (!$error && $errorMessage != "") {

?>
    <script>
        $(document).ready(function () {
            successMessage('<?php echo $errorMessage; ?>');
        });
    </script>
    <?php
}
?>


<script>
    $(document).ready(function () {
        if ($('#blah').attr('src') == "" || $('#blah').attr('src') == "unknown") {
            $('#blah').hide();
        }
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.blah')
                    .attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
            $('#blah').show();
        }
    }
</script>
<script type="text/javascript">
    $('.list-group-item').on('click', function () {
        return false;
    });
    $(document).bind("contextmenu", function (e) {
        e.preventDefault();
    });
    $(document).keydown(function (e) {
        if (e.which === 123) {
            return false;
        }
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
<script type="text/javascript">
    var rowCount1 = 1;

    function addMoreRows1(frm) {
        rowCount1++;
        /*var recRow = '<p id="rowCount1'+rowCount1+'" ><tr><td><div class="input-group" style="display: flex"><span class="input-group-addon" style="width: 49px;">+91</span> <input name="contact_no" type="number" class="form-control" placeholder="Enter Number" required="required"/> &nbsp;&nbsp;</div></td></tr><a href="javascript:void(0);" onclick="removeRow('+rowCount1+');"><i class="fa fa-times plus_icon" aria-hidden="true"></i></a></p>';*/
        var recRow = '<p style="display:flex" id="rowCount1' + rowCount1 + '"><tr><td style="display: table; position: relative;border-collapse: separate"><span class="input-group-addon" style="width: 49px;"><i class="glyphicon glyphicon-envelope"></i></span> <input name="txt_email[]" type="email" class="form-control" autofocus placeholder="Enter Email" required="required"/></td></tr>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="removeRow1(' + rowCount1 + ');"><i class="fa fa-minus plus_icon" aria-hidden="true"></i></a></p>';
        jQuery('#addedRows1').append(recRow);
    }

    function removeRow1(removeNum) {
        jQuery('#rowCount1' + removeNum).remove();
    }
</script>

<script>
    function emailDiv() {
        document.getElementById('emailDiv').style.display = "block";
        document.getElementById('smsDiv').style.display = "none";
    }

    function smsDiv() {
        document.getElementById('emailDiv').style.display = "none";
        document.getElementById('smsDiv').style.display = "block";
    }
</script>

<!--<script type="text/javascript">
    if (screen.width <= 768 || screen.height == 480) //if 1024x768
        window.location.replace("../<?php /*if(isset($_GET['custom_url'])) echo $_GET['custom_url'];*/ ?>")
</script>-->


<?php include "assets/common-includes/footer_includes.php" ?>


<?php /*include "../assets/common-includes/mobile-desktop-url-changer.php" */ ?>
</body>
</html>
