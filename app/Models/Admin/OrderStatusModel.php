<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class OrderStatusModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'order_status';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id',
        'name',
        'sequence',
        'status',
        'createdby',
        'updatedby',
        'deletedby',
        'createdat',
        'updatedat',
        'deletedat'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'createdat';
    protected $updatedField = 'updatedat';
    protected $deletedField = 'deletedat';

    protected $validationRules = [
        'name' => 'required',
        'status' => 'required|in_list[0,1]'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];
}
