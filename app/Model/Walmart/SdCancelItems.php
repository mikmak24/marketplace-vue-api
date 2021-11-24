<?php

namespace App\Model\Walmart;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class SdCancelItems extends Eloquent
{
    protected $connection = 'mongodb-walmart';
    protected $collection = 'sd_cancelitems';
    
    protected $fillable = [
        'order_id', 'order_item_id', 'quantity_ordered',
        'product_name', 'unit_cost', 'unitExtCost', 'website', 'eclipse_po_number'
    ];
}