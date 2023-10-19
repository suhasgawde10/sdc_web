<?php

namespace App\Http\Controllers;

use App\Mail\SendEnquiryMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CardActionController extends Controller
{
    function sendEnquiry(Request $request)
    {
        try {

            $service_id = $request->service_id;
            $name = $request->name;
            $contact = $request->contact;
            $service_name = $request->Service_name;
            $serviceDetails = DB::table('tb_services')->where('id', $service_id)->first();
            $userDetails = DB::table('tb_user_profile')->where('id', $serviceDetails->user_id)->select('id', 'name', 'saved_email', 'altr_contact_no', 'whatsapp_no', 'lead_count')->first();
            $email_message = "You have a customer request for service " . $service_name . "<br>Please contact with the customer Name: " . $name . " & Contact Number: " . $contact;
            $clientEmail = $userDetails->saved_email;
            $clientEmail = 'akhilesh@kubictechnology.in';
            Mail::to($clientEmail)->send(new SendEnquiryMail($email_message));

            
            $insertLeadArr = [
                'user_id' => $serviceDetails->user_id,
                'client_name' => $name,
                'contact_no' => $contact,
                'service_name' => $service_name,
                'created_date' => date('Y-m-d'),
                'approve_status' => 'Pending'
            ];
            $insertData = DB::table('tb_service_request')->insert($insertLeadArr);
            $oldCount = $userDetails->lead_count;
            $newLeadCount = $oldCount+1;
            $updateCountArr = [
                'lead_count' => $newLeadCount
            ];
            $updateData = DB::table('tb_user_profile')->where('id', $serviceDetails->user_id)->update($updateCountArr);
            

            return response()->json(['success' => true, 'message' => 'Your request for ' . $service_name . ' has been sent successfully!']);

        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => 'Something went wrong please try again later!'.$ex->getMessage()]);
        }
    }
    function saveContact($id)
    {


        $userDetails = DB::table('tb_user_profile')->where('id', $id)->first();
        if ($userDetails) {
            $contactResult = (array) $userDetails;
            // Now, $contactResult is an array containing the record's data
        }
        // dd($contactResult);
        $name = $contactResult['name'];

        $designation = $contactResult['designation'];
        /*if ($designation != "") {
            $vcardObj->addJobtitle($contactResult["designation"]);
        }*/
        $contact_no = $contactResult['altr_contact_no'];
        $email = $contactResult['saved_email'];
        $img_name = $contactResult['img_name'];
        $website = $contactResult['website_url'];
        $company_name = $contactResult['company_name'];
        $address = $contactResult['address'];
        $gender = $contactResult['gender'];
        $name = $contactResult['name'];



        // $metaProfilePath = "https://sharedigitalcard.com/" . "user/uploads/" . $email . "/profile/" . $img_name;
        $metaProfilePath = "https://sharedigitalcard.com/user/uploads/male_user.png";
        if (!$this->check_url_exits($metaProfilePath) && $gender == "Male" or $img_name == "") {
            $newMetaProfilePath = "https://sharedigitalcard.com/user/uploads/male_user.png";
        } elseif (!$this->check_url_exits($metaProfilePath) && $gender == "Female" or $img_name == "") {
            $newMetaProfilePath = "https://sharedigitalcard.com/user/uploads/female_user.png";
        } else {
            $newMetaProfilePath = "https://sharedigitalcard.com/user/uploads/" . $email . "/profile/" . $img_name;
        }
        // add personal data





        if ($newMetaProfilePath != "") {
            $getPhoto               = file_get_contents($metaProfilePath);
            $b64vcard               = base64_encode($getPhoto);
            $b64mline               = chunk_split($b64vcard, 74, "\n");
            $b64final               = preg_replace('/(.+)/', ' $1', $b64mline);
            $photo                  = $b64final;
        }
        $vCard = "BEGIN:VCARD\r\n";
        $vCard .= "VERSION:3.0\r\n";
        $vCard .= "FN:" . $name . "\r\n";
        $vCard .= "TITLE:" . $company_name . "\r\n";

        if ($email) {
            $vCard .= "EMAIL;TYPE=internet,pref:" . $email . "\r\n";
        }
        if ($getPhoto) {
            $vCard .= "PHOTO;ENCODING=b;TYPE=JPEG:";
            $vCard .= $photo . "\r\n";
        }

        if ($contact_no) {
            $vCard .= "TEL;TYPE=work,voice:" . $contact_no . "\r\n";
        }

        $vCard .= "END:VCARD\r\n";



        $filename = $name . '.vcf';
        return response($vCard)->header('Content-Type', 'text/x-vcard')->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    function check_url_exits($url)
    {
        /*$new_url = str_replace('https','http',$url);*/
        $headers = get_headers($url);
        return stripos($headers[0], "200 OK") ? true : false;
    }
}
