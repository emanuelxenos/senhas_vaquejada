<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Corrida extends Model
{
    use HasFactory;

    protected $fillable = [
        'senha_id',
        'numero_corrida',
        'resultado',
    ];

    public function senha(): BelongsTo
    {
        return $this->belongsTo(Senha::class);
    }
}
