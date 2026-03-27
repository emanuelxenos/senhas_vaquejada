<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Senha extends Model
{
    protected $fillable = [
        'inscricao_id',
        'numero_senha',
        'status',
        'motivo_cancelamento',
        'cancelado_por',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * A inscrição à qual esta senha pertence
     */
    public function inscricao(): BelongsTo
    {
        return $this->belongsTo(Inscricao::class);
    }

    /**
     * O vaqueiro desta senha (através da inscrição)
     */
    public function vaqueiro()
    {
        return $this->inscricao->vaqueiro();
    }

    /**
     * O bate-esteira desta senha (através da inscrição)
     */
    public function bateEsteira()
    {
        return $this->inscricao->bateEsteira();
    }
}
