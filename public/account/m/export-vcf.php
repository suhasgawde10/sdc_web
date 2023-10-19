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
$nativeNumber = $contactResult['altr_contact_no'];
$email = $contactResult['email'];
$img_name = $contactResult['img_name'];
$website = $contactResult['website_url'];
$company_name = $contactResult['company_name'];
$address = strip_tags($contactResult['address']);
$gender = $contactResult['gender'];
$designation = $contactResult['designation'];
$searchKeyword = $contactResult['user_keyword'];
$business_category = $contactResult['business_category'];
$dateOfBirth = $contactResult['date_of_birth'];
$mapLink = $contactResult['map_link'];
$workInfo = $company_name.", ".$designation.", ".$business_category;
$note = substr(strip_tags($contactResult['about_company']), 0, 200);
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
    $vCard .= "TEL;TYPE=cell:{$contact_no}\r\n";
}

if($nativeNumber){
   $vCard .= "TEL;TYPE=HOME,VOICE:{$nativeNumber}\r\n";
}
if($workInfo){
    $vCard .= "ORG:{$workInfo}\r\n";
}
if($address){
    $vCard .= "ADR;TYPE=WORK:{$address}\r\n";
}
if($note){
    $vCard .= "NOTE:{$note}\r\n";
}
if($searchKeyword){
    $vCard .= "KEYWORD:{$searchKeyword}\r\n";
}
if($dateOfBirth){
    $vCard .= "BDAY:{$dateOfBirth}\r\n";
}
if($website){
    $vCard .= "URL:{$website}\r\n";
}
// if($mapLink){
//     $vCard .= "URL:{$mapLink}\r\n";
// }

$vCard .= "END:VCARD\r\n";
echo $vCard;
exit();
