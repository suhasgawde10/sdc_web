<script type="text/javascript">
    if (screen.width <= 768 || screen.height == 480) {
        var full_url = window.location.href;
console.log(full_url);
    var new_url = full_url.replace("/d/home/", "/");
console.log(new_url);
    var new_url1 = new_url.replace("/d/", "/");
console.log(new_url1);
    window.location = new_url1;
}
</script>


<meta name="description" content="<?php
if ($about_company != "") {
    echo substr(strip_tags($about_company), 0, 160);
} else {
    echo $contact_no . "-" . $user_email;
}
?>">
<meta name="keywords" content="<?php
if ($keyword_array_data != "") {
    foreach ($keyword_array_data as $array_key) {
        echo $array_key;
        if ($user_city != "") {
            echo " in " . $user_city . ",";
        } else {
            echo ",";
        }
    }
}
?>">
<meta property="og:title" content="<?php echo $name; ?> - <?php echo $designation; ?>"/>
<meta property="og:url"
      content="<?php echo SHARED_URL . $_GET['custom_url'] ?>"/>
<meta property="og:description"
      content="<?php echo $contact_no; ?> - <?php echo $user_email; ?>">
<meta property="og:site_name" content="Share Digital Card">
<meta property="og:image" itemprop="image" content="<?php if ($metaProfilePath != "") {
    echo $metaProfilePath;
} elseif ($gender == "Male") {
    echo "https://sharedigitalcard.com/user/uploads/male_user.png";
} elseif ($gender == "Female") {
    echo "https://sharedigitalcard.com/user/uploads/female_user.png";
} ?>">
<meta property="og:type" content="website"/>
<meta property="og:updated_time" content="1440432930"/>
<!-- <meta property="og:image" content="<?php /*if ($img_name == "" && $gender == "Male") {
        echo FULL_WEBSITE_URL."user/uploads/male_user.png";
    } elseif ($img_name == "" && $gender == "Female") {
        echo FULL_WEBSITE_URL."user/uploads/female_user.png";
    } else {
        echo $profilePath;
    } */ ?>">-->

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css">-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css" defer>
<link rel="stylesheet" type="text/css"
      href="<?php echo FULL_DESKTOP_URL; ?>assets/css/style.min.css?version=<?php echo $version; ?>">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js" async></script>
<?php

if ($_SERVER['HTTP_HOST'] == "sharedigitalcard.com") {
    ?>
    <link rel="shortcut icon" type="image/png" href="<?php echo FULL_WEBSITE_URL; ?>assets/img/logo/favicon.png">
    <?php
} else {
    ?>
    <link rel="shortcut icon" type="image/png"
          href="https://freepngimg.com/download/logo/81920-world-globe-computer-silhouette-icons-hq-image-free-png.png">
    <?php
}
?>

<!--<div class="back-color spinner">
    <img src="<?php echo FULL_DESKTOP_URL; ?>assets/images/logo-loading.gif" style="width: auto; height: 150px; ">
</div>-->
<link href='https://fonts.googleapis.com/css?family=Ubuntu|Chewy|Quattrocento+Sans|Kaushan+Script|Comfortaa|Lobster+Two|Raleway|Montserrat|Titillium+Web|Josefin+Sans|Pacifico|Orbitron|Josefin+Slab|Satisfy|Economica|Courgette'
      rel='stylesheet' type='text/css'>
<link href='' rel='stylesheet' type='text/css'>

<?php

if (isset($companyLogoPath) && check_url_exits($companyLogoPath)) {
    ?>
    <div class="back-color spinner">
        <div>
            <img src="<?php echo $folder_url . $companyLogoPath; ?>" style="width: auto; height: 150px; ">
            <?php if (isset($company_name) && $company_name != '') echo "<h3 style='margin-bottom: 4px;'>" . $company_name . "</h3><div style='width: 17%;margin: 0 auto;'><img src='" . FULL_MOBILE_URL . "assets/images/1.gif' style='width: 100%'></div>"; ?>
        </div>
    </div>
    <?php
} else {
    ?>
    <div class="back-color spinner">
        <div>

            <!--<img src="<?php /*echo $folder_url; */ ?>assets/images/logo-loading.gif" style="width: auto; height: 150px; ">-->
            <?php if (isset($company_name) && $company_name != '') echo "<h3 style='margin-bottom: 4px;'>" . $company_name . "</h3><div style='width: 17%;margin: 0 auto;'><img src='" . FULL_MOBILE_URL . "assets/images/1.gif' style='width: 100%'></div>"; ?>
        </div>
    </div>
    <?php
}
?>
<?php

$theme_path = FULL_WEBSITE_URL . "theme/" . $user_theme;

if ($user_theme != '' && check_url_exits($theme_path)) {
    $theme_path = $theme_path;
    echo '<style>
    .content-main {
        background-image: url(' . $theme_path . ');
    }
</style>';
} elseif (isset($user_theme) && (strpos($user_theme, ".") === false)) {
    echo '<style>
    .content-main {
        background-color: #' . $user_theme . ';
    }
</style>';

} else {
    $theme_path = FULL_WEBSITE_URL . "theme/6.png";

    echo '<style>
    .content-main {
        background-image: url(' . $theme_path . ');
    }
</style>';
}


if ($get_data['text_color'] != null) {
    echo '<style>
.text-color-p{
color: #' . $get_data['text_color'] . ';
}
</style>';
}

if ($get_data['background_color'] != null) {
    echo '<style>
    p,h1,h2,h3,h4,h5,h6,.nav-tabs > li > a,span,.text-color-p,.btn,.bank-model-table td,.bank-detail a{
        font-family: ' . $get_data['background_color'] . ';
            }
</style>';
}

?>
