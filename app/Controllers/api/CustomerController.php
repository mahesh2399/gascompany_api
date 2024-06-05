<?php


namespace App\Controllers\Api;
use App\Models\api\CustomersModel;
use CodeIgniter\RESTful\ResourceController;
use App\Models\Admin\UserAddressesModel;
use App\Models\Admin\StatesModel;
use App\Models\Admin\CitiesModel;
use App\Models\Admin\CountriesModel;
use App\Models\Admin\UsersModel;
use App\Models\Admin\CartsModel;


class CustomerController extends ResourceController
{

    public function index()
    {
        $CustomersModels = new CustomersModel();
        $Email = $this->request->getGet('Email');
        if (isset($Email)) {
            $CustomersModels->where('Email', $Email);
        }
        $result = $CustomersModels->find();
        $transformedProducts = $this->transformProducts($result);
        return $this->respond($transformedProducts);
    }

    protected function transformProducts($products)
    {
        $transformedProducts = [];
        foreach ($products as $product) {

            $transformedProducts = [
                'id' => $product['Id'],
                'name' => $product['CustomerName'],
                'email' => $product['Email'],
                'phone' => $product['MobileNo'],
                'country_code' => 1,
                'profile_image' => null,
                'profile_image_id' => 1,
                'status' => 1,
                'email_verified_at' => $product['IsVarified'],
                'payment_account' => [],
                'role_id' => 1,
                'role_name' => 'consumer',
                'role' => [],
                'permission' => [],
                'address' => [],
                'point' => [],
                'wallet' => [],
                'orders_count' => [],
                'is_approved' => 1,
                'created_at' => $product['CreatedDate'],
                'updated_at' => $product['ModifiedDate'],
                'deleted_at' => $product['IsDeleted']
            ];
        }

        return $transformedProducts;
    }


    // public function saveBankDetails()
    // {
    //     $json = $this->request->getJSON();
    //     $cartModel = new CartsModel();
    //     $guid = Uuid::uuid4()->toString();

    //     $CustomerId = isset($json->CustomerId) ? $json->CustomerId : null;
    //     $AccountNo = isset($json->AccountNo) ? $json->AccountNo : null;
    //     $BankName = isset($json->BankName) ? $json->BankName : null;
    //     $HolderName = isset($json->HolderName) ? $json->HolderName : null;
    //     $Swift = isset($json->Swift) ? $json->Swift : null;
    //     $IFSC = isset($json->IFSC) ? $json->IFSC : null;

    //     if ($CustomerId) {

    //         $BankDetails = [
    //             'Id' => $guid,
    //             'CustomerId' => $CustomerId,
    //             'AccountNo' => $AccountNo,
    //             'BankName' => $BankName,
    //             'HolderName' => $HolderName,
    //             'Swift' => $Swift,
    //             'IFSC' => $IFSC,

    //         ];
    //         $cartModel->insert($BankDetails);
    //         return $this->respond(['message' => 'Bank Details saved successfuly']);

    //     } else {
    //         return $this->failUnauthorized('Customer Id  is a must');
    //     }





    // }

    public function getCustomer()
    {
        try {
            $UsersModel = new UsersModel();
            $user_id = $this->request->getGet('user_id');
            if (isset($user_id)) {
                $UsersModel->where('id', $user_id);
                $result = $UsersModel->find();
                $userAddressModel = new UserAddressesModel();
                $CountriesModels = new CountriesModel();
                $StatesModels = new StatesModel();
                $CitiesModels = new CitiesModel();
                $userId = $user_id;
                $userAddresses = $userAddressModel->getUserAddressDetails($userId);
                $userAddressDetails = [];
                foreach ($userAddresses as $response) {
                    $CountriesModels->where('id', $response['country_id']);
                    $StatesModels->where('id', $response['state_id']);
                    $CitiesModels->where('id', $response['city_id']);

                    $userAddressDetails[] = [
                        'id' => $response['id'],
                        'title' => $response['title'],
                        'user_id' => $response['user_id'],
                        'street' => $response['address'],
                        'city' => $CitiesModels->first(),
                        'city_id' => $response['city_id'],
                        'pincode' => $response['pincode'],
                        'is_default' => $response['is_default'],
                        'country_code' => isset($response['country_code']) ? $response['country_code'] : null,
                        'phone' => $response['phone'],
                        'country_id' => $response['country_id'],
                        'state_id' => $response['state_id'],
                        'country' => $CountriesModels->first(),
                        'state' => $StatesModels->first(),
                        'type' => $response['type']
                    ];
                }

                $responsedata[] = [
                    'id' => $result[0]['id'],
                    'name' => $result[0]['name'],
                    'email' => $result[0]['email'],
                    'country_code' => '',
                    'phone' => $result[0]['phone'],
                    'profile_image_id' => 1,
                    'system_reserve' => 1,
                    'status' => 0,
                    'created_by_id' => 1,
                    'email_verified_at' => $result[0]['email_or_otp_verified'],
                    'created_at' => null,
                    'updated_at' => null,
                    'orders_count' => 8,
                    'role' => [],
                    'store' => null,
                    'point' => [],
                    'wallet' => [],
                    'address' => $userAddressDetails,
                    'vendor_wallet' => '',
                    'profile_image' => '',
                    'payment_account' => '',

                ];


                $data = $responsedata;


                $message = [
                    'message' => 'Success!',
                    'status' => 200
                ];

                return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);

                // return $this->respond(['responsedata' => $responsedata, 200]);
            } else {

                $message = [
                    'message' => 'userId not found',
                    'status' => 400
                ];
                return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);

            }
        } catch (\Exception $e) {


            $message = [
                'message' => $e->getMessage(),
                'status' => 500
            ];
            return $this->response->setStatusCode(500)->setJSON(['messageobject' => $message]);

        }
    }   //////// ================ GETCUSTOMER ==================





}
