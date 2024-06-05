<?php

namespace App\Controllers\Admin;

use CodeIgniter\RESTful\ResourceController;

use App\Models\Admin\ProductsModel;
use App\Models\Admin\OrdersModel;
use App\Models\Admin\Order_Groups_Model;
use App\Models\Admin\Order_Items_Model;
use App\Models\Admin\UsersModel;
use App\Models\Admin\SaleOrderModel;
use App\Models\Admin\PaymentgatwayModel;


class OrderController extends ResourceController {


    public function posplaceorder()
    {

        $SaleOrderModels = new SaleOrderModel();
        $OrdersModels = new OrdersModel();
        $OrdersGrpModels = new Order_Groups_Model();
        $OrdersItemModels = new Order_Items_Model();
        $usermodel = new UsersModel();
        $json = $this->request->getJSON();

        $user_id = isset($json->user_id) ? $json->user_id : null;
        $products = isset($json->products) ? $json->products : null;
        $phone = isset($json->phone) ? $json->phone : null;
        $alternative_phone_no = isset($json->alternative_phone_no) ? $json->alternative_phone_no : null;
        

        $sub_total_amount = isset($json->sub_total_amount)? $json->sub_total_amount : null;
        // $total_amount = isset($json->total_amount) ? $json->total_amount : null;
        $total_tax = isset($json->total_tax) ? $json->total_tax : null;
        $total_discount = isset($json->total_discount) ? $json->total_discount : null;
        $grand_total = isset($json->grand_total) ? $json->grand_total : 0;
        $total_shipping_cost = isset($json->total_shipping_cost) ? $json->total_shipping_cost : 0;

        $payment_method = isset($json->payment_method) ? $json->payment_method : "";
        $payment_status = isset($json->payment_status) ? $json->payment_status : "";
        $delivery_status = isset($json->delivery_status) ? $json->delivery_status : "";
        $discount_percentage = isset($json->discount_percentage) ? $json->discount_percentage : "";
        $tax_percentage = isset($json->tax_percentage) ? $json->tax_percentage : "";
        $location_id = isset($json->location_id) ? $json->location_id : "";
        $unit_id = isset($json->unit_id) ? $json->unit_id : "";




        $shipping_address_id = isset($json->shipping_address_id) ? $json->shipping_address_id : null;
        $billing_address_id = isset($json->billing_address_id) ? $json->billing_address_id : null;

        $usrDetails = [];
        if($phone != null && $user_id == null){
            $usrDetails = $usermodel->where('phone',$json->phone)->first();
            $user_id  = $usrDetails['id'];
            $phone = $usrDetails['phone'];
        }
        
        if ($products != null) {
            $insertData = [
                "user_id" => $user_id,
                "guest_user_id" => null,
                "order_code" => 1,
                "shipping_address_id" => $shipping_address_id,
                "billing_address_id" => $billing_address_id,
                "phone_no" => $phone,
                "alternative_phone_no" => $alternative_phone_no,
                "sub_total_amount" => $sub_total_amount,
                "total_tax_amount" => $total_tax,
                "total_coupon_discount_amount" => $total_discount,
                "total_shipping_cost" => $total_shipping_cost,
                "grand_total_amount" => $grand_total,
                "payment_status"=>$payment_status,
                "payment_method"=>$payment_method,
                "is_pos_order"=>1,
                "location_id" => isset($list->location_id) ? $list->location_id : 1,
            ];
            $order_grp_id = $OrdersGrpModels->insert($insertData);
    
            // $OrdersGrpModels->where('id', $order_grp_id);
            $OrdersGrpModels->set(['order_code' => $order_grp_id])->where('id', $order_grp_id)->update();

            $ordInst = [
                'order_group_id' => $order_grp_id,
                'shop_id' => isset($usrDetails['shop_id']) ? $usrDetails['shop_id'] : 1,
                'user_id' => $user_id,
                "order_from"=>'POS',
                'payment_status'=>$payment_status,
                'delivery_status'=>$delivery_status,
                'shipping_cost'=>$total_shipping_cost,
                "location_id" => isset($list->location_id) ? $list->location_id : 1,
            ];
            $ord_id = $OrdersModels->insert($ordInst);

            foreach ($products as $list) {
                $ordItem = [
                    "order_id" => $ord_id,
                    "product_variation_id" => $list->product_variation_id,
                    "qty" => $list->qty,
                    "location_id" => isset($list->location_id) ? $list->location_id : 1,
                    "unit_price" => $list->unit_price,
                    "total_price" => $list->total_price,
                    "unit_id"=>$list->unit_id,
                    "discount_percentage"=>isset($list->discount_percentage) ? $list->discount_percentage : 0,
                    "tax_id"=>isset($list->tax_id) ? $list->tax_id : null,
                    "tax_percentage"=>isset($list->tax_percentage) ? $list->tax_percentage : 0,
                ];
                $OrdersItemModels->insert($ordItem);
    
            }

            foreach ($products as $list) {
                $productModel = new ProductsModel();
                $productModel->where("id", $list->product_id);
                $product = $productModel->first();
                $quantity = $product['stock_qty'] - $list->qty;
                $productModel->set(['stock_qty' => $quantity])->where('id', $list->product_id)->update();
            }

            $resquest_data=[
                'order_code'=>$ord_id,
                'customer_name'=>$json->user_name ?? null,
                'customer_card_number'=>$json->card_number ?? null,
                'card_exp_date'=>$json->ex_mo ?? null,
                'amount'=>$json->costomer_amount ?? 0,
                'balance_amount'=>$json->balance ?? 0
            ];
            $paymentgatwayModels = new PaymentgatwayModel();
            $paymentGatwayId = $paymentgatwayModels ->insert($resquest_data);

        
            $message = [

                'status' => 200,
                'message' => 'Order Placed Successfully'
            ];
            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);

        } else {
            $message = [
                'status' => 400,
                'message' => 'Product Be Must'
            ];
            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
        }


    }
}


