<?php

namespace App\Repositories\interfaces\Warehouse;

use App\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface CategoryInterface extends RepositoryInterface
{
    //
    public function listCategories();
    public function getCategoryById($id);
    public function createCategory(Request $attributes);
    public function updateCategory($request,$id);
    public function deleteCategory($id);

}
