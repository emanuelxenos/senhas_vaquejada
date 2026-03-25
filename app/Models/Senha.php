<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Senha extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'vaqueiro_id',
    ];

    public $timestamps = false;

    public function vaqueiro()
    {
        return $this->belongsTo(Vaqueiro::class);
    }
}
