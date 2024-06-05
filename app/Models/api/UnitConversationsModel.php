<?php
namespace App\Models\api;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class UnitConversationsModel extends Model
{
    protected $table = 'UnitConversations';
    protected $primaryKey = 'Id';
    protected $useAutoIncrement = false;
    protected $allowedFields = [
        'Id',
        'Name',
        'Code',
        'Operator',
        'Value',
        'ParentId',
        'CreatedDate',
        'CreatedBy',
        'ModifiedDate',
        'ModifiedBy',
        'DeletedDate',
        'DeletedBy',
        'IsDeleted'
    ];
}
