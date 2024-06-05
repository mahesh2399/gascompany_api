<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class UserAddressesModel extends Model
{
    protected $table = 'user_addresses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id',
        'user_id',
        'title',
        'type',
        'country_id',
        'state_id',
        'city_id',
        'address',
        'pincode',
        'is_default',
        'created_at',
        'updated_at',
        'deleted_at',
        'phone',
        'country_code'
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


    public function getUserAddressDetails($userId)
    {
        return $this->select('user_addresses.*, cities.name as citiesname, countries.name as countriesname, states.name as statesname')
            ->join('cities', 'user_addresses.city_id = cities.id')
            ->join('countries', 'user_addresses.country_id = countries.id')
            ->join('states', 'user_addresses.state_id = states.id')
            ->where('user_addresses.user_id', $userId)
            ->findAll();
    }
}
