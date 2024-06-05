<?php
namespace App\Models\Admin;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class DeliveryMenModel extends Model
{
    protected $table = 'delivery_men';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        "id",
        "name",
        "is_active",
        "phone",
        "email",
        "store_location"
    ];



}
