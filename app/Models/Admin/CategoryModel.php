<?php
namespace App\Models\Admin;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        "id",
        "name",
        "slug",
        "parent_id",
        "level",
        "sorting_order_level",
        "thumbnail_image",
        "icon",
        "is_featured",
        "is_top",
        "total_sale_count",
        "meta_title",
        "meta_image",
        "meta_description",
        "created_at",
        "updated_at",
        "description",
        "deleted_at"
    ];

    public function getProducts()
    {
        return $this->findAll();
    }


}
