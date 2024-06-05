<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class SupplierAddressesModel extends Model
{
    protected $table = 'supplieraddresses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [



        "id",
        "SupplierId",
        "CountryId ",
        "StateId",
        "CityId ",
        "Address",
        "CountryName",
        "CityName",
        "WarehouseCountryId",
        "WarehouseStateId",
        "WarehouseCityId",
        "WarehouseAddress",
        "IsDeleted",
        "shipping_address",
        "billing_address",
        "supplier_address"
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
