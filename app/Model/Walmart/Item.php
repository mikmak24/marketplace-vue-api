<?php

namespace App\Model\Walmart;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Item extends Eloquent
{
    protected $connection = 'mongodb-walmart';
    protected $collection = 'items';
    
    protected $fillable = [
        'sku', 'product_id_type', 'product_id', 'name', 'description',
        'brand', 'category', 'listed'
    ];
}