<?php

namespace App\Models\api;

use CodeIgniter\Model;

class LoginHistoryModel extends Model
{
    protected $table = 'Loginhistory';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'id',
        'user_id',
        'user_type',
        'mobile_otp',
        'mobile_otp_status',
        'token'
    ];
}