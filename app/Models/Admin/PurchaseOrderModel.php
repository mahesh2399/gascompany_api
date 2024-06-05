<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class PurchaseOrderModel extends Model
{
    protected $table = 'purchaseorders';
    protected $primaryKey = 'Id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        "Id",
        "OrderNumber",
        "Note",
        "quotation",
        "PurchaseReturnNote",
        'IsPurchaseOrderRequest',
        'TermAndCondition',
        'POCreatedDate',
        'Status',
        'DeliveryDate',
        'DeliveryStatus',
        "SupplierId",
        "TotalAmount",
        "TotalTax",
        "TotalDiscount",
        "TotalPaidAmount",
        "PaymentStatus",
        "CreatedDate",
        "CreatedBy",
        "UpdatedBy",
        "ModifiedDate",
        "ModifiedBy",
        "DeletedDate",
        "DeletedBy",
        "IsDeleted",
        "created_at",
        "updated_at",
        "deleted_at"

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
