<?php

require_once "../controller/ManageDesktopCard.php";
$manage = new ManageDesktopCard();
include_once '../sendMail/sendMail.php';
require_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../data-uri-image.php";
$error = false;
$errorMessage = "";
$error1 = false;
$errorMessage1 = "";
$Themerror = false;
$ThemerrorMessage = "";
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $link = "https";
else
    $link = "http";
$link .= "://";
$link = "";
$link .= $_SERVER['HTTP_HOST'];

$link .= $_SERVER['REQUEST_URI'];


include "assets/common-includes/all-query.php";

if($ClientSectionStatus != 1){
    $redirect = FULL_DESKTOP_URL . "our-team" . get_full_param();
    header('Location: '.$redirect);
    die();
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>

    <title>Testimonial - <?php echo $name; ?> - <?php echo $designation; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php include "assets/common-includes/header_includes.php" ?>
    <style id="STYLE_3">
        ._G ._Oi, ._qd ._Ji {
            display: none;
            height: 15px
        }

        ._G:hover ._Oi, ._qd:hover ._Ji {
            display: inline-block
        }

        ._pxg span {
            background-repeat: repeat-x;
            display: block
        }

        ._sxg ._pxg, ._pxg._Esh {
            background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAQAAAADQ4RFAAAA6klEQVR4AZXQMWsCMRiH8SAnQacODgpyg8rh1EEQHXS5xaUdXA5KRUHo+/2/wdN3aBNK34TEZ0rCD86/S/140ZydI9WrVo3etUrk+dJ8Hdog2qYO9YjW16ARD0R7MCpHC+SnRTk6BHQoR0NAg43WvP1LYsbrWh0tN6SwG+3v53n6ItLj//6nFfcsuLOyhphwSZILk/R6nUm6/OQzE83yaGeiXR5dTXTNoSmSaJpGWyQ0aBLaplGc/EijHePkKdTwRLQP5uFurifRnjQ2ahHtzBhHbKw3orU2OvHJEme01JeTjfZ4XCLPPp6+AYsy7RMdMSvnAAAAAElFTkSuQmCC)
        }

        ._sxg ._pxg span, ._pxg._Esh span {
            background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAQAAAADQ4RFAAAA9klEQVR4AZXUoY7iUABG4SYYEgwYwhOsx4MlqUaAQ/AGMxqHIUHDC4DnATaMx7MORVAEh5vAtzUN7M69TXuOurc5SfuLJjH8ykzCJmJ++qgefWVWjJq+M5vVojEYV4s2YFMlqrmBm1r5qC+nXz5ayFmUj/7IOYajkd//uffO/sfzUdZJXZTlIs1fr2WrDFutf79p6KqIq2FoiLadGDvt+HoTISbFk3eF6BZHMyFmxdFBiENR1PEU4qkTj6ZeHDNfTONRPvnDUj1z6ZFPHovq7uCkJ7/rOYG7ejhKwVrD+23DGqThaOVsIAk4cLYKR3NN8b/T/HX6C7jRb/QEnjPPAAAAAElFTkSuQmCC)
        }

        ._Jxg {
            background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAANCAQAAAAz1Zf0AAAAWklEQVR4AY3O0QbDQBCG0UNYQi0hhBJqr8Iy7/94vewYlp65/Ay//4WlLnQLt3BbeIRH5jBFPVMHmlHS0CRnSqdiT3GH1edb8RGmoy4GwrBhM4Qmebn8XDrwBW7xChrojlOZAAAAAElFTkSuQmCC);
        }

        ._Jxg span {
            background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAANCAYAAACZ3F9/AAAAcklEQVQoz2NgoDZ4Xij9nxxNASCNIJpUjQugGheQqvEBVOMDfIoSgPg9VCEh/B7F+UCOAhDvJ6AJJK+Ay/Z8HJryCfnNAIdGA0IaC3BonEBI4wakQIgH4vsEQxeqERYIAlC+AFKg4QwYByCuxyFXj56KAEHuodjGnEtTAAAAAElFTkSuQmCC);
        }

        ._Jxg, ._Jxg span {
            background-size: 14px 13px;
            height: 13px;
            width: 69px
        }

        #STYLE_3 {
            cursor: default;
            text-align: left;

        }

        #IMG_10 {
            color: rgb(26, 13, 171);
            cursor: pointer;
            display: block;
            float: left;
            height: 40px;
            text-align: left;
            width: 40px;
            perspective-origin: 20px 20px;
            transform-origin: 20px 20px;
            border: 0px none rgb(26, 13, 171);
            border-radius: 50% 50% 50% 50%;
            margin: 0px 5px 0px 0px;
            outline: rgb(26, 13, 171) none 0px;
        }

        /*#IMG_10, #IMG_32, #IMG_60, #IMG_79, #IMG_97, #IMG_115, #IMG_137, #IMG_173, #IMG_191, #IMG_209, #IMG_236, #IMG_259, #IMG_277, #IMG_295, #IMG_313, #IMG_342, #IMG_364, #IMG_388, #IMG_406, #IMG_424, #IMG_451, #IMG_469, #IMG_487, #IMG_505, #IMG_523, #IMG_541, #IMG_559, #IMG_577, #IMG_595, #IMG_613, #IMG_635, #IMG_663, #IMG_685, #IMG_703, #IMG_725, #IMG_747, #IMG_769, #IMG_791, #IMG_813, #IMG_835, #IMG_862, #IMG_884, #IMG_906, #IMG_925, #IMG_951, #IMG_973, #IMG_991, #IMG_1013, #IMG_1031, #IMG_1049, #IMG_1072, #IMG_1090, #IMG_1108, #IMG_1126, #IMG_1144, #IMG_1163, #IMG_1181, #IMG_1199, #IMG_1217, #IMG_1235, #IMG_1258, #IMG_1280, #IMG_1298, #IMG_1316, #IMG_1334, #IMG_1352, #IMG_1370, #IMG_1388, #IMG_1406, #IMG_1424, #IMG_1447, #IMG_1465, #IMG_1483, #IMG_1501, #IMG_1519, #IMG_1537, #IMG_1555, #IMG_1572, #IMG_1590, #IMG_1608, #IMG_1631, #IMG_1649, #IMG_1667, #IMG_1685, #IMG_1703, #IMG_1721, #IMG_1739, #IMG_1757, #IMG_1774, #IMG_1792, #IMG_1815, #IMG_1833, #IMG_1851, #IMG_1869, #IMG_1887, #IMG_1905, #IMG_1923, #IMG_1941, #IMG_1959, #IMG_1977, #IMG_2000, #IMG_2018, #IMG_2036, #IMG_2054, #IMG_2072, #IMG_2090, #IMG_2108, #IMG_2126, #IMG_2144, #IMG_2162, #IMG_2185, #IMG_2203, #IMG_2221, #IMG_2239, #IMG_2257, #IMG_2275, #IMG_2293, #IMG_2311, #IMG_2329, #IMG_2347, #IMG_2370, #IMG_2388, #IMG_2406*/

        #DIV_11{
            cursor: default;
            height: auto;
            text-align: left;
            vertical-align: middle;
            width: 100%;
            perspective-origin: 324.5px 47px;
            transform-origin: 324.5px 47px;
            padding: 0px 0px 0px 47px;
        }


        #DIV_12 {
            cursor: default;
            height: 15px;
            text-align: left;
            width: 596px;
            perspective-origin: 301px 7.5px;
            transform-origin: 301px 7.5px;
            padding: 0px 6px 0px 0px;
        }

        /*#DIV_12, #DIV_34, #DIV_62, #DIV_81, #DIV_99, #DIV_117, #DIV_139, #DIV_175, #DIV_193, #DIV_211, #DIV_238, #DIV_261, #DIV_279, #DIV_297, #DIV_315, #DIV_344, #DIV_366, #DIV_390, #DIV_408, #DIV_426, #DIV_453, #DIV_471, #DIV_489, #DIV_507, #DIV_525, #DIV_543, #DIV_561, #DIV_579, #DIV_597, #DIV_615, #DIV_637, #DIV_665, #DIV_687, #DIV_705, #DIV_727, #DIV_749, #DIV_771, #DIV_793, #DIV_815, #DIV_837, #DIV_864, #DIV_886, #DIV_908, #DIV_927, #DIV_953, #DIV_975, #DIV_993, #DIV_1015, #DIV_1033, #DIV_1051, #DIV_1074, #DIV_1092, #DIV_1110, #DIV_1128, #DIV_1146, #DIV_1165, #DIV_1183, #DIV_1201, #DIV_1219, #DIV_1237, #DIV_1260, #DIV_1282, #DIV_1300, #DIV_1318, #DIV_1336, #DIV_1354, #DIV_1372, #DIV_1390, #DIV_1408, #DIV_1426, #DIV_1449, #DIV_1467, #DIV_1485, #DIV_1503, #DIV_1521, #DIV_1539, #DIV_1557, #DIV_1574, #DIV_1592, #DIV_1610, #DIV_1633, #DIV_1651, #DIV_1669, #DIV_1687, #DIV_1705, #DIV_1723, #DIV_1741, #DIV_1759, #DIV_1776, #DIV_1794, #DIV_1817, #DIV_1835, #DIV_1853, #DIV_1871, #DIV_1889, #DIV_1907, #DIV_1925, #DIV_1943, #DIV_1961, #DIV_1979, #DIV_2002, #DIV_2020, #DIV_2038, #DIV_2056, #DIV_2074, #DIV_2092, #DIV_2110, #DIV_2128, #DIV_2146, #DIV_2164, #DIV_2187, #DIV_2205, #DIV_2223, #DIV_2241, #DIV_2259, #DIV_2277, #DIV_2295, #DIV_2313, #DIV_2331, #DIV_2349, #DIV_2372, #DIV_2390, #DIV_2408*/

        #A_13 {
            cursor: pointer;
            text-align: left;
            text-decoration: none;
            border: 0px none rgb(0, 0, 0);

            outline: rgb(0, 0, 0) none 0px;
        }

        /*#A_13, #A_35, #A_63, #A_82, #A_100, #A_118, #A_140, #A_176, #A_194, #A_212, #A_239, #A_262, #A_280, #A_298, #A_316, #A_345, #A_367, #A_391, #A_409, #A_427, #A_454, #A_472, #A_490, #A_508, #A_526, #A_544, #A_562, #A_580, #A_598, #A_616, #A_638, #A_666, #A_688, #A_706, #A_728, #A_750, #A_772, #A_794, #A_816, #A_838, #A_865, #A_887, #A_909, #A_928, #A_954, #A_976, #A_994, #A_1016, #A_1034, #A_1052, #A_1075, #A_1093, #A_1111, #A_1129, #A_1147, #A_1166, #A_1184, #A_1202, #A_1220, #A_1238, #A_1261, #A_1283, #A_1301, #A_1319, #A_1337, #A_1355, #A_1373, #A_1391, #A_1409, #A_1427, #A_1450, #A_1468, #A_1486, #A_1504, #A_1522, #A_1540, #A_1558, #A_1575, #A_1593, #A_1611, #A_1634, #A_1652, #A_1670, #A_1688, #A_1706, #A_1724, #A_1742, #A_1760, #A_1777, #A_1795, #A_1818, #A_1836, #A_1854, #A_1872, #A_1890, #A_1908, #A_1926, #A_1944, #A_1962, #A_1980, #A_2003, #A_2021, #A_2039, #A_2057, #A_2075, #A_2093, #A_2111, #A_2129, #A_2147, #A_2165, #A_2188, #A_2206, #A_2224, #A_2242, #A_2260, #A_2278, #A_2296, #A_2314, #A_2332, #A_2350, #A_2373, #A_2391, #A_2409*/

        #DIV_14 {
            cursor: default;
            height: 15px;
            text-align: left;
            width: 602px;
            perspective-origin: 301px 12.5px;
            transform-origin: 301px 12.5px;
            font-size: 13px;
            padding: 2px 0px 8px;
        }

        /*#DIV_14, #DIV_36, #DIV_64, #DIV_83, #DIV_101, #DIV_119, #DIV_141, #DIV_177, #DIV_195, #DIV_213, #DIV_240, #DIV_263, #DIV_281, #DIV_299, #DIV_317, #DIV_346, #DIV_368, #DIV_392, #DIV_410, #DIV_428, #DIV_455, #DIV_473, #DIV_491, #DIV_509, #DIV_527, #DIV_545, #DIV_563, #DIV_581, #DIV_599, #DIV_617, #DIV_639, #DIV_667, #DIV_689, #DIV_707, #DIV_729, #DIV_751, #DIV_773, #DIV_795, #DIV_817, #DIV_839, #DIV_866, #DIV_888, #DIV_910, #DIV_929, #DIV_955, #DIV_977, #DIV_995, #DIV_1017, #DIV_1035, #DIV_1053, #DIV_1076, #DIV_1094, #DIV_1112, #DIV_1130, #DIV_1148, #DIV_1167, #DIV_1185, #DIV_1203, #DIV_1221, #DIV_1239, #DIV_1262, #DIV_1284, #DIV_1302, #DIV_1320, #DIV_1338, #DIV_1356, #DIV_1374, #DIV_1392, #DIV_1410, #DIV_1428, #DIV_1451, #DIV_1469, #DIV_1487, #DIV_1505, #DIV_1523, #DIV_1541, #DIV_1559, #DIV_1576, #DIV_1594, #DIV_1612, #DIV_1635, #DIV_1653, #DIV_1671, #DIV_1689, #DIV_1707, #DIV_1725, #DIV_1743, #DIV_1761, #DIV_1778, #DIV_1796, #DIV_1819, #DIV_1837, #DIV_1855, #DIV_1873, #DIV_1891, #DIV_1909, #DIV_1927, #DIV_1945, #DIV_1963, #DIV_1981, #DIV_2004, #DIV_2022, #DIV_2040, #DIV_2058, #DIV_2076, #DIV_2094, #DIV_2112, #DIV_2130, #DIV_2148, #DIV_2166, #DIV_2189, #DIV_2207, #DIV_2225, #DIV_2243, #DIV_2261, #DIV_2279, #DIV_2297, #DIV_2315, #DIV_2333, #DIV_2351, #DIV_2374, #DIV_2392, #DIV_2410*/

        #SPAN_15 {
            cursor: default;
            text-align: left;
            border: 0px none rgb(153, 153, 153);

            outline: rgb(153, 153, 153) none 0px;
        }


        #DIV_20{
            cursor: default;
            height: auto;
            text-align: left;
            vertical-align: top;
            width:100%;
            perspective-origin: 301px 27px;
            transform-origin: 301px 27px;

        }


        #G-REVIEW-STARS_21 {
            cursor: default;
            text-align: left;
            padding: 0px 7px 0px 0px;
        }

        #SPAN_27 {
            cursor: default;
            text-align: left;
            white-space: pre-wrap;
            border: 0px none rgb(34, 34, 34);
            outline: rgb(34, 34, 34) none 0px;
        }




        /**/
    </style>
    <link rel="stylesheet" type="text/css" href="<?php echo FULL_DESKTOP_URL; ?>assets/css/component.css"/>
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

<section>
    <div class="digi-heading"></div>
    <div class="container">
        <div class="digi-web-main">
            <div>
                <?php include "assets/common-includes/left_menu.php" ?><!--Left Menu-->
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12 bhoechie-tab-container">
                            <div class=" col-md-2  bhoechie-tab-menu-custom">
                                <?php include "assets/common-includes/nav_tab.php" ?>
                            </div>
                            <div class=" col-md-10 bhoechie-tab margin-padding-remover">
                                <?php
                                /*                                if ($get_service_status != null) {
                                                                    if (isset($_GET['custom_url']) && $get_service_status['digital_card'] == 1) {
                                                                        $alreadyActiveSet = true;
                                                                        $alreadyActiveContent = true;*/
                                include "assets/common-includes/client_review.php";
                                /*   }
                                       }
                               */ ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>


</section>




<?php
if(isset($_GET['feedback']) && $_GET['feedback'] == true){
    ?>
    <script>
        $('.flyout_modal_button')[0].click();
    </script>
    <?php
}
?>

<script>

    $('.digit-group').find('input').each(function() {
        $(this).attr('maxlength', 1);
        $(this).on('keyup', function(e) {
            var parent = $($(this).parent());

            if(e.keyCode === 8 || e.keyCode === 37) {
                var prev = parent.find('input#' + $(this).data('previous'));

                if(prev.length) {
                    $(prev).select();
                }
            } else if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
                var next = parent.find('input#' + $(this).data('next'));

                if(next.length) {
                    $(next).select();
                } else {
                    if(parent.data('autosubmit')) {
                        parent.submit();
                    }
                }
            }
        });
    });
</script>

<script>
    $('.list-group-item').on('click', function () {
        return true;
    });

    function openServiceModal(service_name) {
        $('input[name=service_name]').val(service_name);
        $('.service_title').text(service_name);
        $('#enquiryModal').modal('show');
    }

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



</script>
<?php
if ($service_message != "") {
    ?>
    <script>
        successMessage('<?php echo $service_message; ?>');
    </script>
    <?php
}
?>
<?php include "assets/common-includes/footer.php" ?>
<?php include "assets/common-includes/footer_includes.php" ?>


<?php /*include "../assets/common-includes/mobile-desktop-url-changer.php" */ ?>
</body>
</html>
