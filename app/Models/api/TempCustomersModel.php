<?php
namespace App\Models\api;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class TempCustomersModel extends Model
{
    protected $table = 'TempCustomers';
    protected $primaryKey = 'Id';
    protected $useAutoIncrement = false;
    protected $allowedFields = [
        'Id',
        'CustomerName',
        'ContactPerson',
        'Email',
        'Fax',
        'MobileNo',
        'PhoneNo',
        'Website',
        'Description',
        'Url',
        'IsVarified',
        'IsUnsubscribe',
        'CustomerProfile',
        'Address',
        'CountryName',
        'CityName',
        'CountryId',
        'CityId',
        'IsWalkIn',
        'CreatedDate',
        'CreatedBy',
        'ModifiedDate',
        'ModifiedBy',
        'DeletedDate',
        'DeletedBy',
        'IsDeleted',
        'Password',
        'VerificationToken'
    ];
}
