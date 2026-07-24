<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Category;
use App\Repository\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    public function __construct(public CategoryRepository $categoryRepository) {}

    public function getCategories(): Collection
    {
        return $this->categoryRepository->getCategories();
    }

    public function getCategoryById(int $id): ?Category
    {
        return $this->categoryRepository->findById($id);
    }

    public function createCategory(array $data): Category
    {
        return $this->categoryRepository->create($data);
    }

    public function updateCategory(Category $category, array $data): Category
    {
        return $this->categoryRepository->update($category, $data);
    }

    public function deleteCategory(Category $category): void
    {
        $this->categoryRepository->delete($category);
    }
}