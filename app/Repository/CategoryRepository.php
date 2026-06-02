<?php

namespace App\Repository;

use App\Models\Category;

class CategoryRepository
{

    public function getCategories(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::orderBy('sort_order')->get();
    }

    public function findById(int $id): ?Category
    {
        return Category::find($id);
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);
        return $category->fresh();
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}