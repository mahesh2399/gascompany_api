<?php

namespace App\Models\Admin;

use CodeIgniter\Model;


class OrdersModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [

        "id",
        "order_group_id",
        "shop_id",
        "deliveryman_id",
        "user_id",
        "guest_user_id",
        "location_id",
        "delivery_status",
        "payment_status",
        "order_from",
        "applied_coupon_code",
        "coupon_discount_amount",
        "admin_earning_percentage",
        "total_admin_earnings",
        "total_vendor_earnings",
        "logistic_id",
        "logistic_name",
        "pickup_or_delivery",
        "shipping_delivery_type",
        "scheduled_delivery_info",
        "pickup_hub_id",
        "shipping_cost",
        "tips_amount",
        "reward_points",
        "created_at",
        "updated_at",
        "note"


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



    public function deliverystatus_today()
    {
        $sql = "SELECT delivery_status, COUNT(*) AS count_today
               FROM orders
               WHERE DATE(created_at) = CURDATE()
               GROUP BY delivery_status";
        return $this->query($sql)->getResultArray();
    }

    public function deliverystatus_last7days()
    {
        $sql = "SELECT delivery_status, COUNT(*) AS count_last_7_days
               FROM orders
               WHERE created_at >= CURDATE() - INTERVAL 7 DAY
               GROUP BY delivery_status";
        return $this->query($sql)->getResultArray();
    }

    public function deliverystatus_last30days()
    {
        $sql = "SELECT delivery_status, COUNT(*) AS count_last_30_days
               FROM orders
               WHERE created_at >= CURDATE() - INTERVAL 30 DAY
               GROUP BY delivery_status";
        return $this->query($sql)->getResultArray();
    }

    //************count odf delivery status and details by date ********************

    public function deliverystatus_byFromandTodate($fromDate, $toDate, $deliveryStatus)
    {
        $sql = "SELECT * 
                FROM orders
                WHERE created_at BETWEEN ? AND ? 
                AND delivery_status = ?";
        return $this->db->query($sql, [$fromDate, $toDate, $deliveryStatus])->getResultArray();
    }

    //*************count odf delivery status and details*******************************

    public function deliverystatusreport_byFromandTodate($fromDate, $toDate)
    {
        $sql = "SELECT LOWER(delivery_status) as delivery_status, COUNT(*) as count
            FROM orders
            WHERE created_at BETWEEN ? AND ?
            GROUP BY delivery_status";
        return $this->db->query($sql, [$fromDate, $toDate])->getResultArray();
    }


    public function getAllDeliveryStatuses()
    {
        $sql = "SELECT DISTINCT LOWER(delivery_status) as delivery_status
            FROM orders";
        $result = $this->db->query($sql)->getResultArray();

        $statuses = [];
        foreach ($result as $row) {
            $statuses[] = $row['delivery_status'];
        }

        return $statuses;
    }



}
