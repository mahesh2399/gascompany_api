<?php namespace App\Controllers\Api;

use App\Models\api\ProductModel;
use App\Models\api\UnitConversationsModel;
use App\Models\api\ProductCategoriesModel;
use  App\Models\api\BrandsModel;
use CodeIgniter\RESTful\ResourceController;


class SearchController extends ResourceController
{
    public function searchitem()
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
                    "product_thumbnail"=> [],
            
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
 
}

