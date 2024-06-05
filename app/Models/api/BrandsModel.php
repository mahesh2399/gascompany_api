<?php
namespace App\Models\api;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class BrandsModel extends Model
{
    protected $table = 'brands';
    protected $primaryKey = 'Id';
    protected $useAutoIncrement = false;

    protected $allowedFields = [
        'Id',
        'Name',
        'ImageUrl',
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
