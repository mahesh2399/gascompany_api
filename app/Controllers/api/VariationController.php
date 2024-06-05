<?php


namespace App\Controllers\Api;

use App\Models\Admin\VariationModel;
use CodeIgniter\RESTful\ResourceController;

class VariationController extends ResourceController
{

    public function __construct()
    {
        $this->model = new VariationModel();
    }



    public function Variationcreate()
    {
        try {
            $order = new VariationModel();
            $json = $this->request->getJSON();
            $name = $json->name;

            $insert = array(
                "name" => $name,
                "is_active" => 1,
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

    public function variationgetById($id)
    {
        try {
            $order = new VariationModel();

            $result = $order->find($id);

            if ($result) {
                $response = [
                    'data' => $result,
                    'message_object' => [
                        'message' => 'Variation Get successfully',
                        'statusCode' => 200
                    ]
                ];

                return $this->respond($response);
            } else {
                return $this->failNotFound('Variation not found');
            }
        } catch (\Exception $e) {
            return $this->failServerError('Server Error');
        }
    }

    //********************************************************************************
    public function variationgetAll()
    {
        try {
            $order = new VariationModel();

            $query = "SELECT * FROM variations";
            $result = $order->query($query)->getResult();

            $response = [
                'data' => $result,
                'message_object' => [
                    'message' => 'All Variations Get successfully',
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

            return $this->failServerError('server Error');
        }
    }
    //********************************************************************************

    public function variationupdate($id)
    {
        try {
            $order = new VariationModel();
            $json = $this->request->getJSON();
            $name = $json->name;
            $is_active = $json->is_active;


            $data = [
                "name" => $name,
                "is_active" => $is_active
            ];

            $order->update($id, $data);

            $response = [
                'id' => $id,
                'messageobject' => [
                    'message' => 'Variations updated successfully',
                    'status' => 200
                ]
            ];

            return $this->respond($response);
        } catch (\Exception $e) {
            return $this->failServerError('Server Error');
        }
    }




    //********************************************************************************

    public function variationdeleteById($id)
    {
        try {
            $order = new VariationModel();
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

