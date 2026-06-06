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
        'is_boi_tv',
        'motivo_cancelamento',
        'cancelado_por',
    ];

    protected $casts = [
        'status' => 'string',
        'is_boi_tv' => 'boolean',
    ];

    protected static function booted()
    {
        static::created(function ($senha) {
            $totalBoisConfigurado = $senha->inscricao && $senha->inscricao->categoria
                ? (int) $senha->inscricao->categoria->quantidade_bois
                : 3;
            for ($i = 1; $i <= $totalBoisConfigurado; $i++) {
                $senha->corridas()->create([
                    'numero_corrida' => $i,
                    'resultado' => 'pendente'
                ]);
            }
        });
    }

    /**
     * A inscrição à qual esta senha pertence
     */
    public function inscricao(): BelongsTo
    {
        return $this->belongsTo(Inscricao::class);
    }

    /**
     * As corridas desta senha
     */
    public function corridas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Corrida::class);
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

    /**
     * Atualiza o status da senha com base nas corridas
     */
    public function atualizarStatusAutomatico(): void
    {
        if ($this->status === 'cancelado') {
            return;
        }

        $corridas = $this->corridas()->get();
        $categoria = $this->inscricao ? $this->inscricao->categoria : null;
        
        $totalBoisConfigurado = $categoria ? (int) $categoria->quantidade_bois : 3;
        $minimoBoisSucesso = $categoria ? (int) $categoria->minimo_bois_sucesso : 2;

        $boiBatidoCount = $corridas->where('resultado', 'boi_batido')->count();
        $zeroCount = $corridas->where('resultado', 'zero')->count();
        $pendenteCount = $corridas->where('resultado', 'pendente')->count();

        // Regra: se atingir a quantidade mínima para sucesso, vira boi_batido automaticamente
        if ($boiBatidoCount >= $minimoBoisSucesso) {
            $this->status = 'boi_batido';
        }
        // Se todas as corridas foram computadas e não atingiu os bois batidos mínimos
        elseif ($pendenteCount === 0 && ($boiBatidoCount + $zeroCount) >= $totalBoisConfigurado) {
            $this->status = 'correu';
        }
        // Caso contrário, continua ou volta para pendente
        else {
            $this->status = 'pendente';
        }

        $this->save();
    }
}
