<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'preco_senha',
        'limite_senhas_por_vaqueiro',
        'minimo_bois_sucesso',
    ];

    protected $casts = [
        'preco_senha' => 'decimal:2',
        'limite_senhas_por_vaqueiro' => 'integer',
        'minimo_bois_sucesso' => 'integer',
    ];

    public function inscricoes(): HasMany
    {
        return $this->hasMany(Inscricao::class);
    }
}
