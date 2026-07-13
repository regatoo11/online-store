<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    protected CategoryRepositoryInterface $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getPaginated(array $filters)
    {
        $perPage = $filters['per_page'] ?? 15;
        return $this->repository->paginate($filters, $perPage);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(Category $category, array $data)
    {
        return $this->repository->update($category, $data);
    }

    public function delete(Category $category): bool
    {
        return $this->repository->delete($category);
    }

    public function getTree(): Collection
    {
        return $this->repository->getTree();
    }
}
