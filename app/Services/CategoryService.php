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

    public function getCategoryById(int $id): ?\App\Models\Category
    {
        return $this->categoryRepository->findById($id);
    }

    public function createCategory(array $data): \App\Models\Category
    {
        return $this->categoryRepository->create($data);
    }

    public function updateCategory(\App\Models\Category $category, array $data): \App\Models\Category
    {
        return $this->categoryRepository->update($category, $data);
    }

    public function deleteCategory(\App\Models\Category $category): void
    {
        $this->categoryRepository->delete($category);
    }
}