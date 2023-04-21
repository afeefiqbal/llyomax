<?php

namespace App\Repositories\Warehouse;

use App\Models\Warehouse\Category;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\Warehouse\CategoryInterface;

class CategoryRepository extends BaseRepository implements CategoryInterface
{
    public function getModel()
    {
        return Category::class;
    }
    public function listCategories()
    {
        return $this->model->all();
    }
    public function getCategoryById($id)
    {
        return $this->model->find($id);
    }
    public function createCategory($attributes)
    {
        return $this->model->create($attributes->all());
    }
    public function updateCategory($request,$id)
    {
        $category = $this->model->find($id);
        $category->update($request->all());
        return $category;
    }
    public function deleteCategory($id)
    {
        $category = $this->model->find($id);
        $category->delete();
        return $category;
    }

}
