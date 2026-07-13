<?php

namespace App\Repositories\Contracts;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    public function paginate(array $filters, int $perPage);

    public function find(int $id);

    public function findBySlug(string $slug);

    public function create(array $data);

    public function update(Category $category, array $data);

    public function delete(Category $category): bool;

    public function getTree(): Collection;

    public function getActive(): Collection;
}
