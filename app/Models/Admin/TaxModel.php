<?php
namespace App\Models\Admin;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class TaxModel extends Model
{
    protected $table = 'taxes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        "id",
        "name",
        "created_at",
        "is_active",
        "value"
    ];

    public function getProducts()
    {
        return $this->findAll();
    }


}
