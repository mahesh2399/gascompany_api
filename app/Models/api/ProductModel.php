<?php
namespace App\Models\api;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;


class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'Id';
    protected $useAutoIncrement = false;
    protected $allowedFields = [
        'Id',
        'Name',
        'Code',
        'Barcode',
        'SkuCode',
        'SkuName',
        'Description',
        'ProductUrl',
        'QRCodeUrl',
        'UnitId',
        'PurchasePrice',
        'SalesPrice',
        'Mrp',
        'CategoryId',
        'BrandId',
        'WarehouseId',
        'CreatedDate',
        'CreatedBy',
        'ModifiedDate',
        'ModifiedBy',
        'DeletedDate',
        'DeletedBy',
        'IsDeleted'
    ];

    public function getProducts()
    {
        return $this->findAll();
    }







}
