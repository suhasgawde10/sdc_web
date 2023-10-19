<?php
error_reporting(1);
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

$getDataCard = $manage->getSpecificCardDealerCardData($id);
//var_dump($getDataCard);

$api_key = "6fb9fa56-a66e-490b-a8dd-ad6a37e65f62";
$result = "$url,$api_key";
//echo "<br>";
$token = $security->encryptWebservice($result);
// echo $token;
// die();
$postRequest = array(
    'token' => $token
);
$cURLConnection = curl_init('https://sharedigitalcard.com/SDCDealerCardDisplay');
curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($cURLConnection);
/*print_r($json);
exit;*/
curl_close($cURLConnection);

$data = json_decode($json, true);
//var_dump($data);
$error_data = $data['error'];
$massage = $data['message'];
$cards = $data['card'];
$cards2 = $data['card'];
// use get variable to paging number
$page = !isset($_GET['page']) ? 1 : $_GET['page'];
$limit = 24;

$offset = ($page - 1) * $limit; // offset

/*echo "Loading...";
var_dump($cards);
die();
*/

if($error_data==false){
$total_items = 0; // total items
$total_pages = 0;
$final = null;
}
else{
$total_items = count($cards); // total items
$total_pages = ceil($total_items / $limit);
//$final = array_splice($cards2, $offset, $limit); // splice them according to offset and limit
$final = $cards2;
}



?>

<!DOCTYPE html>
<html>
<head>
    <title>Sample digital business cards - <?php echo strtoupper($company_name); ?> </title>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="DGINDIA" name="keywords">
    <!-- Favicons -->
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <style>
        .gary-img img {
            filter: grayscale(1);
            cursor: not-allowed;
        }

        .text-center {
            text-align: center !important;
        }

        .no_data_found img {
            width: 30%;
            margin-bottom: 3%;
        }

        span a {
            color: #000;
        }

        span a:hover {
            text-decoration: none;
        }

        .table > :not(caption) > * > * {
            padding: 0 !important;
            border-bottom-width: 0px !important;
            box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
        }

        .table td, .table th {
            padding: .75rem;
            vertical-align: top;
            border-top: none !important;
        }

        @import url("https://fonts.googleapis.com/css?family=Quicksand:400,500,700&subset=latin-ext");

        a,
        a:hover {
            text-decoration: none;
        }

        .profile-card {
            width: 100%;
            min-height: 380px;
            box-shadow: 0 3px 6px rgb(0 0 0 / 16%), 0 3px 6px rgb(0 0 0 / 23%);
            background: #fff;
            border-radius: 8px;
            max-width: 700px;
            position: relative;
            margin-bottom: 20px;
        }

        .profile-card.active .profile-card__cnt {
            filter: blur(6px);
        }

        .profile-card.active .profile-card-message,
        .profile-card.active .profile-card__overlay {
            opacity: 1;
            pointer-events: auto;
            transition-delay: 0.1s;
        }

        .profile-card.active .profile-card-form {
            transform: none;
            transition-delay: 0.1s;
        }

        .profile-card__img {
            padding: 25px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
        }

        @media screen and (max-width: 576px) {
            .profile-card__img {
                margin: 0 auto;
                text-align: center;
                width: 150px;
                height: 150px;
            }
        }

        .profile-card__img img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .profile-card__cnt {
            padding: 13px;
            margin-top: 10px;
            /* padding: 0px; */
            /* padding-bottom: 40px; */
            transition: all 0.3s;
        }

        .text-name a {
            color: #000;
        }

        .text-name a:hover {
            text-decoration: none;
            color: #000;
        }

        .profile-card__name {
            font-weight: 600;
            font-size: 20px;
            color: #000;
            /* margin-bottom: 15px;*/
        }

        .profile-card__name a {
            color: #000;
        }

        .profile-card__name a:hover {
            text-decoration: none;
            color: #000;
        }

        .profile-card-social {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }

        .profile-card-social .icon-font {
            display: inline-flex;
        }

        span i {
            color: #BD0A2B;
            padding: 5px;
        }
    </style>

    <!-- Vendor CSS Files -->
    <?php include "white-lable/assets/common-includes/header-includes.php"; ?>


</head>
<body>
<?php include "white-lable/assets/common-includes/header.php"; ?>
<section class="breadcrumbs" style="margin-top: 97px">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center">
            <h2>Demo Card</h2>
            <ol>
                <li><a href="index.php">Home</a></li>
                <li>Demo Card</li>
            </ol>
        </div>

    </div>
</section>
<?php if ($card_status == 1) { ?>
    <section class="inner-page card_section">
        <div class="container-fluid">
            <div class="col-lg-12">
                <div class="row">
                    <ul class="ul_card_profile">
                        <?php
                        if ($error_data == true && $massage != 'No Record Found') {
                            
                            foreach ($final as $card) {
                                $images = $security->decrypt($card['img_name']);
                                $name = $security->decrypt($card['name']);
                                $custom_url = $security->decrypt($card['custom_url']);
                                $designation = $security->decrypt($card['designation']);
                                $alter_num = $security->decrypt($card['altr_contact_no']);
                                $website = $security->decrypt($card['website_url']);
                                $linkdin_link = $security->decrypt($card['linked_in']);
                                $youtube_link = $security->decrypt($card['youtube']);
                                $facebook_link = $security->decrypt($card['facebook']);
                                $twitter_link = $security->decrypt($card['twitter']);
                                $instagram_link = $security->decrypt($card['instagram']);
                                $map_link = $security->decrypt($card['map_link']);
                                $address = $security->decrypt($card['address']);
                                $keyword = $security->decrypt($card['user_keyword']);
                                $card_company_name = $security->decrypt($card['company_name']);
                                $playstore_url = $security->decrypt($card['playstore_url']);
                                $whatsapp_no = $security->decrypt($card['whatsapp_no']);
                                $business_category = $security->decrypt($card['business_category']);
                                $company_logo = $security->decrypt($card['company_logo']);
                                $landline_number = $security->decrypt($card['landline_number']);
                                $domain_link = $security->decrypt($card['dg_domain_link']);
                                $email_id = $security->decrypt($card['email']);
                                ?>

                                <li class="li_card_profile">
                                    <div class="profile-card js-profile-card">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="profile-card__img">
                                                    <a href="<?php echo $domain_link_name . "/" . $custom_url; ?>" target="_blank">
                                                        <img
                                                            src="https://sharedigitalcard.com/user/uploads/<?php echo $email_id . "/profile/" . $images ?>"
                                                            alt="profile card">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="profile-card__cnt js-profile-cnt">
                                                    <div class="text-name">
                                                        <a href="<?php echo $domain_link_name . "/" . $custom_url; ?>" target="_blank"><?php echo $name ?></a>
                                                    </div>
                                                    <div class="profile-card__name">
                                                        <a href="<?php echo $domain_link_name . "/" . $custom_url; ?>" target="_blank">

                                                            <?php if ($designation != "") {
                                                                echo $designation . " - ";
                                                            }
                                                            echo $card_company_name ?></a>
                                                    </div>
                                                    <div class="text-name">
                                                        <a href="<?php echo $domain_link_name . "/" . $custom_url; ?>"
                                                        target="_blank" style="font-size: 14px;letter-spacing: 2px"><?php echo $business_category ?></a>
                                                    </div>


                                                    <div class="profile-card-social">
                                                        <?php
                                                        if ($youtube_link != "") { ?>
                                                            <a href="<?php echo $youtube_link ?>"
                                                               class="" target="_blank">
                                                                <img src="white-lable/assets/img/logo/youtube.png"
                                                                     style="width: 55px">
                                                            </a>
                                                        <?php
                                                        } else {
                                                            ?>
                                                            <a href="#"
                                                               class="gary-img">
                                                                <img src="white-lable/assets/img/logo/youtube.png"
                                                                     style="width: 55px">
                                                            </a>
                                                        <?php
                                                        }
                                                        ?>
                                                        <?php if ($facebook_link != "") {
                                                            ?>
                                                            <a href="<?php echo $facebook_link ?>"
                                                               class="" target="_blank">
                                                                <img src="white-lable/assets/img/logo/facebook.png"
                                                                     style="width: 55px">
                                                            </a>
                                                        <?php
                                                        } else {
                                                            ?>
                                                            <a href="#"
                                                               class="gary-img" target="_blank">
                                                                <img src="white-lable/assets/img/logo/facebook.png"
                                                                     style="width: 55px">
                                                            </a>
                                                        <?php

                                                        } ?>

                                                        <?php if ($twitter_link != "") { ?>
                                                            <a href="<?php echo $twitter_link ?>"
                                                               class="" target="_blank">
                                                                <img src="white-lable/assets/img/logo/twitter.png"
                                                                     style="width: 55px">
                                                            </a>
                                                        <?php
                                                        } else {
                                                            ?>
                                                            <a href="#"
                                                               class="gary-img" target="_blank">
                                                                <img src="white-lable/assets/img/logo/twitter.png"
                                                                     style="width: 55px">
                                                            </a>
                                                        <?php
                                                        } ?>

                                                        <?php if ($instagram_link != "") { ?>
                                                            <a href="<?php echo $instagram_link ?>"
                                                               class="" target="_blank">
                                                                <img src="white-lable/assets/img/logo/instagram%20(2).png"
                                                                     style="width: 55px">
                                                            </a>
                                                        <?php
                                                        } else {
                                                            ?>
                                                            <a href="#"
                                                               class="gary-img" target="_blank">
                                                                <img src="white-lable/assets/img/logo/instagram%20(2).png"
                                                                     style="width: 55px">
                                                            </a>
                                                        <?php
                                                        } ?>


                                                        <?php if ($linkdin_link != "") { ?>
                                                            <a href="<?php echo $linkdin_link ?>"
                                                               class="" target="_blank">
                                                                <img src="white-lable/assets/img/logo/linkdln.png"
                                                                     style="width: 55px">
                                                            </a>
                                                        <?php
                                                        } else {
                                                            ?>
                                                            <a href="#"
                                                               class="gary-img" target="_blank">
                                                                <img src="white-lable/assets/img/logo/linkdln.png"
                                                                     style="width: 55px">
                                                            </a>
                                                        <?php
                                                        } ?>

                                                        <?php if ($website != "") { ?>
                                                            <a href="<?php echo $website ?>"
                                                               class="" target="_blank">
                                                                <img src="white-lable/assets/img/logo/play.png" style="width: 50px">
                                                            </a>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="main-info mt-10">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                    <span class="cont-list">
                                                        <a href="tel:<?php echo $whatsapp_no ?>">
                                                            <i class="fa fa-phone"></i> <?php echo $whatsapp_no ?>
                                                        </a>
                                                        <?php if ($alter_num != "") {
                                                            ?>
                                                            <a href="tel:<?php echo $alter_num ?>"><?php echo " / " . $alter_num; ?></a>
                                                        <?php
                                                        } ?>
                                                    </span><br>
                                                    <span>
                                                        <a href="mailto:<?php echo $email_id ?>">
                                                            <i class="fa fa-at"></i> <?php if ($email_id != "") {
                                                                echo $email_id;
                                                            } else {
                                                                echo "Email Not Available";
                                                            } ?>
                                                        </a>

                                                    </span><br>
                                                    <span>
                                                        <a href="">
                                                            <i class="fa fa-globe"></i>
                                                            <?php if ($website != "") {
                                                                echo $website;
                                                            } else {
                                                                echo "Website not available";
                                                            } ?>
                                                        </a>
                                                    </span>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <table border="0" class="table tabl_info">
                                                                <tr>
                                                                    <td>
                                                                <span><a
                                                                        href="<?php echo $domain_link_name . "/" . $custom_url; ?>" target="_blank">
                                                                        <i class="fa fa-phone"></i> About US
                                                                    </a>
                                                                </span>
                                                                    </td>
                                                                    <td>
                                                                <span><a
                                                                        href="<?php echo $domain_link_name . "/" . $custom_url; ?>" target="_blank">
                                                                        <i class="fa fa-eye"></i> Our Mission
                                                                    </a>
                                                                </span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                <span>
                                                                    <a href="<?php echo $domain_link_name . "/" . $custom_url; ?>" target="_blank">
                                                                        <i class="fa fa-file-pdf-o"></i> Company Profile
                                                                    </a>
                                                                </span>
                                                                    </td>
                                                                    <td>
                                                                <span>
                                                                    <a href="<?php echo $domain_link_name . "/" . $custom_url; ?>" target="_blank">
                                                                        <i class="fa fa-cog"></i> Our Services
                                                                    </a>
                                                                </span>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="cut-padding-left-5 cata-sub-nav">
                                                        <div class="nav-prev arrow" style="cursor: pointer">
                                                            <i class="fa fa-angle-left" aria-hidden="true"></i>
                                                        </div>
                                                        <ul class="tags">
                                                            <?php
                                                            $Lists = explode(', ', $keyword);
                                                            foreach ($Lists as $List) {
                                                                ?>
                                                                <li>
                                                                    <a onclick="searchKey('<?php echo $List ?>')"
                                                                       href="javascript:void(0)"><?php echo $List ?></a>
                                                                </li>
                                                            <?php
                                                            }
                                                            ?>
                                                            <!-- <li>
                                                                 <a onclick="searchKey('Women  hair cut One length')"
                                                                    href="javascript:void(0)">Women  hair cut One length</a>
                                                             </li>-->

                                                        </ul>
                                                        <div class="nav-next arrow" style="cursor: pointer">
                                                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php

                            }
                        } else {
                            ?>
                            <div class="text-center no_data_found">
                                <img src="white-lable/assets/img/notfound_content.jpg" class="not-found-img mb-30">
                                <h5>No record found!!</h5>
                            </div>
                        <?php
                        }
                        ?>

                    </ul>

                </div>


                <div style="" class="pagination-card">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <?php


                            if (!isset($_GET['page'])) { ?>
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Previous"
                                       style="pointer-events: none;cursor: not-allowed;">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                            <?php } elseif ($_GET['page'] == 1) {
                                ?>
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Previous"
                                       style="pointer-events: none;cursor: not-allowed;">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                            <?php

                            } else {
                                ?>
                                <li class="page-item">
                                    <a class="page-link" href="card.php?page=<?php echo $_GET['page'] - 1 ?>"
                                       aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                            <?php
                            }
                            ?>


                            <?php
                            for ($x = 1; $x <= $total_pages; $x++):?>
                                <li class="page-item">
                                    <a class="page-link" href="card.php?page=<?php echo $x; ?>"><?php echo $x; ?></a>

                                </li>

                            <?php endfor; ?>

                            <?php
                            if ($_GET['page']) { ?>
                                <li class="page-item">
                                    <a class="page-link" href="card.php?page=<?php echo $_GET['page'] + 1; ?>"
                                       aria-label="Next">
                                        <span aria-hidden="true">&raquo;<?php echo $max ?></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            <?php } elseif (!$_GET['page']) {
                                ?>
                                <li class="page-item">
                                    <a class="page-link" href="card.php?page=<?php echo $_GET['page'] + 1; ?>"
                                       aria-label="Next">
                                        <span aria-hidden="true">&raquo;<?php echo $max ?></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            <?php
                            } ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>
<?php } else {
    ?>
    <section class="inner-page card_section">
        <div class="container">
            <div class="col-lg-12">
                <div class="row">

                    <?php if ($getDataCard != "") {
                        while ($rowDatacard = mysqli_fetch_array($getDataCard)) {
                            ?>
                            <div class="col-lg-3 col-md-3">
                                <div class="crad-v">
                                    <div class="row">
                                        <div class="col-lg-12 col-12 text-center">
                                            <div class="op">
                                                <strong class="font-bolder-new"><?php echo $rowDatacard['card_name'] ?></strong>
                                            </div>
                                            <div class="view-card">
                                                <a target="_blank" href="<?php echo $rowDatacard['card_link'] ?>" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> View card
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        <?php
                        }
                    }
                    ?>


                </div>
            </div>
    </section>
<?php
} ?>




<?php include "white-lable/assets/common-includes/footer.php"; ?>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

<div id="preloader">
    <div class="loder-img">
        <!--        <img src="panel/uploads/logo/--><?php //echo $logo ?><!--">-->
    </div>
</div>

<?php
include "white-lable/assets/common-includes/footer-includes.php";
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>-->
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>


<script async src="https://www.googletagmanager.com/gtag/js?id=G-ZG5Y9ZEJ2V"></script>

<script>
    window.dataLayer = window.dataLayer || [];
    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-ZG5Y9ZEJ2V');
</script>
<script>
    (function ($) {
        // console.log( 'init-scroll: ' + $(".nav-next").scrollLeft() );
        $(".nav-next").on("click", function () {
            $(this).closest('div.cata-sub-nav').animate({scrollLeft: '+=460'}, 200);
            $val = $(this).closest('div.cata-sub-nav').scrollLeft();
            if ($(this).closest('div.cata-sub-nav').scrollLeft() + $(this).closest('div.cata-sub-nav').innerWidth() >= $(this).closest('div.cata-sub-nav')[0].scrollWidth) {
                $(this).hide();
            } else {
                $(this).show();
            }

            if ($val == 0) {
                $('.nav-prev').hide();
            } else {
                $('.nav-prev').show();
            }

        });
        $(".nav-prev").on("click", function () {
            $(this).closest('div.cata-sub-nav').animate({scrollLeft: '-=460'}, 200)
            $val = $(this).closest('div.cata-sub-nav').scrollLeft();

            if ($(this).closest('div.cata-sub-nav').scrollLeft() + $(this).closest('div.cata-sub-nav').innerWidth() >= $(this).closest('div.cata-sub-nav')[0].scrollWidth) {
                $(this).hide();
            } else {
                $(this).show();
            }

            if ($val == 0) {
                $('.nav-next').hide();
            } else {
                $('.nav-next').show();
            }

        });

    })(jQuery);
</script>
<!--<script>

    var books = [
        { "title": "Professional JavaScript", "author": "Nicholas C. Zakas" },
        { "title": "JavaScript: The Definitive Guide", "author": "David Flanagan" },
        { "title": "High Performance JavaScript", "author": "Nicholas C. Zakas" }
    ];
</script>-->
</body>
</html>
