<?php

namespace App\Model\Walmart;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Image extends Eloquent
{
    protected $connection = 'mongodb-walmart';
    protected $collection = 'images';
    
    protected $fillable = [
        'sku', 'image_url', 'seller'
    ];

     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];
}