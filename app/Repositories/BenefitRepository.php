<?php

namespace App\Repositories;

use App\Models\Benefit;
use App\Repositories\Contracts\IBenefitRepository;

class BenefitRepository implements IBenefitRepository
{

    public function getAllBenefit(): \Illuminate\Database\Eloquent\Collection
    {
        return Benefit::query()->with('tickets')->latest()->get();
    }
}
