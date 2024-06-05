<?php

namespace App\Models\api;

use CodeIgniter\Model;

class GasTypesModel extends Model
{
    protected $table = 'gas_types';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'id',
        'name',
        'description',
        'price',
        'image'
    ];
}