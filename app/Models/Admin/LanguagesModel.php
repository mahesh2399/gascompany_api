<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class LanguagesModel extends Model
{
    protected $table = 'languages';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        "id",
        "name",
        "flag",
        "code",
        "is_rtl",
        "is_active",
        "created_at",
        "updated_at",
        "deleted_at",
        "font"
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
