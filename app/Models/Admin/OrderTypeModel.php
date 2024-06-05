<?php

namespace App\Models\Admin;

use CodeIgniter\Model;


class OrderTypeModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'order_type';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id',
        'name',
        'description',
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
