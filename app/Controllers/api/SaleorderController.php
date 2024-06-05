<?php
namespace App\Controllers\Api;

use App\Models\api\SaleorderModel;
use App\Models\admin\OrdersModel;
use App\Models\api\SalesOrderItemsModel;
use App\Models\api\CartModel;

use CodeIgniter\RESTful\ResourceController;




class SaleorderController extends ResourceController
{
    public function index()
    { 
        $SaleorderModels = new SaleorderModel();
        $OrderNumber = $this->request->getGet('OrderNumber');
        $CustomerId = $this->request->getGet('CustomerId');
        if (isset($OrderNumber)) {
            $SaleorderModels->where('OrderNumber', $OrderNumber);
        }
        if (isset($CustomerId)) {
            $SaleorderModels->where('CustomerId', $CustomerId);
        }
        $result = $SaleorderModels->findAll();
        return $this->respond($result);
    }


    public function confirmOrder()
    {

        $CartModels = new CartModel;

        $CustomerId = $this->request->user->Id;

        $CartModels->where('CustomerId', $CustomerId);
        $cardlist = $CartModels->find();

        $saleorderlist = [

        ];

        $CartModels->insert($saleorderlist);

    }


}
