<?php

namespace App\Walmart;

use Illuminate\Http\Request;

class WMToken {
    public function getToken() {
        $client_id = "5f8344e0-1486-441e-9287-03ebbeb74a3e";
        $client_secret = "AKA0p4Z-PCL__IebI9_9Wl8bGsh5E2lycsqfIUkHnJnyIwSc84F94r-Q7xSec8TXI5KnwBbv_xRUPeXfTVcPS60";
        $url = "https://marketplace.walmartapis.com/v3/token";
        $uniqid = uniqid();
        $authorization_key = base64_encode($client_id.
            ":".$client_secret);
        $code = "";

        $ch = curl_init();
        $options = array(

            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HEADER => false,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => "grant_type=client_credentials",
            CURLOPT_HTTPHEADER => array(
                "WM_SVC.NAME: Walmart Marketplace",
                "WM_QOS.CORRELATION_ID: $uniqid",
                "Authorization: Basic $authorization_key",
                "Accept: application/json",
                "Content-Type: application/x-www-form-urlencoded"
            ),

        );

        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code == 201 || $code == 200) {
            $token = json_decode($response, true);
        }

        $accessToken = $token['access_token'];
        $tokenType = $token['token_type'];
        $expiresIn = floor(($token['expires_in'] / 60) % 60);

        return array('accessToken' => $accessToken, 'tokenType' => $tokenType, 'expiresIn' => $expiresIn);
    }

    public function getTokenDetails($token)
    {
        $client_id = "5f8344e0-1486-441e-9287-03ebbeb74a3e" ;
        $client_secret = "AKA0p4Z-PCL__IebI9_9Wl8bGsh5E2lycsqfIUkHnJnyIwSc84F94r-Q7xSec8TXI5KnwBbv_xRUPeXfTVcPS60";
        $uniqid = uniqid();
        $authorization_key = base64_encode($client_id.":".$client_secret);
       
        $curl = curl_init();
        curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://marketplace.walmartapis.com/v3/token/detail',
        CURLOPT_HTTPHEADER => array(
            "WM_SVC.NAME: Walmart Marketplace",
            "WM_QOS.CORRELATION_ID: $uniqid",
            "Authorization: Basic $authorization_key",
            "Accept: application/json",
            "Content-Type: application/x-www-form-urlencoded",
            "WM_SEC.ACCESS_TOKEN: $token"
        ),

        ]);
        $resp = curl_exec($curl);
        curl_close($curl);
        $tokenDetails = json_decode($resp, true);
    
        if( isset($tokenDetails['expire_at']))
        {
            $expireAt = floor(($tokenDetails['expire_at'] / 60) % 60);
            $issuedAt = floor(($tokenDetails['issued_at'] / 60) % 60);
            $isValid = $tokenDetails['is_valid'];
            $array = array('expireAt' => $expireAt, 'issuedAt' => $issuedAt, 'isValid' => $isValid);
        }
        else
        {
            $array = "";
        }
        return $array;
    }
}

