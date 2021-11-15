<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', 'API\RegisterController@register');
Route::post('login', 'API\RegisterController@login');
 
Route::middleware('auth:api')->group( function () {
    // Route::resource('products', 'API\ProductController');
    Route::post('test', 'API\ProductController@test');

    //Walmart Setter
    Route::post('wm-uploadsetter', 'API\Walmart\WalmartSetterController@uploadproducts');

    //Walmart Dashboard
    Route::get('wmitems', 'API\Walmart\DashboardController@latestOrders');
    Route::get('wmfullfilled', 'API\Walmart\DashboardController@orderFullfilled');
    Route::get('wmpassdue', 'API\Walmart\DashboardController@orderPassdue');
    Route::get('wmdueorders', 'API\Walmart\DashboardController@orderDuetoday');



    Route::get('countnewOrders', 'API\Walmart\DashboardController@getNewOrdersCount');
    Route::get('countcompleteOrders', 'API\Walmart\DashboardController@getCompleteOrdersCount');
    Route::get('countdueOrders', 'API\Walmart\DashboardController@getPassDueOrdersCount');


    Route::post('wmgetorder', 'API\Walmart\DashboardController@getSpecificOrder');
    
    //GetSpecific
    Route::post('wmgetNewOrder', 'API\Walmart\DashboardController@getSpecificNewOrder');





});



