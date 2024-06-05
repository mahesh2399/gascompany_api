<?php

namespace App\Models\Api;

use CodeIgniter\Model;

class SalesOrderItemsModel extends Model
{
  protected $table = 'SalesOrderItems';
  protected $primaryKey = 'id';
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
  ];

  protected bool $allowEmptyInserts = false;

  // Dates
  protected $useTimestamps = false;
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
