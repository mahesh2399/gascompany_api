<?php
namespace App\Models\Admin;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class MenuModel extends Model
{
    protected $table = 'permissions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        "id",
        "name",
        "group_name",
        "guard_name",
        "created_at",
        "updated_at",
        "deleted_at"
    ];

    public function getmenus($user_id){
        $sql = "SELECT p.id as id, p.name as title,p.path as path,p.active,p.icon,p.type,p.level
        FROM role_has_permissions as rhp , permissions as p, users u
        WHERE rhp.permission_id = p.id and u.id=$user_id and p.level = 1 and p.is_show = 1 and u.role_id = rhp.role_id order by p.display_order asc;";
        return $this->query($sql)->getResultArray();
    }

    public function getbyparent_id($parent_id,$user_id){
        $sql = "SELECT p.parent_id, p.name as title,p.path as path,p.active,p.icon,p.type,p.level
        FROM role_has_permissions as rhp , permissions as p, users u
        WHERE rhp.permission_id = p.id and u.id= $user_id and p.level = 2 and p.is_show = 1 and parent_id = $parent_id and u.role_id = rhp.role_id order by p.display_order asc;";
        return $this->query($sql)->getResultArray();
    }

}
