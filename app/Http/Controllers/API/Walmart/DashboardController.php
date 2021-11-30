<?php
   
namespace App\Http\Controllers\API\Walmart;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Product;
use Validator;
use App\Http\Resources\Product as ProductResource;
use App\Model\Walmart\Item;
use App\Model\Walmart\SdOrders;
use App\Model\Walmart\SdShipment;
use Illuminate\Support\Carbon;
use App\Model\Walmart\SDCancelOrders;
use App\Model\Walmart\SDCancelItems;
use File;



class DashboardController extends BaseController
{
    public function latestOrders()
    {
        $results = SdOrders::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->orderBy('order_date', 'desc')->get();
        return $results;
    }

    public function orderFullfilled()
    {
        $results = SdShipment::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->where('tried_shipped', true)  
        ->orderBy('created_at', 'DESC')
        ->get();
        return $results;
    }

    public function orderDueToday(){
        $results = SdShipment::where(DATE('ship_date'), '20210413')  
        ->orderBy('created_at', 'DESC')
        ->get();
        return $results;
    }

    public function getSpecificOrder(Request $request)
    {
        $results = SdShipment::where('eclipse_id', $request['eclipse_id'])  
        ->first();
        return $results;
         
    }

    public function orderPassdue()
    {
        $results = SdOrders::limit(20)    
        ->where('has_shipment', null)
        ->orderBy('created_at', 'DESC')
        ->get();
        return $results;
    }

    public function cancelOrders()
    {
        $results = SDCancelOrders::orderBy('order_date', 'desc')->get();
        return $results;
    }


    public function getNewOrdersCount()
    {
        $results = SdOrders::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->orderBy('created_at', 'desc')->count();
        return  $results;
    }

    public function getCompleteOrdersCount()
    {
        $results = SdShipment::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->where('tried_shipped', true)->count();
        return  $results;
    }

    public function getPassDueOrdersCount()
    {
        $results = SdOrders::where('has_shipment', null)
        ->orderBy('created_at', 'DESC')
        ->count();
        return $results;
    }

    public function getCancelOrdersCount()
    {
        $results = SDCancelOrders::orderBy('created_at', 'DESC')
        ->count();
        return $results;
    }

    //SpecificOrder
    public function getSpecificNewOrder(Request $request)
    {
        $results = SdOrders::where('eclipse_id', $request['eclipse_id'])  
        ->first();
        return $results;
    }

    

}