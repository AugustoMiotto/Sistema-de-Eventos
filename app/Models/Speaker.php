<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Speaker extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'expertise_area',
        'institution',
    ];

    // Relação: Um Palestrante pode participar em vários Eventos
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
