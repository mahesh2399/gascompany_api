<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class PurchaseOrderReturnModel extends Model
{
    protected $table = 'purchaseorderreturn';
    protected $primaryKey = 'Id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        "Id",
        "SupplierId",
        "PurchaseOrderId",
        "ProductId",
        "Quantity",
        "ReturnQuantity",
        "purchareOrderReturnNote",
        "Status",
        "CreatedDate",
        "CreatedBy",
        "DeletedBy",
        "created_at",
        "updated_at",
        "deleted_at",
        "DeliveryDate",
        "ReturnTaxId",
        "ReturnDate",

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

    public function getlistpurchaseorderreturnItem()
    {
        $sql = "SELECT por.Id as Id, por.ReturnDate, por.DeliveryDate,s.supplierName as supplierName,po.orderNumber as order_number,por.purchareOrderReturnNote as returnNotes, sum(pori.returnprice) as total_return_amount FROM purchaseorderreturn as por,suppliers as s,purchaseorders as po,purchaseorderitemsreturn as pori WHERE s.id = por.SupplierId and po.Id = por.PurchaseOrderId and pori.returnPurchaseOrderId = por.Id and pori.returnQuantity > 0 GROUP by por.Id ORDER BY por.Id Desc; ";

        return $this->query($sql)->getResultArray();
    }
}
