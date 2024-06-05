<?php namespace App\Controllers\Api;

use App\Models\api\ProductModel;
use App\Models\api\UnitConversationsModel;
use App\Models\api\ProductCategoriesModel;
use App\Models\api\InventoryModel;
use App\Models\api\BrandsModel;
use CodeIgniter\RESTful\ResourceController;
use App\Models\Admin\PurchaseOrderModel;
use App\Models\api\PurchaseOrderitems;
use App\Models\Admin\OrdersModel;
use App\Models\Admin\Order_Groups_Model;
use App\Models\Admin\Order_Items_Model;
use App\Models\Admin\ProductsModel;
use App\Models\Admin\ProductVariationsModel;
use App\Models\Admin\UsersModel;
use App\Models\Admin\UserAddressesModel;

class ProductController extends ResourceController
{
    public function index()
    {
        $productModel = new ProductModel();
        
        $UnitConversationsModels = new UnitConversationsModel();
        $ProductCategoriesModels = new ProductCategoriesModel();
        $InventoryModel = new InventoryModel();
        $BrandsModels = new BrandsModel();
        $cat_id = $this->request->getGet('cat_id');
        $prd_id = $this->request->getGet('prd_id');
        $prd_name_like = $this->request->getGet('prd_name_like');
        $prd_name = $this->request->getGet('prd_name');
        $limit = $this->request->getGet('limit');
        $offset = $this->request->getGet('offset');
        $limit = isset($limit) ? $limit : 20;
        $offset = isset($offset) ? $offset : 0;

        // $transformedProducts['product_total_count'] = $productModel->countAll();
        if (isset($cat_id)) {
            $productModel->where('CategoryId', $cat_id);
        }
        if (isset($prd_id)) {
            $productModel->where('Id', $prd_id);
        }
        if (isset($prd_name_like)) {
            $productModel->like('Name', $prd_name_like);
        }
        if (isset($prd_name)) {
            $productModel->where('Name', $prd_name);
        }
        // $transformedProducts['product_total_count'] = $productModel->countAllResults();
        $products = $productModel->limit($limit,$offset)->find();
        $transformedProducts['data'] = $this->transformProducts($products, $UnitConversationsModels,$ProductCategoriesModels,$BrandsModels);
        // $transformedProducts['product_result_count'] = count($transformedProducts['product']);

     
        return $this->respond($transformedProducts);
    }

    protected function transformProducts($products, $UnitConversationsModels,$ProductCategoriesModels,$brandsModels)
    {
        $transformedProducts =[];



        
        foreach ($products as $product) {
            $UnitConversationsModel = $UnitConversationsModels->find($product['UnitId']);
            $ProductCategoriesModel = $ProductCategoriesModels->find($product['CategoryId']);

            $brandsModel = $brandsModels->find($product['BrandId']);
            $transformedProducts[] = [
                
                    "id"=> $product['Id'],
                    "name"=>$product['Name'],
                    "short_description"=>$product['Description'],
                    "description"=> "<p><strong>Unleash Your Fitness Potential with Our Men's Gym Co-Ord Set</strong></p><p>Embarking on a fitness journey demands the right companion, and our Men's Gym Co-Ord Set is tailored to be just that. Crafted with your comfort at its core, this set is a testament to the perfect blend of style and functionality. Picture yourself in a gym ensemble that not only complements your active lifestyle but enhances your workout experience.</p><p>When we talk about the essence of this gym co-ord set, it starts with the fabric. We've meticulously chosen a high-quality breathable material that prioritizes air circulation. Ensuring you stay cool and dry during your most intense workouts, this fabric is designed to keep you focused on your fitness goals rather than discomfort.</p><p>In the realm of fitness, style isn't just an accessory – it's an expression. The sleek and modern design of this gym co-ord set is a testament to professionalism in every stitch. It's not just gym wear; it's a statement of your dedication to an active and healthy life. The set includes both a top and shorts, providing a coordinated look that exudes confidence.</p><p>Flexibility and freedom of movement are non-negotiable when it comes to workout wear. Our Men's Gym Co-Ord Set offers just that, allowing you to push your limits, stretch, and strive for more. The fabric is thoughtfully chosen to ensure not only comfort but also durability. It's a set that can endure the most rigorous workout sessions, giving you the confidence that your gym wear is up to the challenge.</p><p>Who is this set for? Well, everyone on the fitness spectrum. Whether you're a gym enthusiast or a casual fitness buff, this co-ord set is designed for all. Weightlifting, cardio, yoga, or any other fitness activity you're into – this set has got your back, quite literally. Its versatility makes it a must-have addition to your workout wardrobe, a go-to for any exercise regimen.</p><p>Investing in your fitness journey is investing in yourself. Elevate your workout experience with our Men's Gym Co-Ord Set, where comfort meets style, and functionality embraces fashion. Make a statement, take charge of your fitness goals, and let this exceptional gym co-ord set be your trusted ally. It's time to break a sweat and look great doing it!</p>",
                    "type"=> "simple",
                    "unit"=> "1 Item",
                    "weight"=> 178,
                    "quantity"=> 120,
                    "price"=> $product['Mrp'],
                    "sale_price"=> $product['SalesPrice'],
                    "discount"=> 10,
                    "is_featured"=> 0,
                    "shipping_days"=> null,
                    "is_cod"=> "0",
                    "is_free_shipping"=> 0,
                    "is_sale_enable"=> 1,
                    "is_return"=> 1,
                    "is_trending"=> 1,
                    "is_approved"=> 1,
                    "sale_starts_at"=> "2023-9-1",
                    "sale_expired_at"=> "2025-10-30",
                    "sku"=>  $product['SkuCode'],
                    "is_random_related_products"=> 1,
                    "stock_status"=> "in_stock",
                    "meta_title"=> "Men Gym Co-Ord Set",
                    "meta_description"=> "The breathable fabric used in our Men Gym Co-Ord Set allows for proper air circulation, keeping you cool and dry even during intense workouts.",
                    "product_thumbnail_id"=> 1263,
                    "product_meta_image_id"=> "1263",
                    "size_chart_image_id"=> 1437,
                    "estimated_delivery_text"=> "Expect your delivery between 5 and 7 days",
                    "return_policy_text"=> "Hassle free 7, 15 and 30 days return might be available.",
                    "safe_checkout"=> 1,
                    "secure_checkout"=> 0,
                    "social_share"=> 1,
                    "encourage_order"=> 1,
                    "encourage_view"=> 1,
                    "slug"=> "men-gym-co-ord-set",
                    "status"=> 1,
                    "store_id"=> 15,
                    "created_by_id"=> "19",
                    "tax_id"=> 1,
                    "deleted_at"=> $product['DeletedDate'],
                    "created_at"=> "2023-09-18T12=>42=>13.000000Z",
                    "updated_at"=> "2023-09-30T04=>24=>25.000000Z",
                    "orders_count"=> 2,
                    "reviews_count"=> 5,
                    "can_review"=> true,
                    "rating_count"=> 3.4,
                    "order_amount"=> 136.22,
                    "review_ratings"=> [
                      1,
                      1,
                      0,
                      1,
                      2
                    ],
                    "related_products"=> [
                      160,
                      153,
                      161,
                      158,
                      167,
                      168
                    ],
                    "cross_sell_products"=> [],
                    "product_thumbnail"=>$product['ProductUrl'],
          
                    "product_galleries"=> [
                   
                    ],
                    "product_meta_image"=>[],
                   
                    "reviews"=> [],
                    "store"=>[],
                    "tax"=>[], 
                    

                    "categories"=>$ProductCategoriesModel ,
                   
                    "tags"=> [
                     
                    ],
                    "attributes"=> [],
                    "variations"=> []
                  
            ];
        }

        return $transformedProducts;
    }

    public function getAllPurchaseorders()
    {    
       
        $purchaseorder = new PurchaseOrderModel();
           
        $brand = $purchaseorder->findAll();
        $data = [];
       
        foreach($brand as $list){
            $data[] = [
                "Id"=>$list['Id'],
           "OrderNumber" =>$list['OrderNumber'],
           "Note" =>$list['Note'],
           "quotation" =>$list['quotation'],
           "PurchaseReturnNote" =>$list['PurchaseReturnNote'],
           'IsPurchaseOrderRequest' =>$list['IsPurchaseOrderRequest'],
           'TermAndCondition' =>$list['TermAndCondition'],
           'POCreatedDate' =>$list['POCreatedDate'],
           'Status' =>$list['Status'],
           'DeliveryDate' =>$list['DeliveryDate'],
           'DeliveryStatus' =>$list['DeliveryStatus'],   
           "SupplierId" =>$list['SupplierId'],     
           "TotalAmount" =>$list['TotalAmount'],
           "TotalTax" =>$list['TotalTax'],
           "TotalDiscount" =>$list['TotalDiscount'],
           "TotalPaidAmount" =>$list['TotalPaidAmount'],
           "PaymentStatus" =>$list['PaymentStatus'],
           "CreatedDate" =>$list['CreatedDate'],
           "CreatedBy" =>$list['CreatedBy'],
           "ModifiedDate" =>$list['ModifiedDate'],
           "ModifiedBy" =>$list['ModifiedBy'],
           "DeletedDate" =>$list['DeletedDate'],
           "DeletedBy" =>$list['DeletedBy'],
           "IsDeleted" =>$list['IsDeleted'],
           "created_at" =>$list['created_at'],
           "updated_at" =>$list['updated_at'],
           "deleted_at"  =>$list['deleted_at'],
            ];
        }
        $message = [
            'message' => 'Successfully fetch a data'
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, "data"=>$data]);
    }

    public function getPurchaseorderItem()
    {
        $purchaseorderitems = new PurchaseOrderitems;
        $json = $this->request->getJSON();
        $id = $json->id;

        $result2 = $purchaseorderitems->where('Id', $id);
        $data['getdata'] = $result2->findAll();
        
        $reponse=[
            "total purchase order items" => $data['getdata'],
        ];
        $message = [
            'message' => 'Successfully fetched'
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $reponse]);

    }

    public function PurchaseOrderUpdate()
    {    
        $purchaseorder = new PurchaseOrderModel();
        $purchaseorderitems = new PurchaseOrderitems();
        $json = $this->request->getJSON();
        $quotation = isset($json->quotation) ? $json->quotation : null;
        $OrderNumber = isset($json->OrderNumber) ? $json->OrderNumber : null;
        $CreatedDate = isset($json->CreatedDate) ? $json->CreatedDate : null;
        $DeliveryDate = isset($json->DeliveryDate) ? $json->DeliveryDate : null;
        $TermAndCondition = isset($json->TermAndCondition) ? $json->TermAndCondition : null;
        $UpdatedBy = isset($json->UpdatedBy) ? $json->UpdatedBy : null;
        $id = isset($json->id) ? $json->id : null;
        $createorder =array(
            "quotation" =>$quotation,
            "OrderNumber" =>$OrderNumber,
            "CreatedDate" =>$CreatedDate,
            "DeliveryDate" =>$DeliveryDate,
            "TermAndCondition" =>$TermAndCondition,
            "UpdatedBy"=>$UpdatedBy
        );
        $purchaseorder->set($createorder)->where('id', $id)->update();       

       $productdata = isset($json->Products) ? $json->Products : null;
     
       foreach($productdata as $array){
        $productdata33 = isset($array->id) ? $array->id : null;
       if($productdata33 != null){
        $update=[
            "ProductId" => $array->ProductId,
            "WarehouseId" => $array->WarehouseId,
            "UnitId" => $array->UnitId,
            "price" => $array->price,
            "Quantity" => $array->Quantity,
            "sub_total_before_discount" => $array->sub_total_before_discount,
            "sub_total_after_discount" => $array->sub_total_after_discount,
            "Discount" => $array->Discount,
            "TaxValue" => $array->TaxValue,
           
        ];
        $purchaseorderitems->set($update)->where('id', $id)->update();

        }
        else{
            $update=[
                "ProductId" => $array->ProductId,
                "WarehouseId" => $array->WarehouseId,
                "UnitId" => $array->UnitId,
                "price" => $array->price,
                "Quantity" => $array->Quantity,
                "sub_total_before_discount" => $array->sub_total_before_discount,
                "sub_total_after_discount" => $array->sub_total_after_discount,
                "Discount" => $array->Discount,
                "TaxValue" => $array->TaxValue,
                "PurchaseOrderId" => $id,
          
            ];
        $purchaseorderitems->insert($update);

        }
       }
        $message = [
            'message' => 'Successfully update a data'
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
    }

    public function GetOrdersItem(){

        $orderstable = new OrdersModel;        
        $ordergrouptable = new Order_Groups_Model;        
        $orderitemstable = new Order_Items_Model;        
        $productstbale = new ProductsModel;        
        $productsvariationtable = new ProductVariationsModel;        
        $userstable = new UsersModel;        
        $useraddresstable = new UserAddressesModel;   
        
        $json = $this->request->getJSON();
        $id = isset($json->id) ? $json->id : null;

        $Ordersdata =  $orderstable->where('id', $id)->first();

        $Ordersdata['ordergroup'] =  $ordergrouptable->where('id', $Ordersdata['order_group_id'])->first();

        // $Ordersdata['order_items'] = $orderitemstable->where('order_id', $Ordersdata['id'])->findAll();
        $Ordersdata['order_items'] = $orderitemstable->getProductName($Ordersdata['id']);
        
        $ordersdata['user'] =  $userstable->where('id',$Ordersdata['user_id'])->first();
        
        $Ordersdata['shipping_addressDetails'] =  $useraddresstable->where('id',$Ordersdata['ordergroup']['shipping_address_id'])->first();

        $Ordersdata['billing_addressDetails'] =  $useraddresstable->where('id',$Ordersdata['ordergroup']['billing_address_id'])->first();
    

        $reponse = [
            "Ordersdata" => $Ordersdata
        ];
        $message = [
            'message' => 'Successfully fetched data'
        ];

        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $reponse]);

    }

 
    
}
