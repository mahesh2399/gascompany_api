<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class CategoriesModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id',
        'name',
        'slug',
        'parent_id',
        'level',
        'sorting_order_level',
        'thumbnail_image',
        'icon',
        'is_featured',
        'is_top',
        'total_sale_count',
        'meta_title',
        'meta_image',
        'meta_description',
        'created_at',
        'updated_at',
        'description',
        'deleted_at',
        'is_active'
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


    public function getProducts()
    {
        return $this->findAll();
    }




    public function getAllCategories()
    {
     
        return $this->orderBy('total_sale_count', 'DESC')->findAll();
    }
    
}

