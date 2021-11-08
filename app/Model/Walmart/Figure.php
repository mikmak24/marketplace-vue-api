<?php

namespace App\Model\Walmart;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Figure extends Eloquent
{
    protected $connection = 'mongodb-walmart';
    protected $collection = 'figures';
    
    protected $fillable = [
        'sku', 'seller', 'price', 'qty', 'tax_code', 'shipping_weight' ,'unit', 'measure'
    ];
}