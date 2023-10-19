<?php
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();

if (isset($_GET['user_id'])) {
    $user_id = $security->decryptWebservice($_GET['user_id']);
    $validateId = $manage->validateUserId($user_id);
}
$bookmark_user_id = "";
$date = date("Y-m-d");
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Contact listing</title>
    <?php include "assets/common-includes/header_includes.php" ?>
</head>

<body style="background-color: white">

<section class="user_contact">


    <div class="col-xs-12">
        <div class="margin1-0">
            <input class="form-control search-box" type="text" id="search-text" placeholder="Search...">
            <i class="fa fa-search" aria-hidden="true"></i>
        </div>
    </div>
    <?php
    if ($validateId != null) {
        $user_contact_id = $validateId['user_contact'];

        if ($user_contact_id != null) {
            ?>
            <ul class="contact_ul" id="list">
                <?php
                function remove_element($array,$value) {
                    return array_diff($array, (is_array($value) ? $value : array($value)));
                }
                $keyword_data = explode(",", $user_contact_id);
                $i = 1;

                $keyword_data = remove_element($keyword_data,$user_id);
                foreach ($keyword_data as $key) {
                    echo "<style>
#added$key{
display: none;
}
</style>
";
                    $display_user_profile = $manage->displayAllUserByID($key);
                    $verify_user = $manage->displayVerifiedUser($key);
                    if ($display_user_profile != null) {
                        $imaga_name = $display_user_profile['img_name'];
                        $name = $display_user_profile['name'];
                        $contact_no = $display_user_profile['contact_no'];
                        $designation = $display_user_profile['designation'];
                        $email = $display_user_profile['email'];
                        $img_name = $display_user_profile['img_name'];
                        $gender = $display_user_profile['gender'];
                        $custom_url = $display_user_profile['custom_url'];
                        $user_profile_id = $display_user_profile['user_id'];
                        $profilePath = "../user/uploads/" . $email . "/profile/" . $img_name;
                    }
                    $get_bookmark = $manage->displayAllBookmark($security->decryptWebservice($_GET['user_id']));
                    if ($get_bookmark != null) {
                        while ($get_bookmark1 = mysqli_fetch_array($get_bookmark)) {
                            $bookmark_user_id = $get_bookmark1['bookmark_user_id'];
                            echo "<style>
#added$bookmark_user_id{
display: block;
}
#non-added$bookmark_user_id{
display: none;
}
</style>";
                        }
                    }
                    ?>
                    <li id="get_search_contact_result"
                        class="tosearch <?php if ($display_user_profile['expiry_date'] <= $date) echo "expire_user"; ?>">
                        <div class="col-xs-12">
                            <div class="row">
                                <a href="<?php echo SHARED_URL.$custom_url; ?>">
                                    <div class="col-xs-3">
                                        <img class="img-circle contact_logo"
                                             src="<?php if (!file_exists($profilePath) && $gender == "Male" or $img_name == "") {
                                                 echo "../user/uploads/male_user.png";
                                             } elseif (!file_exists($profilePath) && $gender == "Female" or $img_name == "") {
                                                 echo "../user/uploads/female_user.png";
                                             } else {
                                                 echo $profilePath;
                                             } ?>">
                                    </div>
                                </a>

                                <div class="col-xs-9" style="position: relative; padding-left: 0px">
                                    <div style="border-bottom: 1px solid #eee;">
                                        <a href="<?php echo SHARED_URL.$custom_url; ?>">
                                            <h5><strong><?php if (isset($name)) echo $name; ?></strong><?php
                                                if ($verify_user == 1) {
                                                    ?>
                                                    <img class="blue-tick" src="assets/img/blue_tick.png">
                                                <?php
                                                }
                                                ?></h5>
                                            <span><?php if (isset($designation)) echo $designation ?></span>

                                            <p><?php if (isset($contact_no)) echo $contact_no ?></p>
                                        </a>

                                        <div class="bookmark">
                                            <img id="non-added<?php echo $key; ?>"
                                                 onclick="addBookmark(<?php echo $key; ?>)"
                                                 src="assets/img/non-added.png"
                                                 alt="<?php echo $user_profile_id; ?>">
                                            <img id="added<?php echo $key; ?>"
                                                 src="assets/img/added.png"
                                                 onclick="removeBookmark(<?php echo $key; ?>)"
                                                 alt="<?php echo $user_profile_id; ?>">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </li>
                    <?php
                    $i++;
                }
                ?>
                <span class="empty-item">no results</span>

                <div id="snackbar">Digital card added to your bookmarks</div>
                <div id="removesnackbar">Digital card removed from your bookmarks</div>
            </ul>
        <?php
        } else {
            ?>
            <div class="col-xs-12 no_contact_found">
                <img src="assets/img/notebook.png">

                <p>No Contact Found</p>
            </div>
        <?php
        }
    }
    ?>

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

<script>
    $(document).ready(function () {

        var jobCount = $('#list .tosearch').length;
        $('.list-count').text(jobCount + ' items');


        $("#search-text").keyup(function () {
            //$(this).addClass('hidden');

            var searchTerm = $("#search-text").val();
            var listItem = $('#list').children('li');


            var searchSplit = searchTerm.replace(/ /g, "'):containsi('")

            //extends :contains to be case insensitive
            $.extend($.expr[':'], {
                'containsi': function (elem, i, match, array) {
                    return (elem.textContent || elem.innerText || '').toLowerCase()
                            .indexOf((match[3] || "").toLowerCase()) >= 0;
                }
            });


            $("#list li").not(":containsi('" + searchSplit + "')").each(function (e) {
                $(this).addClass('hiding out').removeClass('tosearch');
                setTimeout(function () {
                    $('.out').addClass('hidden');
                }, 300);
            });

            $("#list li:containsi('" + searchSplit + "')").each(function (e) {
                $(this).removeClass('hidden out').addClass('tosearch');
                setTimeout(function () {
                    $('.tosearch').removeClass('hiding');
                }, 1);
            });


            var jobCount = $('#list .tosearch').length;
            $('.list-count').text(jobCount + ' items');

            //shows empty state text when no jobs found
            if (jobCount == '0') {
                $('#list').addClass('empty');
            }
            else {
                $('#list').removeClass('empty');
            }

        });


    });
</script>
<script type="text/javascript">
    function search_contact() {
        var text = $('#contact_input').val();

        var dataString = "search_contact=" + text + "&user_id=" +<?php if(isset($_GET['user_id'])) echo $security->decryptWebservice($_GET['user_id']); ?>;
        // alert(dataString);
        if (text == "") {
            /*$(".contact_ul").css({"display": "block"});*/
        } else {
            $.ajax({
                type: "POST",
                url: "bookmark_ajax.php", // Name of the php files
                data: dataString,
                success: function (html) {
                    console.log(html);
                    $(".contact_ul").html(html);
                }

            });
        }

    }
</script>
<script type="text/javascript">
    function addBookmark(id) {
        var img = document.getElementById('non-added' + id);
        var d = img.getAttribute("alt");
        var dataString = "add_bookmark=" + d + "&user_id=" + <?php echo $security->decryptWebservice($_GET['user_id']); ?>;
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

                    var x = document.getElementById("snackbar");
                    x.className = "show";
                    setTimeout(function () {
                        x.className = x.className.replace("show", "");
                    }, 3000);
                    addStyleAttribute($('#added' + id), 'display: block !important');
                    $("#non-added" + id).css({"display": "none"});
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
    function removeBookmark(id) {
        var img = document.getElementById('added' + id);
        var d = img.getAttribute("alt");
        var dataString = "remove_bookmark=" + d;

        $.ajax({
            type: "POST",
            url: "bookmark_ajax.php", // Name of the php files
            data: dataString,
            success: function (html) {
                /*console.log(html)*/
                if (html == 1) {
                    $("#non-added" + id).css({"display": "block"});
                    $("#added" + id).css({"display": "none"});
                    var x = document.getElementById("removesnackbar");
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