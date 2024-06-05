<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class PermissionsModel extends Model
{
    protected $table = 'permissions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        "id",
        "name",
        "group_name",
        "guard_name",
        "created_at",
        "updated_at",
        "path",
        "active",
        "icon",
        "type",
        "level",
        "is_show",
        "parent_id",
        'display_order'
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



    //     public function getProductsWithCategory($categoryId)
// {
//     return $this->select('products.*, categories.*')
//                 ->join('categories', 'product_categories.category_id = categories.id')
//                 ->join('products', 'product_categories.product_id = products.id')
//                 ->where('product_categories.category_id', $categoryId)
//                 ->findAll();
// }  //  ============= GETPRODUCTSWITHCATEGORY ================== 

    public function getProductsWithCategory($categoryId)
    {
        // return $this->select('t3.*')
        //             ->join('categories t2', 't1.category_id = t2.id')
        //             ->join('products t3', 't1.product_id = t3.id')
        //             ->where('t1.category_id', $categoryId)
        //             ->findAll();


        return $this->select('products.*')
            ->join('categories', 'categories.id = product_categories.category_id')
            ->join('products', 'products.id = product_categories.product_id')
            ->where('product_categories.category_id', $categoryId)
            ->findAll();
    }




}
