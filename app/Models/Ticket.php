<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'address',
        'thumbnail',
        'path_video',
        'price',
        'is_popular',
        'about',
        'open_time_at',
        'closed_time_at',
        'category_id',
        'seller_id'
    ];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function seller(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id');
    }

    public function photos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TicketPhoto::class, 'ticket_id', 'id');
    }

    public function haveBenefits(): BelongsToMany {
        return $this->belongsToMany(Benefit::class, "ticket_have_many_benefits", "ticket_id", "benefit_id");
    }

    public function setNameAttribute($value) {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
