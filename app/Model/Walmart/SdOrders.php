<?php

namespace App\Model\Walmart;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class SdOrders extends Eloquent
{
    protected $connection = 'mongodb-walmart';
    protected $collection = 'sd_orders';
    
    protected $fillable = [
        'ship_info', 'purchase_order_id', 'order_id', 'website', 'cust_email',
        'order_date', 'name', 'address1', 'address2', 'city', 'state', 'zip', 
        'country', 'phone', 'order_amount', 'grand_total_cost', 'ship_charge',
        'ship_method_name', 'ship_method', 'order_lines'
    ];
}