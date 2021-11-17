<?php
   
namespace App\Http\Controllers\API\Walmart;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Http\Resources\Product as ProductResource;
use App\Model\Walmart\Item;
use App\Model\Walmart\Image;
use App\Model\Walmart\Product;
use Illuminate\Support\Carbon;
use WMToken;

class ItemsController extends BaseController
{
    public function __construct(WMToken $token)
    {
        $this->token = $token;
    }

    public function getProducts()
    {
        $results = Product::limit(10)
        ->orderBy('created_at', 'DESC')
        ->get();
        return $results;
    }

    public function getSpecificProduct(Request $request)
    {
        $results = Product::where('prod_sku', $request['sku'])  
        ->first();
        return $results;
    }

    public function searchProduct(Request $request)
    {
        $results = Product::where('prod_sku', $request['sku'])  
        ->get();
        return $results;
    }


    public function updateProductContent(Request $request)
    {
        $item = $this->getItems($request->sku);
        $status =  $this->updateProduct($item);

        $response = [
            'success' => $status,
            'data'    => $item['ItemResponse'][0]
        ];

        return response()->json($response, 200);
    }

    public function updateProduct($item) {
        $mart = $item['ItemResponse'][0]['mart'];
        $sku = $item['ItemResponse'][0]['sku'];
        $wpid = $item['ItemResponse'][0]['wpid'];

        if(isset($item['ItemResponse'][0]['upc']))
        {
            $upc = $item['ItemResponse'][0]['upc'];
        }
        else {
            $upc = '';
        }

        $gtin = $item['ItemResponse'][0]['gtin'];
        $name = $item['ItemResponse'][0]['productName'];
        $type = $item['ItemResponse'][0]['productName'];
        $price = $item['ItemResponse'][0]['price']['amount'];
        $publishedStatus = $item['ItemResponse'][0]['publishedStatus'];
        $lifecycleStatus = $item['ItemResponse'][0]['lifecycleStatus'];

        try {
            Product::where('prod_sku', $sku)
            ->update([
                'prod_mart' => $mart,
                'prod_sku' => $sku,
                'prod_wpid' => $wpid,
                'prod_upc' => $upc,
                'prod_gtin' => $gtin,
                'prod_name' => $name,
                'prod_type' => $type,
                'prod_price' => $price,
                'prod_status' => $publishedStatus,
                'prod_lifecycle_status' => $lifecycleStatus

            ]);
            return true;
            
        } catch (\Illuminate\Database\QueryException $e) {
            return false;
        }

    }

    public function getItems($sku) {
        $client_id = "5f8344e0-1486-441e-9287-03ebbeb74a3e" ;
        $client_secret = "AKA0p4Z-PCL__IebI9_9Wl8bGsh5E2lycsqfIUkHnJnyIwSc84F94r-Q7xSec8TXI5KnwBbv_xRUPeXfTVcPS60";
        $uniqid = uniqid();
        $authorization_key = base64_encode($client_id.":".$client_secret);
        $token = $this->token->getToken();
        $accessToken = $token['accessToken'];

        $curl = curl_init();
        curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://marketplace.walmartapis.com/v3/items/'.$sku,
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