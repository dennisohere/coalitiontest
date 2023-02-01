<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    const PRODUCT_DB = 'products.json';


    private function initDbFile()
    {
        if(!Storage::fileExists(self::PRODUCT_DB)){
            Storage::put(self::PRODUCT_DB, json_encode([]));
        }
    }

    /**
     * @return Collection
     */
    public function getData()
    {
        $this->initDbFile();

        $content = Storage::read(self::PRODUCT_DB);

        $products_data = json_decode($content, true);

        $products_array = array_map(function($item){
            return new Product($item);
        }, $products_data);

        return collect($products_array)->sortBy('submitted_at');
    }

    public function save(Product $product)
    {
        $this->initDbFile();

        $products = $this->getData();

        $update_index = null;

        /** @var Product|null $existing_product */
        $existing_product = $products->first(function($prod, $index) use ($product, &$update_index){
            $update_index = $index;
            return $prod->name == $product->name;
        });

        if($existing_product){
            $existing_product->name = $product->name;
            $existing_product->quantity = $product->quantity;
            $existing_product->price = $product->price;
            $existing_product->total_value_number = $existing_product->quantity * $existing_product->price;
            $products->put($update_index, $existing_product);
        } else {
            $product->submitted_at = now();
            $product->total_value_number = $product->quantity * $product->price;
            $products->add($product);
        }

        Storage::put(self::PRODUCT_DB, $products->toJson());

        return $products;
    }

}
