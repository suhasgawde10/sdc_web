<?php
session_start();
include("controller.php");


class ManageAdminApp
{

    public $loginTable = "tb_admin";
    public $dealerTable = "tb_dealer";
    public $planTable = "tb_plan";
    public $teamTable = "tb_team";
    public $testimonialTable = "tb_testimonail";
    public $themeTable = "tb_theme";
    public $franchiseTable = "franchise_price";
    public $otherServiceTable = "tb_other_services";

    public $categoryTbl = "tb_category";
    public $sizeVariantTable = "tbl_size_variant";
    public $productTable = "tbl_product";
    public $sliderTable = "tb_slider";
    public $sizeTable = "tbl_size";
    public $summerImageTable = "tbl_summer_image";
    public $countryTable = "tb_countries";
    public $statesTable = "tb_states";
    public $citiesTable = "tb_cities";
    public $userSubscriptionTable = "tb_user_subscription";
    public $colorVariantTable = "tb_color_variant";
    public $customerTable = "tb_customer_detail";
    public $orderTable = "tb_product_order";
    public $incomeTable = "tb_income";
    public $configTable = "tb_configuration";


    function adminLogin($email, $pass)
    {
        $status = false;
        $controller = new Controller();
        $sql_query = "select * from " . $this->dealerTable . " where email_id='" . $email . "' and password='" . $pass . "'";

//        echo $sql_query;
//        exit;
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    function validateUserEmail($email)
    {
        $result = false;
        $controller = new Controller();
        $sql = "select * from " . $this->dealerTable . " where email_id ='" . $email . "' and status=1";
        $result = $controller->genericSelectAlreadyIterated($sql);
        return $result;
    }

    function sendMail($toName, $toEmail, $subject, $message)
    {
        $sendMail = new sendMailSystem();
        $status = false;
        $sendMailStatus = $sendMail->sendMail($toName, $toEmail, MAIL_FROM_NAME, MAIL_FROM_EMAIL, $subject, $message);

        if ($sendMailStatus) {
            $status = true;
        } else {
            $status = false;
        }
        return $status;
    }

    function sendMailWithBCC($toName, $toEmail, $subject, $message, $bcc)
    {
        $sendMail = new sendMailSystem();
        $status = false;
        $sendMailStatus = $sendMail->sendMailWithBCC($toName, $toEmail, MAIL_FROM_NAME, MAIL_FROM_EMAIL, $subject, $message, $bcc);
        if ($sendMailStatus) {
            $status = true;
        } else {
            $status = false;
        }
        return $status;
    }

    function updatePasswordForForget($password, $email)
    {
        $controller = new Controller();
        $sql_query = "update " . $this->dealerTable . " set password='" . $password . "' where email_id ='" . $email . "'";
        $result = $controller->genericInsertUpdateDelete($sql_query);
        return $result;
    }

    function resetUserPassword($old_password, $new_password)
    {
        $status = false;
        $controller = new Controller();
        $query = "select password from " . $this->dealerTable . " where id='" . $_SESSION['id'] . "'";
        $result = $controller->genericSelectAlreadyIterated($query);
        $user_password = $result["password"];
        $validOldPassword = false;
        if ($user_password == $old_password) {
            $validOldPassword = true;
        }
        if ($validOldPassword) {
            $updateQuery = "update " . $this->dealerTable . " set password='" . $new_password . "' where id='" . $_SESSION['id'] . "'";
            $status = $controller->genericInsertUpdateDelete($updateQuery);
        }
        return $status;
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
            /*echo $query;
            exit();*/
            $result = $controller->genericGetLastInsertedId($query);
            return $result;
        } else {
            return false;
        }
    }

    // master updating the record in database and it uses three parameter $table->used for table name, $data->data in array ,and condition

    /**
     * @param $table
     * @param $data
     * @param $conditions
     * @return bool
     */
    public function update($table, $data, $conditions)
    {
        $controller = new Controller();
        if (!empty($data) && is_array($data)) {
            $colvalSet = '';
            $whereSql = '';
            $i = 0;
            /*    if(!array_key_exists('modified',$data)){
                    $data['modified'] = date("Y-m-d H:i:s");
                }*/
            foreach ($data as $key => $val) {
                $pre = ($i > 0) ? ', ' : '';
                $colvalSet .= $pre . $key . "='" . $val . "'";
                $i++;
            }
            if (!empty($conditions) && is_array($conditions)) {
                $whereSql .= ' WHERE ';
                $i = 0;
                foreach ($conditions as $key => $value) {
                    $pre = ($i > 0) ? ' AND ' : '';
                    $whereSql .= $pre . $key . " = '" . $value . "'";
                    $i++;
                }
            }
            $query = "UPDATE " . $table . " SET " . $colvalSet . $whereSql;
            echo $query;
            exit();
            $result = $controller->genericInsertUpdateDelete($query);
            return $result;
        } else {
            return false;
        }
    }

    function blockUnblock($id, $block_status, $tableName)
    {
        $controller = new Controller();
        $updateQuery = "update " . $tableName . " set status=" . $block_status . " where id=" . $id . "";
        $status = $controller->genericInsertUpdateDelete($updateQuery);
        return $status;
    }

    function deleteData($table, $id)
    {
        $controller = new Controller();
        $query = "delete from " . $table . " where id ='" . $id . "'";
        $status = $controller->genericInsertUpdateDelete($query);
        return $status;
    }

    function getTableDetails($table)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $table . " ORDER BY id DESC";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getCategoryDetails($table)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $table . " ORDER BY id ASC";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getCategoryById($table, $id)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $table . " Where id = " . $id . " Order by id DESC limit 1 ";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }


    function getCategoryNotInSize()
    {
        $controller = new Controller();
        $query = "select * from " . $this->sizeVariantTable . " WHERE cat_id !=''";
        $result_data = $controller->genericSelectAlreadyIterated($query);
        if ($result_data != null) {
            $new_query = "SELECT GROUP_CONCAT(DISTINCT SUBSTRING_INDEX(SUBSTRING_INDEX(cat_id, ',', n.digit+1), ',', -1)) cat_id FROM tbl_size_variant INNER JOIN (SELECT 0 digit UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6) n ON LENGTH(REPLACE(cat_id, ',' , '')) <= LENGTH(cat_id)-n.digit";
            $get_result = $controller->genericSelectAlreadyIterated($new_query);
            $cat_id = $get_result['cat_id'];
            $sql_query = "select * from tbl_category where id not in ($cat_id) ORDER BY id DESC";
        } else {
            $sql_query = "select * from tbl_category  ORDER BY id DESC";
        }
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getAllSizeBySize()
    {
        $controller = new Controller();
        $sql_query = "select * from " . $this->sizeTable . " ORDER BY size ASC";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getProductDetails()
    {
        $controller = new Controller();
        $sql_query = "select pt.*,ct.category_name,cv.id as color_id,cv.product_id,cv.name,cv.image,cv.product_qty from " . $this->productTable . " as pt inner join " . $this->categoryTbl . " as ct on ct.id=pt.cat_id inner join " . $this->colorVariantTable . " as cv on cv.id=pt.id ORDER BY pt.id DESC";
//        echo $sql_query;
//        exit;
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getProductDetailsById($product_id)
    {
        $controller = new Controller();
        $sql_query = "select pt.*,cv.id as color_v_id ,cv.product_id,cv.name,cv.image,cv.product_qty from " . $this->productTable . " as pt inner join " . $this->colorVariantTable . "  as cv on cv.product_id = pt.id  WHERE cv.product_id = " . $product_id . " ";
//        echo $sql_query;
//        exit();
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getColorVarientDetailsByProductId($product_id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM tb_color_variant WHERE product_id = " . $product_id . " ";
//        echo $sql_query;
//        exit();
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getColorVarientImageByProductId($product_id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM tb_color_variant WHERE product_id = " . $product_id . " limit 1";
//        echo $sql_query;
//        exit();
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getColorVarientStockByProductId($product_id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM tb_color_variant WHERE product_id = " . $product_id . "  AND product_qty <=10";
//        echo $sql_query;
//        exit();
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getColorVarientDetailsByProductIdOrColorName($product_id, $color_name)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM tb_color_variant WHERE product_id = " . $product_id . " AND name = '" . $color_name . "' limit 1";
//        echo $sql_query;
//        exit();
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }


    function getActiveTableDetails($table)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $table . " where status=1 ORDER BY id DESC";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getSingleCategoryDetails($table)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $table . " where status=1 ORDER BY id DESC";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getActiveCategoryDetails($table)
    {
        $controller = new Controller();
        $sql_query = "SELECT c1.id,  c1.parent_id,  c1.category_name,  c2.category_name as `parent_title` FROM " . $table . " c1 left outer join " . $table . " c2 on c1.parent_id = c2.id ORDER BY c1.id ASC";
//        $sql_query = "select * from " . $table . " where parent_id = 0";s
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getCategoryData()
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM " . $this->categoryTbl . " WHERE parent_id = 0 Order by id DESC";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getCategoryDataParent($cat_id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM " . $this->categoryTbl . " WHERE parent_id = " . $cat_id . " Order by id DESC";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getCategoryDataParentById($cat_id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM " . $this->categoryTbl . " WHERE id = " . $cat_id . " Order by id DESC";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getPendingOrderCustomerDetails()
    {
        $controller = new Controller();
        $sql_query = "select * from " . $this->customerTable . " WHERE id IN(SELECT DISTINCT customer_id FROM " . $this->orderTable . " WHERE order_status = 1) ORDER by id DESC";
//        echo $sql_query;
//        exit;
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getCompleteCustomerDetails()
    {
        $controller = new Controller();
        $sql_query = "select * from " . $this->customerTable . " WHERE id IN(SELECT DISTINCT customer_id FROM " . $this->orderTable . " WHERE order_status = 2) ORDER BY id DESC";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getCancelCustomerDetails()
    {
        $controller = new Controller();
        $sql_query = "select * from " . $this->customerTable . " WHERE id IN(SELECT DISTINCT customer_id FROM " . $this->orderTable . " WHERE order_status = 0) ORDER BY id DESC";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }


    function getReturnRefundDetails()
    {
        $controller = new Controller();
        $sql_query = "select * from " . $this->customerTable . " WHERE id IN(SELECT DISTINCT customer_id FROM " . $this->orderTable . " WHERE order_status = 4) ORDER BY id DESC";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getFilterPendingOrderCustomerDetails($customer_name, $customer_mobile, $from_date, $to_date)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $this->customerTable . " WHERE id IN(SELECT DISTINCT customer_id FROM " . $this->orderTable . " WHERE order_status = 1) ";

        if ($customer_name != "" && $customer_mobile != "" && $from_date != "" && $to_date != "") {
            $sql_query .= "AND first_name LIKE'%" . $customer_name . "%' AND phone_no LIKE '%" . $customer_mobile . "%' AND created_at between '$from_date' AND '$to_date' ";

        } elseif ($customer_name != "" && $customer_mobile != "" && $from_date != "") {
            $sql_query .= "AND first_name LIKE'%" . $customer_name . "%' AND phone_no LIKE '%" . $customer_mobile . "%' AND created_at BETWEEN " . $from_date . " ";

        } elseif ($customer_name != "" && $customer_mobile != "") {
            $sql_query .= "AND first_name LIKE'%" . $customer_name . "%' AND phone_no LIKE '%" . $customer_mobile . "%'";

        } elseif ($customer_name != "") {
            $sql_query .= "AND first_name LIKE'%" . $customer_name . "%'";
        } elseif ($customer_mobile != "") {
            $sql_query .= "AND phone_no LIKE'%" . $customer_mobile . "%'";
        } elseif ($from_date != "" && $to_date != "") {
            $sql_query .= "AND created_at between '$from_date' AND '$to_date'";
        } else {
            $sql_query .= "ORDER by id DESC";
        }

//        echo $sql_query;
//        exit;
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getFilterCompleteOrderCustomerDetails($customer_name, $customer_mobile, $from_date, $to_date)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $this->customerTable . " WHERE id IN(SELECT DISTINCT customer_id FROM " . $this->orderTable . " WHERE order_status = 2) ";

        if ($customer_name != "" && $customer_mobile != "" && $from_date != "" && $to_date != "") {
            $sql_query .= "AND first_name LIKE'%" . $customer_name . "%' AND phone_no LIKE '%" . $customer_mobile . "%' AND created_at between '$from_date' AND '$to_date' ";

        } elseif ($customer_name != "" && $customer_mobile != "" && $from_date != "") {
            $sql_query .= "AND first_name LIKE'%" . $customer_name . "%' AND phone_no LIKE '%" . $customer_mobile . "%' AND created_at BETWEEN " . $from_date . " ";

        } elseif ($customer_name != "" && $customer_mobile != "") {
            $sql_query .= "AND first_name LIKE'%" . $customer_name . "%' AND phone_no LIKE '%" . $customer_mobile . "%'";

        } elseif ($customer_name != "") {
            $sql_query .= "AND first_name LIKE'%" . $customer_name . "%'";
        } elseif ($customer_mobile != "") {
            $sql_query .= "AND phone_no LIKE'%" . $customer_mobile . "%'";
        } elseif ($from_date != "" && $to_date != "") {
            $sql_query .= "AND created_at between '$from_date' AND '$to_date'";
        } else {
            $sql_query .= "ORDER by id DESC";
        }

//            echo $sql_query;
//            exit;
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getFilterCancelOrderCustomerDetails($customer_name, $customer_mobile, $from_date, $to_date)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $this->customerTable . " WHERE id IN(SELECT DISTINCT customer_id FROM " . $this->orderTable . " WHERE order_status = 0) ";

        if ($customer_name != "" && $customer_mobile != "" && $from_date != "" && $to_date != "") {
            $sql_query .= "AND first_name LIKE'%" . $customer_name . "%' AND phone_no LIKE '%" . $customer_mobile . "%' AND created_at between '$from_date' AND '$to_date' ";

        } elseif ($customer_name != "" && $customer_mobile != "" && $from_date != "") {
            $sql_query .= "AND first_name LIKE'%" . $customer_name . "%' AND phone_no LIKE '%" . $customer_mobile . "%' AND created_at BETWEEN " . $from_date . " ";

        } elseif ($customer_name != "" && $customer_mobile != "") {
            $sql_query .= "AND first_name LIKE'%" . $customer_name . "%' AND phone_no LIKE '%" . $customer_mobile . "%'";

        } elseif ($customer_name != "") {
            $sql_query .= "AND first_name LIKE'%" . $customer_name . "%'";
        } elseif ($customer_mobile != "") {
            $sql_query .= "AND phone_no LIKE'%" . $customer_mobile . "%'";
        } elseif ($from_date != "" && $to_date != "") {
            $sql_query .= "AND created_at between '$from_date' AND '$to_date'";
        } else {
            $sql_query .= "ORDER by id DESC";
        }

//        echo $sql_query;
//        exit;
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }


    function getFilterReturnOrderCustomerDetails($customer_name, $customer_mobile, $from_date, $to_date)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $this->customerTable . " WHERE id IN(SELECT DISTINCT customer_id FROM " . $this->orderTable . " WHERE order_status = 4) ";

        if ($customer_name != "" && $customer_mobile != "" && $from_date != "" && $to_date != "") {
            $sql_query .= "AND first_name LIKE'%" . $customer_name . "%' AND phone_no LIKE '%" . $customer_mobile . "%' AND created_at between '$from_date' AND '$to_date' ";

        } elseif ($customer_name != "" && $customer_mobile != "" && $from_date != "") {
            $sql_query .= "AND first_name LIKE'%" . $customer_name . "%' AND phone_no LIKE '%" . $customer_mobile . "%' AND created_at BETWEEN " . $from_date . " ";

        } elseif ($customer_name != "" && $customer_mobile != "") {
            $sql_query .= "AND first_name LIKE'%" . $customer_name . "%' AND phone_no LIKE '%" . $customer_mobile . "%'";

        } elseif ($customer_name != "") {
            $sql_query .= "AND first_name LIKE'%" . $customer_name . "%'";
        } elseif ($customer_mobile != "") {
            $sql_query .= "AND phone_no LIKE'%" . $customer_mobile . "%'";
        } elseif ($from_date != "" && $to_date != "") {
            $sql_query .= "AND created_at between '$from_date' AND '$to_date'";
        } else {
            $sql_query .= "ORDER by id DESC";
        }

//        echo $sql_query;
//        exit;
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getOrderByCustomerId($id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM tb_product_order WHERE customer_id = " . $id . " ORDER BY id ASC";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getPaymentByCustomerId($id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM tb_customer_detail WHERE id = " . $id . " ORDER BY id ASC";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getOrderByOrderId($id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM tb_product_order WHERE customer_id = " . $id . " ORDER BY id DESC";
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    /*function getOrderByAllCustomersId($id)
    {

        $controller = new Controller();
        $sql_query = "";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }*/


    function getTableDetailsByID($table, $id)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $table . " where id=" . $id;
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    function getColorDetailsByID($table, $id)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $table . " where product_id=" . $id;
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    function getSizeDetails()
    {
        $controller = new Controller();
        $sql_query = "select st.*,ct.name from " . $this->sizeVariantTable . " as st inner join " . $this->categoryTable . " as ct on ct.id = st.cat_id order by st.id desc";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getSizeDetailsById($id)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $this->sizeVariantTable . " as st inner join " . $this->categoryTable . " as ct on ct.id = st.cat_id where st.id=" . $id;
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    function getSizeDetailsByCatIdIterate($id)
    {
        $controller = new Controller();
        $sql_query = "select st.* from " . $this->sizeTable . " as st where 0 < FIND_IN_SET_X(st.id,(select size_id from " . $this->sizeVariantTable . " where cat_id=" . $id . ")) order by st.size asc";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }


    function addProduct($cat_id, $title, $price, $description, $size, $img_name)
    {
        $controller = new Controller();
        $sql_query = "insert into " . $this->productTable . " (cat_id,title,price,description,size,img_name,status) values('$cat_id','$title','$price','$description','$size','$img_name',1)";
        $result = $controller->genericInsertUpdateDelete($sql_query);
        return $result;
    }

    function validIdCheck($id)
    {
        $controller = new Controller();
        $query = "select * from " . $this->productTable . " where id=" . $id;
        $result = $controller->genericSelectCount($query);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }

    }

    function validateCategoryId($cat_id)
    {
        $controller = new Controller();
        $query = "select * from " . $this->categoryTable . " where id=" . $cat_id;
        $result = $controller->genericSelectCount($query);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }

    }

    function validateCategoryName($cat_name)
    {
        $controller = new Controller();
        $query = "select * from " . $this->categoryTbl . " where category_name='" . $cat_name . "'";
        $result = $controller->genericSelectCount($query);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    function validateSizeByName($cat_name)
    {
        $controller = new Controller();
        $query = "select * from " . $this->sizeTable . " where size='" . $cat_name . "'";
        $result = $controller->genericSelectCount($query);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    function validateSize($cat_name, $size)
    {
        $controller = new Controller();
        $query = "select * from " . $this->sizeVariantTable . " where cat_id='" . $cat_name . "' and size_id='" . $size . "'";
        $result = $controller->genericSelectCount($query);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    function validateSizeById($cat_name, $size, $id)
    {
        $controller = new Controller();
        $query = "select * from " . $this->sizeVariantTable . " where cat_id='" . $cat_name . "' and size='" . $size . "' and id!=" . $id;
        $result = $controller->genericSelectCount($query);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    function validateCategoryNameById($cat_name, $id)
    {
        $controller = new Controller();
        $query = "select * from " . $this->categoryTbl . " where category_name='" . $cat_name . "' and id!=" . $id;
        $result = $controller->genericSelectCount($query);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }

    }

    function validateSizeNameById($cat_name, $id)
    {
        $controller = new Controller();
        $query = "select * from " . $this->sizeTable . " where size='" . $cat_name . "' and id!=" . $id;
        $result = $controller->genericSelectCount($query);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }

    }

    function getStateById($state)
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->userSubscriptionTable . " where user_id='" . $id . "' order by id desc";
        $sql = "SELECT * FROM " . $this->statesTable . " WHERE id='$state'";
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql);
        return $result;
    }
    function getCityById($city)
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->userSubscriptionTable . " where user_id='" . $id . "' order by id desc";
        $sql = "SELECT * FROM " . $this->citiesTable . " WHERE id='$city'";
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql);
        return $result;
    }

    function getCountryById($country)
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->userSubscriptionTable . " where user_id='" . $id . "' order by id desc";
        $sql = "SELECT * FROM " . $this->countryTable . " WHERE id='$country'";
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql);
        return $result;
    }

    function getOrderInvoiceDetails($country)
    {
        $controller = new Controller();
        if ($country == "national") {
            $sql_query = "select * from " . $this->userSubscriptionTable . " where country='101' ORDER BY id DESC";
        } else {
            $sql_query = "select * from " . $this->userSubscriptionTable . " where country !='101' ORDER BY id DESC";
        }

        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getUserInvoiceData($id)
    {
        $controller = new Controller();
        $sql = "call mu_getUserInvoiceData(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function getUserInvoiceProductData($id)
    {
        $controller = new Controller();
        $sql = "call mu_getUserInvoiceProductData(?)";
        $type = "i";
        $param = array($id);
        $result = $controller->genericSelectToIterateUsingProcedure($sql, $type, $param);
        return $result;
    }

    function fetch_all_data($result)
    {
        $all = array();
        while ($thing = mysqli_fetch_array($result)) {
            $all[] = $thing;
        }
        return $all;
    }

    function getLastProductId()
    {
        $controller = new Controller();
        $sql = "SELECT * FROM " . $this->productTable . " Order by id desc limit 1";

        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql);
        return $result;
    }

    function getProductDetailsByIdEdit($table, $table2, $id)
    {
        $controller = new Controller();
        $sql_query = "select pt.*,cv.id as color_v_id ,cv.product_id,cv.name,cv.image,cv.product_qty from " . $table . " as pt inner join " . $table2 . " as cv on cv.product_id = pt.id WHERE pt.id = " . $id . " ";
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    function getQuantityData()
    {
        $controller = new Controller();
//        $sql_query = "SELECT pt.*,cv.id as color_id,cv.product_id,cv.name,cv.image,cv.product_qty FROM ".$this->productTable." as pt INNER JOIN ".$this->colorVariantTable." as cv ON cv.id = pt.id ORDER BY pt.id DESC";
        $sql_query = "select * from " . $this->productTable . " WHERE id IN(SELECT DISTINCT product_id FROM " . $this->colorVariantTable . " WHERE product_qty <= 10 ) ORDER by id DESC";

        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getStockData($pro_id, $color_name)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM " . $this->colorVariantTable . " WHERE product_id = " . $pro_id . " AND name = '" . $color_name . "'";
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    function getCustomerDateById($cust_id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM " . $this->customerTable . " WHERE id = " . $cust_id . " LIMIT 1";
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    function getCustomerDateByIdPrint($cust_id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM " . $this->customerTable . " WHERE id = " . $cust_id . " AND invoice_status = 1 LIMIT 1";
//        echo $sql_query."<br>";

        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getCustomer()
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM " . $this->customerTable . " ORDER BY invoice_number DESC LIMIT 1";
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    function getCustomerData($from_date,$to_date,$text,$drp)
    {
        /*$controller = new Controller();
        $sql_query = "SELECT * FROM " . $this->customerTable . " ORDER BY invoice_number DESC";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;*/
        $controller = new Controller();
        if ($from_date != "" && $to_date != "" && $text !="" && $drp != "" ) {
            $sql_query = "SELECT * FROM " . $this->customerTable . "  WHERE invoice_date BETWEEN '$from_date' AND '$to_date' AND invoice_number LIKE '%$text%' OR first_name LIKE '%$text%' OR phone_no LIKE '%$text%' OR invoice_status = '$drp' ORDER BY invoice_number DESC;";
        }elseif ($from_date !="" && $to_date !="") {
            $sql_query = "SELECT * FROM " . $this->customerTable . "  WHERE invoice_date BETWEEN '$from_date' AND '$to_date' ORDER BY invoice_number DESC;";
        }
        elseif ($from_date == "" && $to_date == "" && $text != "") {
            $sql_query = "SELECT * FROM " . $this->customerTable . "  WHERE invoice_number LIKE '%$text%' OR first_name LIKE '%$text%' OR phone_no LIKE '%$text%' OR invoice_status = '$drp' ORDER BY invoice_number DESC;";
        }
        elseif($text !="" )
        {
            $sql_query = "SELECT * FROM " . $this->customerTable . "  WHERE invoice_number ='$text' ORDER BY invoice_number DESC;";
        }
        elseif($drp !="" )
        {
            $sql_query = "SELECT * FROM " . $this->customerTable . "  WHERE invoice_status ='$drp' ORDER BY  invoice_number DESC;";
        }
        else {
            $sql_query = "SELECT * FROM " . $this->customerTable . " ORDER BY invoice_number DESC";
        }
//        echo $sql_query;
//        exit;

        $result = $controller->genericSelectToIterate($sql_query);
        return $result;

    }


    function getIncomeData($fromdate, $todate)
    {
        $controller = new Controller();
        $sql_query = "select * from " . $this->incomeTable . " ";

        if ($fromdate != "" && $todate != "") {
            $sql_query .= "WHERE created_at BETWEEN '" . $fromdate . "' AND '" . $todate . "' ";
        } elseif ($fromdate != "") {
            $sql_query .= "WHERE created_at='" . $fromdate . "' ";
        } else {
            $sql_query .= "ORDER by id DESC";
        }
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getIncomeStatData()
    {
        $controller = new Controller();
        $sql_query = "SELECT id,SUM(grand_total) AS Grand, MONTHNAME(created_at) as Month_Name FROM " . $this->incomeTable . "  GROUP BY Month_Name ORDER BY id ASC";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getConfigData()
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM " . $this->configTable . " LIMIT 1";
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }


    function getCustomerInvoiceData($invoice_number)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM " . $this->customerTable . " WHERE invoice_number = '$invoice_number' LIMIT 1";
//        echo $sql_query;
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    function getAllCustomerInvoiceData($invoice_number)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM " . $this->customerTable . " WHERE id = '$invoice_number' AND invoice_status = 1 LIMIT 1";
//        echo $sql_query;
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    function getCustomerProductForInvoiceData($customer_id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM " . $this->orderTable . " WHERE customer_id = '$customer_id' ";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }


    function getCustomerShippingTaxForInvoiceData($customer_id)
    {
        $controller = new Controller();
//        SELECT*, MAX(pro_tax) FROM tb_product_order WHERE customer_id = '21'
        $sql_query = "SELECT *, MAX(pro_tax) as highPer FROM " . $this->orderTable . " WHERE customer_id = '$customer_id' ";
        /*echo $sql_query;
        exit;*/
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    function getAdminData($email)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM tb_admin_login WHERE email = '$email'   LIMIT 1";
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    function getProductHSNDetailsById($product_id)
    {
        $controller = new Controller();
        $sql_query = " SELECT * FROM ". $this->productTable. " WHERE id = '$product_id' ";
//        $sql_query = " SELECT *, MAX(pro_tax) as highPer FROM tb_product_order WHERE id = '$product_id' ";
//        echo $sql_query;
//        exit;
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }



    function FetchData($table,$id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM " . $table. " where id = ".$id." limit 1";
        /*echo $sql_query;
        exit;*/
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }
    function FetchThemeData($table,$id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM " . $table. " where dealer_id = ".$id." Order by id desc";
        /*echo $sql_query;
        exit;*/
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }


    function getDealer()
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM " . $this->dealerTable . " where login_type = 'dealer' Order by id Desc";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }
    function getDealerByIdEdit($id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM ".$this->dealerTable." WHERE id = $id ";
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    function getPlan($id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM " . $this->planTable . " where dealer_id = $id";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }
    function getPlanByIdEdit($id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM ".$this->planTable." WHERE id = $id ";
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    function getTeamMember($id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM " . $this->teamTable . " where dealer_id = $id Order By id Desc";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }
    function getTheme()
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM " . $this->teamTable . " Order By id Desc";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }
    function getTeamByIdEdit($id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM ".$this->teamTable." WHERE id = $id ";
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }
    function getTestimonial()
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM " . $this->testimonialTable . " Order by id desc";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getDealerFromDomain($url)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM " . $this->dealerTable . " where domain_name LIKE '%$url%' ";
        /*echo $sql_query;
        exit;*/
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }
    function getAllByDealerId($table,$id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM " . $table . " where dealer_id = ".$id." ";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }
    function getAllPriceByDealerId($table,$id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM " . $table . " where dealer_id = ".$id."  LIMIT 4";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function getCountryCategory()
    {
        $controller = new Controller();
        $sql = "SELECT * FROM " . $this->countryTable;
        $result = $controller->genericSelectToIterate($sql);
        return $result;
    }
    function getFranchisePlanPrice($user_id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM franchise_price WHERE  user_id = $user_id ";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }
    function getPrivacyPolicyData($user_id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM tb_privacy_policy WHERE  dealer_id = $user_id  limit 1";
//        echo $sql_query;
//        exit;
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }
    function getSpecificCardDealerCardData($id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM tb_democard WHERE dealer_id = $id";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }
    function getAllServices($table,$id)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM " . $table . " where user_id = ".$id." Order by id desc ";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }


}