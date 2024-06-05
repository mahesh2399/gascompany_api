<?php
namespace App\Models\api;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class SaleorderModel extends Model
{


    protected $table = 'SalesOrders';
    protected $primaryKey = 'Id';
    protected $useAutoIncrement = false;
    protected $allowedFields = [
        'Id',
        'OrderNumber',
        'Note',
        'SaleReturnNote',
        'TermAndCondition',
        'IsSalesOrderRequest',
        'SOCreatedDate',
        'Status',
        'DeliveryDate',
        'DeliveryStatus',
        'CustomerId',
        'TotalAmount',
        'TotalTax',
        'TotalDiscount',
        'TotalPaidAmount',
        'PaymentStatus',
        'CreatedDate',
        'CreatedBy',
        'ModifiedDate',
        'ModifiedBy',
        'DeletedDate',
        'DeletedBy',
        'IsDeleted'
    ];

    public function getSaleData()
    {
        return $this->findAll();
    }



}
