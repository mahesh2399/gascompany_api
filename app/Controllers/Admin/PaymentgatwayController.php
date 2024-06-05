<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Models\Admin\PaymentgatwayModel;
use App\Models\Admin\Order_Groups_Model;
use App\Models\Admin\OrdersModel;

class PaymentgatwayController extends BaseController
{
    public function paymentGatway()
    {
     try{
        $json = $this->request->getJSON();
        $resquest_data=[
            'order_code'=>$json->order_id ?? 0,
            'customer_name'=>$json->user_name ?? null,
            'customer_card_number'=>$json->card_number ?? null,
            'card_exp_date'=>$json->ex_mo ?? null,
            'amount'=>$json->costomer_amount ?? 0,
            'balance_amount'=>$json->balance ?? 0
        ];
        $paymentgatwayModels = new PaymentgatwayModel();
      $paymentDetails=  $paymentgatwayModels->where('order_code',$json->order_id)->first();
      if ($paymentDetails) {
         $paymentgatwayModels ->update($paymentDetails['id'],$resquest_data);
    
      } else {
        # code...
       $paymentgatwayModels ->insert($resquest_data);

      }
        // if($paymentGatwayId){
            $orderModels = new OrdersModel();
$orderData=$orderModels->where('id',$json->order_id)->first();
            $ordergroup = new Order_Groups_Model();
            $order_id =  $json->order_id ?? 0;
            $data =[
                'payment_method'=>$json->payment_method,
                'payment_status'=>'Paid'
            ];
            $orders = $ordergroup->update($orderData['order_group_id'], $data);

            if($orders){
                $ordergroup = new OrdersModel();
                $order_id =  $json->order_id ?? 0;
                $data =[
                    'payment_status'=>'Paid'
                ];
                $orders = $ordergroup->update($order_id, $data);
                $data = "paymant updated successfully";
                $message = [
                    'message' => 'payment updated successfully!',
                    'status' => 200
                ];
                return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);

            }else{
                $data = "Something went wrong!";
                $message = [
                    'message' => 'Something went wrong!',
                    'status' => 400
                ];
                return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message, 'data' => $data]);
            }
        // }
        // else{
        //     $ordergroup = new Order_Groups_Model();
        //     $order_id =  $json->order_id ?? 0;
        //     $data =[
        //         'payment_status'=>'Unpaid'
        //     ];
        //     $orders = $ordergroup->update($order_id, $data);
        //     if($orders){
        //         $ordergroup = new OrdersModel();
        //         $order_id =  $json->order_id ?? 0;
        //         $data =[
        //             'payment_status'=>'Unpaid'
        //         ];
        //         $orders = $ordergroup->update($order_id, $data);
        //         $data = "Something went wrong!";
        //         $message = [
        //             'message' => 'Something went wrong!',
        //             'status' => 400
        //         ];
        //         return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message, 'data' => $data]);
        //     }else{
        //         $data = "Something went wrong!";
        //         $message = [
        //             'message' => 'Something went wrong!',
        //             'status' => 400
        //         ];
        //         return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message, 'data' => $data]);
        //     }
        // }
    } catch (\Exception $e) {
        print_r($e);
        $message = [
            'message' => 'Something when wrong please try again',
            'status' => 400
        ];
        return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
    }
    }   //    =========== PAYMENTGATWAY ==================
}
