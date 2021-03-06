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

//Route::get('/', function () {
//    return view('welcome');
//});

// Index Page

Route::get('/', 'IndexController@index');

// List Category

Route::get('/products/{url}', 'ProductsController@products');
Route::get('/product/{id}', 'ProductsController@product');
Route::match(['get', 'post'], '/products/filter', 'ProductsController@filter');
Route::any('/get-product-price', 'ProductsController@getProductPrice');
Route::post('/cart/apply-coupon', 'ProductsController@applyCoupon');
Route::get('/cart/remove-coupon/', 'ProductsController@removeCoupon');

// Route::get('/admin','AdminController@login');
Route::match(['get', 'post'], '/admin', 'AdminController@login');
// Route::get('/admin/dashboard', 'AdminController@dashboard');
Route::get('/logout', 'AdminController@logout');

Route::match(['get', 'post'], '/add-cart', 'ProductsController@addToCart');
Route::match(['get', 'post'], '/cart', 'ProductsController@cart');
Route::get('/cart/delete-product/{id}', 'ProductsController@deleteCartProduct');
Route::get('/cart/update-quantity/{id}/{quantity}', 'ProductsController@updateCartQuantity');

Route::get('/login-register', 'UsersController@userLoginRegister');
Route::match(['get', 'post'], '/forgot-password', 'UsersController@forgotPassword');
Route::post('/user-register', 'UsersController@register');
Route::get('/confirm/{code}', 'UsersController@confirmAccount');
Route::post('/user-login', 'UsersController@login');
Route::match(['get', 'post'], '/check-email', 'UsersController@checkEmail');
Route::get('/user-logout', 'UsersController@logout');
Route::match(['get', 'post'], '/search-products', 'ProductsController@searchProducts');
Route::post('/check-pincode', 'ProductsController@checkPincode');
Route::post('/check-subscriber-email', 'NewsletterController@checkSubscriber');
Route::post('/add-subscriber-email', 'NewsletterController@addSubscriber');

Route::match(['get', 'post'], '/page/contact', 'CmsController@contact');
Route::match(['get', 'post'], '/page/{url}', 'CmsController@cmsPage');

Route::group(['middleware' => ['frontlogin']], function () {
    Route::match(['get', 'post'], '/account', 'UsersController@account');
    Route::post('/check-user-pwd', 'UsersController@chkUserPassword');
    Route::post('/update-user-pwd', 'UsersController@updatePassword');
    Route::match(['get', 'post'], '/checkout', 'ProductsController@checkout');
    Route::match(['get', 'post'], '/order-review', 'ProductsController@orderReview');
    Route::match(['get', 'post'], '/place-order', 'ProductsController@placeOrder');
    Route::get('/thanks', 'ProductsController@thanks');
    Route::get('/paypal', 'ProductsController@paypal');
    Route::get('/orders', 'ProductsController@userOrders');
    Route::get('/orders/{id}', 'ProductsController@userOrderDetails');
    Route::get('/paypal/thanks', 'ProductsController@thanksPaypal');
    Route::get('/paypal/cancel', 'ProductsController@cancelPaypal');
    Route::post('/paypal/ipn', 'ProductsController@ipnPaypal');
    Route::any('/payumoney', 'PayumoneyController@payumoneyPayment');
    Route::any('/payumoney/response', 'PayumoneyController@payumoneyResponse');
    Route::get('/payumoney/thanks', 'PayumoneyController@payumoneyThanks');
    Route::get('/payumoney/failure', 'PayumoneyController@payumoneyFailure');
    Route::get('/payumoney/verification/{id}', 'PayumoneyController@payumoneyVerification');
    Route::get('/payumoney/verify', 'PayumoneyController@payumoneyVerify');
    Route::match(['get', 'post'], '/wish-list', 'ProductsController@wishList');
    Route::get('wish-list/delete-product/{id}', 'ProductsController@deleteWishList');
});

Route::group(['middleware' => ['adminlogin']], function () {
    // Admin Routes

    Route::get('/admin/dashboard', 'AdminController@dashboard');
    Route::get('/admin/settings', 'AdminController@settings');
    Route::get('/admin/check-pwd', 'AdminController@chkPassword');
    Route::match(['get', 'post'], '/admin/update-pwd', 'AdminController@updatePassword');

    // Category Routes

    Route::match(['get', 'post'], '/admin/add-category', 'CategoryController@addCategory');
    Route::get('/admin/view-categories', 'CategoryController@viewCategory');
    Route::match(['get', 'post'], '/admin/edit-category/{id}', 'CategoryController@editCategory');
    Route::match(['get', 'post'], '/admin/delete-category/{id}', 'CategoryController@deleteCategory');

    // Product Routes

    Route::match(['get', 'post'], '/admin/add-product', 'ProductsController@addProduct');
    Route::get('/admin/view-products', 'ProductsController@viewProducts');
    Route::match(['get', 'post'], '/admin/edit-product/{id}', 'ProductsController@editProduct');
    Route::get('/admin/delete-product/{id}', 'ProductsController@deleteProduct');
    Route::get('/admin/delete-product-image/{id}', 'ProductsController@deleteProductImage');
    Route::get('/admin/delete-product-video/{id}', 'ProductsController@deleteProductImage');
    Route::get('/admin/export-products', 'ProductsController@exportProducts');

    // Product Attributes Routes

    Route::match(['get', 'post'], '/admin/add-attributes/{id}', 'ProductsController@addAttributes');
    Route::match(['get', 'post'], '/admin/edit-attributes/{id}', 'ProductsController@editAttributes');
    Route::match(['get', 'post'], '/admin/add-images/{id}', 'ProductsController@addImages');
    Route::get('/admin/delete-attribute/{id}', 'ProductsController@deleteAttribute');
    Route::get('/admin/delete-additional-image/{id}', 'ProductsController@deleteAdditionalImage');

    // Coupon Routes

    Route::match(['get', 'post'], '/admin/add-coupon', 'CouponsController@addCoupon');
    Route::get('/admin/view-coupons', 'CouponsController@viewCoupons');
    Route::match(['get', 'post'], '/admin/edit-coupon/{id}', 'CouponsController@editCoupon');
    Route::get('/admin/delete-coupon/{id}', 'CouponsController@deleteCoupon');

    // Banner Routes

    Route::match(['get', 'post'], '/admin/add-banner', 'BannersController@addBanner');
    Route::get('/admin/view-banners', 'BannersController@viewBanners');
    Route::match(['get', 'post'], '/admin/edit-banner/{id}', 'BannersController@editBanner');
    Route::get('/admin/delete-banner/{id}', 'BannersController@deleteBanner');

    // Orders Routes

    Route::get('/admin/view-orders', 'ProductsController@viewOrders');
    Route::get('/admin/view-order/{id}', 'ProductsController@viewOrdersDetails');
    Route::get('/admin/view-order-invoice/{id}', 'ProductsController@viewOrdersInvoice');
    Route::get('/admin/print-order-invoice/{id}', 'ProductsController@viewOrdersInvoice');
    Route::post('/admin/update-order-status', 'ProductsController@updateOrderStatus');
    Route::get('/admin/view-pdf-invoice/{id}', 'ProductsController@viewPDFInvoice');
    Route::get('/admin/view-orders-analysis', 'ProductsController@viewOrdersAnalysis');

    // Users Routes

    Route::get('/admin/view-users', 'UsersController@viewUsers');
    Route::get('/admin/export-users', 'UsersController@exportUsers');
    Route::get('/admin/view-users-analysis', 'UsersController@viewUsersAnalysis');
    Route::get('/admin/view-users-countries-analysis', 'UsersController@viewUsersCountriesAnalysis');

    // CMS Pages Routes

    Route::match(['get', 'post'], '/admin/add-cms-page', 'CmsController@addCmsPage');
    Route::get('/admin/view-cms-pages/', 'CmsController@viewCmsPages');
    Route::match(['get', 'post'], '/admin/edit-cms-page/{id}', 'CmsController@editCmsPage');
    Route::get('/admin/delete-cms-page/{id}', 'CmsController@deleteCmsPage');

    // Currency Routes

    Route::match(['get', 'post'], '/admin/add-currency', 'CurrencyController@addCurrency');
    Route::get('/admin/view-currencies', 'CurrencyController@viewCurrency');
    Route::match(['get', 'post'], '/admin/edit-currency/{id}', 'CurrencyController@editCurrency');
    Route::get('/admin/delete-currency/{id}', 'CurrencyController@deleteCurrency');

    // Shipping Charges Route

    Route::get('/admin/view-shipping-charges', 'ShippingController@viewShippingCharges');
    Route::match(['get', 'post'], '/admin/edit-shipping-charge/{id}', 'ShippingController@editShippingCharge');
    // Route::get('/admin/delete-shipping-charge/{id}', 'ShippingController@deleteShippingCharge');

    // Admin/Sub-Admin Routes

    Route::match(['get', 'post'], '/admin/add-admin', 'AdminController@addAdmin');
    Route::get('/admin/view-admins', 'AdminController@viewAdmins');
    Route::match(['get', 'post'], '/admin/edit-admin/{id}', 'AdminController@editAdmin');
    Route::get('/admin/delete-admin/{id}', 'AdminController@deleteAdmin');

    // View Newsletter Subscribers

    Route::get('/admin/view-newsletter-subscribers', 'NewsletterController@viewSubscribers');
    Route::get('/admin/update-newsletter-status/{id}/{status}', 'NewsletterController@updateSubscriber');
    Route::get('/admin/delete-newsletter-subscriber/{id}', 'NewsletterController@deleteSubscriber');
    Route::get('/admin/export-newsletter-subscribers', 'NewsletterController@exportSubscribers');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');
