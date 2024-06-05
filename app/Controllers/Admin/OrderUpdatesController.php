<?php 


namespace App\Controllers\Admin;

use App\Models\Admin\Order_Updates_Model;
use CodeIgniter\RESTful\ResourceController;

class OrderUpdatesController extends ResourceController
{
    public function __construct()
    {
        $this->model = new Order_Updates_Model();
    }

    public function getByOrderId()
    {
        $model = new Order_Updates_Model();
        $orderId = (float) $this->request->getGet('orderid');
        $orderUpdates = $model->where('order_id', $orderId)->orderBy('id', 'DESC')   ->findAll();

        if ($orderUpdates === null) {
            return $this->failNotFound('No order updates found for the given order ID.');
        }

        $response = [
            'status' => 'success',
            'message' => 'Order updates retrieved successfully.',
            'data' => $orderUpdates
        ];

        return $this->respond($orderUpdates);
    }
}
