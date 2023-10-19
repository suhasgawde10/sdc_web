<?php
require_once "../controller/ManageDesktopCard.php";
$manage = new ManageDesktopCard();
require_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
if(isset($_POST['page'])){
    $user_id = $_POST['user_id'];
    // Include pagination library file
    include_once 'assets/common-includes/Pagination.php';


    // Set some useful configuration
    $baseURL = 'getData.php';
    $offset = !empty($_POST['page'])?$_POST['page']:0;
    $limit = 6;

    $get_count = $manage->mdm_displayVideoCount($user_id);

    // Initialize pagination class
    $pagConfig = array(
        'user_id' => $user_id,
        'baseURL' => $baseURL,
        'totalRows' => $get_count,
        'perPage' => $limit,
        'currentPage' => $offset,
        'contentDiv' => 'postContent'
    );
    $pagination =  new Pagination($pagConfig);

    // Fetch records based on the offset and limit
 //   $query = $db->query("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$limit");
    $get_data1 =$manage->mu_displayVideoDetailsByLimit($user_id,$offset,$limit);
?>

     <ul class="video-main-ul">
                                        <?php
                                            while ($form_data = mysqli_fetch_array($get_data1)) {
                                                $video_link = str_replace("watch?v=","embed/",$form_data['video_link']); // &feature=youtu.be
                                                $video_link = str_replace("&feature=youtu.be","",$video_link); // &feature=youtu.be
                                                ?>
                                                <li>
                                                    <div class="info-video">
                                                        <iframe src=<?php echo $video_link; ?>
                                                                frameborder="0"
                                                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                                                allowfullscreen></iframe>
                                                    </div>
                                                </li>
                                            <?php
                                            }
                                        ?>
                                    </ul>
                                                    <!-- Display pagination links -->
                                                    <?php echo $pagination->createLinks();
}
?>