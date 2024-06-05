<?php


namespace App\Controllers\Api;

use App\Models\api\ProductModel;
use App\Models\api\UnitConversationsModel;
use App\Models\api\ProductCategoriesModel;
use App\Models\api\BrandsModel;
use CodeIgniter\RESTful\ResourceController;


use App\Models\Admin\ProductVariationsModel;
use App\Models\Admin\Media_managersModel;

class ProductCategorieController extends ResourceController
{

    public function index()
    {
        $ProductModel = new ProductModel();
        $UnitConversationsModels = new UnitConversationsModel();
        $ProductCategoriesModels = new ProductCategoriesModel();
        $BrandsModels = new BrandsModel();

        $data = $ProductModel->findAll();
        $transformedProducts = $this->transformProducts($data, $UnitConversationsModels, $ProductCategoriesModels, $BrandsModels);
        return $this->respond($transformedProducts);
    }

    protected function transformProducts($products, $UnitConversationsModels, $ProductCategoriesModels, $brandsModels)
    {
        $transformedProducts = [];
        foreach ($products as $product) {
            $UnitConversationsModel = $UnitConversationsModels->find($product['UnitId']);
            $ProductCategoriesModel = $ProductCategoriesModels->find($product['CategoryId']);
            $brandsModel = $brandsModels->find($product['BrandId']);
            $transformedProducts[] = [
                'Id' => $product['Id'],
                'Name' => $product['Name'],
                'Code' => $product['Code'],
                'Barcode' => $product['Barcode'],
                'SkuCode' => $product['SkuCode'],
                'SkuName' => $product['SkuName'],
                'Description' => $product['Description'],
                'ProductUrl' => $product['ProductUrl'],
                'QRCodeUrl' => $product['QRCodeUrl'],
                'UnitId' => $product['UnitId'],
                'Unit_name' => $UnitConversationsModel ? $UnitConversationsModel['Name'] : null,
                'PurchasePrice' => $product['PurchasePrice'],
                'SalesPrice' => $product['SalesPrice'],
                'Mrp' => $product['Mrp'],
                'CategoryId' => $product['CategoryId'],
                'CategoryName' => $ProductCategoriesModel ? $ProductCategoriesModel['Name'] : null,
                'BrandName' => $brandsModel ? $brandsModel['Name'] : null,
                'WarehouseId' => $product['WarehouseId'],
                'CreatedDate' => $product['CreatedDate'],
                'CreatedBy' => $product['CreatedBy'],
                'ModifiedDate' => $product['ModifiedDate'],
                'ModifiedBy' => $product['ModifiedBy'],
                'DeletedDate' => $product['DeletedDate'],
                'DeletedBy' => $product['DeletedBy'],
                'IsDeleted' => $product['IsDeleted']
            ];
        }

        return $transformedProducts;
    }

    public function list()
    {
        $ProductCategoriesModels = new ProductCategoriesModel();
        $ProductCategoriesModels->where('IsDeleted', 0);
        $data = $ProductCategoriesModels->find();

        $index = 1;
        foreach ($data as $dd) {

            $arr = array("a" => "furnishing", "b" => "fashions", "c" => "groceries", "d" => "farm-fresh-produce");

            $key = array_rand($arr);
            $offer_data = $arr[$key];
            $dataset['data'][] = [
                "id" => $index,
                "Ids" => $dd['Id'],
                "index" => $index,
                "name" => $dd['Name'],
                "slug" => $dd['Description'],
                "description" => "Furniture encompasses a wide range of functional and decorative items designed to enhance living and working spaces. It includes various pieces that serve practical purposes while contributing to the overall aesthetics and ambiance of a room or environment. Furniture is an essential aspect of interior design and plays a crucial role in creating comfortable and functional living, working, and recreational spaces.",
                "category_image_id" => null,
                "category_icon_id" => null,
                "status" => 1,
                "type" => "product",
                "commission_rate" => null,
                "parent_id" => null,
                "created_by_id" => "1",
                "created_at" => "2023-08-31T06:32:22.000000Z",
                "updated_at" => "2023-08-31T06:32:22.000000Z",
                "deleted_at" => null,
                "blogs_count" => 6,
                "products_count" => 0,
                "category_image" => $dd['category_image'],
                "category_icon" => null,
                "subcategories" => [],
                "parent" => null
            ];

            $index += 1;
        }
        return $this->respond($dataset);
    }

    public function cat_product()
    {
        $cat_id = $this->request->getGet('id');
        $ProductCategoriesModels = new ProductCategoriesModel();
        $joinCondition = 'ProductCategories.Id = Products.CategoryId';

        $results = $ProductCategoriesModels->select('Products.Id as product_id , Products.Name as product_name ,  ProductCategories.Id as cat_id, ProductCategories.Name as cat_name')
            ->join('Products', $joinCondition)
            ->where('Products.CategoryId', $cat_id)
            ->findAll();

        return $this->respond($results);
    }

    public function getCategorieslistProducts()
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
                $ProductsModels = new ProductModel();
                $ProductsModels->whereIn('id', $product_id);
                $ProductsModels->where('is_published', 1);
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
                    'message' => 'Success!',
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

            $message = [
                'message' => $e->getMessage(),
                'status' => 500
            ];
            return $this->response->setStatusCode(500)->setJSON(['messageobject' => $message]);
        }
    }  /// ================ GETPRODUCTS COMPLETE ================








}
