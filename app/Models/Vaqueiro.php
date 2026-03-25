<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaqueiro extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'representacao',
        'esteira',
        'pagamento',
        'quantidade',
        'data',
        'disponivel',
    ];

    public $timestamps = false;

    public function senhas()
    {
        return $this->hasMany(Senha::class);
    }
}
