<?php
namespace App\Models\api;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class PaymentDetailsModel extends Model
{
    protected $table = 'PaymentDetails';
    protected $primaryKey = 'Id';
    protected $useAutoIncrement = false;

    protected $allowedFields = [
        'Id',

        'CustomerId',
        'OrderId',
        'Amount',
        'PaymentMethod',
        'CardNumber',
        'CreatedDate',
        'CreatedBy',
        'ModifiedDate',
        'ModifiedBy',
        'DeletedDate',
        'DeletedBy',
        'IsDeleted',
    ];


}
