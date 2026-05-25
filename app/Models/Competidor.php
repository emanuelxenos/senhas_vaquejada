<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Competidor extends Model
{

    protected $table = 'competidores';
    protected $fillable = [
        'user_id',
        'nome',
        'cpf',
        'cidade',
        'representacao',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Inscrições onde este competidor é o vaqueiro
     */
    public function inscricoesComoVaqueiro(): HasMany
    {
        return $this->hasMany(Inscricao::class, 'vaqueiro_id');
    }

    /**
     * Inscrições onde este competidor é o bate-esteira
     */
    public function inscricoesComoBateEsteira(): HasMany
    {
        return $this->hasMany(Inscricao::class, 'bate_esteira_id');
    }

    /**
     * Todas as inscrições deste competidor (como vaqueiro ou bate-esteira)
     */
    public function inscricoes()
    {
        return Inscricao::where('vaqueiro_id', $this->id)
            ->orWhere('bate_esteira_id', $this->id);
    }
}
