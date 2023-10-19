<?php

function verifychecksum($trackingid, $description, $amount, $status, $checksum, $key)
{
    $str = $trackingid . "|" . $description . "|" . $amount . "|" . $status . "|" . $key;
    $generatedCheckSum = MD5($str);
    if ($generatedCheckSum == $checksum) {
        return "true";
    } else {
        return "false";
    }
}


function getchecksum($memberid, $totype, $amount, $description, $redirecturl, $key)
{
    $str = $memberid . "|" . $totype . "|" . $amount . "|" . $description . "|" . $redirecturl . "|" . $key;
    $generatedCheckSum = MD5($str);
    return $generatedCheckSum;

}

function compress_image($source_url, $destination_url, $quality) {

    $info = getimagesize($source_url);

    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source_url);

    elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($source_url);

    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source_url);

    imagejpeg($image, $destination_url, $quality);
    return $destination_url;
}


?>
