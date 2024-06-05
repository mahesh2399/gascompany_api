<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class OrdersModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id',
        'order_group_id',
        'shop_id',
        'deliveryman_id',
        'user_id',
        'guest_user_id',
        'location_id',
        'delivery_status',
        'payment_status',
        'applied_coupon_code',
        'coupon_discount_amount',
        'admin_earning_percentage',
        'total_admin_earnings',
        'total_vendor_earnings',
        'logistic_id',
        'logistic_name',
        'pickup_or_delivery',
        'shipping_delivery_type',
        'scheduled_delivery_info',
        'pickup_hub_id',
        'shipping_cost',
        'tips_amount',
        'reward_points',
        'created_at',
        'updated_at',
        'note',
    ];

    protected bool $allowEmptyInserts = false;

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
}
