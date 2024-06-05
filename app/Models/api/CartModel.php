<?php
namespace App\Models\api;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class CartModel extends Model
{
    protected $table = 'Cart';
    protected $primaryKey = 'Id';
    protected $useAutoIncrement = false;

    protected $allowedFields = [
        'Id',
        'GuestId',
        'CustomerId',
        'ProductId',
        'Quantity',
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
