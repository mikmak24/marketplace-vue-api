<?php

namespace App\Model\Walmart;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Feature extends Eloquent
{
    protected $connection = 'mongodb-walmart';
    protected $collection = 'features';

    protected $fillable = [
        'sku', 'seller'
    ];
}