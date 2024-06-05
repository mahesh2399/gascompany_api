<?php

namespace App\Controllers\Admin;

use CodeIgniter\API\ResponseTrait;
use App\Models\Admin\BrandsModel;
use App\Models\Admin\CurrenciesModel;
use App\Models\Admin\TaxModel;
use App\Models\Admin\UnitsModel;
use App\Models\Admin\SupplierModel;
use App\Models\Admin\CategoriesModel;
use App\Models\Admin\OrdersModel;
use App\Models\Admin\ProductVariationStocksModel;
use App\Models\Admin\PurchaseOrderModel;
use App\Models\Admin\SaleOrderModel;
use App\Models\Admin\ProductcategoriesModel;
use App\Models\Admin\LocationsModel;
use App\Models\Admin\PurchaseOrderReturnModel;
use App\Models\Admin\ProductTaxesModel;
use App\Models\Admin\System_settingsModel;
use App\Models\Admin\SupplierAddressesModel;
use App\Models\Admin\ProductsModel;
use App\Models\Admin\ProductsCategoryModel;
use App\Models\Admin\MediaModel;
use App\Models\Admin\Order_Groups_Model;
use App\Models\Admin\UsersModel;
use App\Models\Admin\UserAddressesModel;
use App\Models\Admin\RolesModel;
use App\Models\Admin\ProductVariationsModel;
use App\Models\Admin\Media_managersModel;
use App\Models\Admin\CountriesModel;
use App\Models\Admin\StatesModel;
use App\Models\Admin\CitiesModel;
use App\Models\Admin\Order_Items_Model;
use App\Models\Admin\CategoryLocalizationsModel;
use App\Models\Admin\BrandLocalizationsModel;
use App\Models\Admin\TaxesModel;
use App\Models\Admin\DeliveryMenModel;
use App\Models\Admin\PermissionsModel;
use App\Models\Admin\RoleHasPermissionModel;
use CodeIgniter\RESTful\ResourceController;

date_default_timezone_set("Asia/Kolkata");

class AdminCommonController extends ResourceController
{
    use ResponseTrait;

    public function getBrand()
    {

        $brands = new BrandsModel();


        $brand = $brands->findAll();
        $data = [];


        foreach ($brand as $list) {
            $data[] = [
                "id" => $list['id'],
                "name" => $list['name'],
                "slug" => $list['slug'],
                "type" => "post",
                "description" => $list['meta_description'],
                "status" => $list['is_active'],
                "created_by_id" => 1,
                "created_at" => $list['created_at'],
                "updated_at" => $list['updated_at'],
                "deleted_at" => $list['deleted_at'],
                "blogs_count" => 6,
                "products_count" => 0
            ];
        }


        return $this->respond(['data' => $data]);
    }

    public function BrandStatusChange()
    {

        $brands = new BrandsModel();
        $json = $this->request->getJSON();
        $brands->set(['is_active' => $json->status])->where('id', $json->id)->update();
        $data = "brand status successfully";
        $message = [
            'message' => 'Brand Status Change Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }

    public function BrandEdit()
    {

        $brands = new BrandsModel();
        $json = $this->request->getJSON();
        $Branddata = [
            "name" => $json->name,
            "meta_description" => $json->meta_description,
            "is_active" => $json->is_active
        ];
        $brands->set($Branddata)->where('id', $json->id)->update();
        $data = "brand updated successfully";
        $message = [
            'message' => 'Brand Updated Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }


    public function BrandDelete()
    {
        $brandLocalizationModel = new BrandLocalizationsModel();
        $brandsModel = new BrandsModel();
        $json = $this->request->getJSON();
        $brandId = $json->id;
        // $brandLocalizations = $brandLocalizationModel->where('brand_id', $brandId)->findAll();
        // $brandLocalizationModel->set(['is_active'=>0])->where('brand_id', $brandId)->update();

        $brandsModel->set(['is_active'=>0])->where('id',$brandId)->update();

        $data = "deleted successfully";
        $message = [
            'message' => ' Brand Deleted Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }

    public function BrandCreate()
    {
        $brand = new BrandsModel();
        $json = $this->request->getJSON();

        $result5 = $brand->where('name', $json->name);
        $check = $result5->get()->getNumRows();

        $slug = strtolower($json->name);
        if ($check < 1) {
            $json->slug = $slug . "-" . $this->generateRandomString(4);
            $brand->insert($json);

            $data = "Brand created successfully";

            $message = [
                'message' => 'Brand Created Successfully!',
                'status' => 200
            ];
            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);

        } else {
            $data = "Brand Name Already Exists!";

            $message = [
                'message' => 'Brand Name Already Exists!',
                'status' => 400
            ];
            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message, 'data' => $data]);

        }
    }

    function generateRandomString($length = 4)
    {
        // List of characters to use in the random string
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Shuffle the characters
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }




    public function getTax()
    {

        $tax = new TaxModel();
        $taxes = $tax->findAll();
        $data = [];


        foreach ($taxes as $list) {
            $data[] = [
                "id" => $list['id'],
                "name" => $list['name'],
                "created_at" => $list['created_at'],
                "status" => $list['is_active'],
                "value" => $list['value'],
            ];
        }


        return $this->respond(['data' => $data]);
    }
    // ......................get products.........................

    public function get_products()
    {
        try {
            $ProductsModels = new ProductsModel();
            $units = new UnitsModel();
            $ProductcategoriesModels = new ProductcategoriesModel();
            $ProductsModels->where('stock_qty >', 0);
            $ProductsModels->where('is_published', 1);
            $ProductsModels->where('is_deleted', 0);
            $ProductsModels->orderBy('id', 'desc');
            $data = $ProductsModels->findAll();
            $resposneData = [];
            $ProductVariationsModel = new ProductVariationStocksModel();
            $mediaModel = new Media_managersModel();
            $TaxesModels = new TaxesModel();
            foreach ($data as $resposne) {
                $mediaModel->where('id', $resposne['thumbnail_image']);
                $imageUrl = $mediaModel->findAll();
                $ProductsModelsdata = $ProductVariationsModel->where('product_variation_id ', $resposne['id'])->first();
                $stock_status = (intval($resposne['stock_qty']) > 0) ? 'in_stock' : 'out_stock';
                $resposneData[] = [
                    'id' => $resposne['id'],
                    'name' => $resposne['name'],
                    'slug' => $resposne['slug'],
                    'description' => $resposne['description'],
                    'category_image_id' => null,
                    'category_icon_id' => null,
                    'status' => 1,
                    'type' => "product",
                    'commission_rate' => null,
                    'parent_id' => null,
                    'created_by_id' => "1",
                    'created_at' => $resposne['created_at'],
                    'updated_at' => $resposne['updated_at'],
                    'min_stock_qty' => $resposne['min_stock_qty'],
                    'min_price' => $resposne['min_price'],
                    'price' => $resposne['price'],
                    'blogs_count' => 6,
                    'products_count' => 0,
                    'thumbnail_image' => $resposne['thumbnail_image'],
                    'thumbnail_image_url' => isset($imageUrl[0]['media_file']) ? $imageUrl[0]['media_file'] : null,
                    'category_icon' => null,
                    'stock_qty' => $resposne['stock_qty'],
                    'parent' => null,
                    'short_description' => $resposne['short_description'],
                    'unit' => [],
                    'weight' => [],
                    'quantity' => [],
                    'sale_price' => $resposne['max_price'],
                    'discount' => $resposne['discount_value'],
                    'is_featured' => $resposne['is_featured'],
                    'shipping_days' => [],
                    'sale_starts_at' => [],
                    'sale_expired_at' => [],
                    'sku' => [],
                    'is_random_related_products' => [],
                    'stock_status' => $stock_status,
                    'meta_title' => [],
                    'meta_description' => [],
                    'product_thumbnail_id' => [],
                    'product_meta_image_id' => [],
                    'return_policy_text' => $resposne['return_policy_text'],
                    'safe_checkout' => [],
                    'secure_checkout' => [],
                    'social_share' => [],
                    'encourage_order' => [],
                    'encourage_view' => [],
                    'store_id' => [],
                    'tax_id' => $resposne['tax_id'],
                    'orders_count' => [],
                    'reviews_count' => [],
                    'can_review' => [],
                    'rating_count' => [],
                    'order_amount' => [],
                    'review_ratings' => [],
                    'related_product' => [],
                    'cross_sell_products' => [],
                    'product_thumbnail' => [],
                    'product_galleries' => [],
                    'product_meta_image' => [],
                    'reviews' => [],
                    'store' => [],
                    'store_logo' => [],
                    'store_cover' => [],
                    'vendor' => [],
                    'point' => [],
                    'wallet' => [],
                    'brand_id' => $resposne['brand_id'],
                    'profile_image' => [],
                    'payment_account' => [],
                    'country' => [],
                    'state' => [],
                    'tax_details' => $TaxesModels->where('id', $resposne['tax_id'])->first(),
                    'tags' => [],
                    'shop_id' => $resposne['shop_id'],
                    'express_delivery_hours' => $resposne['express_delivery_hours'],
                    'attributes' => [],
                    'variations' => $ProductsModelsdata,
                    'unit_id' => isset($resposne['unit_id']) ? $resposne['unit_id'] : null,
                    'unit_details' => $units->where('id', $resposne['unit_id'])->first(),
                    'categories' => $ProductcategoriesModels->where('product_id', $resposne['id'])->first(),
                    'location_id' => $resposne['location_id'],
                    'estimated_delivery_text' => $resposne['estimated_delivery_text'],



                ];
            }
            $data = $resposneData;

            $message = [
                'message' => 'Products Get Successfully!',
                'status' => 200
            ];

            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
        } catch (\Exception $e) {
            $message = [

                'message' => $e->getMessage(),
                'status' => 500
            ];
            return $this->response->setStatusCode(500)->setJSON(['messageobject' => $message]);
        }
    }


    public function get_products_search()
    {

        try {
            $searchString = $this->request->getVar('searchString');
            $code = $this->request->getVar('code');
            $ProductsModels = new ProductsModel();
            $units = new UnitsModel();
            $ProductsModels->where('stock_qty >', 0);
            $ProductsModels->where('is_published', 1);

            $ProductsModels->where('is_deleted', 0);

            if (isset($code)) {
                if ($code == '') {
                    $ProductsModels->findAll();

                } else {
                    $ProductsModels->where('id', $code);
                }
            }
            if (isset($searchString)) {
                $ProductsModels->like('name', $searchString);
            }
            $ProductsModels->orderBy('id', 'desc');
            $data = $ProductsModels->findAll();
            $resposneData = [];
            $ProductVariationsModel = new ProductVariationsModel();
            $mediaModel = new Media_managersModel();
            $TaxesModels = new TaxesModel();
            foreach ($data as $resposne) {
                $mediaModel->where('id', $resposne['thumbnail_image']);
                $imageUrl = $mediaModel->findAll();
                $ProductsModelsdata = $ProductVariationsModel->where('product_id', $resposne['id'])->first();
                $stock_status = (intval($resposne['stock_qty']) > 0) ? 'in_stock' : 'out_stock';
                $resposneData[] = [
                    'id' => $resposne['id'],
                    'name' => $resposne['name'],
                    'slug' => $resposne['slug'],
                    'description' => $resposne['description'],
                    'category_image_id' => null,
                    'category_icon_id' => null,
                    'status' => 1,
                    'type' => "product",
                    'commission_rate' => null,
                    'parent_id' => null,
                    'created_by_id' => "1",
                    'created_at' => $resposne['created_at'],
                    'updated_at' => $resposne['updated_at'],
                    'deleted_at' => null,
                    'blogs_count' => 6,
                    'products_count' => 0,
                    'thumbnail_image' => $resposne['thumbnail_image'],
                    'thumbnail_image_url' => isset($imageUrl[0]['media_file']) ? $imageUrl[0]['media_file'] : null,
                    'category_icon' => null,
                    'subcategories' => [],
                    'stock_qty' => $resposne['stock_qty'],
                    'parent' => null,
                    'short_description' => [],
                    'unit' => [],
                    'weight' => [],
                    'quantity' => [],
                    'sale_price' => $resposne['max_price'],
                    'discount' => $resposne['discount_value'],
                    'is_featured' => $resposne['is_featured'],
                    'shipping_days' => [],
                    'is_cod' => [],
                    'is_free_shipping' => [],
                    'is_sale_enable' => [],
                    'is_return' => [],
                    'is_trending' => [],
                    'is_approved' => [],
                    'sale_starts_at' => [],
                    'sale_expired_at' => [],
                    'sku' => [],
                    'is_random_related_products' => [],
                    'stock_status' => $stock_status,
                    'meta_title' => [],
                    'meta_description' => [],
                    'product_thumbnail_id' => [],
                    'product_meta_image_id' => [],
                    'size_chart_image_id' => [],
                    'estimated_delivery_text' => [],
                    'return_policy_text' => [],
                    'safe_checkout' => [],
                    'secure_checkout' => [],
                    'social_share' => [],
                    'encourage_order' => [],
                    'encourage_view' => [],
                    'store_id' => [],
                    'tax_id' => $resposne['tax_id'],
                    'orders_count' => [],
                    'reviews_count' => [],
                    'can_review' => [],
                    'rating_count' => [],
                    'order_amount' => [],
                    'review_ratings' => [],
                    'related_product' => [],
                    'cross_sell_products' => [],
                    'product_thumbnail' => [],
                    'product_galleries' => [],
                    'product_meta_image' => [],
                    'reviews' => [],
                    'store' => [],
                    'store_logo' => [],
                    'store_cover' => [],
                    'vendor' => [],
                    'point' => [],
                    'wallet' => [],
                    'address' => [],
                    'vendor_wallet' => [],
                    'profile_image' => [],
                    'payment_account' => [],
                    'country' => [],
                    'state' => [],
                    'tax_details' => $TaxesModels->where('id', $resposne['tax_id'])->first(),
                    'categories' => [],
                    'category_image' => [],
                    'tags' => [],
                    'attributes' => [],
                    'variations' => $ProductsModelsdata,
                    'unit_id' => isset($resposne['unit_id']) ? $resposne['unit_id'] : null,
                    'unit_details' => $units->where('id', $resposne['unit_id'])->first(),

                ];
            }
            $data = $resposneData;

            $message = [
                'message' => 'Products Get Successfully!',
                'status' => 200
            ];

            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
        } catch (\Exception $e) {
            $message = [

                'message' => $e->getMessage(),
                'status' => 500
            ];
            return $this->response->setStatusCode(500)->setJSON(['messageobject' => $message]);
        }
    }


    public function productCreate()
    {
        $chkupdate = false;
        $ProductsCategoryModels = new ProductsCategoryModel();
        $product_variation = new ProductVariationsModel();
        $product_variation_stock = new ProductVariationStocksModel();
        $ProductTaxesModels = new ProductTaxesModel();

        $json = $this->request->getJSON();

        // Validate JSON data
        if (empty($json)) {
            $data = "Invalid Inputs";
            $message = [
                'message' => "Invalid Inputs",
                'status' => 400
            ];
            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message, 'data' => $data]);
        }

        // Check if required fields are present in the JSON data
        if (!isset($json->name) || !isset($json->price) || !isset($json->brand_id)) {
            $data = "Invalid inputs";
            $message = [
                'message' => "Invalid Inputs",
                'status' => 400
            ];
            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message, 'data' => $data]);
        }
        $productModel = new ProductsModel();

        $productModel->where('is_deleted', 0);
        $result5 = $productModel->where('name', $json->name);
        $check = $result5->get()->getNumRows();
        $productId = isset($json->id) ? $json->id : "";
        if ($check > 0 && $productId == '') {
            $data = "Name already exists";
            $message = [
                'message' => "Name Already Exists",
                'status' => 400
            ];
            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message, 'data' => $data]);
        } else {

            $data = [
                "name" => $json->name,
                "short_description" => isset($json->short_description) ? $json->short_description : null,
                "description" => isset($json->description) ? $json->description : null,
                "price" => isset($json->price) ? $json->price : 0,
                "thumbnail_image" => isset($json->thumbnail_image) ? $json->thumbnail_image : null,
                "discount_start_date" => isset($json->discount_start_date) ? $json->discount_start_date : null,
                "discount_value" => isset($json->discount_value) ? $json->discount_value : null,
                "unit_id" => isset($json->unit_id) ? $json->unit_id : null,
                "brand_id" => isset($json->brand_id) ? $json->brand_id : null,
                "stock_qty" => isset($json->stock_qty) ? $json->stock_qty : null,
                "min_price" => isset($json->min_price) ? $json->min_price : null,
                "max_price" => isset($json->max_price) ? $json->max_price : null,
                "discount_type" => isset($json->discount_type) ? $json->discount_type : "percent",
                "is_published" => isset($json->is_published) ? $json->is_published : 0,
                "min_stock_qty" => isset($json->min_stock_qty) ? $json->min_stock_qty : null,
                "tax_id" => isset($json->tax_id) ? $json->tax_id : null,
                "estimated_delivery_text" => isset($json->estimated_delivery_text) ? $json->estimated_delivery_text : null,
                "return_policy_text" => isset($json->return_policy_text) ? $json->return_policy_text : null,
                "location_id" => isset($json->location_id) ? $json->location_id : null,



                // Add other fields as needed
            ];
            $productId = isset($json->id) ? $json->id : "";
            if ($productId != '') {

                $productModel->set($data)->where('id', $productId)->update();
                $chkupdate = true;
            } else {
                $productId = $productModel->insert($data);
            }

            if (isset($json->category_id)) {
                $pro_cat = [
                    "product_id" => $productId,
                    "category_id" => $json->category_id,
                ];
                $chkCat = $ProductsCategoryModels->where('product_id', $productId)->first();

                if ($chkCat) {
                    $ProductsCategoryModels->set($pro_cat)->where('id', $chkCat['id'])->update();
                } else {
                    $ProductsCategoryId = $ProductsCategoryModels->insert($pro_cat);
                }

            }


            if (isset($json->price)) {
                $product_variations = [
                    "product_id" => $productId,
                    "sku" => strtoupper($json->name) . rand(10, 99),
                    "code" => strtoupper($json->name) . rand(10, 99),
                    "price" => $json->price,
                ];

                $varChk = $product_variation->where('product_id', $productId)->first();
                if ($varChk) {
                    $product_variation->set($product_variations)->where('id', $varChk['id'])->update();
                    $product_variation_id = $varChk['id'];
                } else {
                    $product_variation_id = $product_variation->insert($product_variations);

                }


            }


            if (isset($json->location_id) && isset($json->stock_qty)) {
                $product_variation_stocks = [
                    "product_variation_id" => $product_variation_id,
                    "location_id" => $json->location_id,
                    "stock_qty" => $json->stock_qty,
                    "min_stock_qty" => isset($json->min_stock_qty) ? $json->min_stock_qty : null
                ];
                $product_variation_stock->insert($product_variation_stocks);
            }



            if (isset($json->tax_id)) {
                $tax = [
                    "product_id" => $productId,
                    "tax_value" => isset($json->tax_value) ? $json->tax_value : 0,
                    "tax_id" => $json->tax_id,
                    "tax_type" => isset($json->tax_type) ? $json->tax_type : "percent"
                ];

                $taxChk = $ProductTaxesModels->where('product_id', $productId)->first();
                if ($taxChk) {
                    $ProductTaxesModels->set($tax)->where('id', $taxChk['id'])->update();
                } else {
                    $ProductTaxesModels->insert($tax);
                }

                $ProductTaxesModels->insert($tax);
            }


            // $product_taxes

            if ($productId) {
                if (!$chkupdate) {
                    $data = "Product Added Successfully";
                    $message = [
                        'message' => "Product Added Successfully",
                        'status' => 200
                    ];
                } else {
                    $data = "Product updated successfully";
                    $message = [
                        'message' => "Product Updated Successfully",
                        'status' => 200
                    ];
                }
                return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);

            } else {
                // If file move fails
                $data = "Product upload failed";
                $message = [
                    'message' => "Product Upload Failed",
                    'status' => 400
                ];
                return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message, 'data' => $data]);
            }
        }
    }

    public function productEdit()
    {

        $product = new ProductsModel();
        $json = $this->request->getJSON();
        $productData = [
            "name" => isset($json->name) ? $json->name : null,
            "short_description" => isset($json->short_description) ? $json->short_description : null,
            "description" => isset($json->description) ? $json->description : null,
            "price" => isset($json->price) ? $json->price : null,
            "discount_start_date" => isset($json->discount_start_date) ? $json->discount_start_date : null,
            "discount_value" => isset($json->discount_value) ? $json->discount_value : null,

            // ............................................................................
            "shop_id" => isset($json->shop_id) ? $json->shop_id : null,
            "added_by" => isset($json->added_by) ? $json->added_by : null,
            "slug" => isset($json->slug) ? $json->slug : null,
            "brand_id" => isset($json->brand_id) ? $json->brand_id : null,
            "unit_id" => isset($json->unit_id) ? $json->unit_id : null,
            "thumbnail_image" => isset($json->thumbnail_image) ? $json->thumbnail_image : null,
            "product_tags" => isset($json->product_tags) ? $json->product_tags : null,


            "min_price" => isset($json->price) ? $json->price : null,
            "max_price" => isset($json->price) ? $json->price : null,
            "discount_end_date" => isset($json->discount_end_date) ? $json->discount_end_date : null,




        ];
        $product->set($productData)->where('id', $json->id)->update();
        $data = "paymant updated successfully";
        $message = [
            'message' => 'Product Updated Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }


    // public function productDelete()
    // {

    //     $product = new ProductsModel();
    //     $json = $this->request->getJSON();
    //     $product->delete($json->id);
    //     $data = "deleted successfully";
    //     $message = [
    //         'message' => ' Product Deleted Successfully!',
    //         'status' => 200
    //     ];
    //     return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    // }
    public function productDelete()
{
    $product = new ProductsModel();
    $json = $this->request->getJSON();
    $productId = $json->id;
    // $updateData = ['is_deleted ' => 1]; 
    $product->query("UPDATE `products` SET `is_deleted` = '1' WHERE `products`.`id` = $productId; ");
    // $product->set(['is_deleted ' => 1])->where('id', $productId)->update();
    $message = [
        'message' => 'Product Marked as Deleted Successfully!',
        'status' => 200
    ];
    return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                



    //**************SEARCH PRODUCT BY NAME**********************    
    public function searchProduct()
    {
        try {
            $searchString = $this->request->getVar('searchString');


            if (empty($searchString)) {
                return $this->fail('Search string is required', 400);
            }
            $productmodel = new ProductsModel();
            $products = $productmodel->like('name', $searchString)->findAll();


            $response_object = [
                "data" => $products,
            ];

            return $this->respond($response_object);
        } catch (\Exception $e) {
            return $this->fail('Failed to search products', 500);
        }
    }

    //**************SEARCH PRODUCT BY ID **********************    
    public function searchProductbyID()
    {
        try {
            $searchString = $this->request->getVar('searchString');

            if (empty($searchString)) {
                return $this->fail('Search string is required', 400);
            }
            if (is_numeric($searchString) && intval($searchString) > 0) {
                $productModel = new ProductsModel();
                $product = $productModel->find($searchString);
                if ($product) {
                    $response_object = [
                        "data" => [$product],
                    ];
                    return $this->respond($response_object);
                }
            }

            return $this->fail('Product Not Found', 404);
        } catch (\Exception $e) {
            return $this->fail('Failed to Search Products', 500);
        }
    }

    ///............................ get curreemncy...................................
    public function getcurrency()
    {
        $currencyyys = new CurrenciesModel();
        $currency = $currencyyys->findAll();
        $data = [];

        foreach ($currency as $list) {
            $currencyData = [
                "id" => $list['id'],
                "code" => $list['code'],
                "symbol" => $list['symbol'],
                "no_of_decimal" => 3,
                "exchange_rate" => $list['rate'],
                "symbol_position" => "after_price",
                "thousands_separator" => "comma",
                "decimal_separator" => "comma",
                "status" => $list['is_active'],
                "created_by_id" => null,
                "created_at" => $list['created_at'],
                "updated_at" => $list['updated_at'],
                "deleted_at" => $list['deleted_at']
            ];

            if (isset($list['system_reserve'])) {
                $currencyData["system_reserve"] = $list['system_reserve'];
            } else {

                $currencyData["system_reserve"] = null;
            }

            $data[] = $currencyData;
        }

        return $this->respond(['data' => $data]);
    }


    public function currencyStatusChange()
    {

        $currencies = new CurrenciesModel();
        $json = $this->request->getJSON();
        $currencies->set(['is_active' => $json->status])->where('id', $json->id)->update();
        $data = "paymant updated successfully";
        $message = [
            'message' => 'Currenct Status Change Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }

    public function CurrencyEdit()
    {

        $currencies = new CurrenciesModel();
        $json = $this->request->getJSON();
        if (isset($json->code)) {
            $Currencydata = [
                "code" => $json->code,
                "name" => isset($json->name) ? $json->name : null,
                "symbol" => isset($json->symbol) ? $json->symbol : null,
                // "no_of_decimal" => $json->no_of_decimal,
                "alignment" => isset($json->alignment) ? $json->alignment : null,
                "rate" => isset($json->rate) ? $json->rate : null,
                "no_of_decimal" => 3,
                "exchange_rate" => isset($json->rate) ? $json->rate : null,
                // "symbol_position" => $json->symbol_position,
                "symbol_position" => "after_price",
                "is_active" => isset($json->status) ? $json->status : null,



            ];
            $currencies->set($Currencydata)->where('id', $json->id)->update();
            $data = "successfully updated";
            return $this->respond(['data' => $data]);
        }
    }

    public function currencyDelete()
    {

        $currencys = new CurrenciesModel();
        $json = $this->request->getJSON();
        $currencys->delete($json->id);
        $data = "deleted successfully";
        $message = [
            'message' => 'Currency Deleted Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }


    public function currencyCreate()
    {
        $brand = new CurrenciesModel();
        $json = $this->request->getJSON();

        $brand->insert($json);

        $data = "Brand created successfully";
        return $this->respond(['data' => $data]);
    }


    public function getmediadata()
    {
        $mediaaa = new MediaModel();
        $media = $mediaaa->orderBy('id', 'desc')->findAll();
        $data = [];
        foreach ($media as $list) {

            $data[] = [
                "id" => $list['id'],
                "created_by_id" => $list['user_id'],
                "original_url" => $list['media_file'],
                "size" => $list['media_size'],
                "mime_type" => $list['media_type'],
                "file_name" => $list['media_name'],
                "symbol_position" => $list['media_extension'],
                "created_at" => $list['created_at'],
                "updated_at" => $list['updated_at'],
                "collection_name" => "attachment",
                "conversions_disk" => "public"

            ];
        }

        return $this->respond(['data' => $data]);
    }





    public function mediaimagesCreate()
    {
        $media = new MediaModel();

        // Fetch uploaded file
        $uploadedFile = $this->request->getFile('file');

        // Get the original file extension
        $fileMimeTypeSplitted = explode("/", $uploadedFile->getClientMimeType());

        // $storeData=$uploadedFile->store('../../../public/uploads/media');

        $directory = WRITEPATH . '../public/uploads/media/';

        // Generate a unique name for the file to avoid naming conflicts
        $newName = $uploadedFile->getRandomName();

        // Move the uploaded file to the desired directory with the new name
        $storeData = $uploadedFile->move($directory, $newName);

        $media_object = [
            "user_id" => 1,
            "media_size" => $uploadedFile->getSize(),
            "media_type" => $fileMimeTypeSplitted[0],
            "media_name" => $uploadedFile->getClientName(),
            // "media_name"=>$fileName,
            "media_file" => 'uploads/media/' . $newName,
            "media_extension" => $fileMimeTypeSplitted[1],
        ];

        if ($storeData) {
            $media->insert($media_object);

            $data = "Media Added Successfully";
            $message = [
                'message' => "Media Added Successfully",
                'status' => 200
            ];
            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
        } else {
            // If file move fails
            $data = "Media Upload failed";
            $message = [
                'message' => "Media Upload Failed",
                'status' => 400
            ];
            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message, 'data' => $data]);
        }
    }
    function getRandomName($extension)
    {

        $uniqueId = uniqid();


        $randomName = $uniqueId . '.' . $extension;

        return $randomName;
    }



    // ........................get orderes



    public function getAllOrders()
    {

        $orders = new OrdersModel();
        $ordergroup = new Order_Groups_Model();
        $customer = new UsersModel();

        $Order_Items_Models = new Order_Items_Model();


        $order = $orders->where('user_id IS NOT NULL', NULL, FALSE)->findAll();

        usort($order, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        $data = [];
        foreach ($order as $list) {

            $order_data = $ordergroup->where('id', $list['order_group_id'])->first();
            $cus_data = $customer->where('id', $list['user_id'])->first();

            $datas = $order_data;

            $order_items = $Order_Items_Models->where('order_id', $list['id'])->findAll();

            $order_id = $list['id'];
            $payment_gatway = [];
            if ($order_id) {
                $query = "SELECT * FROM payment_gatway where order_code = $order_id ";
                $payment_gatway = $orders->query($query)->getResult();

            }

            $payment_amount = 0;
            $payment_balance_amount = 0;

            if (count($payment_gatway) > 0) {
                $payment_amount = $payment_gatway[0]->amount;
                $payment_balance_amount = $payment_gatway[0]->balance_amount;
            }

            $data[] = [


                "id" => $list['id'],
                "order_number" => $list['id'],
                "consumer_id" => $list['user_id'],
                "tax_total" => $datas['total_tax_amount'],
                "shipping_total" => $list['shipping_cost'],
                "points_amount" => $list['reward_points'],
                "wallet_balance" => 0,
                "amount" => $datas['grand_total_amount'],
                "total" => $datas['grand_total_amount'],
                "coupon_total_discount" => $list['coupon_discount_amount'],
                "payment_method" => $datas['payment_method'],
                "payment_status" => $list['payment_status'],
                "store_id" => 15,
                "consumer" => $cus_data,
                "order_items_count" => count($order_items),

                "delivery_status" => $list['delivery_status'],
                "created_at" => $list['created_at'],
                "customer_amount" => $payment_amount,
                "balance_amount" => $payment_balance_amount,
            ];

        }

        return $this->respond(['data' => $data]);
    }



    // --------------------tax----------------------


    public function TaxStatusChange()
    {
        $taxes = new TaxModel();
        $json = $this->request->getJSON();
        $taxes->set(['is_active' => $json->status])->where('id', $json->id)->update();
        $data = "paymant updated successfully";
        $message = [
            'message' => 'Tax Status Change Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }

    public function taxCreate()
    {
        $tax = new TaxModel();
        $json = $this->request->getJSON();
        $slug = strtolower($json->name);
        $value = strtolower($json->value);

        $json->value = $value;
        $json->slug = $slug . "-" . $this->generateRandomString(4);
        $tax->insert($json);

        $message = [
            'message' => 'Tax Created Successfully!',
            'status' => 200
        ];

        return $this->respond(['messageobject' => $message]);
    }

    public function taxDelete()
    {

        $brands = new TaxModel();
        $json = $this->request->getJSON();
        $brands->delete($json->id);
        $data = "deleted successfully";
        $message = [
            'message' => 'Tax Deleted Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }



    public function taxEdit()
    {

        $taxes = new TaxModel();
        $json = $this->request->getJSON();
        $tax_data = [
            "name" => $json->name,
            "is_active" => $json->is_active,
            "value" => $json->value,
        ];
        $taxes->set($tax_data)->where('id', $json->id)->update();
        $data = "paymant updated successfully";
        $message = [
            'message' => 'Tax Updated Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }

    // --------------------unit -----------------------------

    public function get_units()
    {

        $unit = new UnitsModel();

        $units = $unit->findAll();
        $data = [];
        foreach ($units as $list) {
            $data[] = [
                "id" => $list['id'],
                "name" => $list['name'],
                "unit_code" => $list['unit_code'],
                "base_unit" => $list['base_unit'],
                "Operator" => $list['Operator'],
                "operation_value" => $list['operation_value'],
                "created_at" => $list['created_at'],
                "status" => $list['is_active'],
            ];
        }
        return $this->respond(['data' => $data]);
    }

    public function unitStatus()
    {
        $units = new UnitsModel();
        $json = $this->request->getJSON();
        $units->set(['is_active' => $json->status])->where('id', $json->id)->update();
        $data = "paymant updated successfully";
        $message = [
            'message' => 'Unit Status Updated Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }

    public function unitCreate()
    {
        $unit = new UnitsModel();
        $json = $this->request->getJSON();

        $unit->insert($json);

        $message = [
            'message' => 'Unit Created Successfully!',
            'status' => 200
        ];

        return $this->respond(['data' => $message]);
    }

    public function unitDelete()
    {

        $units = new UnitsModel();
        $json = $this->request->getJSON();
        $units->delete($json->id);
        $data = "deleted successfully";
        $message = [
            'message' => 'Unit Deleted Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }



    public function unitEdit()
    {

        $units = new UnitsModel();
        $json = $this->request->getJSON();
        $unit_data = [
            "name" => $json->name,
            "is_active" => $json->is_active,
            "unit_code" => $json->unit_code,
            "base_unit" => $json->base_unit,
            "Operator" => $json->Operator,
            "operation_value" => $json->operation_value,

        ];
        $units->set($unit_data)->where('id', $json->id)->update();
        $data = "paymant updated successfully";
        $message = [
            'message' => 'Unit Updated Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }

    // -----------------------supplier------------------

    public function get_suppliers()
    {
        $supplierModel = new SupplierModel();
        $suppliers = $supplierModel->findAll();
        $data = [];

        foreach ($suppliers as $list) {
            $data[] = [
                "id" => $list['Id'],
                "SupplierName" => $list['SupplierName'],
                "Email" => $list['Email'],
                "PhoneNo" => $list['PhoneNo'],
                "Website" => $list['Website'],
                "supplier_address" => $list['supplier_address'],
                "billing_address" => $list['billing_address'],
                "shipping_address" => $list['shipping_address'],
                "ContactPerson" => $list['ContactPerson'],
                "created_at" => $list['created_at'],
                "status" => $list['IsDeleted']

            ];
        }

        return $this->respond(['data' => $data]);
    }

    public function get_supplier_by_id()
    {
        $json = $this->request->getJSON();
        $supplierId = $json->supplier_id;

        $supplierModel = new SupplierModel();
        $supplier = $supplierModel->find($supplierId);

        if (!$supplier) {
            return $this->respond(['error' => 'Supplier not found'], 404);
        }

        $addressModel = new SupplierAddressesModel();
        $supplierAddress = $addressModel->where('SupplierId', $supplier['Id'])->findColumn('supplier_address');
        $billingAddress = $addressModel->where('SupplierId', $supplier['Id'])->findColumn('billing_address');
        $shippingAddress = $addressModel->where('SupplierId', $supplier['Id'])->findColumn('shipping_address');

        $data = [
            "id" => $supplier['Id'],
            "name" => $supplier['SupplierName'],
            "email" => $supplier['Email'],
            "mobile_number" => $supplier['PhoneNo'],
            "website" => $supplier['Website'],
            "ContactPerson" => $supplier['ContactPerson'],
            "fax" => $supplier['Fax'],
            "phone_number" => $supplier['PhoneNo'],
            "description" => $supplier['Description'],
            "url" => $supplier['Url'],
            "is_verified" => $supplier['IsVarified'],
            "is_unsubscribe" => $supplier['IsUnsubscribe'],
            "supplier_profile" => $supplier['SupplierProfile'],
            "business_type" => $supplier['BusinessType'],
            "status" => $supplier['IsDeleted'],
            "addresses" => [
                "supplier_address" => $supplierAddress ? reset($supplierAddress) : null,
                "billing_address" => $billingAddress ? reset($billingAddress) : null,
                "shipping_address" => $shippingAddress ? reset($shippingAddress) : null
            ]
        ];

        return $this->respond(['data' => $data]);
    }


    public function supplierStatus()
    {

        $suppliers = new SupplierModel();
        $json = $this->request->getJSON();
        $suppliers->set(['IsDeleted' => $json->status])->where('Id', $json->id)->update();
        $data = "paymant updated successfully";
        $message = [
            'message' => 'Supplier Stutas Change Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }

    public function supplierCreate()
    {
        $supplier = new SupplierModel();
        $json = $this->request->getJSON();

        $supplier->insert($json);
        $data = "Supplier Created Successfully";

        $messageobject = [
            'message' => 'Supplier Created Successfully!',
            'status' => 200
        ];

        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $messageobject, 'data' => $data]);
    }


    public function supplierDelete()
    {

        $suppliers = new SupplierModel();
        $json = $this->request->getJSON();
        $suppliers->delete($json->id);
        $data = "deleted successfully";
        $message = [
            'message' => 'Supplier Deleted Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }
    public function supplierEdit()
    {

        $suppliers = new SupplierModel();
        $json = $this->request->getJSON();
        $supplier_data = [
            "SupplierName" => $json->SupplierName,
            "ContactPerson" => $json->ContactPerson,
            "Email" => $json->Email,
            "PhoneNo" => $json->PhoneNo,
            "IsDeleted" => $json->IsDeleted,
            "supplier_address" => $json->supplier_address,
            "Website" => $json->Website,
            "billing_address" => $json->billing_address,
            "shipping_address" => $json->shipping_address,

        ];
        $suppliers->set($supplier_data)->where('Id', $json->Id)->update();
        $data = "paymant updated successfully";
        $message = [
            'message' => 'Supplier Updated Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }


    // get purchase orders by supplier id 
    public function get_activesupplier()
    {

        $supplier = new SupplierModel();
        // print_r($supplier);

        $suppliers = $supplier->where(['IsDeleted' => NULL])->findAll();
        $data = [];


        foreach ($suppliers as $list) {
            $data[] = [
                "id" => $list['Id'],
                "name" => $list['SupplierName'],
                "email" => $list['Email'],
                "mobile_number" => $list['MobileNo'],
                "contact_person" => $list['ContactPerson'],
                "created_at" => $list['created_at'],
                "status" => $list['IsDeleted'],
            ];
        }


        return $this->respond(['data' => $data]);
    }


    public function get_PObysupplier()
    {

        $purchaseOrder = new PurchaseOrderModel();
        $json = $this->request->getJSON();
        // print_r($supplier);

        $purchaseOrderData = $purchaseOrder->where(['SupplierId' => $json->supplierId])->where(['PurchaseReturnNote' => 0])->findAll();
        $data = [];

        // get purchase orders by supplier id 

        foreach ($purchaseOrderData as $list) {
            $data[] = [
                "id" => $list['Id'],
                "orderNumber" => $list['OrderNumber'],

                "created_at" => $list['created_at'],
                "status" => $list['IsDeleted'],
            ];
        }


        return $this->respond(['data' => $data]);
    }
    // get_PObypurchaseorderid

    public function get_PObypurchaseorderid()
    {
        $supplier = new SupplierModel();
        $purchaseOrder = new PurchaseOrderModel();
        $json = $this->request->getJSON();
        // print_r($supplier);

        $purchaseOrderData = $purchaseOrder->where(['Id' => $json->id])->first();
        $data = [];


        foreach ($purchaseOrderData as $list) {
            $supp_data = $supplier->where('Id', $list['SupplierId'])->first();

            $data[] = [
                "id" => $list['Id'],
                "orderNumber" => $list['OrderNumber'],
                "poCreatedDate" => $list['POCreatedDate'],
                "deliveryDate" => $list['DeliveryDate'],
                "supplierName " => $supp_data['SupplierName'],
                "totalAmount " => $supp_data['TotalAmount'],
                "totalTax " => $supp_data['TotalTax'],
                "totalDiscount " => $supp_data['TotalDiscount'],
                "totalPaidAmount " => $supp_data['TotalPaidAmount'],

                "created_at" => $list['created_at'],
                "status" => $list['IsDeleted'],
            ];
        }


        return $this->respond(['data' => $data]);
    }


    // purchaseorderreturn
    public function purchaseorderreturn()
    {
        $purchaseOrder = new PurchaseOrderModel();
        $purchaseOrderreturn = new PurchaseOrderReturnModel();
        $json = $this->request->getJSON();
        $podata = $purchaseOrder->where('id', $json->PurchaseOrderId)->first();
        $createreturnorder = array(
            "PurchaseOrderId" => $podata['PurchaseOrderId'],
            "ProductId" => $podata['ProductId'],
            "CreatedDate" => $podata['CreatedDate'],
            "DeliveryDate" => $podata['DeliveryDate'],
            "ReturnQuantity" => $json->ReturnQuantity,
            "Status" => 1
        );
        $purchaseOrderreturn->insert($createreturnorder);
        $purchaseOrder->set(['PurchaseReturnNote' => 1])->where('id', $purchaseOrderreturn)->update();
        return $this->respond(['data' => $purchaseOrderreturn]);
    }


    //  get products by category 

    public function get_productsbycategory()
    {
        try {
            $categoryId = $this->request->getGet('categoryId');

            $idsArray = array_map('intval', explode(',', $categoryId));

            $ProductcategoriesModels = new ProductcategoriesModel();

            $ProductcategoriesModels->whereIn('category_id', $idsArray);

            $wishlist = $ProductcategoriesModels->findAll();
            $product_id = [];
            if ($wishlist) {
                foreach ($wishlist as $list) {
                    $product_id[] = $list['product_id'];
                }
                $ProductsModels = new ProductsModel();
                $ProductsModels->whereIn('id', $product_id);
                $ProductsModels->where('is_published', 1);
                $ProductsModels->where('is_deleted', 0);
                $data = $ProductsModels->findAll();
                $resposneData = [];
                $ProductVariationsModel = new ProductVariationsModel();
                $mediaModel = new Media_managersModel();
                foreach ($data as $resposne) {
                    $mediaModel->where('id', $resposne['thumbnail_image']);
                    $imageUrl = $mediaModel->findAll();
                    $ProductsModelsdata = $ProductVariationsModel->find($resposne['id']);
                    $stock_status = (intval($resposne['stock_qty']) > 0) ? 'in_stock' : 'out_stock';
                    $resposneData[] = [
                        'id' => $resposne['id'],
                        'name' => $resposne['name'],
                        'slug' => $resposne['slug'],
                        'description' => $resposne['description'],
                        'category_image_id' => null,
                        'category_icon_id' => null,
                        'status' => 1,
                        'type' => "product",
                        'commission_rate' => null,
                        'parent_id' => null,
                        'created_by_id' => "1",
                        'created_at' => $resposne['created_at'],
                        'updated_at' => $resposne['updated_at'],
                        'deleted_at' => null,
                        'blogs_count' => 6,
                        'products_count' => 0,
                        'thumbnail_image' => $resposne['thumbnail_image'],
                        'thumbnail_image_url' => isset($imageUrl) ? $imageUrl[0]['media_file'] : null,
                        'category_icon' => null,
                        'subcategories' => [],
                        'parent' => null,
                        'short_description' => [],
                        'unit' => [],
                        'weight' => [],
                        'quantity' => [],
                        'sale_price' => $resposne['max_price'],
                        'discount' => $resposne['discount_value'],
                        'is_featured' => $resposne['is_featured'],
                        'shipping_days' => [],
                        'is_cod' => [],
                        'is_free_shipping' => [],
                        'is_sale_enable' => [],
                        'is_return' => [],
                        'is_trending' => [],
                        'is_approved' => [],
                        'sale_starts_at' => [],
                        'sale_expired_at' => [],
                        'sku' => [],
                        'is_random_related_products' => [],
                        'stock_status' => $stock_status,
                        'meta_title' => [],
                        'meta_description' => [],
                        'product_thumbnail_id' => [],
                        'product_meta_image_id' => [],
                        'size_chart_image_id' => [],
                        'estimated_delivery_text' => [],
                        'return_policy_text' => [],
                        'safe_checkout' => [],
                        'secure_checkout' => [],
                        'social_share' => [],
                        'encourage_order' => [],
                        'encourage_view' => [],
                        'store_id' => [],
                        'tax_id' => [],
                        'orders_count' => [],
                        'reviews_count' => [],
                        'can_review' => [],
                        'rating_count' => [],
                        'order_amount' => [],
                        'review_ratings' => [],
                        'related_product' => [],
                        'cross_sell_products' => [],
                        'product_thumbnail' => [],
                        'product_galleries' => [],
                        'product_meta_image' => [],
                        'reviews' => [],
                        'store' => [],
                        'store_logo' => [],
                        'store_cover' => [],
                        'vendor' => [],
                        'point' => [],
                        'wallet' => [],
                        'address' => [],
                        'vendor_wallet' => [],
                        'profile_image' => [],
                        'payment_account' => [],
                        'country' => [],
                        'state' => [],
                        'tax' => [],
                        'categories' => [],
                        'category_image' => [],
                        'tags' => [],
                        'attributes' => [],
                        'variations' => $ProductsModelsdata,
                    ];
                }
                $data = $resposneData;

                $message = [
                    'message' => 'Successfully Get Products Category!',
                    'status' => 200
                ];

                return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
            } else {
                $message = [
                    'message' => "No data found",
                    'status' => 200
                ];
                return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, "data" => []]);
            }
        } catch (\Exception $e) {
            // Handle the exception
            // You can log the error, return a specific error response, or perform any other appropriate action

            $message = [
                'message' => $e->getMessage(),
                'status' => 500
            ];
            return $this->response->setStatusCode(500)->setJSON(['messageobject' => $message]);
        }
    }


    // --------------------------supplier address-----------------------

    public function get_suppliersaddress()
    {

        $supplierAddress = new SupplierAddressesModel();


        $supplierData = $supplierAddress->findAll();
        $data = [];


        foreach ($supplierData as $list) {
            $data[] = [
                "id" => $list['id'],
                "supplier_id" => $list['supplier_id'],
                "country_id" => $list['country_id'],
                "state_id" => $list['state_id'],
                "city_id" => $list['city_id'],
                "address" => $list['address'],
                "warehouse_country_id" => $list['warehouse_country_id'],
                "warehouse_state_id" => $list['warehouse_state_id'],
                "warehouse_city_id" => $list['warehouse_city_id'],
                "warehouse_address" => $list['warehouse_address'],
                "created_at" => $list['created_at'],
                "status" => $list['is_active'],
            ];
        }


        return $this->respond(['data' => $data]);
    }


    public function supplierAddressStatus()
    {

        $supplierAddress = new SupplierAddressesModel();
        $json = $this->request->getJSON();
        $supplierAddress->set(['is_active' => $json->status])->where('id', $json->id)->update();
        $data = "paymant updated successfully";
        $message = [
            'message' => 'supplierAddressStatus successfully saved!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }






    public function supplierAddressCreate()
    {
        $supplierAddress = new SupplierAddressesModel();
        $json = $this->request->getJSON();
        $supplierAddress->insert($json);

        $data = "Supplier Address created successfully";
        return $this->respond(['data' => $data]);
    }





    public function supplierAddressDelete()
    {

        $supplierAddress = new SupplierAddressesModel();
        $json = $this->request->getJSON();
        $supplierAddress->delete($json->id);
        $data = "deleted successfully";
        $message = [
            'message' => 'Supplier Address Deleted Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }



    public function supplierAddressEdit()
    {

        $supplierAddress = new SupplierAddressesModel();
        $json = $this->request->getJSON();
        $supplier_data = [
            "country_id" => $json->country_id,
            "state_id" => $json->state_id,
            "city_id" => $json->city_id,
            "address" => $json->address,
            "warehouse_country_id" => $json->warehouse_country_id,
            "warehouse_state_id" => $json->warehouse_state_id,
            "warehouse_city_id" => $json->warehouse_city_id,
            "warehouse_address" => $json->warehouse_address,


        ];
        $supplierAddress->set($supplier_data)->where('id', $json->id)->update();
        $data = "paymant updated successfully";
        $message = [
            'message' => 'Supplier Address Updated Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }

    // ----------------------------------Category----------------------------------------
    public function get_category()
    {
        $categoryModel = new CategoriesModel();

        $categories = $categoryModel->orderBy("id", "desc")->findAll();
        $data = [];


        $mediaManagerModel = new Media_managersModel();

        foreach ($categories as $category) {

            $thumbnailImage = '';
            if (!empty($category['thumbnail_image'])) {
                $media = $mediaManagerModel->find($category['thumbnail_image']);
                if ($media) {
                    $thumbnailImage = $media['media_file'];
                }
            }


            $data[] = [
                "id" => $category['id'],
                "name" => $category['name'],
                "slug" => $category['slug'],
                "parent_id" => $category['parent_id'],
                "level" => $category['level'],
                "sorting_order_level" => $category['sorting_order_level'],
                "thumbnail_image" => $thumbnailImage,
                "thumbnail_image_id" => $category['thumbnail_image'],
                "icon" => $category['icon'],
                "description" => $category['description'],
            ];
        }

        return $this->respond(['data' => $data]);
    }




    public function categoryStatus()
    {

        $category = new CategoriesModel();
        $json = $this->request->getJSON();
        $category->set(['is_active' => $json->status])->where('id', $json->id)->update();
        $data = "paymant updated successfully";
        $message = [
            'message' => 'Category Status Change Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }


    public function categoryCreate()
    {
        $category = new CategoriesModel();
        $json = [
            "name" => $this->request->getVar("name"),
            "description" => $this->request->getVar("description"),
            "thumbnail_image" => $this->request->getVar("thumbnail_image"),
        ];
        // $json = $this->request->getJSON();


        $result5 = $category->where('name', $json["name"]);
        $check = $result5->get()->getNumRows();

        if ($check < 1) {

            $category->insert($json);

            $data = "Category Created Successfully";
            $message = [
                'message' => "Category Created Successfully",
                'status' => 200
            ];
            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);

        } else {
            $data = "Category Name Already Exists!";

            $message = [
                'message' => "Category Name Already Exists!",
                'status' => 400
            ];
            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message, 'data' => $data]);

        }
    }


    public function categoryDelete()
    {
        $category = new CategoriesModel();
        $json = $this->request->getJSON();
        $categoryId = $json->id;


        $categoryLocalizationModel = new CategoryLocalizationsModel();
        $categoryLocalizations = $categoryLocalizationModel->where('category_id', $categoryId)->findAll();


        foreach ($categoryLocalizations as $localization) {
            $categoryLocalizationModel->delete($localization['id']);
        }

        $category->delete($categoryId);

        $data = "Category deleted successfully";

        $message = [
            'message' => "Category Deleted Successfully",
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }


    public function categoryEdit()
    {

        $category = new CategoriesModel();
        $json = $this->request->getJSON();
        $category_data = [
            "name" => $json->name,
            "description" => $json->description,
            "thumbnail_image" => isset($json->thumbnail_image) ? $json->thumbnail_image : null,
        ];
        $result5 = $category->where('name', $json->name);
        $check = $result5->get()->getNumRows();

        $category->set($category_data)->where('id', $json->id)->update();

        $data = "Category updated successfully";
        $message = [
            'message' => "Category Updated Successfully",
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);


    }




    public function deliverymanStatus()
    {

        $order = new OrdersModel();
        $json = $this->request->getJSON();
        $updatedata = [
            "delivery_status" => $json->delivery_status,
        ];
        $order->set($updatedata)->where('id', $json->id)->update();
        $data = "paymant updated successfully";
        $message = [
            'message' => 'Deliveryman Status Change Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }

    // ---------------------------------------Stock----------------------------------------------
    public function stockUpdate()
    {

        $productVariation = new ProductVariationStocksModel();
        $ProductVariationsModels = new ProductVariationsModel();

        $product = new ProductsModel();
        $json = $this->request->getJSON();
        $var_data = [
            "stock_qty" => $json->stock_qty,
            "location_id" => $json->location_id,
            "min_stock_qty" => $json->min_stock_qty ? $json->min_stock_qty : null,

        ];

        $productVariation->set($var_data)->where('product_variation_id', $json->product_varitaion_id)->update();

        $stock_data = [
            "stock_qty" => $json->stock_qty,
            "min_stock_qty" => $json->min_stock_qty ? $json->min_stock_qty : null,
        ];

        $pro = $ProductVariationsModels->where('id', $json->product_varitaion_id)->first();


        $product->set($stock_data)->where('id', $pro['product_id'])->update();

        $message = [
            'message' => 'stock Updated Successfully',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
    }

    public function get_stocks()
    {
        $productvariationstocks = new ProductVariationStocksModel();
        $productmodel = new ProductsModel();
        $productvariationmodel = new ProductVariationsModel();
        $productname = $productvariationstocks->getStocks();
        return $this->respond(['data' => $productname]);
    }


    // ----------------------------------Roles----------------------------------

    public function get_roles()
    {

        $role = new RolesModel();


        $roles = $role->findAll();
        $data = [];


        foreach ($roles as $list) {
            $data[] = [
                "id" => $list['id'],
                "name" => $list['name'],
                "guard_name" => $list['guard_name'],
                "created_at" => $list['created_at'],
                "status" => $list['is_active'],
            ];
        }


        return $this->respond(['data' => $data]);
    }

    public function get_role_using_id_mocked()
    {
        $role = new RolesModel();
        $permissionModel = new PermissionsModel();
        $role_id = $this->request->getGet('role_id');

        $role_details = $role->where('id', $role_id)->first();

        $rolePermissonList = $role->query("SELECT * FROM `role_has_permissions` as role_permission,`permissions` as permission WHERE role_permission.role_id=$role_id AND role_permission.permission_id =permission.id")->getResult();

        //    $mockedPermissonList=[];
        //    foreach ($rolePermissonList as $permission) {
        //       $permission->child_permission=$permissionModel->where("parent_id",$permission->id)->findAll();
        //       array_push($mockedPermissonList,$permission);
        //     }
        $role_details["permissions"] = $rolePermissonList;
        return $this->respond(['data' => $role_details]);
    }

    public function get_role_using_id()
    {
        $role = new RolesModel();
        $permissionModel = new PermissionsModel();
        $role_id = $this->request->getGet('role_id');

        $role_details = $role->where('id', $role_id)->first();

        $rolePermissonList = $role->query("SELECT * FROM `role_has_permissions` as role_permission,`permissions` as permission WHERE role_permission.role_id=$role_id AND role_permission.permission_id =permission.id AND permission.level=1")->getResult();

        $mockedPermissonList = [];
        foreach ($rolePermissonList as $permission) {
            $permission->child_permission = $permissionModel->where("parent_id", $permission->id)->findAll();
            array_push($mockedPermissonList, $permission);
        }
        $role_details["permissions"] = $mockedPermissonList;
        return $this->respond(['data' => $role_details]);
    }

    public function get_permissions_list()
    {
        $permissionModel = new PermissionsModel();

        $permissonList = $permissionModel->where("level", 1)->findAll();

        $mockedPermissonList = [];
        foreach ($permissonList as $permission) {
            $permission["child_permission"] = $permissionModel->where("parent_id", $permission["id"])->findAll();
            array_push($mockedPermissonList, $permission);
        }

        return $this->respond(['data' => $mockedPermissonList]);
    }

    public function create_or_edit_role_permissions()
    {
        $role = new RolesModel();
        $permissionModel = new PermissionsModel();
        $roleHasPermissionModel = new RoleHasPermissionModel();
        $request_json = $this->request->getJSON();
        $param_role_id = $this->request->getGet('role_id');
        $role_id = isset($param_role_id) ? ($param_role_id === 'null') ? '' : $param_role_id : '';


        if ($role_id != '') {
            $exists_role = $role->where('id', $role_id)->first();

            $update_role = [
                "name" => isset($request_json->role_name) ? $request_json->role_name : $exists_role["name"],
                "guard_name" => isset($request_json->guard_name) ? $request_json->guard_name : $exists_role["guard_name"],
                "is_active" => isset($request_json->status) ? ($request_json->status == true) ? 1 : 0 : $exists_role["is_active"],
            ];

            $role->set($update_role)->where('id', $role_id)->update();
            $exists_list = $roleHasPermissionModel->where('role_id', $role_id)->findAll();

            foreach ($exists_list as $role) {
                $roleHasPermissionModel->where('role_id', $role["role_id"])->delete();
            }
            $message = [
                'message' => 'Role updated successfully!',
                'status' => 200
            ];
        } else {
            $role_insert_data = [
                "name" => $request_json->role_name,
                "guard_name" => $request_json->guard_name,
                "is_active" => ($request_json->status == true) ? 1 : 0
            ];

            $role_id = $role->insert($role_insert_data);
            $message = [
                'message' => 'Role created successfully',
                'status' => 200
            ];
        }

        foreach ($request_json->permission as $permission) {

            $insert_role_has = [
                "permission_id" => $permission->id,
                "role_id" => $role_id,
            ];

            $roleHasPermissionModel->insert($insert_role_has);
        }

        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
    }

    public function roleStatus()
    {

        $role = new RolesModel();
        $json = $this->request->getJSON();
        $role->set(['is_active' => $json->status])->where('id', $json->id)->update();
        $data = "paymant updated successfully";
        $message = [
            'message' => 'Role Status Change Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }


    public function roleCreate()
    {
        $role = new RolesModel();
        $json = $this->request->getJSON();

        $role->insert($json);

        $data = "Role created successfully";

        $messageobject = [
            'message' => "Role Created Successfully",
            'status' => 200
        ];

        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $messageobject, 'data' => $data]);
    }


    public function roleDelete()
    {

        $role = new RolesModel();
        $roleHasPermissionModel = new RoleHasPermissionModel();
        $json = $this->request->getJSON();
        $role->delete($json->id);
        $exists_list = $roleHasPermissionModel->where('role_id', $json->id)->findAll();

        foreach ($exists_list as $role) {
            $roleHasPermissionModel->where('role_id', $role["role_id"])->delete();
        }
        $data = "Role Deleted Successfully";

        $messageobject = [
            'message' => "Role Deleted Successfully",
            'status' => 200
        ];

        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $messageobject, 'data' => $data]);
    }



    public function roleEdit()
    {

        $role = new RolesModel();
        $json = $this->request->getJSON();
        $role_data = [
            "name" => $json->name,
            "is_active" => $json->is_active,
            "guard_name" => $json->guard_name
        ];

        $role->set($role_data)->where('id', $json->id)->update();
        $data = "Role updated successfully";

        $messageobject = [
            'message' => "Role Updated Successfully",
            'status' => 200
        ];

        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $messageobject, 'data' => $data]);
    }


    // purchase order 

    public function get_purchaseorder()
    {

        $purchase_order = new PurchaseOrderModel();
        $supplier = new SupplierModel();


        $datas = $purchase_order->orderBy('id', 'Desc')->findAll();
        $data = [];


        foreach ($datas as $list) {

            $supplier_data = $supplier->where('Id', $list['SupplierId'])->first();
            $data[] = [
                "id" => $list['Id'],
                "name" => $list['OrderNumber'],
                "note" => $list['Note'],
                "purchase_return" => $list['PurchaseReturnNote'],
                "supplier" => isset($supplier_data['SupplierName']) ? $supplier_data['SupplierName'] : null,
                "created_at" => $list['created_at'],
                "status" => $list['IsDeleted'],
                "POCreatedDate" => $list['POCreatedDate'],
                "DeliveryDate" => $list['DeliveryDate'],
                "TotalTax" => $list['TotalTax'],
                "TotalDiscount" => $list['TotalDiscount'],
                "quotation" => $list['quotation'],
                "TotalAmount" => $list['TotalAmount'],
            ];
        }


        return $this->respond(['data' => $data]);
    }

    public function purchaseOrderDelete()
    {

        $brands = new PurchaseOrderModel();
        $json = $this->request->getJSON();
        $brands->delete($json->Id);
        $data = "deleted successfully";
        $message = [
            'message' => 'Purchase Order Deleted Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }

    // saleorder 
    public function get_saleorder()
    {

        $sale_order = new SaleOrderModel();



        $datas = $sale_order->findAll();
        $data = [];


        foreach ($datas as $list) {


            $data[] = [
                "id" => $list['Id'],
                "order_number" => $list['OrderNumber'],
                "note" => $list['Note'],
                "sale_return" => $list['SaleReturnNote'],

                "created_at" => $list['SOCreatedDate'],
                "status" => $list['IsDeleted'],
            ];
        }


        return $this->respond(['data' => $data]);
    }

    public function userStatusChange()
    {

        $user = new UsersModel();
        $json = $this->request->getJSON();
        $user->set(['is_active' => $json->status])->where('id', $json->id)->update();
        $data = "paymant updated successfully";
        $message = [
            'message' => 'User Status Changed Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }
    //customer
    public function get_userData()
    {
        $user = new UsersModel();
        $user_data = $user->where('user_type', 'customer')->findAll();
        $user_address = new UserAddressesModel();
        $order = new Order_Groups_Model();
        $data = [];
        $add_data1 = "";
        $pincode = "";
        foreach ($user_data as $list) {
            $user_addressdata = $user_address->where('user_id', $list['id'])->findAll();
            $order_count = $order->where('user_id', $list['id'])->countAllResults();




            foreach ($user_addressdata as $add_data) {
                $add_data1 = $add_data['address'];
                $pincode = $add_data['pincode'];

            }



            $data[] = [

                "id" => $list['id'],
                "name" => $list['name'],
                "email" => $list['email'],
                "country_code" => $list['location_id'],
                "role_name" => $list['user_type'],
                "phone" => $list['phone'],
                "system_reserve" => "0",
                "status" => $list['is_active'],
                "created_by_id" => $list['created_by'],
                "email_verified_at" => $list['email_verified_at'],
                "created_at" => $list['created_at'],
                "updated_at" => $list['updated_at'],
                "deleted_at" => $list['deleted_at'],
                "orders_count" => $order_count,
                "point" => null,
                "wallet" => null,
                "address" => $user_addressdata,
                "vendor_wallet" => null,
                "payment_account" => null,
                "address_in_address" => $add_data1,
                "pin_code" => $pincode
            ];

        }
        return $this->respond(['data' => $data]);



    }

    public function get_adminuserData()
    {
        $user = new UsersModel();
        $user_data = $user->where('user_type !=', "customer")->findAll();
        $user_address = new UserAddressesModel();
        $order = new Order_Groups_Model();
        $role = new RolesModel();
        $location = new LocationsModel();


        $data = [];
        foreach ($user_data as $list) {
            $user_addressdata = $user_address->where('user_id', $list['id'])->findAll();
            $order_count = $order->where('user_id', $list['id'])->countAllResults();
            $role_data = $role->where('id', $list['role_id'])->first();
            // $location_data = $location->where('id', $list['id'])->first();

            $location_data = $location->select('name')->where('id', $list['location_id'])->first();

            $data[] = [

                "id" => $list['id'],
                "name" => $list['name'],
                "email" => $list['email'],
                "country_code" => $list['location_id'],
                "role_name" => $list['user_type'],
                "phone" => $list['phone'],
                "system_reserve" => "0",
                "status" => $list['is_active'],
                "created_by_id" => $list['created_by'],
                "email_verified_at" => $list['email_verified_at'],
                "created_at" => $list['created_at'],
                "updated_at" => $list['updated_at'],
                "deleted_at" => $list['deleted_at'],
                "orders_count" => $order_count,
                "point" => null,
                "wallet" => null,
                "address" => $user_addressdata,
                "vendor_wallet" => null,
                "payment_account" => null,
                "role" => $role_data,
                "location_id" => $location_data,

            ];
        }
        return $this->respond(['data' => $data]);
    }


    //**************************************************
    public function updateAdminUserData()
    {
        $request = service('request');
        $json = $request->getJSON();

        if (!$json) {
            return $this->fail('Invalid request format. Request body must be JSON format.', 400);
        }

        $userId = null;
        $updateData = [];

        if (isset($json->id)) {
            $userId = $json->id;

            $updateData = get_object_vars($json);
        }

        $updateData['dial_code'] = $json->country_code;
        $user = new UsersModel();
        $user_data = $user->where('user_type !=', "customer")->find($userId);

        if (!$user_data) {
            return $this->fail('User not found', 404);
        }

        $data = [
            "id" => $user_data['id'],
            "name" => $user_data['name'],
            "email" => $user_data['email'],
            "country_code" => $user_data['location_id'],
            "role_name" => $user_data['user_type'],
            "phone" => $user_data['phone'],
            "system_reserve" => "0",
            "status" => $user_data['is_active'],
            "created_by_id" => $user_data['created_by'],
            "email_verified_at" => $user_data['email_verified_at'],
            "created_at" => $user_data['created_at'],
            "updated_at" => $user_data['updated_at'],
            "deleted_at" => $user_data['deleted_at'],
        ];


        if (isset($json->status)) {
            $updateData['is_active'] = $json->status;
            $data['status'] = $json->status;
        }

        $user->update($userId, $updateData);

        if (isset($json->role->id)) {
            $role_id = $json->role->id;
            $role = new RolesModel();
            $role_data = $role->find($role_id);
            if (!$role_data) {
                return $this->fail('Role not found', 404);
            }
            $updateData['role_id'] = $role_id;
            $data['role'] = $role_data;
        }

        if (isset($json->location_id)) {
            $location_id = $json->location_id;
            $location_model = new LocationsModel();
            $location_data = $location_model->find($location_id);
            if (!$location_data) {
                return $this->fail('Location not found', 404);
            }
            $updateData['location_id'] = $location_id;
            $data['location'] = $location_data;
        }

        if (array_key_exists('newPassword', $updateData)) {
            $newPassword = $updateData['newPassword'];
            unset($updateData['newPassword']);
            $updateData['password'] = password_hash($newPassword, PASSWORD_BCRYPT);
        }



        $user->update($userId, $updateData);

        $user_data = $user->find($userId);

        $order = new Order_Groups_Model();
        $order_count = $order->where('user_id', $userId)->countAllResults();
        $data['orders_count'] = $order_count;

        $messageobject = [
            "message" => 'User Data Updated Successfully',
            'status' => 200
        ];

        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $messageobject]);
    }


    //*****************************************************************
    public function deleteAdminUserData()
    {
        $request = service('request');
        $json = $request->getJSON();

        if (!$json || !isset($json->id)) {
            return $this->fail('Invalid request format. Request body must be JSON format and contain an "id" field.', 400);
        }

        $id = $json->id;
        $user = new UsersModel();
        $user_data = $user->where('user_type !=', "customer")->find($id);

        if (!$user_data) {
            return $this->fail('User not found', 404);
        }

        $user->delete($id);

        return $this->respond(['message' => 'AdminUser deleted successfully'], 200);
    }

    //*****************************************************************

    public function AdminUpdtaeStatus($id)
    {

        try {
            $userModel = new UsersModel();

            $existingUser = $userModel->find($id);
            if (!$existingUser) {
                return $this->failNotFound('User not found');
            }

            $json = $this->request->getJSON();
            $isActive = $json->status;

            $data = [
                "is_active" => $isActive
            ];

            $updated = $userModel->update($id, $data);

            if ($updated) {
                $response = [
                    'id' => $id,
                    'messageobject' => [
                        'message' => 'Admin Status Updated Successfully',
                        'status' => 200
                    ]
                ];

                return $this->respond($response);
            } else {
                return $this->failServerError('Failed to update user status');
            }
        } catch (\Exception $e) {
            return $this->failServerError('Server Error');
        }
    }



    //*****************************************************************

    public function admin_Create()
    {
        $user = new UsersModel();
        $json = $this->request->getJSON();
        $slug = strtolower($json->name);
        $json->slug = $slug . "-" . $this->generateRandomString(4);
        $json->password = password_hash($json->password, PASSWORD_BCRYPT);
        $user->insert($json);
        // $data = "Admin created successfully";
        $data = [

            'messageobject' => [
                'message' => 'Admin Created Successfully',
                'status' => 200
            ]
        ];
        return $this->respond($data);
    }




    public function admin_Delete()
    {
        $user = new UsersModel();
        $json = $this->request->getJSON();
        $user->delete($json->id);
        $data = "Admin Deleted Successfully";
        $message = [
            'message' => 'Admin Deleted Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }




    public function admin_Edit()
    {

        $user = new UsersModel();
        $json = $this->request->getJSON();
        $Userdata = [
            "name" => $json->name,
            "email" => $json->email,
            "phone" => $json->phone,
            "location_id" => $json->location_id,
            "password" => $json->password,
            "role_id" => $json->role_id,
            "is_active" => $json->status
        ];
        $user->set($Userdata)->where('id', $json->id)->update();
        $data = "paymant updated successfully";
        $message = [
            'message' => 'Admin Updated Successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }

    public function getlocation()
    {
        $LocationsModels = new LocationsModel();
        $is_published = $this->request->getGet('is_published');
        $is_default = $this->request->getGet('is_default');

        if ($is_published) {
            $reponse = $LocationsModels->where('is_published', $is_published)->findAll();
        } else if ($is_default) {
            $reponse = $LocationsModels->where('is_default', $is_default)->findAll();

        } else {
            $reponse = $LocationsModels->findAll();

        }

        $message = [
            'message' => 'Location Successfully Fetch Data',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $reponse]);

    }

    public function locationbystock()
    {
        $ProductVariationStocksModels = new ProductVariationStocksModel();
        $loc_id = $this->request->getGet('location_id');
        $reponse = $ProductVariationStocksModels->getstock($loc_id);
        $message = [
            'message' => 'Location Stock Successfully Fetch Data',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $reponse]);

    }

    public function getStockbyproduct()
    {
        $ProductVariationStocksModels = new ProductVariationStocksModel();
        $loc_id = $this->request->getGet('location_id');
        $product_variations_id = $this->request->getGet('product_variations_id');


        $reponse = $ProductVariationStocksModels->where(['location_id' => $loc_id, 'product_variation_id' => $product_variations_id])->first();


        $message = [
            'message' => 'Stock Product Successfully Fetch Data',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $reponse]);

    }

    public function getCustomer()
    {
        try {
            $UsersModel = new UsersModel();
            $RolesModels = new RolesModel();

            $user_id = $this->request->getGet('user_id');
            if (isset($user_id)) {
                $UsersModel->where('id', $user_id);
                $result = $UsersModel->find();
                $userAddressModel = new UserAddressesModel();
                $CountriesModels = new CountriesModel();
                $StatesModels = new StatesModel();
                $CitiesModels = new CitiesModel();

                if ($result[0]['role_id'] != null) {
                    $role = $RolesModels->where('id', $result[0]['role_id'])->first();
                }
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
                    'country_code' => $result[0]['dial_code'],
                    'role' => isset($role) ? $role['name'] : "Admin",
                    'phone' => $result[0]['phone'],
                    'profile_image_id' => 1,
                    'system_reserve' => 1,
                    'status' => 0,
                    'created_by_id' => 1,
                    'email_verified_at' => $result[0]['email_or_otp_verified'],
                    'created_at' => null,
                    'updated_at' => null,
                    'orders_count' => 8,
                    'store' => null,
                    'point' => [],
                    'wallet' => [],
                    'address' => $userAddressDetails,
                    'vendor_wallet' => '',
                    'profile_image' => '',
                    'payment_account' => '',
                    'role_id' => $result[0]['role_id'],

                ];


                $data = $responsedata;


                $message = [
                    'message' => 'Customer Get Successfully!',
                    'status' => 200
                ];

                return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);

                // return $this->respond(['responsedata' => $responsedata, 200]);
            } else {

                $message = [
                    'message' => 'UserId Not Found',
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
    public function updateSetting()
    {
        $System_settingsModels = new System_settingsModel();
        $json = $this->request->getJSON();
        if (isset($json->key)) {
            try {
                $data = [
                    "entity" => $json->key,
                    "value" => isset($json->value) ? $json->value : null,
                    "image_id" => isset($json->image_id) ? $json->image_id : null,
                ];
                $System_settingsModels->set($data)->where('entity', $json->key)->update();
                $message = [
                    'message' => "Updated Setting Successfully",
                    'status' => 200
                ];
                return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
            } catch (\Exception $e) {
                $message = [
                    'message' => $e->getMessage(),
                    'status' => 500
                ];
                return $this->response->setStatusCode(500)->setJSON(['messageobject' => $message]);
            }

        } else {
            $message = [
                'message' => "Missing Some Values",
                'status' => 400
            ];
            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
        }
    }

    public function getSettings()
    {
        $System_settingsModels = new System_settingsModel();
        $MediaModel = new MediaModel();
        $data = $System_settingsModels->findAll();
        $mockedData = [];

        foreach ($data as $setting) {
            $image_details = $MediaModel->where('id', $setting['image_id'])->first();
            $mockedData[$setting["entity"]] = [
                "key" => $setting['entity'],
                "value" => $setting['value'],
                "image_data" => $image_details
            ];
        }
        $message = [
            'message' => 'Settings Get Successfully!',
            'status' => 200
        ];

        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $mockedData]);

    }
    public function getDeliveryman()
    {
        $UsersModels = new UsersModel();
        $userDetails = $UsersModels->where('user_type', 'deliveryman')->findAll();
        $message = [
            'message' => 'Deliveryman Successfully Fetch Data',
            'status' => 200
        ];

        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $userDetails]);
    }

    public function savedelivery()
    {
        $OrdersModels = new OrdersModel();

        $json = $this->request->getJSON();

        if (isset($json->deliveryman_id) && isset($json->order_id)) {
            $OrdersModels->set(['deliveryman_id' => $json->deliveryman_id])->where('id', $json->order_id)->update();

            $message = [
                'message' => 'Delivery Successfully Updated',
                'status' => 200
            ];

            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
        } else {
            $message = [
                'message' => "Some Data's Missing",
                'status' => 400
            ];

            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
        }

    }


    public function userCreate()
    {
        try {
            $userModel = new UsersModel();
            $json = $this->request->getJSON();

            $user = [
                'name' => isset($json->name) ? $json->name : null,
                'phone' => $json->phone,
                'email' => isset($json->email) ? $json->email : null,
                'address' => isset($json->address) ? $json->address : null,
            ];

            $insertedId = $userModel->insert($user);

            $message = [
                'message' => 'User Created Successfully',
                'status' => 200,
                'id' => $insertedId
            ];

            return $this->respond(['messageobject' => $message]);
        } catch (\Exception $e) {
            return $this->fail('Failed to save user', 500);
        }
    }



    public function searchUser()
    {
        try {
            $searchString = $this->request->getVar('searchString');

            if (empty($searchString)) {
                return $this->fail('Search string is required', 400);
            }

            $userModel = new UsersModel();
            $users = $userModel->like('name', $searchString)
                ->orLike('phone', $searchString)
                ->findAll();

            $response_object = [
                "data" => $users,
            ];

            return $this->respond($response_object);
        } catch (\Exception $e) {
            return $this->fail('Failed to search users', 500);
        }
    }




    public function getDeliveryMen()
    {

        $dm = new DeliveryMenModel();
        $location = new LocationsModel();

        $dm_list = $dm->findAll();
        $data = [];


        foreach ($dm_list as $list) {
            $location_data = $location->select('name')->where('id', $list['store_location'])->first();
            $location_data_id = $location->select('id')->where('id', $list['store_location'])->first();

            $data[] = [
                "id" => $list['id'],
                "name" => $list['name'],
                "email" => $list['email'],
                "location_id" => $location_data,
                "status" => $list['is_active'],
                "location_data_id" => $location_data_id,
                "phone" => $list['phone'],

            ];
        }


        return $this->respond(['data' => $data]);
    }



    public function DeliverymenStatusChange()
    {

        $dm_list = new DeliveryMenModel();
        $json = $this->request->getJSON();
        $dm_list->set(['is_active' => $json->status])->where('id', $json->id)->update();
        $data = "status updated successfully";
        $message = [
            'message' => 'status change successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }



    public function DeliveryMenCreate()
    {
        $dm = new DeliveryMenModel();
        $json = $this->request->getJSON();
        $slug = strtolower($json->name);
        //  $value = strtolower($json->value);

        //  $json->value = $value;
        $json->slug = $slug . "-" . $this->generateRandomString(4);
        $dm->insert($json);

        $message = [
            'message' => 'Delivery Men created successfully!',
            'status' => 200
        ];

        return $this->respond(['messageobject' => $message]);
    }


    public function DeliveryMenDelete()
    {

        $dm = new DeliveryMenModel();
        $json = $this->request->getJSON();
        $dm->delete($json->id);
        $data = "deleted successfully";
        $message = [
            'message' => 'deleted successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }


    public function DeliveryMenEdit()
    {

        $dm = new DeliveryMenModel();

        $json = $this->request->getJSON();

        $dm_data = [
            "name" => $json->name,
            "store_location" => $json->store_location,
            "phone" => $json->phone,
            "email" => $json->email,


        ];
        $dm->set($dm_data)->where('id', $json->id)->update();
        $data = "updated successfully";
        $message = [
            'message' => 'updated successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
    }

}