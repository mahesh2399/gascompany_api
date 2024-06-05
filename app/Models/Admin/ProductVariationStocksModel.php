<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class ProductVariationStocksModel extends Model
{
    protected $table = 'product_variation_stocks';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        "id",
        "product_variation_id",
        "location_id",
        "stock_qty",
        "min_stock_qty",
        "created_at",
        "updated_at",
        "deleted_at"
    ];

    protected bool $allowEmptyInserts = false;

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


    // public function getstock($location_id)
    // {
    //     $sql = "SELECT products.name as product_name, product_variations.id as product_variations_id FROM product_variation_stocks,product_variations,products WHERE product_variations.id = product_variation_stocks.id and products.id = product_variations.product_id and location_id = $location_id ";

    //     return $this->query($sql)->getResultArray();
    // }

    // public function getStocks()
    // {
    //     $sql = "SELECT product_variation_stocks.id, product_variation_stocks.stock_qty, product_variation_stocks.min_stock_qty, product_variation_stocks.is_active as status, product_variation_stocks.created_at,products.name as product_name, product_variations.id as product_variations_id FROM product_variation_stocks,product_variations,products WHERE product_variations.id = product_variation_stocks.id and products.id = product_variations.product_id ";

    //     return $this->query($sql)->getResultArray();
    // }
    public function getstock($location_id)
    {
        $sql = "SELECT 
                products.name as product_name, 
                product_variations.id as product_variations_id,
                locations.name as location_name
            FROM  
                product_variation_stocks
            INNER JOIN  
                product_variations ON product_variations.id = product_variation_stocks.id 
            INNER JOIN 
                products ON products.id = product_variations.product_id 
            LEFT JOIN 
                locations ON locations.id = product_variation_stocks.location_id
            WHERE 
                product_variation_stocks.location_id = $location_id OR product_variation_stocks.location_id IS NULL";

        return $this->query($sql)->getResultArray();
    }

    public function getStocks()
    {
        $sql = "SELECT 
                products.id,
                product_variation_stocks.location_id,
                products.stock_qty,
                products.min_stock_qty,
                products.is_published as status, 
                product_variation_stocks.created_at,
                products.name as product_name, 
                product_variations.id as product_variations_id,
                locations.name as location_name
            FROM  
                product_variation_stocks
            INNER JOIN 
                product_variations ON product_variations.id = product_variation_stocks.id
            INNER JOIN 
                products ON products.id = product_variations.product_id
            LEFT JOIN 
                locations ON locations.id = product_variation_stocks.location_id";

        return $this->query($sql)->getResultArray();
    }


}
