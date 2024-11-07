<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Seller extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'telephone',
        'location',
        'slug',
        'photo',
    ];

    public function tickets() {
        return $this->hasMany(Ticket::class, 'seller_id', 'id');
    }

    public function setNameAttribute($value) {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
