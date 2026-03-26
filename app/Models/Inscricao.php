<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inscricao extends Model
{
    protected $table = 'inscricoes';
    protected $fillable = [
        'vaqueiro_id',
        'bate_esteira_id',
        'forma_pagamento',
        'valor_total',
        'status_pagamento',
    ];

    protected $casts = [
        'valor_total' => 'decimal:2',
        'status_pagamento' => 'string',
    ];

    /**
     * O competidor que é o vaqueiro nesta inscrição
     */
    public function vaqueiro(): BelongsTo
    {
        return $this->belongsTo(Competidor::class, 'vaqueiro_id');
    }

    /**
     * O competidor que é o bate-esteira nesta inscrição
     */
    public function bateEsteira(): BelongsTo
    {
        return $this->belongsTo(Competidor::class, 'bate_esteira_id');
    }

    /**
     * As senhas associadas a esta inscrição
     */
    public function senhas(): HasMany
    {
        return $this->hasMany(Senha::class);
    }
}
