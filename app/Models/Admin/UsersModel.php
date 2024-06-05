<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        "id",
        "role_id",
        "user_type",
        "name",
        "email",
        "phone",
        "dial_code",
        "email_or_otp_verified",
        "verification_code",
        "new_email_verification_code",
        "password",
        "remember_token",
        "provider_id",
        "avatar",
        "postal_code",
        "location_id",
        "address",
        "user_balance",
        "is_banned",
        "is_active",
        "shop_id",
        "email_verified_at",
        "created_at",
        "updated_at",
        "deleted_at",
        "created_by"
    ];

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

    public function getuseraddressdetails($id)
    {
        $sql = "SELECT ua.*, co.name as country_name, s.name as state_name, c.name as city_name from user_addresses ua , countries co,states s,cities c where ua.user_id = $id and ua.country_id = co.id and s.id = ua.state_id and c.id = ua.city_id";

        return $this->query($sql)->getResultArray();

    }


    public function getUserByEmail($email)
{
    return $this->where('email', $email)->first();
}
public function getUserById($id)
{
    return $this->find($id);
}

}
