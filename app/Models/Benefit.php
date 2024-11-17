<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Benefit extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
    ];

    protected $table = 'benefits';
    protected $primaryKey = "id";
    public $incrementing = true;

    public function setNameAttribute($value) {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function tickets(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Ticket::class, "ticket_have_many_benefits", "benefit_id", "ticket_id");
    }
}
