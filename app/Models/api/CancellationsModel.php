<?php

namespace App\Models\api;

use CodeIgniter\Model;
use CodeIgniter\Format\TimestampFormatter;

class CancellationsModel extends Model
{
    protected $table = 'Cancellations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'id',
        'booking_id',
        'cancellation_date',
        'cancellation_reason',
        'cancellation_fee'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'timestamp';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $formatter = [
        'cancellation_date' => [ 'format' => 'datetime'],
    ];
}