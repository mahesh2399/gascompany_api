<?php
namespace App\Controllers\Admin;

use App\Models\api\ProductModel;
use App\Models\api\UnitConversationsModel;
use App\Models\api\ProductCategoriesModel;
use App\Models\api\InventoryModel;
use App\Models\api\BrandsModel;
use CodeIgniter\RESTful\ResourceController;
use App\Models\Admin\PurchaseOrderModel;
use App\Models\api\PurchaseOrderitems;
use App\Models\Admin\PurchaseOrderPaymentModel;
use App\Models\Admin\SalesOrderPaymentModel;
use App\Models\Admin\SaleOrderModel;
use App\Models\Admin\SaleOrderItemModel;
use App\Models\Admin\OrdersModel;
use App\Models\Admin\Order_Groups_Model;
use App\Models\Admin\Order_Items_Model;
use App\Models\Admin\ProductsModel;
use App\Models\Admin\ProductVariationsModel;
use App\Models\Admin\UsersModel;
use App\Models\Admin\UserAddressesModel;
use App\Models\Admin\CitiesModel;
use App\Models\Admin\UnitsModel;
use App\Models\Admin\CountriesModel;
use App\Models\Admin\StatesModel;
use App\Models\Admin\MediaModel;
use App\Models\Admin\ProductTaxesModel;
use App\Models\Admin\CategoriesModel;
use App\Models\Admin\SupplierModel;
use App\Models\Admin\PurchaseOrderReturnModel;
use App\Models\Admin\PurchaseorderItemsReturn;
use App\Models\Admin\TaxModel;
use App\Models\Admin\LocationsModel;
use App\Models\Admin\Media_managersModel;

class ProductController extends ResourceController
{
    public function index()
    {
        $productModel = new ProductModel();

        $UnitConversationsModels = new UnitConversationsModel();
        $ProductCategoriesModels = new ProductCategoriesModel();
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
        $products = $productModel->limit($limit, $offset)->find();
        $transformedProducts['data'] = $this->transformProducts($products, $UnitConversationsModels, $ProductCategoriesModels, $BrandsModels);
        // $transformedProducts['product_result_count'] = count($transformedProducts['product']);


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
                "id" => $product['Id'],
                "name" => $product['Name'],
                "short_description" => $product['Description'],
                "description" => "<p><strong>Unleash Your Fitness Potential with Our Men's Gym Co-Ord Set</strong></p><p>Embarking on a fitness journey demands the right companion, and our Men's Gym Co-Ord Set is tailored to be just that. Crafted with your comfort at its core, this set is a testament to the perfect blend of style and functionality. Picture yourself in a gym ensemble that not only complements your active lifestyle but enhances your workout experience.</p><p>When we talk about the essence of this gym co-ord set, it starts with the fabric. We've meticulously chosen a high-quality breathable material that prioritizes air circulation. Ensuring you stay cool and dry during your most intense workouts, this fabric is designed to keep you focused on your fitness goals rather than discomfort.</p><p>In the realm of fitness, style isn't just an accessory – it's an expression. The sleek and modern design of this gym co-ord set is a testament to professionalism in every stitch. It's not just gym wear; it's a statement of your dedication to an active and healthy life. The set includes both a top and shorts, providing a coordinated look that exudes confidence.</p><p>Flexibility and freedom of movement are non-negotiable when it comes to workout wear. Our Men's Gym Co-Ord Set offers just that, allowing you to push your limits, stretch, and strive for more. The fabric is thoughtfully chosen to ensure not only comfort but also durability. It's a set that can endure the most rigorous workout sessions, giving you the confidence that your gym wear is up to the challenge.</p><p>Who is this set for? Well, everyone on the fitness spectrum. Whether you're a gym enthusiast or a casual fitness buff, this co-ord set is designed for all. Weightlifting, cardio, yoga, or any other fitness activity you're into – this set has got your back, quite literally. Its versatility makes it a must-have addition to your workout wardrobe, a go-to for any exercise regimen.</p><p>Investing in your fitness journey is investing in yourself. Elevate your workout experience with our Men's Gym Co-Ord Set, where comfort meets style, and functionality embraces fashion. Make a statement, take charge of your fitness goals, and let this exceptional gym co-ord set be your trusted ally. It's time to break a sweat and look great doing it!</p>",
                "type" => "simple",
                "unit" => "1 Item",
                "weight" => 178,
                "quantity" => 120,
                "price" => $product['Mrp'],
                "sale_price" => $product['SalesPrice'],
                "discount" => 10,
                "is_featured" => 0,
                "shipping_days" => null,
                "is_cod" => "0",
                "is_free_shipping" => 0,
                "is_sale_enable" => 1,
                "is_return" => 1,
                "is_trending" => 1,
                "is_approved" => 1,
                "sale_starts_at" => "2023-9-1",
                "sale_expired_at" => "2025-10-30",
                "sku" => $product['SkuCode'],
                "is_random_related_products" => 1,
                "stock_status" => "in_stock",
                "meta_title" => "Men Gym Co-Ord Set",
                "meta_description" => "The breathable fabric used in our Men Gym Co-Ord Set allows for proper air circulation, keeping you cool and dry even during intense workouts.",
                "product_thumbnail_id" => 1263,
                "product_meta_image_id" => "1263",
                "size_chart_image_id" => 1437,
                "estimated_delivery_text" => "Expect your delivery between 5 and 7 days",
                "return_policy_text" => "Hassle free 7, 15 and 30 days return might be available.",
                "safe_checkout" => 1,
                "secure_checkout" => 0,
                "social_share" => 1,
                "encourage_order" => 1,
                "encourage_view" => 1,
                "slug" => "men-gym-co-ord-set",
                "status" => 1,
                "store_id" => 15,
                "created_by_id" => "19",
                "tax_id" => 1,
                "deleted_at" => $product['DeletedDate'],
                "created_at" => "2023-09-18T12=>42=>13.000000Z",
                "updated_at" => "2023-09-30T04=>24=>25.000000Z",
                "orders_count" => 2,
                "reviews_count" => 5,
                "can_review" => true,
                "rating_count" => 3.4,
                "order_amount" => 136.22,
                "review_ratings" => [
                    1,
                    1,
                    0,
                    1,
                    2
                ],
                "related_products" => [
                    160,
                    153,
                    161,
                    158,
                    167,
                    168
                ],
                "cross_sell_products" => [],
                "product_thumbnail" => $product['ProductUrl'],

                "product_galleries" => [

                ],
                "product_meta_image" => [],

                "reviews" => [],
                "store" => [],

                "tax" => [],


                "categories" => $ProductCategoriesModel,

                "tags" => [

                ],
                "attributes" => [],
                "variations" => []

            ];
        }

        return $transformedProducts;
    }

    // ============= GETSALEORDR ================
    public function getsaleorder()
    {

        $SaleOrderModels = new SaleorderModel;
        $requestMethod = $this->request->getMethod();
        if ($requestMethod !== 'get') {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request method']);
        } else {

            $headers = $this->request->getHeaders();


            if (!isset($headers['AUTHORIZATION'])) {
                $orderNumber = 'SO#00001';

                if (isset($orderNumber)) {
                    $result = $SaleOrderModels->getSaleOrderData($orderNumber);
                    return $this->response->setStatusCode(200)->setJSON(['mesaage' => 'Data fetched successfully', 'ResponseData' => $result]);
                } else {
                    return $this->response->setStatusCode(401)->setJSON(['error' => 'Invaild Order Id']);

                }
            } else {
                return $this->response->setStatusCode(401)->setJSON(['error' => 'Authorization header is missing']);

            }
        }

    }


    public function SOpaymnet()
    {
        $saleseorderpayment = new SalesOrderPaymentModel();
        $saleorder = new SaleOrderModel();

        $json = $this->request->getJSON();
        $refNum = isset($json->refNum) ? $json->refNum : null;
        $Amount = isset($json->Amount) ? $json->Amount : null;
        $paymentDate = isset($json->paymentDate) ? $json->paymentDate : null;
        $paymentMethod = isset($json->paymentMethod) ? $json->paymentMethod : null;
        $note = isset($json->note) ? $json->note : null;
        $soId = isset($json->soId) ? $json->soId : null;
        $CreatedBy = isset($json->CreatedBy) ? $json->CreatedBy : null;

        $createpayment = array(
            "ReferenceNumber" => $refNum,
            "Amount" => $Amount,
            "PaymentDate" => $paymentDate,
            "PaymentMethod" => $paymentMethod,
            "Note" => $note,
            "SalesOrderId" => $soId,
            "CreatedBy" => $CreatedBy

        );

        $createsorder = [
            "TotalPaidAmount" => $Amount,
        ];


        $saleseorderpayment->insert($createpayment);
        $saleorder->set($createsorder)->where('Id', $soId)->update();

        $data = $saleorder->where('Id', $soId)->findAll();
        if ($data[0]['TotalPaidAmount'] == $data[0]['TotalAmount']) {
            $updatepaymentstatus = [
                "PaymentStatus" => 1,
            ];
            $saleorder->set($updatepaymentstatus)->where('Id', $soId)->update();
        }

        $message = [
            'message' => 'Payment Successfully Updated'
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
    }


    public function getSOpayment()
    {
        $saleseorderpayment = new SalesOrderPaymentModel();
        $json = $this->request->getJSON();
        $SalesOrderId = $json->SalesOrderId;
        $data = $saleseorderpayment->where('SalesOrderId', $SalesOrderId)->findAll();
        return $this->response->setStatusCode(200)->setJSON(['sopaymentdata' => $data]);

    }

    public function getPurchaseorders()
    {

        $purchaseorder = new PurchaseOrderModel();

        $brand = $purchaseorder->findAll();
        $data = [];

        foreach ($brand as $list) {
            $data[] = [
                "Id" => $list['Id'],
                "OrderNumber" => $list['OrderNumber'],
                "Note" => $list['Note'],
                "quotation" => $list['quotation'],
                "PurchaseReturnNote" => $list['PurchaseReturnNote'],
                'IsPurchaseOrderRequest' => $list['IsPurchaseOrderRequest'],
                'TermAndCondition' => $list['TermAndCondition'],
                'POCreatedDate' => $list['POCreatedDate'],
                'Status' => $list['Status'],
                'DeliveryDate' => $list['DeliveryDate'],
                'DeliveryStatus' => $list['DeliveryStatus'],
                "SupplierId" => $list['SupplierId'],
                "TotalAmount" => $list['TotalAmount'],
                "TotalTax" => $list['TotalTax'],
                "TotalDiscount" => $list['TotalDiscount'],
                "TotalPaidAmount" => $list['TotalPaidAmount'],
                "PaymentStatus" => $list['PaymentStatus'],
                "CreatedDate" => $list['CreatedDate'],
                "CreatedBy" => $list['CreatedBy'],
                "ModifiedDate" => $list['ModifiedDate'],
                "ModifiedBy" => $list['ModifiedBy'],
                "DeletedDate" => $list['DeletedDate'],
                "DeletedBy" => $list['DeletedBy'],
                "IsDeleted" => $list['IsDeleted'],
                "created_at" => $list['created_at'],
                "updated_at" => $list['updated_at'],
                "deleted_at" => $list['deleted_at'],
            ];
        }
        $message = [
            'message' => 'Data Retrieved Successfully'
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, "data" => $data]);
    }

    public function getPurchaseorderItem()
    {
        $purchaseorder = new PurchaseOrderModel();
        $unitsModel = new UnitsModel();
        $purchaseorderitems = new PurchaseOrderitems;
        $ProductsModels = new ProductModel();
        $SupplierModels = new SupplierModel();
        $TaxModels = new TaxModel();
        $json = $this->request->getJSON();
        $id = $json->id;

        $purchaseorders = $purchaseorder->where('Id', $id)->first();
        $result2 = $purchaseorderitems->where('PurchaseOrderId', $purchaseorders['Id'])->findAll();

        $purchaseorders["supplier_details"] = $SupplierModels->where('Id', $purchaseorders['SupplierId'])->first();

        $orderlistarr = [];

        foreach ($result2 as $list) {
            $productlist = $ProductsModels->where('id', $list['ProductId'])->first();
            $productlist["unitDetails"] = $unitsModel->where('id', $list['UnitId'])->first();
            $productlist["tax_details"] = $TaxModels->where('id', $list['taxid'])->first();
            $list['products'] = $productlist;
            $orderlistarr[] = $list;
        }

        $purchaseorders['purchaseorderitems'] = $orderlistarr;
        $message = [
            'message' => 'Successfully Fetched Purchase Order Item Data'
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $purchaseorders]);

    }


    public function getpurchaseorderbyId()
    {
        $purchaseorder = new PurchaseOrderModel();
        $unitsModel = new UnitsModel();
        $purchaseorderitems = new PurchaseOrderitems;
        $ProductsModels = new ProductModel();
        $SupplierModels = new SupplierModel();
        $id = $this->request->getGet('id');


        $purchaseorders = $purchaseorder->where('Id', $id)->first();
        $result2 = $purchaseorderitems->where('PurchaseOrderId', $purchaseorders['Id']);
        $purchaseorders["supplier_details"] = $SupplierModels->where('Id', $purchaseorders['SupplierId'])->first();
        $purchaseorders['purchaseorderitems'] = $result2->findAll();
        $orderlistarr = [];

        foreach ($purchaseorders['purchaseorderitems'] as $list) {
            $productlist = $ProductsModels->where('id', $list['ProductId'])->first();
            $productlist["unitDetails"] = $unitsModel->where('id', $list['UnitId'])->first();
            $list['products'] = $productlist;
            $orderlistarr[] = $list;
        }

        $purchaseorders['purchaseorderitems'] = $orderlistarr;
        $message = [
            'message' => 'Successfully Fetched'
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $purchaseorders]);

    }

    public function getpurcharorderreturn()
    {

        $orderreturn = new PurchaseOrderReturnModel();
        $orderitemreturn = new PurchaseorderItemsReturn();
        $ProductsModels = new ProductModel();
        $PurchaseOrderModel = new PurchaseOrderModel();
        $unitsModel = new UnitsModel();
        $supplier = new SupplierModel();


        $id = $this->request->getGet('id');
        $returnorderdata = $orderreturn->where('Id', $id)->first();


        $returnorderdata['orderitemreturndata'] = $orderitemreturn->where('returnPurchaseOrderId', $returnorderdata['Id'])->findAll();
        $returnorderdata['supplierdata'] = $supplier->where('id', $returnorderdata['SupplierId'])->first();
        $returnorderdata['purchaseorderdata'] = $PurchaseOrderModel->where('id', $returnorderdata['PurchaseOrderId'])->first();
        $orderlistarr = [];
        foreach ($returnorderdata['orderitemreturndata'] as $itemreturn) {
            $returnorderdata['productdata'] = $ProductsModels->where('id', $itemreturn['returnProductId'])->first();
            $returnorderdata['unitdata'] = $unitsModel->where('id', $itemreturn['returnUnitId'])->first();
            $itemreturn['productdata'] = $ProductsModels->where('id', $itemreturn['returnProductId'])->first();
            $itemreturn['unitdata'] = $unitsModel->where('id', $itemreturn['returnUnitId'])->first();
            $orderlistarr[] = $itemreturn;
        }
        $returnorderdata['orderitemreturndata'] = $orderlistarr;

        $message = [
            'message' => 'Successfully Fetched'
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $returnorderdata]);

    }


    public function getsaleorderbyId()
    {
        $saleorder = new SaleOrderModel();
        $unitsModel = new UnitsModel();
        $saleorderitems = new SaleOrderItemModel;
        $ProductsModels = new ProductModel();
        $OrdersItemModels = new Order_Items_Model();
        $productvariation = new ProductVariationsModel();
        $userstable = new UsersModel;
        $id = $this->request->getGet('id');

        $saleorders = $saleorder->where('Id', $id)->first();
        $saleorders['customer_details'] = $userstable->where('id', $saleorders['CustomerId'])->first();
        $result2 = $OrdersItemModels->where('order_id', $saleorders['order_id']);
        $saleorders['saleorderitems'] = $result2->findAll();
        $orderlistarr = [];

        foreach ($saleorders['saleorderitems'] as $list) {
            $productvariations = $productvariation->where('id', $list['product_variation_id'])->first();
            $productlist = $ProductsModels->where('id', $productvariations['product_id'])->first();
            $productlist["unitDetails"] = $unitsModel->where('id', $productlist['unit_id'])->first();
            $list['products'] = $productlist;
            $orderlistarr[] = $list;
        }

        $saleorders['saleorderitems'] = $orderlistarr;
        $message = [
            'message' => 'Successfully Fetched'
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $saleorders]);

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
        $SupplierId = isset($json->SupplierId) ? $json->SupplierId : null;
        $UpdatedBy = isset($json->UpdatedBy) ? $json->UpdatedBy : null;
        $id = isset($json->id) ? $json->id : null;
        $createorder = array(
            "quotation" => $quotation,
            "OrderNumber" => $OrderNumber,
            "CreatedDate" => $CreatedDate,
            "DeliveryDate" => $DeliveryDate,
            "TermAndCondition" => $TermAndCondition,
            "SupplierId" => $SupplierId,
            "UpdatedBy" => $UpdatedBy
        );
        $purchaseorder->set($createorder)->where('id', $id)->update();

        $productdata = isset($json->Products) ? $json->Products : null;

        foreach ($productdata as $array) {
            $productdata33 = isset($array->id) ? $array->id : null;

            if ($productdata33 != null) {
                $update = [
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

            } else {
                $update = [
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
            'message' => 'Purchase Order And Items Successfully Updated'
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
    }



    public function POpaymnet()
    {
        $purchaseorderpayment = new PurchaseOrderPaymentModel();
        $purchaseorder = new PurchaseOrderModel();

        $json = $this->request->getJSON();
        $refNum = isset($json->refNum) ? $json->refNum : null;
        $Amount = isset($json->Amount) ? $json->Amount : null;
        $paymentDate = isset($json->paymentDate) ? $json->paymentDate : null;
        $paymentMethod = isset($json->paymentMethod) ? $json->paymentMethod : null;
        $note = isset($json->note) ? $json->note : null;
        $poId = isset($json->poId) ? $json->poId : null;
        $CreatedBy = isset($json->CreatedBy) ? $json->CreatedBy : null;

        $createpayment = array(
            "ReferenceNumber" => $refNum,
            "Amount" => $Amount,
            "PaymentDate" => $paymentDate,
            "PaymentMethod" => $paymentMethod,
            "Note" => $note,
            "PurchaseOrderId" => $poId,
            "CreatedBy" => $CreatedBy

        );

        $createporder = [
            "TotalPaidAmount" => $Amount,
        ];


        $purchaseorderpayment->insert($createpayment);
        $purchaseorder->set($createporder)->where('Id', $poId)->update();


        $data = $purchaseorder->where('Id', $poId)->findAll();
        if ($data[0]['TotalPaidAmount'] == $data[0]['TotalAmount']) {
            $updatepaymentstatus = [
                "PaymentStatus" => 1,
            ];
            $purchaseorder->set($updatepaymentstatus)->where('Id', $poId)->update();
        }

        $message = [
            'message' => 'Payment Successfully Updated For Purchase Order'
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
    }


    public function getPOpayment()
    {
        $purchaseorderpayment = new PurchaseOrderPaymentModel();
        $json = $this->request->getJSON();
        $PurchaseOrderId = $json->PurchaseOrderId;
        $data = $purchaseorderpayment->where('PurchaseOrderId', $PurchaseOrderId)->findAll();
        return $this->response->setStatusCode(200)->setJSON(['popaymentdata' => $data]);

    }

    public function PurchaseOrderCreate()
    {
        $purchaseorder = new PurchaseOrderModel();
        $purchaseorderitems = new PurchaseOrderitems();
        $json = $this->request->getJSON();

        // Extract purchase order details from JSON
        $quotation = $json->quotation ?? null;
        $OrderNumber = $json->OrderNumber ?? null;
        $CreatedDate = $json->CreatedDate ?? null;
        $DeliveryDate = $json->DeliveryDate ?? null;
        $TermAndCondition = $json->TermAndCondition ?? null;
        $SupplierId = $json->SupplierId ?? null;
        $UpdatedBy = $json->UpdatedBy ?? null;

        $createorder = [
            "quotation" => $quotation,
            "OrderNumber" => $OrderNumber,
            "CreatedDate" => $CreatedDate,
            "DeliveryDate" => $DeliveryDate,
            "TermAndCondition" => $TermAndCondition,
            "SupplierId" => $SupplierId,
            "UpdatedBy" => $UpdatedBy
        ];

        // Insert purchase order
        $purchaseorder->insert($createorder);

        // Extract product details from JSON and insert/update purchase order items
        $productdata = $json->Products ?? [];
        foreach ($productdata as $product) {
            $update = [
                "ProductId" => $product->ProductId,
                "WarehouseId" => $product->WarehouseId,
                "UnitId" => $product->UnitId,
                "price" => $product->price,
                "Quantity" => $product->Quantity,
                "sub_total_before_discount" => $product->sub_total_before_discount,
                "sub_total_after_discount" => $product->sub_total_after_discount,
                "Discount" => $product->Discount,
                "TaxValue" => $product->TaxValue
            ];

            if (isset($product->id)) {
                $purchaseorderitems->set($update)->where('id', $product->id)->update();
            } else {
                $update["PurchaseOrderId"] = $purchaseorder->insertID(); // Get the last inserted ID
                $purchaseorderitems->insert($update);
            }
        }


        $message = [
            'message' => 'Purchase Order And Items Successfully Created'
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
    }


    //Order Details

    public function GetOrdersItem()
    {

        $orderstable = new OrdersModel;
        $ordergrouptable = new Order_Groups_Model;
        $orderitemstable = new Order_Items_Model;
        $productstbale = new ProductsModel;
        $productsvariationtable = new ProductVariationsModel;
        $userstable = new UsersModel;
        $useraddresstable = new UserAddressesModel;
        $statetable = new StatesModel;
        $countrytable = new CountriesModel;
        $citytable = new CitiesModel;

        $id = $this->request->getGet('id');

        $Ordersdata = $orderstable->where('id', $id)->first();

        $Ordersdata['ordergroup'] = $ordergrouptable->where('id', $Ordersdata['order_group_id'])->first();

        // $Ordersdata['order_items'] = $orderitemstable->where('order_id', $Ordersdata['id'])->findAll();
        $Ordersdata['order_items'] = $orderitemstable->getProductName($Ordersdata['id']);

        $Ordersdata['user'] = $userstable->where('id', $Ordersdata['user_id'])->first();

        $Ordersdata['shipping_addressDetails'] = $useraddresstable->where('id', $Ordersdata['ordergroup']['shipping_address_id'])->first();

        $Ordersdata['billing_addressDetails'] = $useraddresstable->where('id', $Ordersdata['ordergroup']['billing_address_id'])->first();



        if (isset($Ordersdata['shipping_addressDetails']['country_id'])) {
            $Ordersdata['ordergroup']['shipping_addressDetails_country'] = $countrytable->where('id', $Ordersdata['shipping_addressDetails']['country_id'])->first();

            $Ordersdata['ordergroup']['shipping_addressDetails_state'] = $statetable->where('id', $Ordersdata['shipping_addressDetails']['state_id'])->first();

            $Ordersdata['ordergroup']['shipping_addressDetails_city'] = $citytable->where('id', $Ordersdata['shipping_addressDetails']['city_id'])->first();

        }



        $reponse = [
            "Ordersdata" => $Ordersdata
        ];
        $message = [
            'message' => 'Order Details Successfully Fetched'
        ];

        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $reponse]);

    }

    // PurchaseOrder


    public function PurchaseOrder()
    {
        $PM = new PurchaseOrderModel();
        $POIM = new PurchaseOrderitems();


        $json = $this->request->getJSON();
        $OrderNumber = isset($json->ordernumber) ? $json->ordernumber : null;
        $Note = isset($json->description) ? $json->description : null;
        $DeliveryDate = isset($json->deliverydate) ? $json->deliverydate : null;
        $PurchaseReturnNote = isset($json->purchasereturnnote) ? $json->purchasereturnnote : null;
        $TermAndCondition = isset($json->termsandcondition) ? $json->termsandcondition : null;
        $TotalAmount = isset($json->TotalAmount) ? $json->TotalAmount : null;
        $TotalTax = isset($json->TotalTax) ? $json->TotalTax : null;
        $TotalDiscount = isset($json->TotalDiscount) ? $json->TotalDiscount : null;
        $SupplierId = isset($json->SupplierId) ? $json->SupplierId : null;
        $product = !empty($json->products) ? $json->products : null;
        $Note = isset($json->description) ? $json->description : null;
        $DeliveryDate = isset($json->deliverydate) ? $json->deliverydate : null;
        $PurchaseReturnNote = isset($json->purchasereturnnote) ? $json->purchasereturnnote : null;
        $TermAndCondition = isset($json->termsandcondition) ? $json->termsandcondition : null;
        $createddate = isset($json->createddate) ? $json->createddate : null;
        $quotation = isset($json->quotation) ? $json->quotation : null;
        if ($product != null) {
            $datazzFromProductModel = [
                "OrderNumber" => $OrderNumber,
                "Note" => $Note,
                "DeliveryDate" => $DeliveryDate,
                "PurchaseReturnNote" => $PurchaseReturnNote,
                "TermAndCondition" => $TermAndCondition,
                "POCreatedDate" => $createddate,
                "TotalAmount" => $TotalAmount,
                "TotalTax" => $TotalTax,
                "TotalDiscount" => $TotalDiscount,
                "SupplierId" => $SupplierId,
                "quotation" => $quotation,

            ];

            $PM->insert($datazzFromProductModel);
            $insertId = $PM->getInsertID();

            $productModel = new ProductsModel();
            foreach ($product as $datas) {
                $datazzFromPurchaseOrderitems = [

                    "PurchaseOrderId" => $insertId,
                    "ProductId" => $datas->productid,
                    "price" => $datas->price,
                    "taxid" => $datas->taxid,
                    "TaxValue" => $datas->taxvalue,
                    "Discount" => $datas->discount,
                    "UnitId" => $datas->unitid,
                    "Quantity" => $datas->quantity,
                    "sub_total_after_discount" => $datas->sub_total_after_discount,
                    "sub_total_before_discount" => $datas->sub_total_before_discount,
                ];
                $productModel->where("id", $datas->productid);
                $product = $productModel->first();
                $quantity = $product['stock_qty'] - $datas->quantity;
                $productModel->set(['stock_qty' => $quantity])->where('id', $datas->productid)->update();

                $POIM->insert($datazzFromPurchaseOrderitems);

                $message = [
                    'message' => 'Purchase Order Created Successfully',
                    "status" => 200
                ];

            }

        } else {
            $message = [
                'message' => 'Invalid Input',
                "status" => 400
            ];
            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
        }

        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
    }

    public function Edit_PurchaseOrder()
    {
        $PM = new PurchaseOrderModel();
        $POIM = new PurchaseOrderitems();
        $json = $this->request->getJSON();
        $OrderNumber = isset($json->ordernumber) ? $json->ordernumber : null;
        $Note = isset($json->description) ? $json->description : null;
        $DeliveryDate = isset($json->deliverydate) ? $json->deliverydate : null;
        $PurchaseReturnNote = isset($json->purchasereturnnote) ? $json->purchasereturnnote : null;
        $TermAndCondition = isset($json->termsandcondition) ? $json->termsandcondition : null;
        $POCreatedDate = isset($json->createddate) ? $json->createddate : null;
        $Status = isset($json->status) ? $json->status : null;


        $TotalAmount = isset($json->TotalAmount) ? $json->TotalAmount : null;
        $TotalTax = isset($json->TotalTax) ? $json->TotalTax : null;
        $TotalDiscount = isset($json->TotalDiscount) ? $json->TotalDiscount : null;
        $SupplierId = isset($json->SupplierId) ? $json->SupplierId : null;

        $product = !empty($json->products) ? $json->products : null;

        $Note = isset($json->description) ? $json->description : null;
        $DeliveryDate = isset($json->deliverydate) ? $json->deliverydate : null;
        $PurchaseReturnNote = isset($json->purchasereturnnote) ? $json->purchasereturnnote : null;
        $TermAndCondition = isset($json->termsandcondition) ? $json->termsandcondition : null;
        $POCreatedDate = isset($json->createddate) ? $json->createddate : null;
        $Status = isset($json->status) ? $json->status : null;
        $Id = isset($json->Id) ? $json->Id : null;
        $Order_Prefix = '#PO';
        if ($product != null) {
            $datazzFromProductModel = [
                "OrderNumber" => $OrderNumber,
                "Note" => $Note,
                "DeliveryDate" => $DeliveryDate,
                "PurchaseReturnNote" => $PurchaseReturnNote,
                "TermAndCondition" => $TermAndCondition,
                "POCreatedDate" => $POCreatedDate,
                "TotalAmount" => $TotalAmount,
                "TotalTax" => $TotalTax,
                "TotalDiscount" => $TotalDiscount,
                "SupplierId" => $SupplierId,
                "Id" => $Id,

            ];

            $PM->set($datazzFromProductModel)->where('Id', $json->Id)->update();

            $insertId = $PM->getInsertID();


            foreach ($product as $datas) {
                $datazzFromPurchaseOrderitems = [

                    "PurchaseOrderId" => $insertId,
                    "ProductId" => $datas->productid,
                    // "Status" =>  $datas->status,
                    "price" => $datas->price,
                    "TaxValue" => $datas->taxvalue,
                    "Discount" => $datas->discount,
                    // "DiscountPercentage" =>  $datas->discount,
                    "UnitId" => $datas->unitid,
                    "CreatedDate" => $POCreatedDate,
                    // "WarehouseId" =>  $datas->warehouseid,
                    "Quantity" => $datas->quantity,
                    "sub_total_after_discount" => $datas->sub_total_after_discount,
                    "sub_total_before_discount" => $datas->sub_total_before_discount,
                ];

                // $POIM->update($datazzFromPurchaseOrderitems);
                $POIM->set($datazzFromPurchaseOrderitems)->where('Id', $json->Id)->update();

                $message[] = [
                    'message' => 'Purchase Order Successfully Updated',

                ];
                // $datas[('purchaseorderid')] =$insertId;
                // // $POIM->insert($datas); 



            }

        } else {
            $message = [
                'message' => 'No Data Provided'
            ];
        }

        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
    }


    public function getProductlistbyBrand()
    {
        try {
            $brandId = $this->request->getGet('brandId');

            $ProductsModels = new ProductModel();
            $units = new UnitsModel();
            $ProductsModels->where('brand_id', $brandId);
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
                    'stock_qty' => $resposne['stock_qty'],
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
                    'unit_details' => $units->where('id', $resposne['unit_id'])->first()
                ];
            }
            $data = $resposneData;

            $message = [
                'message' => 'Products Successfully Fetched!',
                'status' => 200
            ];

            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);

        } catch (\Exception $e) {
            // Handle the exception
            // You can log the error, return a specific error response, or perform any other appropriate action

            $message = [
                'message' => $e->getMessage(),
                'status' => 500
            ];
            return $this->response->setStatusCode(500)->setJSON(['messageobject' => $message]);
        }

    }  /// ================ GETPRODUCTS COMPLETE ================


    // end of sale order

    public function getProductDetails()
    {

        $products = new ProductsModel();
        $productcategory = new ProductcategoriesModel();
        $producttaxes = new ProductTaxesModel();
        $productvariation = new ProductVariationsModel();
        $brand = new BrandsModel();
        $categories = new CategoriesModel();
        $units = new UnitsModel();
        $media = new MediaModel();
        $id = $this->request->getGet('id');


        $productdata = $products->where('id', $id)->first();

        if (isset($productdata['brand_id'])) {
            $productdata['brandData'] = $brand->where("id", $productdata['brand_id'])->first();
        }

        if (isset($productdata['unit_id'])) {
            $productdata['uniData'] = $units->where("id", $productdata['unit_id'])->first();
        }

        $categoryId = $productcategory->where("product_id", $id)->first();

        if (isset($categoryId['category_id'])) {
            $productdata['categoryData'] = $categories->where("id", $categoryId['category_id'])->first();
        }
        if (isset($productdata['thumbnail_image'])) {
            $mediadata = $media->where("id", $productdata['thumbnail_image'])->first();
            $productdata['imgURL'] = $mediadata['media_file'];
        }




        $message = [
            'message' => 'Successfully Fetched Data'
        ];

        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $productdata]);
    }




    public function getProductstockDetails()
    {
        $productsModel = new ProductsModel();
        $locationsModel = new LocationsModel();

        $db = db_connect();
        $query = $db->query("
            SELECT p.id AS product_id, p.name AS product_name, p.min_stock_qty, p.stock_qty, l.name AS location_name, l.address AS location_address
            FROM products p
            LEFT JOIN locations l ON p.shop_id = l.id
            WHERE p.is_published = 1
        ");

        $result = $query->getResultArray();


        $response = [
            'success' => true,
            'message' => 'Product Details Data Retrieved Successfully.',
            'data' => $result
        ];


        return $this->response->setJSON($response);
    }





    public function getProductstockDetailsById($productId)
    {

        $db = db_connect();


        $query = $db->query("
        SELECT p.id AS product_id, p.name AS product_name, p.min_stock_qty, p.stock_qty, l.name AS location_name, l.address AS location_address
        FROM products p
        LEFT JOIN locations l ON p.shop_id = l.id
        WHERE p.is_published = 1 AND p.id = ?
    ", [$productId]);


        $result = $query->getRowArray();


        if ($result) {

            $response = [
                'success' => true,
                'message' => 'Product details for ' . $result['product_name'] . ' retrieved successfully.',
                'data' => $result,
            ];


        } else {

            $response = [
                'success' => false,
                'message' => 'Product not found with ID ' . $productId . '.',
                'data' => null
            ];
        }


        return $this->response->setJSON($response);
    }


    public function updateProductStock($id)
    {
        $productsModel = new ProductsModel();

        $product = $productsModel->find($id);
        if (!$product) {
            return $this->failNotFound("Product ID {$id} does not exist.");
        }


        $data = $this->request->getJSON();
        $stockQty = $data->stock_qty ?? null;
        $minStockQty = $data->min_stock_qty ?? null;


        if ($stockQty === null || $minStockQty === null) {
            return $this->failValidationErrors("Both 'stock_qty' and 'min_stock_qty' fields are required.");
        }


        $updateData = [
            'stock_qty' => $stockQty,
            'min_stock_qty' => $minStockQty,
        ];

        $result = $productsModel->update($id, $updateData);

        if ($result) {

            $message = [
                'message' => 'Product Stock Updated Successfully.',
                'status' => 200
            ];

            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
        } else {
            $message = [
                'message' => 'Something when wrong.',
                'status' => 400
            ];

            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
            // return $this->fail("Failed to update the product stock.");
        }
    }



public function getProductByLocationId()
{
    $data = $this->request->getGet();
    $locationId = $data['locationId'] ?? null;

    if ($locationId === null) {
        return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid location ID']);
    }

    $locationModel = new LocationsModel();
    $location = $locationModel->find($locationId);

    if ($location === null) {
        return $this->response->setStatusCode(404)->setJSON(['error' => 'Location not found']);
    }

    $productModel = new ProductsModel();

    $products = $productModel
        ->where('location_id', $locationId)
        ->where('is_published', 1)
        ->findAll();

    if (empty($products)) {
        return $this->response->setStatusCode(404)->setJSON(['error' => 'No published products found for the provided location ID']);
    }

    $responseProducts = [];
    foreach ($products as $product) {
     
        $productVariationsModel = new ProductVariationsModel();
        $productVariations = $productVariationsModel->where('product_id', $product['id'])->findAll();
        $stock_status = (intval($product['stock_qty']) > 0) ? 'in_stock' : 'out_stock';

        $mediaModel = new Media_managersModel();
        $media = $mediaModel->find($product['thumbnail_image']);
        $thumbnailImageUrl = isset($media['media_file']) ? $media['media_file'] : null;


        $unitsModel = new UnitsModel();
        $unit = $unitsModel->find($product['unit_id']);

        $responseProducts[] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'slug' => $product['slug'],

            'description' => $product['description'],
            'thumbnail_image_url' => $thumbnailImageUrl,
            'sale_price' => $product['max_price'],
            'stock_qty' => $product['stock_qty'],
            'is_featured' => $product['is_featured'],
            'created_at' => $product['created_at'],
            'updated_at' => $product['updated_at'],
            'variations' => $productVariations,
            'unit_details' => $unit,
            'stock_status' => $stock_status,
            'created_by_id' => "1",
            'discount' => $product['discount_value'],
            
            
            'is_featured' => $product['is_featured'],
        ];
    }

    return $this->response->setStatusCode(200)->setJSON([
        'location' => [
            'id' => $location['id'],
            'name' => $location['name'],
        ],
        'products' => $responseProducts,
    ]);
}


    
    
    
}