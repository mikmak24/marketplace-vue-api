<?php
   
namespace App\Http\Controllers\API\Walmart;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Http\Resources\Product as ProductResource;
use App\Model\Walmart\Item;
use App\Model\Walmart\SdOrders;
use App\Model\Walmart\Image;
use App\Model\Walmart\Figure;
use App\Model\Walmart\Feature;
use Rap2hpoutre\FastExcel\FastExcel;

class WalmartSetterController extends BaseController
{
    public function uploadproducts(Request $request)
    {
        $file = $request->file;
        $collection = fastexcel()->import($file);
        $this->importItems($collection);
        $this->importFigures($collection);
        $this->importFeatures($collection);
        $this->importImages($collection);
        return response()->json([
            'status' => 'Successful',
            'data'=> $collection
        ]);
    }

    public function importImages($collection)
    {
        for ($i = 0;$i < count($collection);$i++)
        {
            if (!empty($collection[$i]['sku']))
            {
                $image = new Image();
                $isExist = Image::select("*")->where("sku", strval($collection[$i]['sku']))->exists();
                if (!$isExist)
                {
                    $image->sku = strval($collection[$i]['sku']);
                    $image->seller = strval($collection[$i]['seller']);
                    $images = explode(',', $collection[$i]['image_url']);
                    for ($j = 0;$j < count($images);$j++)
                    {
                        $image->push('images', array(
                            $j => $images[$j]
                        ));
                    }
                    $image->save();
                }
            }
        }
    }

    public function importItems($collection)
    {
        for ($i = 0;$i < count($collection);$i++)
        {
            $item = new Item();
            $isExist = Item::select("*")->where("sku", strval($collection[$i]['sku']))->exists();
            if (!$isExist && !empty($collection[$i]['sku']))
            {
                $item->sku = strval($collection[$i]['sku']);
                $item->product_id_type = strval($collection[$i]['product_id_type']);
                $item->product_id = $collection[$i]['product_id'];
                $item->name = $collection[$i]['name'];
                $item->description = $collection[$i]['description'];
                $item->brand = $collection[$i]['brand'];
                $item->category = $collection[$i]['category'];
                $item->listed = FALSE;
                $item->save();
            }
        }
    }

    public function importFigures($collection)
    {
        for ($i = 0;$i < count($collection);$i++)
        {
            $figure = new Figure();
            $isExist = Figure::select("*")->where("sku", strval($collection[$i]['sku']))->exists();
            if (!$isExist && !empty($collection[$i]['sku']))
            {
                $figure->sku = strval($collection[$i]['sku']);
                $figure->seller = strval($collection[$i]['seller']);
                $figure->price = floatval($collection[$i]['price']);
                $figure->qty = floatval($collection[$i]['qty']);
                $figure->tax_code = floatval($collection[$i]['tax_code']);
                $figure->push('shipping_weight', ['shipping_weight' => array(
                    'unit' => $collection[$i]['unit'],
                    'measure' => $collection[$i]['measure']
                ) ]);
                $figure->save();
            }
        }
    }

    public function importFeatures($collection)
    {
        for ($i = 0;$i < count($collection);$i++)
        {
            if (!empty($collection[$i]['sku']))
            {
                $feature = new Feature();
                $isExist = Feature::select("*")->where("sku", strval($collection[$i]['sku']))->exists();
                if (!$isExist)
                {
                    $feature->sku = strval($collection[$i]['sku']);
                    $feature->seller = strval($collection[$i]['seller']);
                    $features = explode(',', $collection[$i]['features']);
                    for ($j = 0;$j < count($features);$j++)
                    {
                        $feature->push('features', array(
                            $j => $features[$j]
                        ));
                    }
                    $feature->save();
                }
            }
        }
    }
}