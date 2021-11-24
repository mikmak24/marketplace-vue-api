<?php

namespace App\Console\Commands\Walmart;

use Illuminate\Console\Command;
use WMToken;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use App\Model\Walmart\SDCancelOrders;
use App\Model\Walmart\SDCancelItems;
use File;

class WMCancelOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wm-cancelorders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(WMToken $token)
    {
        parent::__construct();
        $this->token = $token;

    }

     /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client_id = "5f8344e0-1486-441e-9287-03ebbeb74a3e" ;
        $client_secret = "AKA0p4Z-PCL__IebI9_9Wl8bGsh5E2lycsqfIUkHnJnyIwSc84F94r-Q7xSec8TXI5KnwBbv_xRUPeXfTVcPS60";
        $uniqid = uniqid();
        $authorization_key = base64_encode($client_id.":".$client_secret);
        $token = $this->token->getToken();
        $accessToken = $token['accessToken'];

        $curl = curl_init();
        curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://marketplace.walmartapis.com/v3/orders?status=Cancelled&limit=200&productInfo=true&createdStartDate=2021-10-01&createdEndDate=2021-11-25',
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
        $orders = json_decode($resp, true);

        $order = $orders['list']['elements']['order'];

        $this->sdOrders($orders['list']['elements']['order']);
        var_dump("-----------------------------------------");
        $this->sdItems($orders['list']['elements']['order']);

    }

        public function sdOrders($orders)
        {
            for($i = 0; $i < count($orders); $i++ )
            {
                //var_dump($orders[$i]['customerOrderId']);
                $ship_info = $orders[$i]['shippingInfo'];
                $purchase_order_id = $orders[$i]['customerOrderId'];
                $order_id =     $orders[$i]['purchaseOrderId'];
                $website =      'Pstock_Walmart';
                $cust_email =   $orders[$i]['customerEmailId'];
                $order_date =   date("Y-m-d H:i:s", $orders[$i]['orderDate'] / 1000);
                $name =         $orders[$i]['shippingInfo']['postalAddress']['name'];
                $address1 =     $orders[$i]['shippingInfo']['postalAddress']['address1'];
                $address2 =     $orders[$i]['shippingInfo']['postalAddress']['address2'];
                $city =         $orders[$i]['shippingInfo']['postalAddress']['city'];
                $state =        $orders[$i]['shippingInfo']['postalAddress']['state'];
                $zip =          $orders[$i]['shippingInfo']['postalAddress']['postalCode'];
                $country =      $orders[$i]['shippingInfo']['postalAddress']['country'];
                $phone =        $orders[$i]['shippingInfo']['phone'];
                $order_amount =  $this->computeOrderAmount($orders[$i]['orderLines']['orderLine']);
                $grand_total_cost = $order_amount['prodCharge'] + $order_amount['shipCharge'];
                $ship_charge		= $order_amount['shipCharge'];

                $orderDetails = $orders[$i]['orderLines']['orderLine'];

                for($e = 0; $e < count($orderDetails); $e++)
                {
                  $order_item_id =    $orderDetails[$e]['item']['sku'];
                  $quantity_ordered = $orderDetails[$e]['lineNumber'];
                  $product_name =     $orderDetails[$e]['item']['productName'];
                  $unit_cost =        $orderDetails[$e]['charges']['charge'][0]['chargeAmount']['amount'];
                }
                
                if($ship_info['methodCode'] == 'Value' || $ship_info['methodCode'] == 'Standard'){
                    $ship_method_name = 'WALMART';
                    $ship_method = 'WALMART';							//PS BEST METHOD
                }else{
                    $ship_method_name = 'PS BEST EXPEDITE';				//PS BEST EXPEDITE
                    $ship_method = 'PSEX';
                }

                $order_lines = $orders[$i]['orderLines']['orderLine'];
                $sdOrdercheck = SDCancelOrders::where('order_id', '=', $order_id)->first();

                if ($sdOrdercheck === null) {
                    $sdOrder = new SDCancelOrders();

                    $sdOrder->ship_info =  $ship_info;
                    $sdOrder->purchase_order_id =  $purchase_order_id; 
                    $sdOrder->order_id =  $order_id;
                    $sdOrder->website = $website;
                    $sdOrder->cust_email = $cust_email;
                    $sdOrder->order_date =  $order_date;
                    $sdOrder->name = $name;
                    $sdOrder->address1  =  $address1;
                    $sdOrder->address2  =  $address2;
                    $sdOrder->city  =  $city;
                    $sdOrder->state  = $state;
                    $sdOrder->zip  = $zip;
                    $sdOrder->country  =  $country;
                    $sdOrder->phone  =  $phone;
                    $sdOrder->order_amount  = $order_amount;
                    $sdOrder->grand_total_cost  = $grand_total_cost;
                    $sdOrder->ship_charge  = $ship_charge;
                    $sdOrder->ship_method_name  = $ship_method_name;
                    $sdOrder->ship_method  = $ship_method;
                    $sdOrder->order_lines  = $order_lines;

                    $sdOrder->quantity_ordered = $quantity_ordered;
                    $sdOrder->product_name = $product_name;
                    $sdOrder->unit_cost = $unit_cost;
                    $sdOrder->order_item_id = $order_item_id;

                    $sdOrder->save();

                    var_dump("SDOrders: " . $order_id);

                }
            }

        }

        public function sdItems($orders)
        {
            for($i = 0; $i < count($orders); $i++ )
            {

                $orderDetails = $orders[$i]['orderLines']['orderLine'];

                for($e = 0; $e < count($orderDetails); $e++)
                {
                    //var_dump($orders[$i]['customerOrderId']);
                    $order_id =     $orders[$i]['purchaseOrderId'];
                    $order_item_id =    $orderDetails[$e]['item']['sku'];
                    $quantity_ordered = $orderDetails[$e]['lineNumber'];
                    $product_name =     $orderDetails[$e]['item']['productName'];
                    $unit_cost =        $orderDetails[$e]['charges']['charge'][0]['chargeAmount']['amount'];
                    $unitExtCost = ($quantity_ordered * $unit_cost);
                    $website = 'Pstock_Walmart';
                    $eclipse_po_number =  $orderDetails[$e]['item']['sku'];


                    $sdItemcheck = SDCancelItems::where('order_id', '=', $order_id)->first();
                    if ($sdItemcheck === null) {

                    $sdItem = new SDCancelItems();
                    $sdItem->order_id = $order_id;
                    $sdItem->order_item_id = $order_item_id;
                    $sdItem->quantity_ordered = $quantity_ordered;
                    $sdItem->product_name = $product_name;
                    $sdItem->unit_cost = $unit_cost;
                    $sdItem->website = $website;
                    $sdItem->eclipse_po_number = $eclipse_po_number;

                    $sdItem->save();

                    var_dump("SDItems: " . $order_id);
                    }
                }

            }

        }

        public function computeOrderAmount($orderLine) 
        {
            try{
              $prodCharge = 0;
              $shipCharge = 0;
              $skus = array();
      
              if(isset($orderLine['charges']['charge']['chargeType'])){ //for single item with 'VALUE' or 'STANDARD' shipping which means free shipping
                // var_dump('Single Charge');
                // dump($orderLine);
                $charge = $orderLine['charges']['charge'];
                // var_dump($charge);
                if( $charge['chargeType'] == 'PRODUCT' ){
                  $prodCharge += $charge['chargeAmount']['amount'];
                  $skus[(int)$orderLine['item']['sku']] = ['prodName' => $orderLine['item']['productName'], 'qty' => 1, 'price' => floatval($charge['chargeAmount']['amount'])];
                }
              }else{								//if there are multiple charges e.g. Multiple items or One item with shipment charge
                // var_dump('Multiple Charges');
                // dump($orderLine);
      
                if(isset($orderLine[0])){//Multiple  orderlines, Items, take a look on PO 4578116229735
                  foreach ($orderLine as $ol) {
                    $charge = $ol['charges']['charge'];
                    // dump($charge);
      
                    if( isset($charge['chargeType']) ){
                      if( isset($charge['chargeType']) && $charge['chargeType'] == 'PRODUCT' ){
                        $prodCharge += $charge['chargeAmount']['amount'];
                        if(!array_key_exists((int)$ol['item']['sku'], $skus)){
                          // var_dump('key not found xx');
                          $skus[(int)$ol['item']['sku']] = ['prodName' => $ol['item']['productName'],'qty' => 1, 'price' => floatval($charge['chargeAmount']['amount'])];
                          // var_dump($skus);
                        }else{
                          var_dump('key exists');
                          $skus[$ol['item']['sku']]['qty'] = $skus[$ol['item']['sku']]['qty'] + 1;
                        }
                      }
                      elseif($charge['chargeType'] == 'SHIPPING'){
                        $shipCharge += $charge['chargeAmount']['amount'];
                      }
                    }elseif( isset($charge[0]) ){//3577954771606 - Multiple Item quantities, with shipping each quantity
                      foreach($charge as $chg){
                        if( isset($chg['chargeType']) && $chg['chargeType'] == 'PRODUCT' ){
                          $prodCharge += $chg['chargeAmount']['amount'];
                          if(!array_key_exists((int)$ol['item']['sku'], $skus)){
                            // var_dump('key not found yy');
                            $skus[(int)$ol['item']['sku']] = ['prodName' => $ol['item']['productName'],'qty' => 1, 'price' => floatval($chg['chargeAmount']['amount'])];
                            // var_dump($skus);
                          }else{
                            // var_dump('key exists');
                            $skus[$ol['item']['sku']]['qty'] = $skus[$ol['item']['sku']]['qty'] + 1;
                          }
                        }
                        elseif($chg['chargeType'] == 'SHIPPING'){
                          $shipCharge += $chg['chargeAmount']['amount'];
                        }
                      }
                    }
                  }
                  // dump($skus);
      
                }else{
                  foreach ($orderLine['charges']['charge'] as $charge) {//2578126400966 Multiple charges in one orderLine, with charge of item price and shipping
                    // dump($charge);
      
      
                    // $charge = $order['charges']['charge'];
                    if( $charge['chargeType'] == 'PRODUCT' ){
                      $prodCharge += $charge['chargeAmount']['amount'];
      
                      if(!array_key_exists((int)$orderLine['item']['sku'], $skus)){
                        // var_dump('key not found zz');
                        $skus[(int)$orderLine['item']['sku']] = ['prodName' => $orderLine['item']['productName'], 'qty' => 1, 'price' => floatval($charge['chargeAmount']['amount'])];
      
                        // var_dump($skus);
      
                      }else{
                        var_dump('key exists');
                        $skus[$orderLine['item']['sku']]['qty'] = $skus[$orderLine['item']['sku']]['qty'] + 1;
                      }
                    }
                    elseif($charge['chargeType'] == 'SHIPPING'){
                      $shipCharge += $charge['chargeAmount']['amount'];
                    }
      
                  }
                }
              }
      
              $charges = array(
              'items'		 => $skus,		 //sku's included in the order
              'prodCharge' => $prodCharge, //sum of all products price in the order
              'shipCharge' => $shipCharge	 //shipping charge
              );
              return $charges;
            }
            catch (Exception $e) {
              $this->writeError($e);
            }
        }

}