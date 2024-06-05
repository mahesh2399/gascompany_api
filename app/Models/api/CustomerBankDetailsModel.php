<?php
namespace App\Models\api;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class CustomerBankDetailsModel extends Model
{
    protected $table = 'CustomerBankDetails';
    protected $primaryKey = 'Id';
    protected $useAutoIncrement = false;

    protected $allowedFields = [
        'Id',

        'CustomerId',
        'AccountNo',
        'BankName',
        'HolderName',
        'CustomerId',
        'Swift',
        'IFSC',


        'CreatedDate',
        'CreatedBy',
        'ModifiedDate',
        'ModifiedBy',
        'DeletedDate',
        'DeletedBy',
        'IsDeleted',
    ];

    public function getProducts()
    {
        return $this->findAll();
    }
}
