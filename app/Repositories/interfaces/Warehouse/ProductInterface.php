<?php

namespace App\Repositories\interfaces\Warehouse;

use App\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface ProductInterface extends RepositoryInterface
{
    //
    public function listProducts();
    public function getProductById($id);
    public function createProduct(Request $attributes);
    public function updateProduct($request,$id);
    public function deleteProduct($id);

}
