<?php 


namespace App\Controllers\Admin;

use App\Models\Admin\ContactusModel;
use CodeIgniter\RESTful\ResourceController;

class ContactusController extends ResourceController
{

    public function __construct()
    {
        $this->model = new ContactusModel();
    }


    public function createcontactus()
    {
        try {
            $order = new ContactusModel();
            $jsonString = $this->request->getJSON();

    
            $name = $jsonString->name;
            $email = $jsonString->email;
            $phone = $jsonString->phone;
            $supportfor = $jsonString->support_for;
            $messageText = $jsonString->message;
            // $isseen = $jsonString->isseen;
    
            $insert = array(
                "name" => $name,
                "email" => $email,
                "phone" => $phone,
                "support_for" => $supportfor,
                "message" => $messageText,
                "is_seen" => 0
            );
       

           
    
            $order->insert($insert);
            $insertId = $order->getInsertID();

             
            
            if ($insertId) {
                $successMessage = [
                    'message' => 'Successfly Created!',
                    'status' => 200
                ];
                return $this->response->setStatusCode(200)->setJSON(['messageobject' => $successMessage, 'data' => $insertId]);
            } else {
                $errorMessage = [
                    'message' => 'Failed to insert data.',
                    'status' => 500
                ];
                return $this->response->setStatusCode(500)->setJSON(['messageobject' => $errorMessage]);
            }
        } catch (\Exception $e) {
            $errorMessage = [
                'message' => 'Server Error',
                'status' => 500
            ];
            return $this->response->setStatusCode(500)->setJSON(['messageobject' => $errorMessage]);
        }
    }
        




    public function getContactusById($id)
{
    try {
        $order = new ContactusModel();
        $contactus = $order->find($id);
        if (!$contactus) {
            return $this->failNotFound('Contactus not found');
        }

        $response = [
            'data' => $contactus,
            'messageobject' => [
                'message' => 'Contactus found',
                'status' => 200
            ]
        ];

        return $this->respond($response);
    } catch (\Exception $e) {
        return $this->failServerError('Server Error');
    }
}

    

    public function getAll()
    {
        try {
            $order = new ContactusModel();
            
            $query = "SELECT * FROM contact_us_messages";
            $result = $order->query($query)->getResult();
    
            $response = [
                'data' => $result,
                'messageobject' => [
                    'message' => 'Contactus Get Successfully',
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


    public function updatecontactus($id)
    {
        try {
            $order = new ContactusModel();
            $json = $this->request->getJSON();
            $name = $json->name;
            $email = $json->email;
            $phone = $json->phone;
            $supportfor = $json->support_for;
            $message = $json->message;
            // $isseen = $json->is_seen;
    

            $existingRecord = $order->find($id);
            if (!$existingRecord) {
                return $this->failNotFound('Contactus not found');
            }
    
            $data = [
                "name" => $name,
                "email" => $email,
                "phone" => $phone,
                "support_for" => $supportfor,
                "message" => $message,
                // "is_seen" => 0,
            ];
    
            $order->update($id, $data);
    
            $response = [
                'id' => $id,
                'messageobject' => [
                    'message' => 'Contactus Updated Successfully',
                    'status' => 200
                ]
            ];
    
            return $this->respond($response);
        } catch (\Exception $e) {
            return $this->failServerError('Server Error');
        }
    }
    
    
    
    public function updateStatusStatus($id){
        try {
            $order = new ContactusModel();
            $json = $this->request->getJSON();
            $isseen = $json->is_seen;
    
            // Check if the record exists
            $existingOrder = $order->find($id);
            if (!$existingOrder) {
                return $this->failNotFound('Order not found');
            }
    
            // Update the is_seen
            $data = [
                "is_seen" => $isseen,
            ];
            $order->update($id, $data);
    
            $response = [
                'id' => $id,
                'message_object' => [
                    'message' => 'Status Updated Successfully',
                    'status' => 200
                ]
            ];
    
            return $this->respond($response);
        } catch (\Exception $e) {
            return $this->failServerError('Server Error');
        }
    }

public function contactusdeleteById($id)
{
    try {
        $order = new ContactusModel();


        $existingRecord = $order->find($id);
        if (!$existingRecord) {
            return $this->failNotFound('Contactus not found');
        }

        $deleted = $order->delete($id);

        if ($deleted) {
            $response = [
                'id' => $id,
                'messageobject' => [
                    'message' => 'Contactus Deleted Successfully',
                    'status' => 200
                ]
            ];

            return $this->respondCreated($response);
        } else {
            return $this->failServerError('Failed to delete contactus');
        }
    } catch (\Exception $e) {
        return $this->failServerError('Server Error');
    }
}

}

