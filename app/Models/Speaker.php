<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Speaker extends Model
{
    use HasFactory, SoftDeletes; // Ativa as lixeiras automáticas

    // Define os campos que podem ser guardados na base de dados
    protected $fillable = [
        'name',
        'email',
        'phone',
        'expertise_area',
        'institution',
        'bio',
        'profile_photo_path',
    ];

    public function events() {
        return $this->belongsToMany(Event::class);
    }
}
