<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Contracts\ICategoryRepository;

class CategoryRepository implements ICategoryRepository
{
    public function getAllCategories(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::query()->latest()->get();
    }
}
