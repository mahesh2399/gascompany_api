<?php

namespace App\Models\api;

use CodeIgniter\Model;

class StoresModel extends Model
{
    protected $table = 'stores';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'id',
        'name',
        'email',
        'phonenumber',
        'address',
        'opening_hours',
        'delivery_area',
        'description'
    ];
}