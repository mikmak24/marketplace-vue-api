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

class DashboardController extends BaseController
{
    public function getItems()
    {
        $results = SdOrders::limit(10)    
        ->orderBy('created_at', 'DESC')
        ->get();
        return $results;
    }

    public function orderFullfilled()
    {
        $results = SdShipment::limit(20)  
        ->where('tried_shipped', true)  
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

}