<?php
namespace App\Controllers\Admin;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Admin\SaleOrderModel;
use App\Models\Admin\Order_Groups_Model;
use App\Models\Admin\OrdersModel;
use App\Models\Admin\Order_Items_Model;
use App\Models\Admin\UsersModel;
use App\Models\Admin\ProductsModel;
use App\Models\Admin\Order_return_itemsModel;
use App\Models\Admin\Order_returnsModel;
use App\Models\Admin\ProductVariationsModel;
use App\Models\Admin\UnitsModel;
use App\Models\Admin\TaxModel;
// use App\Models\Admin\ProductVariationsModel;




class SaleOrderController extends ResourceController
{
    // ============= GETSALEORDR ================
    public function getsaleorder()
    {

        $SaleOrderModels = new SaleorderModel;
        $requestMethod = $this->request->getMethod();
        if ($requestMethod !== 'get') {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request method']);
        } else {

            $headers = $this->request->getHeaders();


            if (!isset($headers['AUTHORIZATION'])) {
                $orderNumber = 'SO#00001';

                if (isset($orderNumber)) {


                    $result = $SaleOrderModels->getSaleOrderData($orderNumber);

                    return $this->response->setStatusCode(200)->setJSON(['mesaage' => 'saleorder Successfully Fetch Data', 'ResponseData' => $result]);
                } else {
                    return $this->response->setStatusCode(401)->setJSON(['error' => 'Invaild Order Id']);

                }
            } else {
                return $this->response->setStatusCode(401)->setJSON(['error' => 'Authorization header is missing']);

            }
        }

    }

    public function getSaleorderDetails()
    {
        $SaleOrderModels = new SaleOrderModel;
        $saleDetails = $SaleOrderModels->getSaleorders();

        $message = [
            'message' => 'Successfully Retrieved Sale Order Details!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $saleDetails]);

    }

    public function autogetSaleorder()
    {
        $salesautonumber = 0;
        $SaleOrderModels = new SaleOrderModel();
        $salesData = $SaleOrderModels->orderBy('id', 'DESC')->first();
        $salesautonumber = isset($salesData) ? intval($salesData["Id"]) : 0;
        $salesautonumber += 1;
        $formatted_number = str_pad($salesautonumber, 5, '0', STR_PAD_LEFT);
        $salesorderNumber = "SO#" . $formatted_number;

        $message = [
            'message' => 'Successfully Generated Sales Order Number!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'salesorderNumber' => $salesorderNumber]);
    }


    public function SaleOrderAddEdit()
    {

        $SaleOrderModels = new SaleOrderModel();
        $OrdersModels = new OrdersModel();
        $OrdersGrpModels = new Order_Groups_Model();
        $OrdersItemModels = new Order_Items_Model();
        $usermodel = new UsersModel();
        $productModel = new ProductsModel();
        $ProductVariationsModels = new ProductVariationsModel();
        $TaxModels = new TaxModel();



        $json = $this->request->getJSON();

        $salesorderId = isset($json->Id) ? $json->Id : "";
        $OrderNumber = isset($json->ordernumber) ? $json->ordernumber : null;
        $Note = isset($json->Note) ? $json->Note : null;
        $SaleReturnNote = isset($json->SaleReturnNote) ? $json->SaleReturnNote : null;
        $TermAndCondition = isset($json->termandcondition) ? $json->termandcondition : null;
        $IsSalesOrderRequest = isset($json->IsSalesOrderRequest) ? $json->IsSalesOrderRequest : 'YES';
        $SOCreatedDate = isset($json->SOCreatedDate) ? $json->SOCreatedDate : null;
        $DeliveryDate = isset($json->deliverydate) ? $json->deliverydate : null;
        $Status = isset($json->Status) ? $json->Status : null;
        $DeliveryStatus = isset($json->DeliveryStatus) ? $json->DeliveryStatus : null;
        $CustomerId = isset($json->CustomerId) ? $json->CustomerId : null;
        $TotalAmount = isset($json->TotalAmount) ? $json->TotalAmount : null;
        $TotalTax = isset($json->TotalTax) ? $json->TotalTax : null;
        $TotalDiscount = isset($json->TotalDiscount) ? $json->TotalDiscount : null;
        $TotalPaidAmount = isset($json->TotalPaidAmount) ? $json->TotalPaidAmount : 0;
        // $PaymentStatus = isset($json->PaymentStatus) ? $json->PaymentStatus : null;
        $PurchaseReturnNote = isset($json->purchasereturnnote) ? $json->purchasereturnnote : null;
        $products = !empty($json->products) ? $json->products : null;
        // $subTotal = !empty($json->subTotal) ? $json->subTotal : null;
        $total_shipping_cost = isset($json->total_shipping_cost) ? $json->total_shipping_cost : 0;
        $payment_method = isset($json->payment_method) ? $json->payment_method : null;
        $payment_status = isset($json->PaymentStatus) ? $json->PaymentStatus : null;

        $shipping_address_id = isset($json->shipping_address_id) ? $json->shipping_address_id : null;
        $billing_address_id = isset($json->billing_address_id) ? $json->billing_address_id : null;
       

        $user = $usermodel->where('id', $CustomerId)->first();

        if ($payment_status == "Paid" && $TotalPaidAmount == 0) {
            $TotalPaidAmount = $TotalAmount;
        }

        $countRes = $SaleOrderModels->where('OrderNumber', $OrderNumber)->first();

        if (!$countRes || ($salesorderId !='' && $countRes)) {

            if($salesorderId !=''){
                $message = [
                    'status' => 200,
                    'message' => 'Order Updated Successfully'
                ];
            }else{
                $message = [
                    'status' => 200,
                    'message' => 'Order Created Successfully'
                ];
            }

            $data = [
                "OrderNumber" => $OrderNumber,
                "Note" => $Note,
                "SaleReturnNote" => $SaleReturnNote,
                "TermAndCondition" => $TermAndCondition,
                "IsSalesOrderRequest" => $IsSalesOrderRequest,
                "SOCreatedDate" => $SOCreatedDate,
                "Status" => $Status,
                "DeliveryStatus" => $DeliveryStatus,
                "CustomerId" => $CustomerId,
                "TotalAmount" => $TotalAmount,
                "TotalTax" => $TotalTax,
                "TotalDiscount" => $TotalDiscount,
                "TotalPaidAmount" => $TotalPaidAmount,
                "PaymentStatus" => $payment_status,
                "PurchaseReturnNote" => $PurchaseReturnNote,
                "DeliveryDate" => $DeliveryDate

            ];

            if ($salesorderId != "") {
                $SaleOrderModels->set($data)->where('Id', $salesorderId)->update();
                $saleorderid=$salesorderId;
            } else {
                $saleorderid = $SaleOrderModels->insert($data);
            }



            $insertData = [
                "user_id" => $CustomerId,
                "guest_user_id" => null,
                "order_code" => 1,
                "shipping_address_id" => isset($shipping_address_id) ? $shipping_address_id : null,
                "billing_address_id" => isset($billing_address_id) ? $billing_address_id : null,
                "phone_no" => isset($user['phone']) ? $user['phone'] : null,
                "alternative_phone_no" => isset($user['phone']) ? $user['phone'] : null,
                "total_tax_amount" => $TotalTax,
                "total_coupon_discount_amount" => $TotalDiscount,
                "total_shipping_cost" => $total_shipping_cost,
                "payment_method" => $payment_method,
                "payment_status" => $payment_status,
                "grand_total_amount" => $TotalAmount

            ];

            if($salesorderId != ""){
                $saleorder = $SaleOrderModels->where('Id',$salesorderId)->first();

                $ordInst = [
                    'shop_id' => isset($user['shop_id']) ? $user['shop_id'] : 1,
                    'user_id' => $CustomerId,
                    "payment_method" => $payment_method,
                    "payment_status" => $payment_status,
                    "order_form" => "ADM",
                    'delivery_status'=>'Processing'
                ];
                $OrdersModels->set($ordInst)->where('id',$saleorder['order_id'])->update();
                $ord_id = $saleorder['order_id'];

                $ordDetails = $OrdersModels->where('id',$ord_id)->first();

                $OrdersGrpModels->set($insertData)->where('id',$ordDetails['order_group_id'])->update();

            }else{
                $order_grp_id = $OrdersGrpModels->insert($insertData);
                $OrdersGrpModels->set(['order_code' => $order_grp_id])->where('id', $order_grp_id)->update();

                $ordInst = [
                    'order_group_id' => $order_grp_id,
                    'shop_id' => isset($user['shop_id']) ? $user['shop_id'] : 1,
                    'user_id' => $CustomerId,
                    "payment_method" => $payment_method,
                    "payment_status" => $payment_status,
                    "order_form" => "ADM",
                    'delivery_status'=>'Processing'
                ];
                $ord_id = $OrdersModels->insert($ordInst);
            }

            $SaleOrderModels->set(["order_id" => $ord_id])->where('Id', $saleorderid)->update();

            foreach ($products as $list) {

                
                $itmId = isset($list->id) ? $list->id : "";
                if($itmId != ""){
                    $orditmDetails = $OrdersItemModels->where('id', $itmId)->first();
                    $productModel->where("id", $list->product_id);
                    $product = $productModel->first();
                    $product['stock_qty'] = $product['stock_qty'] + $orditmDetails['qty'];
                    $quantity = $product['stock_qty'] - $list->quantity;
                    $productModel->set(['stock_qty' => $quantity])->where('id', $list->product_id)->update();
                }else{
                    $productModel->where("id", $list->product_id);
                    $product = $productModel->first();
                    $quantity = $product['stock_qty'] - $list->quantity;
                    $productModel->set(['stock_qty' => $quantity])->where('id', $list->product_id)->update();
                }
               
            }

           foreach ($products as $list) {

                $productModel->where('id', $list->product_id);
                $product = $productModel->findAll();

                $productvariation = $ProductVariationsModels->where('product_id',$list->product_id)->first();


               

                if(isset($list->tax_id)){
                  $taxsdetails =  $TaxModels->where('id',$list->tax_id)->first();
                  $taxvalue = $taxsdetails['value'];
                }else{
                    $taxvalue = 0;
                }

               


                $ordItem = [
                    "order_id" => $ord_id,
                    "product_variation_id" => $productvariation['id'],
                    "qty" => $list->quantity,
                    "location_id" => isset($list->location_id) ? $list->location_id : null,
                    "unit_price" => $list->price,
                    "discount"=>isset($list->discount_percentage) ? $list->discount_percentage : null,
                    "total_price" => $list->total_price,
                    "tax_id"=>isset($list->tax_id) ? $list->tax_id : null,
                    "total_tax"=>$taxvalue,
                    "unit_id"=>isset($list->unit_id) ? $list->unit_id : null
                ];
                $itmId = isset($list->id) ? $list->id : "";
                if ($itmId != '') {
                    $OrdersItemModels->set($ordItem)->where('id', $list->id)->update();
                } else {
                    $OrdersItemModels->insert($ordItem);
                }

            }

            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);

        } else {
            $message = [
                'status' => 400,
                'message' => 'Order Number Already Exits'
            ];
            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
        }


    }

    public function getsaleorderdetailsbyid()
    {
        
        $requestData = $this->request->getJSON();
    
        $saleorderid = $requestData->saleorderid;
    

        $SaleOrderModels = new SaleOrderModel();
        $OrdersModels = new OrdersModel();
        $OrdersGrpModels = new Order_Groups_Model();
        $OrdersItemModels = new Order_Items_Model();
        $usermodels = new UsersModel();
        $ProductVariationsModels = new ProductVariationsModel();
        $ProductsModels = new ProductsModel();
        $UnitModel = new UnitsModel();

        

        $sod = $SaleOrderModels->where('OrderNumber', $saleorderid)->first();

        $userlist = $usermodels->where('id',$sod['CustomerId'])->first();

        $sod['customer_name'] = $userlist['name'];
        

        // $orders = $SaleOrderModels->

        $orderlist = $OrdersItemModels->where('order_id', $sod['order_id'])->findAll();
        $orderlistarr = [];
        foreach($orderlist as $list){
           $productvariationlist =  $ProductVariationsModels->where('id',$list['product_variation_id'])->first();
           $productlist = $ProductsModels->where('id',$productvariationlist['product_id'])->first();
           $list['products']= $productlist;
           $list['products']['unit_details']= $UnitModel->where('id',$productlist['unit_id'])->first();
           $orderlistarr[] = $list;
        }
        $sod['order_items'] = $orderlistarr;

        $message = [
            'status' => 200,
            'message' => 'Sale Order Details Fetch Data Successfully'
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $sod]);
    }

    public function saleOrderDelete()
    {
        $SaleOrderModels = new SaleOrderModel();
        $json = $this->request->getJSON();
        $purchaseorderreturn= $SaleOrderModels->delete($json->id);

        $message = [
            'status' => 200,
            'message' => 'Order Deleted Successfully'
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
    }

    public function getsaleorderdetailByIdNew()
    {
        
        $requestData = $this->request->getJSON();
        $saleorderid = $requestData->id;
    
        $SaleOrderModels = new SaleOrderModel();
        $OrdersModels = new OrdersModel();
        $OrdersGrpModels = new Order_Groups_Model();
        $OrdersItemModels = new Order_Items_Model();
        $usermodels = new UsersModel();
        $ProductVariationsModels = new ProductVariationsModel();
        $ProductsModels = new ProductsModel();
        $UnitsModels = new UnitsModel();
        $TaxModels = new TaxModel();

        $sod = $SaleOrderModels->where('Id', $saleorderid)->first();
        $userlist = $usermodels->where('id',$sod['CustomerId'])->first();

        $sod['customer_name'] = $userlist['name'];

        $orderlist = $OrdersItemModels->where('order_id', $sod['order_id'])->findAll();
        $orderlistarr = [];
        foreach($orderlist as $list){
           $productvariationlist =  $ProductVariationsModels->where('id',$list['product_variation_id'])->first();
           $productlist = $ProductsModels->where('id',$productvariationlist['product_id'])->first();
           $list['unit_details'] = $UnitsModels->where('id',$productlist['unit_id'])->first();
           $list['tax_details'] = $TaxModels->where('id',$list['tax_id'])->first();
           $list['products']= $productlist;
           $orderlistarr[] = $list;
        }
        $sod['order_items'] = $orderlistarr;

        $message = [
            'status' => 200,
            'message' => 'Sale Order Details Retrieved Successfully'
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $sod]);
    }

    public function saleorderreturn()
    {
        $Order_return_itemsModels = new Order_return_itemsModel;
        $Order_returnsModels = new Order_returnsModel;

        $OrdersItemModels = new Order_Items_Model();

        $ProductVariationsModels = new ProductVariationsModel();
        $ProductsModels = new ProductsModel();

        $json = $this->request->getJSON();
        $order_return_id = isset($json->id) ? $json->id : "";

        $SaleOrderModels = new SaleOrderModel();

        if (isset($json->ordernumber)) {
            $sodtl = $SaleOrderModels->where('OrderNumber', $json->ordernumber)->first();
            $message = "";
            if ($sodtl) {
                $ordRtn = $Order_returnsModels->where('order_id', $json->ordernumber)->findAll();
                if ($ordRtn) {
                    $message = [
                        'status' => 400,
                        'message' => 'Unable To Return'
                    ];
                    return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
                } else {
                   
                    if($order_return_id !=''){
                        $message = [
                            'message' => 'Order Return Updated Successfully ',
                            'status' => 200
                        ];
                    }else{
                        $message = [
                            'message' => 'Order Return Created Successfully ',
                            'status' => 200
                        ];
                    }
                    $data = [
                        "order_id"=>$sodtl['order_id'],
                        "ordernumber" => $json->ordernumber,
                        "return_date" => $json->return_date,
                        "return_reason" => $json->return_reason
                    ];
                    if($order_return_id != ""){
                       $Order_returnsModels->set($data)->where('id',$order_return_id)->update();
                       $orId = $order_return_id;
                    }else{
                        $orId = $Order_returnsModels->insert($data);
                    }

                    foreach ($json->order_item_id as $list) {
                        $oritemreturn = [
                            "order_returns_id" => $orId,
                            "order_item_id" => $list->order_item_id,
                            "returned_quantity" => $list->returned_quantity,
                            "returned_amount" => $list->returned_amount,
                            "discount" => isset($list->discount)? $list->discount : 0,
                            "tax_id" => $list->tax_id,
                        ];

                        $Order_return_items_id = isset($list->id) ? $list->id : "";

                        if($Order_return_items_id != ""){
                            $order_return_item_details = $Order_return_itemsModels->where('id',$Order_return_items_id)->first();

                            $orderitems = $OrdersItemModels->where('id', $list->order_item_id)->first();
                            $product_varitions = $ProductVariationsModels->where('id',$orderitems['product_variation_id'])->first();
                            $product = $ProductsModels->where('id',$product_varitions['product_id'])->first();

                            $product['stock_qty'] = $product['stock_qty'] - $order_return_item_details['returned_quantity'];
                            $stock_qty = $product['stock_qty'] + $list->returned_quantity;


                            $ProductsModels->set(['stock_qty'=>$stock_qty])->where('id',$product_varitions['product_id'])->update();

                            $Order_return_itemsModels->set($oritemreturn)->where('id',$Order_return_items_id)->update();
                        }else{
                            $Order_return_itemsModels->insert($oritemreturn);
                            $orderitems = $OrdersItemModels->where('id', $list->order_item_id)->first();
                            $product_varitions = $ProductVariationsModels->where('id',$orderitems['product_variation_id'])->first();
                            $product = $ProductsModels->where('id',$product_varitions['product_id'])->first();
                            $stock_qty = $product['stock_qty'] + $list->returned_quantity;
                            $ProductsModels->set(['stock_qty'=>$stock_qty])->where('id',$product_varitions['product_id'])->update();
                        }


                    }
                  
                    return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
                }
            } else {
                $message = [
                    'status' => 400,
                    'message' => 'Already Return Applied'
                ];
                return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
            }

        } else {
            $message = [
                'status' => 400,
                'message' => 'Bad Request'
            ];
            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
        }
    }

    public function salesordergetlist(){

        $SaleOrderModels = new SaleOrderModel();
        $salesorderlist = $SaleOrderModels->getsalesorderreturnlist();

       $message = [
            'status' => 200,
            'message' => 'Data Fetched Successfully'
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $salesorderlist]);

        
    }

    public function getsaleorderreturnbyid(){

        $Order_return_itemsModels = new Order_return_itemsModel;
        $Order_returnsModels = new Order_returnsModel;
        $OrdersItemModels = new Order_Items_Model();
        $ProductVariationsModels = new ProductVariationsModel();
        $ProductsModels = new ProductsModel();
        $SaleOrderModels = new SaleOrderModel();


        $order_return_id = $this->request->getGet('order_return_id');
        

        $orderreturn = $Order_returnsModels->getorderreturnbyid($order_return_id);

        $orderreturn[0]['items'] = $Order_returnsModels->getreturnitems($order_return_id);

        $message = [
            'status' => 200,
            'message' => 'Data Fetched Successfully'
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $orderreturn[0]]);

    }


    public function getsaleorderreturnbyidview(){

        $Order_return_itemsModels = new Order_return_itemsModel;
        $Order_returnsModels = new Order_returnsModel;
        $OrdersItemModels = new Order_Items_Model();
        $ProductVariationsModels = new ProductVariationsModel();
        $ProductsModels = new ProductsModel();
        $SaleOrderModels = new SaleOrderModel();


        $order_return_id = $this->request->getGet('order_return_id');
        

        $orderreturn = $Order_returnsModels->getorderreturnbyid($order_return_id);

        $orderreturn[0]['items'] = $Order_returnsModels->getreturnitems($order_return_id);
        $SubTotal = 0;
        $Total_discount = 0;
        $Total_tax = 0;
        $total_value = 0;


        foreach($orderreturn[0]['items'] as $list ){
            $tempSubTotal = $list['unit_price'] * $list['returned_quantity'];
            $SubTotal +=  $tempSubTotal;
            $temp_dis = $list['discount'];
            if($list['discount'] == null){
                $temp_dis = 0;
            }
            $n = $temp_dis;
            $p = $tempSubTotal;
            $temp_total_per = ($n*$p)/100;

            

            $Total_discount +=$temp_total_per;
            $total_value = $tempSubTotal - $temp_total_per;
            $tax_value = $list['tax_value'];
            if($list['tax_value'] == null){
                $tax_value = 0;
            }
            $temp_tax = ($tax_value * $total_value)/100;
            $Total_tax += $temp_tax;

        }
        $orderreturn[0]['total_sub_total'] =  $SubTotal;
        $orderreturn[0]['total_discount_total'] = $Total_discount;
        $orderreturn[0]['total_tax_total'] = $Total_tax;
        $orderreturn[0]['total_grand_total'] = $SubTotal -  $Total_discount + $Total_tax;

        $message = [
            'status' => 200,
            'message' => 'Data Fetched Successfully'
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $orderreturn[0]]);

    }

    public function saleOrderReturnDelete()
    {
        $Order_returnsModels = new Order_returnsModel;
        $json = $this->request->getJSON();
        $purchaseorderreturn= $Order_returnsModels->delete($json->id);

        $message = [
            'status' => 200,
            'message' => 'Order Return Deleted Successfully'
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
    }


}
