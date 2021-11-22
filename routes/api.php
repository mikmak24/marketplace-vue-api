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
    
    //****StartOfWalmart****

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

    //ItemCollection
    Route::get('wmitemscollection', 'API\Walmart\ItemsController@getProducts');
    Route::post('wmgetproduct', 'API\Walmart\ItemsController@getSpecificProduct');
    Route::post('wmsearchproduct', 'API\Walmart\ItemsController@searchProduct');
    Route::post('wmsetpromotion', 'API\Walmart\ItemsController@setPromotion');

    
    //OrdersController
    Route::post('wmsearchorder', 'API\Walmart\OrdersController@searchOrder');
    Route::post('wmsearcheclipseid', 'API\Walmart\OrdersController@searchEclipseID');
    Route::post('wmgetwalmartdetails', 'API\Walmart\OrdersController@getWalmartDetails');





    Route::post('wmupdatecontent', 'API\Walmart\ItemsController@updateProductContent');




    //****EndOfWalmart****





});



