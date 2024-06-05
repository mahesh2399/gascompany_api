<?php
namespace App\Models\api;

use CodeIgniter\Model;

class CustomersModel extends Model
{
    protected $table = 'Customers';
    protected $primaryKey = 'id'; 
    protected $useAutoIncrement = false;
    protected $allowedFields = [
        'id',
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