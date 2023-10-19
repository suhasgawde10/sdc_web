<?php

/*require_once '../vendor/Behat-Transliterator/Transliterator.php';
require_once '../vendor/jeroendesloovere-vcard/VCard.php';*/


use JeroenDesloovere\VCard\VCard;

class VcardExport
{

    public function contactVcardExportService($contactResult)
    {
        require_once '../vendor/jeroendesloovere-vcard/VCard.php';
        require_once '../vendor/Behat-Transliterator/Transliterator.php';

        // define vcard
        $vcardObj = new VCard();

        $name = $contactResult['name'];

        $designation = $contactResult['designation'];
        if ($designation != "") {
            $vcardObj->addJobtitle($contactResult["designation"]);
        }
        $contact_no = $contactResult['contact_no'];
        $email = $contactResult['email'];
        $img_name = $contactResult['img_name'];
        $website = $contactResult['website_url'];
        $company_name = $contactResult['company_name'];
        $address = $contactResult['address'];
        $gender = $contactResult['gender'];
        if ($website != "") {
            $vcardObj->addURL($contactResult["website_url"]);
        }

        if ($address != "") {
            // $vcardObj->addAddress($contactResult["address"]);
        }
        if ($company_name != "") {
            $vcardObj->addCompany($contactResult["company_name"]);
        }



        $metaProfilePath = FULL_WEBSITE_URL."user/uploads/" . $email . "/profile/" . $img_name;

        if (!check_url_exits($metaProfilePath) && $gender == "Male" or $img_name == "") {
            $newMetaProfilePath = "https://sharedigitalcard.com/user/uploads/male_user.png";
        } elseif (!check_url_exits($metaProfilePath) && $gender == "Female" or $img_name == "") {
            $newMetaProfilePath = "https://sharedigitalcard.com/user/uploads/female_user.png";
        } else {
            $newMetaProfilePath = "https://sharedigitalcard.com/user/uploads/" . $email . "/profile/" . $img_name;
        }
        // add personal data
          $vcardObj->addName($contactResult["name"]);
                 $vcardObj->addBirthday($contactResult["date_of_birth"]);
                 $vcardObj->addEmail($contactResult["email"]);

        $vcardObj->addPhoneNumber($contactResult["contact_no"]);
        $vcardObj->addPhoto($newMetaProfilePath);

        $vcardObj->addNickName($contactResult["name"]);

        return $vcardObj->download();
    }
}

?>
<?php ?>