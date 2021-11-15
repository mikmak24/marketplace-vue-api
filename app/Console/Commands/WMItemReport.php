<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class WMItemReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wm-itemreport';

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
    public function __construct()
    {
        parent::__construct();
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
        $accessToken = "eyJraWQiOiI0MmE2ZTBjNC0wMjk3LTQyN2MtYmEyMi1kZmJiMWQ2Mzc5ODkiLCJlbmMiOiJBMjU2R0NNIiwiYWxnIjoiZGlyIn0..D89Rstg-l74UZaEX.Bs4cQeNI6kdJGA8cgFqid6IoFknrH9xTuG87Ejq-kbg0K5jSMKuREjRmUd5hIv32_IEQqbaog7oVzT2qg8yqptzYmqLlKWeDbIvvt49BCTBbAwuV4V-3p-hHDFowA6PnMh4VA86zdW5D71AzwcUw_DNG3pW0Mm1k3LIjedd3kGDd4giaWF4N3ol66eujyJju2-Dpd36qk8-ZdDZ6ZpbIDI3wuCFkgPdo6tT5lz9NqNepLPkQK2V-cuvEtTuCekpZ0kFQpcSLMLAY0-BdLRTcRISIk739SiK99SJFK3KVl6i5g9RY0dwO8YygqWO6G775TRWpjSJAGUpqHkrOgzFn1sZg5IVZbncmDdIL19zt04ANnDU-f1H8l4l9AiEgYkdaXj2YJ7N7qJW2d_Y0JnQQBXbnFNaFpEGYlLJNDzF6IXeOrP3WquXF9dRds6ydWlsW_XTVVhF_ID5iAYd20BvYeYYp9zrSmRw3N6s3YQoAh8lgojMVxmFljyd_e5jujeuqokDLDGMnoClDZyHNxlAaoHK8noquXy2JM_-vo3J-avgrgpUKwoOlI_GkKST1DyAYRUDwq63lP4ibxMGNRIfYNJylMDvNXiRzrz4Yxzhrkp38Z2vxzSxuTVSSBPo_CtKzHyUQCHqRN0lUZn5J3FInFUqL3l7pMQEL-ktrQPFtJHpulk8jQ5Zlk-QM158h.qg53zckOyCrz9VEUL1IqRA";

        $curl = curl_init();
        curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://marketplace.walmartapis.com/v3/getReport?type=item',
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
        $items = json_decode($resp, true);

        dd($items);
    }
}
