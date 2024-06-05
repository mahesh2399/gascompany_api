<?php
namespace App\Models\Admin;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class BrandLocalizationsModel extends Model
{
    protected $table = 'brand_localizations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        "id",
        "brand_id",
        "lang_key",
        "name",
        "brand_image",
        "is_active",
        "meta_title",
        "meta_image",
        "meta_description",
        "created_at",
        "updated_at",
        "deleted_at"
    ];



}
