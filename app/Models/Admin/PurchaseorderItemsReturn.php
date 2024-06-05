<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class PurchaseorderItemsReturn extends Model
{
    protected $table = 'purchaseorderitemsreturn';
    protected $primaryKey = 'Id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        "Id",
        "returnProductId",
        "returnStatus",
        "returnPurchaseOrderId",
        "returnprice",
        "returnQuantity",
        "returnTaxValue",
        "returnDiscount",
        "returnDiscountPercentage",
        "returnsub_total_before_discount",
        "returnsub_total_after_discount",
        "returnCreatedDate",
        "returnUnitId",
        "returnTaxId",
        "returnWarehouseId",
        "created_at",
        "created_by",
        "updated_at",
        "deleted_at",
        "returnTaxId",
        " returnTaxId_2",

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
