<?php
namespace App\Models\api;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class InventoryModel extends Model
{
    protected $table = 'Inventory';
    protected $primaryKey = 'Id';
    protected $useAutoIncrement = false;

    protected $allowedFields = [
        'Id',
        'ProductId',
        'Stock',
        'ProductId',
        'AveragePurchasePrice',
        'AverageSalesPrice',

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
