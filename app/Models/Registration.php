<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'event_id',
        'status',
        'attended',
    ];

    protected function casts(): array
    {
        return [
            'attended' => 'boolean',
        ];
    }

    // Relação: A Inscrição pertence a um Utilizador
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relação: A Inscrição pertence a um Evento
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
