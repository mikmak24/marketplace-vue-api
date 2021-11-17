<?php

namespace App\Model\Walmart;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Token extends Eloquent
{
    protected $connection = 'mongodb-walmart';
    protected $collection = 'tokens';
    
    protected $fillable = [
        'seller_id', 'token'
    ];
}