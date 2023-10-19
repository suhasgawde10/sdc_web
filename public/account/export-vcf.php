<?php

include "../controller/ManageMobileCard.php";
$manage = new ManageMobileCard();
$contactResult = $manage->mdm_getDigitalCardDetailsOFUser($_GET['custom_url']);
$name = $contactResult['name'];

$designation = $contactResult['designation'];
/*if ($designation != "") {
    $vcardObj->addJobtitle($contactResult["designation"]);
}*/
$contact_no = $contactResult['contact_no'];
$email = $contactResult['email'];
$img_name = $contactResult['img_name'];
$website = $contactResult['website_url'];
$company_name = $contactResult['company_name'];
$address = $contactResult['address'];
$gender = $contactResult['gender'];
/*if ($website != "") {
    $vcardObj->addURL($contactResult["website_url"]);
}*/

if ($address != "") {
    // $vcardObj->addAddress($contactResult["address"]);
}/*
if ($company_name != "") {
    $vcardObj->addCompany($contactResult["company_name"]);
}*/



$metaProfilePath = FULL_WEBSITE_URL."user/uploads/" . $email . "/profile/" . $img_name;

if (!check_url_exits($metaProfilePath) && $gender == "Male" or $img_name == "") {
    $newMetaProfilePath = "https://sharedigitalcard.com/user/uploads/male_user.png";
} elseif (!check_url_exits($metaProfilePath) && $gender == "Female" or $img_name == "") {
    $newMetaProfilePath = "https://sharedigitalcard.com/user/uploads/female_user.png";
} else {
    $newMetaProfilePath = "https://sharedigitalcard.com/user/uploads/" . $email . "/profile/" . $img_name;
}
// add personal data



header('Content-Type: text/x-vcard');
header('Content-Disposition: inline; filename= "'.$name.'.vcf"');

if($newMetaProfilePath!=""){
    $getPhoto               = file_get_contents($metaProfilePath);
    $b64vcard               = base64_encode($getPhoto);
    $b64mline               = chunk_split($b64vcard,74,"\n");
    $b64final               = preg_replace('/(.+)/', ' $1', $b64mline);
    $photo                  = $b64final;
}
$vCard = "BEGIN:VCARD\r\n";
$vCard .= "VERSION:3.0\r\n";
$vCard .= "FN:" . $name . "\r\n";
$vCard .= "TITLE:" . $company_name . "\r\n";

if($email){
    $vCard .= "EMAIL;TYPE=internet,pref:" . $email . "\r\n";
}
if($getPhoto){
    $vCard .= "PHOTO;ENCODING=b;TYPE=JPEG:";
    $vCard .= $photo . "\r\n";
}

if($contact_no){
    $vCard .= "TEL;TYPE=work,voice:" . $contact_no . "\r\n";
}

$vCard .= "END:VCARD\r\n";
echo $vCard;
exit();
