<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use SoftDeletes; // Ativa a eliminação suave

    // Campos que podem ser guardados na base de dados em massa
    protected $fillable = [
        'promoter_id',
        'speaker_id',
        'category_id',
        'name',
        'description',
        'location',
        'target_audience',
        'max_slots',
        'is_free',
        'price',
        'start_at',
        'cover_photo',
        'status',
        'manage_registrations',
    ];

    // Converte automaticamente os tipos de dados
    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'is_free' => 'boolean',
            'manage_registrations' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    public function users()
{
    return $this->belongsToMany(User::class, 'registrations')
                ->withPivot('status')
                ->withTimestamps();
}

    // Relação: O Evento pertence a um Promotor (User)
    public function promoter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'promoter_id');
    }

    // Um evento possui vários palestrantes
    public function speakers()
    {
        return $this->belongsToMany(Speaker::class);
    }

    // Relação: O Evento pertence a uma Categoria
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Relação: O Evento tem várias Inscrições
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    // Relação: O Evento tem várias Avaliações
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
