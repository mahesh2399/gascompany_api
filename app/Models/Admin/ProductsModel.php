<?php

namespace App\Models\Admin;

use CodeIgniter\Model;


class ProductsModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'id',
        'shop_id',
        'added_by',
        'name',
        'slug',
        'brand_id',
        'unit_id',
        'thumbnail_image',
        'gallery_images',
        'product_tags',
        'short_description',
        'description',
        'price',
        'min_price',
        'max_price',
        'discount_value',
        'discount_type',
        'discount_start_date',
        'discount_end_date',
        'sell_target',
        'stock_qty',
        'is_published',
        'is_featured',
        'min_purchase_qty',
        'max_purchase_qty',
        'min_stock_qty',
        'has_variation',
        'has_warranty',
        'total_sale_count',
        'standard_delivery_hours',
        'express_delivery_hours',
        'size_guide',
        'meta_title',
        'meta_description',
        'meta_img',
        'reward_points',
        'created_at',
        'updated_at',
        'deleted_at',
        'vedio_link',
        'created_by',
        'updated_by',
        'is_import',
        'tax_id',
        'return_policy_text',
        'estimated_delivery_text',
        'location_id',
        'is_deleted'
    ];

    protected bool $allowEmptyInserts = true;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function searchproduct($search = "")
    {
        if ($search == "") {
            $sql = "SELECT p.* FROM product_categories as pc, categories as c, products as p WHERE p.id = pc.product_id and c.id= pc.category_id and p.is_published = 1";
        } else {
            $sql = "SELECT p.* FROM product_categories as pc, categories as c, products as p WHERE p.id = pc.product_id and c.id= pc.category_id and (c.name like '%$search%' or p.name like '%$search%') and p.is_published = 1";
        }


        return $this->query($sql)->getResultArray();



        ;
    }



}