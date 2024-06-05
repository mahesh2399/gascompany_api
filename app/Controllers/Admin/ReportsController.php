<?php


namespace App\Controllers\Admin;

use App\Models\Admin\VariationModel;
use App\Models\Admin\Order_Items_Model;
use App\Models\Admin\OrdersModel;
use App\Models\Admin\CategoriesModel;


use CodeIgniter\RESTful\ResourceController;

class ReportsController extends ResourceController
{
    public $OrdersModel;

    public function __construct()
    {
        $this->model = new VariationModel();
        $this->OrdersModel = new OrdersModel(); 

    }


    // public function salesReport()
    // {
    //     $order = new Order_Items_Model();
    //     $data = $order->salesorder();

    //     if (count($data) == 0) {
    //         $message = [
    //             'message' => 'NO DATA FOUND',
    //             'status' => 400
    //         ];

    //         return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
    //     } else {

    //         $message = [
    //             'message' => 'Sales data retrieved successfully!',
    //             'status' => 200
    //         ];

    //         return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    //     }



    // }





    public function salesReport()
    {
        $order = new Order_Items_Model();
        $data = $order->salesorder();
    
        if (count($data) == 0) {
            $message = [
                'message' => 'NO DATA FOUND',
                'status' => 400
            ];
    
            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
        } else {
            foreach ($data as &$product) {
                $product['created_at'] = date("Y-m-d", strtotime($product['created_at']));
            }
    
            $message = [
                'message' => 'Sales data retrieved successfully!',
                'status' => 200
            ];
    
            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
        }
    }

    public function DeliveryReport()
    {
        $order = new Order_Items_Model();
        $data = $order->deleveryreport();

        if (count($data) == 0) {
            $message = [
                'message' => 'NO DATA FOUND',
                'status' => 400
            ];

            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
        } else {

            $message = [
                'message' => 'Delivery data retrieved successfully!',
                'status' => 200
            ];

            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
        }


    }




    public function CategorySalesReport()
    {
        $catsare = new CategoriesModel();
        $data = $catsare->getAllCategories();
    
        if (count($data) == 0) {
            $message = [
                'message' => 'NO DATA FOUND',
                'status' => 400
            ];
    
            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
        } else {
            
            usort($data, function($a, $b) {
                return $b['total_sale_count'] - $a['total_sale_count'];
            });
    
            $message = [
                'message' => 'Sales data retrieved successfully!',
                'status' => 200
            ];
    
            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
        }
    }
    

    public function carddetailsforsales()
    {
        try {
            $productModel = new Order_Items_Model();
    
            $data['todaysales'] = $productModel->todaysales();
            $data['lat7dayssales'] = $productModel->lat7dayssales();
            $data['lat30dayssales'] = $productModel->lat30dayssales();
            $data['thisyearsales'] = $productModel->thisyearsales();
    
            $response = [];
            foreach ($data as $key => $value) {
                if (!empty($value)) {
                    $response[] = [
                        'product_name' => $value[0]['product_name'],
                        $key => $value[0]['total_sales']
                    ];
                } else {
                    $response[] = [
                        'product_name' => 'Not Available',
                        $key => 0
                    ];
                }
            }
    
            $message = [
                'message' => 'Sales data successfully retrieved'
            ];
    
            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $response]);
    
        } catch (\Exception $e) {
            print_r($e);
            $message = [
                'message' => 'Something went wrong, please try again',
                'status' => 400
            ];
            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
        }
    }

    public function DeliveryReportCart()
{
    $order = new Order_Items_Model();
    $data = $order->deleveryreport();

    if (count($data) == 0) {
        $message = [
            'message' => 'NO DATA FOUND',
            'status' => 400
        ];

        return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
    } else {
        // Transform the data into the desired structure
        $transformedData = [];
        foreach ($data as $item) {
            $transformedData[$item['delivery_status']] = $item['total'];
        }

        // Construct the response data
        $message = [
            'message' => 'Delivery report retrieved successfully!',
            'status' => 200
        ];
        $responseData = [
            'messageobject' => $message,
            'data' => $transformedData
        ];

        return $this->response->setStatusCode(200)->setJSON($responseData);
    }
}

public function carddetailsforsalesBrandandCatagories()
{
    try {
        $productModel = new Order_Items_Model();

        $data['todaysales'] = $productModel->TodayHighSaleBrandandCatagories();
        $data['lat7dayssales'] = $productModel->lat7daysHighSaleBrandandCatagories();
        $data['lat30dayssales'] = $productModel->lat30daysHighSaleBrandandCatagories();
        $data['thisyearsales'] = $productModel->YearHighSaleBrandandCatagories();

        $response = [];
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                $response[] = [
                    'product_name' => $value[0]['product_name'],
                    'brand_name' => $value[0]['brand_name'],
                    'category_name' => $value[0]['category_name'],
                    $key => $value[0]['total_sales']
                ];
            } else {
                $response[] = [
                     'product_name' => '-',
                    'brand_name' => '-',
                    'category_name' => '-',
                    $key => 0 
                ];
            }
        }

        $message = [
            'message' => 'Successfully fetched sales count'
        ];

        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $response]);

    } catch (\Exception $e) {
        print_r($e);
        $message = [
            'message' => 'Something went wrong, please try again',
            'status' => 400
        ];
        return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
    }
}

public function getdeliveryStatusCountToday()
{
    try {
        $productModel = new OrdersModel();

        $data['todaydeliverystaus'] = $productModel->deliverystatus_today();
        

        $response = [];
        foreach ($data as $key => $value) {
            $statusCounts = [];
            foreach ($value as $item) {
                $statusCounts[$item['delivery_status']] = $item['count_today'] ?? $item['count_last_7_days'] ?? $item['count_last_30_days'];
            }
            $response[$key] = $statusCounts;
        }

        $message = [
            'message' => 'Successfully fetched delivery status count'
        ];

        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $response]);

    } catch (\Exception $e) {
        print_r($e);
        $message = [
            'message' => 'Something went wrong, please try again',
            'status' => 400
        ];
        return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
    }
}

public function getdeliveryStatusCount7Days()
{
    try {
        $productModel = new OrdersModel();

        $data['lat7daysdeliverystaus'] = $productModel->deliverystatus_last7days();

        $response = [];
        foreach ($data as $key => $value) {
            $statusCounts = [];
            foreach ($value as $item) {
                $statusCounts[$item['delivery_status']] = $item['count_today'] ?? $item['count_last_7_days'] ?? $item['count_last_30_days'];
            }
            $response[$key] = $statusCounts;
        }

        $message = [
            'message' => 'Successfully fetched delivery status count 7 days'
        ];

        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $response]);

    } catch (\Exception $e) {
        print_r($e);
        $message = [
            'message' => 'Something went wrong, please try again',
            'status' => 400
        ];
        return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
    }
}

public function getdeliveryStatusCount30Days()
{
    try {
        $productModel = new OrdersModel();

       
        $data['lat30daydeliverystaus'] = $productModel->deliverystatus_last30days();

        $response = [];
        foreach ($data as $key => $value) {
            $statusCounts = [];
            foreach ($value as $item) {
                $statusCounts[$item['delivery_status']] = $item['count_today'] ?? $item['count_last_7_days'] ?? $item['count_last_30_days'];
            }
            $response[$key] = $statusCounts;
        }

        $message = [
            'message' => 'Successfully fetched delivery status count 30days'
        ];

        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $response]);

    } catch (\Exception $e) {
        print_r($e);
        $message = [
            'message' => 'Something went wrong, please try again',
            'status' => 400
        ];
        return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
    }
}




//************count odf delivery status and details *********BY from & TOdate***********

public function getDeliveryStatus()
{
    // Get the request body data
    
    $requestData = $this->request->getJSON();

    $fromDate = $requestData->fromDate;
    $toDate = $requestData->toDate;
    $deliveryStatus = $requestData->deliveryStatus;

    $result = $this->OrdersModel->deliverystatus_byFromandTodate($fromDate, $toDate, $deliveryStatus);

    $response = [];

    if (!empty($result)) {
        $response['status'] = 'Delivery status retrieved successfully';
        $response['data']['delivery_status'] = $deliveryStatus;
        $response['data']['count'] = count($result);
        $response['data']['records'] = $result;
    } else {
        $response['status'] = 'error';
        $response['message'] = "No delivery status found for the given date range and status.";
    }

    return $this->response->setJSON($response);
}


//************count odf delivery status and details(all data) *********BY from & TOdate***********

public function getDeliveryStatusReport()
{
    try{
    // Get the request body data
    $requestData = $this->request->getJSON();

    $fromDate = $requestData->fromDate;
    $toDate = $requestData->toDate;

    $result = $this->OrdersModel->deliverystatusreport_byFromandTodate($fromDate, $toDate);

    $response = [];
    $deliveryStatuses = [];

    if (!empty($result)) {
        $response['status'] = 'success';
        $response['message'] = 'Delivery status report retrieved successfully.';
        $response['data'] = [];

        foreach ($result as $record) {
            $status = $record['delivery_status'];

            $response['data'][$status] = [
                'delivery_status' => $status,
                'count' => $record['count'],
                'records' => []
            ];

            $statusRecords = $this->OrdersModel->deliverystatus_byFromandTodate($fromDate, $toDate, $status);
            foreach ($statusRecords as $statusRecord) {
                $response['data'][$status]['records'][] = $statusRecord;
            }
        }

        // Get all delivery statuses
        $allStatuses = $this->OrdersModel->getAllDeliveryStatuses();

        // Add missing statuses with count 0
        foreach ($allStatuses as $status) {
            if (!isset($response['data'][$status])) {
                $response['data'][$status] = [
                    'delivery_status' => $status,
                    'count' => 0,
                    'records' => []
                ];
            }
        }
    } else {
        $response['status'] = 'success';
        $response['message'] = 'No data found for the specified date range.';
        $response['data'] = [];
        $allStatuses = $this->OrdersModel->getAllDeliveryStatuses();

        foreach ($allStatuses as $status) {
            $response['data'][$status] = [
                'delivery_status' => $status,
                'count' => 0,
                'records' => []
            ];
        }
    }

    return $this->response->setJSON($response);
} catch (\Exception $e) {
   
    return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'Internal Server Error']);
}
}

}