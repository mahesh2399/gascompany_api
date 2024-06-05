<?php 


namespace App\Controllers\Admin;

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
            $result5 = $order->where('name',$name);
            $check = $result5->get()->getNumRows();
            if($check<1){
                $insert = array(
                    "name"=>$name,
                    "is_active"=>1,
                );
                $order->insert($insert);
                $insertId = $order->getInsertID();
                $message = [
                    'message' => 'Success!',
                    'status' => 200
                ];
        
                return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $insertId]);
            }else{
                $data = "Variation name already exists!";
    
                $message = [
                    'message' => "Variation Name Already Exists!",
                    'status' => 400
                ];
                return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message, 'data' => $data]);
            }
          
        } catch (\Exception $e) {
            $error_message = [
                'messageobject' => [
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
                'messageobject' => [
                    'message' => 'Variation Get successfully',
                    'status' => 200
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
                'messageobject' => [
                    'message' => 'All Variations Get successfully',
                    'status' => 200
                ]
            ];
    
            return $this->respondCreated($response);
        } catch (\Exception $e) {
            $error_message = [
                'messageobject' => [
                    'message' => 'Server Error',
                    'statusCode' => 500
                ]
            ];
    
            return $this->failServerError('Server Error');
        }
    }
    //********************************************************************************

    public function variationupdate($id){
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
                    'message' => 'Variations Updated Successfully',
                    'status' => 200
                ]
            ];
    
            return $this->respond($response);
        } catch (\Exception $e) {
            return $this->failServerError('Server Error');
        }
    }
    
    
//********************************************************************************

public function variationdeleteById($id){
    try {
        $order = new VariationModel();
        $deleted = $order->delete($id);

        if ($deleted) {
            $response = [
                'id' => $id,
                'messageobject' => [
                    'message' => 'Variation Deleted Successfully',
                    'status' => 200
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

    public function variationupdateStatus($id)
    {
        try {
            $variationModel = new VariationModel();

            // Find the variation by ID
            $existingVariation = $variationModel->find($id);
            if (!$existingVariation) {
                return $this->failNotFound('Variation not found');
            }

            // Get JSON request data
            $json = $this->request->getJSON();
            $isActive = isset($json->is_active) ? $json->is_active : null;

            // Check if is_active is provided in the request
            if ($isActive === null) {
                return $this->failValidationErrors('is_active field is required');
            }

            // Validate the is_active value (should be 0 or 1)
            if (!in_array($isActive, [0, 1], true)) {
                return $this->failValidationErrors('is_active must be either 0 or 1');
            }

            // Prepare data for update
            $data = [
                'is_active' => $isActive
            ];

            // Update the variation
            $updated = $variationModel->update($id, $data);

            if ($updated) {
                $response = [
                    'id' => $id,
                    'message_object' => [
                        'message' => 'Variation Status Updated Successfully',
                        'status' => 200
                    ]
                ];

                return $this->respond($response);
            } else {
                return $this->failServerError('Failed to update variation status');
            }
        } catch (\Exception $e) {
            return $this->failServerError('Server error');
        }
    }
}