<?php

namespace App\Models\api;

use CodeIgniter\Model;

class GasBookingsModel extends Model
{
    protected $table = 'Gas_Bookings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'id',
        'user_id',
        'store_id',
        'delivery_man_id',
        'gas_type_id',
        'gas_quantity',
        'booking_date',
        'delivery_date',
        'status',
        'payment_status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'timestamp';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $formatter = [
        'booking_date' => [ 'format' => 'datetime'],
        'delivery_date' => [ 'format' => 'datetime'],
    ];
}