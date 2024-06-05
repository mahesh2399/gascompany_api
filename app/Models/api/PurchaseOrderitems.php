<?php

namespace App\Models\api;

use CodeIgniter\Model;

class PurchaseOrderitems extends Model
{
    protected $table = 'purchaseorderitems';
    protected $primaryKey = 'Id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        "Id",
        "ProductId",
        "Status",
        "PurchaseOrderId",
        "price",
        'Quantity',
        "taxid",
        'TaxValue',
        'Discount',
        'DiscountPercentage',
        'sub_total_before_discount',
        'sub_total_after_discount',
        'CreatedDate',
        'UnitId',
        'WarehouseId',
        "created_at",
        "updated_at",
        "deleted_at",

    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    // protected $dateFormat = 'datetime';
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




    public function order($poId)
    {
        return $this->belongsTo(PurchaseOrderModel::class, 'ProductId', 'Id');
    }
}
