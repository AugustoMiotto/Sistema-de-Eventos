<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'email', 'password', 'profile_photo_path', 'is_promoter'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes; // <-- Adicionado o SoftDeletes

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_promoter' => 'boolean', // Garante que é tratado como true/false
        ];
    }

    // Relação: Um utilizador (promotor) pode ter vários eventos
    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'promoter_id')
                    ->belongsToMany(Event::class, 'registrations')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    // Relação: Um utilizador pode ter várias inscrições
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    // Relação: Um utilizador pode fazer várias avaliações
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
