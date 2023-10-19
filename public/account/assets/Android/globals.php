<?php


include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();


if (isset($_GET['user_id'])) {
    $_SESSION['valid_user_id'] = $_GET['user_id'];
}else{
    $_SESSION['valid_user_id'] = "";
}
$user_id = $security->decryptWebservice($_SESSION['valid_user_id']);
$validateId = $manage->validateUserId($user_id);
$bookmark_user_id = "";

function pagination($query, $per_page = 10,$page = 1, $url = '?'){
    global $manage;
    $row = $manage->getSumOfRow($query);
    $total = $row['num'];
    $adjacents = "2";

    $page = ($page == 0 ? 1 : $page);
    $start = ($page - 1) * $per_page;

    $prev = $page - 1;
    $next = $page + 1;
    $lastpage = ceil($total/$per_page);
    $lpm1 = $lastpage - 1;

    $pagination = "";
    if($lastpage > 1)
    {
        $pagination .= "<ul class='pagination'>";
        $pagination .= "<li class='details' style='margin-top:2px'>Page $page of $lastpage</li>";
        if ($lastpage < 7 + ($adjacents * 2))
        {
            for ($counter = 1; $counter <= $lastpage; $counter++)
            {
                if ($counter == $page)
                    $pagination.= "<li><a class='current'>$counter</a></li>";
                else
                    $pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";
            }
        }
        elseif($lastpage > 5 + ($adjacents * 2))
        {
            if($page < 1 + ($adjacents * 2))
            {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<li><a class='current'>$counter</a></li>";
                    else
                        $pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";
                }
                $pagination.= "<li class='dot'>...</li>";
                $pagination.= "<li><a href='{$url}page=$lpm1'>$lpm1</a></li>";
                $pagination.= "<li><a href='{$url}page=$lastpage'>$lastpage</a></li>";
            }
            elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
            {
                $pagination.= "<li><a href='{$url}page=1'>1</a></li>";
                $pagination.= "<li><a href='{$url}page=2'>2</a></li>";
                $pagination.= "<li class='dot'>...</li>";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<li><a class='current'>$counter</a></li>";
                    else
                        $pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";
                }
                $pagination.= "<li class='dot'>..</li>";
                $pagination.= "<li><a href='{$url}page=$lpm1'>$lpm1</a></li>";
                $pagination.= "<li><a href='{$url}page=$lastpage'>$lastpage</a></li>";
            }
            else
            {
                $pagination.= "<li><a href='{$url}page=1'>1</a></li>";
                $pagination.= "<li><a href='{$url}page=2'>2</a></li>";
                $pagination.= "<li class='dot'>..</li>";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<li><a class='current'>$counter</a></li>";
                    else
                        $pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";
                }
            }
        }

        if ($page < $counter - 1){
            $pagination.= "<li><a href='{$url}page=$next'>Next</a></li>";
            $pagination.= "<li><a href='{$url}page=$lastpage'>Last</a></li>";
        }else{
            $pagination.= "<li><a class='current'>Next</a></li>";
            $pagination.= "<li><a class='current'>Last</a></li>";
        }
        $pagination.= "</ul>\n";
    }


    return $pagination;
}

$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
$limit = 6; //if you want to dispaly 10 records per page then you have to change here
$startpoint = ($page * $limit) - $limit;
$statement = "" . $manage->profileTable . " as pt inner join " . $manage->loginTable . " as lt on lt.user_id = pt.id where lt.type='User' and pt.expiry_date >=CURDATE() "; //you have to pass your query over here
$get_details=$manage->displayAllSearchUser($statement,$startpoint,$limit);

?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Contact listing</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>

        fieldset {
            min-width: 0;
            margin: 0;
            width: 100%;
            border: 1px solid #ccc !important;
            padding: 20px;
            margin-bottom: 2%;
            border-radius: 2px;
        }

        legend {
            display: block;
            width: auto;
            max-width: 100%;
            margin-bottom: .5rem;
            line-height: inherit;
            white-space: normal;
            margin-left: 7px;
            background: #e7eded;
            padding: 3px 15px;
            font-size: 16px;;
        }


    </style>
</head>

<body style="background-color: white">

<section class="user_contact">

    <div class="col-xs-12">


        <form action="" method="post" id="globalSearch">
            <!-- <select name="city" class="form-control" id="city" required="required">
                 <option value="">Select City</option>
                 <option value="Mumbai">Mumbai</option>
                 <option value="Pune">Pune</option>
                 <option value="Nashik">Nashik</option>
             </select> -->

            <div class="margin1-0">
                <input class="form-control search-box search_input" onkeyup="search_card()" type="text" id="search-text"
                       placeholder="Enter kewords...">
                <i class="fa fa-search" aria-hidden="true"></i>
            </div>

        </form>

    </div>
    <div id="get_search_result">
        <?php
        if ($get_details != null) {
        ?>
        <ul class="contact_ul">
            <?php
            while ($form_data = mysqli_fetch_array($get_details)) {
                /*$imaga_name = $form_data['img_name'];*/
                $name = $form_data['name'];
                $contact_no = $form_data['contact_no'];
                $designation = $form_data['designation'];
                $email = $form_data['email'];
                $img_name = $form_data['img_name'];
                $gender = $form_data['gender'];
                $custom_url = $form_data['custom_url'];
                $user_profile_id = $form_data['user_id'];
                $profilePath = "../user/uploads/" . $email . "/profile/" . $img_name;
                $verify_user = $manage->displayVerifiedUser($user_profile_id);
                echo "<style>
                #global-added$user_profile_id{
                    display: none;
                }
            </style>";
                $get_bookmark = $manage->displayAllBookmark($_SESSION['valid_user_id']);
                if ($get_bookmark != null) {
                    while ($get_bookmark1 = mysqli_fetch_array($get_bookmark)) {
                        $bookmark_user_id = $get_bookmark1['bookmark_user_id'];
                        echo "<style>
                #global-added$bookmark_user_id{
                    display: block;
                }
                #global-non-added$bookmark_user_id{
                    display: none;
                }
            </style>";
                    }
                }
                ?>

                <li>
                <div class="col-xs-12">
                    <div class="row">
                        <a href="https://sharedigitalcard.com/m/index.php?custom_url=<?php echo $form_data['custom_url']; ?>">
                            <div class="col-xs-3" style="padding-right:0px;">
                                <div class="">
                                    <img class="contact_logo img-circle"
                                         src="<?php
                                         if (!file_exists($profilePath) && $gender == "Male" or $img_name == "") {
                                             echo "../user/uploads/male_user.png";
                                         } elseif (!file_exists($profilePath) && $gender == "Female" or $img_name == "") {
                                             echo "../user/uploads/female_user.png";
                                         } else {
                                             echo $profilePath;
                                         }
                                         ?>"><?php
                                    ?>
                                </div>
                            </div>
                        </a>

                        <div class="col-xs-9" style="position: relative">
                            <div style="border-bottom: 1px solid #eee;">
                                <a href="https://sharedigitalcard.com/m/index.php?custom_url=<?php echo $form_data['custom_url']; ?>">
                                    <h5>
                                        <strong><?php
                                            if (isset($form_data['name']))
                                                echo $form_data['name'];
                                            ?>
                                        </strong><?php
                                        if ($verify_user == 1) {
                                            ?>
                                            <img class="blue-tick" src="assets/img/blue_tick.png"><?php

                                        }

                                        ?></h5>
                                                    <span><?php
                                                        if (isset($form_data['designation'])) echo $form_data['designation'];
                                                        ?></span>

                                    <p><?php
                                        if (isset($form_data['contact_no'])) echo $form_data['contact_no'];
                                        ?></p></a>

                                <div class="bookmark">
                                    <img id="global-non-added<?php echo $user_profile_id ?>"
                                         onclick="addGlobalBookmark(<?php echo $user_profile_id ?>)"
                                         src="assets/img/non-added.png"
                                         alt="<?php echo $user_profile_id ?>">
                                    <img id="global-added<?php echo $user_profile_id ?>"
                                         src="assets/img/added.png"
                                         onclick="removeGlobalBookmark(<?php echo $user_profile_id ?>)"
                                         alt="<?php echo $user_profile_id ?>">
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
                <div class="col-xs-6 text-center">
                    <p>No data found</p>
                </div><?php
            }
            ?></ul>
        <?php
        echo "<div id='pagingg' >";
        echo pagination($statement,$limit,$page);
        echo "</div>";
        ?>
    </div>

    <br>


    <div id="globalsnackbar">Digital card added to your bookmarks</div>
    <div id="globalremovesnackbar">Digital card removed to your bookmarks</div>


</section>

<?php include "assets/common-includes/footer_includes.php" ?>

<!--<script>
    $(document).ready(function () {
        $("#search_data").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $(".contact_ul li").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });

        });
    });
</script>-->


<script type="text/javascript">
    function search_card() {
        //var city = $("#city").val();
        var search_input = $(".search_input").val();
        var dataString = "search=" + search_input + "&user_id=" + <?php if(isset($user_id)) echo $user_id ?>;
        //alert(dataString);
        $.ajax({
            type: "POST",
            url: "bookmark_ajax.php", // Name of the php files
            data: dataString,
            success: function (html) {
                $("#get_search_result").html(html);
            }
        });
    }
</script>
<script type="text/javascript">
    function addGlobalBookmark(id) {
        var img = document.getElementById('global-non-added' + id);
        var d = img.getAttribute("alt");
        var dataString = "add_bookmark=" + d + "&user_id=" + <?php echo $user_id ?>;
        //alert(dataString);
        $.ajax({
            type: "POST",
            url: "bookmark_ajax.php", // Name of the php files
            data: dataString,
            success: function (html) {
                if (html == 1) {
                    function addStyleAttribute($element, styleAttribute) {
                        $element.attr('style', $element.attr('style') + '; ' + styleAttribute);
                    }

                    var x = document.getElementById("globalsnackbar");
                    x.className = "show";
                    setTimeout(function () {
                        x.className = x.className.replace("show", "");
                    }, 3000);
                    addStyleAttribute($('#global-added' + id), 'display: block !important');
                    $("#global-non-added" + id).css({"display": "none"});
                }
                else {
                    alert("Please try again");
                }
            },
            error: function (err) {
                console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
            }
        });
    }
</script>
<script type="text/javascript">
    function removeGlobalBookmark(id) {
        var img = document.getElementById('global-added' + id);
        var d = img.getAttribute("alt");
        var dataString = "remove_bookmark=" + d;
        $.ajax({
            type: "POST",
            url: "bookmark_ajax.php", // Name of the php files
            data: dataString,
            success: function (html) {
                /*console.log(html)*/
                if (html == 1) {
                    $("#global-non-added" + id).css({"display": "block"});
                    $("#global-added" + id).css({"display": "none"});
                    var x = document.getElementById("globalremovesnackbar");
                    x.className = "show";
                    setTimeout(function () {
                        x.className = x.className.replace("show", "");
                    }, 3000);
                }
                else {
                    alert("Please try again");
                }
            },
            error: function (err) {
                console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
            }
        });
    }
</script>
</body>
</html>