<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardAjaxController extends Controller
{
    public function getProductDetails($id)
    {
        try {
            $data = DB::table('tb_services')->where('id', $id)->first();
            // dd($data);
            $businessType = 'Service';
            if($data->serv_type == 1){
                $businessType = 'Product';
            }
            $userId = $data->user_id;
            $text = urlencode('I am interested in your ' . $data->service_name ." ". $businessType.' which is listed in your digital card please reply to my message.');
            
            $userDetails = DB::table('tb_user_profile')->where('id', $userId)->select('altr_contact_no', 'whatsapp_no', 'country')->first();

            $getCountyCode = DB::table('countries')->where('id', $userDetails->country)->first();
            $countyCode = 91;
            if($getCountyCode){
                $countyCode = $getCountyCode->phonecode;
            }
            $whatsAppNo = $userDetails->altr_contact_no;
            if($userDetails->whatsapp_no){
                $whatsAppNo = $userDetails->whatsapp_no;
            }

            $enquiryData = [
                'text' => $text,
                'country_code' => $countyCode,
                'number' => $whatsAppNo,
            ];

            
            return response()->json(['status' => true, 'data' => $data, 'enquiryData' => $enquiryData]);
        } catch (\Exception $ex) {
            return response()->json(['status' => false, 'message' => $ex->getMessage()]);
        }
    }
}
