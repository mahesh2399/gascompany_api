<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class Order_returnsModel extends Model
{
    protected $table = 'order_returns';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id',
        "order_id",
        "return_date",
        "return_reason",
        "return_status",
        "created_at",
        "updated_at"
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

    public function getorderreturnbyid($id)
    {
        $sql = "SELECT ord_rtn.id as id, ord_rtn.return_date,so.OrderNumber as so_number,u.name as customer_name,u.id as customer_id, orders.payment_status, orders.delivery_status,ord_rtn.return_reason,u.email as customer_email,u.phone as customer_phone
        FROM order_returns as ord_rtn,salesorders as so, users as u,orders as orders
        WHERE ord_rtn.id = $id and so.order_id = ord_rtn.order_id and u.id = so.CustomerId and ord_rtn.order_id = orders.id; ";

        return $this->query($sql)->getResultArray();


    }

    public function getorderreturnbyidview($id)
    {
        $sql = "SELECT ord_rtn.id as id, ord_rtn.return_date,so.OrderNumber as so_number,u.name as customer_name,u.id as customer_id, orders.payment_status, orders.delivery_status,ord_rtn.return_reason
        FROM order_returns as ord_rtn,salesorders as so, users as u,orders as orders
        WHERE ord_rtn.id = $id and so.order_id = ord_rtn.order_id and u.id = so.CustomerId and ord_rtn.order_id = orders.id; ";

        return $this->query($sql)->getResultArray();


    }

    public function getreturnitems($id)
    {

        $sql = "SELECT ori.id as id, oi.id as order_item_id, p.name as product_name, p.id as product_id, pv.id as product_variation_id, p.unit_id, ori.returned_quantity, oi.qty as order_quantity, oi.unit_price, oi.total_tax, oi.total_price,oi.tax_id,oi.discount,taxes.name as tax_name, taxes.value as tax_value, u.name as unit_name
        FROM order_return_items as ori,  product_variations as pv,order_returns as ord_rtn,
        order_items as oi left JOIN taxes as taxes on oi.tax_id = taxes.id,
        products p LEFT JOIN units u ON u.id = p.unit_id 
        WHERE ori.order_returns_id = $id and oi.id = ori.order_item_id and  ord_rtn.id = ori.order_returns_id and pv.id = oi.product_variation_id and p.id = pv.product_id";

        return $this->query($sql)->getResultArray();
    }
}
