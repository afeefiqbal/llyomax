<?php

namespace App\Repositories\Warehouse;

use App\Models\User;
use App\Models\Warehouse\DeliveryBoy;
use App\Models\Warehouse\Product;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\Warehouse\DeliveryBoyInterface;
use App\Repositories\interfaces\Warehouse\ProductInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProductRepository extends BaseRepository implements ProductInterface
{
    public function getModel()
    {
        return Product::class;
    }
    public function listProducts()
    {
        return $this->model->all();
    }
    public function getProductById($id)
    {
        return $this->model->find($id);
    }
    public function createProduct($attributes)
    {
         $product = $this->model->create($attributes->all());
         if (isset($attributes->product_image)) {
            $product->addMediaFromBase64(json_decode($attributes->product_image)->data)
                ->usingFileName(Str::random() . '.jpeg')
                ->toMediaCollection('product_images');
        }
        return $product;
    }
    public function updateProduct($request,$id)
    {
        $product = $this->model->find($id);
        $product->update($request->all());
        if (isset($request->product_image)) {
            $product->clearMediaCollection('product_images');
            $product->addMediaFromBase64(json_decode($request->product_image)->data)
                ->usingFileName(Str::random() . '.jpeg')
                ->toMediaCollection('product_images');
        }
        return $product;
        return $product;
    }
    public function deleteProduct($id)
    {
        $product = $this->model->find($id);
        $product->delete();
        return $product;
    }

}
