<?php 


namespace App\Controllers\Admin;

use App\Models\Admin\OrderStatusModel;
use App\Models\Admin\OrdersModel;
use App\Models\Admin\Order_Groups_Model;
use CodeIgniter\RESTful\ResourceController;

class OrderStatusController extends ResourceController
{

    public function __construct()
    {
        $this->model = new OrderStatusModel();
    }

    public function create()
    {
        try {
            $order = new OrderStatusModel();
            $json = $this->request->getJSON();
            $name = $json->name;
            $sequence = $json->sequence;
            // $status = $json->status;
    
            $insert = array(
                "name"=>$name,
                "sequence"=>$sequence,
                "status"=>1,
            );
            $order->insert($insert);
            $insertId = $order->getInsertID();
            $message = [
                'message' => 'Order Status Created Successfully!',
                'status' => 200
            ];
    
            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $insertId]);
        } catch (\Exception $e) {
            $error_message = [
                'messageobject' => [
                    'message' => 'Server Error',
                    'status' => 500
                ]
            ];
            return $this->failServerError('Server Error');
        }
    }
    
    //********************************************************************************

    public function getById($id)
    {
        try {
            $order = new OrderStatusModel();
            $result = $order->find($id);
    
            if (!$result) {
                return $this->failNotFound('Order not found');
            }
    
            $response = [
                'data' =>  $result,
                'messageobject' => [
                    'message' => 'Order Status Retrieved Successfully',
                    'status' => 200
                ]
            ];
    
            return $this->respond($response);
        } catch (\Exception $e) {
            return $this->failServerError('Server Error');
        }
    }
    
    
//********************************************************************************
    public function getAll()
    {
        try {
            $order = new OrderStatusModel();
            
            $query = "SELECT * FROM order_status";
            $result = $order->query($query)->getResult();
    
            $response = [
                'data' => $result,
                'messageobject' => [
                    'message' => 'Orders Status Get Successfully',
                    'status' => 200
                ]
            ];
    
            return $this->respondCreated($response);
        } catch (\Exception $e) {
            $error_message = [
                'messageobject' => [
                    'message' => 'Server Error',
                    'status' => 500
                ]
            ];
    
            return $this->failServerError('Server Error');
        }
    }
    //********************************************************************************

    public function updateStatus($id){
        try {
            $order = new OrderStatusModel();
            $json = $this->request->getJSON();
            $name = $json->name;
            $sequence = $json->sequence;
            $status = $json->status;
    
            // Check if the ID exists in the database
            $existingOrder = $order->find($id);
            if (!$existingOrder) {
                return $this->failNotFound('Order not found');
            }
    
            $data = [
                'name' => $name,
                'sequence' => $sequence,    
                'status' => $status,
            ];
    
            $order->update($id, $data);
    
            $response = [
                'id' => $id,
                'messageobject' => [
                    'message' => 'Status Updated Successfully',
                    'status' => 200
                ]
            ];
    
            return $this->respond($response);
        } catch (\Exception $e) {
            return $this->failServerError('Server Error');
        }
    }
    
//********************************************************************************
public function updateStatusStatus($id){
    try {
        $order = new OrderStatusModel();
        $json = $this->request->getJSON();
        $status = $json->status;

        // Check if the record exists
        $existingOrder = $order->find($id);
        if (!$existingOrder) {
            return $this->failNotFound('Order not found');
        }

        // Update the status
        $data = [
            'status' => $status,
        ];
        $order->update($id, $data);

        $response = [
            'id' => $id,
            'messageobject' => [
                'message' => 'Status Updated Successfully',
                'status' => 200
            ]
        ];

        return $this->respond($response);
    } catch (\Exception $e) {
        return $this->failServerError('Server Error');
    }
}


    
//********************************************************************************
public function deleteById($id){
    try {
        $order = new OrderStatusModel();
        
        // Check if the ID exists in the database
        $existingOrder = $order->find($id);
        if (!$existingOrder) {
            return $this->failNotFound('Order not found');
        }

        $deleted = $order->delete($id);

        if ($deleted) {
            $response = [
                'id' => $id,
                'messageobject' => [
                    'message' => 'Order Status Deleted Successfully',
                    'status' => 200
                ]
            ];
    
            return $this->respondCreated($response);
        } else {
            return $this->failServerError('Failed to delete order status');
        }
    } catch (\Exception $e) {
        return $this->failServerError('Server Error');
    }
}


//********************************************************************************

public function orderStatusUpdate(){
   
    $order = new OrdersModel();
    $ordergroup = new Order_Groups_Model();
    $json = $this->request->getJSON();
    $id = $json->id;
    $status = isset($json->status) ? $json->status:null;
     
    if ($status != null) {
        $getID = $order->where('id', $id)->first();

        $datamodel = [
            'payment_status' => $status,
        ];

        $order->set($datamodel)->where("id", $id)->update();
        $ordergroup->set($datamodel)->where("id", $getID['order_group_id'])->update();
        
        $response = [
            'messageobject' => [
                'message' => 'Status Updated Successfully',
                'status' => 200
            ]
        ];

        return $this->respond($response);
    
    } else {
        $response = [
            'id' => $id,
            'messageobject' => [
                'message' => 'Status Is Missing',
                'status' => 201
            ]
        ];
    
        return $this->respond($response);
    }
}


public function changesOrderStatus()
{
    $orderModel = new OrdersModel();
    $requestMethod = $this->request->getMethod();
    if ($requestMethod != 'post') {
    } else {
        $headers = $this->request->getHeaders();
        if (!isset($headers['AUTHORIZATION'])) {
            $json = $this->request->getJSON();
            if (isset($json->order_id)) {

                $order_found = $orderModel->where('id', $json->order_id)->first();

                $var = $orderModel->set(['delivery_status' => $json->status])->where(['id' => $json->order_id])->update();

                $message = [
                    'message' => 'Status Updated Successfully',
                    'status' => 200
                ];
                return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
            } else {
                $message = [
                    'message' => 'User Not Found',
                    'status' => 400
                ];
                return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
            }
        } else {
            $message = [
                'message' => 'Unauthorized User',
                'status' => 401
            ];
            return $this->response->setStatusCode(401)->setJSON(['messageobject' => $message]);
        }
    }
}

public function changesPaymentMethod()
{
    $orderModel = new Order_Groups_Model();
    $requestMethod = $this->request->getMethod();
    if ($requestMethod != 'post') {
        // return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request method']);
    } else {
        $headers = $this->request->getHeaders();
        if (!isset($headers['AUTHORIZATION'])) {
            $json = $this->request->getJSON();
            if (isset($json->order_id)) {

                $order_found = $orderModel->where('id', $json->order_id)->first();

                $var = $orderModel->set(['payment_method' => $json->status])->where(['id' => $json->order_id])->update();

                $message = [
                    'message' => 'Payment Method Updated Successfully',
                    'status' => 200
                ];
                return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
            } else {
                $message = [
                    'message' => 'User Not Found',
                    'status' => 400
                ];
                return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
            }
        } else {
            $message = [
                'message' => 'Unauthorized User',
                'status' => 401
            ];
            return $this->response->setStatusCode(401)->setJSON(['messageobject' => $message]);
        }
    }
}



public function changesPaymentStatus()
{
    $orderModel = new OrdersModel();
    $requestMethod = $this->request->getMethod();
    if ($requestMethod != 'post') {
        // return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request method']);
    } else {
        $headers = $this->request->getHeaders();
        if (!isset($headers['AUTHORIZATION'])) {
            $json = $this->request->getJSON();
            if (isset($json->order_id)) {

                $order_found = $orderModel->where('id', $json->order_id)->first();

                $var = $orderModel->set(['payment_status' => $json->status])->where(['id' => $json->order_id])->update();

                $message = [
                    'message' => 'payment Status Updated Successfully',
                    'status' => 200
                ];
                return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
            } else {
                $message = [
                    'message' => 'User Not Found',
                    'status' => 400
                ];
                return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
            }
        } else {
            $message = [
                'message' => 'Unauthorized User',
                'status' => 401
            ];
            return $this->response->setStatusCode(401)->setJSON(['messageobject' => $message]);
        }
    }
}

}

  

