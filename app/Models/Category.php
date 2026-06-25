<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    // Relação: Uma Categoria possui vários Eventos
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
