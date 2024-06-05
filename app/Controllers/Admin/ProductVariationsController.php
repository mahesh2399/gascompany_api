<?php
namespace App\Controllers\Admin;

use App\Models\Admin\ProductVariationsModel;
use CodeIgniter\RESTful\ResourceController;

class ProductVariationsController extends ResourceController
{

    public function __construct()
    {
        $this->model = new ProductVariationsModel();
    }

    public function ProductVariationscreate()
    {
        try {
            $productVariation = new ProductVariationsModel();
            $json = $this->request->getJSON();

            $product_id = $json->product_id;
            $variation_key = $json->variation_key;
            $sku = $json->sku;
            $code = $json->code;
            $price = $json->price;

            $insert = [
                "product_id" => $product_id,
                "variation_key" => $variation_key,
                "sku" => $sku,
                "code" => $code,
                "price" => $price,
            ];
            $productVariation->insert($insert);
            $insertId = $productVariation->getInsertID();
            $message = [
                'message' => 'Product variation created successfully!',
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
    //**********************************************************************************

    public function getById($id)
    {
        try {
            $productVariation = new ProductVariationsModel();
            $data = $productVariation->find($id);

            if (!$data) {
                return $this->failNotFound('Product variation not found');
            }

            return $this->respond($data);
        } catch (\Exception $e) {
            return $this->failServerError('Server Error');
        }
    }
    //**********************************************************************************

    public function getAll()
    {
        try {
            $productVariation = new ProductVariationsModel();
            $data = $productVariation->findAll();

            return $this->respond($data);
        } catch (\Exception $e) {
            return $this->failServerError('Server Error');
        }
    }
    //**********************************************************************************

    public function deleteById($id)
    {
        try {
            $productVariation = new ProductVariationsModel();
            $data = $productVariation->find($id);

            if (!$data) {
                return $this->failNotFound('Product variation not found');
            }

            $productVariation->delete($id);
            $message = [
                'message' => 'Product variation deleted successfully',
                'status' => 200
            ];

            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
        } catch (\Exception $e) {
            return $this->failServerError('Server Error');
        }
    }
    //**********************************************************************************

    public function updateById()
    {
        try {
            $productVariation = new ProductVariationsModel();
            $json = $this->request->getJSON();

            // Check if ID is provided in the JSON data
            if (isset($json->id)) {
                $id = $json->id;
                $data = [
                    "product_id" => $json->product_id,
                    "variation_key" => $json->variation_key,
                    "sku" => $json->sku,
                    "code" => $json->code,
                    "price" => $json->price,
                ];

                $productVariation->update($id, $data);
                $message = [
                    'message' => 'Product variation updated successfully',
                    'status' => 200
                ];
            } else {
                // ID is not provided, insert a new row
                $data = [
                    "product_id" => $json->product_id,
                    "variation_key" => $json->variation_key,
                    "sku" => $json->sku,
                    "code" => $json->code,
                    "price" => $json->price,
                ];

                $productVariation->insert($data);
                $message = [
                    'message' => 'Product variation inserted successfully',
                    'status' => 201
                ];
            }

            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
        } catch (\Exception $e) {
            return $this->failServerError('Server Error');
        }
    }


}
