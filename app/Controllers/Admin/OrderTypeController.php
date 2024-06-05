<?php 


namespace App\Controllers\Admin;

use App\Models\Admin\OrderTypeModel;
use CodeIgniter\RESTful\ResourceController;

class OrderTypeController extends ResourceController
{

    public function __construct()
    {
        $this->model = new OrderTypeModel();
    }
    public function create()
    {
        try {
            $order = new OrderTypeModel();
            $json = $this->request->getJSON();
            $name = $json->name;
            $description = $json->description;
            // $status = $json->status;
    
            $insert = array(
                "name"=>$name,
                "description"=>$description,
                "status"=>1,
            );
            $order->insert($insert);
            $insertId = $order->getInsertID();
            $message = [
                'message' => 'Record Created Successfully!',
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
    public function getById()
    {
        try {
            $order = new OrderTypeModel();
            $url = $this->request->getUri();
            $id = $url->getSegment(3);
    
            $result = $order->find($id);
    
            if (!$result) {
                return $this->failNotFound('Order not found');
            }
    
            $response = [
                'data' => $result,
                'messageobject' => [
                    'message' => 'Order Get Successfully',
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
            $order = new OrderTypeModel();
            
            $query = "SELECT * FROM order_type";
            $result = $order->query($query)->getResult();
    
            $response = [
                'data' => $result,
                'messageobject' => [
                    'message' => 'Orders Get Successfully',
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

    
public function updateStatus($id)
{
    try {
        $order = new OrderTypeModel();
        $json = $this->request->getJSON();
        $name = $json->name;
        $description = $json->description;
        $status = $json->status;

        $existingOrder = $order->find($id);
        if (!$existingOrder) {
            return $this->failNotFound('Order not found');
        }

        $data = [
            'name' => $name,
            'description' => $description,
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

//************************************
public function updateStatusStatus($id)
{
    try {
        $order = new OrderTypeModel();
        $json = $this->request->getJSON();
        $status = $json->status;

        $existingOrder = $order->find($id);
        if (!$existingOrder) {
            return $this->failNotFound('Order not found');
        }

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

public function deleteById($id)
{
    try {
        $order = new OrderTypeModel();
        $existingOrder = $order->find($id);

        if (!$existingOrder) {
            return $this->failNotFound('Order not found');
        }

        $deleted = $order->delete($id);

        if ($deleted) {
            $response = [
                'id' => $id,
                'messageobject' => [
                    'message' => 'Order Deleted Successfully',
                    'status' => 200
                ]
            ];

            return $this->respondCreated($response);
        } else {
            return $this->failServerError('Server Error');
        }
    } catch (\Exception $e) {
        return $this->failServerError('Server Error');
    }
}


}

