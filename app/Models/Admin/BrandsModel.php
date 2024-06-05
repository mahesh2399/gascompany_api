<?php
namespace App\Models\Admin;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class BrandsModel extends Model
{
    protected $table = 'brands';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        "id",
        "name",
        "slug",
        "brand_image",
        "total_sales_amount",
        "is_active",
        "meta_title",
        "meta_image",
        "meta_description",
        "created_at",
        "updated_at",
        "deleted_at"
    ];

    public function getProducts()
    {
        return $this->findAll();
    }


}
