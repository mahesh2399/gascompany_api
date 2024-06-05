<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class SaleOrderModel extends Model
{
    protected $table = 'salesorders';
    protected $primaryKey = 'Id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'Id',
        'OrderNumber',
        'order_id',
        'Note',
        'SaleReturnNote',
        'TermAndCondition',
        'IsSalesOrderRequest',
        'SOCreatedDate',
        'Status',
        'DeliveryDate',
        'DeliveryStatus',
        'CustomerId',
        'TotalAmount',
        'TotalTax',
        'TotalDiscount',
        'TotalPaidAmount',
        'PaymentStatus',
        'CreatedDate',
        'CreatedBy',
        'ModifiedDate',
        'ModifiedBy',
        'DeletedDate',
        'DeletedBy',
        'IsDeleted'


        //    "theme_id",
        //    "created_at",
        //    "updated_at"
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'CreatedDate';
    protected $updatedField = 'ModifiedDate';
    protected $deletedField = 'DeletedDate';

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

    public function getSaleorders()
    {

        $sql = "SELECT salesorders.Id,salesorders.OrderNumber, salesorders.SOCreatedDate, salesorders.DeliveryDate, salesorders.CustomerId, salesorders.TotalAmount, salesorders.PaymentStatus, users.name, count(order_items.id) as total_items FROM users, salesorders,order_items WHERE users.id=salesorders.CustomerId and order_items.order_id = salesorders.order_id GROUP by order_items.order_id ORDER BY salesorders.id DESC";
        return $this->query($sql)->getResultArray();
    }

    public function getSalesorderReturnItems()
    {

    }

    public function getsalesorderreturnlist()
    {
        $sql = "SELECT order_returns.id as return_id , order_returns.order_id as order_id, order_returns.return_date as return_date, sum(order_return_items.returned_amount) as return_amount, sum(order_return_items.returned_quantity) as returned_quantity, count(order_return_items.order_item_id) as product_count,
        users.name as customers_name,salesorders.OrderNumber as salesorders_number
        FROM order_returns,order_return_items,orders,users,salesorders
        WHERE orders.id = order_returns.order_id and order_return_items.order_returns_id = order_returns.id and orders.user_id = users.id and salesorders.order_id = orders.id 
        GROUP by order_returns.id ORDER BY order_returns.id DESC";

        return $this->query($sql)->getResultArray();
    }



    public function items()
    {
        return $this->hasMany(SaleOrderItemModel::class, 'Id', 'OrderNumber');
    }
}
