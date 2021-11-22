<?php

namespace App\Console\Commands\Walmart;

use Illuminate\Console\Command;
use WMToken;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

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
        $dt = Carbon::now();

        $client_id = "5f8344e0-1486-441e-9287-03ebbeb74a3e" ;
        $client_secret = "AKA0p4Z-PCL__IebI9_9Wl8bGsh5E2lycsqfIUkHnJnyIwSc84F94r-Q7xSec8TXI5KnwBbv_xRUPeXfTVcPS60";
        $uniqid = uniqid();
        $authorization_key = base64_encode($client_id.":".$client_secret);
        $token = $this->token->getToken();
        $accessToken = $token['accessToken'];

        $curl = curl_init();
        curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://marketplace.walmartapis.com/v3/orders?status=Cancelled&limit=150&productInfo=true',
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
        
        Storage::disk('local')->put('walmart/wmcancelledorder.txt', toArray($orders));

    }

}