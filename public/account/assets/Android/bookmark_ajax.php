<?php
include "../controller/ManageUser.php";
$manage = new ManageUser();

$error = false;
$errorMessage = "";


if (isset($_POST['add_bookmark'])) {

    $add_bookmark = $_POST['add_bookmark'];
    $user_id = $_POST['user_id'];
    $insertUserBookmark = $manage->addBookmark($user_id, $add_bookmark);
    if ($insertUserBookmark) {
        echo true;
    } else {
        echo false;
    }
}

if (isset($_POST['remove_bookmark'])) {
    $remove_bookmark = $_POST['remove_bookmark'];
    $removeUserBookmark = $manage->removeBookmark($remove_bookmark);
    if ($removeUserBookmark) {
        echo true;
    } else {
        echo false;
    }
}

if (isset($_POST['delete_bookmark'])) {
    $delete_bookmark = $_POST['delete_bookmark'];
    $deleteBookmark = $manage->removeBookmark($delete_bookmark);
    if ($deleteBookmark) {
        echo true;
    } else {
        echo false;
    }
}

/*Contact search*/
/*if (isset($_POST['search_contact']) && (isset($_POST['user_id']))) {
    $text = $_POST['search_contact'];
    $user_id = $_POST['user_id'];
    $display_user_profile = $manage->displayContactUser($text);
    if ($display_user_profile != null) {
        $i = 1;
        while ($get_result_data1 = mysqli_fetch_array($display_user_profile)) {
            $get_user_contact_id = $get_result_data1['user_id'];
            $data = $manage->checkContactUserId($get_user_contact_id, $user_id);
        }
        if ($data != null) {

            echo "<style>
#added$get_user_contact_id{
display: none;
}
</style>
";
            $display_user_profile = $manage->displayAllUserByID($get_user_contact_id);
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
            $get_bookmark = $manage->displayAllBookmark($user_id);
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

            echo '
                                    <li id="get_search_contact_result">
                                        <div class="col-xs-12">
                                            <div class="row">
                                                <a href="' .SHARED_URL . $custom_url . '">
                                                    <div class="col-xs-4">
                                                        <div class="contact_logo">

                                                            <img class="img-circle"
                                                                src="';
            if (!file_exists($profilePath) && $gender == "Male" or $gender == "") {
                echo "../user/uploads/male_user.png";
            } elseif (!file_exists($profilePath) && $gender == "Female" or $gender == "") {
                echo "../user/uploads/female_user.png";
            } else {
                echo $profilePath;
            }
            echo '">';
            echo '

                                                        </div>
                                                    </div>
                                                </a>
                                                    <div class="col-xs-8" style="position: relative">
                                                        <h5><strong>';
            if (isset($name)) echo $name;
            echo '
</strong></h5>
                                                        <span>';
            if (isset($designation)) echo $designation;
            echo '</span>

                                                        <p>';
            if (isset($contact_no)) echo $contact_no;
            echo '</p>
                                                        <div class="bookmark">
                                                            <img id="non-added' . $get_user_contact_id . '"
                                                                 onclick="addBookmark(' . $get_user_contact_id . ')"
                                                                 src="assets/img/non-added.png"
                                                                 alt="' . $user_profile_id . '">
                                                            <img id="added' . $get_user_contact_id . '"
                                                                 src="assets/img/added.png"
                                                                 onclick="removeBookmark(' . $get_user_contact_id . ')"
                                                                 alt="' . $user_profile_id . '">
                                                        </div>
                                                    </div>


                                            </div>
                                        </div>
                                    </li>';

            $i++;

        } else {
            echo '
                                <div class="col-xs-6 text-center">
                                    <p>No data found</p>
                                </div>
                            ';
        }
    }


}*/
/**/
/*Global search*/
if (isset($_POST['search']) && (isset($_POST['user_id']))) {
    $search = $_POST['search'];
   if($search==""){
       $error = true;
       echo '
       <div class="col-xs-6 text-center">
                                <p>Enter some text<br></p>
                            </div>';
   }
    if(!$error){
        $result = $manage->displaySearchUser($search);
        if ($result != null) {
            echo '
     <ul class="contact_ul">';

            while ($form_data = mysqli_fetch_array($result)) {
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
                $get_bookmark = $manage->displayAllBookmark($_POST['user_id']);
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
                echo '

                                <li>
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <a href="https://sharedigitalcard.com/m/index.php?custom_url=' . $form_data['custom_url'] . '">
                                                <div class="col-xs-3" style="padding-right:0px;">
                                                    <div class="">
                                                        <img class="contact_logo img-circle"
                                                             src="';
                if (!file_exists($profilePath) && $gender == "Male" or $img_name == "") {
                    echo "../user/uploads/male_user.png";
                } elseif (!file_exists($profilePath) && $gender == "Female" or $img_name == "") {
                    echo "../user/uploads/female_user.png";
                } else {
                    echo $profilePath;
                }
                echo '">';
                echo '
                                                    </div>
                                                </div>
                                                 </a>

                                                <div class="col-xs-9" style="position: relative">
                                                <div style="border-bottom: 1px solid #eee;">
                                                <a href="' .SHARED_URL. $form_data['custom_url'] . '">
                                                    <h5>
                                                        <strong>';
                if (isset($form_data['name']))
                    echo $form_data['name'];
                echo '
</strong>';
                                                if($verify_user==1){
           echo '
                                                    <img class="blue-tick" src="assets/img/blue_tick.png">';

                                                }

                                                    echo '</h5>
                                                    <span>';
                if (isset($form_data['designation'])) echo $form_data['designation'];
                echo '</span>

                                                    <p>';
                if (isset($form_data['contact_no'])) echo $form_data['contact_no'];
                echo '</p></a>

                                                    <div class="bookmark">
                                                        <img id="global-non-added' . $user_profile_id . '"
                                                                 onclick="addGlobalBookmark(' . $user_profile_id . ')"
                                                                 src="assets/img/non-added.png"
                                                                 alt="' . $user_profile_id . '">
                                                            <img id="global-added' . $user_profile_id . '"
                                                                 src="assets/img/added.png"
                                                                 onclick="removeGlobalBookmark(' . $user_profile_id . ')"
                                                                 alt="' . $user_profile_id . '">
                                                    </div>
                                                </div>
                                                </div>
                                        </div>
                                    </div>
                                </li>';
            }
        } else {
            echo '<div class="col-xs-6 text-center">
                                <p>No data found</p>
                            </div>';
        }
        echo '</ul>';
    }


}





?>

