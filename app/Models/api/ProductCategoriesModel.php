<?php
namespace App\Models\api;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class ProductCategoriesModel extends Model
{
    protected $table = 'product_categories';
    protected $primaryKey = 'Id';
    protected $useAutoIncrement = false;
    protected $allowedFields = [
        'id',
        'product_id',
        'category_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function get_cat_product($id)
    {
        return $this->findAll();
    }

}
