<?php

namespace App\Services;

use App\Repository\CategoryRepository;

class CategoryService
{
    public CategoryRepository $categoryRepository;
    public function __construct(CategoryRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategories(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->categoryRepository->getCategories();
    }
}