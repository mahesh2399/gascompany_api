<?php
namespace App\Models\Admin;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class MediaModel extends Model
{
    protected $table = 'media_managers';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        "id",
        "user_id",
        "media_file",
        "media_size",
        "media_type",
        "media_name",
        "media_extension",
        "created_at",
        "updated_at",
        "deleted_at"
    ];

    public function getProducts()
    {
        return $this->findAll();
    }

}