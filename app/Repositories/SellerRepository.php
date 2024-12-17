<?php

namespace App\Repositories;

use App\Models\Seller;
use App\Repositories\Contracts\ISellerRepository;

class SellerRepository implements ISellerRepository
{

    public function getAllSellers(): \Illuminate\Database\Eloquent\Collection
    {
        return Seller::query()->with('tickets')->latest()->get();
    }
}
