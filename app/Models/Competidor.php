<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competidor extends Model
{

    protected $table = 'competidores';
    protected $fillable = [
        'nome',
        'cpf',
        'cidade',
        'representacao',
    ];

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
