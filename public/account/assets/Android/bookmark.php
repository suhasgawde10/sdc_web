<?php
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();

if (isset($_GET['user_id'])) {
    $user_id = $security->decryptWebservice($_GET['user_id']);
    $get_bookmark = $manage->displayAllBookmark($user_id);
}
$bookmark_user_id = "";
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Contact listing</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        #no_bookmark {
            display: none;
        }
    </style>
</head>

<body style="background-color: white">

<section class="user_contact">
    <div class="container-fluid">
        <div>
            <ul class="contact_ul" id="remove_ul">
                <?php
                if ($get_bookmark != null) {
                    while ($get_data = mysqli_fetch_array($get_bookmark)) {
                        $imaga_name = $get_data['img_name'];
                        $name = $get_data['name'];
                        $contact_no = $get_data['contact_no'];
                        $designation = $get_data['designation'];
                        $email = $get_data['email'];
                        $img_name = $get_data['img_name'];
                        $gender = $get_data['gender'];
                        $custom_url = $get_data['custom_url'];
                        $user_profile_id = $get_data['user_id'];
                        $bookmark_user_id = $get_data['bookmark_user_id'];
                        $profilePath = "../user/uploads/" . $email . "/profile/" . $img_name;
                        $user_id1 = $get_data['user_id'];
                        $verify_user = $manage->displayVerifiedUser($user_id1);
                        ?>
                        <li id="bookmark_li<?php echo $bookmark_user_id ?>">
                            <div class="row">
                                <div class="col-xs-3" style="padding-right: 0px;">
                                    <a href="<?php echo SHARED_URL.$custom_url; ?>">
                                        <img class="img-circle contact_logo"
                                             src="<?php if (!file_exists($profilePath) && $gender == "Male" or $img_name == "") {
                                                 echo "../user/uploads/male_user.png";
                                             } elseif (!file_exists($profilePath) && $gender == "Female" or $img_name == "") {
                                                 echo "../user/uploads/female_user.png";
                                             } else {
                                                 echo $profilePath;
                                             } ?>">

                                    </a>
                                </div>

                                <div class="col-xs-9" style="position: relative">
                                    <div style="border-bottom: 1px solid #eee;">
                                        <a href="<?php echo SHARED_URL.$custom_url; ?>">
                                            <h5><strong><?php if (isset($name)) echo $name; ?></strong>
                                                <?php
                                                if ($verify_user == 1) {
                                                    ?>
                                                    <img class="blue-tick" src="assets/img/blue_tick.png">
                                                <?php
                                                }
                                                ?>
                                            </h5>
                                            <span><?php if (isset($designation)) echo $designation ?></span>

                                            <p><?php if (isset($contact_no)) echo $contact_no ?></p>
                                        </a>

                                        <div class="bookmark">
                                            <img id="added<?php echo $bookmark_user_id; ?>"
                                                 src="assets/img/trash.png"
                                                 onclick="removeBookmark(<?php echo $bookmark_user_id; ?>)"
                                                 alt="<?php echo $user_profile_id; ?>" style="width: 9%">
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </li>
                    <?php
                    }
                } else {
                    ?>
                    <div class="col-xs-12 no_contact_found">
                        <img src="assets/img/bookmark.png">

                        <p>No Bookmark Found</p>
                    </div>
                <?php
                }
                ?>
                <div class="col-xs-12 no_contact_found" id="no_bookmark">
                    <img src="assets/img/bookmark.png">

                    <p>No Bookmark Found</p>
                </div>
                <div id="removesnackbar">Digital card removed to your bookmarks</div>

            </ul>
        </div>
    </div>
</section>




<?php include "assets/common-includes/footer_includes.php" ?>





<script>
    function removeBookmark(id) {
        var img = document.getElementById('added' + id);
        var d = img.getAttribute("alt");
        var dataString = "delete_bookmark=" + d;

        $.ajax({
            type: "POST",
            url: "bookmark_ajax.php", // Name of the php files
            data: dataString,
            cache: false,
            success: function (html) {
                if (html == 1) {
                    if ($('#remove_ul li').size() == 1) {
                        $("#bookmark_li" + id).remove();
                        $("#no_bookmark").css({"display": "block"});
                        var x = document.getElementById("removesnackbar");
                        x.className = "show";
                        setTimeout(function () {
                            x.className = x.className.replace("show", "");
                        }, 3000);
                    } else {
                        $("#bookmark_li" + id).remove();
                        var x = document.getElementById("removesnackbar");
                        x.className = "show";
                        setTimeout(function () {
                            x.className = x.className.replace("show", "");
                        }, 3000);
                    }
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