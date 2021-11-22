<?php

namespace App\Model\Walmart;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Product extends Eloquent
{
    protected $connection = 'mongodb-walmart';
    protected $collection = 'products';
    
    protected $fillable = [
        'prod_mart', 'prod_sku', 'prod_wpid', 'prod_upc', 'prod_gtin',
        'prod_name', 'prod_type', 'prod_price', 'prod_status',
        'prod_lifecycle_status'
    ];

    public function image()
    {
        return $this->hasOne('App\Model\Walmart\Image');
    }

     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = [
    //     'created_at', 'updated_at',
    // ];
}