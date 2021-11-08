<?php

namespace App\Model\Walmart;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class SdShipment extends Eloquent
{
    protected $connection = 'mongodb-walmart';
    protected $collection = 'sd_shipments';
    
    protected $fillable = [
        'msn', 'eclipse_id', 'shipping_expense',
        'carrier_type', 'carrier_method', 'tracking_number', 'ship_date', 'ship_status', 'product_id', 'qty', 
        'billTo', 'weight', 'carton_id',  'updated_at', 'created_at', 'is_shipped', 'tried_shipped',  
    ];
}