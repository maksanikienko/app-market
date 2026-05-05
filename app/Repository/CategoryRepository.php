<?php

namespace App\Repository;

use App\Models\Category;

class CategoryRepository
{

    public function getCategories(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::all();
    }
}