<?php

namespace App\Controllers\Admin;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Admin\ScheduleDeliveryTimeListModel;

class ScheduleDeliveryTimeListController extends ResourceController
{
    protected $model;

    public function __construct()
    {
        $this->model = new ScheduleDeliveryTimeListModel();
    }

    public function createScheduleDelivery()
    {
        try {
            $json = $this->request->getJSON();
            $timeline = $json->timeline;
            $sorting_order = $json->sorting_order;

            $insert = [
                "timeline" => $timeline,
                "sorting_order" => $sorting_order,
            ];
            $this->model->insert($insert);
            $insertId = $this->model->getInsertID();
            $message = [
                'message' => 'ScheduleDelivery created successfully!',
                'status' => 200
            ];

            return $this->respondCreated(['messageobject' => $message, 'data' => $insertId]);
        } catch (\Exception $e) {
            return $this->failServerError('Server Error');
        }
    }
    public function ScheduleDeliverygetById($id)
    {
        try {
            $result = $this->model->find($id);
    
            if (!$result) {
                return $this->failNotFound('Schedule delivery not found');
            }
    

            return $this->respond([
                'message' => 'Schedule delivery retrieved successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return $this->failServerError('Server Error');
        }
    }
    
    public function getAllScheduleDelivery()
    {
        try {
            $result = $this->model->findAll();
    

            $message = 'Data retrieved successfully';
    

            $response = [
                'message' => $message,
                'data' => $result
            ];
    

            return $this->respond($response);
        } catch (\Exception $e) {
            return $this->failServerError('Server Error');
        }
    }
    public function updateScheduleDeliveryById()
    {
        try {
            $json = $this->request->getJSON();
            $id = $json->id;
            $timeline = $json->timeline;
            $sorting_order = $json->sorting_order;
    

            $existingRecord = $this->model->find($id);
            if (!$existingRecord) {
                return $this->failNotFound('Schedule delivery not found');
            }
    

            $data = [
                'timeline' => $timeline,
                'sorting_order' => $sorting_order,
            ];
    

            $updated = $this->model->update($id, $data);
    
            if ($updated) {
                return $this->respondUpdated([
                    'message' => 'Schedule delivery updated successfully',
                    'data' => [
                        'id' => $id,
                        'timeline' => $timeline,
                        'sorting_order' => $sorting_order
                    ]
                ]);
            } else {
                return $this->failServerError('Failed to update schedule delivery');
            }
        } catch (\Exception $e) {
            return $this->failServerError('Server Error');
        }
    }
    
    public function deleteScheduleDeliveryById()
    {
        try {
            $json = $this->request->getJSON();
            $id = $json->id;
    
            $existingRecord = $this->model->find($id);
            if (!$existingRecord) {
                return $this->failNotFound('Schedule delivery not found');
            }
    
            $deleted = $this->model->delete($id, true); 
    
            if ($deleted) {
                return $this->respondDeleted(['id' => $id, 'message' => 'Schedule delivery deleted successfully']);
            } else {
                return $this->failServerError('Failed to delete schedule delivery');
            }
        } catch (\Exception $e) {
            return $this->failServerError('Server Error');
        }
    }
    
}
