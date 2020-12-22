<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();
Route::group(['middleware' => 'auth'], function() {
	Route::get('/dashboard', function () {
	    return view('home');
	});
});

Route::group(['middleware' => ['auth', 'active']], function() {

	Route::get('/', 'HomeController@index');
	Route::get('/dashboard-filter/{start_date}/{end_date}', 'HomeController@dashboardFilter');

	Route::get('language_switch/{locale}', 'LanguageController@switchLanguage');

	Route::get('role/permission/{id}', 'RoleController@permission')->name('role.permission');
	Route::post('role/set_permission', 'RoleController@setPermission')->name('role.setPermission');
	Route::resource('role', 'RoleController');

	Route::post('category/import', 'CategoryController@import')->name('category.import');
	Route::resource('category', 'CategoryController');

	Route::get('brand/ezpos_brand_search', 'BrandController@ezposBrandSearch')->name('brand.search');
	Route::resource('brand', 'BrandController');

	Route::resource('supplier', 'SupplierController');

	Route::resource('store', 'StoreController');

	Route::resource('tax', 'TaxController');

	Route::get('products/gencode', 'ProductController@generateCode');
	Route::get('products/saleunit/{id}', 'ProductController@saleUnit');
	Route::post('products/demo', 'ProductController@demo');
	Route::get('products/product_store/{id}', 'ProductController@productstoreData');
	Route::post('importproduct', 'ProductController@importProduct')->name('product.import');
	Route::post('exportproduct', 'ProductController@exportProduct')->name('product.export');
	Route::get('products/print_barcode','ProductController@printBarcode')->name('product.printBarcode');
	Route::get('products/ezpos_product_search', 'ProductController@ezposProductSearch')->name('product.search');
	Route::resource('products', 'ProductController');

	Route::resource('customer_group', 'CustomerGroupController');

	Route::get('customer/getDeposit/{id}', 'CustomerController@getDeposit');
	Route::post('customer/add_deposit', 'CustomerController@addDeposit')->name('customer.addDeposit');
	Route::post('customer/update_deposit', 'CustomerController@updateDeposit')->name('customer.updateDeposit');
	Route::post('customer/deleteDeposit', 'CustomerController@deleteDeposit')->name('customer.deleteDeposit');
	Route::resource('customer', 'CustomerController');

	Route::get('sale/product_sale/{id}','SaleController@productSaleData');
	Route::get('pos', 'SaleController@posSale')->name('sale.pos');
	Route::get('sale/ezpos_sale_search', 'SaleController@ezposSaleSearch')->name('sale.search');
	Route::get('sale/ezpos_product_search', 'SaleController@ezposProductSearch')->name('product_sale.search');
	Route::get('sale/getcustomergroup/{id}', 'SaleController@getCustomerGroup')->name('sale.getcustomergroup');
	Route::get('sale/getproduct/{id}', 'SaleController@getProduct')->name('sale.getproduct');
	Route::get('sale/getproduct/{category_id}/{brand_id}', 'SaleController@getProductByFilter');
	Route::get('sale/getfeatured', 'SaleController@getFeatured');
	Route::get('sale/get_gift_card', 'SaleController@getGiftCard');
	Route::get('sale/paypalSuccess', 'SaleController@paypalSuccess');
	Route::get('sale/paypalPaymentSuccess/{id}', 'SaleController@paypalPaymentSuccess');
	Route::get('sale/gen_invoice/{id}', 'SaleController@genInvoice')->name('sale.invoice');
	Route::post('sale/add_payment', 'SaleController@addPayment')->name('sale.add-payment');
	Route::get('sale/getpayment/{id}', 'SaleController@getPayment')->name('sale.get-payment');
	Route::post('sale/updatepayment', 'SaleController@updatePayment')->name('sale.update-payment');
	Route::post('sale/deletepayment', 'SaleController@deletePayment')->name('sale.delete-payment');
	Route::get('sale/{id}/create', 'SaleController@createSale');
	Route::resource('sale', 'SaleController');

	Route::get('delivery', 'DeliveryController@index')->name('delivery.index');
	Route::get('delivery/create/{id}', 'DeliveryController@create');
	Route::post('delivery/store', 'DeliveryController@store')->name('delivery.store');
	Route::get('delivery/{id}/edit', 'DeliveryController@edit');
	Route::post('delivery/update', 'DeliveryController@update')->name('delivery.update');
	Route::post('delivery/delete/{id}', 'DeliveryController@delete')->name('delivery.delete');

	Route::get('purchase/product_purchase/{id}','PurchaseController@productPurchaseData');
	Route::get('purchase/ezpos_product_search', 'PurchaseController@ezposProductSearch')->name('product_purchase.search');
	Route::post('purchase/add_payment', 'PurchaseController@addPayment')->name('purchase.add-payment');
	Route::get('purchase/getpayment/{id}', 'PurchaseController@getPayment')->name('purchase.get-payment');
	Route::post('purchase/updatepayment', 'PurchaseController@updatePayment')->name('purchase.update-payment');
	Route::post('purchase/deletepayment', 'PurchaseController@deletePayment')->name('purchase.delete-payment');
	Route::resource('purchase', 'PurchaseController');

	Route::get('transfers/product_transfer/{id}','TransferController@productTransferData');
	Route::get('transfers/getproduct/{id}', 'TransferController@getProduct')->name('transfer.getproduct');
	Route::get('transfers/ezpos_product_search', 'TransferController@ezposProductSearch')->name('product_transfer.search');
	Route::resource('transfers', 'TransferController');

	Route::get('qty_adjustment/getproduct/{id}', 'AdjustmentController@getProduct')->name('adjustment.getproduct');
	Route::get('qty_adjustment/ezpos_product_search', 'AdjustmentController@ezposProductSearch')->name('product_adjustment.search');
	Route::resource('qty_adjustment', 'AdjustmentController');

	Route::get('return-sale/getcustomergroup/{id}', 'ReturnController@getCustomerGroup')->name('return.getcustomergroup');
	Route::get('return-sale/getproduct/{id}', 'ReturnController@getProduct')->name('return.getproduct');
	Route::get('return-sale/ezpos_product_search', 'ReturnController@ezposProductSearch')->name('product_return.search');
	Route::get('return-sale/product_return/{id}','ReturnController@productReturnData');
	Route::resource('return-sale', 'ReturnController');

	Route::get('return-purchase/getcustomergroup/{id}', 'ReturnPurchaseController@getCustomerGroup')->name('return-purchase.getcustomergroup');
	Route::post('return-purchase/sendmail', 'ReturnPurchaseController@sendMail')->name('return-purchase.sendmail');
	Route::get('return-purchase/getproduct/{id}', 'ReturnPurchaseController@getProduct')->name('return-purchase.getproduct');
	Route::get('return-purchase/ezpos_product_search', 'ReturnPurchaseController@ezposProductSearch')->name('product_return-purchase.search');
	Route::get('return-purchase/product_return/{id}','ReturnPurchaseController@productReturnData');
	Route::post('return-purchase/deletebyselection', 'ReturnPurchaseController@deleteBySelection');
	Route::resource('return-purchase', 'ReturnPurchaseController');

	Route::get('report/product_quantity_alert', 'ReportController@productQuantityAlert')->name('report.qtyAlert');
	Route::get('report/store_stock', 'ReportController@storeStock')->name('report.storeStock');
	Route::post('report/store_stock', 'ReportController@storeStockById')->name('report.storeStock');
	Route::get('report/daily_sale/{year}/{month}', 'ReportController@dailySale');
	Route::post('report/daily_sale/{year}/{month}', 'ReportController@dailySaleByStore')->name('report.dailySaleByStore');
	Route::get('report/monthly_sale/{year}', 'ReportController@monthlySale');
	Route::post('report/monthly_sale/{year}', 'ReportController@monthlySaleByStore')->name('report.monthlySaleByStore');
	Route::get('report/daily_purchase/{year}/{month}', 'ReportController@dailyPurchase');
	Route::post('report/daily_purchase/{year}/{month}', 'ReportController@dailyPurchaseByStore')->name('report.dailyPurchaseByStore');
	Route::get('report/monthly_purchase/{year}', 'ReportController@monthlyPurchase');
	Route::post('report/monthly_purchase/{year}', 'ReportController@monthlyPurchaseByStore')->name('report.monthlyPurchaseByStore');
	Route::get('report/best_seller', 'ReportController@bestSeller');
	Route::post('report/best_seller', 'ReportController@bestSellerByStore')->name('report.bestSellerByStore');
	Route::post('report/profit_loss', 'ReportController@profitLoss')->name('report.profitLoss');
	Route::post('report/product_report', 'ReportController@productReport')->name('report.product');
	Route::post('report/purchase', 'ReportController@purchaseReport')->name('report.purchase');
	Route::post('report/sale_report', 'ReportController@saleReport')->name('report.sale');
	Route::post('report/payment_report_by_date', 'ReportController@paymentReportByDate')->name('report.paymentByDate');
	Route::post('report/customer_report', 'ReportController@customerReport')->name('report.customer');
	Route::post('report/supplier', 'ReportController@supplierReport')->name('report.supplier');
	Route::post('report/due_report_by_date', 'ReportController@dueReportByDate')->name('report.dueByDate');

	Route::get('user/profile/{id}', 'UserController@profile')->name('user.profile');
	Route::put('user/update_profile/{id}', 'UserController@profileUpdate')->name('user.profileUpdate');
	Route::put('user/changepass/{id}', 'UserController@changePassword')->name('user.password');
	Route::get('user/genpass', 'UserController@generatePassword');
	Route::resource('user','UserController');

	Route::get('setting/general_setting', 'SettingController@generalSetting')->name('setting.general');
	Route::post('setting/general_setting_store', 'SettingController@generalSettingStore')->name('setting.generalStore');
	Route::get('setting/mail_setting', 'SettingController@mailSetting')->name('setting.mail');
	Route::post('setting/mail_setting_store', 'SettingController@mailSettingStore')->name('setting.mailStore');
	Route::get('setting/pos_setting', 'SettingController@posSetting')->name('setting.pos');
	Route::post('setting/pos_setting_store', 'SettingController@posSettingStore')->name('setting.posStore');
	Route::get('setting/empty-database', 'SettingController@emptyDatabase')->name('setting.emptyDatabase');

	Route::get('expense_categories/gencode', 'ExpenseCategoryController@generateCode');
	Route::resource('expense_categories', 'ExpenseCategoryController');

	Route::resource('expenses', 'ExpenseController');

	Route::get('gift_cards/gencode', 'GiftCardController@generateCode');
	Route::post('gift_cards/recharge/{id}', 'GiftCardController@recharge')->name('gift_cards.recharge');
	Route::resource('gift_cards', 'GiftCardController');

	Route::get('stock-count', 'StockCountController@index')->name('stock-count.index');
	Route::post('stock-count/store', 'StockCountController@store')->name('stock-count.store');
	Route::post('stock-count/finalize', 'StockCountController@finalize')->name('stock-count.finalize');
	Route::get('stock-count/stockdif/{id}', 'StockCountController@stockDif');
	Route::get('stock-count/{id}/qty_adjustment', 'StockCountController@qtyAdjustment')->name('stock-count.adjustment');
	
	Route::get('/home', 'HomeController@index')->name('home');
});

