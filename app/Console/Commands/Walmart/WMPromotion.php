<?php

namespace App\Console\Commands\Walmart;

use Illuminate\Console\Command;
use WMToken;


class WMPromotion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wm-promotion';

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
        //$body = $this->getPromotionBody($request);
        $client_id = "5f8344e0-1486-441e-9287-03ebbeb74a3e" ;
        $client_secret = "AKA0p4Z-PCL__IebI9_9Wl8bGsh5E2lycsqfIUkHnJnyIwSc84F94r-Q7xSec8TXI5KnwBbv_xRUPeXfTVcPS60";
        $uniqid = uniqid();
        $authorization_key = base64_encode($client_id.":".$client_secret);
        $token = $this->token->getToken();
        $accessToken = $token['accessToken'];
     
        $data = json_encode(
        '
        {
            "sku": "10060374",
            "replaceAll": "false",
            "pricing": [
                {
                    "currentPriceType": "CLEARANCE",
                    "comparisonPriceType":  "BASE",
                    "currentPrice": {
                        "currency":  "USD",
                        "amount":  "60"
                    },
                    "comparisonPrice": {
                        "currency": "USD",
                        "amount": "71.22"
                    },
                    "priceDisplayCodes": "CHECKOUT",
                    "effectiveDate": "2021-12-01T14:36",
                    "expirationDate": "2021-12-11T14:36",
                    "processMode": "UPSERT"
                }
            ]
        }
        ');
        

        //$body = http_build_query($data);

        $url = 'https://marketplace.walmartapis.com/v3/price';

        $header = array (
            "WM_SVC.NAME: Walmart Marketplace",
            "WM_QOS.CORRELATION_ID: $uniqid",
            "Authorization: Basic $authorization_key",
            "WM_SEC.ACCESS_TOKEN: $accessToken",
            "Content-Type: application/json"
        );
       

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_decode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $response = curl_exec($ch);
        curl_close($ch);
        var_dump($response);





        

    }
}
