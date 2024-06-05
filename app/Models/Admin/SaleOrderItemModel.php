<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class SaleOrderItemModel extends Model
{
    protected $table = 'salesorderitems';
    protected $primaryKey = 'Id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'Id',
        'ProductId',
        'Status',
        'SalesOrderId',
        'UnitPrice',
        'Quantity',
        'TaxValue',
        'Discount',
        'DiscountPercentage',
        'CreatedDate',
        'UnitId',
        'WarehouseId',
        'TaxId'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'CreatedDate';
    protected $updatedField = '';

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
