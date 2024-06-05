<?php

namespace App\Models\api;

use CodeIgniter\Model;

class GasDeliverymanModel extends Model
{
    protected $table = 'gas_deliveryman';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'id',
        'name',
        'email',
        'phonenumber',
        'address',
        'password',
        'user_type',
        'is_mobile_verified',
        'mobile_otp',
        'description',
        'token'
    ];
}