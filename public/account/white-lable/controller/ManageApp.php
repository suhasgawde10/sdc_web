<?php
session_start();
include("controller.php");

class ManageApp
{

    public $loginTable = "tb_admin_login";
    public $categoryTable = "tbl_category";
    public $categoryTbl = "tb_category";
    public $sizeTable = "tbl_size";
    public $sizeVariantTable = "tbl_size_variant";
    public $productTable = "tbl_product";
    public $sliderTable = "tb_slider";
    public $summerImageTable = "tbl_summer_image";
    public $countryTable = "tb_countries";
    public $statesTable = "tb_states";
    public $citiesTable = "tb_cities";
    public $userSubscriptionTable = "tb_user_subscription";
    public $reviewTable = "tb_review";
    public $colorVariantTable = "tb_color_variant";
    public $customerTable = "tb_customer_detail";
    public $orderTable = "tb_product_order";
    public $configTable = "tb_configuration";



    function sendMail($toName, $toEmail,$cc_mail, $subject, $message)
    {
        $sendMail = new sendMailSystem();
        $status = false;
        $sendMailStatus = $sendMail->sendMail($toName, $toEmail, MAIL_FROM_NAME, MAIL_FROM_EMAIL,$cc_mail, $subject, $message);
        if ($sendMailStatus) {
            $status = true;
        } else {
            $status = false;
        }
        return $status;
    }

    function sendSMS($contact, $message)
    {

        $sendSmsUrl = SMS_URL . "?username=" . urlencode(SMS_USERNAME) . "&apikey=" . urlencode(SMS_APIKEY) . "&apirequest=Text&sender=" . urlencode(SMS_SENDER) . "&mobile=" . urlencode($contact) . "&message=" . urlencode($message) . "&route=TRANS&format=JSON";
        /*        $sendSmsUrl = SMS_URL . "?username=" . urlencode(SMS_USERNAME) . "&sender=" . urlencode($sender_id) . "&to=" . urlencode($contact) . "&message=" . urlencode($message) . "&reqid=1&format=json";*/
        $sendSmsUrl1 = str_replace(" ", "%20", $sendSmsUrl);
        $json = file_get_contents($sendSmsUrl1);
        $json = json_decode($json);
        if ($json->status == "success") {
            return true;
        } else {
            return false;
        }
    }
    function getSizeDetails()
    {
        $controller = new Controller();
        /*$sql_query = "select st.* from " . $this->sizeVariantTable . " as st inner join " . $this->categoryTable . " as ct on ct.id = st.cat_id group by st.size";*/
        $sql_query = "select * from tbl_size group by size";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getTableDetails($table)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $table . " ORDER BY id DESC";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getProductByCategory($cat_id,$product_id)
    {
        $controller = new Controller();
        $sql_query = "select pt.*,cv.id as color_v_id ,cv.product_id,cv.name,cv.image,cv.product_qty from " . $this->productTable . " as pt inner join " . $this->colorVariantTable . " as cv on cv.product_id = pt.id WHERE pt.cat_id = ". $cat_id ." AND cv.product_qty > 0 AND pt.id != ". $product_id ." ORDER BY pt.id Desc";

//        echo $sql_query;
//        exit;

        /*select pt.*,cv.id as color_v_id ,cv.product_id,cv.name,cv.image,cv.product_qty from " . $this->productTable . " as pt inner join " . $this->colorVariantTable . " as cv on cv.product_id = pt.id WHERE pt.cat_id = ". $cat_id ." AND cv.product_qty > 0 AND pt.id = ". $product_id ." ORDER BY pt.id Desc */

        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getColorVarientByProductId($product,$color_id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM ". $this->colorVariantTable ." WHERE product_id = ".$product." AND id !=".$color_id ." AND product_qty >=1 ORDER BY id DESC";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getProductDetails($start,$end)
    {
        $controller = new Controller();
        $sql_query = "select pt.*,ct.name from " . $this->productTable . " as pt inner join " . $this->categoryTable . " as ct on ct.id=pt.cat_id ORDER BY pt.id DESC limit $start,$end";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }
    function getProductDetailsByProductId($pr_id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM ".$this->productTable." where id = ".$pr_id." ";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }
    function getProductTaxDetailsByProductId($pr_id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM ".$this->productTable." where id = ".$pr_id." ";
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    function getProductDetailsCount()
    {
        $controller = new Controller();
        $sql_query = "select count(pt.id) as product_count from " . $this->productTable . " as pt inner join " . $this->categoryTable . " as ct on ct.id=pt.cat_id ORDER BY pt.id DESC";
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        if($result['product_count'] !=null){
            return $result['product_count'];
        }else{
            return 0;
        }
    }

    function getProductDetailsByLimit()
    {
        $controller = new Controller();
        $sql_query = "select pt.*,ct.name from " . $this->productTable . " as pt inner join " . $this->categoryTable . " as ct on ct.id=pt.cat_id ORDER BY pt.id DESC limit 0,10";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getProductDetailsBySearchCount($cat_id)
    {
        $controller = new Controller();
        $sql_query = "select count(pt.id) as product_count from " . $this->productTable . " as pt inner join " . $this->categoryTbl . " as ct on ct.id=pt.cat_id ";
        if ($cat_id !="") {
            $sql_query .= "where pt.cat_id='$cat_id' order by pt.id desc";
        } else {
            $sql_query .= "order by pt.id desc";
        }
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        if($result['product_count'] !=null){
            return $result['product_count'];
        }else{
            return 0;
        }
    }

    function getProductDetailsBySearch($cat_id,$start,$end)
    {
        $controller = new Controller();
        $sql_query = "select pt.*,ct.category_name from " . $this->productTable . " as pt inner join " . $this->categoryTbl . " as ct on ct.id=pt.cat_id ";

        if ($cat_id !="") {
            $sql_query .= "where pt.cat_id='$cat_id' order by pt.id desc limit $start,$end";
        } else {
            $sql_query .= "order by pt.id desc limit $start,$end";
        }
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getProductDetailsByCategory($cat_id)
    {
        $controller = new Controller();
        $sql_query = "select pt.*,ct.category_name from " . $this->productTable . " as pt inner join " . $this->categoryTbl . " as ct on ct.id=pt.cat_id ";
//        echo $sql_query;
//        exit;
        if ($cat_id !="") {
            $sql_query .= "where pt.cat_id='$cat_id' order by pt.id desc";
        } else {
            $sql_query .= "order by pt.id desc limit 5";
        }
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getProductSearchData($cat_id,$shot_by,$start,$end)
    {
        $controller = new Controller();
        $sql_query = "select pt.*,ct.category_name from " . $this->productTable . " as pt inner join " . $this->categoryTbl . " as ct on ct.id=pt.cat_id ";


        if ($cat_id !="" && $shot_by == "Newest") {
            $sql_query .= "where pt.cat_id='$cat_id' order by pt.id desc limit $start,$end";
        } else {
            $sql_query .= "where pt.cat_id='$cat_id' order by pt.id asc limit $start,$end";
        }
//        echo $sql_query;
//        exit;

        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }
    function getProductSearchDataByPrice($cat_id,$price,$start,$end)
    {
        $controller = new Controller();
        $sql_query = "select pt.*,ct.category_name from " . $this->productTable . " as pt inner join " . $this->categoryTbl . " as ct on ct.id=pt.cat_id ";


        if ($cat_id !="" && $price == "high") {
            $sql_query .= "where pt.cat_id='$cat_id' order by pt.actual_price ASC limit $start,$end";
        } else {
            $sql_query .= "where pt.cat_id='$cat_id' order by pt.actual_price DESC limit $start,$end";
        }
//        echo $sql_query;
//        exit;
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }



    function getProductsDetailsById($cat_id,$product_id)
    {
        $controller = new Controller();
        $sql_query = "select pt.*,cv.id as color_v_id ,cv.product_id,cv.name,cv.image,cv.product_qty from " . $this->productTable . " as pt inner join " . $this->colorVariantTable . " as cv on cv.product_id = pt.id WHERE pt.cat_id = ". $cat_id ." AND cv.product_qty > 0 AND pt.id = ". $product_id ." ORDER BY pt.id Desc limit 1";
/*            echo $sql_query;
            exit();*/
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }


    function getProductsStockDetailsById($product_id,$color_id,$cat_id)
    {
        $controller = new Controller();
        $sql_query = "select pt.*,cv.id as color_v_id ,cv.product_id,cv.name,cv.image,cv.product_qty from " . $this->productTable . " as pt inner join " . $this->colorVariantTable . " as cv on cv.product_id = pt.id WHERE pt.cat_id = ". $cat_id ." AND cv.id = ".$color_id." AND pt.id = ". $product_id ." ORDER BY pt.id Desc limit 1";

//        echo $sql_query;
//        exit;
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getProductsDetailsWithColorById($cat_id,$product_id,$color_name)
    {
        $controller = new Controller();
        $sql_query = "select pt.*,cv.id as color_v_id ,cv.product_id,cv.name,cv.image,cv.product_qty from " . $this->productTable . " as pt inner join " . $this->colorVariantTable . " as cv on cv.product_id = pt.id WHERE pt.cat_id = ". $cat_id ." AND cv.product_qty > 0 AND cv.name='".$color_name."'  AND pt.id = ". $product_id ." ORDER BY pt.id Desc limit 1";
//            echo $sql_query;
//            exit();
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getProductsQtyDetailsById($cat_id,$product_id)
    {
        $controller = new Controller();
        $sql_query = "select pt.*,cv.id as color_v_id ,cv.product_id,cv.name,cv.image,cv.product_qty from " . $this->productTable . " as pt inner join " . $this->colorVariantTable . " as cv on cv.product_id = pt.id WHERE pt.cat_id = ". $cat_id ." AND cv.product_qty <= 0 AND pt.id = ". $product_id ." ORDER BY pt.id Desc limit 1";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getAllProductData()
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM ".$this->productTable." ORDER by id DESC";
//        echo $sql_query;
//        exit;
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }
    function getAllHomeProductData()
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM ".$this->productTable." ORDER by id DESC limit 8";
//        echo $sql_query;
//        exit;
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }
    function getAllTopSellingProductData()
    {
        $controller = new Controller();
        $sql_query = "SELECT *,COUNT(pro_id) AS repeted FROM ".$this->orderTable." GROUP BY pro_id ORDER BY repeted DESC";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getHomeTopSellingProductData()
    {
        $controller = new Controller();
        $sql_query = "SELECT *,COUNT(pro_id) AS repeted FROM ".$this->orderTable." GROUP BY pro_id ORDER BY repeted DESC limit 8";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getCategoryDataHeader()
    {
        $controller = new Controller();
        $sql_query = "SELECT COUNT(cat_id), tp.*, tc.*   FROM ".$this->productTable." as tp INNER JOIN ".$this->colorVariantTable." as tc ON tp.id = tc.product_id;";
//        echo $sql_query;
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }
    function getCategoryData()
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM ". $this->categoryTbl ." WHERE parent_id = 0 Order by category_name Asc";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }
    function getCategoryDataParent($cat_id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM ". $this->categoryTbl ." WHERE parent_id = ".$cat_id." ";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }
    function getCategoryChildrenExists($parent_id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM " . $this->categoryTbl . " WHERE parent_id = " . $parent_id . " ";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getProductDetailsBySearchTitle($cat_id,$start,$end)
    {
        $controller = new Controller();
        $sql_query = "select pt.*,ct.name from " . $this->productTable . " as pt inner join " . $this->categoryTable . " as ct on ct.id=pt.cat_id ";
        if ($cat_id !="") {
            $sql_query .= "where pt.cat_id='$cat_id' order by pt.id desc limit 7";
        } else {
            $sql_query .= "order by pt.id desc limit 7";
        }
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getProductDetailsById($cat_id,$color_id,$id)
    {
        $controller = new Controller();
//        $sql_query = "select pt.*,ct.category_name from " . $this->productTable . " as pt inner join " . $this->categoryTbl . " as ct on ct.id=pt.cat_id where pt.id=" . $id;
        $sql_query ="select pt.*,cv.id as color_v_id ,cv.product_id,cv.name,cv.image,cv.product_qty from ". $this->productTable ." as pt inner join ". $this->colorVariantTable ." as cv on cv.product_id = pt.id WHERE pt.cat_id = ". $cat_id ." AND cv.id =". $color_id ." AND cv.product_qty > 0 AND pt.id = ". $id ." ORDER BY pt.id ASC LIMIT 1";

        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    function getTableDetailsByID($table, $id)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $table . " where id=" . $id;
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }


    function getProductBySize($id,$cat_id)
    {
        $controller = new Controller();
        $sql_query = "select count(id) as total_product,img_name from " . $this->productTable . " where size_id like '%" . $id . "%' and cat_id='$cat_id' order by id desc limit 1";
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }


    function getSizeDetailsBySize($size)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $this->sizeTable . " where size='$size'";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getCatDetailsFromProduct()
    {
        $controller = new Controller();
        $sql_query = "select * from " . $this->productTable . " as st inner join " . $this->categoryTable . " as ct on ct.id=st.cat_id group by cat_id order by cat_id desc";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getSizeBycatId($id)
    {
        $controller = new Controller();
        $sql_query = "SELECT
  GROUP_CONCAT(DISTINCT SUBSTRING_INDEX(SUBSTRING_INDEX(size_id, ',', n.digit+1), ',', -1)) size_id
FROM
  tbl_product
  INNER JOIN
  (SELECT 0 digit UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3  UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6) n
  ON LENGTH(REPLACE(size_id, ',' , '')) <= LENGTH(size_id)-n.digit where cat_id=".$id;
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    function getActiveTableDetails($table)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $table . " where status=1 ORDER BY id DESC";
//        echo $sql_query;
//        exit;
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }


    public function insert($table, $data)
    {
        $controller = new Controller();
        if (!empty($data) && is_array($data)) {
            $colname = '';
            $colval = '';
            $i = 0;
            /*    if(!array_key_exists('modified',$data)){
                    $data['modified'] = date("Y-m-d H:i:s");
                }*/
            foreach ($data as $key => $val) {
                $pre = ($i > 0) ? ', ' : '';
                $colname .= $pre . $key;
                $colval .= $pre . "'$val'";
                $i++;
            }

            $query = "insert into " . $table . " (" . $colname . ") values (" . $colval . ") ";
//            echo $query;
//            exit();
            $result = $controller->genericGetLastInsertedId($query);
            return $result;
        } else {
            return false;
        }
    }


    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
    function getReviewDetailsById($pro_id,$cust_name)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $this->reviewTable . " where pro_id='$pro_id' ";
        if($cust_name !=''){
            $sql_query .=" and name like '%$cust_name' ";
        }
        $sql_query .=" ORDER BY id DESC";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }


    function getReviewDetails($start,$end)
    {
        $controller = new Controller();
        $sql_query = "select rt.*,pt.title from " . $this->reviewTable . " as rt inner join " . $this->productTable . " as pt on pt.id=rt.pro_id ORDER BY id DESC limit $start,$end";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }
    function getReviewDetailsCount()
    {
        $controller = new Controller();
        $sql_query = "select COUNT(id) as total_count from " . $this->reviewTable . " ORDER BY id DESC";
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        if($result['total_count'] !=null){
            return $result['total_count'];
        }else{
            return 0;
        }

    }
    function getCountryCategory()
    {
        $controller = new Controller();
       $sql = "SELECT * FROM " . $this->countryTable;
        $result = $controller->genericSelectToIterate($sql);
        return $result;
    }
    function getCityDataByStateID($state_id)
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->userSubscriptionTable . " where user_id='" . $id . "' order by id desc";
        $sql = "SELECT * FROM ".$this->citiesTable." WHERE state_id='$state_id'";
        $result = $controller->genericSelectToIterate($sql);
        return $result;
    }
    function getStateCategory($state)
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->userSubscriptionTable . " where user_id='" . $id . "' order by id desc";
        $sql = "SELECT * FROM ".$this->statesTable." WHERE country_id='$state'";
        $result = $controller->genericSelectToIterate($sql);
        return $result;
    }
    function getStateById($state)
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->userSubscriptionTable . " where user_id='" . $id . "' order by id desc";
        $sql = "SELECT * FROM ".$this->statesTable." WHERE id='$state'";
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql);
        return $result;
    }
    function getCityById($state)
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->userSubscriptionTable . " where user_id='" . $id . "' order by id desc";
        $sql = "SELECT * FROM ".$this->citiesTable." WHERE id='$state'";
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql);
        return $result;
    }
    function getCountryById($country)
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->userSubscriptionTable . " where user_id='" . $id . "' order by id desc";
        $sql = "SELECT * FROM ".$this->countryTable ." WHERE id='$country'";
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql);
        return $result;
    }

    function getLastInvoiceNumber($currency_type='INR')
    {
        $controller = new Controller();
        $sql = "call mu_getLastInvoiceNumber(?)";
        $type = "s";
        $param = array($currency_type);
        $get = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        return $get;
    }
    function insertUserDataForRazor($status, $invoice_no, $total_amount, $payment_brand, $payment_mode, $bankId, $timestamp, $payment_type,$for_bill,$for_email,$user_gstno,$p_name, $p_contact_no, $p_country, $p_state, $p_city, $p_pin_code, $p_user_address,$from_bill,$from_gstno,$from_pan,$sac_code,$order_id,$payment_id,$error_code,$error_desc)
    {
        $controller = new Controller();
       $procedure = "CALL mu_insertUserDataForPayzor('$status','$invoice_no', '$total_amount', '$payment_brand', '$payment_mode', '$bankId', '$timestamp','$payment_type','$for_bill','$for_email','$user_gstno','$p_name', '$p_contact_no', '$p_country', '$p_state', '$p_city', '$p_pin_code', '$p_user_address','$from_bill','$from_gstno','$from_pan','$sac_code','$order_id','$payment_id','$error_code','$error_desc',@p_out_param)";
        $mysqli = $controller->connect();

        $results1 = $mysqli->query($procedure);
        $results2 = $mysqli->query("SELECT @p_out_param as last_id");
        $row = $results2->fetch_object();
        return $row->last_id;
    }

    function insertUserProductData($pro_id,$p_subscription_id,$p_pro_name,$p_quantity=1,$p_price)
    {
        $controller = new Controller();
        $procedure = "CALL mu_insertUserProductData('$pro_id','$p_subscription_id','$p_pro_name','$p_quantity','$p_price')";
        $mysqli = $controller->connect();
        $result = $mysqli->query($procedure);
        return $result;
    }
    function getUserInvoiceData($id)
    {
        $controller = new Controller();
        $sql = "call mu_getUserInvoiceData(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql,$type,$param);
        var_dump($result);
        return $result;
    }

    function getUserInvoiceProductData($id)
    {
        $controller = new Controller();
        $sql = "call mu_getUserInvoiceProductData(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectToIterateUsingProcedure($sql,$type,$param);
        return $result;
    }
    function getLastCustomerId()
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->userSubscriptionTable . " where user_id='" . $id . "' order by id desc";
        $sql = "SELECT * FROM ".$this->customerTable ." Order by id desc limit 1";
//        echo $sql;
//        exit;
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql);
        return $result;
    }
    function getTaxByProductId($product_id)
    {
        $controller = new Controller();
//        $sql = "SELECT * FROM ".$this->productTable ." WHERE id = ".$product_id." ";
        $sql = "select pt.*,cv.id as color_v_id ,cv.product_id,cv.name,cv.image,cv.product_qty from ".$this->productTable." as pt inner join ".$this->colorVariantTable." as cv on cv.product_id = pt.id WHERE cv.product_qty > 0 AND pt.id = ".$product_id." ORDER BY pt.id Desc limit 1 ";
//        echo $sql;
//        exit;
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql);
        return $result;
    }

    function getProductDataByCustomerId($customer_id)
    {
        $controller = new Controller();
        $sql = "SELECT * FROM ".$this->orderTable." WHERE customer_id='$customer_id'";
        $result = $controller->genericSelectToIterate($sql);
        return $result;
    }

    function getConfigData()
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM ".$this->configTable." LIMIT 1";
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    function Update_email($message,$id)
    {
        $controller = new Controller();
        $sql_query = "UPDATE tb_customer_detail SET order_email= '$message' WHERE id = $id ";
        $result = $controller->genericInsertUpdateDelete($sql_query);
        return $result;
    }

    function getLargeSlider($table)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $table . " where device = 1 ORDER BY id DESC";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }
    function getMobileSlider($table)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $table . " where device = 2 ORDER BY id DESC";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }



}