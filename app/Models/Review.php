<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'rating',
        'comment',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    // Relação: A Avaliação foi feita por um Utilizador
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relação: A Avaliação pertence a um Evento específico
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
