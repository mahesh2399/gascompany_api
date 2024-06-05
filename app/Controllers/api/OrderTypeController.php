<?php


namespace App\Controllers\Api;

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
            $insert = array(
                "name" => $name,
                "description" => $description,
                "status" => 1,
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
            $order = new OrderTypeModel();
            $url = $this->request->getUri();
            $id = $url->getSegment(3);

            $query = "SELECT * FROM order_type WHERE id ='$id'";
            $result = $order->query($query)->getRow();
            $response = [
                'data' => $result,
                'message_object' => [
                    'message' => 'Order Get successfully',
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
            $order = new OrderTypeModel();

            $query = "SELECT * FROM order_type";
            $result = $order->query($query)->getResult();

            $response = [
                'data' => $result,
                'message_object' => [
                    'message' => 'Orders Get successfully',
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

    public function updateStatus($id)
    {
        try {
            $order = new OrderTypeModel();
            $json = $this->request->getJSON();
            $name = $json->name;
            $description = $json->description;
            $status = $json->status;

            $data = [
                'name' => $name,
                'description' => $description,
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

    public function deleteById($id)
    {
        try {
            $order = new OrderTypeModel();
            $deleted = $order->delete($id);

            if ($deleted) {
                $response = [
                    'id' => $id,
                    'message_object' => [
                        'message' => 'Order deleted successfully',
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

