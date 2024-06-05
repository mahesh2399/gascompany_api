<?php
namespace App\Controllers\Admin;

use App\Models\api\ProductModel;
use App\Models\api\UnitConversationsModel;

use App\Models\api\InventoryModel;

use CodeIgniter\RESTful\ResourceController;

use App\Models\api\PurchaseOrderitems;
use App\Models\Admin\PurchaseOrderModel;
use App\Models\Admin\PurchaseOrderReturnModel;
use App\Models\Admin\PurchaseorderItemsReturn;
use App\Models\Admin\ProductsModel;
use App\Models\Admin\SupplierModel;
use App\Models\Admin\UnitsModel;
use App\Models\Admin\TaxModel;




class PurchaseOrderController extends ResourceController
{
    public function purchaseOrderCreate()
    {
        $PM = new PurchaseOrderModel();
        $POIM = new PurchaseOrderitems();
        $ProductsModels = new ProductsModel();


        $json = $this->request->getJSON();
        $OrderNumber = isset($json->ordernumber) ? $json->ordernumber : null;
        $Note = isset($json->description) ? $json->description : null;
        $DeliveryDate = isset($json->deliverydate) ? $json->deliverydate : null;
        $PurchaseReturnNote = isset($json->purchasereturnnote) ? $json->purchasereturnnote : null;
        $TermAndCondition = isset($json->termsandcondition) ? $json->termsandcondition : null;
        $POCreatedDate = isset($json->createddate) ? $json->createddate : null;
        $Status = isset($json->status) ? $json->status : null;
        $TotalAmount = isset($json->TotalAmount) ? $json->TotalAmount : null;
        $TotalTax = isset($json->TotalTax) ? $json->TotalTax : null;
        $TotalDiscount = isset($json->TotalDiscount) ? $json->TotalDiscount : null;
        $TotalPaidAmount = isset($json->TotalPaidAmount) ? $json->TotalPaidAmount : null;
        $quotation = isset($json->quotation) ? $json->quotation : null;

        $SupplierId = isset($json->SupplierId) ? $json->SupplierId : null;


        $product = !empty($json->products) ? $json->products : null;

        $message = [];

        $Order_Prefix = "#PO";

        if ($product != null) {
            $datazzFromProductModel = [
                "OrderNumber" => $OrderNumber,
                "Note" => $Note,
                "DeliveryDate" => $DeliveryDate,
                "PurchaseReturnNote" => $PurchaseReturnNote,
                "TermAndCondition" => $TermAndCondition,
                "POCreatedDate" => $POCreatedDate,
                "SupplierId" => $SupplierId,
                "TotalAmount" => $TotalAmount,
                "TotalTax" => $TotalTax,
                "TotalDiscount" => $TotalDiscount,
                "TotalPaidAmount" => $TotalAmount,
                "quotation" => $quotation
                ,
            ];

            if (isset($json->Id)) {
                $PM->set($datazzFromProductModel)->where('Id', $json->Id)->update();
                $insertId = $json->Id;
                $message = [
                    'status' => 200,
                    'message' => 'Purchase Order Updated Successfully',
                ];
            } else {
                $PM->insert($datazzFromProductModel);
                $insertId = $PM->getInsertID();
                $message = [
                    'status' => 200,
                    'message' => 'Purchase Order Created Successfully',
                ];
            }



            foreach ($product as $datas) {


                $datazzFromPurchaseOrderitems = [


                    "PurchaseOrderId" => $insertId,
                    "ProductId" => $datas->productid,
                    // "Status" =>  $datas->status,
                    "price" => $datas->price,
                    "TaxValue" => $datas->taxvalue,
                    "Discount" => $datas->discount,
                    // "DiscountPercentage" =>  $datas->discount,
                    "UnitId" => $datas->unitid,
                    "CreatedDate" => $POCreatedDate,
                    // "WarehouseId" =>  $datas->warehouseid,
                    "Quantity" => $datas->quantity,
                    "sub_total_after_discount" => $datas->sub_total_after_discount,
                    "sub_total_before_discount" => $datas->sub_total_before_discount,
                    "taxid" => $datas->taxid,
                ];
                $data = "";
                $data = isset($datas->Id) ? $datas->Id : "";

                if ($data != "") {
                    $productDetails = $ProductsModels->where('id', $datas->productid)->first();
                    $poiDetails = $POIM->where('Id', $data)->first();
                    $stq = $productDetails['stock_qty'] - $poiDetails['Quantity'];
                    $stq = $stq + $datas->quantity;
                    $ProductsModels->set(['stock_qty' => $stq])->where('id', $datas->productid)->update();
                    $POIM->set($datazzFromPurchaseOrderitems)->where('Id', $data)->update();

                } else {
                    $productDetails = $ProductsModels->where('id', $datas->productid)->first();
                    $stq = $productDetails['stock_qty'];
                    $stq = $stq + $datas->quantity;
                    $ProductsModels->set(['stock_qty' => $stq])->where('id', $datas->productid)->update();
                    $POIM->insert($datazzFromPurchaseOrderitems);


                }





            }
            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);

        } else {
            $message = [
                'status' => 400,
                'message' => 'No Data Provided'
            ];
            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
        }


    }

    public function purchaseOrderEdit()
    {
        $purchaseorder = new PurchaseOrderReturnModel();
        $purchaseorderitem = new PurchaseorderItemsReturn();

        $json = $this->request->getJSON();
        $id = isset($json->id) ? $json->id : null;

        $PurchaseOrderId = isset($json->PurchaseOrderId) ? $json->PurchaseOrderId : null;
        $productId = isset($json->ProductId) ? $json->ProductId : null;
        $quantity = isset($json->Quantity) ? $json->Quantity : null;
        $createdby = isset($json->CreatedBy) ? $json->CreatedBy : null;
        $purchareorderreturnnote = isset($json->purchareOrderReturnNote) ? $json->purchareOrderReturnNote : null;
        $Status = isset($json->Status) ? $json->Status : null;
        $products = isset($json->products) ? $json->products : null;

        $createorder = array(
            "Id" => $id,
            "PurchaseOrderId" => $PurchaseOrderId,
            "ProductId" => $productId,
            "Quantity" => $quantity,
            "CreatedBy" => $createdby,
            "purchareOrderReturnNote" => $purchareorderreturnnote,
            "Status" => $Status
        );

        // Update the purchase order
        $purchaseorder->set($createorder)->where('Id', $id)->update();

        // Update each purchase order item
        foreach ($products as $item) {
            $returnorderitems = array(

                "returnProductId" => $item->returnProductId,
                "returnPurchaseOrderId" => $PurchaseOrderId,
                "returnprice" => $item->returnprice,
                "returnTaxValue" => $item->returnTaxValue,
                "returnDiscount" => $item->returnDiscount,
                "returnDiscountPercentage" => $item->returnDiscountPercentage,
                "returnsub_total_before_discount" => $item->returnsub_total_before_discount,
                "returnsub_total_after_discount" => $item->returnsub_total_after_discount,
                "created_by" => $item->created_by,
                "returnQuantity" => $item->returnQuantity,
                "returnUnitId" => $item->returnUnitId,
                "returnWarehouseId" => $item->returnWarehouseId,
                "returnStatus" => $item->returnStatus,
            );
            $purchaseorderitem->set($returnorderitems)->where('Id', $item->Id)->update();


            // Update each purchase order item
        }
        // Respond with success message
        return $this->response->setStatusCode(200)->setJSON(['messageObject' => "Purchase order and items updated successfully"]);
    }


    public function purchaseOrderReturnCreate()
    {

        $orderReturn = new PurchaseOrderReturnModel();
        $orderItemsReturn = new PurchaseorderItemsReturn();
        $ProductsModels = new ProductsModel();
        $json = $this->request->getJSON();
        $PurchaseOrderId = isset($json->purchaseOrderId) ? $json->purchaseOrderId : null;
        $productId = isset($json->productId) ? $json->productId : null;
        $quantity = isset($json->quantity) ? $json->quantity : null;
        $Returnquantity = isset($json->Returnquantity) ? $json->Returnquantity : null;
        $createdby = isset($json->createdby) ? $json->createdby : null;
        $purchareorderreturnnote = isset($json->purchareorderreturnnote) ? $json->purchareorderreturnnote : null;
        $products = isset($json->return_products) ? $json->return_products : null;
        $supplierId = isset($json->supplierId) ? $json->supplierId : null;

        $DeliveryDate = isset($json->delivery_date) ? $json->delivery_date : null;
        $ReturnDate = isset($json->order_date) ? $json->order_date : null;

        $createorder = array(
            "PurchaseOrderId" => $PurchaseOrderId,
            "Quantity" => $quantity,
            "CreatedBy" => $createdby,
            "purchareOrderReturnNote" => $purchareorderreturnnote,
            "SupplierId" => $supplierId,
            "ReturnDate" => $ReturnDate,
            "DeliveryDate" => $DeliveryDate
        );
        $chkId = isset($json->Id) ? $json->Id : "";
        if ($chkId != '') {
            $orderReturn->set($createorder)->where('Id', $json->Id)->update();
            $insertId = $json->Id;
            $message = [
                'message' => 'Purchase Order Return Update Successfully',
                'status' => 200
            ];
        } else {
            $orderReturn->insert($createorder);
            $insertId = $orderReturn->getInsertID();
            $message = [
                'message' => 'Purchase Order Return Created Successfully',
                'status' => 200
            ];
        }

        foreach ($products as $datas) {

            $returnorderitems = [
                "returnProductId" => $datas->product_id,
                "returnPurchaseOrderId" => $insertId,
                "returnprice" => $datas->price,
                "returnTaxValue" => $datas->returnTaxValue,
                "returnDiscount" => $datas->returndiscount,
                "returnDiscountPercentage" => $datas->returndiscountpercentage,
                "returnsub_total_before_discount" => $datas->returnsub_total_before_discount,
                "returnsub_total_after_discount" => $datas->returnsub_total_after_discount,
                "created_by" => $createdby,
                "returnQuantity" => $datas->returnquantity,
                "returnUnitId" => $datas->returnunitid,
                "returnTaxId" => $datas->returnTaxId,
                "returnWarehouseId" => $datas->returnwarehouseid,
                "returnStatus" => $datas->returnstatus,
            ];

            $id = isset($datas->Id) ? $datas->Id : "";

            if ($id != "") {


                $orditmRtn = $orderItemsReturn->where('Id', $id)->first();

                $product = $ProductsModels->where('id', $datas->product_id)->first();

                $product['stock_qty'] = $product['stock_qty'] + $orditmRtn['returnQuantity'];

                $stock_qty = $product['stock_qty'] - $datas->returnquantity;

                $ProductsModels->set(['stock_qty' => $stock_qty])->where('id', $datas->product_id)->update();

                $orderItemsReturn->set($returnorderitems)->where('Id', $id)->update();

            } else {
                $orderItemsReturn->insert($returnorderitems);
                $product = $ProductsModels->where('id', $datas->product_id)->first();
                $stock_qty = $product['stock_qty'] - $datas->returnquantity;
                $ProductsModels->set(['stock_qty' => $stock_qty])->where('id', $datas->product_id)->update();
            }

        }


        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
    }

    public function PurchaseOrder()
    {
        $Purchaseautonumber = 0;
        $PurchaseOrderModel = new PurchaseOrderModel();
        $purchaseData = $PurchaseOrderModel->orderBy('id', 'DESC')->first();
        $Purchaseautonumber = isset($purchaseData) ? intval($purchaseData["Id"]) : 0;
        $Purchaseautonumber += 1;
        $formatted_number = str_pad($Purchaseautonumber, 5, '0', STR_PAD_LEFT);
        $PurchaseorderNumber = "PO#" . $formatted_number;

        $message = [
            'message' => 'Purchase Order Number Generated Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'PurchaseorderNumber' => $PurchaseorderNumber]);
    }

    public function getPurchaseorderReturn()
    {
        $purchaseorder = new PurchaseOrderReturnModel();


        $purchaseorderreturn = $purchaseorder->getlistpurchaseorderreturnItem();

        $message = [
            'message' => 'Purchase Order Returns Data Fetched Successfully.'
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $purchaseorderreturn]);

    }

    public function getPurchaseorderReturnById()
    {
        $purchaseorder = new PurchaseOrderReturnModel();
        $ProductsModels = new ProductsModel();
        $PurchaseOrderModel = new PurchaseOrderModel();
        $PurchaseorderItemsReturns = new PurchaseorderItemsReturn();
        $UnitsModels = new UnitsModel();
        $TaxModels = new TaxModel();
        $json = $this->request->getJSON();
        $purchaseorderreturn = $purchaseorder->where('Id', $json->id)->first();

        $purchaseorderreturn['purchase_order_details'] = $PurchaseOrderModel->where('Id', $purchaseorderreturn['PurchaseOrderId'])->first();

        $purchaseorderreturn['return_items'] = [];
        $PurchaseorderItemsReturnslist = $PurchaseorderItemsReturns->where('returnPurchaseOrderId', $purchaseorderreturn['Id'])->findAll();

        $SupplierModels = new SupplierModel();
        $purchaseorderreturn['suppliers_details'] = [];
        $purchaseorderreturn['suppliers_details'] = $SupplierModels->where('id', $purchaseorderreturn['SupplierId'])->first();
        $returnItems = [];
        foreach ($PurchaseorderItemsReturnslist as $list) {
            $list['products_details'] = $ProductsModels->where('id', $list['returnProductId'])->first();
            $list['tax_details'] = $TaxModels->where('id', $list['returnTaxId'])->first();
            $list['products_details']['units_details'] = $UnitsModels->where('id', $list['products_details']['unit_id'])->first();
            $returnItems[] = $list;
        }

        $purchaseorderreturn['return_items'] = $returnItems;

        $message = [
            'message' => 'Purchase Order Return Data Fetched Successfully.'
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $purchaseorderreturn]);

    }

    public function deletePurchaseorderReturnById()
    {
        $purchaseorderreturn = new PurchaseOrderReturnModel();
        $json = $this->request->getJSON();
        $purchaseorderreturn = $purchaseorderreturn->delete($json->id);

        $message = [
            'message' => 'Purchase Order Return Deleted Successfully.',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
    }
}



