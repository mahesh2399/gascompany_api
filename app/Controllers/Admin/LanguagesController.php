<?php 

namespace App\Controllers\Admin;

use App\Models\Admin\LanguagesModel;
use CodeIgniter\RESTful\ResourceController;

class LanguagesController extends ResourceController
{

    public function __construct()
    {
        $this->model = new LanguagesModel();
    }

    public function languageCreate()
    {
        try {
            $language = new LanguagesModel();
            $json = $this->request->getJSON();
            $name = $json->name;
            $code = $json->code; 
            $is_active = $json->is_active;

    
            $insert = array(
                "name" => $name,
                "code" => $code,
                "is_active" => $is_active

            );
            $language->insert($insert);
            $insertId = $language->getInsertID();
            $message = [
                'message' => 'Language created successfully!',
                'status' => 200
            ];
    
            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $insertId]);
        } catch (\Exception $e) {
            return $this->failServerError('Server Error');
        }
    }
//*************************************
    public function languageGetById($id)
    {
        try {
            $language = new LanguagesModel();
            $result = $language->find($id);

            if ($result) {
                $response = [
                    'data' => $result,
                    'message_object' => [
                        'message' => 'Language found successfully',
                        'statusCode' => 200
                    ]
                ];
                return $this->respond($response);
            } else {
                return $this->failNotFound('Language not found');
            }
        } catch (\Exception $e) {
            return $this->failServerError('Server Error');
        }
    }

    public function languageGetAll()
    {
        try {
            $language = new LanguagesModel();
            $result = $language->findAll();
    
            $response = [
                'data' => $result,
                'message_object' => [
                    'message' => 'All Languages retrieved successfully',
                    'statusCode' => 200
                ]
            ];
    
            return $this->respondCreated($response);
        } catch (\Exception $e) {
            return $this->failServerError('Server Error');
        }
    }
//*************************************
    public function languageUpdate($id)
{
    try {
        $language = new LanguagesModel();

        // Check if language exists
        $existingLanguage = $language->find($id);
        if (!$existingLanguage) {
            return $this->failNotFound('Language not found');
        }

        $json = $this->request->getJSON();
        $name = $json->name;
        $code = $json->code;
        $is_active = $json->is_active;

        $data = [
            "name" => $name,
            "code" => $code,
            "is_active" => $is_active
        ];

        $updated = $language->update($id, $data);

        if ($updated !== false) {
            if ($updated > 0) {
                $response = [
                    'id' => $id,
                    'message_object' => [
                        'message' => 'Language updated successfully',
                        'statusCode' => 200
                    ]
                ];

                return $this->respond($response);
            } else {
                return $this->failServerError('Failed to update language');
            }
        } else {
            return $this->failServerError('Failed to update language');
        }
    } catch (\Exception $e) {
        return $this->failServerError('Server Error');
    }
}

    //*************************************
public function languageDeleteById($id)
{
    try {
        $language = new LanguagesModel();

        $existingLanguage = $language->find($id);
        if (!$existingLanguage) {
            return $this->failNotFound('Language not found');
        }

        $deleted = $language->delete($id);

        if ($deleted) {
            $response = [
                'id' => $id,
                'message_object' => [
                    'message' => 'Language deleted successfully',
                    'statusCode' => 200
                ]
            ];

            return $this->respondDeleted($response);
        } else {
            return $this->failServerError('Failed to delete language');
        }
    } catch (\Exception $e) {
        return $this->failServerError('Server Error');
    }
}
//*************************************
public function languageUpdateStatus($id)
{
    try {
        $language = new LanguagesModel();

        $existingLanguage = $language->find($id);
        if (!$existingLanguage) {
            return $this->failNotFound('Language status not found');
        }

        $json = $this->request->getJSON();
        $is_active = $json->is_active;

        $data = [
            "is_active" => $is_active
        ];

        $updated = $language->update($id, $data);

        if ($updated) {
            $response = [
                'id' => $id,
                'message_object' => [
                    'message' => 'Language status updated successfully',
                    'statusCode' => 200
                ]
            ];

            return $this->respond($response);
        } else {
            return $this->failServerError('Failed to update language status');
        }
    } catch (\Exception $e) {
        return $this->failServerError('Server Error');
    }
}
    
}
