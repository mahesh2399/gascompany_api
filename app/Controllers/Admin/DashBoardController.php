<?php

namespace App\Controllers\Admin;

use App\Models\Admin\Order_Groups_Model;
use App\Models\Admin\OrdersModel;
use App\Models\Admin\UsersModel;
use App\Models\Admin\CategoriesModel;
use App\Models\Admin\MenuModel;
use App\Models\Admin\RoleHasPermissionModel;
use App\Models\Admin\PermissionsModel;
use CodeIgniter\RESTful\ResourceController;
class DashBoardController extends ResourceController
{


    public function getdashboardcarddetails()
    {
        try {
            $productModel = new Order_Groups_Model();
            $ordertable = new OrdersModel();
            $userstable = new UsersModel();
            $categoriestable = new CategoriesModel();
           
            $data['count_of_today_Orders'] = $productModel->count_of_today_Orders(); // for  today order          
            $data['count_of_last_7days_Order'] = $productModel->count_of_last_7days_Order();// for last 7 days order
            $data['count_of_last_30day_sOrder'] = $productModel->count_of_last_30day_sOrder();
            $data['count_of_this_Year_Orders'] = $productModel->count_of_this_Year_Orders();// for  year order

            $data['count_of_today_earnings'] = $productModel->count_of_today_earnings();
            $data['count_of_last_7days_earnings'] = $productModel->count_of_last_7days_earnings();//for last 7 days earning
            $data['count_of_last_30days_earnings'] = $productModel->count_of_last_30days_earnings();
            $data['count_of_this_year_earnings'] = $productModel->count_of_thisyear_earnings();

            $data['count_of_today_pending_earnings'] = $productModel->count_of_today_pending_earnings();
            $data['count_last_7days_pending_earnings'] = $productModel->count_last_7days_pending_earnings();//for week pending_earnings
            $data['count_last_30days_pending_earnings'] = $productModel->count_last_30days_pending_earnings();//for month pending_earnings
            $data['count_of_thisyear_pending_earnings'] = $productModel->count_of_thisyear_pending_earnings();

            $data['count_of_paid_today'] = $productModel->count_of_paid_today();//for today paid
            $data['count_of_paid_last_7days'] = $productModel->count_of_paid_last_7days();//for week paid
            $data['count_of_paid_last_30days'] = $productModel->count_of_paid_last_30days();//for month paid
            $data['count_of_paid_thisyear'] = $productModel->count_of_paid_thisyear();//for week paid 

            $data['count_of_un_paid_today'] = $productModel->count_of_un_paid_today(); // for today
            $data['count_of_un_paid_last_7days'] = $productModel->count_of_un_paid_last_7days(); // for last 7 days
            $data['count_of_un_paid_last_30days'] = $productModel->count_of_un_paid_last_30days(); // for last 30 days
            $data['count_of_un_paid_thisyear'] = $productModel->count_of_un_paid_thisyear(); // for last year

            $data['count_of_paid'] = $productModel->count_of_paid();
            $data['count_of_un_paid'] = $productModel->count_of_un_paid();

            $data['count_of_grand_paid_total_amount'] = $productModel->count_of_grand_paid_total_amount();
            $data['count_of_grandtotal_pending_amount'] = $productModel->count_of_grandtotal_pending_amount();

            $data['count_of_grandtotal_amount'] = $productModel->count_of_grandtotal_amount();

            $data['count_of_grandtotal_amount_for_today'] = $productModel->count_of_grandtotal_amount_for_today();// for today
            $data['count_of_grandtotal_amount_for_last7days'] = $productModel->count_of_grandtotal_amount_for_last7days(); // for last7days
            $data['count_of_grandtotal_amount_for_last30days'] = $productModel->count_of_grandtotal_amount_for_last30days(); // for last30days
            $data['count_of_grandtotal_amount_for_thisyear'] = $productModel->count_of_grandtotal_amount_for_thisyear();// for this year

            $data['total_purchase_orders'] = $productModel->total_purchase_orders();
            $data['total_purchase_order_returns'] = $productModel->total_purchase_order_returns();
            $data['total_sales_orders'] = $productModel->total_sales_orders();
            $data['total_sales_order_returns'] = $productModel->total_sales_order_returns();

            $data['total_brand'] = $productModel->total_brand();
            $data['total_subscribed_users'] = $productModel->total_subscribed_users();
            $data['totalProductSale'] = $productModel->totalProductSale();

            $result = $productModel->where('payment_status', "Unpaid")->select('sum(grand_total_amount) as sumQuantities')->first();
            $data['sum'] = $result['sumQuantities'];

            // =====
            $result1 = $productModel->where('payment_status', "Paid")->select('sum(grand_total_amount) as sumQuantities1')->first();
            $data['sum_2'] = $result1['sumQuantities1'];

            // =====
            $data['count'] = $productModel->get()->getNumRows();

            // ===== PAID ORDERS
            $result2 = $productModel->where('payment_status', "Paid");
            $data['PAID ORDERS COUNT'] = $result2->get()->getNumRows();

            // ===== UNPAID ORDERS
            $result3 = $productModel->where('payment_status', "Pnpaid");
            $data['UNPAID ORDERS COUNT'] = $result3->get()->getNumRows();

            // =====PICKED UP ORDERS
            $result4 = $ordertable->where('delivery_status', "order_placed");
            $data['PICKED UP ORDERS'] = $result4->get()->getNumRows();

            // =====CANCELLED ORDERS
            $result5 = $ordertable->where('delivery_status', "cancelled");
            $data['CANCELLED ORDERS'] = $result5->get()->getNumRows();

            // =====TODAY'S EARNING
            $result6 = $productModel->where('created_at', new \Datetime('now') && 'payment_status', "Paid")->select('sum(grand_total_amount) as sumQuantities1')->first();
            $data['TODAY EARNING'] = $result6['sumQuantities1'];

            // =====TODAY'S PENDING EARNING
            // $result7 = $productModel->where('created_at', new \Datetime('now') && 'payment_status', "Unpaid")->select('sum(grand_total_amount) as sumQuantities1')->first();
            // $data['TODAY PENDING EARNING'] = $result7['sumQuantities1'];

            // =====TOTAL CUSTOMER
            $result8 = $userstable->where('user_type', "customer");
            $data['TOTAL CUSTOMER'] = $result8->get()->getNumRows();

            // =====TOTAL CATEGORY
            $data['CATEGORY'] = $categoriestable->get()->getNumRows();

            $reponse = [
                "TOTAL_PAID_AMOUNT" => $data['sum'],
                "TOTAL_UNPAID_AMOUNT" => $data['sum_2'],
                "TOTAL_ORDERS_COUNT" => $data['count'],
                "PAID_ORDERS_COUNT" => $data['PAID ORDERS COUNT'],
                "UNPAID_ORDERS_COUNT" => $data['UNPAID ORDERS COUNT'],
                "PICKED_UP_ORDERS" => $data['PICKED UP ORDERS'],
                "CANCELLED_ORDERS" => $data['CANCELLED ORDERS'],
                "today_earning" => 0,
                "today_pending_earning" => 0,
                "total_customers" => $data['TOTAL CUSTOMER'],
                "total_category" => $data['CATEGORY'],

                "count_of_today_Orders" => $data['count_of_today_Orders'][0]['total_orders_today'],// for today order
                "count_of_last_7days_Order" => $data['count_of_last_7days_Order'][0]['total_orders_last_7days_order'],// for last 7 days order
                "count_of_last_30day_sOrder" => $data['count_of_last_30day_sOrder'][0]['total_orders_last_30days_order'],
                "count_of_this_Year_Orders" => $data['count_of_this_Year_Orders'][0]['total_orders_thisyear'],// for year order     

                "count_of_today_earnings" => $data['count_of_today_earnings'][0]['total_today_earnings'],
                "count_of_last_7days_earnings" => $data['count_of_last_7days_earnings'][0]['total_last_7_days_earnings'],//  for last 7 days earning
                "count_of_last_30days_earnings" => $data['count_of_last_30days_earnings'][0]['total_last_30_days_earnings'],
                "count_of_this_year_earnings" => $data['count_of_this_year_earnings'][0]['total_this_year_earnings'],

                "count_of_today_pending_earnings" => $data['count_of_today_pending_earnings'][0]['total_today_pending_earnings'],
                "count_last_7days_pending_earnings" => $data['count_last_7days_pending_earnings'][0]['total_week_pending_earnings'],//for week pending_earnings
                "count_last_30days_pending_earnings" => $data['count_last_30days_pending_earnings'][0]['total_month_pending_earnings'],//for month pending_earnings
                "count_of_thisyear_pending_earnings" => $data['count_of_thisyear_pending_earnings'][0]['total_year_pending_earnings'],// for year pending earnings


                "count_of_paid_today" => $data['count_of_paid_today'][0]['total_count_of_paid_today'], // for today paid
                "count_of_paid_last_7days" => $data['count_of_paid_last_7days'][0]['total_count_of_paid_last7days'], // for week paid
                "count_of_paid_last_30days" => $data['count_of_paid_last_30days'][0]['total_count_of_paid_last30days'], // for month paid
                "count_of_paid_thisyear" => $data['count_of_paid_thisyear'][0]['total_count_of_paid_this_year'], // for year paid

                "count_of_un_paid_today" => $data['count_of_un_paid_today'][0]['total_unpaid_count_of_today'],
                "count_of_un_paid_last_7days" => $data['count_of_un_paid_last_7days'][0]['total_unpaid_count_of_last7days'],
                "count_of_un_paid_last_30days" => $data['count_of_un_paid_last_30days'][0]['total_unpaid_count_of_last30days'],
                "count_of_un_paid_thisyear" => $data['count_of_un_paid_thisyear'][0]['total_unpaid_count_of_this_year'],

                "count_of_paid" => $data['count_of_paid'][0]['count_of_paid'],
                "count_of_un_paid" => $data['count_of_un_paid'][0]['count_of_paid'],
                // this for revenue
                "count_of_grand_paid_total_amount" => $data['count_of_grand_paid_total_amount'][0]['total_grandtotal'],
                "count_of_grandtotal_pending_amount" => $data['count_of_grandtotal_pending_amount'][0]['total_pending_grandtotal'],

                "count_of_grandtotal_amount" => $data['count_of_grandtotal_amount'][0]['total_revenue'],


                "count_of_grandtotal_amount_for_today" => $data['count_of_grandtotal_amount_for_today'][0]['total_revenue_today'],
                "count_of_grandtotal_amount_for_last7days" => $data['count_of_grandtotal_amount_for_last7days'][0]['total_revenue_last7days'],
                "count_of_grandtotal_amount_for_last30days" => $data['count_of_grandtotal_amount_for_last30days'][0]['total_revenue_last30days'],
                "count_of_grandtotal_amount_for_thisyear" => $data['count_of_grandtotal_amount_for_thisyear'][0]['total_revenue_thisyear'],

               
               
                "total_purchase_orders" => $data['total_purchase_orders'][0]['total_purchase_orders'],
                "total_purchase_order_returns" => $data['total_purchase_order_returns'][0]['total_purchase_order_returns'],
                "total_sales_orders" => $data['total_sales_orders'][0]['total_sales_orders'],
                "total_sales_order_returns" => $data['total_sales_order_returns'][0]['total_sales_order_returns'],



                "total_brand" => $data['total_brand'][0]['total_brand'],
                "total_subscribed_users" => $data['total_subscribed_users'][0]['total_subscribed_users'],
                "totalProductSale" => $data['totalProductSale'][0]['total_quantity'],


            ];
            $message = [
                'message' => 'Successfully fetched dashboard count'
            ];

            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $reponse]);

        } catch (\Exception $e) {
            $message = [
                'message' => 'Something when wrong please try again',
                'status' => 400
            ];
            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
        }
    }

    public function getmenu(){
        $user_id = $this->request->getGet('user_id');
        {
            try {
                $MenuModels = new MenuModel();
                $menulist = $MenuModels->getmenus($user_id);
                $menulevel=[];
                foreach($menulist as $mlist){
                $sublistdb = $MenuModels->getbyparent_id($mlist['id'],$user_id);
              
                if($sublistdb){
                  $menulevel[] = [
                    "id"=> $mlist['id'],
                    "title"=> $mlist['title'],
                    "path"=> isset($mlist['path'])? $mlist['path']:null,
                    "active"=>  isset($mlist['active'])?($mlist['active']== '1'? true:false):false,
                    "icon"=> isset($mlist['icon']) ? $mlist['icon'] : null,
                    "type"=> isset($mlist['type']) ? $mlist['type']:null,
                    "level"=> isset($mlist['level']) ? $mlist['level']:null,
                    "children"=> $sublistdb
                  ];
                }else{
                  $menulevel[] = [
                    "id"=> $mlist['id'],
                    "title"=> $mlist['title'],
                    "path"=> isset($mlist['path'])? $mlist['path']:null,
                    "active"=> isset($mlist['active'])?($mlist['active']== '1'? true:false):false,
                    "icon"=> isset($mlist['icon']) ? $mlist['icon'] : null,
                    "type"=> isset($mlist['type']) ? $mlist['type']:null,
                    "level"=> isset($mlist['level']) ? $mlist['level']:null
                  ];
                }    
                }
               
                $response = [
                    'data' => $menulevel,
                    'message_object' => [
                        'message' => 'Menu retrieved successfully',
                        'statusCode' => 200
                    ]
                ];
        
                return $this->respond($response);
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

    }

    public function batchmenulist(){
      $PermissionsModels = new PermissionsModel;
      $PermissionsModels->truncate();
      $PermissionsModels->query('ALTER TABLE ' . 'permissions' . ' AUTO_INCREMENT = 1');
      $result1 =[
        [
          "id"=> 1,
          "title"=> "dashboard",
          "path"=> "/dashboard",
          "active"=> false,
          "icon"=> "ri-home-line",
          "type"=> "sub",
          "level"=> 1,
        ],          
        [
          "id"=> 2,
          "title"=> "products",
          "active"=> false,
          "icon"=> "ri-store-3-line",
          "type"=> "sub",
          "level"=> 1,
          "children"=> [
            [
              "id"=>1,
              "parent_id"=> 2,
              "title"=> "Brands",
              "path"=> "/brand",
              "type"=> "link",
              "level"=> 2,
              "permission"=> ["brand.index"],  
            ],
            [
              "id"=>2,
              "parent_id"=> 2,
              "title"=> "Category",
              "path"=> "/category",
              "type"=> "link",
              "level"=> 2,
              "permission"=> ["category.index"],
            ],
            [
              "id"=>3,
              "parent_id"=> 2,
              "title"=> "Products",
              "path"=> "/product",
              "type"=> "link",
              "badgeType"=> "badge bg-warning text-dark ml-3",
              "level"=> 2,
              "permission"=> ["product.index"],
            ],
            [
              "id"=>4,
              "parent_id"=> 2,
              "title"=> "Variations",
              "path"=> "/variation",
              "type"=> "link",
              "level"=> 2,
              "permission"=> ["variation.index"],
            ],
          ],
        ],
        [
          "id"=> 3,
          "title"=> "Manage Sales",
          "active"=> false,
          "icon"=> "ri-list-unordered",
          "type"=> "sub",
          "level"=> 1,
          "children"=> [
            [
              "id"=>1,
              "parent_id"=> 3,
              "title"=> "All Orders",
              "path"=> "/order",
              "type"=> "link",
              "level"=> 2,
              "permission"=> ["order.index"],
            ],
            [
              "id"=>2,
              "parent_id"=> 3,
              "title"=> "Stock",
              "path"=> "/stock",
              "type"=> "link",
              "level"=> 2,
              "permission"=> ["stock.index", "stock.create"],
            ],
          ],
        ],
        [
          "id"=> 4,
          "title"=> "Manage Supply",
          "active"=> false,
          "icon"=> "ri-red-packet-fill",
          "type"=> "sub",
          "level"=> 1,
          "children"=> [
            [
              "id"=>1,
              "parent_id"=> 4,
              "title"=> "Suppliers",
              "path"=> "/supplier",
              "type"=> "link",
              "level"=> 2,
            ],
            [
              "id"=>2,
              "parent_id"=> 4,
              "title"=> "Purchase Orders",
              "path"=> "/purchase-order",
              "type"=> "link",
              "level"=> 2,
              "permission"=> ["role.index"],
            ],
            [
              "id"=>3,
              "parent_id"=> 4,
              "title"=> "Purchase Orders Return",
              "path"=> "/purchaseOrderReturn",
              "type"=> "link",
              "level"=> 2,
              "permission"=> ["role.index"],
            ],
            [
              "id"=>4,
              "parent_id"=> 4,
              "title"=> "Sale Orders",
              "path"=> "/sales-order",
              "type"=> "link",
              "level"=> 2,
              "permission"=> ["role.index"],
            ],
            [
              "id"=>5,
              "parent_id"=> 4,
              "title"=> "Sale Orders Return",
              "path"=> "/sales-order-return",
              "type"=> "link",
              "level"=> 2,
              "permission"=> ["role.index"],
            ],
          ],
        ],
        [
          "id"=> 5,
          "title"=> "Manage Customer",
          "active"=> false,
          "icon"=> "ri-window-line",
          "type"=> "sub",
          "level"=> 1,
          "children"=> [
            [
              "id"=>1,
              "parent_id"=> 5,
              "title"=> "Customers",
              "path"=> "/customers",
              "type"=> "link",
              "level"=> 2,
              "permission"=> ["customer.index"],
            ],
          ],
        ],
        [
          "id"=> 7,
          "title"=> "Manage users",
          "active"=> false,
          "icon"=> "ri-contacts-line",
          "type"=> "sub",
          "level"=> 1,
          "children"=> [
            [
              "id"=>1,
              "parent_id"=> 7,
              "title"=> "Add User",
              "path"=> "/user/create",
              "type"=> "link",
              "level"=> 2,
              "permission"=> ["user.index", "user.create"],
            ],                
            [
              "id"=>2,
              "parent_id"=> 7,
              "title"=> "Users",
              "path"=> "/user",
              "type"=> "link",
              "level"=> 2,
              "permission"=> ["user.index"],
            ],
            [
              "id"=>3,
              "parent_id"=> 7,
              "title"=> "Roles",
              "path"=> "/roless",
              "type"=> "link",
              "level"=> 2,
            ],

            [
              "id"=>4,
              "parent_id"=> 7,
              "title"=> "Delivery Men",
              "path"=> "/delivery_man",
              "type"=> "link",
              "level"=> 2,
            ],

          ],
        ],
      
        [
          "id"=> 8,
          "title"=> "Reports",
          "active"=> false,
          "icon"=> "ri-window-line",
          "type"=> "sub",
          "level"=> 1,
          "children"=> [
            [
              "id"=>1,
              "parent_id"=> 8,
              "title"=> "Sales Report",
              "path"=> "/sales-report",
              "type"=> "link",
              "level"=> 2,
              "permission"=> ["theme.index"],
            ],
            [
              "id"=>2,
              "parent_id"=> 8,
              "title"=> "Delivery Report",
              "path"=> "/delivery-report",
              "type"=> "link",
              "level"=> 3,
            ],
            [ 
              "id"=>3,
              "parent_id"=> 8, 
              "title"=> "Products Sale Report", 
              "path"=> "/product-sales-report", 
              "type"=> "link", 
              "level"=> 2, 
            ], 
            [ 
              "id"=>4,
              "parent_id"=> 8, 
              "title"=> "Categories Sale Report", 
              "path"=> "/category-sales-report", 
              "type"=> "link", 
              "level"=> 2, 
            ],
          ]
        ],
        [
          "id"=> 9,
          "title"=> "System Config",
          "active"=> false,
          "icon"=> "ri-store-2-line",
          "type"=> "sub",
          "level"=> 1,
          "children"=> [
            [
              "id"=>1,
              "parent_id"=> 9,
              "title"=> "Tax",
              "path"=> "/tax",
              "type"=> "link",
              "badgeType"=> "badge bg-warning text-dark ml-3",
              "badgeValue"=> 0,
              "level"=> 2,
            ],
            [
              "id"=>2,
              "parent_id"=> 9,
              "title"=> "Units",
              "path"=> "/units",
              "type"=> "link",
              "badgeType"=> "badge bg-danger ml-3",
              "badgeValue"=> 0,
              "level"=> 2,
            ],
            [
              "id"=>3,
              "parent_id"=> 9,
              "title"=> "Order Types",
              "path"=> "/order-type",
              "type"=> "link",
              "badgeType"=> "badge bg-danger ml-3",
              "badgeValue"=> 0,
              "level"=> 2,
            ],
            [
              "id"=>4,
              "parent_id"=> 9,
              "title"=> "Order Status",
              "path"=> "/order-status",
              "type"=> "link",
              "badgeType"=> "badge bg-danger ml-3",
              "badgeValue"=> 0,
              "level"=> 2,
              "permission"=> ["withdraw_request.index"],
            ],
          ],
        ],            
        [
          "id"=> 10,
          'title'=> "Support",
          "active"=> false,
          "icon"=> "ri-article-line",
          "type"=> "sub",
          "level"=> 1,
          "children"=> [
            [
              "id"=>1,
              "parent_id"=> 10,
              "title"=> "User Queries",
              "path"=> "/user-queries",
              "type"=> "link",
              "level"=> 2,
            ],
          ],
        ],
        [
          "id"=> 11,
          "title"=> "System Settings",
          "active"=> false,
          "icon"=> "ri-tools-fill",
          "type"=> "sub",
          "level"=> 1,
          "children"=> [                
            [
              "id"=>1,
              "parent_id"=> 11,
              "title"=> "OTP Settings",
              "path"=> "/otp-settings",
              "type"=> "link",
              "level"=> 2,
              "permission"=> ["store.index", "store.create"]
            ],
            [
              "id"=>2,
              "parent_id"=> 11,
              "title"=> "Order Settings",
              "path"=> "/order-setting",
              "type"=> "link",
              "badgeType"=> "badge bg-warning text-dark ml-3",
              "badgeValue"=> 0,
              "level"=> 2,
              "permission"=> ["store.index"]
            ],
            [
              "id"=>3,
              "parent_id"=> 11,
              "title"=> "Invoice Settings",
              "path"=> "/invoice",
              "type"=> "link",
              "badgeType"=> "badge bg-warning text-dark ml-3",
              "badgeValue"=> 0,
              "level"=> 2,
              "permission"=> ["store.index"]
            ],
            [
              "id"=>4,
              "parent_id"=> 11,
              "title"=> "General Settings",
              "path"=> "/general-setting",
              "type"=> "link",
              "badgeType"=> "badge bg-warning text-dark ml-3",
              'badgeValue'=> 0,
              "level"=> 2,
              "permission"=> ["store.index"]
            ],
            [
              "id"=>5,
              "parent_id"=> 11,
              "title"=> "SMTP Settings",
              "path"=> "/smpt-setting",
              "type"=> "link",
              "badgeType"=> "badge bg-warning text-dark ml-3",
              "badgeValue"=> 0,
              "level"=> 2,
              "permission"=> ["store.index"]
            ],
            [
              "id"=>6,
              "parent_id"=> 11,
              "title"=> "Social Media Settings",
              "path"=> "/social-media-setting",
              "type"=> "link",
              "badgeType"=> "badge bg-warning text-dark ml-3",
              'badgeValue'=> 0,
              'level'=> 2,
              "permission"=> ["store.index"]
            ],
            [
              "id"=>7,
              "parent_id"=> 11,
              'title'=> " Multi Currency Settings",
              "path"=> "/currency",
              "type"=> "link",
              "badgeType"=> "badge bg-warning text-dark ml-3",
              "badgeValue"=> 0,
              "level"=> 2,
               "permission"=> ["store.index"]
              ],
                  [
                    "id"=>8,
                    "parent_id"=> 11,
                    "title"=> "Multi Language Settings",
                    "path"=> "/multilanguage-setting",
                    "type"=> "link",
                    "badgeType"=> "badge bg-warning text-dark ml-3",
                    "badgeValue"=> 0,
                    "level"=> 2,
                    "permission"=> ["store.index"],
              ],    
          ],
          ]
      ];

      foreach($result1 as $row){
        $data = [
          'name'=>$row['title'],
          'group_name'=>$row['title'],
          'guard_name'=>"web",
          "path"=>isset($row['path'])?$row['path']:null,
          "active" =>isset($row['active'])?$row['active'] : null,
          "icon" =>isset($row['icon'])?$row['icon'] : null,
          "type" =>isset($row['type'])?$row['type'] : null,
          "level" =>isset($row['level'])?$row['level'] : null,
          "is_show" =>1,
          "parent_id"=>isset($row['parent_id'])?$row['parent_id'] : null,
          "display_order"=>isset($row['id'])?$row['id']:null
        ];
        $permission_id = $PermissionsModels->insert($data);
        if(isset($row['children'])){
          foreach($row['children'] as $child){
            $data = [
            'name'=>$child['title'],
            'group_name'=>$row['title'],
            'guard_name'=>"web",
            "path"=>isset($child['path'])?$child['path']:null,
            "active" =>isset($child['active'])?$child['active'] : null,
            "icon" =>isset($child['icon'])?$child['icon'] : null,
            "type" =>isset($child['type'])?$child['type'] : null,
            "level" =>isset($child['level'])?$child['level'] : null,
            "is_show" =>1,
            "parent_id"=>$permission_id,
            "display_order"=>isset($child['id'])?$child['id']:null
            ];
             $PermissionsModels->insert($data);
          }
        }else{
          $permission_id = null;
        }
      }
      
      $message = [
        'message' => 'Successfully Updated'
    ];

    return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);


    }

    public function getmenu_v2(){
        {
            try {      
                $UsersModel = new UsersModel();
                $RoleHasPermissionModel = new RoleHasPermissionModel();
                $PermissionsModel = new PermissionsModel();
    
                $user_id = $this->request->getGet('user_id');
                $user_data = $UsersModel->where('id', $user_id)->first();
                
                $role_query="SELECT * FROM `role_has_permissions` as rhp,`permissions` as p WHERE rhp.role_id=".$user_data["role_id"]." AND rhp.permission_id = p.id AND P.level='1'";
                // $role_query="SELECT * FROM `role_has_permissions` as rhp,`permissions` as p WHERE rhp.role_id=1 AND rhp.permission_id = p.id AND P.level='1'";
                $role_permission = $RoleHasPermissionModel->query($role_query)->getResult();
                
                $final_user_permission;
                $mocked_permission_list=[];
                foreach ($role_permission as $permission){
                  $children_list=$PermissionsModel->where('parent_id', $permission->id)->findAll();
                  
                  $final_user_permission["id"]=intval($permission->id);
                  $final_user_permission["title"]=$permission->name;
                  // if(isset($permission->path) && $permission->path != "null"){
                  //   $final_user_permission["path"]=$permission->path;
                  // }
                  if(isset($permission->name) && $permission->name == "dashboard"){
                    $final_user_permission["path"]="/dashboard";
                  }
          
                  $final_user_permission["active"]=isset($permission->active)?($permission->active == '1'? true:false):false;
                  $final_user_permission["icon"]=$permission->icon;
                  $final_user_permission["type"]=$permission->type;
                  $final_user_permission["level"]=intval($permission->level);
                  
                  $final_childern;
                  $mocked_sub_permission_list=[];
                  foreach($children_list as $childern){
                    $final_childern["parent_id"]=intval($childern["parent_id"]);
                    $final_childern["title"]=$childern["name"];                   
                    $final_childern["path"]=$childern["path"];                                      
                    $final_childern["type"]=$childern["type"];
                    $final_childern["level"]=intval($childern["level"]);
                    $final_childern["permission"]=[];
                    // $childern["active"]=isset($childern["active"])?($childern["active"] == '1'? true:false):false;
                    array_push($mocked_sub_permission_list, $final_childern);
                  }
                  $final_user_permission["childern"]=$mocked_sub_permission_list;
                  array_push($mocked_permission_list, $final_user_permission);
                }
                $response = [
                    'data' => $mocked_permission_list,
                    'message_object' => [
                        'message' => 'Menu retrieved successfully',
                        'statusCode' => 200
                    ]
                ];
        
                return $this->respond($response);
            } catch (\Exception $e) {
                $error_message = [
                    'message_object' => [
                        'message' => 'Server Error',
                        'statusCode' => 500
                    ]
                ];
        
                return $this->failServerError($error_message);
            }
        }

    }
}