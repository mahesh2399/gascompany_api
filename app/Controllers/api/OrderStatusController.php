<?php 


namespace App\Controllers\Api;

use App\Models\Admin\OrderStatusModel;
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
    
            $insert = array(
                "name"=>$name,
                "sequence"=>$sequence,
                "status"=>1,
            );
            $order->insert($insert);
            $insertId = $order->getInsertID();
            $message = [
                'message' => 'Success!',
                'status' => 200
            ];
    
            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $insertId]);
        } catch (\Exception $e) {
            $error_message = [
                'message_object' => [
                    'message' => 'Server Error',
                    'statusCode' => 500
                ]
            ];
            return $this->failServerError('Server Error');
        }
    }
    
    //********************************************************************************

    public function getById()
    {
        try {
            $order = new OrderStatusModel();
            $url = $this->request->getUri();
            $id = $url->getSegment(3);
            $json = $this->request->getJSON();
            $Id = $json->id;
           
            $result = $order->where("id", $Id);
            $data['getData'] = $result->findAll();
          $response = [
              'data' =>  $data['getData'],
              'message_object' => [
                  'message' => 'Order status Get successfully',
                  'statusCode' => 200
              ]
          ];
            return $this->respond($response);
        } catch (\Exception $e) {
            $error_message = [
                'message_object' => [
                    'message' => 'Server Error',
                    'statusCode' => 500
                ]
            ];
    
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
                'message_object' => [
                    'message' => 'Orders status Get successfully',
                    'statusCode' => 200
                ]
            ];
    
            return $this->respondCreated($response);
        } catch (\Exception $e) {
            $error_message = [
                'message_object' => [
                    'message' => 'Server Error',
                    'statusCode' => 500
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
    
            $data = [
                'name' => $name,
                'sequence' => $sequence,    
                'status' => $status,
            ];
    
            $order->update($id, $data);
    
            $response = [
                'id' => $id,
                'message_object' => [
                    'message' => 'Status updated successfully',
                    'statusCode' => 200
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
        $deleted = $order->delete($id);

        if ($deleted) {
            $response = [
                'id' => $id,
                'message_object' => [
                    'message' => 'Order status deleted successfully',
                    'statusCode' => 200
                ]
            ];
    
            return $this->respondCreated($response);
        } else {
            return $this->failNotFound('Order not found');
        }
    } catch (\Exception $e) {
        return $this->failServerError('Server Error');
    }
}


}

