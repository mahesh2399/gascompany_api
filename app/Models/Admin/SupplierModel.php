<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

// use Ramsey\Uuid\Uuid;

class SupplierModel extends Model
{
    protected $table = 'suppliers';
    protected $primaryKey = 'Id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [


        "Id",
        "SupplierName",
        "ContactPerson",
        "Email",
        "Fax",
        "MobileNo",
        "PhoneNo",
        "Website",
        "Description",
        "Url",
        "IsVarified",
        "IsUnsubscribe",
        "SupplierProfile",
        "BusinessType",
        "SupplierAddressId",
        "BillingAddressId",
        "ShippingAddressId",
        "CreatedDate",
        "CreatedBy",
        "ModifiedDate",
        "ModifiedBy",
        "DeletedDate",
        "DeletedBy",
        "IsDeleted",
        "created_at",
        "shipping_address",
        "billing_address",
        "supplier_address"
        // "Supplier_logo"

    ];




}
