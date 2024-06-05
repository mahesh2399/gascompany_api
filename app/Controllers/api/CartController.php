<?php
 
namespace App\Controllers\Api;

use App\Models\api\CartModel;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;
use App\Models\api\ProductModel;

class CartController extends ResourceController
{
    protected $modelName = 'App\Models\YourModel';
    protected $format    = 'json';

    public function index()
    {
        $productModel = new ProductModel();
        $cardModels = new CartModel();
        $CustomerId = $this->request->getGet('CustomerId');
        $GuestId = $this->request->getGet('GuestId');
        if (isset($CustomerId)) {
            $cardModels->where('CustomerId', $CustomerId);
        }
        if (isset($GuestId)) {
            $cardModels->where('GuestId', $GuestId);
        }

        $cardModelsResponse = $cardModels->where([ 'IsDeleted'=> null ])->findAll();
        
        $transformedProducts['product'] = $this->transformProducts($cardModelsResponse, $productModel);

        return $this->respond($transformedProducts);
    
    }

    public function transformProducts($cardModelsResponse, $productModel)
    {
        $transformedProducts =[];
        foreach ($cardModelsResponse as $product) {
            $productModels = $productModel->find($product['ProductId']);

            $transformedProducts[] = [
                'Id'=> $product['Id'],
                'GuestId' => $product['GuestId'],
                'ProductId' => $product['ProductId'],
                'quantity' => $product['Quantity'],
                'CreatedDate' => $product['CreatedDate'],
                'CreatedBy' => $product['CreatedBy'],
                'ModifiedDate' => $product['ModifiedDate'],
                'ModifiedBy' => $product['ModifiedBy'],
                'DeletedDate' => $product['DeletedDate'],
                'DeletedBy' => $product['DeletedBy'],
                'IsDeleted' => $product['IsDeleted'],
                'productId' => $productModels ? $productModels['Id'] : null,
                'ProductName' => $productModels ? $productModels['Name'] : null,
                'ProductCode' => $productModels ? $productModels['Code'] : null,
                'ProductBarcode' => $productModels ? $productModels['Barcode'] : null,
                'ProductSkuCode' => $productModels ? $productModels['SkuCode'] : null,
                'ProductSkuName' => $productModels ? $productModels['SkuName'] : null,
                'ProductDescription' => $productModels ? $productModels['Description'] : null,
                'ProductProductUrl' => $productModels ? $productModels['ProductUrl'] : null,
                'ProductQRCodeUrl' => $productModels ? $productModels['QRCodeUrl'] : null,
                'ProductUnitId' => $productModels ? $productModels['UnitId'] : null,
                'ProductPurchasePrice' => $productModels ? $productModels['PurchasePrice'] : null,
                'ProductSalesPrice' => $productModels ? $productModels['SalesPrice'] : null,
                'ProductMrp' => $productModels ? $productModels['Mrp'] : null,
                'ProductCategoryId' => $productModels ? $productModels['CategoryId'] : null,
                'ProductBrandId' => $productModels ? $productModels['BrandId'] : null,
                'WarehouseId' => $productModels ? $productModels['WarehouseId'] : null,
                'ProductCreatedDate' => $productModels ? $productModels['CreatedDate'] : null,
                'ProductCreatedBy' => $productModels ? $productModels['CreatedBy'] : null,
                'ProductModifiedDate' => $productModels ? $productModels['ModifiedDate'] : null,
                'ProductModifiedBy' => $productModels ? $productModels['ModifiedBy'] : null,
                'ProductDeletedDate' => $productModels ? $productModels['DeletedDate'] : null,
                'ProductDeletedBy' => $productModels ? $productModels['DeletedBy'] : null,
                'ProductIsDeleted' => $productModels ? $productModels['IsDeleted'] : null,
            ];
        }

        return $transformedProducts;
    }


    // =================== ADD CART  =================
    public function addcart(){
        // Get JSON data from the request
        $json = $this->request->getJSON();
        $cartModel = new CartModel();

        // Generate a unique ID using UUID
        $guid = Uuid::uuid4()->toString();

   
        $GuestId = isset($json->GuId) ? $json->GuId : null;
        $CustomerId = isset($json->CustomerId) ? $json->CustomerId : null;
     
        $ProductId = $json->product_id;

        if ($ProductId && ($GuestId || $CustomerId)) {
          
            $cartData = [
                'Id' => $guid,
                'GuestId' => $GuestId,
                'CustomerId' =>  $CustomerId,
                'ProductId' => $ProductId,
                'Quantity' => 1,
                'IsDeleted' => null,
            ];

                if($CustomerId && $GuestId){
                    $existingUser = $cartModel->where(['GuestId' =>  $GuestId, 'CustomerId'=> null ])->findAll();
                if ($existingUser) {
                    // Update the guest id for all records with the same user id
                    $cartModel->set(['CustomerId' => $CustomerId])->where('GuestId', $GuestId)->update();
                   }
                }  

             if($CustomerId || $GuestId){
                    if($CustomerId){
                        $existingCart = $cartModel->where(['CustomerId' => $json->CustomerId, 'ProductId' => $ProductId,'IsDeleted' => null])->first();
                    }else if($GuestId){
                        $existingCart = $cartModel->where(['GuestId' =>  $GuestId, 'ProductId' => $ProductId,'IsDeleted' => null])->first();
                    }else{
                        return $this->failUnauthorized('Guest Id or User Id is a must'); 
                    }          
                    if($existingCart){
                        $newQuantity = $existingCart['Quantity'] +  1;
                        $cartModel->set(['Quantity' => $newQuantity])->where('ProductId', $ProductId)->update();
                    }else{
                        $cartModel->insert($cartData);
                    }
                    return $this->respond([ 'message' => 'Item added to cart', 'GuestId'=>$GuestId]);


            }else{ 
            return $this->failUnauthorized('Guest Id or User Id is a must'); 
               }
             
     }else{
          return $this->failUnauthorized('Product Id  is a must');
     }
    

}


public function removecart(){
    // Get JSON data from the request
    $json = $this->request->getJSON();
    $cartModel = new CartModel();

    // Generate a unique ID using UUID
    $guid = Uuid::uuid4()->toString();
   

    $GuestId = isset($json->GuestId) ? $json->GuestId : null;
    $CustomerId = isset($json->CustomerId) ? $json->CustomerId : null;
 
    $ProductId = $json->ProductId;

    if ($ProductId && ($GuestId || $CustomerId)) {
      
      if($CustomerId && $GuestId){


            $existingUser = $cartModel->where(['GuestId' => $json->GuestId, 'CustomerId'=> null ])->findAll();
            if ($existingUser) {
                // Update the guest id for all records with the same user id
                $cartModel->set(['CustomerId' => $CustomerId])->where('GuestId', $GuestId)->update();
               }
 }  

         if($CustomerId || $GuestId){
                if($CustomerId){
                    $existingCart = $cartModel->where(['CustomerId' => $json->CustomerId, 'ProductId' => $json->ProductId])->first();
                }else if($GuestId){
                    $existingCart = $cartModel->where(['GuestId' => $json->GuestId, 'ProductId' => $json->ProductId])->first();      
                }else{
                    return $this->failUnauthorized('Guest Id or User Id is a must'); 
                }          
                if($existingCart){
                    $newQuantity = $existingCart['Quantity'] -  1;
                    $cartModel->set(['Quantity' => $newQuantity])->where('ProductId', $ProductId)->update();
                }
              
                return $this->respond([ 'message' => 'Item removed successfully']);


        }else{
               return $this->failUnauthorized('Guest Id or User Id is a must'); 
           }
         
 }else{
      return $this->failUnauthorized('Product Id  is a must');
 }

}


public function deletecartitem(){
   
    $json = $this->request->getJSON();
    $cartModel = new CartModel();
    $GuestId = isset($json->GuestId) ? $json->GuestId : null;
    $CustomerId = isset($json->CustomerId) ? $json->CustomerId : null;
    $ProductId = $json->ProductId;

    if ($ProductId && ($GuestId || $CustomerId)) {
      
       if($CustomerId && $GuestId){
          $existingUser = $cartModel->where(['GuestId' => $json->GuestId, 'CustomerId'=> null ])->findAll();
            if ($existingUser){
                $cartModel->set(['CustomerId' => $CustomerId])->where('GuestId', $GuestId)->update();
               }
    }  

         if($CustomerId || $GuestId){
                if($CustomerId){
                    $existingCart = $cartModel->where(['CustomerId' => $json->CustomerId, 'ProductId' => $json->ProductId])->first();
                }else if($GuestId){
                    $existingCart = $cartModel->where(['GuestId' => $json->GuestId, 'ProductId' => $json->ProductId])->first();
                }else{
                    return $this->failUnauthorized('Guest Id or User Id is a must'); 
                }          
                if($existingCart){
                    $IsDeleted =  0;
                    $cartModel->set(['IsDeleted' => $IsDeleted])->where('ProductId', $ProductId)->update();
                }
              
                return $this->respond([ 'message' => 'Item deleted successfully']);


        }else{
        return $this->failUnauthorized('Guest Id or User Id is a must'); 
           }
         
 }else{
      return $this->failUnauthorized('Product Id  is a must');
 }


}

   }      




