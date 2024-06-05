<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Home;
use App\Controllers\api\CartController;
use App\Controllers\api\WishlistsController;
use App\Controllers\api\OrdersController;
use App\Controllers\api\ProductCategorieController;
use App\Controllers\api\AuthController;
use App\Controllers\api\CustomerController;
use App\Controllers\api\ProductController;
use App\Controllers\api\SaleorderController;
/**
 * @var RouteCollection $routes
 */

// $routes->group("api", function ($routes) {
//     $routes->post("signup", "Signup::signup",['filter' => 'authFilter']);
//     $routes->post("otpverify", "Signup::otp_verification",['filter' => 'authFilter']);
//     $routes->post("signin", "Signup::login", ['filter' => 'authFilter']);
//     $routes->post("resendotp", "Signup::resendotp", ['filter' => 'authFilter']);

// });

$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {
    $routes->post('login', 'AuthController::login');
    $routes->post('register', 'AuthController::register');
    $routes->get('getproduct', 'ProductController::index');
    $routes->get('getcategorielist', 'ProductCategorieController::list');
    $routes->get('catprodlist', 'ProductCategorieController::cat_product');
    $routes->get('getSaleOrderData', 'SaleorderController::index');
    $routes->get('getCartData', 'CartController::index');
    $routes->post('addcart', 'CartController::addcart');
    $routes->post('removecart', 'CartController::removecart');
    $routes->post('deletecartitem', 'CartController::deletecartitem');
    $routes->get('getCustomerData', 'CustomerController::index');
    $routes->get('emailValidaion', 'AuthController::isEmailvalidation');
    $routes->post('updatedEmailPassword', 'AuthController::isUpdatedEmailPassword');
    // $routes->post('BankDetails', 'CustomerController::saveBankDetails');
    $routes->post('changePassword', 'AdminAuthController::changePassword');
    $routes->post('updateuserprofile', 'AdminAuthController::updateUserProfile');

    $routes->post('forgotPassword', 'AdminAuthController::forgetPassword');
    $routes->get('getCustomer', 'CustomerController::getCustomer');
    $routes->get('getOrderitems', 'ProductController::GetOrdersItem');


    // ORDER TYPE
    $routes->post('create', 'OrderTypeController::create');
    $routes->get('getById/(:any)', 'OrderTypeController::getById/$1');
    $routes->get('getAll', 'OrderTypeController::getAll');
    $routes->post('updateStatus/(:any)', 'OrderTypeController::updateStatus/$1');
    $routes->delete('deleteById/(:any)', 'OrderTypeController::deleteById/$1');
    //ORDER STATUS
    $routes->post('OrderStatus/create', 'OrderStatusController::create');
    $routes->get('OrderStatus/getById/(:any)', 'OrderStatusController::getById/$1');
    $routes->get('OrderStatus/getAll', 'OrderStatusController::getAll');
    $routes->post('OrderStatus/updateStatus/(:any)', 'OrderStatusController::updateStatus/$1');
    $routes->delete('OrderStatus/deleteById/(:any)', 'OrderStatusController::deleteById/$1');
    //VARIATIONS
    $routes->post('Variation/create', 'VariationController::Variationcreate');
    $routes->get('Variation/getById/(:num)', 'VariationController::variationgetById/$1');
    $routes->get('Variation/getAll', 'VariationController::variationgetAll');
    $routes->post('Variation/updateStatus/(:num)', 'VariationController::variationupdate/$1');
    $routes->delete('Variation/deleteById/(:num)', 'VariationController::variationdeleteById/$1');



    // $routes->get('getCustomer', 'CustomerController::getCustomer');   
    // $routes->post('PurchaseOrder', 'ProductController::PurchaseOrder');
    $routes->get('getPurchaseorders', 'ProductController::getPurchaseorders');
    $routes->get('getPurchase_order_Item', 'ProductController::getPurchaseorderItem');
    $routes->post('PurchaseOrderUpdate', 'ProductController::PurchaseOrderUpdate');




});

$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
    $routes->post('changePassword', 'AdminAuthController::changePassword');
    $routes->post('demochangesfortable', 'OrderControllerdemoo::dEMO');
    $routes->get('getpbrands', 'ProductController::getProductlistbyBrand');
    $routes->get('getproductsbylocation', 'ProductController::getProductByLocationId');


    $routes->post('sendnotifiedemail','UserController::sendEmailAndStoreData');
// Menu route


    $routes->get('getmenu', 'DashBoardController::getmenu');
    $routes->get('getmenu/v2', 'DashBoardController::getmenu_v2');

    $routes->get('batchgetmenu', 'DashBoardController::batchmenulist');


    $routes->get('get_Product_Details', 'ProductController::getProductDetails');



    $routes->post('forgotPassword', 'AdminAuthController::forgetPassword');
    $routes->post('createPurchaseOrder', 'ProductController::PurchaseOrder');
    $routes->post('editPurchaseOrder', 'ProductController::Edit_PurchaseOrder');
    
    $routes->post('login', 'AdminAuthController::login');
    $routes->get('getbrands', 'AdminCommonController::getBrand');
    $routes->post('brdstschg', 'AdminCommonController::BrandStatusChange');

    $routes->get('gettax', 'AdminCommonController::getTax');

    $routes->post('brdedit', 'AdminCommonController::BrandEdit');
    $routes->post('brndele', 'AdminCommonController::BrandDelete');
    $routes->post('brandcreate', 'AdminCommonController::BrandCreate');
    $routes->post('updateuserprofile', 'AdminAuthController::updateUserProfile');


    $routes->post('tax_togg', 'AdminCommonController::TaxStatusChange');
    $routes->post('taxcreate', 'AdminCommonController::taxCreate');
    $routes->post('products_delete', 'AdminCommonController::productsDelete');
    $routes->post('tax_delete', 'AdminCommonController::taxDelete');
    $routes->post('tax_edit', 'AdminCommonController::taxEdit');


    // delivermen
    $routes->get('deliverymen_get', 'AdminCommonController::getDeliveryMen');
    $routes->post('deliverymen_togg', 'AdminCommonController::DeliverymenStatusChange');
    $routes->post('deliverymen_create', 'AdminCommonController::DeliveryMenCreate');
    $routes->post('deliverymen_delete', 'AdminCommonController::DeliveryMenDelete');
    $routes->post('deliverymen_edit', 'AdminCommonController::DeliveryMenEdit');



    $routes->get('getcurrency', 'AdminCommonController::getcurrency');
    $routes->post('updatestauscurrency', 'AdminCommonController::currencyStatusChange');
    $routes->post('currencyedit', 'AdminCommonController::CurrencyEdit');
    $routes->post('currencyDelete', 'AdminCommonController::currencyDelete');
    $routes->post('currencyCreate', 'AdminCommonController::currencyCreate');
    $routes->get('getMedia', 'AdminCommonController::getmediadata');
    $routes->post('createmedia', 'AdminCommonController::mediaimagesCreate');

    $routes->get('getDashboardCardDetails', 'DashBoardController::getdashboardcarddetails');


    // ORDER TYPE
    $routes->post('create', 'OrderTypeController::create');
    $routes->get('getById/(:any)', 'OrderTypeController::getById/$1');
    $routes->get('getAll', 'OrderTypeController::getAll');
    $routes->post('updateStatus/(:any)', 'OrderTypeController::updateStatus/$1');
    $routes->post('updateStatusStatus/(:any)', 'OrderTypeController::updateStatusStatus/$1');
    $routes->delete('deleteById/(:any)', 'OrderTypeController::deleteById/$1');

    //ORDER STATUS
    
    $routes->post('change/order/paymentstatus', 'OrderStatusController::changesPaymentStatus', );
    $routes->post('change/order/status', 'OrderStatusController::changesOrderStatus', );
    $routes->post('change/order/paymentmethod', 'OrderStatusController::changesPaymentMethod', );

    $routes->post('OrderStatus/create', 'OrderStatusController::create');
    $routes->get('OrderStatus/getById/(:any)', 'OrderStatusController::getById/$1');
    $routes->get('OrderStatus/getAll', 'OrderStatusController::getAll');
    $routes->post('OrderStatus/updateStatus/(:any)', 'OrderStatusController::updateStatus/$1');
    $routes->post('OrderStatus/updateStatusStatus/(:any)', 'OrderStatusController::updateStatusStatus/$1');
    $routes->delete('OrderStatus/deleteById/(:any)', 'OrderStatusController::deleteById/$1');




    //VARIATIONS
    $routes->post('Variation/create', 'VariationController::Variationcreate');
    $routes->get('Variation/getById/(:num)', 'VariationController::variationgetById/$1');
    $routes->get('Variation/getAll', 'VariationController::variationgetAll');
    $routes->post('Variation/update/(:num)', 'VariationController::variationupdate/$1');
    $routes->delete('Variation/deleteById/(:num)', 'VariationController::variationdeleteById/$1');
    $routes->post('Variation/updateStatus/(:num)', 'VariationController::variationupdateStatus/$1');



    //Languages
    $routes->post('Language/create', 'LanguagesController::languageCreate');
    $routes->get('Language/getById/(:num)', 'LanguagesController::languageGetById/$1');
    $routes->get('Language/getAll', 'LanguagesController::languageGetAll');
    $routes->post('Language/updateStatus/(:num)', 'LanguagesController::languageUpdate/$1');
    $routes->delete('Language/deleteById/(:num)', 'LanguagesController::languageDeleteById/$1');
    $routes->post('Language/updateStatusstatus/(:num)', 'LanguagesController::languageUpdateStatus/$1');


    $routes->post('orderStatusUpdate', 'OrderStatusController::orderStatusUpdate');
    $routes->get('orderupdates', 'OrderUpdatesController::getByOrderId/$id');
    // Contactus 
    $routes->post('contactus/create', 'ContactusController::createcontactus');
    $routes->get('contactus/getContactusById/(:num)', 'ContactusController::getContactusById/$1');
    $routes->get('contactus/getAll', 'ContactusController::getAll');
    $routes->put('contactus/update/(:num)', 'ContactusController::updatecontactus/$1');
    $routes->delete('contactus/deleteById/(:num)', 'ContactusController::contactusdeleteById/$1');
    $routes->put('contactus/updatestatus/(:num)', 'ContactusController::updateStatusStatus/$1');


    //PRODUCT VARIATION
    // $routes->post('Product/create', 'ProductVariationsController::ProductVariationscreate');
    $routes->get('Product/getById/(:num)', 'ProductVariationsController::getById/$1');
    $routes->get('Product/getAll', 'ProductVariationsController::getAll');
    $routes->delete('Product/deleteById/(:num)', 'ProductVariationsController::deleteById/$1');
    $routes->put('Product/updateById', 'ProductVariationsController::updateById');

    //SALES CUSTOMER
    $routes->post('SalesCustomer/create', 'SalesCustomerController::create');
    $routes->get('SalesCustomer/getById/(:num)', 'SalesCustomerController::getById/$1');
    $routes->get('SalesCustomer/getAll', 'SalesCustomerController::getAll');
    $routes->post('SalesCustomer/update/(:num)', 'SalesCustomerController::update/$1');
    $routes->delete('SalesCustomer/delete/(:num)', 'SalesCustomerController::delete/$1');

    // Order Details

    $routes->get('getOrderitemss', 'ProductController::GetOrdersItem');


    //SDT List
    $routes->post('SDTime/create', 'ScheduleDeliveryTimeListController::createScheduleDelivery');
    $routes->get('SDTime/getById/(:num)', 'ScheduleDeliveryTimeListController::ScheduleDeliverygetById/$1');
    $routes->get('SDTime/getAll', 'ScheduleDeliveryTimeListController::getAllScheduleDelivery');
    $routes->put('SDTime/update', 'ScheduleDeliveryTimeListController::updateScheduleDeliveryById');
    $routes->post('SDTime/deleteById', 'ScheduleDeliveryTimeListController::deleteScheduleDeliveryById');

    $routes->get('getOrders', 'AdminCommonController::getAllOrders');
    $routes->get('adminuserdetails', 'AdminCommonController::getCustomer');


    $routes->get('get_products', 'AdminCommonController::get_products');
    $routes->post('product/create', 'AdminCommonController::productCreate');
    $routes->post('product/edit', 'AdminCommonController::productEdit');
    $routes->post('product/delete', 'AdminCommonController::productDelete');
    $routes->get('searchProduct', 'AdminCommonController::searchProduct');
    $routes->get('get_products_search', 'AdminCommonController::get_products_search');

    $routes->get('searchProductbyID', 'AdminCommonController::searchProductbyID');



             ////////////////productstockdetails////////////////////

    $routes->get('ProductstockDetails', 'ProductController::getProductstockDetails');
    $routes->get('getProductstockDetailsById/(:num)', 'ProductController::getProductstockDetailsById/$1');
    $routes->post('updateProductStock/(:num)', 'ProductController::updateProductStock/$1');



    $routes->get('get_units', 'AdminCommonController::get_units');
    $routes->post('unit/create', 'AdminCommonController::unitCreate');
    $routes->post('unit/edit', 'AdminCommonController::unitEdit');
    $routes->post('unit/delete', 'AdminCommonController::unitDelete');
    $routes->post('unit_status', 'AdminCommonController::unitStatus');

    $routes->get('get_category', 'AdminCommonController::get_category');
    $routes->post('category/create', 'AdminCommonController::categoryCreate');
    $routes->post('category/editt', 'AdminCommonController::categoryEdit');
    $routes->post('category/delete', 'AdminCommonController::categoryDelete');
    $routes->post('category_status', 'AdminCommonController::categoryStatus');


    $routes->post('deliveryman/status', 'AdminCommonController::deliverymanStatus');
    $routes->post('stock/update', 'AdminCommonController::stockUpdate');
    $routes->get('get_stocks', 'AdminCommonController::get_stocks');



    $routes->get('get_purchaseorder', 'AdminCommonController::get_purchaseorder');
    $routes->post('purchase_order_delete', 'AdminCommonController::purchaseOrderDelete');



    $routes->get('get_saleorder', 'AdminCommonController::get_saleorder');
    $routes->get('create_PurchaseOrder', 'PurchaseOrderController::PurchaseOrder');
    $routes->get('PurchaseOrder', 'PurchaseOrderController::PurchaseOrder');


//create and search
    $routes->post('usercreate', 'AdminCommonController::userCreate');
    $routes->get('SearchUsers', 'AdminCommonController::searchUser');


    // ---------------- purchase order return --------------------------------



    $routes->get('get_activesupplier', 'AdminCommonController::get_activesupplier');
    $routes->get('get_PObysupplier', 'AdminCommonController::get_PObysupplier');
    $routes->get('get_PObypurchaseorderid', 'AdminCommonController::get_PObypurchaseorderid');
    $routes->get('get_PObypurchaseorderid', 'AdminCommonController::get_PObypurchaseorderid');


    $routes->post('demo', 'PurchaseOrderController::dEMO');
    $routes->post('demoPut', 'PurchaseOrderController::updateDEMO');





    //POpayment
    $routes->post('popayment', 'ProductController::POpaymnet');
    $routes->post('getpopayment', 'ProductController::getPOpayment');

    //SOPayment
    $routes->post('sopayment', 'ProductController::SOpaymnet');
    $routes->post('getsopayment', 'ProductController::getSOpayment');


    $routes->get('get_roles', 'AdminCommonController::get_roles');
    $routes->post('role/create', 'AdminCommonController::roleCreate');
    $routes->post('role/edit', 'AdminCommonController::roleEdit');
    $routes->post('role/delete', 'AdminCommonController::roleDelete');
    $routes->post('role_status', 'AdminCommonController::roleStatus');
    $routes->get('role', 'AdminCommonController::get_role_using_id');
    $routes->get('role/v2', 'AdminCommonController::get_role_using_id_mocked');
    $routes->get('permission/list', 'AdminCommonController::get_permissions_list');
    $routes->post('role/createOrEdit', 'AdminCommonController::create_or_edit_role_permissions');



    $routes->get('get_suppliers', 'AdminCommonController::get_suppliers');
    $routes->get('get_suppliersbyid', 'AdminCommonController::get_supplier_by_id');
    $routes->post('supplier/create', 'AdminCommonController::supplierCreate');
    $routes->post('supplier/edit', 'AdminCommonController::supplierEdit');


    $routes->get('getdemosuppliers/(:num)', 'AdminCommonController::getSupplierDetails/$1');

    // $routes->post('tax_edit', 'AdminCommonController::taxEdit');

    $routes->post('supplier/delete', 'AdminCommonController::supplierDelete');
    $routes->post('supplier_status', 'AdminCommonController::supplierStatus');


    $routes->get('get_suppliersaddress', 'AdminCommonController::get_suppliersaddress');
    $routes->post('supplierAddress/create', 'AdminCommonController::supplierAddressCreate');
    $routes->post('supplierAddress/edit', 'AdminCommonController::supplierAddressEdit');
    $routes->post('supplierAddress/delete', 'AdminCommonController::supplierAddressDelete');
    $routes->post('supplierAddress_status', 'AdminCommonController::supplierAddressStatus');
    $routes->get('get_products/category', 'AdminCommonController::get_productsbycategory');



    //Customer
    $routes->get('get_Customer', 'AdminCommonController::get_userData');
    $routes->post('user_togg', 'AdminCommonController::userStatusChange');


    //AdminUser
    $routes->get('getAdminuser', 'AdminCommonController::get_adminuserData');
    $routes->post('update_adminuseData', 'AdminCommonController::updateAdminUserData');
    $routes->delete('deleteAdmin', 'AdminCommonController::deleteAdminUserData');
    $routes->post('updateAdminuserstatus/(:num)', 'AdminCommonController::AdminUpdtaeStatus/$1');

    $routes->post('createAdminuser', 'AdminCommonController::admin_Create');
    $routes->post('adminDelete', 'AdminCommonController::admin_Delete');
    $routes->post('adminEdit', 'AdminCommonController::admin_Edit');

    // Reports
    $routes->get('salesreport', 'ReportsController::salesReport');
    $routes->get('categorysalesreport', 'ReportsController::CategorySalesReport');

    $routes->get('deliveryreport', 'ReportsController::DeliveryReport');
    $routes->get('highsalesreport', 'ReportsController::carddetailsforsales');  
    $routes->get('deliveryreportcart', 'ReportsController::DeliveryReportCart');
    $routes->get('highsalesreportBrandandcatagories', 'ReportsController::carddetailsforsalesBrandandCatagories');  
    
    // Delivery Report Today,7Days,30Days GET ROUTE
    $routes->get('deliverystatuscart', 'ReportsController::getdeliveryStatusCountToday');
    $routes->get('deliverystatuscart7days', 'ReportsController::getdeliveryStatusCount7Days');
    $routes->get('deliverystatuscart30days', 'ReportsController::getdeliveryStatusCount30Days');


    $routes->post('deliverystatusByDate', 'ReportsController::getDeliveryStatus');
    $routes->post('deliverystatusReportByDate', 'ReportsController::getDeliveryStatusReport');




    // location
    $routes->get('getalllocation', 'AdminCommonController::getlocation');
    $routes->get('locationbystock', 'AdminCommonController::locationbystock');
    $routes->get('getStockbyproduct', 'AdminCommonController::getStockbyproduct');



    // $routes->get('getCustomer', 'CustomerController::getCustomer');   
    // $routes->post('PurchaseOrder', 'ProductController::PurchaseOrder');
    $routes->post('saleorderaddedit', 'SaleOrderController::SaleOrderAddEdit');

    $routes->post('posplaceorder', 'OrderController::posplaceorder');


    $routes->get('getPurchaseorders', 'ProductController::getPurchaseorders');
    $routes->post('getPurchase_order_Item', 'ProductController::getPurchaseorderItem');
    $routes->post('PurchaseOrderUpdate', 'ProductController::PurchaseOrderUpdate');

    $routes->get('po_order_get_byId', 'ProductController::getpurchaseorderbyId');
    $routes->get('getpurchaseorder_return', 'ProductController::getpurcharorderreturn');
    $routes->get('so_order_get_byId', 'ProductController::getsaleorderbyId');

    $routes->post('purchaseOrderReturnCreate', 'PurchaseOrderController::purchaseOrderReturnCreate');
    $routes->get('get_purchaseorder_return', 'PurchaseOrderController::getPurchaseorderReturn');
    $routes->post('getPurchaseorderReturnById', 'PurchaseOrderController::getPurchaseorderReturnById');
    $routes->post('PurchaseReturn/delete', 'PurchaseOrderController::deletePurchaseorderReturnById');

    // $routes->post('purchaseOrderReturnCreate', 'PurchaseOrderController::purchaseOrderReturnCreate');
    $routes->put('purchaseReturnEdit','PurchaseOrderController::purchaseOrderEdit');


    $routes->post('PurchaseOrdercreate', 'PurchaseOrderController::PurchaseOrdercreate');



    $routes->post('update/setting', 'AdminCommonController::updateSetting');
    $routes->get('get/setting', 'AdminCommonController::getSettings');


    $routes->get('getcategoriesproduct', 'ProductCategorieController::getCategorieslistProducts');

    $routes->get('autogetSaleorder', 'SaleOrderController::autogetSaleorder');
    $routes->post('saleorderreturn', 'SaleOrderController::saleorderreturn');
    $routes->get('get_SaleorderDetails', 'SaleOrderController::getSaleorderDetails');
    $routes->get('get_ordercount', 'SaleOrderController::getOrderCount');
    $routes->post('getsaleorderdetailsbyid', 'SaleOrderController::getsaleorderdetailsbyid');
    $routes->post('getsaleorderdetailByIdNew', 'SaleOrderController::getsaleorderdetailByIdNew');
    $routes->post('SalesOrder/delete', 'SaleOrderController::saleOrderDelete');
    $routes->post('sales_order_return/delete', 'SaleOrderController::saleOrderReturnDelete');

    $routes->get('getuseraddress', 'UserController::getuserAddress');
    $routes->get('getusersmail', 'UserController::getmailcheck');



    //PAYMENTGETWAY 

    $routes->post('paymentgatway','PaymentgatwayController::paymentGatway');
    $routes->get('getDeliveryman','AdminCommonController::getDeliveryman');
    $routes->post('savedelivery','AdminCommonController::savedelivery');

    $routes->get('salesordergetlist','SaleOrderController::salesordergetlist');
    $routes->get('getsaleorderreturnbyid','SaleOrderController::getsaleorderreturnbyid');
    $routes->get('getsaleorderreturnbyidview','SaleOrderController::getsaleorderreturnbyidview');


    



   //Petty cash 
   $routes->post('addPettyCash', 'PettyCashController::addPettyCash');
   $routes->get('getPettyCash', 'PettyCashController::getPettyCash');
   $routes->get('getcounter', 'CounterNumberController::getCounterNumber');
   $routes->post('getallPettyCash', 'PettyCashController::getPettyCash1');

   $routes->post('addExpense', 'PettyCashController::addExpense');
   $routes->get('getExpenses', 'PettyCashController::getExpenses');
   $routes->get('getBalance', 'PettyCashController::getBalance');
   $routes->get('getAllTransactions', 'PettyCashController::getAllTransactions');
   $routes->post('editexpense/(:num)', 'PettyCashController::editExpense/$1');
   $routes->post('updateRecon', 'PettyCashController::updatePettyCash');
   $routes->post('editpettyCash','PettyCashController::editPettyCash');


});


$routes->group('authapi', ['namespace' => 'App\Controllers\Api'], function ($routes) {
    $routes->post('confirm-order', 'SaleorderController::confirmOrder', ['namespace' => 'App\Controllers\Api']);
});
$routes->get('verify-email', 'AuthController::verifyEmail', ['namespace' => 'App\Controllers\Api']);
$routes->post('logincheck', 'AuthController::login', ['namespace' => 'App\Controllers\Api']);





$routes->get('/', [Home::class, 'index']);
$routes->post('/check', [Home::class, 'check']);





