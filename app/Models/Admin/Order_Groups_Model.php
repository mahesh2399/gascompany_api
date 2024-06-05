<?php

namespace App\Models\Admin;

use CodeIgniter\Model;


class Order_Groups_Model extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'order_groups';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [

        "id",
        "user_id",
        "guest_user_id",
        "order_code",
        "shipping_address_id",
        "billing_address_id",
        "location_id",
        "phone_no",
        "alternative_phone_no",
        "sub_total_amount",
        "total_tax_amount",
        "total_coupon_discount_amount",
        "total_shipping_cost",
        "grand_total_amount",
        "payment_method",
        "payment_status",
        "payment_details",
        "is_manual_payment",
        "manual_payment_details",
        "is_pos_order",
        "pos_order_address",
        "additional_discount_value",
        "additional_discount_type",
        "total_discount_amount",
        "total_tips_amount",
        "created_at",
        "updated_at",
        "deleted_at"

    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];



    //*************************COUNT OF ORDERS************************

    public function count_of_today_Orders()// for today order
    {
        $sql = "SELECT COUNT(*) as total_orders_today
    FROM orders
    WHERE DATE(created_at) = CURRENT_DATE();";

        return $this->query($sql)->getResultArray();
    }


    public function count_of_last_7days_Order()  // for last 7 days order
    {
        $sql = "SELECT COUNT(*) as total_orders_last_7days_order
        FROM orders
        WHERE created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY);";

        return $this->query($sql)->getResultArray();
    }


    public function count_of_last_30day_sOrder()
    {
        $sql = "SELECT COUNT(*) as  total_orders_last_30days_order
                 FROM orders
                 WHERE created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY);";

        return $this->query($sql)->getResultArray();
    }



    public function count_of_this_Year_Orders()// for year order
    {
        $sql = "SELECT COUNT(*) as total_orders_thisyear
    FROM orders
    WHERE YEAR(created_at) = YEAR(CURRENT_DATE());";

        return $this->query($sql)->getResultArray();
    }


    //*********************cOUNT OF EARNINGS*****************

    public function count_of_today_earnings()
    {
        $sql = "SELECT COALESCE(SUM(order_groups.grand_total_amount), 0) AS total_today_earnings 
        FROM order_groups 
        JOIN orders ON orders.order_group_id = order_groups.id 
        WHERE DATE(order_groups.created_at) = CURRENT_DATE() 
        AND orders.payment_status = 'Paid';
        ";
        return $this->query($sql)->getResultArray();
    }


    public function count_of_last_7days_earnings()   // for last 7 days earning
    {
        $sql = "SELECT COALESCE(SUM(order_groups.grand_total_amount), 0) AS total_last_7_days_earnings 
            FROM order_groups 
            JOIN orders ON orders.order_group_id = order_groups.id 
            WHERE order_groups.created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY) 
            AND orders.payment_status = 'Paid'";
        return $this->query($sql)->getResultArray();
    }


    public function count_of_last_30days_earnings()
    {
        $sql = "SELECT COALESCE(SUM(order_groups.grand_total_amount), 0) AS total_last_30_days_earnings 
        FROM order_groups 
        JOIN orders ON orders.order_group_id = order_groups.id 
        WHERE order_groups.created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY) 
        AND orders.payment_status = 'Paid'";

        return $this->query($sql)->getResultArray();
    }


    public function count_of_thisyear_earnings()
    {
        $sql = "SELECT COALESCE(SUM(order_groups.grand_total_amount), 0) AS total_this_year_earnings 
    FROM order_groups 
    JOIN orders ON orders.order_group_id = order_groups.id 
    WHERE YEAR(order_groups.created_at) = YEAR(CURRENT_DATE()) 
    AND orders.payment_status = 'Paid'";
        return $this->query($sql)->getResultArray();
    }

    //*******************COUNT OF PENDING-EARNINGS*******************


    public function count_of_today_pending_earnings()
    {
        $sql = "SELECT COALESCE(SUM(order_groups.grand_total_amount), 0) AS total_today_pending_earnings 
        FROM order_groups 
        JOIN orders ON orders.order_group_id = order_groups.id 
        WHERE DATE(order_groups.created_at) = CURRENT_DATE() 
        AND orders.payment_status = 'Unpaid'";
        return $this->query($sql)->getResultArray();
    }


    public function count_last_7days_pending_earnings()//for week pending_earnings
    {
        $sql = "SELECT COALESCE(SUM(order_groups.grand_total_amount), 0) AS total_week_pending_earnings 
        FROM order_groups 
        JOIN orders ON orders.order_group_id = order_groups.id 
        WHERE orders.payment_status = 'Unpaid' 
        AND order_groups.created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        return $this->query($sql)->getResultArray();
    }


    public function count_last_30days_pending_earnings()//for month pending_earnings
    {
        $sql = "SELECT COALESCE(SUM(order_groups.grand_total_amount), 0) AS total_month_pending_earnings 
        FROM order_groups 
        JOIN orders ON orders.order_group_id = order_groups.id 
        
        WHERE orders.payment_status = 'Unpaid' and order_groups.created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY);";

        return $this->query($sql)->getResultArray();
    }


    public function count_of_thisyear_pending_earnings()
    {
        $sql = "SELECT COALESCE(SUM(order_groups.grand_total_amount), 0) AS total_year_pending_earnings 
    FROM order_groups 
    JOIN orders ON orders.order_group_id = order_groups.id 
    WHERE orders.payment_status = 'Unpaid' 
    AND YEAR(order_groups.created_at) = YEAR(CURRENT_DATE());";
        return $this->query($sql)->getResultArray();
    }


    //*********************COUNT OF PAID*************************


    public function count_of_paid_today()
    {
        $sql = "SELECT COUNT(*) AS total_count_of_paid_today
            FROM orders
            WHERE payment_status = 'Paid' AND DATE(created_at) = CURRENT_DATE();";
        return $this->query($sql)->getResultArray();
    }


    public function count_of_paid_last_7days()
    {
        $sql = "SELECT COUNT(*) AS total_count_of_paid_last7days
            FROM orders
            WHERE payment_status = 'Paid' AND created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY);";
        return $this->query($sql)->getResultArray();
    }


    public function count_of_paid_last_30days()
    {
        $sql = "SELECT COUNT(*) AS total_count_of_paid_last30days
            FROM orders
            WHERE payment_status = 'Paid' AND created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY);";
        return $this->query($sql)->getResultArray();
    }


    public function count_of_paid_thisyear()
    {
        $sql = "SELECT COUNT(*) AS total_count_of_paid_this_year
        FROM orders
        WHERE payment_status = 'Paid' AND YEAR(created_at) = YEAR(CURRENT_DATE());;";
        return $this->query($sql)->getResultArray();
    }


    //***********COUNT OF UN-PAID *************************


    public function count_of_un_paid_today()
    {
        $sql = "SELECT COUNT(*) AS total_unpaid_count_of_today
                FROM orders
                WHERE payment_status != 'Paid' 
                AND DATE(created_at) = CURDATE()";

        return $this->query($sql)->getResultArray();
    }


    public function count_of_un_paid_last_7days()
    {
        $sql = "SELECT COUNT(*) AS total_unpaid_count_of_last7days
      FROM orders
      WHERE payment_status != 'Paid' 
      AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";

        return $this->query($sql)->getResultArray();
    }


    public function count_of_un_paid_last_30days()
    {
        $sql = "SELECT COUNT(*) AS total_unpaid_count_of_last30days
            FROM orders
            WHERE payment_status != 'Paid' 
            AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";

        return $this->query($sql)->getResultArray();
    }


    public function count_of_un_paid_thisyear()
    {
        $sql = "SELECT COUNT(*) AS total_unpaid_count_of_this_year
        FROM orders
        WHERE payment_status = 'Unpaid'
        AND YEAR(created_at) = YEAR(CURDATE())";


        return $this->query($sql)->getResultArray();
    }

    //**********************************************************

    public function count_of_paid()
    {
        $sql = "SELECT COUNT(*) AS count_of_paid
        FROM orders
        WHERE payment_status = 'Paid'";
        return $this->query($sql)->getResultArray();
    }

    public function count_of_un_paid()
    {
        $sql = "SELECT COUNT(*) AS count_of_paid
        FROM orders
        WHERE payment_status != 'Paid'";
        return $this->query($sql)->getResultArray();
    }

    //**********************************************************

    public function count_of_grand_paid_total_amount()
    {
        $sql = "SELECT COALESCE(SUM(order_groups.grand_total_amount), 0) AS total_grandtotal FROM order_groups 
    JOIN orders ON orders.order_group_id = order_groups.id 
    WHERE orders.payment_status = 'Paid'";
        return $this->query($sql)->getResultArray();
    }


    public function count_of_grandtotal_pending_amount()
    {
        $sql = "SELECT COALESCE(SUM(order_groups.grand_total_amount), 0) AS total_pending_grandtotal
    FROM order_groups 
    JOIN orders ON orders.order_group_id = order_groups.id 
    WHERE orders.payment_status = 'Unpaid'";
        return $this->query($sql)->getResultArray();
    }

    //  **************************FOR REVENUE*****************

    public function count_of_grandtotal_amount()
    {
        $sql = "SELECT COALESCE(SUM(order_groups.grand_total_amount), 0) AS total_revenue
    FROM order_groups 
    JOIN orders ON orders.order_group_id = order_groups.id";
        return $this->query($sql)->getResultArray();
    }
    //*************

    public function count_of_grandtotal_amount_for_today()
    {
        $sql = "SELECT COALESCE(SUM(order_groups.grand_total_amount), 0) AS total_revenue_today 
        FROM order_groups 
        JOIN orders ON orders.order_group_id = order_groups.id 
        WHERE DATE(order_groups.created_at) = CURRENT_DATE();";
        return $this->query($sql)->getResultArray();
    }

    public function count_of_grandtotal_amount_for_last7days()
    {
        $sql = "SELECT COALESCE(SUM(order_groups.grand_total_amount), 0) AS total_revenue_last7days 
        FROM order_groups 
        JOIN orders ON orders.order_group_id = order_groups.id 
        WHERE order_groups.created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY);";
        return $this->query($sql)->getResultArray();
    }


    public function count_of_grandtotal_amount_for_last30days()
    {
        $sql = "SELECT COALESCE(SUM(order_groups.grand_total_amount), 0) AS total_revenue_last30days
        FROM order_groups 
        JOIN orders ON orders.order_group_id = order_groups.id 
        WHERE order_groups.created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)";
        return $this->query($sql)->getResultArray();
    }



    public function count_of_grandtotal_amount_for_thisyear()
    {
        $sql = "SELECT COALESCE(SUM(order_groups.grand_total_amount), 0) AS total_revenue_thisyear
        FROM order_groups
        JOIN orders ON orders.order_group_id = order_groups.id
        WHERE YEAR(orders.created_at) = YEAR(CURDATE())";
        return $this->query($sql)->getResultArray();
    }

    //************************************************************

    public function total_purchase_orders()
    {
        $sql = "SELECT COUNT(*) AS total_purchase_orders FROM purchaseorders";
        return $this->db->query($sql)->getResultArray();
    }

    public function total_purchase_order_returns()
    {
        $sql = "SELECT COUNT(*) AS total_purchase_order_returns FROM purchaseorderreturn";
        return $this->db->query($sql)->getResultArray();
    }

    public function total_sales_orders()
    {
        $sql = "SELECT COUNT(*) AS total_sales_orders FROM salesorders";
        return $this->db->query($sql)->getResultArray();
    }

    public function total_sales_order_returns()
    {
        $sql = "SELECT COUNT(*) AS total_sales_order_returns FROM order_returns";
        return $this->db->query($sql)->getResultArray();
    }

    //************************************************************

    public function total_brand()
    {
        $sql = "SELECT COUNT(id) AS total_brand FROM brands";
        return $this->query($sql)->getResultArray();
    }



    public function total_subscribed_users()
    {
        $sql = "SELECT COUNT(*) AS total_subscribed_users FROM subscribed_users;";
        return $this->query($sql)->getResultArray();
    }



    public function totalProductSale()
    {
        $sql = "SELECT SUM(qty) AS total_quantity FROM order_items";
        return $this->query($sql)->getResultArray();
    }


    // public function count_of_thismonth_earnings()
    // {
    //     $sql = "SELECT COALESCE(SUM(order_groups.grand_total_amount), 0) AS this_month_earnings FROM order_groups 
    //     JOIN orders ON orders.order_group_id = order_groups.id 
    //     WHERE DATE_FORMAT(order_groups.created_at, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m') 
    //     AND orders.payment_status = 'paid'";
    //     return $this->query($sql)->getResultArray();
    // }

    // public function count_of_thisyear_earnings()
    // {
    //     $sql = "SELECT COALESCE(SUM(grand_total_amount), 0) AS this_year_earnings 
    //     FROM order_groups, orders 
    //     WHERE orders.order_group_id = order_groups.id 
    //     AND YEAR(order_groups.created_at) = YEAR(CURRENT_DATE()) 
    //     AND orders.payment_status = 'paid'";
    //     return $this->query($sql)->getResultArray();
    // }









}
