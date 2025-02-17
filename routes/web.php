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


/*
************************ Auth Elements ***********************
 */
//dd(bcrypt(123456789));
Auth::routes();
Route::post('register-validate', 'UserController@registerValidate')->name('validate');


/*
************************ Website ***********************
 */
//Route::get('/', 'HomeController@index');
Route::get('/', 'WebController@welcome');
Route::get('about', 'WebController@about');
Route::get('documents', 'WebController@documents');
Route::get('contacts', 'WebController@contacts');
Route::get('web-news', 'WebController@webNews');
Route::get('web-news/{id}', 'WebController@newsInner');
Route::get('marketing-plan', 'WebController@marketing');
Route::get('business', 'WebController@business');
Route::get('benefits', 'WebController@benefits');
Route::get('promotion', 'WebController@promotion');
Route::get('rules', 'WebController@rules');
Route::get('products', 'WebController@products');
Route::get('web-product/{id}', 'WebController@webProduct');


Route::get('faq', 'WebController@faq');

/*
************************ Profile ***********************
 */
Route::get('home', 'HomeController@index')->name('home')->middleware("auth");
Route::get('invitations', 'HomeController@invitations')->name('invitations')->middleware("auth");
Route::get('hierarchy', 'HomeController@hierarchy')->name('hierarchy')->middleware("auth");
Route::get('hierarchy/{id}', 'HomeController@hierarchyTree')->name('hierarchyTree')->middleware("auth");
Route::get('get_inviters_hierarchy', 'HomeController@getInvitersHierarchy')->name('hierarchy')->middleware("auth");
Route::get('tree/{id}', 'HomeController@tree')->name('tree')->middleware("auth");
Route::get('team', 'HomeController@team')->name('team')->middleware("auth");
Route::get('user_processing', 'HomeController@processing')->name('processing')->middleware("auth");
Route::get('programs' , 'HomeController@programs')->name('programs')->middleware("auth");
Route::get('notifications', 'HomeController@notifications')->name('notifications')->middleware("auth");
Route::get('profile', 'HomeController@profile')->name('profile')->middleware("auth");
Route::post('/request', 'ProcessingController@request')->name('request');
Route::get('faq-profile','FaqController@index')->middleware("auth");
Route::post('updateProfile', 'HomeController@updateProfile')->name('updateProfile');
Route::post('updateAvatar', 'HomeController@updateAvatar')->name('updateAvatar');
Route::get('fortune_wheel', 'HomeController@fortuneWheel')->name('fortune_wheel');
Route::get('fortune_wheel_access/{user_id}', 'HomeController@fortuneWheelAccess')->name('fortune_wheel_access');
Route::get('fortune_wheel_attempt/{user_id}/{success}', 'HomeController@fortuneWheelAttempt')->name('fortune_wheel_attempt');

Route::get('marketing', 'HomeController@marketing')->name('marketing');

/*
************************ Shop ***********************
 */
Route::get('product/{id}','StoreController@show')->middleware("activation");
Route::get('story-store', 'StoreController@story')->middleware("activation");
Route::get('activation-calendar', 'StoreController@activationCalendar')->middleware("activation");
Route::get('main-store', 'StoreController@store')->middleware("activation");

/*
************************ Not Used ***********************
 */
Route::get('reviews', 'WebController@reviews')->name('reviews');
Route::get('review/{id}/view', 'WebController@review')->name('review_id');
Route::get('my_reviews', 'HomeController@my_reviews')->name('my_reviews');
Route::post('reviews/like', 'HomeController@reviewsLike')->name('reviews_like');
Route::post('comments/like', 'HomeController@commentsLike')->name('comments_like');
Route::get('review/{id}/edit', 'HomeController@review')->name('review_edit');
Route::get('review/add', 'HomeController@review')->name('review_add');
Route::post('review/{id}/view', 'HomeController@commentAdd')->name('comment_add');
Route::post('review', 'HomeController@updateReview')->name('review');
Route::post('update_review_image', 'HomeController@updateReviewImage')->name('updateReviewImage');

Route::get('partner/create', 'HomeController@partner')->name('partner_create');
Route::post('partner/create', 'HomeController@partnerStore')->name('partner_store');
Route::get('partner/sponsor/users', 'HomeController@partnerSponsorUsers')->name("partner_sponsor_users");
Route::get('partner/sponsor/positions', 'HomeController@partnerSponsorPositions')->name("partner_sponsor_positions");
Route::get('partner/user/offices', 'HomeController@partnerUserOffices')->name("partner_user_offices");

Route::get('rang-history', 'UserController@rangHistory')->middleware("activation");// скоро нужно удалить

Route::post('transfer', 'ProcessingController@transfer')->name('transfer');
Route::get('transfer/{status}/{processing_id}', 'ProcessingController@transferAnswer');
Route::post('/transfer', 'ProcessingController@transfer')->name('transfer');
Route::get('/transfer/{status}/{processing_id}', 'ProcessingController@transferAnswer');


/*
************************ Pay Elements ***********************
 */
Route::get('pay-types', 'PayController@payTypes')->middleware("auth");
Route::get('pay-prepare', 'PayController@payPrepare')->middleware("auth");
Route::any('pay-processing/{id}', 'PayController@payProcessing');
Route::get('payeer', 'PayController@payeer')->name('payeer');


/*
************************ Admin Control ***********************
 */
Route::get('activation/{user_id}', 'UserController@activation')->middleware('admin');
Route::get('activation/{user_id}/without_bonus', 'UserController@activationWithoutBonus')->name('activation_without_bonus')->middleware('admin');
Route::get('deactivation/{user_id}', 'UserController@deactivation')->middleware('admin');
Route::get('upgrade-activation/{order_id}', 'UserController@activationUpgrade')->middleware('admin');
Route::get('upgrade-deactivation/{order_id}', 'UserController@deactivationUpgrade')->middleware('admin');
Route::get('success-basket-status/{basket_id}/{order_id}', 'UserController@successBasket')->name('success-basket');
Route::get('cancel-basket-status/{basket_id}/{order_id}', 'UserController@cancelBasket')->name('cancel-basket');
Route::get('progress', 'AdminController@progress')->middleware("admin");
Route::get('not_cash_bonuses', 'AdminController@notCashBonuses')->middleware("admin");
Route::get('not_cash_bonuses/{not_cash_bonuses_id}/{status}', 'AdminController@notCashBonusesAnswer')->middleware("admin");
Route::get('offices_bonus', 'AdminController@offices_bonus')->middleware("admin");
Route::get('sponsor_users', 'UserController@sponsor_users')->middleware("admin");
Route::get('sponsor_positions', 'UserController@sponsor_positions')->middleware("admin");
Route::get('user_offices', 'UserController@user_offices');
Route::get('user/{id}/transfer','UserController@transfer');
Route::post('user/transfer','UserController@transferStore');
Route::get('user/{id}/program','UserController@program');
Route::post('user/{id}/program','UserController@programStore');
Route::get('user/{id}/processing','UserController@processing');
Route::post('user/processing','UserController@processingStore');
Route::get('user/{id}/add_bonus','UserController@addBonus')->middleware("admin");
Route::post('user/{id}/add_bonus','UserController@addBonusUser')->middleware("admin");
Route::get('user-export', 'UserController@export')->middleware("admin");
Route::get('new-contracts', 'UserController@newContracts')->middleware("admin");
Route::get('monthly-report', 'UserController@monthOrders')->middleware("admin");
Route::get('activation_history', 'UserController@activationHistory');

Route::get('admin/notifications', 'AdminController@notifications')->name('admin_notifications')->middleware("admin");
Route::get('admin/move-status', 'AdminController@moveStatus')->name('move_status')->middleware("admin");

Route::get('order', 'ProductController@orders');
Route::get('overview-money', 'ProcessingController@overview')->name('overview');
Route::get('status-counts', 'ProcessingController@statusCounts')->name('counts');
Route::get('status-money', 'ProcessingController@status')->name('status');
Route::get('admin/reviews', 'AdminController@reviews')->name('admin_reviews')->middleware('admin');
Route::get('admin/comments', 'AdminController@comments')->name('admin_comments')->middleware('admin');
Route::get('admin/comment/{id}/{status}', 'AdminController@commentStatus')->name('admin_comment_status')->middleware('admin');
Route::get('admin/reviews/{id}/edit', 'AdminController@reviewEdit')->name('admin_review_edit')->middleware('admin');
Route::get('admin/reviews/{id}/add', 'AdminController@reviewAdd')->name('admin_review_add')->middleware('admin');
Route::get('admin/review/{id}/{status}', 'AdminController@reviewStatus')->name('admin_review_status')->middleware('admin');

Route::get('exchange', 'AdminController@exchange')->middleware("admin");
Route::get('settings/{id}/edit', 'AdminController@exchangeEdit')->middleware("admin");
Route::put('settings-update/{id}', 'AdminController@exchangeUpdate')->middleware("admin");


/*
************************ Resource ***********************
 */
Route::resource('user', 'UserController')->middleware("admin");
Route::resource('package', 'PackageController')->middleware("admin");
Route::resource('role', 'RoleController')->middleware("admin");
Route::resource('office', 'OfficeController')->middleware("admin");
Route::resource('city', 'CityController')->middleware("admin");
Route::resource('country', 'CountryController')->middleware("admin");
Route::resource('basket', 'BasketController')->middleware('auth');
Route::resource('processing', 'ProcessingController');
Route::resource('store', 'ProductController')->middleware("admin");
Route::resource('page', 'PageController')->middleware("admin");
Route::resource('news','NewsController')->middleware("admin");

/*
************************ Anything else ***********************
 */
//Route::get('bot_activation', 'AutoActivationController@bot_activation');
Route::get('check_mentor', 'AutoActivationController@checkMentor');


/*
************************ Test Elements ***********************
 */
Route::get('tester', 'TestController@tester');//calculatePassiveAndCashbackBonus //calculateInviteBonus
Route::get('setbots', 'TestController@setBotsExcel');
Route::get('tester-export', 'TestController@testerExport');

//Route::get('setbots', 'TestController@setBots');
Route::get('auto-activation', 'TestController@testerActivation');



/*
************************ Old Elements ***********************
 */

Route::get('shopuser', 'UserController@getshopusers')->middleware("admin");
Route::get('clientswithoutphone', 'ClientController@getclientswithoutphone')->middleware("admin");
Route::resource('client', 'ClientController')->middleware("admin");
Route::get('/copy', 'UserController@copyUsers')->name('copy');
Route::get('/auto', 'UserController@autoActivate')->name('auto');
Route::get('userorders', 'ProductController@userorders');
Route::get('basket_items/{basket_id}', 'ProductController@basket_items');
Route::post('buycontact', 'BasketController@buycontact');

/*Новости*/

Route::get('/faqgetadmin','FaqController@alladminfaq')->middleware("admin");
Route::get('/faqgetguest','FaqController@allguestfaq')->middleware("admin");


/*Мобильный*/
Route::resource('/recommendations', 'RecommendationController');
Route::resource('/course', 'CourseController');
Route::resource('/{course_id}/lessons', 'MobileApp\LessonsController');

Route::get('userregister', function(){
    return view('auth.registeruser');
});
Route::post('/changedeliverystatus','ProductController@changedeliverystatus')->middleware("admin");
