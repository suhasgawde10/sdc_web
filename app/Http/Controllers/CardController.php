<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CardController extends Controller
{
    function commonDetails($slug)
    {
        // $user = Cache::remember('user', 10, function () use ($slug) {
            $user = DB::table('tb_user_profile')->where('custom_url', $slug)->first();
        // });
        if ($user === null) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
        }
        $userEmail = $user->saved_email;
        $userId = $user->id;
        $coverProfiles = DB::table('tb_cover_profile')->where('user_id', $userId)->get();

        $carouselImages = $coverProfiles->map(function ($coverProfile) use ($userEmail) {
            return [
                'url' => asset('/account/user/uploads/' . $userEmail . '/profile/' . $coverProfile->cover_pic),
            ];
        });


        // $services = Cache::remember('services', 10, function () use ($userId) {
            $servicesData = DB::table('tb_services')->where('user_id', $userId)->get();

            // Create an empty array to hold services grouped by serv_type
            $servicesByType = [];

            // Loop through the services and group them by serv_type
            foreach ($servicesData as $service) {
                $servType = $service->serv_type;
                if (!isset($servicesByType[$servType])) {
                    $servicesByType[$servType] = [];
                }
                $servicesByType[$servType][] = [
                    "id" => $service->id,
                    "user_id" => $service->user_id,
                    "position_order" => $service->position_order,
                    "service_name" => $service->service_name,
                    "description" => $service->description,
                    "img_name" => $service->img_name,
                    "request_status" => $service->request_status,
                    "status" => $service->status,
                    "read_more" => $service->read_more,
                    "whatsapp_status" => $service->whatsapp_status,
                    "serv_type" => $service->serv_type,
                    "pay_status" => $service->pay_status,
                    "read_more_txt" => $service->read_more_txt,
                    "amount" => $service->amount,
                    "pay_link" => $service->pay_link,
                    "call_status" => $service->call_status,
                ];
            }

            $services = $servicesByType;
        // });

        $clientsReviewCount = DB::table('tb_client_review')->where('user_id', $userId)->count();

        $clientReviewsAvg = DB::table('tb_client_review')
            ->where('user_id', $userId)
            ->avg('rating_number');

        $otherLinks = DB::table('tb_other_link')->where('user_id', $userId)->get();
        $otherLinkArr = [];
        foreach ($otherLinks as $key => $otherLink) {
            $otherLinkArr[$otherLink->id] = [
                'icon_name' => $this->parse_url_all($otherLink->link),
                'link_url'  => $otherLink->link,
            ];
        }
        // dd($otherLinkArr);

        $sectionStatus = DB::table('tb_section_status')
            ->select([
                DB::raw('SUM(CASE WHEN section_id = 10 THEN 1 ELSE 0 END) AS profile_status'),
                DB::raw('SUM(CASE WHEN section_id = 1 THEN 1 ELSE 0 END) AS service_status'),
                DB::raw('SUM(CASE WHEN section_id = 11 THEN 1 ELSE 0 END) AS product_status'),
                DB::raw('SUM(CASE WHEN section_id = 2 THEN 1 ELSE 0 END) AS gallery_status'),
                DB::raw('SUM(CASE WHEN section_id = 4 THEN 1 ELSE 0 END) AS client_status'),
                DB::raw('SUM(CASE WHEN section_id = 6 THEN 1 ELSE 0 END) AS team_status'),
                DB::raw('SUM(CASE WHEN section_id = 7 THEN 1 ELSE 0 END) AS bank_status')
            ])
            ->where('user_id', $userId)
            ->whereIn('website', [1])
            ->whereIn('digital_card', [1])
            ->first();

        if ($sectionStatus) {
            $sectionStatusArray = (array)$sectionStatus;
        } else {
            $sectionStatusArray = [];
        }
        $sectionIcon = DB::table('tb_section_icon')
            ->select([
                DB::raw('MAX(CASE WHEN section_id = 10 THEN section_img END) AS profile_status'),
                DB::raw('MAX(CASE WHEN section_id = 1 THEN section_img END) AS service_status'),
                DB::raw('MAX(CASE WHEN section_id = 11 THEN section_img END) AS product_status'),
                DB::raw('MAX(CASE WHEN section_id = 2 THEN section_img END) AS gallery_status'),
                DB::raw('MAX(CASE WHEN section_id = 4 THEN section_img END) AS client_status'),
                DB::raw('MAX(CASE WHEN section_id = 6 THEN section_img END) AS team_status'),
                DB::raw('MAX(CASE WHEN section_id = 7 THEN section_img END) AS bank_status')
            ])
            ->where('user_id', $userId)
            ->first();
            if ($sectionIcon) {
                $sectionIconArray = (array)$sectionIcon;
            } else {
                $sectionIconArray = [];
            }

        $sectionName = DB::table('tb_section_name')->where('user_id', $userId)->get()->toArray();
        
        $masterMenus = [];
        foreach ($sectionIconArray as $key => $value) {
            $masterMenus[$key] = [
                "status" => $sectionStatusArray[$key],
                "icon_image" => $value,
            ];
        }
        // dd($masterMenus,$sectionName);
        return [
            'user'                  => $user,
            'carouselImages'        => $carouselImages,
            'services'              => $services,
            'clientsReviewCount'    => $clientsReviewCount,
            'clientReviewsAvg'      => $clientReviewsAvg,
            'otherLinkArr'          => $otherLinkArr,
            'masterMenus'           => $masterMenus,
            'sectionName'           => $sectionName,
        ];
    }
   
    public function showHome($slug)
    {

        $data = $this->commonDetails($slug);
        $user = $data['user'];
        $clientsReviewCount = $data['clientsReviewCount'];
        $clientReviewsAvg = $data['clientReviewsAvg'];
        $otherLinkArr = $data['otherLinkArr'];
        $masterMenus = $data['masterMenus'];
        $sectionName = $data['sectionName'];
        

        // dd($user);
        $carouselImages = $data['carouselImages'];
        $services = $data['services'];
        $themeView = 'card_theme1'; // Default theme

        return view($themeView . '.index', compact('user', 'carouselImages', 'slug', 'services', 'clientsReviewCount', 'clientReviewsAvg', 'otherLinkArr','masterMenus', 'sectionName'));
    }
    public function showCompany($slug)
    {
        $data = $this->commonDetails($slug);
        $user = $data['user'];
        $clientsReviewCount = $data['clientsReviewCount'];
        $clientReviewsAvg = $data['clientReviewsAvg'];
        $carouselImages = $data['carouselImages'];
        $services = $data['services'];
        $otherLinkArr = $data['otherLinkArr'];
        $masterMenus = $data['masterMenus'];
        $sectionName = $data['sectionName'];
        $userId = $user->id;
        // $teams = Cache::remember('teams', 10, function () use ($userId) {
            $teams = DB::table('tb_our_team')->where('user_id', $userId)->orderBy('position_order', 'ASC')->get()->map(function ($teams) {
                return [
                    'name' => $teams->name,
                    'designation' => $teams->designation,
                    'img_name' => $teams->img_name,
                    'dg_link' => $teams->dg_link,
                    'c_number' => $teams->c_number,
                    'w_number' => $teams->w_number,
                    'status' => $teams->status,
                ];
            });
        // });
        $themeView = 'card_theme1'; // Default theme
        return view($themeView . '.company', compact('user', 'carouselImages', 'slug', 'teams', 'clientsReviewCount', 'clientReviewsAvg', 'otherLinkArr', 'masterMenus', 'sectionName'));
    }
    public function showGallery($slug)
    {
        $data = $this->commonDetails($slug);
        $user = $data['user'];
        $clientsReviewCount = $data['clientsReviewCount'];
        $clientReviewsAvg = $data['clientReviewsAvg'];
        $carouselImages = $data['carouselImages'];
        $services = $data['services'];
        $otherLinkArr = $data['otherLinkArr'];
        $masterMenus = $data['masterMenus'];
        $sectionName = $data['sectionName'];
        $userId = $user->id;
        // $images = Cache::remember('images', 10, function () use ($userId) {
            $images = DB::table('tb_image')->where('user_id', $userId)->where('status', 1)->orderBy('position_order', 'ASC')->get()->map(function ($images) {
                return [
                    'image_name' => $images->image_name,
                    'img_name'   => $images->img_name,
                ];
            });
        // });

        // $videos = Cache::remember('videos', 10, function () use ($userId) {
            $videos = DB::table('tb_video')->where('user_id', $userId)->where('status', 1)->get()->map(function ($videos) {
                return [
                    'video_link' => $videos->video_link,
                ];
            });
        // });
        //  dd($videos);
        // Determine which theme view to use
        $themeView = 'card_theme1'; // Default theme
        return view($themeView . '.gallery', compact('user', 'carouselImages', 'slug', 'images', 'videos', 'clientsReviewCount', 'clientReviewsAvg', 'otherLinkArr', 'masterMenus', 'sectionName'));
    }
    public function showClients($slug)
    {
        $data = $this->commonDetails($slug);
        $user = $data['user'];
        $clientsReviewCount = $data['clientsReviewCount'];
        $clientReviewsAvg = $data['clientReviewsAvg'];
        $carouselImages = $data['carouselImages'];
        $userId = $user->id;
        $otherLinkArr = $data['otherLinkArr'];
        $masterMenus = $data['masterMenus'];
        $sectionName = $data['sectionName'];
        // $clients = Cache::remember('clients', 10, function () use ($userId) {
            $clients = DB::table('tb_clients')->where('user_id', $userId)->where('status', 1)->orderBy('position_order', 'ASC')->get()->map(function ($clients) {
                return [
                    'name'     => $clients->name,
                    'img_name' => $clients->img_name,
                ];
            });
        // });

        // $clientReviews = Cache::remember('clientReviews', 10, function () use ($userId) {
            $clientReviews = DB::table('tb_client_review')->where('user_id', $userId)->where('status', 1)->get()->map(function ($clientReviews) {
                return [
                    'name'            => $clientReviews->name,
                    'description'     => $clientReviews->description,
                    'img_name'        => $clientReviews->img_name,
                    'rating_number'   => $clientReviews->rating_number,
                    'created_date'   => $clientReviews->created_date,
                ];
            });
        // });

        $themeView = 'card_theme1'; // Default theme

        return view($themeView . '.clients', compact('user', 'carouselImages', 'slug', 'clients', 'clientReviews', 'clientsReviewCount', 'clientReviewsAvg', 'otherLinkArr', 'masterMenus', 'sectionName'));
    }

    public function showPayments($slug)
    {

        $data = $this->commonDetails($slug);
        $user = $data['user'];
        $clientsReviewCount = $data['clientsReviewCount'];
        $clientReviewsAvg = $data['clientReviewsAvg'];
        $carouselImages = $data['carouselImages'];
        $userId = $user->id;
        $otherLinkArr = $data['otherLinkArr'];
        $masterMenus = $data['masterMenus'];
        $sectionName = $data['sectionName'];
        // $bankDetails = Cache::remember('bank_details', 10, function () use ($userId) {
            $bankDetails = DB::table('tb_bank_details')->where('user_id', $userId)->first();
        // });
        $bankDetail = [
            "id" => $bankDetails->id,
            "user_id" => $bankDetails->user_id,
            "name" => $this->decryptCustom($bankDetails->name),
            "bank_name" =>  $this->decryptCustom($bankDetails->bank_name),
            "account_number" => $this->decryptCustom($bankDetails->account_number),
            "ifsc_code" => $this->decryptCustom($bankDetails->ifsc_code),
            "status" => "1",
            "default_bank" => 1,
        ];
        $bank_details_content = "IFSC Code: " . $bankDetail['ifsc_code'] . " | Account Number: " . $bankDetail['account_number'] . " | Bank Name: " . $bankDetail['bank_name'] . " | Name: " . $bankDetail['name'];

        $upiDetails = DB::table('tb_gateway')->where('user_id', $userId)->first();
        $paypalDetails = DB::table('tb_paypal')->where('user_id', $userId)->first();

        // dd($bankDetail);
        // Determine which theme view to use
        $themeView = 'card_theme1'; // Default theme
        return view($themeView . '.payments', compact('user', 'carouselImages', 'slug', 'bankDetail', 'bank_details_content', 'upiDetails', 'paypalDetails', 'clientsReviewCount', 'clientReviewsAvg', 'otherLinkArr', 'masterMenus', 'sectionName'));
    }

    public function decryptCustom($string, $key = 5)
    {
        $result = '';
        $string = base64_decode($string);
        for ($i = 0, $k = strlen($string); $i < $k; $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result .= $char;
        }
        return $result;
    }

    public function showProducts($slug)
    {
        $data = $this->commonDetails($slug);
        $user = $data['user'];
        $clientsReviewCount = $data['clientsReviewCount'];
        $clientReviewsAvg = $data['clientReviewsAvg'];
        $carouselImages = $data['carouselImages'];
        $services = $data['services'];
        $masterMenus = $data['masterMenus'];
        $sectionName = $data['sectionName'];
        $themeView = 'card_theme1'; // Default theme

        return view($themeView . '.products', compact('user', 'carouselImages', 'slug', 'services', 'clientsReviewCount', 'clientReviewsAvg', 'masterMenus', 'sectionName'));
    }

    public function showServices($slug)
    {
        $data = $this->commonDetails($slug);
        $user = $data['user'];
        $clientsReviewCount = $data['clientsReviewCount'];
        $clientReviewsAvg = $data['clientReviewsAvg'];
        $carouselImages = $data['carouselImages'];
        $services = $data['services'];
        $masterMenus = $data['masterMenus'];
        $sectionName = $data['sectionName'];
        $themeView = 'card_theme1'; // Default theme

        return view($themeView . '.services', compact('user', 'carouselImages', 'slug', 'services', 'clientsReviewCount', 'clientReviewsAvg', 'masterMenus', 'sectionName'));
    }

    function parse_url_all($url)
    {
        $url = substr($url, 0, 4) == 'http' ? $url : 'http://' . $url;
        $d = parse_url($url);
        $tmp = explode('.', $d['host']);
        $n = count($tmp);
        if ($n >= 2) {
            if ($n == 4 || ($n == 3 && strlen($tmp[($n - 2)]) <= 3)) {
                $d['domain'] = $tmp[($n - 3)] . "." . $tmp[($n - 2)] . "." . $tmp[($n - 1)];
                $d['domainX'] = $tmp[($n - 3)];
            } else {
                $d['domain'] = $tmp[($n - 2)] . "." . $tmp[($n - 1)];
                $d['domainX'] = $tmp[($n - 2)];
            }
        }
        return $d;
    }
}
