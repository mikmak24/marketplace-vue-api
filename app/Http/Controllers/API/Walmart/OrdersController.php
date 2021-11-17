<?php
   
namespace App\Http\Controllers\API\Walmart;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Http\Resources\Product as ProductResource;
use App\Model\Walmart\Item;
use App\Model\Walmart\Product;
use Illuminate\Support\Carbon;
use App\Model\Walmart\SdOrders;
use App\Model\Walmart\SdShipment;
use WMToken;

class OrdersController extends BaseController
{
    public function __construct(WMToken $token)
    {
        $this->token = $token;
    }

    public function searchOrder(Request $request)
    {
        $results = SdOrders::where('order_id', $request['orderID'])  
        ->get();
        return $results;
    }

    public function searchEclipseID(Request $request)
    {
        $results = SdShipment::where('eclipse_id', $request['eclipseID'])  
        ->get();
        return $results;
    }

    public function getWalmartDetails(Request $request)
    {
        $orderId = $request['order_id'];
        return $this->getOrder($orderId);
    }

    public function getOrder($orderID) {
        $client_id = "5f8344e0-1486-441e-9287-03ebbeb74a3e" ;
        $client_secret = "AKA0p4Z-PCL__IebI9_9Wl8bGsh5E2lycsqfIUkHnJnyIwSc84F94r-Q7xSec8TXI5KnwBbv_xRUPeXfTVcPS60";
        $uniqid = uniqid();
        $authorization_key = base64_encode($client_id.":".$client_secret);
        $token = $this->token->getToken();
        $accessToken = $token['accessToken'];

        $curl = curl_init();
        curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://marketplace.walmartapis.com/v3/orders/'.$orderID,
        CURLOPT_HTTPHEADER => array(
            "WM_SVC.NAME: Walmart Marketplace",
            "WM_QOS.CORRELATION_ID: $uniqid",
            "Authorization: Basic $authorization_key",
            "Accept: application/json",
            "Content-Type: application/x-www-form-urlencoded",
            "WM_SEC.ACCESS_TOKEN: $accessToken"
        ),

        ]);
        $resp = curl_exec($curl);
        curl_close($curl);
        return json_decode($resp, true);

    }

    

}