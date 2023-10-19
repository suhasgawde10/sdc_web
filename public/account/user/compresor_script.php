<?php
ob_start();
error_reporting(1);
ini_set('memory_limit', '-1');
date_default_timezone_set("Asia/Kolkata");
include_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../controller/ManageUser.php";
$manage = new ManageUser();
$controller = new Controller();
$con = $controller->connect();
header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getImagesFromFolder($folderPath) {
    $images = array();

    // Open the folder
    $dir = opendir($folderPath);

    // Loop through files and folders
    while (($file = readdir($dir)) !== false) {
        // Skip parent and current directory pointers
        if ($file == '.' || $file == '..') {
            continue;
        }

        $filePath = $folderPath . '/' . $file;

        // If the current item is a directory, recursively call the function
        if (is_dir($filePath)) {
            $subfolderImages = getImagesFromFolder($filePath);
            $images = array_merge($images, $subfolderImages);
        }

        // Check if the current item is an image file
        $imageExtensions = array('.jpg', '.jpeg', '.png', '.gif');
        $fileExtension = strtolower(strrchr($file, '.'));

        if (in_array($fileExtension, $imageExtensions)) {
            $images[] = $filePath;
        }
    }

    // Close the folder
    closedir($dir);

    return $images;
}

// Specify the path to the upload folder
// $directory_name = "uploads";
$uploadFolder = 'uploads';

// Get all images from the folder and subfolders
$allImages = getImagesFromFolder($uploadFolder);

// Display the image paths
$i = 1;
foreach ($allImages as $image) {
  $i++;
  $fileSize = $manage->getImageFileSize($image);
  if (($fileSize >= 100)) {
    $manage->imageCompressor($image);
  }


    // echo $image . '<br>';
    //echo '<li>'.$i.' <img src="'.$image.'" ></li>';
}


?>
